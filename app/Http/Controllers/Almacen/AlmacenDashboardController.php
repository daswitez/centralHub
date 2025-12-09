<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlmacenDashboardController extends Controller
{
    /**
     * Dashboard de inventario de almacenes
     */
    public function index(): View
    {
        // KPIs generales
        $kpiTotalStock = DB::selectOne("
            SELECT COALESCE(SUM(cantidad_t), 0) as total
            FROM almacen.inventario
        ");

        $kpiTotalAlmacenes = DB::selectOne("
            SELECT COUNT(DISTINCT almacen_id) as total
            FROM almacen.inventario
            WHERE cantidad_t > 0
        ");

        $kpiTotalSKUs = DB::selectOne("
            SELECT COUNT(DISTINCT sku) as total
            FROM almacen.inventario
            WHERE cantidad_t > 0
        ");

        $kpiRecepcionesHoy = DB::selectOne("
            SELECT COUNT(*) as total
            FROM almacen.recepcion
            WHERE DATE(fecha_recepcion) = CURRENT_DATE
        ");

        // Stock por almacén
        $stockPorAlmacen = DB::select("
            SELECT a.almacen_id, a.codigo_almacen, a.nombre as almacen_nombre,
                   COALESCE(SUM(i.cantidad_t), 0) as stock_total,
                   COUNT(DISTINCT i.sku) as total_skus,
                   COUNT(DISTINCT i.lote_salida_id) as total_lotes
            FROM cat.almacen a
            LEFT JOIN almacen.inventario i ON i.almacen_id = a.almacen_id
            GROUP BY a.almacen_id, a.codigo_almacen, a.nombre
            ORDER BY stock_total DESC
        ");

        // Stock detallado por SKU (top 20)
        $stockDetalle = DB::select("
            SELECT a.codigo_almacen, a.nombre as almacen_nombre,
                   i.sku, SUM(i.cantidad_t) as cantidad_total
            FROM almacen.inventario i
            JOIN cat.almacen a ON a.almacen_id = i.almacen_id
            GROUP BY a.codigo_almacen, a.nombre, i.sku
            HAVING SUM(i.cantidad_t) > 0
            ORDER BY cantidad_total DESC
            LIMIT 20
        ");

        // Últimas recepciones
        $ultimasRecepciones = DB::select("
            SELECT r.recepcion_id, r.fecha_recepcion, r.observacion,
                   a.nombre as almacen_nombre,
                   e.codigo_envio, e.estado as envio_estado
            FROM almacen.recepcion r
            LEFT JOIN cat.almacen a ON a.almacen_id = r.almacen_id
            LEFT JOIN logistica.envio e ON e.envio_id = r.envio_id
            ORDER BY r.fecha_recepcion DESC
            LIMIT 10
        ");

        // Últimos movimientos
        $ultimosMovimientos = DB::select("
            SELECT m.mov_id, m.tipo, m.fecha_mov, m.cantidad_t,
                   a.nombre as almacen_nombre, ls.sku
            FROM almacen.movimiento m
            LEFT JOIN cat.almacen a ON a.almacen_id = m.almacen_id
            LEFT JOIN planta.lotesalida ls ON ls.lote_salida_id = m.lote_salida_id
            ORDER BY m.fecha_mov DESC
            LIMIT 10
        ");

        return view('almacen.dashboard', [
            'kpi_total_stock' => (float)($kpiTotalStock->total ?? 0),
            'kpi_total_almacenes' => (int)($kpiTotalAlmacenes->total ?? 0),
            'kpi_total_skus' => (int)($kpiTotalSKUs->total ?? 0),
            'kpi_recepciones_hoy' => (int)($kpiRecepcionesHoy->total ?? 0),
            'stock_por_almacen' => $stockPorAlmacen,
            'stock_detalle' => $stockDetalle,
            'ultimas_recepciones' => $ultimasRecepciones,
            'ultimos_movimientos' => $ultimosMovimientos
        ]);
    }
}
