<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterGaji extends Model
{
    protected $table = 'parameter_gajis';

    protected $fillable = [
        'no_peraturan',
        'tgl_berlaku',
        'gajipokok_ketua',
        'persen_gapokwakil',
        'persen_gapokanggota',
        'persen_tunjabketua',
        'persen_tunjabwakil',
        'persen_tunjabanggota',
        'persen_tunketua_aleg',
        'persen_tunwakil_aleg',
        'persen_tunsek_aleg',
        'persen_tunanggota_aleg',
        'persen_uangpaket',
        'status',
        'file',
    ];

    protected $casts = [
        'tgl_berlaku' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }
}
