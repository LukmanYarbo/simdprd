<?php

namespace Database\Seeders;

use App\Models\PegawaiAsn;
use Illuminate\Database\Seeder;

class PegawaiAsnSeeder extends Seeder
{
    public function run(): void
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'id_skpd' => 3,
    'nip' => '19700429 199603 1 005',
    'nik' => '2423424242',
    'nokk' => '2345235345',
    'nama' => 'VICTOR FRANGKY NANLESSY, S,Pi, M.Si',
    'tempat_lahir' => 'AMBON',
    'tgl_lahir' => '1971-01-01',
    'jenis_kelamin' => 'L',
    'id_agama' => 2,
    'id_status_kawin' => 'K',
    'id_pangkat_golongan' => 15,
    'id_jabatan' => 1,
    'id_status_pegawai' => 1,
    'tanggal_mulai_kerja' => NULL,
    'tanggal_berhenti' => NULL,
    'ket_jabatan' => 'Sekretaris DPRD',
    'email' => NULL,
    'nohp' => NULL,
    'norek' => NULL,
    'npwp' => NULL,
    'foto' => NULL,
    'created_at' => '2026-03-14 15:01:44',
    'updated_at' => '2026-03-14 15:01:44',
  ),
  1 => 
  array (
    'id' => 2,
    'id_skpd' => 3,
    'nip' => '19830816 201102 1 001',
    'nik' => '345356346',
    'nokk' => '456456464',
    'nama' => 'AGUS ANHARIS SUDIRO, S.Pd.I',
    'tempat_lahir' => 'KEDIRI',
    'tgl_lahir' => '1983-08-16',
    'jenis_kelamin' => 'L',
    'id_agama' => 1,
    'id_status_kawin' => 'K',
    'id_pangkat_golongan' => 11,
    'id_jabatan' => 10,
    'id_status_pegawai' => 1,
    'tanggal_mulai_kerja' => NULL,
    'tanggal_berhenti' => NULL,
    'ket_jabatan' => 'Bendahara Pengeluaran',
    'email' => NULL,
    'nohp' => NULL,
    'norek' => NULL,
    'npwp' => NULL,
    'foto' => NULL,
    'created_at' => '2026-03-14 15:03:27',
    'updated_at' => '2026-03-14 15:03:27',
  ),
);

        foreach ($data as $item) {
            PegawaiAsn::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
