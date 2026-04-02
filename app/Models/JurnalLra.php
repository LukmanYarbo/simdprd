<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalLra extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'jurnal_lras';

    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class, 'id_anggaran');
    }
}
