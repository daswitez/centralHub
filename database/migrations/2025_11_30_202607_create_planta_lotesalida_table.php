<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planta.lotesalida', function (Blueprint $table) {
            $table->bigIncrements('lote_salida_id');

            $table->string('codigo_lote_salida', 50)->unique();

            $table->unsignedBigInteger('lote_planta_id');

            $table->string('sku', 120);
            $table->decimal('peso_t', 12, 3);

            $table->timestampTz('fecha_empaque');

            $table->foreign('lote_planta_id')
                ->references('lote_planta_id')
                ->on('planta.loteplanta');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planta.lotesalida');
    }
};
