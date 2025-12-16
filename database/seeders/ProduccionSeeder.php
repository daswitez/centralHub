<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProduccionSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚜 Generando datos de producción...\n";

        // IDs necesarios
        $plantaId = DB::table('cat.planta')->first()->planta_id ?? 1;
        $productorId = DB::table('campo.productor')->first()->productor_id ?? 1;
        $variedadId = DB::table('cat.variedadpapa')->first()->variedad_id ?? 1;

        // 1. Solicitudes de Producción
        $solicitudExists = DB::table('campo.solicitud_produccion')->where('codigo_solicitud', 'SOL-2024-001')->exists();

        if (!$solicitudExists) {
            $solicitudId = DB::table('campo.solicitud_produccion')->insertGetId([
                'planta_id' => $plantaId,
                'productor_id' => $productorId,
                'variedad_id' => $variedadId,
                'cantidad_solicitada_t' => 10.5,
                'fecha_necesaria' => date('Y-m-d', strtotime('+2 weeks')),
                'estado' => 'ACEPTADA',
                'fecha_solicitud' => now(),
                'fecha_respuesta' => now(),
                'codigo_solicitud' => 'SOL-2024-001'
            ], 'solicitud_id');
        }

        // 2. Asignación de Conductores
        $transportistaId = DB::table('cat.transportista')->first()->transportista_id ?? 1;
        // User and Vehiculo columns do not exist in campo.asignacion_conductor

        // Ensure we have a valid request ID, either newly created or existing
        $solicitudId = DB::table('campo.solicitud_produccion')->where('codigo_solicitud', 'SOL-2024-001')->value('solicitud_id');

        if ($solicitudId) {
            $asignacionExists = DB::table('campo.asignacion_conductor')
                ->where('solicitud_id', $solicitudId)
                ->where('transportista_id', $transportistaId)
                ->exists();

            if (!$asignacionExists) {
                DB::table('campo.asignacion_conductor')->insert([
                    'solicitud_id' => $solicitudId,
                    'transportista_id' => $transportistaId,
                    'fecha_asignacion' => now(),
                    'estado' => 'ASIGNADO',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // 3. Lecturas de Sensores en Campo
        // Necesitamos un lote de campo real creado antes
        $loteCampo = DB::table('campo.lotecampo')->first();
        if ($loteCampo) {
            // Check if readings already exist for this lot to avoid duplicates on re-seed
            $lecturasExist = DB::table('campo.sensorlectura')->where('lote_campo_id', $loteCampo->lote_campo_id)->exists();

            if (!$lecturasExist) {
                $tipos = ['HUMEDAD_SUELO', 'TEMPERATURA', 'PH'];
                foreach ($tipos as $tipo) {
                    for ($i = 0; $i < 5; $i++) {
                        DB::table('campo.sensorlectura')->insert([
                            'lote_campo_id' => $loteCampo->lote_campo_id,
                            'tipo' => $tipo, // Column is 'tipo', not 'tipo_sensor'
                            'valor_num' => rand(20, 80), // Column is 'valor_num'
                            // 'unidad' => 'C', // Column missing
                            'fecha_hora' => now()->subDays($i), // Column is 'fecha_hora'
                            // 'dispositivo_id' => 'DEV-001', // Column missing
                            // 'latitud' => -17.0, // Column missing
                            // 'longitud' => -66.0 // Column missing
                        ]);
                    }
                }
            }
        }

        // 4. Control de Procesos en Planta
        // Necesitamos un lote de planta real
        $lotePlanta = DB::table('planta.loteplanta')->first();
        if ($lotePlanta) {
            $procesosExist = DB::table('planta.controlproceso')->where('lote_planta_id', $lotePlanta->lote_planta_id)->exists();

            if (!$procesosExist) {
                $procesos = ['LAVADO', 'SELECCION', 'EMPAQUE'];
                foreach ($procesos as $proc) {
                    DB::table('planta.controlproceso')->insert([
                        'lote_planta_id' => $lotePlanta->lote_planta_id,
                        'etapa' => $proc, // Column is 'etapa', not 'proceso'
                        // 'responsable' => 'Operador', // Column missing
                        'fecha_hora' => now(), // Column is 'fecha_hora'
                        // 'fecha_fin' => now(), // Column missing
                        'parametro' => 'Temperatura', // New required column 'parametro'
                        'valor_num' => 15.5,          // New required column 'valor_num'
                        // 'parametros_control' => '...', // Column missing
                        'estado' => 'OK' // Column is 'estado' (result)
                    ]);
                }
            }
        }

        echo "✓ Producción, Sensores y Asignaciones creadas\n";
    }
}
