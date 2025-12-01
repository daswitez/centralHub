<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planta.loteplanta_entradacampo', function (Blueprint $table) {

            $table->unsignedBigInteger('lote_planta_id');
            $table->unsignedBigInteger('lote_campo_id');

            $table->decimal('peso_entrada_t', 12, 3);

            $table->primary(['lote_planta_id', 'lote_campo_id']);

            $table->foreign('lote_planta_id')
                ->references('lote_planta_id')
                ->on('planta.loteplanta');

            $table->foreign('lote_campo_id')
                ->references('lote_campo_id')
                ->on('campo.lotecampo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planta.loteplanta_entradacampo');
    }
};
