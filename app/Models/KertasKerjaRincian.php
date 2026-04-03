<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KertasKerjaRincian extends Model
{
    protected $fillable = [
        'kertas_kerja_id',
        'kategori',
        'jabatan',
        'uraian',
        'besaran',
        'orang',
        'bulan_kali',
        'jumlah'
    ];

    public function kertasKerja()
    {
        return $this->belongsTo(KertasKerja::class, 'kertas_kerja_id');
    }
}
