<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de ubicaciones físicas (racks)
     * Formato: Pasillo-Rack-Nivel (ej: A-01-03)
     */
    public function up(): void
    {
        Schema::create('almacen.ubicacion', function (Blueprint $table) {
            $table->bigIncrements('ubicacion_id');
            
            $table->unsignedBigInteger('zona_id');
            
            // Código único de ubicación (ej: A-01-03)
            $table->string('codigo_ubicacion', 30)->unique();
            
            // Posición física
            $table->string('pasillo', 5)->comment('A, B, C...');
            $table->integer('rack')->comment('Número de rack: 1, 2, 3...');
            $table->integer('nivel')->comment('Nivel vertical: 1 (suelo), 2, 3...');
            
            // Capacidad del espacio
            $table->decimal('capacidad_t', 8, 3)->comment('Capacidad en toneladas');
            
            // Estado de ocupación
            $table->boolean('ocupado')->default(false);
            $table->unsignedBigInteger('lote_salida_id')->nullable()
                  ->comment('Lote actualmente almacenado');
            $table->decimal('cantidad_almacenada_t', 8, 3)->nullable();
            $table->timestampTz('fecha_ocupacion')->nullable();
            
            // Características especiales
            $table->boolean('refrigerado')->default(false);
            $table->boolean('acceso_montacargas')->default(true);
            $table->string('observaciones', 200)->nullable();
            
            // Foreign keys
            $table->foreign('zona_id')
                  ->references('zona_id')
                  ->on('almacen.zona')
                  ->cascadeOnDelete();
            
            $table->foreign('lote_salida_id')
                  ->references('lote_salida_id')
                  ->on('planta.lotesalida')
                  ->nullOnDelete();
            
            // Índices
            $table->unique(['zona_id', 'pasillo', 'rack', 'nivel']);
            $table->index('ocupado');
            $table->index('lote_salida_id');
        });

        // Índice para búsqueda por posición
        DB::statement('CREATE INDEX ix_ubic_pos ON almacen.ubicacion(zona_id, pasillo, rack)');
    }

    public function down(): void
    {
        Schema::dropIfExists('almacen.ubicacion');
    }
};
