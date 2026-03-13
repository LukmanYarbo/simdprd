<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuratKeputusanSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('surat_keputusan')->insertOrIgnore([
                'id' => $i,
                'no_sk' => 'SK/00' . $i . '/DPRD/2026',
                'ket_sk' => 'Penetapan Jabatan Anggota DPRD',
                'tgl_sk' => now()->format('Y-m-d'),
                'id_alat_kelengkapan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
