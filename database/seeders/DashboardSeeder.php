<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        echo "📊 Generando datos históricos para el Dashboard (6 meses)...\n";

        $faker = \Faker\Factory::create('es_ES');

        // RECURSOS BASE
        $plantas = DB::table('cat.planta')->pluck('planta_id');
        $productores = DB::table('campo.productor')->pluck('productor_id');
        $variedades = DB::table('cat.variedadpapa')->pluck('variedad_id');
        $clientes = DB::table('cat.cliente')->get();
        $transportistas = DB::table('cat.transportista')->pluck('transportista_id');
        $rutas = DB::table('logistica.ruta')->pluck('ruta_id');
        $almacenes = DB::table('cat.almacen')->pluck('almacen_id');

        if ($plantas->isEmpty() || $clientes->isEmpty()) {
            echo "⚠️ Faltan catálogos base. Ejecuta DemoDataSeeder primero.\n";
            return;
        }

        // 1. GENERAR HISTÓRICO DE PRODUCCIÓN (Lotes Campo -> Planta -> Salida)
        echo "  🏭 Generando 40 cadenas de producción históricas...\n";

        for ($i = 0; $i < 40; $i++) {
            // Fecha aleatoria últimos 6 meses
            $fechaInicio = Carbon::now()->subDays(rand(1, 180));

            // Lote Campo
            $lcId = DB::table('campo.lotecampo')->insertGetId([
                'codigo_lote_campo' => 'LC-HIST-' . $i . '-' . rand(100, 999),
                'productor_id' => $productores->random(),
                'variedad_id' => $variedades->random(),
                'fecha_siembra' => $fechaInicio->copy()->subMonths(4),
                'fecha_cosecha' => $fechaInicio->copy()->subDays(2),
                'superficie_ha' => rand(2, 10)
                // 'created_at' => $fechaInicio, // Table has no timestamps
                // 'updated_at' => $fechaInicio
            ], 'lote_campo_id');

            // Lote Planta
            $lpId = DB::table('planta.loteplanta')->insertGetId([
                'codigo_lote_planta' => 'LP-HIST-' . $i . '-' . rand(100, 999),
                'planta_id' => $plantas->random(),
                'fecha_inicio' => $fechaInicio,
                'rendimiento_pct' => rand(70, 95)
                // 'created_at' => $fechaInicio,
                // 'updated_at' => $fechaInicio
            ], 'lote_planta_id');

            // Relación Entrada
            DB::table('planta.loteplanta_entradacampo')->insert([
                'lote_planta_id' => $lpId,
                'lote_campo_id' => $lcId,
                'peso_entrada_t' => rand(10, 50)
            ]);

            // Lote Salida (Producto Terminado)
            // Generar 1 o 2 salidas por lote planta
            $numSalidas = rand(1, 2);
            for ($j = 0; $j < $numSalidas; $j++) {
                DB::table('planta.lotesalida')->insert([
                    'codigo_lote_salida' => 'LS-HIST-' . $i . '-' . $j . '-' . rand(100, 999),
                    'lote_planta_id' => $lpId,
                    'sku' => rand(0, 1) ? 'Papa Lavada 25kg' : 'Papa Industrial 1tn',
                    'peso_t' => rand(5, 20),
                    'fecha_empaque' => $fechaInicio->copy()->addDays(rand(1, 3))
                    // 'created_at' => $fechaInicio,
                    // 'updated_at' => $fechaInicio
                ]);
            }
        }

        // 2. GENERAR ENVÍOS HISTÓRICOS (Logística)
        echo "  🚛 Generando 30 envíos históricos...\n";

        for ($i = 0; $i < 30; $i++) {
            $fechaSalida = Carbon::now()->subDays(rand(1, 180));
            $estado = $faker->randomElement(['ENTREGADO', 'ENTREGADO', 'ENTREGADO', 'CANCELADO']); // Mayoría entregados
            if ($fechaSalida->diffInDays(now()) < 5)
                $estado = 'EN_RUTA'; // Recientes en ruta

            DB::table('logistica.envio')->insert([
                'codigo_envio' => 'ENV-HIST-' . $i . '-' . rand(1000, 9999),
                'ruta_id' => $rutas->random(),
                'transportista_id' => $transportistas->random(),
                'fecha_salida' => $fechaSalida,
                'fecha_llegada' => $estado == 'ENTREGADO' ? $fechaSalida->copy()->addDays(rand(1, 4)) : null,
                'estado' => $estado,
                'almacen_origen_id' => $almacenes->random()
                // 'created_at' => $fechaSalida,
                // 'updated_at' => $fechaSalida
            ]);
        }

        // 3. GENERAR PEDIDOS Y VENTAS (Comercial)
        echo "  🛒 Generando 60 pedidos históricos (Ventas)....\n";

        for ($i = 0; $i < 60; $i++) {
            $fecha = Carbon::now()->subDays(rand(0, 180));
            $cliente = $clientes->random();

            $estado = match (rand(1, 10)) {
                1 => 'PENDIENTE',
                2 => 'CANCELADO',
                default => 'COMPLETADO'
            };

            $pedidoId = DB::table('comercial.pedido')->insertGetId([
                'codigo_pedido' => 'PED-HIST-' . $i . '-' . rand(1000, 9999),
                'cliente_id' => $cliente->cliente_id,
                'fecha_pedido' => $fecha,
                // 'fecha_entrega_estimada' => $fecha->copy()->addDays(rand(2, 5)), // Column missing
                'estado' => $estado
                // 'total_usd' => 0, // Column missing
                // 'observaciones' => $faker->sentence(), // Column missing
                // 'created_at' => $fecha,
                // 'updated_at' => $fecha
            ], 'pedido_id');

            // Detalles
            $cantidad = rand(5, 50);
            $precio = rand(300, 600);
            // $subtotal = $cantidad * $precio; // Calculated on read

            DB::table('comercial.pedidodetalle')->insert([
                'pedido_id' => $pedidoId,
                'sku' => rand(0, 1) ? 'Papa Lavada 25kg' : 'Papa Industrial 1tn',
                'cantidad_t' => $cantidad,
                'precio_unit_usd' => $precio
                // 'subtotal' => $subtotal // Column missing
            ]);

            // Total USD is calculated on the fly in controller, no column in table.
            // DB::table('comercial.pedido')->where('pedido_id', $pedidoId)->update(['total_usd' => $subtotal]);
        }

        echo "✓ Datos históricos generados exitosamente.\n";
    }
}
