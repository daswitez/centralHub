<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificadocadena', function (Blueprint $table) {

            $table->unsignedBigInteger('certificado_padre_id');
            $table->unsignedBigInteger('certificado_hijo_id');

            $table->primary(['certificado_padre_id', 'certificado_hijo_id']);

            $table->foreign('certificado_padre_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');

            $table->foreign('certificado_hijo_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificadocadena');
    }
};
