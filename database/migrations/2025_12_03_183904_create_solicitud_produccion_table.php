<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campo.solicitud_produccion', function (Blueprint $table) {
            $table->id('solicitud_id');
            $table->unsignedBigInteger('planta_id');
            $table->unsignedBigInteger('productor_id');
            $table->unsignedBigInteger('variedad_id');
            $table->decimal('cantidad_solicitada_t', 10, 3);
            $table->date('fecha_necesaria');
            $table->enum('estado', ['PENDIENTE', 'ACEPTADA', 'RECHAZADA', 'COMPLETADA'])
                  ->default('PENDIENTE');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->text('justificacion_rechazo')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('planta_id')->references('planta_id')->on('cat.planta');
            $table->foreign('productor_id')->references('productor_id')->on('campo.productor');
            $table->foreign('variedad_id')->references('variedad_id')->on('cat.variedadpapa');
            
            // Índices para búsquedas
            $table->index('estado');
            $table->index('planta_id');
            $table->index('productor_id');
            $table->index('fecha_necesaria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campo.solicitud_produccion');
    }
};
