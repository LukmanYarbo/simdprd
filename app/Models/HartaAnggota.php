<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HartaAnggota extends Model
{
    protected $fillable = [
        'id_anggota',
        'jenis_harta',
        'nama_harta',
        'keterangan',
        'tahun_perolehan',
        'harga_perolehan',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
