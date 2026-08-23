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
            'perubahan_status_anggota',
            'keluarga',
            'pendidikan',
            'harta',
            'users',
            'roles',
            'permissions',
            'alat_kelengkapan',
            'surat_keputusan',
            'pemda',
            'jabatan_asn',
            'skpd',
            'tunjangan',
            'pegawai_asn',
            'surat_tugas',
            'penanda_tangan',
            'parameter_gaji',
            'tarif_pajak',
            'potongan',
            'transaksi_gaji',
            'pph21_a2',
            'anggaran',
            'jurnal_lra',
            'kertas_kerja',
            'database_management',
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
            'view perubahan_status_anggota', 'create perubahan_status_anggota',
            'view keluarga', 'create keluarga', 'edit keluarga',
            'view pendidikan', 'create pendidikan', 'edit pendidikan',
            'view harta', 'create harta', 'edit harta',
            'view alat_kelengkapan', 'create alat_kelengkapan', 'edit alat_kelengkapan',
            'view surat_keputusan', 'create surat_keputusan', 'edit surat_keputusan',
            'view pemda', 'create pemda', 'edit pemda',
            'view jabatan_asn', 'create jabatan_asn', 'edit jabatan_asn',
            'view skpd', 'create skpd', 'edit skpd',
            'view tunjangan', 'create tunjangan', 'edit tunjangan',
            'view pegawai_asn', 'create pegawai_asn', 'edit pegawai_asn',
            'view surat_tugas', 'create surat_tugas', 'edit surat_tugas',
            'view penanda_tangan', 'create penanda_tangan', 'edit penanda_tangan',
            'view parameter_gaji', 'create parameter_gaji', 'edit parameter_gaji',
            'view tarif_pajak', 'create tarif_pajak', 'edit tarif_pajak',
            'view potongan', 'create potongan', 'edit potongan',
            'view transaksi_gaji',
            'view pph21_a2',
            'view anggaran', 'create anggaran', 'edit anggaran',
            'view jurnal_lra',
            'view kertas_kerja', 'create kertas_kerja', 'edit kertas_kerja',
        ];
        // Filter permissions that actually exist to avoid errors if something went wrong
        $validOperatorPermissions = \Spatie\Permission\Models\Permission::whereIn('name', $operatorPermissions)->get();
        $operatorRole->syncPermissions($validOperatorPermissions);

        // Assign specific permissions to User
        $userPermissions = [
            'view dashboard',
            'view anggota',
            'view perubahan_status_anggota',
            'view keluarga',
            'view pendidikan',
            'view harta',
            'view alat_kelengkapan',
            'view surat_keputusan',
            'view pemda',
            'view jabatan_asn',
            'view skpd',
            'view tunjangan',
            'view pegawai_asn',
            'view surat_tugas',
            'view penanda_tangan',
            'view parameter_gaji',
            'view tarif_pajak',
            'view potongan',
            'view transaksi_gaji',
            'view pph21_a2',
            'view anggaran',
            'view jurnal_lra',
            'view kertas_kerja',
        ];
        $validUserPermissions = \Spatie\Permission\Models\Permission::whereIn('name', $userPermissions)->get();
        $userRole->syncPermissions($validUserPermissions);

        // Create Users (only if they don't exist)
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
            ]);
            $admin->assignRole($adminRole);
        }

        if (!User::where('email', 'operator@gmail.com')->exists()) {
            $operator = User::factory()->create([
                'name' => 'Operator User',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('password'),
            ]);
            $operator->assignRole($operatorRole);
        }
        
        if (!User::where('email', 'user@gmail.com')->exists()) {
            $user = User::factory()->create([
                 'name' => 'Regular User',
                 'email' => 'user@gmail.com',
                 'password' => Hash::make('password'),
            ]);
            $user->assignRole($userRole);
        }
    }
}
