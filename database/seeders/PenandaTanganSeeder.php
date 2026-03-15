<?php

namespace Database\Seeders;

use App\Models\PenandaTangan;
use Illuminate\Database\Seeder;

class PenandaTanganSeeder extends Seeder
{
    public function run(): void
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'id_skpd' => 3,
    'id_anggota' => NULL,
    'id_pegawai_asn' => 1,
    'jenis_dokumen' => 'Surat Tugas,SPPD,Surat Keputusan,Pengajuan Gaji',
    'created_at' => '2026-03-14 15:03:46',
    'updated_at' => '2026-03-14 15:04:18',
  ),
  1 => 
  array (
    'id' => 2,
    'id_skpd' => 3,
    'id_anggota' => NULL,
    'id_pegawai_asn' => 2,
    'jenis_dokumen' => 'Pengajuan Gaji',
    'created_at' => '2026-03-14 15:03:59',
    'updated_at' => '2026-03-14 15:03:59',
  ),
);

        foreach ($data as $item) {
            PenandaTangan::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
