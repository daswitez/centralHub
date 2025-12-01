<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cat.variedadpapa', function (Blueprint $table) {
            $table->bigIncrements('variedad_id');

            $table->string('codigo_variedad', 40)->unique();
            $table->string('nombre_comercial', 120);
            $table->string('aptitud', 80)->nullable();
            $table->integer('ciclo_dias_min')->nullable();
            $table->integer('ciclo_dias_max')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cat.variedadpapa');
    }
};
