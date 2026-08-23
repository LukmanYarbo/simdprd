<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'TK',
            'SD',
            'SLTA/Sederajat',
            'SMA/Sederajat',
            'D.III',
            'D.IV',
            'S.I',
            'S.II',
            'S.III',
            'DR',
            'Lainnya',
        ];

        foreach ($data as $d) {
            \Illuminate\Support\Facades\DB::table('jenis_pendidikan')->updateOrInsert(
                ['nama' => $d],
                ['nama' => $d, 'updated_at' => now()]
            );
        }
    }
}
