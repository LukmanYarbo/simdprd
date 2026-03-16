<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pemda;

class PemdaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pemda::updateOrCreate(
            ['id' => 1],
            [
                'namapemda' => 'Pemerintah Kabupaten Bolaang Mongondow Utara',
                'alamat' => 'Jl. Trans Sulawesi, Boroko',
                'kota' => 'Boroko',
                'kabupaten' => 'Bolaang Mongondow Utara',
                'propinsi' => 'Sulawesi Utara',
                'kode_pos' => '95765',
                'nama_bupati' => 'Nama Bupati',
                'jabatan_bupati' => 'Bupati Bolaang Mongondow Utara',
                'judul_bupati' => 'Bupati',
                'nama_wakil_bupati' => 'Nama Wakil Bupati',
                'jabatan_wakil_bupati' => 'Wakil Bupati Bolaang Mongondow Utara',
                'judul_wakil_bupati' => 'Wakil Bupati',
                'id_sekda' => null,
                'logo_pemda' => null,
            ]
        );
    }
}
