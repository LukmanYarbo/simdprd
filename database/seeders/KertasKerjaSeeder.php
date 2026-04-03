<?php

namespace Database\Seeders;

use App\Models\KertasKerja;
use Illuminate\Database\Seeder;

class KertasKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $kk = KertasKerja::create(array (
  'tahun_anggaran' => 2027,
  'status' => 'DRAFT',
  'total_pagu' => 9141160700,
));

        $kk->rincians()->create(array (
  'kategori' => 'Gaji Pokok',
  'jabatan' => 'Ketua',
  'uraian' => 'Gaji Pokok - Ketua',
  'besaran' => 2100000,
  'orang' => 1,
  'bulan_kali' => 14,
  'jumlah' => 29400000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Gaji Pokok',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Gaji Pokok - Wakil Ketua',
  'besaran' => 1680000,
  'orang' => 2,
  'bulan_kali' => 14,
  'jumlah' => 47040000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Gaji Pokok',
  'jabatan' => 'Anggota',
  'uraian' => 'Gaji Pokok - Anggota',
  'besaran' => 1575000,
  'orang' => 17,
  'bulan_kali' => 14,
  'jumlah' => 374850000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Istri',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Istri - Ketua',
  'besaran' => 210000,
  'orang' => 1,
  'bulan_kali' => 14,
  'jumlah' => 2940000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Istri',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Istri - Wakil Ketua',
  'besaran' => 168000,
  'orang' => 2,
  'bulan_kali' => 14,
  'jumlah' => 4704000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Istri',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Istri - Anggota',
  'besaran' => 157500,
  'orang' => 17,
  'bulan_kali' => 17,
  'jumlah' => 45517500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Anak',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Anak - Ketua',
  'besaran' => 42000,
  'orang' => 2,
  'bulan_kali' => 14,
  'jumlah' => 1176000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Anak',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Anak - Wakil Ketua',
  'besaran' => 33600,
  'orang' => 4,
  'bulan_kali' => 14,
  'jumlah' => 1881600,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Anak',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Anak - Anggota',
  'besaran' => 31500,
  'orang' => 36,
  'bulan_kali' => 14,
  'jumlah' => 15876000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Jabatan',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Jabatan - Ketua',
  'besaran' => 3045000,
  'orang' => 1,
  'bulan_kali' => 14,
  'jumlah' => 42630000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Jabatan',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Jabatan - Wakil Ketua',
  'besaran' => 2436000,
  'orang' => 2,
  'bulan_kali' => 14,
  'jumlah' => 68208000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Jabatan',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Jabatan - Anggota',
  'besaran' => 2283750,
  'orang' => 17,
  'bulan_kali' => 14,
  'jumlah' => 543532500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Beras',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Beras - Ketua',
  'besaran' => 72420,
  'orang' => 4,
  'bulan_kali' => 12,
  'jumlah' => 3476160,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Beras',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Beras - Wakil Ketua',
  'besaran' => 72420,
  'orang' => 8,
  'bulan_kali' => 12,
  'jumlah' => 6952320,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Beras',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Beras - Anggota',
  'besaran' => 72420,
  'orang' => 68,
  'bulan_kali' => 12,
  'jumlah' => 59094720,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan PPh',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Tunjangan PPh - Pimpinan & Anggota',
  'besaran' => 18000000,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 18000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Pembulatan',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Pembulatan - Pimpinan & Anggota',
  'besaran' => 50000,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 50000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Uang Paket',
  'jabatan' => 'Ketua',
  'uraian' => 'Uang Paket - Ketua',
  'besaran' => 210000,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 2520000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Uang Paket',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Uang Paket - Wakil Ketua',
  'besaran' => 168000,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 4032000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Uang Paket',
  'jabatan' => 'Anggota',
  'uraian' => 'Uang Paket - Anggota',
  'besaran' => 157500,
  'orang' => 17,
  'bulan_kali' => 12,
  'jumlah' => 32130000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Perumahan',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Perumahan - Ketua',
  'besaran' => 12000000,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 144000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Perumahan',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Perumahan - Wakil Ketua',
  'besaran' => 10000000,
  'orang' => 12,
  'bulan_kali' => 12,
  'jumlah' => 1440000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Perumahan',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Perumahan - Anggota',
  'besaran' => 6056000,
  'orang' => 17,
  'bulan_kali' => 12,
  'jumlah' => 1235424000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Transportasi',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Transportasi - Ketua',
  'besaran' => 0,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 0,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Transportasi',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Transportasi - Wakil Ketua',
  'besaran' => 0,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 0,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Transportasi',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Transportasi - Anggota',
  'besaran' => 14000000,
  'orang' => 17,
  'bulan_kali' => 12,
  'jumlah' => 2856000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Komisi',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Komisi - Ketua',
  'besaran' => 228375,
  'orang' => 3,
  'bulan_kali' => 12,
  'jumlah' => 8221500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Komisi',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Komisi - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 3,
  'bulan_kali' => 12,
  'jumlah' => 5481000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Komisi',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan Komisi - Sekretaris',
  'besaran' => 121800,
  'orang' => 3,
  'bulan_kali' => 12,
  'jumlah' => 4384800,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Komisi',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Komisi - Anggota',
  'besaran' => 91350,
  'orang' => 12,
  'bulan_kali' => 12,
  'jumlah' => 13154400,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banggar',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Banggar - Ketua',
  'besaran' => 228375,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 2740500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banggar',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Banggar - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 3654000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banggar',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan Banggar - Sekretaris',
  'besaran' => 121800,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 1461600,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banggar',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Banggar - Anggota',
  'besaran' => 91350,
  'orang' => 7,
  'bulan_kali' => 12,
  'jumlah' => 7673400,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banmus',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Banmus - Ketua',
  'besaran' => 228375,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 2740500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banmus',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Banmus - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 3654000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banmus',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan Banmus - Sekretaris',
  'besaran' => 121800,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 1461600,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Banmus',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Banmus - Anggota',
  'besaran' => 91350,
  'orang' => 7,
  'bulan_kali' => 12,
  'jumlah' => 7673400,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Balegda',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Balegda - Ketua',
  'besaran' => 228375,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 2740500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Balegda',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Balegda - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 1827000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Balegda',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan Balegda - Sekretaris',
  'besaran' => 121800,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 1461600,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Balegda',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Balegda - Anggota',
  'besaran' => 91350,
  'orang' => 7,
  'bulan_kali' => 12,
  'jumlah' => 7673400,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan BK',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan BK - Ketua',
  'besaran' => 228375,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 2740500,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan BK',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan BK - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 1827000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan BK',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan BK - Sekretaris',
  'besaran' => 121800,
  'orang' => 1,
  'bulan_kali' => 12,
  'jumlah' => 1461600,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan BK',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan BK - Anggota',
  'besaran' => 91350,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 2192400,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Pansus',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Pansus - Ketua',
  'besaran' => 228375,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 5481000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Pansus',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Pansus - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 3654000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Pansus',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan Pansus - Sekretaris',
  'besaran' => 121800,
  'orang' => 2,
  'bulan_kali' => 12,
  'jumlah' => 2923200,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Pansus',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Pansus - Anggota',
  'besaran' => 91350,
  'orang' => 15,
  'bulan_kali' => 12,
  'jumlah' => 16443000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Panja',
  'jabatan' => 'Ketua',
  'uraian' => 'Tunjangan Panja - Ketua',
  'besaran' => 228375,
  'orang' => 0,
  'bulan_kali' => 0,
  'jumlah' => 0,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Panja',
  'jabatan' => 'Wakil Ketua',
  'uraian' => 'Tunjangan Panja - Wakil Ketua',
  'besaran' => 152250,
  'orang' => 0,
  'bulan_kali' => 0,
  'jumlah' => 0,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Panja',
  'jabatan' => 'Sekretaris',
  'uraian' => 'Tunjangan Panja - Sekretaris',
  'besaran' => 121800,
  'orang' => 0,
  'bulan_kali' => 0,
  'jumlah' => 0,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Panja',
  'jabatan' => 'Anggota',
  'uraian' => 'Tunjangan Panja - Anggota',
  'besaran' => 91350,
  'orang' => 0,
  'bulan_kali' => 0,
  'jumlah' => 0,
));
        $kk->rincians()->create(array (
  'kategori' => 'Komunikasi Insentif (TKI)',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Komunikasi Insentif (TKI) - Pimpinan & Anggota',
  'besaran' => 6300000,
  'orang' => 20,
  'bulan_kali' => 12,
  'jumlah' => 1512000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Uang Jasa Pengabdian',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Uang Jasa Pengabdian - Pimpinan & Anggota',
  'besaran' => 150000000,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 150000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Tunjangan Reses',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Tunjangan Reses - Pimpinan & Anggota',
  'besaran' => 6300000,
  'orang' => 20,
  'bulan_kali' => 3,
  'jumlah' => 378000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Asuransi JKK',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Asuransi JKK - Pimpinan & Anggota',
  'besaran' => 3000000,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 3000000,
));
        $kk->rincians()->create(array (
  'kategori' => 'Asuransi JKM',
  'jabatan' => 'Pimpinan & Anggota',
  'uraian' => 'Asuransi JKM - Pimpinan & Anggota',
  'besaran' => 6000000,
  'orang' => 1,
  'bulan_kali' => 1,
  'jumlah' => 6000000,
));

        // --------------------------------------------------

    }
}
