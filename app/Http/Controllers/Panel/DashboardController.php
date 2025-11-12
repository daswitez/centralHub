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
        return view('panel.ventas');
    }

    /** Panel logística */
    public function logistica(): View
    {
        $envios = DB::select('select envio_id, codigo_envio, fecha_salida, fecha_llegada, estado from logistica.envio order by envio_id desc limit 10');
        return view('panel.logistica', ['envios' => $envios]);
    }

    /** Panel planta/procesos */
    public function planta(): View
    {
        $batches = DB::select('
            select ls.codigo_lote_salida, lp.codigo_lote_planta, ls.peso_t, lp.rendimiento_pct
            from planta.lotesalida ls join planta.loteplanta lp on lp.lote_planta_id = ls.lote_planta_id
            order by ls.lote_salida_id desc limit 10
        ');
        return view('panel.planta', ['batches' => $batches]);
    }

    /** Panel certificaciones */
    public function certificaciones(): View
    {
        $certs = DB::select('select codigo_certificado, ambito, area, emisor, vigente_desde, vigente_hasta from certificacion.certificado order by certificado_id desc limit 12');
        return view('panel.certificaciones', ['certs' => $certs]);
    }
}


