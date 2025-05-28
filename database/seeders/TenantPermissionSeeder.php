<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Company\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TenantPermissionSeeder extends Seeder
{
    public function run()
    {
        // Get all users with their own databases
        $users = User::whereNotNull('db_name')->get();

        foreach ($users as $user) {
            try {
                // Switch to user's database
                $originalDb = Config::get('database.connections.mysql.database');
                Config::set('database.connections.mysql.database', $user->db_name);
                DB::purge('mysql');
                DB::reconnect('mysql');
                DB::statement("USE `{$user->db_name}`");

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

                // Assign role to user
                $user->assignRole($role);

                // Restore original database
                Config::set('database.connections.mysql.database', $originalDb);
                DB::purge('mysql');
                DB::reconnect('mysql');
                DB::statement("USE `{$originalDb}`");

            } catch (\Exception $e) {
                \Log::error("Failed to seed permissions for user {$user->id}: " . $e->getMessage());
                
                // Restore original database in case of error
                Config::set('database.connections.mysql.database', $originalDb);
                DB::purge('mysql');
                DB::reconnect('mysql');
                DB::statement("USE `{$originalDb}`");
            }
        }
    }
} 