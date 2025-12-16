<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            DemoDataSeeder::class,
            LogisticaRutasSeeder::class,
            AlmacenesCompletosSeeder::class,
            TrazabilidadCompletaSeeder::class, // <--- Crea lotes base y limpia anteriores
            ProduccionSeeder::class,         // <--- Añade datos a los lotes creados arriba
            CertificacionesSeeder::class,    // <--- Añade certificados
            AlmacenTransaccionalSeeder::class, // <--- Añade inventario
            DashboardSeeder::class,          // <--- Rellena gráficos e históricos (Dashboard)
            // TrazabilidadDemoSeeder::class, // <--- Redundante con TrazabilidadCompleta, causa duplicados
        ]);
    }
}
