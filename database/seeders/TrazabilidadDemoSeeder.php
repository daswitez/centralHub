<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrazabilidadDemoSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Creando datos de trazabilidad completa...\n";

        // IDs dinámicos - usando los existentes
        $planta_id = DB::table('cat.planta')->first()->planta_id ?? 1;
        $productor_id = DB::table('campo.productor')->first()->productor_id ?? 1;
        $variedad_id = DB::table('cat.variedadpapa')->first()->variedad_id ?? 1;
        $cliente_id = DB::table('cat.cliente')->first()->cliente_id ?? 1;
        $almacen_id = DB::table('cat.almacen')->first()->almacen_id ?? 1;
        $transportista_id = DB::table('cat.transportista')->first()->transportista_id ?? 1;
        $ruta_id = DB::table('logistica.ruta')->first()->ruta_id ?? 1;

        // 1. LOTES DE CAMPO (solo columnas existentes)
        echo "  📍 Creando lotes de campo...\n";
        
        $lotesCampo = [];
        for ($i = 1; $i <= 3; $i++) {
            $id = DB::table('campo.lotecampo')->insertGetId([
                'codigo_lote_campo' => "LC-2024-00$i",
                'productor_id' => $productor_id,
                'variedad_id' => $variedad_id,
                'fecha_siembra' => date('Y-m-d', strtotime("-4 months +$i days")),
                'fecha_cosecha' => date('Y-m-d', strtotime("-1 week +$i days")),
                'superficie_ha' => rand(25, 45) / 10
            ], 'lote_campo_id');
            $lotesCampo["LC-2024-00$i"] = $id;
        }

        // 2. LOTES DE PLANTA
        echo "  🏭 Creando lotes de planta...\n";
        
        $lotesPlanta = [];
        $lpId1 = DB::table('planta.loteplanta')->insertGetId([
            'codigo_lote_planta' => 'LP-2024-001',
            'planta_id' => $planta_id,
            'fecha_inicio' => date('Y-m-d', strtotime("-5 days")),
            'rendimiento_pct' => 78
        ], 'lote_planta_id');
        $lotesPlanta['LP-2024-001'] = $lpId1;

        // Asociar lote de campo
        DB::table('planta.loteplanta_entradacampo')->insert([
            'lote_planta_id' => $lpId1,
            'lote_campo_id' => $lotesCampo['LC-2024-001'],
            'peso_entrada_t' => 15.5
        ]);

        $lpId2 = DB::table('planta.loteplanta')->insertGetId([
            'codigo_lote_planta' => 'LP-2024-002',
            'planta_id' => $planta_id,
            'fecha_inicio' => date('Y-m-d', strtotime("-3 days")),
            'rendimiento_pct' => 82
        ], 'lote_planta_id');
        $lotesPlanta['LP-2024-002'] = $lpId2;

        DB::table('planta.loteplanta_entradacampo')->insert([
            'lote_planta_id' => $lpId2,
            'lote_campo_id' => $lotesCampo['LC-2024-002'],
            'peso_entrada_t' => 12.3
        ]);

        // 3. LOTES DE SALIDA
        echo "  📦 Creando lotes de salida...\n";
        
        $lotesSalida = [];
        $lsId1 = DB::table('planta.lotesalida')->insertGetId([
            'codigo_lote_salida' => 'LS-2024-001',
            'lote_planta_id' => $lpId1,
            'sku' => 'Papa lavada 25kg',
            'peso_t' => 12.0,
            'fecha_empaque' => date('Y-m-d H:i:s', strtotime("-4 days"))
        ], 'lote_salida_id');
        $lotesSalida['LS-2024-001'] = $lsId1;

        $lsId2 = DB::table('planta.lotesalida')->insertGetId([
            'codigo_lote_salida' => 'LS-2024-002',
            'lote_planta_id' => $lpId2,
            'sku' => 'Papa seleccionada 50kg',
            'peso_t' => 10.5,
            'fecha_empaque' => date('Y-m-d H:i:s', strtotime("-2 days"))
        ], 'lote_salida_id');
        $lotesSalida['LS-2024-002'] = $lsId2;

        // 4. ENVÍOS
        echo "  🚛 Creando envíos...\n";
        
        $envId1 = DB::table('logistica.envio')->insertGetId([
            'codigo_envio' => 'ENV-2024-001',
            'ruta_id' => $ruta_id,
            'transportista_id' => $transportista_id,
            'fecha_salida' => date('Y-m-d H:i:s', strtotime("-3 days")),
            'estado' => 'COMPLETADO',
            'almacen_origen_id' => $almacen_id
        ], 'envio_id');

        DB::table('logistica.enviodetalle')->insert([
            'envio_id' => $envId1,
            'lote_salida_id' => $lsId1,
            'cliente_id' => $cliente_id,
            'cantidad_t' => 12.0
        ]);

        $envId2 = DB::table('logistica.envio')->insertGetId([
            'codigo_envio' => 'ENV-2024-002',
            'ruta_id' => $ruta_id,
            'transportista_id' => $transportista_id,
            'fecha_salida' => date('Y-m-d H:i:s', strtotime("-1 day")),
            'estado' => 'EN_RUTA',
            'almacen_origen_id' => $almacen_id
        ], 'envio_id');

        DB::table('logistica.enviodetalle')->insert([
            'envio_id' => $envId2,
            'lote_salida_id' => $lsId2,
            'cliente_id' => $cliente_id,
            'cantidad_t' => 10.5
        ]);

        // 5. PEDIDOS
        echo "  📄 Creando pedidos...\n";
        
        DB::table('comercial.pedido')->insert([
            'codigo_pedido' => 'PED-2024-001',
            'cliente_id' => $cliente_id,
            'fecha_pedido' => date('Y-m-d H:i:s', strtotime("-5 days")),
            'estado' => 'COMPLETADO'
        ]);

        DB::table('comercial.pedido')->insert([
            'codigo_pedido' => 'PED-2024-002',
            'cliente_id' => $cliente_id,
            'fecha_pedido' => date('Y-m-d H:i:s', strtotime("-2 days")),
            'estado' => 'PENDIENTE'
        ]);

        echo "\n✅ Datos creados:\n";
        echo "   - 3 Lotes de Campo (LC-2024-001 a 003)\n";
        echo "   - 2 Lotes de Planta (LP-2024-001 a 002)\n";
        echo "   - 2 Lotes de Salida (LS-2024-001 a 002)\n";
        echo "   - 2 Envíos (ENV-2024-001 a 002)\n";
        echo "   - 2 Pedidos (PED-2024-001 a 002)\n";
        echo "\n🔍 Prueba buscando: LC-2024-001\n";
    }
}
