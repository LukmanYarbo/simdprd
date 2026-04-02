<?php

namespace App\Livewire\Admin\Anggaran;

use App\Models\JurnalLra;
use Livewire\Component;
use Livewire\WithPagination;

class JurnalLraIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTahun = '';
    public $filterBulan = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterTahun() { $this->resetPage(); }
    public function updatingFilterBulan() { $this->resetPage(); }

    public function render()
    {
        $query = JurnalLra::query();

        if ($this->search) {
            $query->where('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhere('item_anggaran', 'like', '%' . $this->search . '%');
        }

        if ($this->filterTahun) {
            $query->where('bln_thn', 'like', '%-' . $this->filterTahun);
        }

        if ($this->filterBulan) {
            $query->where('bln_thn', 'like', $this->filterBulan . '-%');
        }

        return view('livewire.admin.anggaran.jurnal-lra-index', [
            'journals' => $query->latest()->paginate(20),
        ]);
    }
}
