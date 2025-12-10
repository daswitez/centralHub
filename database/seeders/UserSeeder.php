<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador de prueba
        $user = User::firstOrCreate(
            ['email' => 'admin@centralhub.com'],
            [
                'name' => 'Admin CentralHub',
                'password' => Hash::make('password123'),
            ]
        );

        // Asignar rol de admin si no lo tiene
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        echo "Usuario creado: admin@centralhub.com / password123 (ROL: admin)\n";
    }
}
