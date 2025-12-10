<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportInventarioController extends Controller
{
    /**
     * Vista principal del reporte
     */
    public function index(Request $request): View
    {
        $almacenId = $request->get('almacen_id', 'TODOS');
        $nivelStock = $request->get('nivel_stock', 'TODOS');
        $sku = $request->get('sku', '');

        $data = $this->getReportData($almacenId, $nivelStock, $sku);
        $almacenes = DB::select("SELECT almacen_id, nombre, codigo_almacen FROM cat.almacen ORDER BY nombre");
        $totales = $this->getTotales($almacenId);
        $stockPorAlmacen = $this->getStockPorAlmacen();
        $movimientosRecientes = $this->getMovimientosRecientes($almacenId);

        return view('reportes.inventario.index', [
            'data' => $data,
            'almacenes' => $almacenes,
            'totales' => $totales,
            'stock_por_almacen' => $stockPorAlmacen,
            'movimientos_recientes' => $movimientosRecientes,
            'filtros' => [
                'almacen_id' => $almacenId,
                'nivel_stock' => $nivelStock,
                'sku' => $sku,
            ],
        ]);
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(Request $request)
    {
        $almacenId = $request->get('almacen_id', 'TODOS');
        $nivelStock = $request->get('nivel_stock', 'TODOS');
        $sku = $request->get('sku', '');

        $data = $this->getReportData($almacenId, $nivelStock, $sku);
        $totales = $this->getTotales($almacenId);
        $stockPorAlmacen = $this->getStockPorAlmacen();

        $pdf = Pdf::loadView('reportes.inventario.pdf', [
            'data' => $data,
            'totales' => $totales,
            'stock_por_almacen' => $stockPorAlmacen,
            'filtros' => [
                'almacen_id' => $almacenId,
                'nivel_stock' => $nivelStock,
                'sku' => $sku,
            ],
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('reporte-inventario-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar a Excel con estilos
     */
    public function exportCsv(Request $request)
    {
        $almacenId = $request->get('almacen_id', 'TODOS');
        $nivelStock = $request->get('nivel_stock', 'TODOS');
        $sku = $request->get('sku', '');

        $data = $this->getReportData($almacenId, $nivelStock, $sku);
        $totales = $this->getTotales($almacenId);

        $excelData = [];
        foreach ($data as $row) {
            $estado = $row->estado_stock ?? 'NORMAL';
            $estadoBadge = match($estado) {
                'CRÍTICO' => 'danger',
                'BAJO' => 'warning',
                'ALTO' => 'info',
                default => 'success'
            };

            $excelData[] = [
                $row->almacen,
                $row->codigo_almacen,
                $row->sku,
                ['value' => number_format($row->cantidad_actual ?? 0, 2), 'class' => 'text-right'],
                ['value' => number_format($row->entradas_30d ?? 0, 2), 'class' => 'text-right positive'],
                ['value' => number_format($row->salidas_30d ?? 0, 2), 'class' => 'text-right negative'],
                ['value' => $row->movimientos_30d ?? 0, 'class' => 'text-center'],
                ['value' => $row->dias_inventario ?? '∞', 'class' => 'text-center'],
                ['value' => $estado, 'badge' => $estadoBadge],
            ];
        }

        $service = new \App\Services\ExcelExportService();

        return $service
            ->setTitle('Reporte de Estado de Inventario')
            ->setPrimaryColor('#ffc107')
            ->setSummary([
                'Almacén' => $almacenId === 'TODOS' ? 'Todos' : $almacenId,
                'Nivel de Stock' => $nivelStock,
                'SKU Buscado' => $sku ?: 'Todos',
                'Total Almacenes' => number_format($totales->total_almacenes ?? 0),
                'Total SKUs' => number_format($totales->total_skus ?? 0),
                'Stock Total' => number_format($totales->stock_total ?? 0, 2) . ' t',
                'Items Críticos' => number_format($totales->items_criticos ?? 0),
                'Items Bajos' => number_format($totales->items_bajos ?? 0),
            ])
            ->setHeaders([
                'Almacén', 'Código', 'SKU', 'Stock Actual',
                'Entradas 30d', 'Salidas 30d', 'Movimientos', 'Días Inv.', 'Estado'
            ])
            ->setData($excelData)
            ->download('inventario-' . now()->format('Y-m-d') . '.xls');
    }

    /**
     * Obtener datos del reporte
     */
    private function getReportData(string $almacenId, string $nivelStock, string $sku): array
    {
        $almacenCondition = $almacenId !== 'TODOS' ? "AND a.almacen_id = :almacen_id" : "";
        $skuCondition = !empty($sku) ? "AND i.sku ILIKE :sku" : "";
        
        $params = [];
        
        if ($almacenId !== 'TODOS') {
            $params['almacen_id'] = $almacenId;
        }
        if (!empty($sku)) {
            $params['sku'] = '%' . $sku . '%';
        }

        // Consulta simplificada basada en el esquema real
        // inventario: almacen_id, lote_salida_id, sku, cantidad_t
        // movimiento: almacen_id, lote_salida_id, tipo, cantidad_t, fecha_mov
        $sql = "
            WITH stock_actual AS (
                SELECT 
                    i.almacen_id, 
                    i.sku,
                    i.lote_salida_id,
                    sum(i.cantidad_t) as cantidad_actual
                FROM almacen.inventario i
                GROUP BY i.almacen_id, i.sku, i.lote_salida_id
            ),
            movimientos_recientes AS (
                SELECT 
                    m.almacen_id, 
                    m.lote_salida_id,
                    sum(CASE WHEN m.tipo = 'ENTRADA' THEN m.cantidad_t ELSE 0 END) as entradas,
                    sum(CASE WHEN m.tipo = 'SALIDA' THEN m.cantidad_t ELSE 0 END) as salidas,
                    count(*) as num_movimientos
                FROM almacen.movimiento m
                WHERE m.fecha_mov >= current_date - interval '30 days'
                GROUP BY m.almacen_id, m.lote_salida_id
            )
            SELECT 
                a.nombre as almacen, 
                a.codigo_almacen,
                sa.sku,
                sum(sa.cantidad_actual) as cantidad_actual,
                coalesce(sum(mr.entradas), 0) as entradas_30d,
                coalesce(sum(mr.salidas), 0) as salidas_30d,
                coalesce(sum(mr.num_movimientos), 0) as movimientos_30d,
                CASE 
                    WHEN coalesce(sum(mr.salidas), 0) > 0 
                    THEN floor(sum(sa.cantidad_actual) / (sum(mr.salidas) / 30))
                    ELSE 999 
                END as dias_inventario,
                CASE 
                    WHEN sum(sa.cantidad_actual) < 5 THEN 'CRÍTICO'
                    WHEN sum(sa.cantidad_actual) < 20 THEN 'BAJO'
                    WHEN sum(sa.cantidad_actual) < 100 THEN 'NORMAL'
                    ELSE 'ALTO'
                END as estado_stock
            FROM stock_actual sa
            JOIN cat.almacen a ON a.almacen_id = sa.almacen_id
            LEFT JOIN movimientos_recientes mr ON mr.almacen_id = sa.almacen_id AND mr.lote_salida_id = sa.lote_salida_id
            WHERE 1=1 $almacenCondition $skuCondition
            GROUP BY a.almacen_id, a.nombre, a.codigo_almacen, sa.sku
            ORDER BY 
                CASE 
                    WHEN sum(sa.cantidad_actual) < 5 THEN 1
                    WHEN sum(sa.cantidad_actual) < 20 THEN 2
                    ELSE 3
                END,
                a.nombre, 
                sa.sku
        ";

        $results = DB::select($sql, $params);

        // Filtrar por nivel de stock si es necesario
        if ($nivelStock !== 'TODOS') {
            $results = array_filter($results, function($item) use ($nivelStock) {
                return $item->estado_stock === $nivelStock;
            });
            $results = array_values($results);
        }

        return $results;
    }

    /**
     * Obtener totales generales
     */
    private function getTotales(string $almacenId): object
    {
        $almacenCondition = $almacenId !== 'TODOS' ? "WHERE a.almacen_id = :almacen_id" : "";
        
        $params = [];
        if ($almacenId !== 'TODOS') {
            $params['almacen_id'] = $almacenId;
        }

        $sql = "
            SELECT 
                count(DISTINCT a.almacen_id) as total_almacenes,
                count(DISTINCT i.sku) as total_skus,
                coalesce(sum(i.cantidad_t), 0) as stock_total,
                sum(CASE WHEN i.cantidad_t < 5 THEN 1 ELSE 0 END) as items_criticos,
                sum(CASE WHEN i.cantidad_t >= 5 AND i.cantidad_t < 20 THEN 1 ELSE 0 END) as items_bajos
            FROM cat.almacen a
            LEFT JOIN almacen.inventario i ON i.almacen_id = a.almacen_id
            $almacenCondition
        ";

        return DB::selectOne($sql, $params) ?? (object)[
            'total_almacenes' => 0,
            'total_skus' => 0,
            'stock_total' => 0,
            'items_criticos' => 0,
            'items_bajos' => 0,
        ];
    }

    /**
     * Stock por almacén (para gráfico)
     */
    private function getStockPorAlmacen(): array
    {
        return DB::select("
            SELECT 
                a.nombre as almacen,
                a.codigo_almacen,
                coalesce(sum(i.cantidad_t), 0) as stock_total,
                count(DISTINCT i.sku) as num_skus,
                a.capacidad_total_t
            FROM cat.almacen a
            LEFT JOIN almacen.inventario i ON i.almacen_id = a.almacen_id
            GROUP BY a.almacen_id, a.nombre, a.codigo_almacen, a.capacidad_total_t
            ORDER BY stock_total DESC
        ");
    }

    /**
     * Movimientos recientes
     */
    private function getMovimientosRecientes(string $almacenId): array
    {
        $almacenCondition = $almacenId !== 'TODOS' ? "AND m.almacen_id = :almacen_id" : "";
        
        $params = [];
        if ($almacenId !== 'TODOS') {
            $params['almacen_id'] = $almacenId;
        }

        $sql = "
            SELECT 
                m.fecha_mov as fecha,
                a.nombre as almacen,
                i.sku,
                m.tipo,
                m.cantidad_t,
                m.referencia
            FROM almacen.movimiento m
            JOIN cat.almacen a ON a.almacen_id = m.almacen_id
            LEFT JOIN almacen.inventario i ON i.almacen_id = m.almacen_id AND i.lote_salida_id = m.lote_salida_id
            WHERE m.fecha_mov >= current_date - interval '7 days'
            $almacenCondition
            ORDER BY m.fecha_mov DESC
            LIMIT 10
        ";

        return DB::select($sql, $params);
    }
}
