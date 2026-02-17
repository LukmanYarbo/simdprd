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
            'id_jabatan' => 1, // Ketua
            'nama_anggota' => 'Budi Santoso, S.T.', // A specific name for the chairperson
        ]);

        // 2. Create Wakil Ketua (2 people)
        Anggota::factory()->count(2)->create([
            'id_jabatan' => 2, // Wakil Ketua
        ]);

        // 3. Create Anggota (997 people)
        Anggota::factory()->count(997)->create([
            'id_jabatan' => 3, // Anggota
        ]);
    }
}
