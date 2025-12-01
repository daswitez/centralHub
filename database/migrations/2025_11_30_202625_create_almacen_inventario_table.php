<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('almacen.inventario', function (Blueprint $table) {

            $table->unsignedBigInteger('almacen_id');
            $table->unsignedBigInteger('lote_salida_id');

            $table->string('sku', 120);
            $table->decimal('cantidad_t', 12, 3);

            $table->primary(['almacen_id', 'lote_salida_id']);

            $table->foreign('almacen_id')
                ->references('almacen_id')
                ->on('cat.almacen');

            $table->foreign('lote_salida_id')
                ->references('lote_salida_id')
                ->on('planta.lotesalida');
        });

        DB::statement('CREATE INDEX ix_inv_sku ON almacen.inventario(almacen_id, sku)');
    }

    public function down()
    {
        Schema::dropIfExists('almacen.inventario');
    }
};
