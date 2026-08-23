<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TunjanganUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (\App\Models\TunjanganUmum::exists()) {
            return;
        }

        \App\Models\TunjanganUmum::create([
            'tunjangan_beras' => 7242.00,
            'jumlah_beras' => 10,
            'tunjangan_anak_persen' => 2,
            'tunjangan_istri_persen' => 10,
            'status' => 'Y',
        ]);
    }
}
