<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de órdenes de envío interno (Planta → Almacén)
     */
    public function up(): void
    {
        Schema::create('logistica.orden_envio', function (Blueprint $table) {
            $table->bigIncrements('orden_envio_id');
            
            // Código único
            $table->string('codigo_orden', 30)->unique();
            
            // Origen (Planta)
            $table->unsignedBigInteger('planta_origen_id');
            $table->unsignedBigInteger('lote_salida_id');
            
            // Destino (Almacén)
            $table->unsignedBigInteger('almacen_destino_id');
            $table->unsignedBigInteger('zona_destino_id')->nullable();
            
            // Asignación de transporte
            $table->unsignedBigInteger('transportista_id')->nullable();
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            
            // Cantidades
            $table->decimal('cantidad_t', 12, 3);
            
            // Estado del envío
            $table->string('estado', 30)->default('PENDIENTE')
                  ->comment('PENDIENTE, CONDUCTOR_ASIGNADO, EN_CARGA, EN_RUTA, ENTREGADO, CANCELADO');
            
            // Fechas
            $table->timestampTz('fecha_creacion')->default(now());
            $table->date('fecha_programada')->nullable();
            $table->timestampTz('fecha_asignacion')->nullable();
            $table->timestampTz('fecha_salida')->nullable();
            $table->timestampTz('fecha_llegada')->nullable();
            
            // Prioridad y notas
            $table->string('prioridad', 10)->default('NORMAL')
                  ->comment('URGENTE, NORMAL, BAJA');
            $table->text('observaciones')->nullable();
            
            // Auditoría
            $table->unsignedBigInteger('creado_por')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('planta_origen_id')
                  ->references('planta_id')
                  ->on('cat.planta');
            
            $table->foreign('lote_salida_id')
                  ->references('lote_salida_id')
                  ->on('planta.lotesalida');
            
            $table->foreign('almacen_destino_id')
                  ->references('almacen_id')
                  ->on('cat.almacen');
            
            $table->foreign('zona_destino_id')
                  ->references('zona_id')
                  ->on('almacen.zona')
                  ->nullOnDelete();
            
            $table->foreign('transportista_id')
                  ->references('transportista_id')
                  ->on('cat.transportista')
                  ->nullOnDelete();
            
            $table->foreign('vehiculo_id')
                  ->references('vehiculo_id')
                  ->on('cat.vehiculo')
                  ->nullOnDelete();
            
            $table->foreign('creado_por')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
            
            // Índices
            $table->index('estado');
            $table->index('fecha_programada');
            $table->index(['planta_origen_id', 'estado']);
            $table->index(['almacen_destino_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistica.orden_envio');
    }
};
