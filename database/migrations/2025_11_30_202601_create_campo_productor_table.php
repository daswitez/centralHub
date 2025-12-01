<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campo.productor', function (Blueprint $table) {
            $table->bigIncrements('productor_id');

            $table->string('codigo_productor', 40)->unique();
            $table->string('nombre', 140);

            $table->unsignedBigInteger('municipio_id');

            $table->string('telefono', 40)->nullable();

            $table->foreign('municipio_id')
                ->references('municipio_id')
                ->on('cat.municipio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('campo.productor');
    }
};
