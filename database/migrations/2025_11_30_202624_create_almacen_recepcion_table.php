<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('almacen.recepcion', function (Blueprint $table) {
            $table->bigIncrements('recepcion_id');

            $table->unsignedBigInteger('envio_id');
            $table->unsignedBigInteger('almacen_id');

            $table->timestampTz('fecha_recepcion')->default(now());
            $table->string('observacion', 200)->nullable();

            $table->foreign('envio_id')
                ->references('envio_id')
                ->on('logistica.envio');

            $table->foreign('almacen_id')
                ->references('almacen_id')
                ->on('cat.almacen');
        });

        DB::statement('CREATE INDEX ix_rec_alm_fec ON almacen.recepcion(almacen_id, fecha_recepcion)');
    }

    public function down()
    {
        Schema::dropIfExists('almacen.recepcion');
    }
};
