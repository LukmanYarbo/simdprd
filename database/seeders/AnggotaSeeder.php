<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Ketua (1 person)
        Anggota::factory()->create([
            'id_dprd' => 1, // Ketua
            'nama_anggota' => 'FRANGKY CHENDRA', // A specific name for the chairperson
        ]);

        // 2. Create Wakil Ketua (2 people)
        $wakilKetuaNames = [
            'FIKRI GAM',
            'ABDUL MULO DAENG MULISA',
        ];

        foreach ($wakilKetuaNames as $name) {
            Anggota::factory()->create([
                'id_dprd' => 2, // Wakil Ketua
                'nama_anggota' => $name,
            ]);
        }

        // 3. Create Anggota (17 people)
        $anggotaNames = [
            'RAMJAN SUNE',
            'SUTRISNO VAN GOBEL',
            'DJONI PATIRO',
            'RONAL BOLOTA',
            'DRS SALIM BIN ABDULLAH',
            'MASDIYANI LANTANA',
            'TIA APRILIA MODANGGU',
            'RAMLAN TINAMONGA',
            'DEPRI PONTOH',
            'SEM HASSAN',
            'MEIDI PONTOH',
            'ANDRIANSAH SEPTIAN PAKAYA',
            'ABDUL ZAMAD LAUMA',
            'DONAL LAMUNTE',
            'MARDAN UMAR',
            'DEWI ZANDRA ASTUTI MONDO',
            'SAIFUL AMBARAK',
        ];

        foreach ($anggotaNames as $name) {
            Anggota::factory()->create([
                'id_dprd' => 3, // Anggota
                'nama_anggota' => $name,
            ]);
        }
    }
}
