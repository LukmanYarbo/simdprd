<?php

namespace App\Livewire\Admin\Anggaran;

use App\Models\Anggaran;
use Livewire\Component;

class DaftarAnggaran extends Component
{
    public $anggarans;
    public $selected_id;

    // Status and History Modals
    public $alasan_perubahan = '';
    public $historyData = [];
    public $detailData = null;

    protected $listeners = ['deleteAnggaran'];

    public function render()
    {
        $this->anggarans = Anggaran::with('rincians')
            ->withSum('jurnalLra as total_debet', 'debet')
            ->withSum('jurnalLra as total_kredit', 'kredit')
            ->orderBy('tahun_anggaran', 'desc')
            ->get();
            
        return view('livewire.admin.anggaran.daftar-anggaran')->layout('layouts.admin');
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
        $this->detailData = Anggaran::with('rincians')->findOrFail($id);
        $this->dispatch('show-detail-modal');
    }

    public function deleteAnggaran($id)
    {
        Anggaran::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Anggaran berhasil dihapus.', icon: 'success');
    }
}
