<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar relación transportista → vehículo asignado
     * Y agregar vehiculo_id a logistica.envio para trazabilidad
     */
    public function up(): void
    {
        // 1. Agregar vehículo asignado al transportista
        Schema::table('cat.transportista', function (Blueprint $table) {
            $table->unsignedBigInteger('vehiculo_asignado_id')->nullable()->after('estado');
            
            $table->foreign('vehiculo_asignado_id')
                  ->references('vehiculo_id')
                  ->on('cat.vehiculo')
                  ->nullOnDelete();
        });

        // 2. Agregar vehículo usado en cada envío (para trazabilidad)
        Schema::table('logistica.envio', function (Blueprint $table) {
            $table->unsignedBigInteger('vehiculo_id')->nullable()->after('transportista_id');
            
            $table->foreign('vehiculo_id')
                  ->references('vehiculo_id')
                  ->on('cat.vehiculo')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('logistica.envio', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_id']);
            $table->dropColumn('vehiculo_id');
        });

        Schema::table('cat.transportista', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_asignado_id']);
            $table->dropColumn('vehiculo_asignado_id');
        });
    }
};
