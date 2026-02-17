<?php

namespace App\Livewire\Admin\Anggota;

use App\Models\Anggota;
use Livewire\Component;

class Show extends Component
{
    public Anggota $anggota;

    public function mount(Anggota $anggota)
    {
        $this->anggota = $anggota->load(['agama', 'statusKawin', 'statusKeanggotaan', 'jabatan', 'keluarga.ikatanKeluarga']);
    }

    public function render()
    {
        return view('livewire.admin.anggota.show');
    }
}
