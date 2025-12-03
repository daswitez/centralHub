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
        Schema::create('campo.asignacion_conductor', function (Blueprint $table) {
            $table->id('asignacion_id');
            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('transportista_id');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->enum('estado', ['ASIGNADO', 'EN_RUTA', 'COMPLETADO'])
                  ->default('ASIGNADO');
            $table->timestamp('fecha_inicio_ruta')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('solicitud_id')
                  ->references('solicitud_id')
                  ->on('campo.solicitud_produccion')
                  ->onDelete('cascade');
            $table->foreign('transportista_id')
                  ->references('transportista_id')
                  ->on('cat.transportista');
            
            // Índices
            $table->index('estado');
            $table->index('solicitud_id');
            $table->index('transportista_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campo.asignacion_conductor');
    }
};
