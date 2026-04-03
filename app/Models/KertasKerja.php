<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KertasKerja extends Model
{
    protected $fillable = ['tahun_anggaran', 'status', 'total_pagu'];

    public function rincians()
    {
        return $this->hasMany(KertasKerjaRincian::class, 'kertas_kerja_id');
    }
}
