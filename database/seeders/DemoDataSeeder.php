<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Seed simplificado de datos de prueba...\n\n";

        // 1. CATÁLOGOS BASE
        echo "📋 Creando catálogos...\n";
        
        // Departamentos
        DB::table('cat.departamento')->updateOrInsert(['nombre' => 'La Paz'], ['nombre' => 'La Paz']);
        DB::table('cat.departamento')->updateOrInsert(['nombre' => 'Cochabamba'], ['nombre' => 'Cochabamba']);
        DB::table('cat.departamento')->updateOrInsert(['nombre' => 'Potosí'], ['nombre' => 'Potosí']);
        DB::table('cat.departamento')->updateOrInsert(['nombre' => 'Oruro'], ['nombre' => 'Oruro']);
        
        $laPaz = DB::table('cat.departamento')->where('nombre', 'La Paz')->first()->departamento_id;
        $cbba = DB::table('cat.departamento')->where('nombre', 'Cochabamba')->first()->departamento_id;
        $potosi = DB::table('cat.departamento')->where('nombre', 'Potosí')->first()->departamento_id;
        $oruro = DB::table('cat.departamento')->where('nombre', 'Oruro')->first()->departamento_id;
        
        // Municipios
        DB::table('cat.municipio')->updateOrInsert(['nombre' => 'La Paz', 'departamento_id' => $laPaz], ['nombre' => 'La Paz', 'departamento_id' => $laPaz]);
        DB::table('cat.municipio')->updateOrInsert(['nombre' => 'El Alto', 'departamento_id' => $laPaz], ['nombre' => 'El Alto', 'departamento_id' => $laPaz]);
        DB::table('cat.municipio')->updateOrInsert(['nombre' => 'Cochabamba', 'departamento_id' => $cbba], ['nombre' => 'Cochabamba', 'departamento_id' => $cbba]);
        DB::table('cat.municipio')->updateOrInsert(['nombre' => 'Quillacollo', 'departamento_id' => $cbba], ['nombre' => 'Quillacollo', 'departamento_id' => $cbba]);
        DB::table('cat.municipio')->updateOrInsert(['nombre' => 'Potosí', 'departamento_id' => $potosi], ['nombre' => 'Potosí', 'departamento_id' => $potosi]);
        DB::table('cat.municipio')->updateOrInsert(['nombre' => 'Oruro', 'departamento_id' => $oruro], ['nombre' => 'Oruro', 'departamento_id' => $oruro]);
        
        // Variedades
        DB::table('cat.variedadpapa')->updateOrInsert(['codigo_variedad' => 'HUA'], ['codigo_variedad' => 'HUA', 'nombre_comercial' => 'Huaycha', 'aptitud' => 'CONSUMO_FRESCO', 'ciclo_dias_min' => 120, 'ciclo_dias_max' => 150]);
        DB::table('cat.variedadpapa')->updateOrInsert(['codigo_variedad' => 'WAY'], ['codigo_variedad' => 'WAY', 'nombre_comercial' => "Waych'a", 'aptitud' => 'INDUSTRIAL', 'ciclo_dias_min' => 130, 'ciclo_dias_max' => 160]);
        DB::table('cat.variedadpapa')->updateOrInsert(['codigo_variedad' => 'DES'], ['codigo_variedad' => 'DES', 'nombre_comercial' => 'Desirée', 'aptitud' => 'CONSUMO_FRESCO', 'ciclo_dias_min' => 110, 'ciclo_dias_max' => 140]);
        DB::table('cat.variedadpapa')->updateOrInsert(['codigo_variedad' => 'ALF'], ['codigo_variedad' => 'ALF', 'nombre_comercial' => 'Alpha', 'aptitud' => 'INDUSTRIAL', 'ciclo_dias_min' => 140, 'ciclo_dias_max' => 170]);
        
        $munPotosi = DB::table('cat.municipio')->where('nombre', 'Potosí')->first()->municipio_id;
        $munOruro = DB::table('cat.municipio')->where('nombre', 'Oruro')->first()->municipio_id;
        $munCochabamba = DB::table('cat.municipio')->where('nombre', 'Cochabamba')->first()->municipio_id;
        $munLaPaz = DB::table('cat.municipio')->where('nombre', 'La Paz')->first()->municipio_id;
        $munElAlto = DB::table('cat.municipio')->where('nombre', 'El Alto')->first()->municipio_id;
        
        // Plantas
        DB::table('cat.planta')->updateOrInsert(['codigo_planta' => 'PLT-PT-01'], ['codigo_planta' => 'PLT-PT-01', 'nombre' => 'Planta Potosí - Principal', 'municipio_id' => $munPotosi, 'direccion' => 'Av. Industrial km 5']);
        DB::table('cat.planta')->updateOrInsert(['codigo_planta' => 'PLT-OR-01'], ['codigo_planta' => 'PLT-OR-01', 'nombre' => 'Planta Oruro - Norte', 'municipio_id' => $munOruro, 'direccion' => 'Zona Norte Industrial']);
        DB::table('cat.planta')->updateOrInsert(['codigo_planta' => 'PLT-CB-01'], ['codigo_planta' => 'PLT-CB-01', 'nombre' => 'Planta Cochabamba - Valle', 'municipio_id' => $munCochabamba, 'direccion' => 'Valle Alto s/n']);
        
        // Almacenes
        DB::table('cat.almacen')->updateOrInsert(['codigo_almacen' => 'ALM-LP-01'], ['codigo_almacen' => 'ALM-LP-01', 'nombre' => 'Almacén La Paz Centro', 'municipio_id' => $munLaPaz, 'direccion' => 'Av. Buenos Aires 1234']);
        DB::table('cat.almacen')->updateOrInsert(['codigo_almacen' => 'ALM-CB-01'], ['codigo_almacen' => 'ALM-CB-01', 'nombre' => 'Almacén Cochabamba Valle', 'municipio_id' => $munCochabamba, 'direccion' => 'Av. Ayacucho 567']);
        DB::table('cat.almacen')->updateOrInsert(['codigo_almacen' => 'ALM-EA-01'], ['codigo_almacen' => 'ALM-EA-01', 'nombre' => 'Almacén El Alto Industrial', 'municipio_id' => $munElAlto, 'direccion' => 'Ciudad Satélite Mz A']);
        
        // Clientes
        DB::table('cat.cliente')->updateOrInsert(['codigo_cliente' => 'CLI-001'], ['codigo_cliente' => 'CLI-001', 'nombre' => 'Supermercados ABC', 'tipo' => 'RETAIL', 'municipio_id' => $munLaPaz, 'direccion' => 'Av. Arce 2345']);
        DB::table('cat.cliente')->updateOrInsert(['codigo_cliente' => 'CLI-002'], ['codigo_cliente' => 'CLI-002', 'nombre' => 'Distribuidora La Económica', 'tipo' => 'MAYORISTA', 'municipio_id' => $munElAlto, 'direccion' => 'Calle 1 #123']);
        DB::table('cat.cliente')->updateOrInsert(['codigo_cliente' => 'CLI-003'], ['codigo_cliente' => 'CLI-003', 'nombre' => 'Procesadora de Alimentos SRL', 'tipo' => 'PROCESADOR', 'municipio_id' => $munCochabamba, 'direccion' => 'Av. Blanco Galindo km 8']);
        DB::table('cat.cliente')->updateOrInsert(['codigo_cliente' => 'CLI-004'], ['codigo_cliente' => 'CLI-004', 'nombre' => 'Mercado Campesino', 'tipo' => 'MAYORISTA', 'municipio_id' => $munLaPaz, 'direccion' => 'Plaza del Agricultor']);
        
        // Transport istas
        DB::table('cat.transportista')->updateOrInsert(['codigo_transp' => 'TRN-001'], ['codigo_transp' => 'TRN-001', 'nombre' => 'Transportes Andinos SRL', 'nro_licencia' => 'LIC-2024-001']);
        DB::table('cat.transportista')->updateOrInsert(['codigo_transp' => 'TRN-002'], ['codigo_transp' => 'TRN-002', 'nombre' => 'Logística del Valle', 'nro_licencia' => 'LIC-2024-002']);
        DB::table('cat.transportista')->updateOrInsert(['codigo_transp' => 'TRN-003'], ['codigo_transp' => 'TRN-003', 'nombre' => 'Transporte Urbano Express', 'nro_licencia' => 'LIC-2024-003']);
        
        echo "✓ Catálogos creados\n\n";

        // 2. PRODUCTORES Y CAMPO
        echo "👨‍🌾 Creando productores y lotes...\n";
        
        DB::table('campo.productor')->updateOrInsert(['codigo_productor' => 'PROD-001'], ['codigo_productor' => 'PROD-001', 'nombre' => 'Juan Pérez Mamani', 'municipio_id' => $munPotosi, 'telefono' => '71234567']);
        DB::table('campo.productor')->updateOrInsert(['codigo_productor' => 'PROD-002'], ['codigo_productor' => 'PROD-002', 'nombre' => 'María González Quispe', 'municipio_id' => $munPotosi, 'telefono' => '72345678']);
        DB::table('campo.productor')->updateOrInsert(['codigo_productor' => 'PROD-003'], ['codigo_productor' => 'PROD-003', 'nombre' => 'Pedro Condori', 'municipio_id' => $munOruro, 'telefono' => '73456789']);
        DB::table('campo.productor')->updateOrInsert(['codigo_productor' => 'PROD-004'], ['codigo_productor' => 'PROD-004', 'nombre' => 'Ana Flores', 'municipio_id' => $munCochabamba, 'telefono' => '74567890']);
        
        $prod1 = DB::table('campo.productor')->where('codigo_productor', 'PROD-001')->first()->productor_id;
        $prod2 = DB::table('campo.productor')->where('codigo_productor', 'PROD-002')->first()->productor_id;
        $prod3 = DB::table('campo.productor')->where('codigo_productor', 'PROD-003')->first()->productor_id;
        $prod4 = DB::table('campo.productor')->where('codigo_productor', 'PROD-004')->first()->productor_id;
        
        $var1 = DB::table('cat.variedadpapa')->where('codigo_variedad', 'HUA')->first()->variedad_id;
        $var2 = DB::table('cat.variedadpapa')->where('codigo_variedad', 'WAY')->first()->variedad_id;
        $var3 = DB::table('cat.variedadpapa')->where('codigo_variedad', 'DES')->first()->variedad_id;
        $var4 = DB::table('cat.variedadpapa')->where('codigo_variedad', 'ALF')->first()->variedad_id;
        
        // Lotes de Campo
        DB::table('campo.lotecampo')->updateOrInsert(['codigo_lote_campo' => 'LC-2024-001'], ['codigo_lote_campo' => 'LC-2024-001', 'productor_id' => $prod1, 'variedad_id' => $var1, 'superficie_ha' => 2.5, 'fecha_siembra' => '2024-09-15', 'fecha_cosecha' => '2024-12-28', 'humedad_suelo_pct' => 65.0]);
        DB::table('campo.lotecampo')->updateOrInsert(['codigo_lote_campo' => 'LC-2024-002'], ['codigo_lote_campo' => 'LC-2024-002', 'productor_id' => $prod1, 'variedad_id' => $var2, 'superficie_ha' => 3.0, 'fecha_siembra' => '2024-09-20', 'fecha_cosecha' => '2024-12-30', 'humedad_suelo_pct' => 68.0]);
        DB::table('campo.lotecampo')->updateOrInsert(['codigo_lote_campo' => 'LC-2024-003'], ['codigo_lote_campo' => 'LC-2024-003', 'productor_id' => $prod2, 'variedad_id' => $var1, 'superficie_ha' => 1.8, 'fecha_siembra' => '2024-09-10', 'fecha_cosecha' => '2024-12-25', 'humedad_suelo_pct' => 70.0]);
        DB::table('campo.lotecampo')->updateOrInsert(['codigo_lote_campo' => 'LC-2024-004'], ['codigo_lote_campo' => 'LC-2024-004', 'productor_id' => $prod3, 'variedad_id' => $var3, 'superficie_ha' => 4.2, 'fecha_siembra' => '2024-09-25', 'fecha_cosecha' => '2024-12-29', 'humedad_suelo_pct' => 62.0]);
        DB::table('campo.lotecampo')->updateOrInsert(['codigo_lote_campo' => 'LC-2024-005'], ['codigo_lote_campo' => 'LC-2024-005', 'productor_id' => $prod4, 'variedad_id' => $var4, 'superficie_ha' => 3.5, 'fecha_siembra' => '2024-09-18', 'fecha_cosecha' => '2024-12-27', 'humedad_suelo_pct' => 66.0]);

        echo "✓ Campo creado\n\n";
        echo "✅ Seed completado!\n";
        echo "💡 Usa los formularios de transacciones para crear lotes de planta, envíos y pedidos\n";
    }
}
