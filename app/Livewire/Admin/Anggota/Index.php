<?php

namespace App\Livewire\Admin\Anggota;

use App\Models\Anggota;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $item = Anggota::findOrFail($id);
        if ($item->foto_anggota) {
            \Storage::disk('public')->delete($item->foto_anggota);
        }
        $item->delete();

        session()->flash('success', 'Anggota berhasil dihapus.');
    }

    public function render()
    {
        $anggota = Anggota::with(['agama', 'statusKawin', 'statusKeanggotaan', 'jabatan'])
            ->where(function($query) {
                $query->where('nama_anggota', 'like', '%' . $this->search . '%')
                      ->orWhere('nik', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.anggota.index', [
            'anggota' => $anggota,
        ]);
    }
}
