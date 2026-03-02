<?php

namespace App\Livewire\Admin\Anggota;

use App\Models\Anggota;
use Livewire\Component;

class Show extends Component
{
    public Anggota $anggota;

    public function mount(Anggota $anggota)
    {
        $this->anggota = $anggota->load(['agama', 'statusKawin', 'statusKeanggotaan', 'jabatan', 'keluarga.ikatanKeluarga', 'pendidikan.jenisPendidikan']);
    }

    public function render()
    {
        return view('livewire.admin.anggota.show');
    }
}
