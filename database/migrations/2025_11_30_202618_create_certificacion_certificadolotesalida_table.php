<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificadolotesalida', function (Blueprint $table) {

            $table->unsignedBigInteger('certificado_id');
            $table->unsignedBigInteger('lote_salida_id');

            $table->primary(['certificado_id', 'lote_salida_id']);

            $table->foreign('certificado_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');

            $table->foreign('lote_salida_id')
                ->references('lote_salida_id')
                ->on('planta.lotesalida');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificadolotesalida');
    }
};
