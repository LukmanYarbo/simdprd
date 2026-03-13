<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParameterGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ParameterGaji::create([
            'no_peraturan' => 'PP No. 18 TAHUN 2017',
            'tgl_berlaku' => '2018-01-01',
            'gajipokok_ketua' => 2100000,
            'persen_gapokwakil' => 80.00,
            'persen_gapokanggota' => 75.00,
            'persen_tunjabketua' => 145.00,
            'persen_tunjabwakil' => 145.00,
            'persen_tunjabanggota' => 145.00,
            'persen_tunketua_aleg' => 7.50,
            'persen_tunwakil_aleg' => 5.00,
            'persen_tunsek_aleg' => 4.00,
            'persen_tunanggota_aleg' => 3.00,
            'persen_uangpaket' => 10.00,
            'status' => 'Y',
        ]);
    }
}
