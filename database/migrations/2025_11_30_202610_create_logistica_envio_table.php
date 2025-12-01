<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logistica.envio', function (Blueprint $table) {
            $table->bigIncrements('envio_id');

            $table->string('codigo_envio', 40)->unique();

            $table->unsignedBigInteger('ruta_id')->nullable();
            $table->unsignedBigInteger('transportista_id')->nullable();
            $table->unsignedBigInteger('almacen_origen_id')->nullable();

            $table->timestampTz('fecha_salida');
            $table->timestampTz('fecha_llegada')->nullable();

            $table->decimal('temp_min_c', 6, 2)->nullable();
            $table->decimal('temp_max_c', 6, 2)->nullable();

            $table->string('estado', 20)->default('EN_RUTA');

            $table->foreign('ruta_id')
                ->references('ruta_id')
                ->on('logistica.ruta');

            $table->foreign('transportista_id')
                ->references('transportista_id')
                ->on('cat.transportista');

            $table->foreign('almacen_origen_id')
                ->references('almacen_id')
                ->on('cat.almacen');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logistica.envio');
    }
};
