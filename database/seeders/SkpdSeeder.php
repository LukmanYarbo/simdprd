<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skpds = [
            'Sekretariat Daerah',
            'Sekretariat DPRD',
            'Inspektorat Daerah',
            'Dinas Pendidikan dan Kebudayaan',
            'Dinas Kesehatan',
            'Dinas Pekerjaan Umum dan Penataan Ruang',
            'Dinas Perumahan Kawasan Permukiman dan Pertanahan',
            'Satuan Polisi Pamong Praja dan Kebakaran',
            'Dinas Sosial',
            'Dinas Pengendalian Penduduk Keluarga Berencana Pemberdayaan Perempuan dan Perlindungan Anak',
            'Dinas Lingkungan Hidup dan Kehutanan',
            'Dinas Kependudukan dan Pencatatan Sipil',
            'Dinas Pemberdayaan Masyarakat dan Desa',
            'Dinas Perhubungan',
            'Dinas Komunikasi Informatika dan Persandian',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
            'Dinas Pemuda dan Olahraga',
            'Dinas Perpustakaan dan Kearsipan',
            'Dinas Kelautan dan Perikanan',
            'Dinas Pariwisata',
            'Dinas Pertanian',
            'Dinas Ketahanan Pangan',
            'Dinas Perdagangan Perindustrian Koperasi dan Usaha Kecil Menengah',
            'Dinas Tenaga Kerja dan Transmigrasi',
            'Badan Perencanaan Pembangunan Penelitian dan Pengembangan',
            'Badan Keuangan Daerah',
            'Badan Kepegawaian Pendidikan dan Pelatihan',
            'Badan Penanggulangan Bencana Daerah',
            'Badan Kesatuan Bangsa dan Politik',
            'Kecamatan Bolangitang Barat',
            'Kecamatan Bolangitang Timur',
            'Kecamatan Bintauna',
            'Kecamatan Sangkub',
            'Kecamatan Kaidipang',
            'Kecamatan Pinogaluman',
            'Bagian Tata Pemerintahan',
            'Bagian Kesejahteraan Rakyat',
            'Bagian Hukum',
            'Bagian Perekonomian dan SDA',
            'Bagian Administrasi Pembangunan',
            'Bagian Pengadaan Barang dan Jasa',
            'Bagian Umum',
            'Bagian Organisasi',
            'Bagian Protokol dan Komunikasi Pimpinan'
        ];

        foreach ($skpds as $skpd) {
            \App\Models\Skpd::create(['namaskpd' => $skpd]);
        }
    }
}
