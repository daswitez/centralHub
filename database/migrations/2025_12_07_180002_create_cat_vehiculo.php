<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de vehículos para la flota de transporte
     */
    public function up(): void
    {
        Schema::create('cat.vehiculo', function (Blueprint $table) {
            $table->bigIncrements('vehiculo_id');
            
            // Identificación
            $table->string('codigo_vehiculo', 20)->unique();
            $table->string('placa', 15)->unique();
            
            // Características del vehículo
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->integer('anio')->nullable();
            $table->string('color', 30)->nullable();
            
            // Capacidades
            $table->decimal('capacidad_t', 8, 3)->comment('Capacidad en toneladas');
            $table->string('tipo', 30)->default('CAMION')
                  ->comment('CAMION, FURGON, REFRIGERADO, CISTERNA');
            
            // Estado
            $table->string('estado', 20)->default('DISPONIBLE')
                  ->comment('DISPONIBLE, EN_USO, MANTENIMIENTO, FUERA_SERVICIO');
            
            // Mantenimiento
            $table->date('fecha_ultima_revision')->nullable();
            $table->date('fecha_proxima_revision')->nullable();
            $table->integer('kilometraje')->default(0);
            
            // Documentación
            $table->date('vencimiento_seguro')->nullable();
            $table->date('vencimiento_inspeccion')->nullable();
            
            // Índices
            $table->index('estado');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat.vehiculo');
    }
};
