<?php

namespace App\Livewire\Admin\KertasKerja;

use App\Models\KertasKerja;
use Livewire\Component;

class DaftarKertasKerja extends Component
{
    public $kertasKerjas;
    public $selected_id;

    public $detailData = null;

    protected $listeners = ['deleteKertasKerja', 'plotToAnggaran'];

    public function render()
    {
        $this->kertasKerjas = KertasKerja::with('rincians')->orderBy('tahun_anggaran', 'desc')->get();
        return view('livewire.admin.kertas-kerja.daftar-kertas-kerja');
    }

    public function showDetail($id)
    {
        $this->detailData = KertasKerja::with('rincians')->findOrFail($id);
        $this->dispatch('show-detail-modal');
    }

    /**
     * Kertas Kerja FINAL yang sudah di-plot ke Master Anggaran
     * tidak boleh dihapus.
     */
    public static function isTerplotDanFinal(int $tahunAnggaran, string $status): bool
    {
        if ($status !== 'FINAL') {
            return false;
        }

        return \App\Models\Anggaran::where('tahun_anggaran', $tahunAnggaran)->exists();
    }

    public function deleteKertasKerja($id)
    {
        $kertasKerja = KertasKerja::findOrFail($id);

        if (self::isTerplotDanFinal((int) $kertasKerja->tahun_anggaran, $kertasKerja->status)) {
            $this->dispatch('swal',
                title: 'Tidak Dapat Dihapus',
                text: 'Kertas Kerja Tahun ' . $kertasKerja->tahun_anggaran . ' berstatus FINAL dan sudah ter-plot ke Master Anggaran.',
                icon: 'error'
            );
            return;
        }

        $kertasKerja->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Kertas Kerja berhasil dihapus.', icon: 'success');
    }

    public function plotToAnggaran($id)
    {
        $kertasKerja = KertasKerja::with('rincians')->findOrFail($id);

        if ($kertasKerja->status !== 'FINAL') {
            $this->dispatch('swal', title: 'Peringatan!', text: 'Hanya Kertas Kerja berstatus FINAL yang bisa di-plot.', icon: 'warning');
            return;
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Create or Update Master Anggaran
            $anggaran = \App\Models\Anggaran::updateOrCreate(
                ['tahun_anggaran' => $kertasKerja->tahun_anggaran],
                [
                    'status' => 'DRAFT', 
                    'total_pagu' => $kertasKerja->total_pagu,
                ]
            );

            // 2. Map and Aggregate Kertas Kerja to Anggaran Rincian
            $mapping = [
                'gaji_pokok' => ['Gaji Pokok'],
                'tunjangan_keluarga' => ['Tunjangan Istri', 'Tunjangan Anak'],
                'tunjangan_jabatan' => ['Tunjangan Jabatan'],
                'tunjangan_beras' => ['Tunjangan Beras'],
                'tunjangan_pph' => ['Tunjangan PPh'],
                'pembulatan' => ['Pembulatan'],
                'uang_paket' => ['Uang Paket'],
                'tunjangan_alat_kelengkapan' => ['Tunjangan Komisi', 'Tunjangan Banggar', 'Tunjangan Banmus', 'Tunjangan Balegda', 'Tunjangan BK'],
                'tunjangan_alat_kelengkapan_lainnya' => ['Tunjangan Pansus', 'Tunjangan Panja'],
                'tunjangan_perumahan' => ['Tunjangan Perumahan'],
                'tunjangan_transportasi' => ['Tunjangan Transportasi'],
                'tunjangan_komunikasi_insentif' => ['Komunikasi Insentif (TKI)'],
                'uang_jasa_pengabdian' => ['Uang Jasa Pengabdian'],
                'tunjangan_reses' => ['Tunjangan Reses'],
                'jkk' => ['Asuransi JKK'],
                'jkm' => ['Asuransi JKM'],
            ];

            $descriptions = [
                'gaji_pokok' => 'Gaji Pokok / Uang Representasi',
                'tunjangan_keluarga' => 'Tunjangan Keluarga',
                'tunjangan_jabatan' => 'Tunjangan Jabatan',
                'tunjangan_beras' => 'Tunjangan Beras',
                'tunjangan_pph' => 'Tunjangan PPh',
                'pembulatan' => 'Pembulatan',
                'uang_paket' => 'Uang Paket',
                'tunjangan_alat_kelengkapan' => 'Tunj. Alat Kelengkapan',
                'tunjangan_alat_kelengkapan_lainnya' => 'Tunj. AKD Lainnya (Pansus/Panja)',
                'tunjangan_perumahan' => 'Tunjangan Perumahan',
                'tunjangan_transportasi' => 'Tunjangan Transportasi',
                'tunjangan_komunikasi_insentif' => 'Komunikasi Insentif (TKI)',
                'uang_jasa_pengabdian' => 'Uang Jasa Pengabdian',
                'tunjangan_reses' => 'Tunjangan Reses',
                'jkk' => 'Asuransi JKK',
                'jkm' => 'Asuransi JKM',
            ];

            foreach ($mapping as $kode => $categories) {
                $sum = $kertasKerja->rincians->whereIn('kategori', $categories)->sum('jumlah');

                \App\Models\AnggaranRincian::updateOrCreate(
                    ['anggaran_id' => $anggaran->id, 'kode_item' => $kode],
                    [
                        'uraian' => $descriptions[$kode] ?? ucwords(str_replace('_', ' ', $kode)),
                        'besaran' => $sum,
                        'jumlah' => $sum,
                        'sisa_pagu' => $sum,
                    ]
                );
            }

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatch('swal', title: 'Berhasil!', text: 'Data Kertas Kerja ('.$kertasKerja->tahun_anggaran.') telah berhasil di-plot ke Master Anggaran.', icon: 'success');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatch('swal', title: 'Gagal!', text: 'Terjadi kesalahan: ' . $e->getMessage(), icon: 'error');
        }
    }
}
