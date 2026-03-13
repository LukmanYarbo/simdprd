<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TunjanganPerumahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TunjanganPerumahan::create([
            'tgl_berlaku' => '2021-01-01',
            'no_peraturan' => 'PERBUB NO 10 TAHUN 2021',
            'nilai_tunjangan_ketua' => 12000000.00,
            'nilai_tunjangan_wakil' => 10000000.00,
            'nilai_tunjangan_anggota' => 6056000.00,
            'status' => 'Y',
        ]);
    }
}
