<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planta.loteplanta', function (Blueprint $table) {
            $table->bigIncrements('lote_planta_id');

            $table->string('codigo_lote_planta', 50)->unique();

            $table->unsignedBigInteger('planta_id');

            $table->timestampTz('fecha_inicio');
            $table->timestampTz('fecha_fin')->nullable();

            $table->decimal('rendimiento_pct', 5, 2)->nullable();

            $table->foreign('planta_id')
                ->references('planta_id')
                ->on('cat.planta');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planta.loteplanta');
    }
};
