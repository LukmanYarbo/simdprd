<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Resources based on sidebar
        $resources = [
            'anggota',
            'users',
            'roles',
            'permissions',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        // Create Permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                \Spatie\Permission\Models\Permission::create(['name' => "{$action} {$resource}"]);
            }
        }

        // Dashboard specific permission
        \Spatie\Permission\Models\Permission::create(['name' => 'view dashboard']);

        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $operatorRole = Role::create(['name' => 'operator']);
        $userRole = Role::create(['name' => 'user']);

        // Assign all permissions to Admin
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Assign specific permissions to Operator (Example: can manage Anggota but not Users/RBAC)
        $operatorPermissions = [
            'view dashboard',
            'view anggota', 'create anggota', 'edit anggota',
        ];
        $operatorRole->givePermissionTo($operatorPermissions);

        // Assign specific permissions to User (Example: View only)
        $userPermissions = [
            'view dashboard',
            'view anggota',
        ];
        $userRole->givePermissionTo($userPermissions);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@simdprd.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Create Operator User
        $operator = User::factory()->create([
             'name' => 'Operator User',
             'email' => 'operator@simdprd.com',
             'password' => Hash::make('password'),
        ]);
        $operator->assignRole($operatorRole);
        
        // Create Regular User
        $user = User::factory()->create([
             'name' => 'Regular User',
             'email' => 'user@simdprd.com',
             'password' => Hash::make('password'),
        ]);
        $user->assignRole($userRole);
    }
}
