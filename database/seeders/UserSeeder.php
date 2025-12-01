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
        User::create([
            'name' => 'Admin AgroPapas',
            'email' => 'admin@agropapas.com',
            'password' => Hash::make('password123'),
        ]);

        echo "Usuario creado: admin@agropapas.com / password123\n";
    }
}
