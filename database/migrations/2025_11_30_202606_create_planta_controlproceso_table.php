<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planta.controlproceso', function (Blueprint $table) {

            $table->bigIncrements('control_id');

            $table->unsignedBigInteger('lote_planta_id');

            $table->string('etapa', 40);
            $table->timestampTz('fecha_hora');

            $table->string('parametro', 60);
            $table->decimal('valor_num', 18, 6)->nullable();
            $table->string('valor_texto', 200)->nullable();

            $table->string('estado', 20)->default('OK');

            $table->foreign('lote_planta_id')
                ->references('lote_planta_id')
                ->on('planta.loteplanta');
        });

        DB::statement('CREATE INDEX ix_control_lotehora ON planta.controlproceso(lote_planta_id, fecha_hora)');
    }

    public function down()
    {
        Schema::dropIfExists('planta.controlproceso');
    }
};
