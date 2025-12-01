<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('almacen.pedidodetalle', function (Blueprint $table) {
            $table->bigIncrements('pedido_detalle_id');

            $table->unsignedBigInteger('pedido_almacen_id');
            $table->string('sku', 120);
            $table->decimal('cantidad_t', 12, 3);

            $table->unsignedBigInteger('lote_salida_id')->nullable();

            $table->foreign('pedido_almacen_id')
                ->references('pedido_almacen_id')
                ->on('almacen.pedido');

            $table->foreign('lote_salida_id')
                ->references('lote_salida_id')
                ->on('planta.lotesalida');
        });

        DB::statement('CREATE INDEX ix_palm_pedido ON almacen.pedidodetalle(pedido_almacen_id)');
    }

    public function down()
    {
        Schema::dropIfExists('almacen.pedidodetalle');
    }
};
