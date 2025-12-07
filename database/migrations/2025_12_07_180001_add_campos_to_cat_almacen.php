<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campos realistas a la tabla de almacenes
     */
    public function up(): void
    {
        Schema::table('cat.almacen', function (Blueprint $table) {
            // Capacidades
            $table->decimal('capacidad_total_t', 12, 3)->nullable()->after('lon')
                  ->comment('Capacidad máxima en toneladas');
            $table->decimal('capacidad_disponible_t', 12, 3)->nullable()->after('capacidad_total_t')
                  ->comment('Capacidad actualmente disponible');
            
            // Tipo y estado
            $table->string('tipo', 30)->default('CENTRAL')->after('capacidad_disponible_t')
                  ->comment('CENTRAL, DISTRIBUCION, REFRIGERADO, SECO');
            $table->string('estado', 20)->default('ACTIVO')->after('tipo')
                  ->comment('ACTIVO, MANTENIMIENTO, INACTIVO');
            
            // Condiciones ambientales (para almacenes refrigerados)
            $table->decimal('temperatura_min_c', 5, 2)->nullable()->after('estado');
            $table->decimal('temperatura_max_c', 5, 2)->nullable()->after('temperatura_min_c');
            
            // Información operativa
            $table->integer('num_zonas')->default(1)->after('temperatura_max_c');
            $table->string('telefono', 20)->nullable()->after('num_zonas');
            $table->string('email', 100)->nullable()->after('telefono');
            $table->string('responsable', 100)->nullable()->after('email')
                  ->comment('Nombre del jefe de almacén');
            $table->string('horario_operacion', 50)->nullable()->after('responsable')
                  ->comment('Ej: 08:00-18:00');
        });
    }

    public function down(): void
    {
        Schema::table('cat.almacen', function (Blueprint $table) {
            $table->dropColumn([
                'capacidad_total_t',
                'capacidad_disponible_t',
                'tipo',
                'estado',
                'temperatura_min_c',
                'temperatura_max_c',
                'num_zonas',
                'telefono',
                'email',
                'responsable',
                'horario_operacion'
            ]);
        });
    }
};
