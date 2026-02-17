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

        // Create Permissions
        \Spatie\Permission\Models\Permission::create(['name' => 'view users']);
        \Spatie\Permission\Models\Permission::create(['name' => 'view roles']);
        \Spatie\Permission\Models\Permission::create(['name' => 'view settings']);

        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        $operatorRole = Role::create(['name' => 'operator']);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@simdprd.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole($adminRole);

        // Optional: Create dummy users for other roles
        $operator = User::factory()->create([
             'name' => 'Operator User',
             'email' => 'operator@simdprd.com',
             'password' => Hash::make('password'),
        ]);
        $operator->assignRole($operatorRole);
        
        $user = User::factory()->create([
             'name' => 'Regular User',
             'email' => 'user@simdprd.com',
             'password' => Hash::make('password'),
        ]);
        $user->assignRole($userRole);
    }
}
