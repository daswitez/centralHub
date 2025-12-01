<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW planta.v_trazabilidad_lote_salida AS
            SELECT
              ls.codigo_lote_salida,
              lp.codigo_lote_planta,
              p.codigo_planta,
              (
                SELECT string_agg(lc2.codigo_lote_campo, ', ' ORDER BY lc2.codigo_lote_campo)
                FROM planta.loteplanta_entradacampo lec2
                JOIN campo.lotecampo lc2 ON lc2.lote_campo_id = lec2.lote_campo_id
                WHERE lec2.lote_planta_id = lp.lote_planta_id
              ) AS lotes_campo,
              (
                SELECT MIN(ev2.codigo_envio)
                FROM logistica.enviodetalle ed2
                JOIN logistica.envio ev2 ON ev2.envio_id = ed2.envio_id
                WHERE ed2.lote_salida_id = ls.lote_salida_id
              ) AS primer_envio,
              (
                SELECT string_agg(DISTINCT c2.codigo_cliente, ', ' ORDER BY c2.codigo_cliente)
                FROM logistica.enviodetalle ed2
                JOIN cat.cliente c2 ON c2.cliente_id = ed2.cliente_id
                WHERE ed2.lote_salida_id = ls.lote_salida_id
              ) AS clientes,
              (
                SELECT MIN(ev2.temp_min_c)
                FROM logistica.enviodetalle ed2
                JOIN logistica.envio ev2 ON ev2.envio_id = ed2.envio_id
                WHERE ed2.lote_salida_id = ls.lote_salida_id
              ) AS envio_temp_min_c,
              (
                SELECT MAX(ev2.temp_max_c)
                FROM logistica.enviodetalle ed2
                JOIN logistica.envio ev2 ON ev2.envio_id = ed2.envio_id
                WHERE ed2.lote_salida_id = ls.lote_salida_id
              ) AS envio_temp_max_c,
              ls.peso_t,
              lp.rendimiento_pct
            FROM planta.lotesalida ls
            JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
            JOIN cat.planta p         ON p.planta_id = lp.planta_id;
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS planta.v_trazabilidad_lote_salida CASCADE");
    }
};
