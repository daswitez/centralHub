<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Panel principal: Producción Bolivia (KPIs + tablas) */
    public function home(): View
    {
        // KPIs simples desde el esquema (si existen); valores por defecto si no hay datos
        $stock = DB::selectOne('select coalesce(sum(cantidad_t),0) as t from almacen.inventario');
        $enviosHoy = DB::selectOne('select count(*) as c from logistica.envio where date(fecha_salida)=current_date');
        $lotesIot = DB::selectOne('select count(distinct lote_campo_id) as c from campo.sensorlectura');

        return view('panel.home', [
            'kpi_stock_t' => (float) ($stock->t ?? 0),
            'kpi_envios_hoy' => (int) ($enviosHoy->c ?? 0),
            'kpi_lotes_iot' => (int) ($lotesIot->c ?? 0),
            'stock_items' => DB::select('select * from almacen.v_stock order by codigo_almacen, sku limit 10'),
            'traza_items' => DB::select('select * from planta.v_trazabilidad_lote_salida order by codigo_lote_salida limit 10'),
        ]);
    }

    /** Panel comercial/ventas */
    public function ventas(): View
    {
        // KPIs de ventas
        $pedidosHoy = DB::selectOne('SELECT count(*) as c FROM comercial.pedido WHERE date(fecha_pedido) = current_date');
        
        // Calcular ingresos desde pedidodetalle
        $ingresosMes = DB::selectOne('
            SELECT coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total 
            FROM comercial.pedidodetalle pd
            JOIN comercial.pedido p ON p.pedido_id = pd.pedido_id
            WHERE date_trunc(\'month\', p.fecha_pedido) = date_trunc(\'month\', current_date)
        ');
        
        $pedidosCerrados = DB::selectOne('SELECT count(*) as c FROM comercial.pedido WHERE estado = \'COMPLETADO\' AND date_trunc(\'month\', fecha_pedido) = date_trunc(\'month\', current_date)');
        
        // Precio promedio por tonelada (calculado desde pedidodetalle)
        $precioPromedio = DB::selectOne('
            SELECT coalesce(avg(precio_unit_usd), 0) as promedio 
            FROM comercial.pedidodetalle pd
            JOIN comercial.pedido p ON p.pedido_id = pd.pedido_id
            WHERE date_trunc(\'month\', p.fecha_pedido) = date_trunc(\'month\', current_date)
        ');
        
        // Últimos pedidos con información del cliente
        $pedidos = DB::select('
            SELECT p.pedido_id, p.codigo_pedido, c.nombre as cliente, p.estado, p.fecha_pedido,
                   count(pd.pedido_detalle_id) as num_items,
                   coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total
            FROM comercial.pedido p 
            JOIN cat.cliente c ON c.cliente_id = p.cliente_id
            LEFT JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            GROUP BY p.pedido_id, p.codigo_pedido, c.nombre, p.estado, p.fecha_pedido
            ORDER BY p.pedido_id DESC 
            LIMIT 10
        ');
        
        // Ventas por canal (últimos 7 meses) - calculado desde pedidodetalle
        $ventasPorMes = DB::select('
            SELECT 
                to_char(p.fecha_pedido, \'YYYY-MM-01\') as mes,
                sum(case when c.tipo = \'MAYORISTA\' then pd.cantidad_t * pd.precio_unit_usd else 0 end) as mayorista,
                sum(case when c.tipo = \'RETAIL\' then pd.cantidad_t * pd.precio_unit_usd else 0 end) as retail,
                sum(case when c.tipo = \'PROCESADOR\' then pd.cantidad_t * pd.precio_unit_usd else 0 end) as procesador
            FROM comercial.pedido p
            JOIN cat.cliente c ON c.cliente_id = p.cliente_id
            JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            WHERE p.fecha_pedido >= current_date - interval \'6 months\'
            GROUP BY to_char(p.fecha_pedido, \'YYYY-MM-01\')
            ORDER BY mes ASC
        ');
        
        return view('panel.ventas', [
            'kpi_pedidos_hoy' => (int)($pedidosHoy->c ?? 0),
            'kpi_ingresos_mes' => (float)($ingresosMes->total ?? 0),
            'kpi_pedidos_cerrados' => (int)($pedidosCerrados->c ?? 0),
            'kpi_precio_promedio' => (float)($precioPromedio->promedio ?? 0),
            'pedidos' => $pedidos,
            'ventas_por_mes' => $ventasPorMes
        ]);
    }

    /** Panel logística */
    public function logistica(): View
    {
        // Envíos con información completa
        $envios = DB::select('
            SELECT e.envio_id, e.codigo_envio, e.fecha_salida, e.fecha_llegada, e.estado,
                   t.nombre as transportista, r.codigo_ruta as ruta,
                   count(DISTINCT ed.envio_detalle_id) as num_lotes,
                   sum(ed.cantidad_t) as total_ton
            FROM logistica.envio e
            LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
            LEFT JOIN logistica.ruta r ON r.ruta_id = e.ruta_id
            LEFT JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            GROUP BY e.envio_id, e.codigo_envio, e.fecha_salida, e.fecha_llegada, e.estado, t.nombre, r.codigo_ruta
            ORDER BY e.envio_id DESC LIMIT 15
        ');
        
        // KPIs de logística
        $enviosEnRuta = DB::selectOne('SELECT count(*) as c FROM logistica.envio WHERE estado = \'EN_RUTA\'');
        $enviosCompletados = DB::selectOne('SELECT count(*) as c FROM logistica.envio WHERE estado = \'ENTREGADO\' AND date_trunc(\'month\', fecha_llegada) = date_trunc(\'month\', current_date)');
        $tonelajeEnTransito = DB::selectOne('
            SELECT coalesce(sum(ed.cantidad_t), 0) as total
            FROM logistica.envio e
            JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            WHERE e.estado = \'EN_RUTA\'
        ');
        
        return view('panel.logistica', [
            'envios' => $envios,
            'kpi_envios_en_ruta' => (int)($enviosEnRuta->c ?? 0),
            'kpi_envios_completados' => (int)($enviosCompletados->c ?? 0),
            'kpi_tonelaje_transito' => (float)($tonelajeEnTransito->total ?? 0)
        ]);
    }

    /** Panel planta/procesos */
    public function planta(): View
    {
        // Batches con información completa de trazabilidad
        $batches = DB::select('
            SELECT ls.codigo_lote_salida, lp.codigo_lote_planta, ls.peso_t, lp.rendimiento_pct,
                   count(distinct lpe.lote_campo_id) as num_lotes_campo,
                   p.nombre as planta,
                   lp.fecha_inicio
            FROM planta.lotesalida ls 
            JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
            LEFT JOIN planta.loteplanta_entradacampo lpe ON lpe.lote_planta_id = lp.lote_planta_id
            LEFT JOIN cat.planta p ON p.planta_id = lp.planta_id
            GROUP BY ls.lote_salida_id, ls.codigo_lote_salida, lp.codigo_lote_planta, ls.peso_t, lp.rendimiento_pct, p.nombre, lp.fecha_inicio
            ORDER BY ls.lote_salida_id DESC 
            LIMIT 10
        ');
        
        // Control de procesos recientes
        $controlProcesos = DB::select('
            SELECT cp.*, lp.codigo_lote_planta
            FROM planta.controlproceso cp
            JOIN planta.loteplanta lp ON lp.lote_planta_id = cp.lote_planta_id
            ORDER BY cp.fecha_hora DESC 
            LIMIT 5
        ');
        
        // KPIs de planta
        $rendimientoPromedio = DB::selectOne('
            SELECT coalesce(avg(rendimiento_pct), 0) as promedio
            FROM planta.loteplanta
            WHERE date_trunc(\'month\', fecha_inicio) = date_trunc(\'month\', current_date)
        ');
        
        $lotesProducidos = DB::selectOne('
            SELECT count(*) as c
            FROM planta.loteplanta
            WHERE date_trunc(\'month\', fecha_inicio) = date_trunc(\'month\', current_date)
        ');
        
        return view('panel.planta', [
            'batches' => $batches,
            'control_procesos' => $controlProcesos,
            'kpi_rendimiento_promedio' => (float)($rendimientoPromedio->promedio ?? 0),
            'kpi_lotes_producidos' => (int)($lotesProducidos->c ?? 0)
        ]);
    }

    /** Panel certificaciones */
    public function certificaciones(): View
    {
        // Certificados con información de lotes asociados
        $certs = DB::select('
            SELECT c.codigo_certificado, c.ambito, c.area, c.emisor, c.vigente_desde, c.vigente_hasta,
                   count(distinct cls.lote_salida_id) as num_lotes_salida,
                   count(distinct clp.lote_planta_id) as num_lotes_planta,
                   count(distinct clc.lote_campo_id) as num_lotes_campo,
                   CASE
                       WHEN c.vigente_hasta IS NULL THEN true
                       WHEN c.vigente_hasta >= current_date THEN true
                       ELSE false
                   END as vigente
            FROM certificacion.certificado c
            LEFT JOIN certificacion.certificadolotesalida cls ON cls.certificado_id = c.certificado_id
            LEFT JOIN certificacion.certificadoloteplanta clp ON clp.certificado_id = c.certificado_id
            LEFT JOIN certificacion.certificadolotecampo clc ON clc.certificado_id = c.certificado_id
            GROUP BY c.certificado_id, c.codigo_certificado, c.ambito, c.area, c.emisor, c.vigente_desde, c.vigente_hasta
            ORDER BY c.certificado_id DESC 
            LIMIT 15
        ');
        
        // KPIs de certificaciones
        $certsVigentes = DB::selectOne('
            SELECT count(*) as c FROM certificacion.certificado 
            WHERE vigente_hasta IS NULL OR vigente_hasta >= current_date
        ');
        
        $certsPorVencer = DB::selectOne('
            SELECT count(*) as c FROM certificacion.certificado 
            WHERE vigente_hasta BETWEEN current_date AND current_date + interval \'30 days\'
        ');
        
        return view('panel.certificaciones', [
            'certs' => $certs,
            'kpi_certs_vigentes' => (int)($certsVigentes->c ?? 0),
            'kpi_certs_por_vencer' => (int)($certsPorVencer->c ?? 0)
        ]);
    }
}
