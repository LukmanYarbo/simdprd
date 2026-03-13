<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TunjanganKomunikasiIntensifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TunjanganKomunikasiIntensif::create([
            'tgl_berlaku' => '2018-01-01',
            'no_peraturan' => 'PP NO 18 TAHUN 2017',
            'nilai_tunjangan_tki' => 6300000.00,
            'status' => 'Y',
        ]);
    }
}
