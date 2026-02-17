<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IkatanKeluargaSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ikatan_keluarga')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['nama' => 'Suami'],
            ['nama' => 'Istri'],
            ['nama' => 'Anak'],
            ['nama' => 'Lainnya'],
        ];

        DB::table('ikatan_keluarga')->insert($data);
    }
}
