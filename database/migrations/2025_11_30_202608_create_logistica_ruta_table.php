<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logistica.ruta', function (Blueprint $table) {
            $table->bigIncrements('ruta_id');

            $table->string('codigo_ruta', 40)->unique();
            $table->string('descripcion', 160)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logistica.ruta');
    }
};
