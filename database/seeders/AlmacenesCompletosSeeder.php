<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmacenesCompletosSeeder extends Seeder
{
    /**
     * Seeder completo para almacenes, zonas, ubicaciones y vehículos
     */
    public function run(): void
    {
        echo "🏭 Actualizando almacenes con datos completos...\n";

        // 1. Actualizar almacenes existentes con nuevos campos
        DB::table('cat.almacen')->where('codigo_almacen', 'ALM-LPZ')->update([
            'capacidad_total_t' => 500,
            'capacidad_disponible_t' => 320,
            'tipo' => 'CENTRAL',
            'estado' => 'ACTIVO',
            'temperatura_min_c' => 2,
            'temperatura_max_c' => 8,
            'num_zonas' => 3,
            'telefono' => '+591 2 2456789',
            'email' => 'almacen.lpz@agropapas.com',
            'responsable' => 'Carlos Mamani',
            'horario_operacion' => '06:00-22:00'
        ]);

        DB::table('cat.almacen')->where('codigo_almacen', 'ALM-CBB')->update([
            'capacidad_total_t' => 350,
            'capacidad_disponible_t' => 200,
            'tipo' => 'DISTRIBUCION',
            'estado' => 'ACTIVO',
            'temperatura_min_c' => 4,
            'temperatura_max_c' => 12,
            'num_zonas' => 2,
            'telefono' => '+591 4 4567890',
            'email' => 'almacen.cbb@agropapas.com',
            'responsable' => 'María Condori',
            'horario_operacion' => '07:00-20:00'
        ]);

        DB::table('cat.almacen')->where('codigo_almacen', 'ALM-SCZ')->update([
            'capacidad_total_t' => 750,
            'capacidad_disponible_t' => 450,
            'tipo' => 'REFRIGERADO',
            'estado' => 'ACTIVO',
            'temperatura_min_c' => 0,
            'temperatura_max_c' => 5,
            'num_zonas' => 4,
            'telefono' => '+591 3 3456789',
            'email' => 'almacen.scz@agropapas.com',
            'responsable' => 'Roberto Flores',
            'horario_operacion' => '00:00-23:59'
        ]);

        // Obtener IDs de almacenes
        $almLpz = DB::table('cat.almacen')->where('codigo_almacen', 'ALM-LPZ')->first();
        $almCbb = DB::table('cat.almacen')->where('codigo_almacen', 'ALM-CBB')->first();
        $almScz = DB::table('cat.almacen')->where('codigo_almacen', 'ALM-SCZ')->first();

        // 2. Crear vehículos
        echo "🚛 Creando flota de vehículos...\n";
        
        $vehiculos = [
            [
                'codigo_vehiculo' => 'VEH-001',
                'placa' => '1234-ABC',
                'marca' => 'Volvo',
                'modelo' => 'FH16',
                'anio' => 2020,
                'color' => 'Blanco',
                'capacidad_t' => 25,
                'tipo' => 'REFRIGERADO',
                'estado' => 'DISPONIBLE',
                'kilometraje' => 85000
            ],
            [
                'codigo_vehiculo' => 'VEH-002',
                'placa' => '5678-DEF',
                'marca' => 'Mercedes-Benz',
                'modelo' => 'Actros',
                'anio' => 2019,
                'color' => 'Azul',
                'capacidad_t' => 20,
                'tipo' => 'CAMION',
                'estado' => 'DISPONIBLE',
                'kilometraje' => 120000
            ],
            [
                'codigo_vehiculo' => 'VEH-003',
                'placa' => '9012-GHI',
                'marca' => 'Scania',
                'modelo' => 'R500',
                'anio' => 2021,
                'color' => 'Rojo',
                'capacidad_t' => 30,
                'tipo' => 'REFRIGERADO',
                'estado' => 'DISPONIBLE',
                'kilometraje' => 45000
            ],
            [
                'codigo_vehiculo' => 'VEH-004',
                'placa' => '3456-JKL',
                'marca' => 'Hyundai',
                'modelo' => 'HD78',
                'anio' => 2022,
                'color' => 'Blanco',
                'capacidad_t' => 8,
                'tipo' => 'FURGON',
                'estado' => 'DISPONIBLE',
                'kilometraje' => 25000
            ]
        ];

        foreach ($vehiculos as $v) {
            DB::table('cat.vehiculo')->updateOrInsert(
                ['codigo_vehiculo' => $v['codigo_vehiculo']],
                $v
            );
        }

        // Asignar vehículos a transportistas
        $transportistas = DB::table('cat.transportista')->get();
        $vehiculoIds = DB::table('cat.vehiculo')->pluck('vehiculo_id')->toArray();
        
        foreach ($transportistas as $i => $t) {
            if (isset($vehiculoIds[$i])) {
                DB::table('cat.transportista')
                    ->where('transportista_id', $t->transportista_id)
                    ->update(['vehiculo_asignado_id' => $vehiculoIds[$i]]);
            }
        }

        // 3. Crear zonas para almacén La Paz
        echo "📦 Creando zonas de almacenamiento...\n";
        
        if ($almLpz) {
            $zonasLpz = [
                ['codigo' => 'LPZ-ZA', 'nombre' => 'Zona A - Refrigerado', 'tipo' => 'REFRIGERADO', 'cap' => 180, 'temp' => 4, 'pasillos' => 3, 'racks' => 5, 'niveles' => 4],
                ['codigo' => 'LPZ-ZB', 'nombre' => 'Zona B - Seco', 'tipo' => 'SECO', 'cap' => 200, 'temp' => null, 'pasillos' => 4, 'racks' => 5, 'niveles' => 3],
                ['codigo' => 'LPZ-ZC', 'nombre' => 'Zona C - Cuarentena', 'tipo' => 'CUARENTENA', 'cap' => 50, 'temp' => null, 'pasillos' => 1, 'racks' => 3, 'niveles' => 2]
            ];

            foreach ($zonasLpz as $z) {
                $zonaId = DB::table('almacen.zona')->insertGetId([
                    'almacen_id' => $almLpz->almacen_id,
                    'codigo_zona' => $z['codigo'],
                    'nombre' => $z['nombre'],
                    'tipo' => $z['tipo'],
                    'capacidad_t' => $z['cap'],
                    'ocupacion_actual_t' => 0,
                    'temperatura_objetivo_c' => $z['temp'],
                    'estado' => 'DISPONIBLE',
                    'num_pasillos' => $z['pasillos'],
                    'num_racks_por_pasillo' => $z['racks'],
                    'num_niveles' => $z['niveles']
                ], 'zona_id');

                // Crear ubicaciones para esta zona
                $this->crearUbicaciones($zonaId, $z['pasillos'], $z['racks'], $z['niveles'], $z['tipo'] === 'REFRIGERADO');
            }
        }

        // 4. Crear zonas para almacén SCZ
        if ($almScz) {
            $zonasScz = [
                ['codigo' => 'SCZ-ZA', 'nombre' => 'Zona A - Congelado', 'tipo' => 'CONGELADO', 'cap' => 200, 'temp' => -18, 'pasillos' => 2, 'racks' => 6, 'niveles' => 4],
                ['codigo' => 'SCZ-ZB', 'nombre' => 'Zona B - Refrigerado', 'tipo' => 'REFRIGERADO', 'cap' => 300, 'temp' => 2, 'pasillos' => 4, 'racks' => 6, 'niveles' => 4],
                ['codigo' => 'SCZ-ZC', 'nombre' => 'Zona C - Seco', 'tipo' => 'SECO', 'cap' => 150, 'temp' => null, 'pasillos' => 2, 'racks' => 4, 'niveles' => 3],
                ['codigo' => 'SCZ-ZD', 'nombre' => 'Zona D - Despacho', 'tipo' => 'SECO', 'cap' => 100, 'temp' => null, 'pasillos' => 2, 'racks' => 3, 'niveles' => 2]
            ];

            foreach ($zonasScz as $z) {
                $zonaId = DB::table('almacen.zona')->insertGetId([
                    'almacen_id' => $almScz->almacen_id,
                    'codigo_zona' => $z['codigo'],
                    'nombre' => $z['nombre'],
                    'tipo' => $z['tipo'],
                    'capacidad_t' => $z['cap'],
                    'ocupacion_actual_t' => 0,
                    'temperatura_objetivo_c' => $z['temp'],
                    'estado' => 'DISPONIBLE',
                    'num_pasillos' => $z['pasillos'],
                    'num_racks_por_pasillo' => $z['racks'],
                    'num_niveles' => $z['niveles']
                ], 'zona_id');

                $this->crearUbicaciones($zonaId, $z['pasillos'], $z['racks'], $z['niveles'], in_array($z['tipo'], ['REFRIGERADO', 'CONGELADO']));
            }
        }

        echo "\n✅ Datos de almacenes creados:\n";
        echo "   └─ 3 Almacenes actualizados con nuevos campos\n";
        echo "   └─ 4 Vehículos creados y asignados\n";
        echo "   └─ 7 Zonas de almacenamiento\n";
        echo "   └─ Múltiples ubicaciones (racks) por zona\n";
    }

    /**
     * Crear ubicaciones (racks) para una zona
     */
    private function crearUbicaciones(int $zonaId, int $pasillos, int $racks, int $niveles, bool $refrigerado): void
    {
        $zona = DB::table('almacen.zona')->where('zona_id', $zonaId)->first();
        $capacidadPorUbicacion = round($zona->capacidad_t / ($pasillos * $racks * $niveles), 3);

        $letras = ['A', 'B', 'C', 'D', 'E', 'F'];
        
        for ($p = 0; $p < $pasillos; $p++) {
            for ($r = 1; $r <= $racks; $r++) {
                for ($n = 1; $n <= $niveles; $n++) {
                    $pasillo = $letras[$p];
                    $codigo = "{$zona->codigo_zona}-{$pasillo}-" . str_pad($r, 2, '0', STR_PAD_LEFT) . "-{$n}";
                    
                    DB::table('almacen.ubicacion')->insert([
                        'zona_id' => $zonaId,
                        'codigo_ubicacion' => $codigo,
                        'pasillo' => $pasillo,
                        'rack' => $r,
                        'nivel' => $n,
                        'capacidad_t' => $capacidadPorUbicacion,
                        'ocupado' => false,
                        'refrigerado' => $refrigerado,
                        'acceso_montacargas' => $n <= 2 // Niveles 1-2 accesibles con montacargas
                    ]);
                }
            }
        }
    }
}
