<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportingTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agama')->insert([
            ['nama' => 'Islam'],
            ['nama' => 'Kristen'],
            ['nama' => 'Katolik'],
            ['nama' => 'Hindu'],
            ['nama' => 'Budha'],
            ['nama' => 'Konghucu'],
        ]);

        DB::table('status_kawin')->insert([
            ['nama' => 'Belum Kawin', 'kode' => 'TK'],
            ['nama' => 'Kawin', 'kode' => 'K'],
            ['nama' => 'Cerai Hidup', 'kode' => 'CH'],
            ['nama' => 'Cerai Mati', 'kode' => 'CM'],
        ]);

        DB::table('status_keanggotaan')->insert([
            ['nama' => 'Aktif'],
            ['nama' => 'Tidak Aktif'],
            ['nama' => 'PAW'],
            ['nama' => 'Meninggal Dunia'],
            ['nama' => 'Mengundurkan Diri'],
        ]);

        DB::table('jabatan_dprd')->insert([
            ['nama' => 'Ketua DPRD'],
            ['nama' => 'Wakil Ketua DPRD'],
            ['nama' => 'Anggota DPRD'],
           
        ]);
        DB::table('jabatan_alat_kelengkapan')->insert([
            ['nama' => 'Ketua'],
            ['nama' => 'Wakil'],
            ['nama' => 'Sekretaris'],
            ['nama' => 'Anggota'],
        ]);
    }
}
