<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PangkatGolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['pangkat' => 'Juru Muda', 'golongan' => 'I/a'],
            ['pangkat' => 'Juru Muda Tk. I', 'golongan' => 'I/b'],
            ['pangkat' => 'Juru', 'golongan' => 'I/c'],
            ['pangkat' => 'Juru Tk. I', 'golongan' => 'I/d'],
            ['pangkat' => 'Pengatur Muda', 'golongan' => 'II/a'],
            ['pangkat' => 'Pengatur Muda Tk. I', 'golongan' => 'II/b'],
            ['pangkat' => 'Pengatur', 'golongan' => 'II/c'],
            ['pangkat' => 'Pengatur Tk. I', 'golongan' => 'II/d'],
            ['pangkat' => 'Penata Muda', 'golongan' => 'III/a'],
            ['pangkat' => 'Penata Muda Tk. I', 'golongan' => 'III/b'],
            ['pangkat' => 'Penata', 'golongan' => 'III/c'],
            ['pangkat' => 'Penata Tk. I', 'golongan' => 'III/d'],
            ['pangkat' => 'Pembina', 'golongan' => 'IV/a'],
            ['pangkat' => 'Pembina Tk. I', 'golongan' => 'IV/b'],
            ['pangkat' => 'Pembina Utama Muda', 'golongan' => 'IV/c'],
            ['pangkat' => 'Pembina Utama Madya', 'golongan' => 'IV/d'],
            ['pangkat' => 'Pembina Utama', 'golongan' => 'IV/e'],
        ];

        foreach ($data as $item) {
            \App\Models\PangkatGolongan::firstOrCreate(['golongan' => $item['golongan']], $item);
        }
    }
}
