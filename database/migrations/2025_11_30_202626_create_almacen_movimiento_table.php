<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('almacen.movimiento', function (Blueprint $table) {

            $table->bigIncrements('mov_id');

            $table->unsignedBigInteger('almacen_id');
            $table->unsignedBigInteger('lote_salida_id');

            $table->string('tipo', 12); // ENTRADA / SALIDA
            $table->decimal('cantidad_t', 12, 3);

            $table->timestampTz('fecha_mov')->default(now());

            $table->string('referencia', 40)->nullable();
            $table->string('detalle', 200)->nullable();

            $table->foreign('almacen_id')
                ->references('almacen_id')
                ->on('cat.almacen');

            $table->foreign('lote_salida_id')
                ->references('lote_salida_id')
                ->on('planta.lotesalida');
        });

        DB::statement('CREATE INDEX ix_mov_alm_fec ON almacen.movimiento(almacen_id, fecha_mov)');
    }

    public function down()
    {
        Schema::dropIfExists('almacen.movimiento');
    }
};
