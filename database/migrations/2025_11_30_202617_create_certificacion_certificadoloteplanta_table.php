<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificadoloteplanta', function (Blueprint $table) {

            $table->unsignedBigInteger('certificado_id');
            $table->unsignedBigInteger('lote_planta_id');

            $table->primary(['certificado_id', 'lote_planta_id']);

            $table->foreign('certificado_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');

            $table->foreign('lote_planta_id')
                ->references('lote_planta_id')
                ->on('planta.loteplanta');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificadoloteplanta');
    }
};
