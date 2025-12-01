<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificadoevidencia', function (Blueprint $table) {

            $table->bigIncrements('evidencia_id');

            $table->unsignedBigInteger('certificado_id');

            $table->string('tipo', 60);
            $table->string('descripcion', 400)->nullable();
            $table->string('url_archivo', 400)->nullable();

            $table->timestampTz('fecha_registro')->default(now());

            $table->foreign('certificado_id')
                ->references('certificado_id')
                ->on('certificacion.certificado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificadoevidencia');
    }
};
