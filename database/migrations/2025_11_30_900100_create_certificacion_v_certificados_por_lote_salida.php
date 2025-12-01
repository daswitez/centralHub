<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW certificacion.v_certificados_por_lote_salida AS
            SELECT
              ls.codigo_lote_salida,
              cert.codigo_certificado,
              cert.ambito,
              cert.area,
              cert.vigente_desde,
              cert.vigente_hasta,
              cert.emisor
            FROM planta.lotesalida ls
            JOIN certificacion.certificadolotesalida cls ON cls.lote_salida_id = ls.lote_salida_id
            JOIN certificacion.certificado cert          ON cert.certificado_id = cls.certificado_id

            UNION ALL
            SELECT
              ls.codigo_lote_salida,
              cert.codigo_certificado,
              cert.ambito,
              cert.area,
              cert.vigente_desde,
              cert.vigente_hasta,
              cert.emisor
            FROM planta.lotesalida ls
            JOIN planta.loteplanta lp                    ON lp.lote_planta_id = ls.lote_planta_id
            JOIN certificacion.certificadoloteplanta clp ON clp.lote_planta_id = lp.lote_planta_id
            JOIN certificacion.certificado cert          ON cert.certificado_id = clp.certificado_id

            UNION ALL
            SELECT
              ls.codigo_lote_salida,
              cert.codigo_certificado,
              cert.ambito,
              cert.area,
              cert.vigente_desde,
              cert.vigente_hasta,
              cert.emisor
            FROM planta.lotesalida ls
            JOIN planta.loteplanta lp                         ON lp.lote_planta_id = ls.lote_planta_id
            JOIN planta.loteplanta_entradacampo lec           ON lec.lote_planta_id = lp.lote_planta_id
            JOIN certificacion.certificadolotecampo clc       ON clc.lote_campo_id = lec.lote_campo_id
            JOIN certificacion.certificado cert               ON cert.certificado_id = clc.certificado_id

            UNION ALL
            SELECT
              ls.codigo_lote_salida,
              cert.codigo_certificado,
              cert.ambito,
              cert.area,
              cert.vigente_desde,
              cert.vigente_hasta,
              cert.emisor
            FROM planta.lotesalida ls
            JOIN logistica.enviodetalle ed                    ON ed.lote_salida_id = ls.lote_salida_id
            JOIN certificacion.certificadoenvio ce            ON ce.envio_id = ed.envio_id
            JOIN certificacion.certificado cert               ON cert.certificado_id = ce.certificado_id
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS certificacion.v_certificados_por_lote_salida CASCADE");
    }
};
