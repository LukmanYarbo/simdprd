<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skpds = [
            'Dewan Perwakilan Rakyat Daerah',
            'Sekretariat Daerah',
            'Sekretariat DPRD'
        ];

        foreach ($skpds as $skpd) {
            \App\Models\Skpd::firstOrCreate(['namaskpd' => $skpd]);
        }
    }
}
