<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de zonas dentro de un almacén
     * Ej: Zona A (Refrigerado), Zona B (Seco), etc.
     */
    public function up(): void
    {
        Schema::create('almacen.zona', function (Blueprint $table) {
            $table->bigIncrements('zona_id');
            
            $table->unsignedBigInteger('almacen_id');
            
            // Identificación
            $table->string('codigo_zona', 20)->unique();
            $table->string('nombre', 50);
            $table->string('descripcion', 200)->nullable();
            
            // Tipo de almacenamiento
            $table->string('tipo', 30)->default('SECO')
                  ->comment('REFRIGERADO, CONGELADO, SECO, CUARENTENA');
            
            // Capacidades
            $table->decimal('capacidad_t', 12, 3)->comment('Capacidad máxima en toneladas');
            $table->decimal('ocupacion_actual_t', 12, 3)->default(0);
            
            // Condiciones ambientales
            $table->decimal('temperatura_objetivo_c', 5, 2)->nullable();
            $table->decimal('humedad_objetivo_pct', 5, 2)->nullable();
            
            // Estado
            $table->string('estado', 20)->default('DISPONIBLE')
                  ->comment('DISPONIBLE, LLENO, MANTENIMIENTO, CERRADO');
            
            // Ubicación física dentro del almacén
            $table->integer('num_pasillos')->default(1);
            $table->integer('num_racks_por_pasillo')->default(1);
            $table->integer('num_niveles')->default(1);
            
            $table->timestampTz('created_at')->default(now());
            
            // Foreign keys
            $table->foreign('almacen_id')
                  ->references('almacen_id')
                  ->on('cat.almacen')
                  ->cascadeOnDelete();
            
            // Índices
            $table->index(['almacen_id', 'tipo']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almacen.zona');
    }
};
