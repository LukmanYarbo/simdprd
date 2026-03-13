<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifPajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tax = \App\Models\TarifPajak::create([
            'no_peraturan' => 'UU No. 7 TAHUN 2021',
            'tgl_berlaku' => '2021-01-01',
            'ptkp' => 54000000,
            'tambahan_ptkp_istri' => 4500000,
            'tambahan_ptkp_tanggungan' => 4500000,
            'persen_biaya_jabatan' => 5.00,
            'max_biaya_jabatan' => 500000,
            'status' => 'Y',
        ]);

        $lapis = [
            ['dari' => 0, 'sampai' => 60000000, 'persen' => 5.00],
            ['dari' => 60000001, 'sampai' => 250000000, 'persen' => 15.00],
            ['dari' => 250000001, 'sampai' => 500000000, 'persen' => 25.00],
            ['dari' => 500000001, 'sampai' => 5000000000, 'persen' => 30.00],
            ['dari' => 5000000001, 'sampai' => null, 'persen' => 35.00],
        ];

        foreach ($lapis as $l) {
            \App\Models\TarifLapisPajak::create(array_merge($l, ['id_tarif_pajak' => $tax->id]));
        }
    }
}
