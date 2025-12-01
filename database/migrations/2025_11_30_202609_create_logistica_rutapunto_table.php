<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logistica.rutapunto', function (Blueprint $table) {

            $table->unsignedBigInteger('ruta_id');
            $table->integer('orden');

            $table->unsignedBigInteger('cliente_id');

            $table->primary(['ruta_id', 'orden']);

            $table->foreign('ruta_id')
                ->references('ruta_id')
                ->on('logistica.ruta');

            $table->foreign('cliente_id')
                ->references('cliente_id')
                ->on('cat.cliente');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logistica.rutapunto');
    }
};
