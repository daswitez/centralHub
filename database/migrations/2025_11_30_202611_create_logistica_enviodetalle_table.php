<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logistica.enviodetalle', function (Blueprint $table) {
            $table->bigIncrements('envio_detalle_id');

            $table->unsignedBigInteger('envio_id');
            $table->unsignedBigInteger('lote_salida_id');
            $table->unsignedBigInteger('cliente_id');

            $table->decimal('cantidad_t', 12, 3);

            $table->foreign('envio_id')
                ->references('envio_id')
                ->on('logistica.envio');

            $table->foreign('lote_salida_id')
                ->references('lote_salida_id')
                ->on('planta.lotesalida');

            $table->foreign('cliente_id')
                ->references('cliente_id')
                ->on('cat.cliente');
        });

        DB::statement('CREATE INDEX ix_ed_envio   ON logistica.enviodetalle(envio_id)');
        DB::statement('CREATE INDEX ix_ed_lote    ON logistica.enviodetalle(lote_salida_id)');
        DB::statement('CREATE INDEX ix_ed_cliente ON logistica.enviodetalle(cliente_id)');
    }

    public function down()
    {
        Schema::dropIfExists('logistica.enviodetalle');
    }
};
