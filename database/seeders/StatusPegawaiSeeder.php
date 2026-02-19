<?php

namespace Database\Seeders;

use App\Models\StatusPegawai;
use Illuminate\Database\Seeder;

class StatusPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Aktif',
            'Tidak Aktif',
            'Pindah',
            'Diberhentikan',
        ];

        foreach ($statuses as $status) {
            StatusPegawai::firstOrCreate(['nama' => $status]);
        }
    }
}
