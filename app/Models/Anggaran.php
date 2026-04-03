<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rincians()
    {
        return $this->hasMany(AnggaranRincian::class, 'anggaran_id');
    }

    public function jurnalLra()
    {
        return $this->hasMany(JurnalLra::class, 'id_anggaran');
    }

    public function riwayatPerubahans()
    {
        return $this->hasMany(RiwayatPerubahanAnggaran::class, 'id_anggaran');
    }
}
