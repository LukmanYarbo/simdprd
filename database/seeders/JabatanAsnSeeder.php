<?php

namespace Database\Seeders;

use App\Models\JabatanAsn;
use Illuminate\Database\Seeder;

class JabatanAsnSeeder extends Seeder
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
    'id_skpd' => 3,
    'id_esselon' => 6,
    'nama_jabatan' => 'Sekretaris DPRD',
    'created_at' => '2026-03-13T15:47:19.000000Z',
    'updated_at' => '2026-03-13T15:50:49.000000Z',
  ),
  1 => 
  array (
    'id' => 7,
    'id_skpd' => 3,
    'id_esselon' => 7,
    'nama_jabatan' => 'Kepala Bagian',
    'created_at' => '2026-03-13T15:51:21.000000Z',
    'updated_at' => '2026-03-13T15:51:21.000000Z',
  ),
  2 => 
  array (
    'id' => 8,
    'id_skpd' => 3,
    'id_esselon' => 9,
    'nama_jabatan' => 'Kepala Sub Bagian',
    'created_at' => '2026-03-13T15:51:43.000000Z',
    'updated_at' => '2026-03-13T15:51:43.000000Z',
  ),
  3 => 
  array (
    'id' => 9,
    'id_skpd' => 3,
    'id_esselon' => 11,
    'nama_jabatan' => 'Staf',
    'created_at' => '2026-03-13T15:51:59.000000Z',
    'updated_at' => '2026-03-13T15:51:59.000000Z',
  ),
  4 => 
  array (
    'id' => 10,
    'id_skpd' => 3,
    'id_esselon' => 11,
    'nama_jabatan' => 'Bendahara',
    'created_at' => '2026-03-14T14:59:23.000000Z',
    'updated_at' => '2026-03-14T14:59:23.000000Z',
  ),
);

        foreach ($data as $item) {
            JabatanAsn::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
