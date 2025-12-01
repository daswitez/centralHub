<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cat.municipio', function (Blueprint $table) {
            $table->bigIncrements('municipio_id');

            $table->unsignedBigInteger('departamento_id');

            $table->string('nombre', 120);

            $table->unique(['departamento_id', 'nombre']);

            $table->foreign('departamento_id')
                ->references('departamento_id')
                ->on('cat.departamento');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cat.municipio');
    }
};
