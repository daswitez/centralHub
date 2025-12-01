<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificacion.certificado', function (Blueprint $table) {
            $table->bigIncrements('certificado_id');

            $table->string('codigo_certificado', 60)->unique();
            $table->string('ambito', 30);   // CAMPO / PLANTA / ENVIO / GENERAL
            $table->string('area', 40);     // HACCP / ISO / BPM

            $table->date('vigente_desde');
            $table->date('vigente_hasta')->nullable();

            $table->string('emisor', 160);
            $table->string('url_archivo', 400)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificacion.certificado');
    }
};
