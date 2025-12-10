<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportLogisticaController extends Controller
{
    /**
     * Vista principal del reporte
     */
    public function index(Request $request): View
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonths(1)->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $estado = $request->get('estado', 'TODOS');
        $transportistaId = $request->get('transportista_id', 'TODOS');

        $data = $this->getReportData($fechaInicio, $fechaFin, $estado, $transportistaId);
        $transportistas = DB::select("SELECT transportista_id, nombre FROM cat.transportista ORDER BY nombre");
        $estados = DB::select("SELECT DISTINCT estado FROM logistica.envio ORDER BY estado");
        $totales = $this->getTotales($fechaInicio, $fechaFin, $estado, $transportistaId);
        $enviosPorEstado = $this->getEnviosPorEstado($fechaInicio, $fechaFin);
        $evolucionDiaria = $this->getEvolucionDiaria($fechaInicio, $fechaFin);

        return view('reportes.logistica.index', [
            'data' => $data,
            'transportistas' => $transportistas,
            'estados' => $estados,
            'totales' => $totales,
            'envios_por_estado' => $enviosPorEstado,
            'evolucion_diaria' => $evolucionDiaria,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => $estado,
                'transportista_id' => $transportistaId,
            ],
        ]);
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonths(1)->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $estado = $request->get('estado', 'TODOS');
        $transportistaId = $request->get('transportista_id', 'TODOS');

        $data = $this->getReportData($fechaInicio, $fechaFin, $estado, $transportistaId);
        $totales = $this->getTotales($fechaInicio, $fechaFin, $estado, $transportistaId);
        $enviosPorEstado = $this->getEnviosPorEstado($fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('reportes.logistica.pdf', [
            'data' => $data,
            'totales' => $totales,
            'envios_por_estado' => $enviosPorEstado,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => $estado,
                'transportista_id' => $transportistaId,
            ],
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('reporte-logistica-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar a Excel con estilos
     */
    public function exportCsv(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonths(1)->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $estado = $request->get('estado', 'TODOS');
        $transportistaId = $request->get('transportista_id', 'TODOS');

        $data = $this->getReportData($fechaInicio, $fechaFin, $estado, $transportistaId);
        $totales = $this->getTotales($fechaInicio, $fechaFin, $estado, $transportistaId);

        $excelData = [];
        foreach ($data as $row) {
            $tasa = $row->tasa_cumplimiento ?? 0;
            $tasaBadge = $tasa >= 80 ? 'success' : ($tasa >= 50 ? 'warning' : 'danger');

            $excelData[] = [
                $row->transportista,
                $row->placa ?? '-',
                ['value' => $row->tipo_vehiculo ?? '-', 'class' => 'text-center'],
                ['value' => number_format($row->capacidad_t ?? 0, 1), 'class' => 'text-right'],
                ['value' => $row->total_envios, 'class' => 'text-center'],
                ['value' => $row->entregados ?? 0, 'badge' => 'success'],
                ['value' => $row->en_ruta ?? 0, 'badge' => 'warning'],
                ['value' => $row->pendientes ?? 0, 'badge' => 'secondary'],
                ['value' => number_format($row->toneladas_totales ?? 0, 2), 'class' => 'text-right'],
                ['value' => number_format($row->horas_promedio_entrega ?? 0, 1) . 'h', 'class' => 'text-center'],
                ['value' => '±' . number_format($row->variacion_temp_promedio ?? 0, 1) . '°C', 'class' => 'text-center'],
                ['value' => number_format($tasa, 1) . '%', 'badge' => $tasaBadge],
            ];
        }

        $service = new \App\Services\ExcelExportService();

        return $service
            ->setTitle('Reporte de Análisis Logístico')
            ->setPrimaryColor('#17a2b8')
            ->setSummary([
                'Período' => $fechaInicio . ' a ' . $fechaFin,
                'Estado Filtrado' => $estado,
                'Total Envíos' => number_format($totales->total_envios ?? 0),
                'Entregados' => number_format($totales->entregados ?? 0),
                'En Ruta' => number_format($totales->en_ruta ?? 0),
                'Toneladas Totales' => number_format($totales->toneladas_totales ?? 0, 2) . ' t',
                'Tiempo Promedio' => number_format($totales->horas_promedio ?? 0, 1) . ' horas',
                'Tasa de Entrega' => number_format($totales->tasa_entrega ?? 0, 1) . '%',
            ])
            ->setHeaders([
                'Transportista', 'Placa', 'Tipo', 'Capacidad', 'Envíos',
                'Entregados', 'En Ruta', 'Pendientes', 'Toneladas',
                'Tiempo Prom.', 'Var. Temp.', 'Cumplimiento'
            ])
            ->setData($excelData)
            ->download('logistica-' . now()->format('Y-m-d') . '.xls');
    }

    /**
     * Obtener datos del reporte por transportista
     */
    private function getReportData(string $fechaInicio, string $fechaFin, string $estado, string $transportistaId): array
    {
        $estadoCondition = $estado !== 'TODOS' ? "AND e.estado = :estado" : "";
        $transportistaCondition = $transportistaId !== 'TODOS' ? "AND t.transportista_id = :transportista_id" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($estado !== 'TODOS') {
            $params['estado'] = $estado;
        }
        if ($transportistaId !== 'TODOS') {
            $params['transportista_id'] = $transportistaId;
        }

        // Vehículo se relaciona con transportista via vehiculo_asignado_id
        // O a través del envío via envio.vehiculo_id
        $sql = "
            SELECT 
                t.transportista_id, 
                t.nombre as transportista,
                v.placa, 
                v.tipo as tipo_vehiculo, 
                v.capacidad_t,
                count(DISTINCT e.envio_id) as total_envios,
                sum(CASE WHEN e.estado = 'ENTREGADO' THEN 1 ELSE 0 END) as entregados,
                sum(CASE WHEN e.estado = 'EN_RUTA' THEN 1 ELSE 0 END) as en_ruta,
                sum(CASE WHEN e.estado = 'PENDIENTE' THEN 1 ELSE 0 END) as pendientes,
                coalesce(sum(ed.cantidad_t), 0) as toneladas_totales,
                coalesce(avg(
                    CASE WHEN e.fecha_llegada IS NOT NULL 
                    THEN EXTRACT(EPOCH FROM (e.fecha_llegada - e.fecha_salida))/3600 
                    END
                ), 0) as horas_promedio_entrega,
                coalesce(avg(e.temp_max_c - e.temp_min_c), 0) as variacion_temp_promedio,
                CASE 
                    WHEN count(DISTINCT e.envio_id) > 0 
                    THEN (sum(CASE WHEN e.estado = 'ENTREGADO' THEN 1 ELSE 0 END)::float / count(DISTINCT e.envio_id)) * 100 
                    ELSE 0 
                END as tasa_cumplimiento
            FROM cat.transportista t
            LEFT JOIN cat.vehiculo v ON v.vehiculo_id = t.vehiculo_asignado_id
            LEFT JOIN logistica.envio e ON e.transportista_id = t.transportista_id
                AND e.fecha_salida BETWEEN :fecha_inicio AND :fecha_fin
            LEFT JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            WHERE 1=1 $estadoCondition $transportistaCondition
            GROUP BY t.transportista_id, t.nombre, v.placa, v.tipo, v.capacidad_t
            ORDER BY tasa_cumplimiento DESC, total_envios DESC
        ";

        return DB::select($sql, $params);
    }

    /**
     * Obtener totales generales
     */
    private function getTotales(string $fechaInicio, string $fechaFin, string $estado, string $transportistaId): object
    {
        $estadoCondition = $estado !== 'TODOS' ? "AND e.estado = :estado" : "";
        $transportistaCondition = $transportistaId !== 'TODOS' ? "AND e.transportista_id = :transportista_id" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($estado !== 'TODOS') {
            $params['estado'] = $estado;
        }
        if ($transportistaId !== 'TODOS') {
            $params['transportista_id'] = $transportistaId;
        }

        $sql = "
            SELECT 
                count(DISTINCT e.envio_id) as total_envios,
                sum(CASE WHEN e.estado = 'ENTREGADO' THEN 1 ELSE 0 END) as entregados,
                sum(CASE WHEN e.estado = 'EN_RUTA' THEN 1 ELSE 0 END) as en_ruta,
                coalesce(sum(ed.cantidad_t), 0) as toneladas_totales,
                coalesce(avg(
                    CASE WHEN e.fecha_llegada IS NOT NULL 
                    THEN EXTRACT(EPOCH FROM (e.fecha_llegada - e.fecha_salida))/3600 
                    END
                ), 0) as horas_promedio,
                CASE 
                    WHEN count(DISTINCT e.envio_id) > 0 
                    THEN (sum(CASE WHEN e.estado = 'ENTREGADO' THEN 1 ELSE 0 END)::float / count(DISTINCT e.envio_id)) * 100 
                    ELSE 0 
                END as tasa_entrega
            FROM logistica.envio e
            LEFT JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            WHERE e.fecha_salida BETWEEN :fecha_inicio AND :fecha_fin
            $estadoCondition $transportistaCondition
        ";

        return DB::selectOne($sql, $params) ?? (object)[
            'total_envios' => 0,
            'entregados' => 0,
            'en_ruta' => 0,
            'toneladas_totales' => 0,
            'horas_promedio' => 0,
            'tasa_entrega' => 0,
        ];
    }

    /**
     * Envíos por estado (para gráfico de dona)
     */
    private function getEnviosPorEstado(string $fechaInicio, string $fechaFin): array
    {
        return DB::select("
            SELECT 
                estado,
                count(*) as cantidad,
                coalesce(sum(ed.cantidad_t), 0) as toneladas
            FROM logistica.envio e
            LEFT JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            WHERE e.fecha_salida BETWEEN :fecha_inicio AND :fecha_fin
            GROUP BY estado
            ORDER BY cantidad DESC
        ", [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);
    }

    /**
     * Evolución diaria de envíos
     */
    private function getEvolucionDiaria(string $fechaInicio, string $fechaFin): array
    {
        return DB::select("
            SELECT 
                date(fecha_salida) as fecha,
                to_char(fecha_salida, 'DD Mon') as fecha_label,
                count(*) as total_envios,
                sum(CASE WHEN estado = 'ENTREGADO' THEN 1 ELSE 0 END) as entregados
            FROM logistica.envio
            WHERE fecha_salida BETWEEN :fecha_inicio AND :fecha_fin
            GROUP BY date(fecha_salida), to_char(fecha_salida, 'DD Mon')
            ORDER BY fecha ASC
        ", [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);
    }
}
