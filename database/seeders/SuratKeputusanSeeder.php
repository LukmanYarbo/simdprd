<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuratKeputusanSeeder extends Seeder
{
    public function run(): void
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'no_sk' => 'SK/001/KOMISI/DPRD-BMU/2026',
    'ket_sk' => 'Surat Keputusan Komisi',
    'tgl_sk' => '2024-03-13',
    'file_sk' => NULL,
    'status' => 'A',
    'id_alat_kelengkapan' => 2,
    'created_at' => '2026-03-13 15:47:19',
    'updated_at' => '2026-03-15 09:11:10',
  ),
  1 => 
  array (
    'id' => 6,
    'no_sk' => 'SK/002/BANMUS/DPRD-BMU/2026',
    'ket_sk' => 'Surat Keputusan Badan Musyawarah',
    'tgl_sk' => '2024-03-13',
    'file_sk' => NULL,
    'status' => 'A',
    'id_alat_kelengkapan' => 3,
    'created_at' => '2026-03-15 08:55:27',
    'updated_at' => '2026-03-15 09:11:22',
  ),
  2 => 
  array (
    'id' => 7,
    'no_sk' => 'SK/003/BANGGAR/DPRD-BMU/2026',
    'ket_sk' => 'SK Badan Anggaran',
    'tgl_sk' => '2024-03-13',
    'file_sk' => NULL,
    'status' => 'A',
    'id_alat_kelengkapan' => 4,
    'created_at' => '2026-03-15 09:03:02',
    'updated_at' => '2026-03-15 09:11:47',
  ),
  3 => 
  array (
    'id' => 8,
    'no_sk' => 'SK/004/BK/DPRD-BMU/2026',
    'ket_sk' => 'Surat Kepeutusan Badan Kehormatan',
    'tgl_sk' => '2024-03-13',
    'file_sk' => NULL,
    'status' => 'A',
    'id_alat_kelengkapan' => 5,
    'created_at' => '2026-03-15 09:12:33',
    'updated_at' => '2026-03-15 09:12:33',
  ),
  4 => 
  array (
    'id' => 9,
    'no_sk' => 'SK/002/BALEGDA/DPRD-BMU/2026',
    'ket_sk' => 'Surat Keputusan Baden Legislasi Daerah',
    'tgl_sk' => '2024-03-13',
    'file_sk' => NULL,
    'status' => 'A',
    'id_alat_kelengkapan' => 6,
    'created_at' => '2026-03-15 09:26:00',
    'updated_at' => '2026-03-15 09:26:00',
  ),
);

        foreach ($data as $item) {
            DB::table('surat_keputusan')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
