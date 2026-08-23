<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TunjanganTransportasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TunjanganTransportasi::firstOrCreate([
            'no_peraturan' => 'PERBUB NO 10 TAHUN 2021',
        ], [
            'tgl_berlaku' => '2021-01-01',
            'no_peraturan' => 'PERBUB NO 10 TAHUN 2021',
            'nilai_tunjangan_ketua' => 0.00,
            'nilai_tunjangan_wakil' => 0.00,
            'nilai_tunjangan_anggota' => 14000000.00,
            'status' => 'Y',
        ]);
    }
}
