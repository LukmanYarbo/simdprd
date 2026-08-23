<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlatKelengkapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'nama' => 'Pimpinan DPRD',
    'ket' => 'Pimpinan DPRD',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  1 => 
  array (
    'id' => 2,
    'nama' => 'KOMISI',
    'ket' => 'Komisi DPRD',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  2 => 
  array (
    'id' => 3,
    'nama' => 'BANMUS',
    'ket' => 'Badan Musyawarah',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  3 => 
  array (
    'id' => 4,
    'nama' => 'BANGGAR',
    'ket' => 'Badan Anggaran',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  4 => 
  array (
    'id' => 5,
    'nama' => 'BK',
    'ket' => 'Badan Kehormatan',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  5 => 
  array (
    'id' => 6,
    'nama' => 'BALEGDA',
    'ket' => 'Badan Legislasi Daerah',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  6 => 
  array (
    'id' => 7,
    'nama' => 'PANSUS',
    'ket' => 'Panitia Khusus',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
  7 => 
  array (
    'id' => 8,
    'nama' => 'PANJA',
    'ket' => 'Panitia Kerja',
    'nama_komisi' => NULL,
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:47:19.000000Z',
  ),
);

        foreach ($data as $item) {
            \App\Models\AlatKelengkapan::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
