<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        // Permisos
        $permissions = [
            'crear_solicitudes',
            'ver_solicitudes',
            'responder_solicitudes',
            'gestionar_conductores',
            'ver_conductores',
            'ver_trazabilidad',
            'gestionar_planta',
            'gestionar_almacen',
            'gestionar_campo',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }

        // Roles
        $rolePlanta = Role::firstOrCreate([
            'name' => 'planta',
            'guard_name' => $guard,
        ]);

        $roleProductor = Role::firstOrCreate([
            'name' => 'productor',
            'guard_name' => $guard,
        ]);

        $roleConductor = Role::firstOrCreate([
            'name' => 'conductor',
            'guard_name' => $guard,
        ]);

        $roleAdmin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guard,
        ]);

        // Asignar permisos
        $rolePlanta->syncPermissions([
            'crear_solicitudes',
            'ver_solicitudes',
            'gestionar_planta',
            'ver_trazabilidad',
            'ver_conductores',
        ]);

        $roleProductor->syncPermissions([
            'responder_solicitudes',
            'ver_solicitudes',
            'gestionar_campo',
            'ver_trazabilidad',
        ]);

        $roleConductor->syncPermissions([
            'ver_conductores',
        ]);

        $roleAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

        $this->command->info('✓ Roles y permisos creados correctamente');
    }
}
