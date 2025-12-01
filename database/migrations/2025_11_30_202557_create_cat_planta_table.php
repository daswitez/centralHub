<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cat.planta', function (Blueprint $table) {
            $table->bigIncrements('planta_id');

            $table->string('codigo_planta', 40)->unique();
            $table->string('nombre', 140);

            $table->unsignedBigInteger('municipio_id');

            $table->string('direccion', 200)->nullable();
            $table->decimal('lat', 9, 6)->nullable();
            $table->decimal('lon', 9, 6)->nullable();

            $table->foreign('municipio_id')
                ->references('municipio_id')
                ->on('cat.municipio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cat.planta');
    }
};
