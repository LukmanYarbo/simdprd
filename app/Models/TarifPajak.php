<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifPajak extends Model
{
    protected $table = 'tarif_pajak';

    protected $fillable = [
        'no_peraturan',
        'tgl_berlaku',
        'ptkp',
        'tambahan_ptkp_istri',
        'tambahan_ptkp_tanggungan',
        'persen_biaya_jabatan',
        'max_biaya_jabatan',
        'status',
    ];

    protected $casts = [
        'tgl_berlaku' => 'date',
    ];

    public function lapisPajak()
    {
        return $this->hasMany(TarifLapisPajak::class, 'id_tarif_pajak')->orderBy('dari');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }
}
