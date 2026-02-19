<?php

namespace Database\Seeders;

use App\Models\JabatanAsn;
use Illuminate\Database\Seeder;

class JabatanAsnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            'Kepala Dinas',
            'Sekretaris Dinas',
            'Kepala Bidang',
            'Kepala Seksi',
            'Staf Pelaksana',
            'Fungsional',
        ];

        // Ensure at least one esselon exists
        $esselon = \App\Models\Esselon::first();
        if (!$esselon) {
            $esselon = \App\Models\Esselon::create(['nama' => 'Non-Esselon']);
        }

        foreach ($jabatans as $jabatan) {
            JabatanAsn::firstOrCreate([
                'nama_jabatan' => $jabatan,
            ], [
                'id_esselon' => $esselon->id
            ]);
        }
    }
}
