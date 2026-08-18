<?php

namespace App\Livewire\Admin\Anggota;

use App\Models\Anggota;
use App\Models\StatusKeanggotaan;
use App\Models\PerubahanStatusAnggota;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageStatusChange extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedMember = null;
    public $isModalOpen = false;

    // Form fields
    public $id_status_keanggotaan;
    public $tgl_perubahan;
    public $no_sk;
    public $alasan;
    public $file_sk;

    protected $rules = [
        'id_status_keanggotaan' => 'required|exists:status_keanggotaan,id',
        'tgl_perubahan' => 'required|date',
        'no_sk' => 'nullable|string|max:255',
        'alasan' => 'nullable|string',
        'file_sk' => 'nullable|file|max:2048', // 2MB max
    ];

    public function mount()
    {
        $this->tgl_perubahan = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($memberId)
    {
        $this->resetValidation();
        $this->selectedMember = Anggota::findOrFail($memberId);
        $this->id_status_keanggotaan = $this->selectedMember->id_status_keanggotaan;
        $this->tgl_perubahan = date('Y-m-d');
        $this->no_sk = '';
        $this->alasan = '';
        $this->file_sk = null;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedMember = null;
    }

    public function saveStatusChange()
    {
        $this->validate();

        $filePath = null;
        if ($this->file_sk) {
            $extension = $this->file_sk->getClientOriginalExtension();
            $filename = 'sk_status_' . Str::slug($this->selectedMember->nama_anggota) . '_' . now()->format('Ymd_His') . '.' . $extension;
            $filePath = $this->file_sk->storeAs('surat_keputusan', $filename, 'public');
        }

        // 1. Create history record
        PerubahanStatusAnggota::create([
            'id_anggota' => $this->selectedMember->id,
            'id_status_keanggotaan' => $this->id_status_keanggotaan,
            'tgl_perubahan' => $this->tgl_perubahan,
            'no_sk' => $this->no_sk,
            'alasan' => $this->alasan,
            'file_sk' => $filePath,
        ]);

        // 2. Update member record
        $updateData = [
            'id_status_keanggotaan' => $this->id_status_keanggotaan,
        ];

        // Check if new status is not "Aktif" (ID 1 assumed based on earlier tinker output)
        if ($this->id_status_keanggotaan != 1) {
            $updateData['tgl_berhenti'] = $this->tgl_perubahan;
        } else {
            $updateData['tgl_berhenti'] = null;
        }

        $this->selectedMember->update($updateData);

        $this->closeModal();
        $this->dispatch('swal', title: 'Berhasil', text: 'Status keanggotaan berhasil diperbarui.', icon: 'success');
    }

    public function render()
    {
        $anggota = Anggota::with(['statusKeanggotaan', 'jabatan'])
            ->where(function($query) {
                $query->where('nama_anggota', 'like', '%' . $this->search . '%')
                      ->orWhere('nik', 'like', '%' . $this->search . '%');
            })
            ->leftJoin('jabatan_dprd', 'anggota.id_dprd', '=', 'jabatan_dprd.id')
            ->orderByRaw("CASE
                WHEN LOWER(jabatan_dprd.nama) LIKE '%ketua%' THEN 1
                WHEN LOWER(jabatan_dprd.nama) LIKE '%wakil%' THEN 2
                WHEN LOWER(jabatan_dprd.nama) LIKE '%anggota%' THEN 3
                ELSE 4
            END")
            ->orderBy('jabatan_dprd.nama')
            ->orderBy('nama_anggota')
            ->select('anggota.*')
            ->paginate(10);

        $statuses = StatusKeanggotaan::all();
        
        // Fetch recent history
        $history = PerubahanStatusAnggota::with(['anggota', 'statusKeanggotaan'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.anggota.manage-status-change', [
            'anggota' => $anggota,
            'statuses' => $statuses,
            'history' => $history,
        ]);
    }
}
