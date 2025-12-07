<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mejorar tabla de recepción en almacén con más campos
     */
    public function up(): void
    {
        Schema::table('almacen.recepcion', function (Blueprint $table) {
            // Vincular con orden de envío
            $table->unsignedBigInteger('orden_envio_id')->nullable()->after('almacen_id');
            
            // Ubicación donde se almacenó
            $table->unsignedBigInteger('zona_id')->nullable()->after('orden_envio_id');
            $table->unsignedBigInteger('ubicacion_id')->nullable()->after('zona_id');
            
            // Cantidades
            $table->decimal('cantidad_esperada_t', 12, 3)->nullable()->after('ubicacion_id');
            $table->decimal('cantidad_recibida_t', 12, 3)->nullable()->after('cantidad_esperada_t');
            $table->decimal('diferencia_t', 12, 3)->nullable()->after('cantidad_recibida_t')
                  ->comment('Positivo = sobrante, Negativo = faltante');
            
            // Estado del producto recibido
            $table->string('estado_producto', 30)->default('BUENO')->after('diferencia_t')
                  ->comment('BUENO, DAÑADO, PARCIAL, RECHAZADO');
            
            // Condiciones de llegada
            $table->decimal('temperatura_llegada_c', 5, 2)->nullable()->after('estado_producto');
            
            // Auditoría
            $table->unsignedBigInteger('recibido_por')->nullable()->after('temperatura_llegada_c');
            
            // Firma digital del conductor (base64)
            $table->text('firma_conductor')->nullable()->after('recibido_por');
            
            // Foreign keys
            $table->foreign('orden_envio_id')
                  ->references('orden_envio_id')
                  ->on('logistica.orden_envio')
                  ->nullOnDelete();
            
            $table->foreign('zona_id')
                  ->references('zona_id')
                  ->on('almacen.zona')
                  ->nullOnDelete();
            
            $table->foreign('ubicacion_id')
                  ->references('ubicacion_id')
                  ->on('almacen.ubicacion')
                  ->nullOnDelete();
            
            $table->foreign('recibido_por')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('almacen.recepcion', function (Blueprint $table) {
            $table->dropForeign(['orden_envio_id']);
            $table->dropForeign(['zona_id']);
            $table->dropForeign(['ubicacion_id']);
            $table->dropForeign(['recibido_por']);
            
            $table->dropColumn([
                'orden_envio_id',
                'zona_id',
                'ubicacion_id',
                'cantidad_esperada_t',
                'cantidad_recibida_t',
                'diferencia_t',
                'estado_producto',
                'temperatura_llegada_c',
                'recibido_por',
                'firma_conductor'
            ]);
        });
    }
};
