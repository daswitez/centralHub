<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificadoenvio', function (Blueprint $table) {

            $table->unsignedBigInteger('certificado_id');
            $table->unsignedBigInteger('envio_id');

            $table->primary(['certificado_id', 'envio_id']);

            $table->foreign('certificado_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');

            $table->foreign('envio_id')
                ->references('envio_id')
                ->on('logistica.envio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificadoenvio');
    }
};
