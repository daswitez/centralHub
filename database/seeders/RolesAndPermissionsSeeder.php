<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Solicitudes
            'crear_solicitudes',
            'ver_solicitudes',
            'responder_solicitudes',
            
            // Conductores
            'gestionar_conductores',
            'ver_conductores',
            
            // Trazabilidad
            'ver_trazabilidad',
            
            // Transacciones
            'gestionar_planta',
            'gestionar_almacen',
            'gestionar_campo',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos

        // Rol Planta: Puede crear solicitudes, gestionar planta
        $rolePlanta = Role::create(['name' => 'planta']);
        $rolePlanta->givePermissionTo([
            'crear_solicitudes',
            'ver_solicitudes',
            'gestionar_planta',
            'ver_trazabilidad',
            'ver_conductores'
        ]);

        // Rol Productor: Puede responder solicitudes, gestionar campo
        $roleProductor = Role::create(['name' => 'productor']);
        $roleProductor->givePermissionTo([
            'responder_solicitudes',
            'ver_solicitudes',
            'gestionar_campo',
            'ver_trazabilidad'
        ]);

        // Rol Conductor: Puede ver asignaciones
        $roleConductor = Role::create(['name' => 'conductor']);
        $roleConductor->givePermissionTo([
            'ver_conductores'
        ]);

        // Rol Admin: Tiene todos los permisos
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        echo "✓ Roles y permisos creados exitosamente\n";
        echo "  - Planta: puede crear solicitudes y gestionar planta\n";
        echo "  - Productor: puede responder solicitudes y gestionar campo\n";
        echo "  - Conductor: puede ver asignaciones\n";
        echo "  - Admin: tiene todos los permisos\n";
    }
}
