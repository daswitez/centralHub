<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campo.sensorlectura', function (Blueprint $table) {
            $table->bigIncrements('lectura_id');

            $table->unsignedBigInteger('lote_campo_id');

            $table->timestampTz('fecha_hora');

            $table->string('tipo', 50);
            $table->decimal('valor_num', 18, 6)->nullable();
            $table->string('valor_texto', 200)->nullable();

            $table->foreign('lote_campo_id')
                ->references('lote_campo_id')
                ->on('campo.lotecampo');
        });

        DB::statement('CREATE INDEX ix_sensor_lotehora ON campo.sensorlectura(lote_campo_id, fecha_hora)');
    }

    public function down()
    {
        Schema::dropIfExists('campo.sensorlectura');
    }
};
