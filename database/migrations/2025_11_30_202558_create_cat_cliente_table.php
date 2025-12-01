<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cat.cliente', function (Blueprint $table) {
            $table->bigIncrements('cliente_id');

            $table->string('codigo_cliente', 40)->unique();
            $table->string('nombre', 160);
            $table->string('tipo', 60);

            $table->unsignedBigInteger('municipio_id')->nullable();

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
        Schema::dropIfExists('cat.cliente');
    }
};
