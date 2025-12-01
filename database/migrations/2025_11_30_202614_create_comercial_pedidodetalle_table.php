<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comercial.pedidodetalle', function (Blueprint $table) {
            $table->bigIncrements('pedido_detalle_id');

            $table->unsignedBigInteger('pedido_id');

            $table->string('sku', 120);
            $table->decimal('cantidad_t', 12, 3);
            $table->decimal('precio_unit_usd', 12, 2);

            $table->foreign('pedido_id')
                ->references('pedido_id')
                ->on('comercial.pedido');
        });

        DB::statement('CREATE INDEX ix_pd_pedido ON comercial.pedidodetalle(pedido_id)');
    }

    public function down()
    {
        Schema::dropIfExists('comercial.pedidodetalle');
    }
};
