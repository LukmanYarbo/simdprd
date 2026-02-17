<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlatKelengkapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'KOMISI', 'ket' => 'Komisi DPRD'],
            ['nama' => 'BANMUS', 'ket' => 'Badan Musyawarah'],
            ['nama' => 'BANGGAR', 'ket' => 'Badan Anggaran'],
            ['nama' => 'BK', 'ket' => 'Badan Kehormatan'],
            ['nama' => 'BALEGDA', 'ket' => 'Badan Legislasi Daerah'],
            ['nama' => 'PANSUS', 'ket' => 'Panitia Khusus'],
            ['nama' => 'PANJA', 'ket' => 'Panitia Kerja'],
        ];

        foreach ($data as $item) {
            \App\Models\AlatKelengkapan::create($item);
        }
    }
}
