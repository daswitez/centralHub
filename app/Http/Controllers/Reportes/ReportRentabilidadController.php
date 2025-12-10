<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportRentabilidadController extends Controller
{
    /**
     * Vista principal del reporte con filtros y gráficos
     */
    public function index(Request $request): View
    {
        // Obtener valores de filtros
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $tipoCliente = $request->get('tipo_cliente', 'TODOS');
        $topN = (int) $request->get('top_n', 10);

        // Consulta principal con filtros
        $data = $this->getReportData($fechaInicio, $fechaFin, $tipoCliente, $topN);

        // Tipos de cliente para el select
        $tiposCliente = DB::select("SELECT DISTINCT tipo FROM cat.cliente ORDER BY tipo");

        // Totales generales
        $totales = $this->getTotales($fechaInicio, $fechaFin, $tipoCliente);

        // Datos para gráfico de ventas por tipo de cliente
        $ventasPorTipo = $this->getVentasPorTipo($fechaInicio, $fechaFin);

        return view('reportes.rentabilidad-cliente.index', [
            'data' => $data,
            'tipos_cliente' => $tiposCliente,
            'totales' => $totales,
            'ventas_por_tipo' => $ventasPorTipo,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'tipo_cliente' => $tipoCliente,
                'top_n' => $topN,
            ],
        ]);
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $tipoCliente = $request->get('tipo_cliente', 'TODOS');
        $topN = (int) $request->get('top_n', 10);

        $data = $this->getReportData($fechaInicio, $fechaFin, $tipoCliente, $topN);
        $totales = $this->getTotales($fechaInicio, $fechaFin, $tipoCliente);
        $ventasPorTipo = $this->getVentasPorTipo($fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('reportes.rentabilidad-cliente.pdf', [
            'data' => $data,
            'totales' => $totales,
            'ventas_por_tipo' => $ventasPorTipo,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'tipo_cliente' => $tipoCliente,
                'top_n' => $topN,
            ],
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('reporte-rentabilidad-cliente-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar a Excel con estilos
     */
    public function exportCsv(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $tipoCliente = $request->get('tipo_cliente', 'TODOS');
        $topN = (int) $request->get('top_n', 100);

        $data = $this->getReportData($fechaInicio, $fechaFin, $tipoCliente, $topN);
        $totales = $this->getTotales($fechaInicio, $fechaFin, $tipoCliente);

        // Preparar datos para Excel con formato
        $excelData = [];
        $ranking = 1;
        foreach ($data as $row) {
            $diff = $row->diferencia_precio ?? 0;
            $diffFormatted = ($diff >= 0 ? '+' : '') . number_format($diff, 2);
            
            $excelData[] = [
                $ranking++,
                $row->nombre,
                ['value' => $row->tipo, 'badge' => $row->tipo === 'MAYORISTA' ? 'primary' : ($row->tipo === 'RETAIL' ? 'success' : 'info')],
                $row->departamento ?? '-',
                $row->municipio ?? '-',
                ['value' => $row->num_pedidos, 'class' => 'text-center'],
                ['value' => number_format($row->total_toneladas, 2), 'class' => 'text-right'],
                ['value' => '$ ' . number_format($row->total_ingresos, 2), 'class' => 'text-right font-bold'],
                ['value' => '$ ' . number_format($row->precio_promedio, 2), 'class' => 'text-right'],
                ['value' => $diffFormatted, 'class' => 'text-center ' . ($diff >= 0 ? 'positive' : 'negative')],
            ];
        }

        $service = new \App\Services\ExcelExportService();

        return $service
            ->setTitle('Reporte de Rentabilidad por Cliente')
            ->setPrimaryColor('#007bff')
            ->setSummary([
                'Período' => $fechaInicio . ' a ' . $fechaFin,
                'Tipo de Cliente' => $tipoCliente,
                'Total Clientes' => number_format($totales->total_clientes ?? 0),
                'Total Pedidos' => number_format($totales->total_pedidos ?? 0),
                'Total Toneladas' => number_format($totales->total_toneladas ?? 0, 2) . ' t',
                'Total Ingresos' => '$ ' . number_format($totales->total_ingresos ?? 0, 2),
                'Precio Promedio' => '$ ' . number_format($totales->precio_promedio ?? 0, 2) . ' /t',
            ])
            ->setHeaders([
                '#', 'Cliente', 'Tipo', 'Departamento', 'Municipio',
                'Pedidos', 'Toneladas', 'Total USD', 'Precio Prom.', 'vs Mercado'
            ])
            ->setData($excelData)
            ->download('rentabilidad-cliente-' . now()->format('Y-m-d') . '.xls');
    }

    /**
     * Obtener datos del reporte
     */
    private function getReportData(string $fechaInicio, string $fechaFin, string $tipoCliente, int $topN): array
    {
        $tipoCondition = $tipoCliente !== 'TODOS' ? "AND c.tipo = :tipo" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($tipoCliente !== 'TODOS') {
            $params['tipo'] = $tipoCliente;
        }

        $sql = "
            WITH promedio_general AS (
                SELECT coalesce(avg(precio_unit_usd), 0) as promedio
                FROM comercial.pedidodetalle pd
                JOIN comercial.pedido p ON p.pedido_id = pd.pedido_id
                WHERE p.fecha_pedido BETWEEN :fecha_inicio AND :fecha_fin
            )
            SELECT 
                c.cliente_id, 
                c.nombre, 
                c.tipo,
                m.nombre as municipio, 
                d.nombre as departamento,
                count(DISTINCT p.pedido_id) as num_pedidos,
                coalesce(sum(pd.cantidad_t), 0) as total_toneladas,
                coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total_ingresos,
                coalesce(avg(pd.precio_unit_usd), 0) as precio_promedio,
                coalesce(avg(pd.precio_unit_usd), 0) - (SELECT promedio FROM promedio_general) as diferencia_precio
            FROM cat.cliente c
            JOIN comercial.pedido p ON p.cliente_id = c.cliente_id
            JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            LEFT JOIN cat.municipio m ON m.municipio_id = c.municipio_id
            LEFT JOIN cat.departamento d ON d.departamento_id = m.departamento_id
            WHERE p.fecha_pedido BETWEEN :fecha_inicio AND :fecha_fin
            $tipoCondition
            GROUP BY c.cliente_id, c.nombre, c.tipo, m.nombre, d.nombre
            ORDER BY total_ingresos DESC
            LIMIT $topN
        ";

        return DB::select($sql, $params);
    }

    /**
     * Obtener totales generales
     */
    private function getTotales(string $fechaInicio, string $fechaFin, string $tipoCliente): object
    {
        $tipoCondition = $tipoCliente !== 'TODOS' ? "AND c.tipo = :tipo" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($tipoCliente !== 'TODOS') {
            $params['tipo'] = $tipoCliente;
        }

        $sql = "
            SELECT 
                count(DISTINCT c.cliente_id) as total_clientes,
                count(DISTINCT p.pedido_id) as total_pedidos,
                coalesce(sum(pd.cantidad_t), 0) as total_toneladas,
                coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total_ingresos,
                coalesce(avg(pd.precio_unit_usd), 0) as precio_promedio
            FROM cat.cliente c
            JOIN comercial.pedido p ON p.cliente_id = c.cliente_id
            JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            WHERE p.fecha_pedido BETWEEN :fecha_inicio AND :fecha_fin
            $tipoCondition
        ";

        return DB::selectOne($sql, $params) ?? (object)[
            'total_clientes' => 0,
            'total_pedidos' => 0,
            'total_toneladas' => 0,
            'total_ingresos' => 0,
            'precio_promedio' => 0,
        ];
    }

    /**
     * Obtener ventas agrupadas por tipo de cliente (para gráfico)
     */
    private function getVentasPorTipo(string $fechaInicio, string $fechaFin): array
    {
        return DB::select("
            SELECT 
                c.tipo,
                count(DISTINCT p.pedido_id) as num_pedidos,
                coalesce(sum(pd.cantidad_t), 0) as total_toneladas,
                coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total_ingresos
            FROM cat.cliente c
            JOIN comercial.pedido p ON p.cliente_id = c.cliente_id
            JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            WHERE p.fecha_pedido BETWEEN :fecha_inicio AND :fecha_fin
            GROUP BY c.tipo
            ORDER BY total_ingresos DESC
        ", [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);
    }
}
