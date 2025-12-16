<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmacenTransaccionalSeeder extends Seeder
{
    public function run(): void
    {
        echo "🏢 Generando movimientos de almacén...\n";

        $almacenId = DB::table('cat.almacen')->first()->almacen_id ?? 1;
        $envioId = DB::table('logistica.envio')->where('estado', 'COMPLETADO')->first()->envio_id ?? null;

        // 1. Recepción de un Envío
        if ($envioId) {
            $recepcionExists = DB::table('almacen.recepcion')
                ->where('envio_id', $envioId)
                ->where('almacen_id', $almacenId)
                ->exists();

            if (!$recepcionExists) {
                $recepcionId = DB::table('almacen.recepcion')->insertGetId([
                    'envio_id' => $envioId,
                    'almacen_id' => $almacenId,
                    'fecha_recepcion' => now(),
                    'observacion' => 'Recepción conforme, sin daños visibles'
                ], 'recepcion_id');

                echo "  - Recepción #$recepcionId creada\n";
            }
        }

        // 2. Inventario inicial simulado
        // Necesitamos un lote de salida (producto terminado)
        $loteSalida = DB::table('planta.lotesalida')->first();
        $sku = 'Papa lavada 25kg'; // This seems to be a text field in inventory, valid.

        if ($loteSalida) {
            $invExists = DB::table('almacen.inventario')
                ->where('almacen_id', $almacenId)
                ->where('lote_salida_id', $loteSalida->lote_salida_id)
                ->exists();

            if (!$invExists) {
                DB::table('almacen.inventario')->insert([
                    'almacen_id' => $almacenId,
                    'lote_salida_id' => $loteSalida->lote_salida_id,
                    'sku' => $sku,
                    'cantidad_t' => 5.0,
                    // 'fecha_entrada' => now()->subDays(2), // Column missing
                    // 'estado' => 'DISPONIBLE' // Column missing
                    // 'ubicacion_id' // Column missing based on migration view
                ]);

                echo "  - Inventario creado para Lote Salida {$loteSalida->codigo_lote_salida}\n";
            }

            // 3. Movimiento Interno
            // Using logic based on lotesalida

            // Check if movement exists (check by lote_salida and type)
            $movExists = DB::table('almacen.movimiento')
                ->where('lote_salida_id', $loteSalida->lote_salida_id)
                ->where('tipo', 'ENTRADA')
                ->exists();

            if (!$movExists) {
                DB::table('almacen.movimiento')->insert([
                    'almacen_id' => $almacenId,
                    'lote_salida_id' => $loteSalida->lote_salida_id,
                    'tipo' => 'ENTRADA',
                    'cantidad_t' => 5.0,
                    'fecha_mov' => now()->subDays(2),
                    'referencia' => 'REF-INIT-001',
                    'detalle' => 'Entrada inicial por ajuste'
                    // removed invalid columns like ubicacion, usuario, motivo, sku
                ]);
                echo "  - Movimiento de entrada creado\n";
            }
        } else {
            echo "⚠️ No se encontraron lotes de salida para generar inventario.\n";
        }

        echo "✓ Transacciones de almacén generadas\n";
    }
}
