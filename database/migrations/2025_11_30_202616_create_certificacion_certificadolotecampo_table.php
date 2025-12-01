<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificadolotecampo', function (Blueprint $table) {

            $table->unsignedBigInteger('certificado_id');
            $table->unsignedBigInteger('lote_campo_id');

            $table->primary(['certificado_id', 'lote_campo_id']);

            $table->foreign('certificado_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');

            $table->foreign('lote_campo_id')
                ->references('lote_campo_id')
                ->on('campo.lotecampo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificadolotecampo');
    }
};
