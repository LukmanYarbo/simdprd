<?php

namespace App\Livewire\Admin\Anggaran;

use App\Models\Anggaran;
use App\Models\AnggaranRincian;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AnggaranForm extends Component
{
    public $anggaran_id;
    public $tahun_anggaran;
    public $status = 'DRAFT';
    public $isEditMode = false;
    
    public $rincians = [];
    public $total_pagu = 0;

    protected $defaultItems = [
        ['kode_item' => 'gaji_pokok', 'uraian' => 'Gaji Pokok / Uang Representasi'],
        ['kode_item' => 'tunjangan_keluarga', 'uraian' => 'Tunjangan Keluarga'],
        ['kode_item' => 'tunjangan_jabatan', 'uraian' => 'Tunjangan Jabatan'],
        ['kode_item' => 'tunjangan_beras', 'uraian' => 'Tunjangan Beras'],
        ['kode_item' => 'tunjangan_pph', 'uraian' => 'Tunjangan PPh'],
        ['kode_item' => 'pembulatan', 'uraian' => 'Pembulatan'],
        ['kode_item' => 'uang_paket', 'uraian' => 'Uang Paket'],
        ['kode_item' => 'tunjangan_alat_kelengkapan', 'uraian' => 'Tunj. Alat Kelengkapan'],
        ['kode_item' => 'tunjangan_alat_kelengkapan_lainnya', 'uraian' => 'Tunj. AKD Lainnya (Pansus/Panja)'],
        ['kode_item' => 'tunjangan_perumahan', 'uraian' => 'Tunjangan Perumahan'],
        ['kode_item' => 'uang_jasa_pengabdian', 'uraian' => 'Uang Jasa Pengabdian'],
        ['kode_item' => 'tunjangan_reses', 'uraian' => 'Tunjangan Reses'],
        ['kode_item' => 'tunjangan_transportasi', 'uraian' => 'Tunjangan Transportasi'],
        ['kode_item' => 'jkk', 'uraian' => 'Asuransi JKK'],
        ['kode_item' => 'jkm', 'uraian' => 'Asuransi JKM'],
        ['kode_item' => 'tunjangan_komunikasi_insentif', 'uraian' => 'Komunikasi Insentif (TKI)'],
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditMode = true;
            $this->anggaran_id = $id;
            $anggaran = Anggaran::with('rincians')->findOrFail($id);
            $this->tahun_anggaran = $anggaran->tahun_anggaran;
            $this->status = $anggaran->status;
            
            $items = [];
            foreach ($anggaran->rincians as $rincian) {
                $items[] = [
                    'id' => $rincian->id,
                    'kode_item' => $rincian->kode_item,
                    'uraian' => $rincian->uraian,
                    'besaran' => number_format($rincian->besaran, 0, ',', '.'),
                    'jumlah' => $rincian->jumlah,
                ];
            }
            $this->rincians = $items;
            $this->calculateTotal();
        } else {
            $this->isEditMode = false;
            $this->tahun_anggaran = date('Y') + 1;
            
            $this->rincians = [];
            foreach ($this->defaultItems as $item) {
                $this->rincians[] = [
                    'id' => null,
                    'kode_item' => $item['kode_item'],
                    'uraian' => $item['uraian'],
                    'besaran' => '0',
                    'jumlah' => 0,
                ];
            }
        }
    }

    public function addRow()
    {
        $this->rincians[] = [
            'id' => null,
            'kode_item' => 'lainnya_' . time(),
            'uraian' => '',
            'besaran' => '0',
            'jumlah' => 0,
        ];
    }

    public function removeRow($index)
    {
        unset($this->rincians[$index]);
        $this->rincians = array_values($this->rincians);
        $this->calculateTotal();
    }

    public function updatedRincians($value, $name)
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->rincians as $index => &$rincian) {
            $besaran = (int) str_replace(['.', ','], '', $rincian['besaran'] ?? '0');
            
            $jumlah = $besaran;
            $rincian['jumlah'] = $jumlah;
            $total += $jumlah;
        }
        $this->total_pagu = $total;
    }

    public function store()
    {
        $this->validate([
            'tahun_anggaran' => 'required|integer|unique:anggarans,tahun_anggaran' . ($this->isEditMode ? ',' . $this->anggaran_id : ''),
            'status' => 'required|in:DRAFT,FINAL',
            'rincians.*.uraian' => 'required|string',
            'rincians.*.besaran' => 'required',
        ]);

        $this->calculateTotal();

        DB::transaction(function () {
            if ($this->isEditMode) {
                $anggaran = Anggaran::findOrFail($this->anggaran_id);
                $anggaran->update([
                    'tahun_anggaran' => $this->tahun_anggaran,
                    'status' => $this->status,
                    'total_pagu' => $this->total_pagu,
                ]);

                // Delete items that are no longer in the list (if any)
                $existingIds = collect($this->rincians)->pluck('id')->filter()->toArray();
                AnggaranRincian::where('anggaran_id', $anggaran->id)
                    ->whereNotIn('id', $existingIds)
                    ->delete();

                foreach ($this->rincians as $item) {
                    $jumlah = $item['jumlah'];
                    if (!empty($item['id'])) {
                        // update
                        $rincian = AnggaranRincian::find($item['id']);
                        $selisih = $jumlah - $rincian->jumlah;
                        $rincian->update([
                            'uraian' => $item['uraian'],
                            'besaran' => (int) str_replace(['.', ','], '', $item['besaran']),
                            'jumlah' => $jumlah,
                            'sisa_pagu' => $rincian->sisa_pagu + $selisih,
                        ]);
                    } else {
                        // create
                        AnggaranRincian::create([
                            'anggaran_id' => $anggaran->id,
                            'kode_item' => $item['kode_item'],
                            'uraian' => $item['uraian'],
                            'besaran' => (int) str_replace(['.', ','], '', $item['besaran']),
                            'jumlah' => $jumlah,
                            'sisa_pagu' => $jumlah,
                        ]);
                    }
                }
            } else {
                $anggaran = Anggaran::create([
                    'tahun_anggaran' => $this->tahun_anggaran,
                    'status' => $this->status,
                    'total_pagu' => $this->total_pagu,
                ]);

                foreach ($this->rincians as $item) {
                    $jumlah = $item['jumlah'];
                    AnggaranRincian::create([
                        'anggaran_id' => $anggaran->id,
                        'kode_item' => $item['kode_item'],
                        'uraian' => $item['uraian'],
                        'besaran' => (int) str_replace(['.', ','], '', $item['besaran']),
                        'jumlah' => $jumlah,
                        'sisa_pagu' => $jumlah,
                    ]);
                }
            }
        });

        session()->flash('swal', [
            'title' => 'Berhasil!',
            'text' => 'Data Anggaran berhasil ' . ($this->isEditMode ? 'diperbarui.' : 'disimpan.'),
            'icon' => 'success'
        ]);

        return redirect()->route('admin.anggaran.index');
    }

    public function render()
    {
        return view('livewire.admin.anggaran.anggaran-form');
    }
}
