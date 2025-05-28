<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'accounting_tree',
            'accounting_entries',
            'accounting_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo($permissions);

        // You can create more roles and assign specific permissions as needed
        // For example:
        // $accountant = Role::create(['name' => 'accountant', 'guard_name' => 'web']);
        // $accountant->givePermissionTo(['accounting_entries', 'accounting_reports']);
    }
} 