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
        foreach ([
            'Islam',
            'Kristen',
            'Katolik',
            'Hindu',
            'Budha',
            'Konghucu',
        ] as $nama) {
            DB::table('agama')->updateOrInsert(['nama' => $nama], ['nama' => $nama]);
        }

        foreach ([
            ['nama' => 'Belum Kawin', 'kode' => 'TK'],
            ['nama' => 'Kawin', 'kode' => 'K'],
            ['nama' => 'Cerai Hidup', 'kode' => 'CH'],
            ['nama' => 'Cerai Mati', 'kode' => 'CM'],
        ] as $item) {
            DB::table('status_kawin')->updateOrInsert(['kode' => $item['kode']], $item);
        }

        foreach ([
            'Aktif',
            'Tidak Aktif',
            'PAW',
            'Meninggal Dunia',
            'Mengundurkan Diri',
        ] as $nama) {
            DB::table('status_keanggotaan')->updateOrInsert(['nama' => $nama], ['nama' => $nama]);
        }

        foreach ([
            'Ketua DPRD',
            'Wakil Ketua DPRD',
            'Anggota DPRD',
        ] as $nama) {
            DB::table('jabatan_dprd')->updateOrInsert(['nama' => $nama], ['nama' => $nama]);
        }

        foreach ([['nama' => 'Ketua'], ['nama' => 'Wakil'], ['nama' => 'Sekretaris'], ['nama' => 'Anggota']] as $item) {
            DB::table('jabatan_alat_kelengkapan')->updateOrInsert(['nama' => $item['nama']], $item);
        }
    }
}
