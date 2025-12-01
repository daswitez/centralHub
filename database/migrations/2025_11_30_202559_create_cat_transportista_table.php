<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cat.transportista', function (Blueprint $table) {
            $table->bigIncrements('transportista_id');

            $table->string('codigo_transp', 40)->unique();
            $table->string('nombre', 140);
            $table->string('nro_licencia', 60)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cat.transportista');
    }
};
