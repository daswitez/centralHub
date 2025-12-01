<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comercial.pedido', function (Blueprint $table) {
            $table->bigIncrements('pedido_id');

            $table->string('codigo_pedido', 40)->unique();

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('almacen_id')->nullable();

            $table->timestampTz('fecha_pedido');

            $table->string('estado', 20)->default('ABIERTO');

            $table->foreign('cliente_id')
                ->references('cliente_id')
                ->on('cat.cliente');

            $table->foreign('almacen_id')
                ->references('almacen_id')
                ->on('cat.almacen');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comercial.pedido');
    }
};
