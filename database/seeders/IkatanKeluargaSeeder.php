<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class IkatanKeluargaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'Suami'],
            ['nama' => 'Istri'],
            ['nama' => 'Anak'],
            ['nama' => 'Lainnya'],
        ];

        foreach ($data as $item) {
            DB::table('ikatan_keluarga')->updateOrInsert(['nama' => $item['nama']], $item);
        }
    }
}
