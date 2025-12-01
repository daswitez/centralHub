<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campo.lotecampo', function (Blueprint $table) {
            $table->bigIncrements('lote_campo_id');

            $table->string('codigo_lote_campo', 50)->unique();

            $table->unsignedBigInteger('productor_id');
            $table->unsignedBigInteger('variedad_id');

            $table->decimal('superficie_ha', 9, 2);
            $table->date('fecha_siembra');
            $table->date('fecha_cosecha')->nullable();
            $table->decimal('humedad_suelo_pct', 5, 2)->nullable();

            $table->foreign('productor_id')
                ->references('productor_id')
                ->on('campo.productor');

            $table->foreign('variedad_id')
                ->references('variedad_id')
                ->on('cat.variedadpapa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('campo.lotecampo');
    }
};
