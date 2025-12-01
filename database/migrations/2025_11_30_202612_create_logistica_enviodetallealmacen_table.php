<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logistica.enviodetallealmacen', function (Blueprint $table) {
            $table->bigIncrements('envio_detalle_alm_id');

            $table->unsignedBigInteger('envio_id');
            $table->unsignedBigInteger('lote_salida_id');
            $table->unsignedBigInteger('almacen_id');

            $table->decimal('cantidad_t', 12, 3);

            $table->foreign('envio_id')
                ->references('envio_id')
                ->on('logistica.envio');

            $table->foreign('lote_salida_id')
                ->references('lote_salida_id')
                ->on('planta.lotesalida');

            $table->foreign('almacen_id')
                ->references('almacen_id')
                ->on('cat.almacen');
        });

        DB::statement('CREATE INDEX ix_eda_envio   ON logistica.enviodetallealmacen(envio_id)');
        DB::statement('CREATE INDEX ix_eda_lote    ON logistica.enviodetallealmacen(lote_salida_id)');
        DB::statement('CREATE INDEX ix_eda_almacen ON logistica.enviodetallealmacen(almacen_id)');
    }

    public function down()
    {
        Schema::dropIfExists('logistica.enviodetallealmacen');
    }
};
