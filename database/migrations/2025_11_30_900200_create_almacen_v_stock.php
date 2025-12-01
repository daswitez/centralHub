<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW almacen.v_stock AS
            SELECT
              a.codigo_almacen,
              i.sku,
              SUM(i.cantidad_t) AS stock_t
            FROM almacen.inventario i
            JOIN cat.almacen a ON a.almacen_id = i.almacen_id
            GROUP BY a.codigo_almacen, i.sku;
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS almacen.v_stock CASCADE");
    }
};
