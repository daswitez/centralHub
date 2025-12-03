<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrazabilidadCompletaSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Limpiando datos anteriores...\n";
        
        // Limpiar en orden inverso por dependencias
        DB::table('logistica.enviodetalle')->whereIn('envio_id', function($query) {
            $query->select('envio_id')->from('logistica.envio')->where('codigo_envio', 'like', 'ENV-2024-%');
        })->delete();
        
        DB::table('logistica.envio')->where('codigo_envio', 'like', 'ENV-2024-%')->delete();
        DB::table('planta.lotesalida')->where('codigo_lote_salida', 'like', 'LS-2024-%')->delete();
        DB::table('planta.loteplanta_entradacampo')->whereIn('lote_planta_id', function($query) {
            $query->select('lote_planta_id')->from('planta.loteplanta')->where('codigo_lote_planta', 'like', 'LP-2024-%');
        })->delete();
        DB::table('planta.loteplanta')->where('codigo_lote_planta', 'like', 'LP-2024-%')->delete();
        DB::table('campo.lotecampo')->where('codigo_lote_campo', 'like', 'LC-2024-%')->delete();

        echo "🏗️ Creando datos de trazabilidad completa...\n";

        // Obtener IDs necesarios
        $planta_id = DB::table('cat.planta')->first()->planta_id ?? 1;
        $productor_id = DB::table('campo.productor')->first()->productor_id ?? 1;
        $variedad_id = DB::table('cat.variedadpapa')->first()->variedad_id ?? 1;
        $cliente_id = DB::table('cat.cliente')->first()->cliente_id ?? 1;
        $almacen_id = DB::table('cat.almacen')->first()->almacen_id ?? 1;
        $transportista_id = DB::table('cat.transportista')->first()->transportista_id ?? 1;
        $ruta_id = DB::table('logistica.ruta')->first()->ruta_id ?? 1;

        // === CADENA 1: Completa (Campo → Planta → Salida → Envío) ===
        echo "  📍 Cadena 1: LC-2024-001 → LP-2024-001 → LS-2024-001 → ENV-2024-001\n";
        
        $lc1 = DB::table('campo.lotecampo')->insertGetId([
            'codigo_lote_campo' => 'LC-2024-001',
            'productor_id' => $productor_id,
            'variedad_id' => $variedad_id,
            'fecha_siembra' => '2024-08-10',
            'fecha_cosecha' => '2024-11-25',
            'superficie_ha' => 5.2
        ], 'lote_campo_id');

        $lp1 = DB::table('planta.loteplanta')->insertGetId([
            'codigo_lote_planta' => 'LP-2024-001',
            'planta_id' => $planta_id,
            'fecha_inicio' => '2024-11-26',
            'rendimiento_pct' => 82
        ], 'lote_planta_id');

        DB::table('planta.loteplanta_entradacampo')->insert([
            'lote_planta_id' => $lp1,
            'lote_campo_id' => $lc1,
            'peso_entrada_t' => 18.5
        ]);

        $ls1 = DB::table('planta.lotesalida')->insertGetId([
            'codigo_lote_salida' => 'LS-2024-001',
            'lote_planta_id' => $lp1,
            'sku' => 'Papa lavada 25kg',
            'peso_t' => 15.2,
            'fecha_empaque' => '2024-11-27 10:30:00'
        ], 'lote_salida_id');

        $env1 = DB::table('logistica.envio')->insertGetId([
            'codigo_envio' => 'ENV-2024-001',
            'ruta_id' => $ruta_id,
            'transportista_id' => $transportista_id,
            'fecha_salida' => '2024-11-28 08:00:00',
            'estado' => 'COMPLETADO',
            'almacen_origen_id' => $almacen_id
        ], 'envio_id');

        DB::table('logistica.enviodetalle')->insert([
            'envio_id' => $env1,
            'lote_salida_id' => $ls1,
            'cliente_id' => $cliente_id,
            'cantidad_t' => 15.2
        ]);

        // === CADENA 2: Parcial hasta Salida ===
        echo "  📍 Cadena 2: LC-2024-002 → LP-2024-002 → LS-2024-002\n";
        
        $lc2 = DB::table('campo.lotecampo')->insertGetId([
            'codigo_lote_campo' => 'LC-2024-002',
            'productor_id' => $productor_id,
            'variedad_id' => $variedad_id,
            'fecha_siembra' => '2024-08-15',
            'fecha_cosecha' => '2024-11-28',
            'superficie_ha' => 3.8
        ], 'lote_campo_id');

        $lp2 = DB::table('planta.loteplanta')->insertGetId([
            'codigo_lote_planta' => 'LP-2024-002',
            'planta_id' => $planta_id,
            'fecha_inicio' => '2024-11-29',
            'rendimiento_pct' => 75
        ], 'lote_planta_id');

        DB::table('planta.loteplanta_entradacampo')->insert([
            'lote_planta_id' => $lp2,
            'lote_campo_id' => $lc2,
            'peso_entrada_t' => 13.8
        ]);

        $ls2 = DB::table('planta.lotesalida')->insertGetId([
            'codigo_lote_salida' => 'LS-2024-002',
            'lote_planta_id' => $lp2,
            'sku' => 'Papa seleccionada 50kg',
            'peso_t' => 10.3,
            'fecha_empaque' => '2024-11-30 14:15:00'
        ], 'lote_salida_id');

        // === CADENA 3: Solo Campo → Planta ===
        echo "  📍 Cadena 3: LC-2024-003 → LP-2024-003\n";
        
        $lc3 = DB::table('campo.lotecampo')->insertGetId([
            'codigo_lote_campo' => 'LC-2024-003',
            'productor_id' => $productor_id,
            'variedad_id' => $variedad_id,
            'fecha_siembra' => '2024-09-01',
            'fecha_cosecha' => '2024-12-01',
            'superficie_ha' => 4.5
        ], 'lote_campo_id');

        $lp3 = DB::table('planta.loteplanta')->insertGetId([
            'codigo_lote_planta' => 'LP-2024-003',
            'planta_id' => $planta_id,
            'fecha_inicio' => '2024-12-02',
            'rendimiento_pct' => 79
        ], 'lote_planta_id');

        DB::table('planta.loteplanta_entradacampo')->insert([
            'lote_planta_id' => $lp3,
            'lote_campo_id' => $lc3,
            'peso_entrada_t' => 16.2
        ]);

        // === CADENA 4: Envío con múltiples salidas ===
        echo "  📍 Cadena 4: ENV-2024-002 (contiene LS-2024-002 y nueva LS-2024-003)\n";
        
        $ls3 = DB::table('planta.lotesalida')->insertGetId([
            'codigo_lote_salida' => 'LS-2024-003',
            'lote_planta_id' => $lp3,
            'sku' => 'Papa premium 10kg',
            'peso_t' => 8.5,
            'fecha_empaque' => '2024-12-02 16:45:00'
        ], 'lote_salida_id');

        $env2 = DB::table('logistica.envio')->insertGetId([
            'codigo_envio' => 'ENV-2024-002',
            'ruta_id' => $ruta_id,
            'transportista_id' => $transportista_id,
            'fecha_salida' => '2024-12-03 07:30:00',
            'estado' => 'EN_RUTA',
            'almacen_origen_id' => $almacen_id
        ], 'envio_id');

        DB::table('logistica.enviodetalle')->insert([
            'envio_id' => $env2,
            'lote_salida_id' => $ls2,
            'cliente_id' => $cliente_id,
            'cantidad_t' => 10.3
        ]);

        DB::table('logistica.enviodetalle')->insert([
            'envio_id' => $env2,
            'lote_salida_id' => $ls3,
            'cliente_id' => $cliente_id,
            'cantidad_t' => 8.5
        ]);

        echo "\n✅ Datos de trazabilidad completa creados:\n";
        echo "   └─ 3 Lotes de Campo (LC-2024-001 a 003)\n";
        echo "   └─ 3 Lotes de Planta (LP-2024-001 a 003)\n";
        echo "   └─ 3 Lotes de Salida (LS-2024-001 a 003)\n";
        echo "   └─ 2 Envíos (ENV-2024-001 y 002)\n";
        echo "\n🔍 Prueba buscando cualquier código desde cualquier punto de la cadena!\n";
    }
}
