<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EsselonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['00','01','Ia', 'Ib', 'IIa', 'IIb', 'IIIa', 'IIIb', 'IVa', 'IVb','Staf','Fungsional'];
        
        foreach ($data as $nama) {
            \App\Models\Esselon::firstOrCreate(['nama' => $nama]);
        }
    }
}
