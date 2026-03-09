<?php

namespace App\Livewire\Admin\Potongan;

use App\Models\Potongan;
use Livewire\Component;

class ManagePotongan extends Component
{
    public $potongan_id;
    public $tunjangan_bpjs;
    public $potongan_bpjs;
    public $maksimal_potongan_bpjs;
    public $jkk;
    public $jkm;
    public $maks_jkkjkm;

    public $isEditMode = false;

    protected $listeners = ['deletePotongan'];

    public function render()
    {
        return view('livewire.admin.potongan.manage-potongan', [
            'potongans' => Potongan::latest()->get()
        ]);
    }

    public function resetFields()
    {
        $this->potongan_id = null;
        $this->tunjangan_bpjs = null;
        $this->potongan_bpjs = null;
        $this->maksimal_potongan_bpjs = null;
        $this->jkk = null;
        $this->jkm = null;
        $this->maks_jkkjkm = null;
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->resetFields();
        $this->dispatch('openModal');
    }

    public function store()
    {
        $this->validate([
            'tunjangan_bpjs' => 'required|numeric|min:0|max:100',
            'potongan_bpjs' => 'required|numeric|min:0|max:100',
            'maksimal_potongan_bpjs' => 'required|numeric|min:0',
            'jkk' => 'required|numeric|min:0|max:100',
            'jkm' => 'required|numeric|min:0|max:100',
            'maks_jkkjkm' => 'required|numeric|min:0',
        ]);

        Potongan::create([
            'tunjangan_bpjs' => $this->tunjangan_bpjs,
            'potongan_bpjs' => $this->potongan_bpjs,
            'maksimal_potongan_bpjs' => $this->maksimal_potongan_bpjs,
            'jkk' => $this->jkk,
            'jkm' => $this->jkm,
            'maks_jkkjkm' => $this->maks_jkkjkm,
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('swal', title: 'Berhasil', text: 'Data potongan berhasil ditambahkan.', icon: 'success');
        $this->resetFields();
    }

    public function edit($id)
    {
        $this->resetFields();
        $potongan = Potongan::findOrFail($id);
        $this->potongan_id = $potongan->id;
        $this->tunjangan_bpjs = $potongan->tunjangan_bpjs;
        $this->potongan_bpjs = $potongan->potongan_bpjs;
        $this->maksimal_potongan_bpjs = $potongan->maksimal_potongan_bpjs;
        $this->jkk = $potongan->jkk;
        $this->jkm = $potongan->jkm;
        $this->maks_jkkjkm = $potongan->maks_jkkjkm;
        $this->isEditMode = true;
        
        $this->dispatch('openModal');
    }

    public function update()
    {
        $this->validate([
            'tunjangan_bpjs' => 'required|numeric|min:0|max:100',
            'potongan_bpjs' => 'required|numeric|min:0|max:100',
            'maksimal_potongan_bpjs' => 'required|numeric|min:0',
            'jkk' => 'required|numeric|min:0|max:100',
            'jkm' => 'required|numeric|min:0|max:100',
            'maks_jkkjkm' => 'required|numeric|min:0',
        ]);

        $potongan = Potongan::findOrFail($this->potongan_id);
        $potongan->update([
            'tunjangan_bpjs' => $this->tunjangan_bpjs,
            'potongan_bpjs' => $this->potongan_bpjs,
            'maksimal_potongan_bpjs' => $this->maksimal_potongan_bpjs,
            'jkk' => $this->jkk,
            'jkm' => $this->jkm,
            'maks_jkkjkm' => $this->maks_jkkjkm,
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('swal', title: 'Berhasil', text: 'Data potongan berhasil diperbarui.', icon: 'success');
        $this->resetFields();
    }

    public function deletePotongan($id)
    {
        Potongan::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data potongan berhasil dihapus.', icon: 'success');
    }
}
