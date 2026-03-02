<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifLapisPajak extends Model
{
    protected $table = 'tarif_lapis_pajak';

    protected $fillable = [
        'id_tarif_pajak',
        'dari',
        'sampai',
        'persen',
    ];

    public function tarifPajak()
    {
        return $this->belongsTo(TarifPajak::class, 'id_tarif_pajak');
    }
}
