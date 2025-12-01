<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('almacen.pedido', function (Blueprint $table) {
            $table->bigIncrements('pedido_almacen_id');

            $table->string('codigo_pedido', 40)->unique();

            $table->unsignedBigInteger('almacen_id');

            $table->timestampTz('fecha_pedido');

            $table->string('estado', 20)->default('ABIERTO');

            $table->foreign('almacen_id')
                ->references('almacen_id')
                ->on('cat.almacen');
        });
    }

    public function down()
    {
        Schema::dropIfExists('almacen.pedido');
    }
};
