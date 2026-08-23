<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PotonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (\App\Models\Potongan::exists()) {
            return;
        }

        \App\Models\Potongan::create([
            'tunjangan_bpjs' => 0.00,
            'potongan_bpjs' => 1.00,
            'maksimal_potongan_bpjs' => 12000000.00,
            'jkk' => 0.24,
            'jkm' => 0.72,
            'maks_jkkjkm' => 300000.00,
            'pot_pph' => 15.00,
        ]);
    }
}
