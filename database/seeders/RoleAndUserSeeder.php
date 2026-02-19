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
            'alat_kelengkapan',
            'surat_keputusan',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        // Create Permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                \Spatie\Permission\Models\Permission::firstOrCreate(['name' => "{$action} {$resource}"]);
            }
        }

        // Dashboard specific permission
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view dashboard']);

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign all permissions to Admin
        $adminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Assign specific permissions to Operator
        $operatorPermissions = [
            'view dashboard',
            'view anggota', 'create anggota', 'edit anggota',
            'view alat_kelengkapan', 'create alat_kelengkapan', 'edit alat_kelengkapan',
            'view surat_keputusan', 'create surat_keputusan', 'edit surat_keputusan',
        ];
        // Filter permissions that actually exist to avoid errors if something went wrong
        $validOperatorPermissions = \Spatie\Permission\Models\Permission::whereIn('name', $operatorPermissions)->get();
        $operatorRole->syncPermissions($validOperatorPermissions);

        // Assign specific permissions to User
        $userPermissions = [
            'view dashboard',
            'view anggota',
            'view alat_kelengkapan', // Users usually can view master data
            'view surat_keputusan',
        ];
        $validUserPermissions = \Spatie\Permission\Models\Permission::whereIn('name', $userPermissions)->get();
        $userRole->syncPermissions($validUserPermissions);

        // Create Users (only if they don't exist)
        if (!User::where('email', 'admin@simdprd.com')->exists()) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
            ]);
            $admin->assignRole($adminRole);
        }

        if (!User::where('email', 'operator@simdprd.com')->exists()) {
            $operator = User::factory()->create([
                'name' => 'Operator User',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('password'),
            ]);
            $operator->assignRole($operatorRole);
        }
        
        if (!User::where('email', 'user@simdprd.com')->exists()) {
            $user = User::factory()->create([
                 'name' => 'Regular User',
                 'email' => 'user@gmail.com',
                 'password' => Hash::make('password'),
            ]);
            $user->assignRole($userRole);
        }
    }
}
