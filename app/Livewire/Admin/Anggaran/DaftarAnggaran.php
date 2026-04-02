<?php

namespace App\Livewire\Admin\Anggaran;

use App\Models\Anggaran;
use Livewire\Component;

class DaftarAnggaran extends Component
{
    public $anggarans;
    public $isEditMode = false;
    public $selected_id;

    // Status and History Modals
    public $alasan_perubahan = '';
    public $historyData = [];
    public $detailData = null;

    // Form fields
    public $tahun_anggaran;
    public $gaji_pokok = 0;
    public $tunjangan_keluarga = 0;
    public $tunjangan_jabatan = 0;
    public $tunjangan_beras = 0;
    public $tunjangan_pph = 0;
    public $pembulatan = 0;
    public $uang_paket = 0;
    public $tunjangan_alat_kelengkapan = 0;
    public $tunjangan_alat_kelengkapan_lainnya = 0;
    public $tunjangan_perumahan = 0;
    public $uang_jasa_pengabdian = 0;
    public $tunjangan_reses = 0;
    public $tunjangan_transportasi = 0;
    public $jkk = 0;
    public $jkm = 0;
    public $tunjangan_komunikasi_insentif = 0;
    public $status = 'DRAFT';

    protected $listeners = ['deleteAnggaran'];

    public function render()
    {
        $this->anggarans = Anggaran::orderBy('tahun_anggaran', 'desc')->get();
        return view('livewire.admin.anggaran.daftar-anggaran');
    }

    public function resetFields()
    {
        $this->reset(['selected_id', 'isEditMode', 'tahun_anggaran', 'gaji_pokok', 'tunjangan_keluarga', 'tunjangan_jabatan', 'tunjangan_beras', 'tunjangan_pph', 'pembulatan', 'uang_paket', 'tunjangan_alat_kelengkapan', 'tunjangan_alat_kelengkapan_lainnya', 'tunjangan_perumahan', 'uang_jasa_pengabdian', 'tunjangan_reses', 'tunjangan_transportasi', 'jkk', 'jkm', 'tunjangan_komunikasi_insentif', 'status']);
        $this->gaji_pokok = 0;
        $this->tunjangan_keluarga = 0;
        $this->tunjangan_jabatan = 0;
        $this->tunjangan_beras = 0;
        $this->tunjangan_pph = 0;
        $this->pembulatan = 0;
        $this->uang_paket = 0;
        $this->tunjangan_alat_kelengkapan = 0;
        $this->tunjangan_alat_kelengkapan_lainnya = 0;
        $this->tunjangan_perumahan = 0;
        $this->uang_jasa_pengabdian = 0;
        $this->tunjangan_reses = 0;
        $this->tunjangan_transportasi = 0;
        $this->jkk = 0;
        $this->jkm = 0;
        $this->tunjangan_komunikasi_insentif = 0;
    }

    public function store()
    {
        $this->validate([
            'tahun_anggaran' => 'required|numeric|unique:anggarans,tahun_anggaran',
            'status' => 'required|in:DRAFT,FINAL',
        ]);

        Anggaran::create($this->getFormData());

        $this->resetFields();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Anggaran berhasil ditambahkan.', icon: 'success');
    }

    public function edit($id)
    {
        $anggaran = Anggaran::findOrFail($id);
        $this->selected_id = $id;
        $this->tahun_anggaran = $anggaran->tahun_anggaran;
        $this->gaji_pokok = $anggaran->gaji_pokok;
        $this->tunjangan_keluarga = $anggaran->tunjangan_keluarga;
        $this->tunjangan_jabatan = $anggaran->tunjangan_jabatan;
        $this->tunjangan_beras = $anggaran->tunjangan_beras;
        $this->tunjangan_pph = $anggaran->tunjangan_pph;
        $this->pembulatan = $anggaran->pembulatan;
        $this->uang_paket = $anggaran->uang_paket;
        $this->tunjangan_alat_kelengkapan = $anggaran->tunjangan_alat_kelengkapan;
        $this->tunjangan_alat_kelengkapan_lainnya = $anggaran->tunjangan_alat_kelengkapan_lainnya;
        $this->tunjangan_perumahan = $anggaran->tunjangan_perumahan;
        $this->uang_jasa_pengabdian = $anggaran->uang_jasa_pengabdian;
        $this->tunjangan_reses = $anggaran->tunjangan_reses;
        $this->tunjangan_transportasi = $anggaran->tunjangan_transportasi;
        $this->jkk = $anggaran->jkk;
        $this->jkm = $anggaran->jkm;
        $this->tunjangan_komunikasi_insentif = $anggaran->tunjangan_komunikasi_insentif;
        $this->status = $anggaran->status;

        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'tahun_anggaran' => 'required|numeric|unique:anggarans,tahun_anggaran,' . $this->selected_id,
            'status' => 'required|in:DRAFT,FINAL',
        ]);

        $anggaran = Anggaran::findOrFail($this->selected_id);
        $anggaran->update($this->getFormData());

        $this->resetFields();
        $this->isEditMode = false;
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Anggaran berhasil diperbarui.', icon: 'success');
    }

    // Modal Status Unlock
    public function openStatusModal($id)
    {
        $this->selected_id = $id;
        $this->alasan_perubahan = '';
        $this->dispatch('show-status-modal');
    }

    public function updateStatus()
    {
        $this->validate([
            'alasan_perubahan' => 'required|min:5',
        ], [
            'alasan_perubahan.required' => 'Alasan perubahan wajib diisi.',
            'alasan_perubahan.min' => 'Alasan perubahan minimal 5 karakter.',
        ]);

        $anggaran = Anggaran::findOrFail($this->selected_id);
        
        $anggaran->riwayatPerubahans()->create([
            'status_sebelumnya' => $anggaran->status,
            'status_baru' => 'DRAFT',
            'alasan_perubahan' => $this->alasan_perubahan,
            'user_id' => auth()->id(),
        ]);

        $anggaran->update(['status' => 'DRAFT']);

        $this->dispatch('hide-status-modal');
        $this->dispatch('swal', title: 'Berhasil', text: 'Status anggaran dikembalikan ke DRAFT.', icon: 'success');
    }

    // Modal History
    public function showHistory($id)
    {
        $anggaran = Anggaran::with(['riwayatPerubahans' => function($q) {
            $q->latest();
        }, 'riwayatPerubahans.user'])->findOrFail($id);
        
        $this->historyData = $anggaran->riwayatPerubahans;
        $this->dispatch('show-history-modal');
    }

    public function showDetail($id)
    {
        $this->detailData = Anggaran::findOrFail($id);
        $this->dispatch('show-detail-modal');
    }

    public function deleteAnggaran($id)
    {
        Anggaran::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Anggaran berhasil dihapus.', icon: 'success');
    }

    protected function getFormData()
    {
        return [
            'tahun_anggaran' => $this->tahun_anggaran,
            'gaji_pokok' => $this->cleanNumber($this->gaji_pokok),
            'tunjangan_keluarga' => $this->cleanNumber($this->tunjangan_keluarga),
            'tunjangan_jabatan' => $this->cleanNumber($this->tunjangan_jabatan),
            'tunjangan_beras' => $this->cleanNumber($this->tunjangan_beras),
            'tunjangan_pph' => $this->cleanNumber($this->tunjangan_pph),
            'pembulatan' => $this->cleanNumber($this->pembulatan),
            'uang_paket' => $this->cleanNumber($this->uang_paket),
            'tunjangan_alat_kelengkapan' => $this->cleanNumber($this->tunjangan_alat_kelengkapan),
            'tunjangan_alat_kelengkapan_lainnya' => $this->cleanNumber($this->tunjangan_alat_kelengkapan_lainnya),
            'tunjangan_perumahan' => $this->cleanNumber($this->tunjangan_perumahan),
            'uang_jasa_pengabdian' => $this->cleanNumber($this->uang_jasa_pengabdian),
            'tunjangan_reses' => $this->cleanNumber($this->tunjangan_reses),
            'tunjangan_transportasi' => $this->cleanNumber($this->tunjangan_transportasi),
            'jkk' => $this->cleanNumber($this->jkk),
            'jkm' => $this->cleanNumber($this->jkm),
            'tunjangan_komunikasi_insentif' => $this->cleanNumber($this->tunjangan_komunikasi_insentif),
            'status' => $this->status,
        ];
    }

    protected function cleanNumber($value)
    {
        if (is_string($value)) {
            return (int) str_replace([',', '.'], '', $value);
        }
        return (int) $value;
    }
}
