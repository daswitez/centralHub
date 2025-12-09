<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Panel principal: Dashboard Ejecutivo con resumen general */
    public function home(): View
    {
        // ====== KPIs PRINCIPALES ======
        
        // Inventario total en almacenes
        $stockTotal = DB::selectOne('SELECT coalesce(sum(cantidad_t),0) as t FROM almacen.inventario');
        
        // Envíos del día
        $enviosHoy = DB::selectOne('SELECT count(*) as c FROM logistica.envio WHERE date(fecha_salida)=current_date');
        
        // Envíos en ruta activos
        $enviosEnRuta = DB::selectOne("SELECT count(*) as c FROM logistica.envio WHERE estado = 'EN_RUTA'");
        
        // Órdenes de envío pendientes
        $ordenesPendientes = DB::selectOne("SELECT count(*) as c FROM logistica.orden_envio WHERE estado IN ('PENDIENTE', 'CONDUCTOR_ASIGNADO')");
        
        // Lotes procesados este mes
        $lotesProducidos = DB::selectOne("
            SELECT count(*) as c FROM planta.loteplanta 
            WHERE date_trunc('month', fecha_inicio) = date_trunc('month', current_date)
        ");
        
        // Toneladas empacadas este mes
        $toneladasEmpacadas = DB::selectOne("
            SELECT coalesce(sum(peso_t), 0) as t FROM planta.lotesalida 
            WHERE date_trunc('month', fecha_empaque) = date_trunc('month', current_date)
        ");
        
        // Productores registrados
        $productoresActivos = DB::selectOne("SELECT count(*) as c FROM campo.productor");
        
        // Pedidos del mes
        $pedidosMes = DB::selectOne("
            SELECT count(*) as c FROM comercial.pedido 
            WHERE date_trunc('month', fecha_pedido) = date_trunc('month', current_date)
        ");
        
        // Vehículos disponibles (verificar si la columna existe)
        $vehiculosDisponibles = DB::selectOne("SELECT count(*) as c FROM cat.vehiculo WHERE estado = 'DISPONIBLE'");

        
        // Rendimiento promedio de plantas
        $rendimientoPromedio = DB::selectOne("
            SELECT coalesce(avg(rendimiento_pct), 0) as r FROM planta.loteplanta 
            WHERE fecha_inicio >= current_date - interval '30 days'
        ");
        
        // ====== RESÚMENES POR ÁREA ======
        
        // Resumen de ventas por cliente (Top 5 del mes)
        $ventasPorCliente = DB::select("
            SELECT 
                c.cliente_id,
                c.nombre as cliente,
                c.tipo,
                count(DISTINCT p.pedido_id) as num_pedidos,
                coalesce(sum(pd.cantidad_t), 0) as toneladas_vendidas,
                coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total_usd
            FROM cat.cliente c
            JOIN comercial.pedido p ON p.cliente_id = c.cliente_id
            JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            WHERE date_trunc('month', p.fecha_pedido) = date_trunc('month', current_date)
            GROUP BY c.cliente_id, c.nombre, c.tipo
            ORDER BY total_usd DESC
            LIMIT 5
        ");
        
        // Totales de ventas del mes
        $ventasMesTotales = DB::selectOne("
            SELECT 
                count(DISTINCT p.pedido_id) as total_pedidos,
                coalesce(sum(pd.cantidad_t), 0) as total_toneladas,
                coalesce(sum(pd.cantidad_t * pd.precio_unit_usd), 0) as total_usd
            FROM comercial.pedido p
            JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            WHERE date_trunc('month', p.fecha_pedido) = date_trunc('month', current_date)
        ");

        
        // Últimos envíos
        $ultimosEnvios = DB::select("
            SELECT e.codigo_envio, e.estado, e.fecha_salida, 
                   t.nombre as transportista,
                   v.placa as vehiculo,
                   coalesce(sum(ed.cantidad_t), 0) as toneladas
            FROM logistica.envio e
            LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
            LEFT JOIN cat.vehiculo v ON v.vehiculo_id = e.vehiculo_id
            LEFT JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            GROUP BY e.envio_id, e.codigo_envio, e.estado, e.fecha_salida, t.nombre, v.placa
            ORDER BY e.fecha_salida DESC
            LIMIT 5
        ");
        
        // Últimas órdenes de envío
        $ultimasOrdenes = DB::select("
            SELECT oe.orden_envio_id, oe.codigo_orden, oe.estado, oe.fecha_programada, oe.prioridad,
                   p.nombre as planta, a.nombre as almacen,
                   t.nombre as conductor
            FROM logistica.orden_envio oe
            LEFT JOIN cat.planta p ON p.planta_id = oe.planta_origen_id
            LEFT JOIN cat.almacen a ON a.almacen_id = oe.almacen_destino_id
            LEFT JOIN cat.transportista t ON t.transportista_id = oe.transportista_id
            ORDER BY oe.fecha_creacion DESC
            LIMIT 5
        ");
        
        // Últimos lotes de salida
        $ultimosLotesSalida = DB::select("
            SELECT ls.codigo_lote_salida, ls.sku, ls.peso_t, ls.fecha_empaque,
                   pl.nombre as planta
            FROM planta.lotesalida ls
            JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
            JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            ORDER BY ls.fecha_empaque DESC
            LIMIT 5
        ");
        
        // Distribución por variedad (sin filtro de fecha para obtener todos los datos)
        $variedadesDistribucion = DB::select("
            SELECT v.nombre_comercial as variedad, count(lc.lote_campo_id) as cantidad
            FROM campo.lotecampo lc
            JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            GROUP BY v.variedad_id, v.nombre_comercial
            ORDER BY cantidad DESC
            LIMIT 6
        ");

        
        // Estado de plantas
        $plantasResumen = DB::select("
            SELECT p.nombre, p.codigo_planta,
                   count(lp.lote_planta_id) as lotes_mes,
                   coalesce(avg(lp.rendimiento_pct), 0) as rendimiento_prom
            FROM cat.planta p
            LEFT JOIN planta.loteplanta lp ON lp.planta_id = p.planta_id 
                AND date_trunc('month', lp.fecha_inicio) = date_trunc('month', current_date)
            GROUP BY p.planta_id
            ORDER BY lotes_mes DESC
            LIMIT 4
        ");

        // === NUEVOS DATOS PARA GRÁFICOS ===
        
        // Envíos por estado (para gráfico de dona)
        $enviosPorEstado = DB::select("
            SELECT estado, count(*) as cantidad
            FROM logistica.envio
            GROUP BY estado
            ORDER BY cantidad DESC
        ");
        
        // Producción mensual (últimos 6 meses)
        $produccionMensual = DB::select("
            SELECT 
                to_char(lp.fecha_inicio, 'Mon') as mes,
                to_char(lp.fecha_inicio, 'YYYY-MM') as mes_orden,
                count(lp.lote_planta_id) as lotes,
                coalesce(sum(ls.peso_t), 0) as toneladas
            FROM planta.loteplanta lp
            LEFT JOIN planta.lotesalida ls ON ls.lote_planta_id = lp.lote_planta_id
            WHERE lp.fecha_inicio >= current_date - interval '6 months'
            GROUP BY to_char(lp.fecha_inicio, 'Mon'), to_char(lp.fecha_inicio, 'YYYY-MM')
            ORDER BY mes_orden ASC
        ");

        return view('panel.home', [
            // KPIs principales
            'kpi_stock_t' => (float) ($stockTotal->t ?? 0),
            'kpi_envios_hoy' => (int) ($enviosHoy->c ?? 0),
            'kpi_envios_en_ruta' => (int) ($enviosEnRuta->c ?? 0),
            'kpi_ordenes_pendientes' => (int) ($ordenesPendientes->c ?? 0),
            'kpi_lotes_mes' => (int) ($lotesProducidos->c ?? 0),
            'kpi_toneladas_empacadas' => (float) ($toneladasEmpacadas->t ?? 0),
            'kpi_productores' => (int) ($productoresActivos->c ?? 0),
            'kpi_pedidos_mes' => (int) ($pedidosMes->c ?? 0),
            'kpi_vehiculos_disponibles' => (int) ($vehiculosDisponibles->c ?? 0),
            'kpi_rendimiento' => round((float) ($rendimientoPromedio->r ?? 0), 1),
            
            // Tablas resumen
            'ventas_por_cliente' => $ventasPorCliente,
            'ventas_mes_totales' => $ventasMesTotales,
            'ultimos_envios' => $ultimosEnvios,
            'ultimas_ordenes' => $ultimasOrdenes,
            'ultimos_lotes' => $ultimosLotesSalida,
            'variedades' => $variedadesDistribucion,
            'plantas' => $plantasResumen,
            
            // Datos para gráficos
            'envios_por_estado' => $enviosPorEstado,
            'produccion_mensual' => $produccionMensual,
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
