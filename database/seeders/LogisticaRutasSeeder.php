<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogisticaRutasSeeder extends Seeder
{
    public function run(): void
    {
        echo "🛣️ Creando rutas de logística...\n";

        // Rutas principales
        $rutas = [
            [
                'codigo_ruta' => 'R-LPZ-001',
                'descripcion' => 'Ruta Altiplano Norte: La Paz - El Alto - Copacabana'
            ],
            [
                'codigo_ruta' => 'R-CBB-001',
                'descripcion' => 'Ruta Valle Bajo: Cochabamba - Quillacollo - Vinto'
            ],
            [
                'codigo_ruta' => 'R-SCZ-001',
                'descripcion' => 'Ruta Norte Integrado: Santa Cruz - Montero'
            ],
            [
                'codigo_ruta' => 'R-PTS-001',
                'descripcion' => 'Ruta Potosí - Sucre'
            ]
        ];

        // Obtener clientes para usar como puntos
        $clientes = DB::table('cat.cliente')->pluck('cliente_id')->toArray();

        if (empty($clientes)) {
            echo "⚠️ No hay clientes para asignar a rutas. Saltando creación de puntos...\n";
            // We can still create routes even if there are no clients for points
        }

        foreach ($rutas as $r) {
            // Usar updateOrInsert para la ruta
            DB::table('logistica.ruta')->updateOrInsert(
                ['codigo_ruta' => $r['codigo_ruta']],
                ['descripcion' => $r['descripcion']]
            );

            $rutaId = DB::table('logistica.ruta')->where('codigo_ruta', $r['codigo_ruta'])->value('ruta_id');

            // Limpiar puntos existentes para esta ruta para evitar duplicados al re-correr el seeder
            DB::table('logistica.rutapunto')->where('ruta_id', $rutaId)->delete();

            if (!empty($clientes)) {
                // Asignar 3 puntos aleatorios (clientes) a cada ruta
                // Ensure we don't try to pick more clients than available
                $numClientsToPick = min(3, count($clientes));

                // Use collect() to enable random() method on the array
                $puntosCliente = collect($clientes)->random($numClientsToPick);

                // If only one client is picked, random() returns the item directly, not a collection.
                // Ensure $puntosCliente is always iterable.
                if ($numClientsToPick === 1) {
                    $puntosCliente = [$puntosCliente];
                }

                $orden = 1;

                foreach ($puntosCliente as $clienteId) {
                    DB::table('logistica.rutapunto')->insert([
                        'ruta_id' => $rutaId,
                        'orden' => $orden++,
                        'cliente_id' => $clienteId
                    ]);
                }
            }
        }

        echo "✓ Rutas y puntos creados correctamente\n";
    }
}
