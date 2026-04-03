<?php

namespace App\Livewire\Admin\KertasKerja;

use App\Models\KertasKerja;
use App\Models\KertasKerjaRincian;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KertasKerjaForm extends Component
{
    public $kertas_kerja_id;
    public $tahun_anggaran;
    public $status = 'DRAFT';
    public $isEditMode = false;

    public $total_pagu = 0;
    public $kategori_totals = [];

    // Default categories based on user requirement
    public $rincians = [];

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditMode = true;
            $this->kertas_kerja_id = $id;
            $kertasKerja = KertasKerja::with('rincians')->findOrFail($id);
            $this->tahun_anggaran = $kertasKerja->tahun_anggaran;
            $this->status = $kertasKerja->status;

            $items = [];
            foreach ($kertasKerja->rincians as $rincian) {
                $items[] = [
                    'id' => $rincian->id,
                    'kategori' => $rincian->kategori,
                    'jabatan' => $rincian->jabatan,
                    'uraian' => $rincian->uraian,
                    'besaran' => number_format($rincian->besaran, 0, ',', '.'),
                    'orang' => $rincian->orang,
                    'bulan_kali' => $rincian->bulan_kali,
                    'jumlah' => $rincian->jumlah,
                ];
            }
            $this->rincians = $items;
            $this->calculateTotal();
        } else {
            $this->tahun_anggaran = date('Y') + 1;
            $this->generateDefaultTemplate();
        }
    }

    private function generateDefaultTemplate()
    {
        // Define the template based on spec
        $template = [
            'Gaji Pokok' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Istri' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Anak' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Jabatan' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Beras' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan PPh' => ['Pimpinan & Anggota'],
            'Pembulatan' => ['Pimpinan & Anggota'],
            'Uang Paket' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Perumahan' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Transportasi' => ['Ketua', 'Wakil Ketua', 'Anggota'],
            'Tunjangan Komisi' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Tunjangan Banggar' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Tunjangan Banmus' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Tunjangan Balegda' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Tunjangan BK' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Tunjangan Pansus' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Tunjangan Panja' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
            'Komunikasi Insentif (TKI)' => ['Pimpinan & Anggota'],
            'Uang Jasa Pengabdian' => ['Pimpinan & Anggota'],
            'Tunjangan Reses' => ['Pimpinan & Anggota'],
            'Asuransi JKK' => ['Pimpinan & Anggota'],
            'Asuransi JKM' => ['Pimpinan & Anggota'],
        ];

        $items = [];
        foreach ($template as $kategori => $jabatans) {
            foreach ($jabatans as $jabatan) {
                $items[] = [
                    'id' => null,
                    'kategori' => $kategori,
                    'jabatan' => $jabatan,
                    'uraian' => $kategori . ' - ' . $jabatan,
                    'besaran' => '0',
                    'orang' => 1,
                    'bulan_kali' => 1,
                    'jumlah' => 0,
                ];
            }
        }
        $this->rincians = $items;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;
        $katTotals = [];

        foreach ($this->rincians as $index => &$rincian) {
            $besaran = (int) str_replace(['.', ','], '', $rincian['besaran'] ?? '0');
            $orang = (int) ($rincian['orang'] ?? 1);
            $bulan = (int) ($rincian['bulan_kali'] ?? 1);

            $jumlah = $besaran * $orang * $bulan;
            $rincian['jumlah'] = $jumlah;
            $total += $jumlah;

            $kat = $rincian['kategori'];
            if (!isset($katTotals[$kat])) {
                $katTotals[$kat] = 0;
            }
            $katTotals[$kat] += $jumlah;
        }
        $this->total_pagu = $total;
        $this->kategori_totals = $katTotals;
    }

    public function updated($property, $value)
    {
        if (str_starts_with($property, 'rincians.')) {
            $parts = explode('.', $property);
            if (count($parts) === 3 && $parts[2] === 'besaran') {
                $index = $parts[1];
                $itemChanged = $this->rincians[$index];

                if ($itemChanged['kategori'] === 'Gaji Pokok' && $itemChanged['jabatan'] === 'Ketua') {
                    $this->applyParameterDprd($value);
                }
            }
            $this->calculateTotal();
        }
    }

    private function applyParameterDprd($gajiPokokKetuaString)
    {
        $parameter = \App\Models\ParameterGaji::where('status', 'Y')->latest()->first();
        if (!$parameter)
            return;

        $gapokKetua = (int) str_replace(['.', ','], '', $gajiPokokKetuaString);
        if ($gapokKetua === 0)
            return;

        // Ambil data Tunjangan Umum untuk Beras & Keluarga
        $tunUmum = \App\Models\TunjanganUmum::where('status', 'Y')->latest()->first();
        $berasValue = $tunUmum ? ($tunUmum->tunjangan_beras * $tunUmum->jumlah_beras) : 0;
        $persenIstri = $tunUmum ? $tunUmum->tunjangan_istri_persen : 10;
        $persenAnak = $tunUmum ? $tunUmum->tunjangan_anak_persen : 2;

        // Ambil data Tunjangan Perumahan
        $tunPerumahan = \App\Models\TunjanganPerumahan::where('status', 'Y')->latest()->first();
        
        // Ambil data Tunjangan Transportasi
        $tunTransport = \App\Models\TunjanganTransportasi::where('status', 'Y')->latest()->first();

        // Ambil data TKI
        $tunTKI = \App\Models\TunjanganKomunikasiIntensif::where('status', 'Y')->latest()->first();

        // Hitung Gaji Pokok turunan
        $gapokWakil = $gapokKetua * ($parameter->persen_gapokwakil / 100);
        $gapokAnggota = $gapokKetua * ($parameter->persen_gapokanggota / 100);

        // Hitung Tunjangan Istri (Persentase dari Gapok masing-masing)
        $tunjIstriKetua = $gapokKetua * ($persenIstri / 100);
        $tunjIstriWakil = $gapokWakil * ($persenIstri / 100);
        $tunjIstriAnggota = $gapokAnggota * ($persenIstri / 100);

        // Hitung Tunjangan Anak (Persentase dari Gapok masing-masing)
        $tunjAnakKetua = $gapokKetua * ($persenAnak / 100);
        $tunjAnakWakil = $gapokWakil * ($persenAnak / 100);
        $tunjAnakAnggota = $gapokAnggota * ($persenAnak / 100);

        // Hitung Tunjangan Jabatan dari Gapok Ketua
        $tunjabKetua = $gapokKetua * ($parameter->persen_tunjabketua / 100);
        $tunjabWakil = $gapokWakil * ($parameter->persen_tunjabwakil / 100);
        $tunjabAnggota = $gapokAnggota * ($parameter->persen_tunjabanggota / 100);

        // Hitung Uang Paket masing-masing (dikali masing-masing Gapok)
        $uangPaketKetua = $gapokKetua * ($parameter->persen_uangpaket / 100);
        $uangPaketWakil = $gapokWakil * ($parameter->persen_uangpaket / 100);
        $uangPaketAnggota = $gapokAnggota * ($parameter->persen_uangpaket / 100);

        // Hitung Susunan Alat Kelengkapan dari Tunjab Ketua
        $tunAlegKetua = $tunjabKetua * ($parameter->persen_tunketua_aleg / 100);
        $tunAlegWakil = $tunjabKetua * ($parameter->persen_tunwakil_aleg / 100);
        $tunAlegSek = $tunjabKetua * ($parameter->persen_tunsek_aleg / 100);
        $tunAlegAnggota = $tunjabKetua * ($parameter->persen_tunanggota_aleg / 100);

        $alatKelengkapanCategories = [
            'Tunjangan Komisi',
            'Tunjangan Banggar',
            'Tunjangan Banmus',
            'Tunjangan Balegda',
            'Tunjangan BK',
            'Tunjangan Pansus',
            'Tunjangan Panja'
        ];

        // Terapkan kembali ke baris rincians
        foreach ($this->rincians as &$row) {
            $kat = $row['kategori'];
            $jab = $row['jabatan'];

            if ($kat === 'Gaji Pokok') {
                if ($jab === 'Wakil Ketua')
                    $row['besaran'] = number_format($gapokWakil, 0, ',', '.');
                if ($jab === 'Anggota')
                    $row['besaran'] = number_format($gapokAnggota, 0, ',', '.');
            } elseif ($kat === 'Tunjangan Istri') {
                if ($jab === 'Ketua') $row['besaran'] = number_format($tunjIstriKetua, 0, ',', '.');
                if ($jab === 'Wakil Ketua') $row['besaran'] = number_format($tunjIstriWakil, 0, ',', '.');
                if ($jab === 'Anggota') $row['besaran'] = number_format($tunjIstriAnggota, 0, ',', '.');
            } elseif ($kat === 'Tunjangan Anak') {
                if ($jab === 'Ketua') $row['besaran'] = number_format($tunjAnakKetua, 0, ',', '.');
                if ($jab === 'Wakil Ketua') $row['besaran'] = number_format($tunjAnakWakil, 0, ',', '.');
                if ($jab === 'Anggota') $row['besaran'] = number_format($tunjAnakAnggota, 0, ',', '.');
            } elseif ($kat === 'Tunjangan Beras') {
                if (in_array($jab, ['Ketua', 'Wakil Ketua', 'Anggota'])) {
                    $row['besaran'] = number_format($berasValue, 0, ',', '.');
                }
            } elseif ($kat === 'Tunjangan Jabatan') {
                if ($jab === 'Ketua')
                    $row['besaran'] = number_format($tunjabKetua, 0, ',', '.');
                if ($jab === 'Wakil Ketua')
                    $row['besaran'] = number_format($tunjabWakil, 0, ',', '.');
                if ($jab === 'Anggota')
                    $row['besaran'] = number_format($tunjabAnggota, 0, ',', '.');
            } elseif ($kat === 'Uang Paket') {
                if ($jab === 'Ketua')
                    $row['besaran'] = number_format($uangPaketKetua, 0, ',', '.');
                if ($jab === 'Wakil Ketua')
                    $row['besaran'] = number_format($uangPaketWakil, 0, ',', '.');
                if ($jab === 'Anggota')
                    $row['besaran'] = number_format($uangPaketAnggota, 0, ',', '.');
            } elseif ($kat === 'Tunjangan Perumahan') {
                if ($tunPerumahan) {
                    if ($jab === 'Ketua') $row['besaran'] = number_format($tunPerumahan->nilai_tunjangan_ketua, 0, ',', '.');
                    if ($jab === 'Wakil Ketua') $row['besaran'] = number_format($tunPerumahan->nilai_tunjangan_wakil, 0, ',', '.');
                    if ($jab === 'Anggota') $row['besaran'] = number_format($tunPerumahan->nilai_tunjangan_anggota, 0, ',', '.');
                }
            } elseif ($kat === 'Tunjangan Transportasi') {
                if ($tunTransport) {
                    if ($jab === 'Ketua') $row['besaran'] = number_format($tunTransport->nilai_tunjangan_ketua, 0, ',', '.');
                    if ($jab === 'Wakil Ketua') $row['besaran'] = number_format($tunTransport->nilai_tunjangan_wakil, 0, ',', '.');
                    if ($jab === 'Anggota') $row['besaran'] = number_format($tunTransport->nilai_tunjangan_anggota, 0, ',', '.');
                }
            } elseif ($kat === 'Komunikasi Insentif (TKI)') {
                if ($tunTKI) {
                    $row['besaran'] = number_format($tunTKI->nilai_tunjangan_tki, 0, ',', '.');
                }
            } elseif (in_array($kat, $alatKelengkapanCategories)) {
                if ($jab === 'Ketua')
                    $row['besaran'] = number_format($tunAlegKetua, 0, ',', '.');
                if ($jab === 'Wakil Ketua')
                    $row['besaran'] = number_format($tunAlegWakil, 0, ',', '.');
                if ($jab === 'Sekretaris')
                    $row['besaran'] = number_format($tunAlegSek, 0, ',', '.');
                if ($jab === 'Anggota')
                    $row['besaran'] = number_format($tunAlegAnggota, 0, ',', '.');
            }
        }
    }

    public function store()
    {
        $this->validate([
            'tahun_anggaran' => 'required|integer',
            'status' => 'required|in:DRAFT,FINAL',
            'rincians.*.besaran' => 'required',
            'rincians.*.orang' => 'required|integer|min:0',
            'rincians.*.bulan_kali' => 'required|integer|min:0',
        ]);

        $this->calculateTotal();

        // Check if tahun_anggaran is already taken by another record
        $existsQuery = KertasKerja::where('tahun_anggaran', $this->tahun_anggaran);
        if ($this->isEditMode) {
            $existsQuery->where('id', '!=', $this->kertas_kerja_id);
        }
        
        if ($existsQuery->exists()) {
            $this->addError('tahun_anggaran', 'Tahun anggaran ' . $this->tahun_anggaran . ' sudah memiliki kertas kerja.');
            return;
        }

        DB::beginTransaction();
        try {
            if ($this->isEditMode) {
                $kertasKerja = KertasKerja::findOrFail($this->kertas_kerja_id);
                $kertasKerja->update([
                    'tahun_anggaran' => $this->tahun_anggaran,
                    'status' => $this->status,
                    'total_pagu' => $this->total_pagu,
                ]);

                // Update details
                $existingIds = $kertasKerja->rincians->pluck('id')->toArray();
                $keptIds = [];

                foreach ($this->rincians as $item) {
                    $jumlah = (int) str_replace(['.', ','], '', $item['besaran']) * $item['orang'] * $item['bulan_kali'];

                    if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                        $rincian = KertasKerjaRincian::find($item['id']);
                        $rincian->update([
                            'besaran' => (int) str_replace(['.', ','], '', $item['besaran']),
                            'orang' => $item['orang'],
                            'bulan_kali' => $item['bulan_kali'],
                            'jumlah' => $jumlah,
                        ]);
                        $keptIds[] = $item['id'];
                    } else {
                        $newRecord = $kertasKerja->rincians()->create([
                            'kategori' => $item['kategori'],
                            'jabatan' => $item['jabatan'],
                            'uraian' => $item['uraian'],
                            'besaran' => (int) str_replace(['.', ','], '', $item['besaran']),
                            'orang' => $item['orang'],
                            'bulan_kali' => $item['bulan_kali'],
                            'jumlah' => $jumlah,
                        ]);
                        $keptIds[] = $newRecord->id;
                    }
                }

                // Delete removed items (if any, though template is fixed)
                KertasKerjaRincian::where('kertas_kerja_id', $kertasKerja->id)
                    ->whereNotIn('id', $keptIds)
                    ->delete();

                session()->flash('success', 'Data Kertas Kerja berhasil diperbarui.');
            } else {
                $kertasKerja = KertasKerja::create([
                    'tahun_anggaran' => $this->tahun_anggaran,
                    'status' => $this->status,
                    'total_pagu' => $this->total_pagu,
                ]);

                foreach ($this->rincians as $item) {
                    $jumlah = (int) str_replace(['.', ','], '', $item['besaran']) * $item['orang'] * $item['bulan_kali'];

                    $kertasKerja->rincians()->create([
                        'kategori' => $item['kategori'],
                        'jabatan' => $item['jabatan'],
                        'uraian' => $item['uraian'],
                        'besaran' => (int) str_replace(['.', ','], '', $item['besaran']),
                        'orang' => $item['orang'],
                        'bulan_kali' => $item['bulan_kali'],
                        'jumlah' => $jumlah,
                    ]);
                }

                session()->flash('success', 'Data Kertas Kerja berhasil disimpan.');
            }

            DB::commit();
            return redirect()->route('admin.kertas-kerja.index');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', title: 'Kesalahan!', text: 'Gagal merubah data: ' . $e->getMessage(), icon: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.kertas-kerja.kertas-kerja-form');
    }
}
