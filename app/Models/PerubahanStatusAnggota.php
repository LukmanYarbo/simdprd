<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerubahanStatusAnggota extends Model
{
    use HasFactory;

    protected $table = 'perubahan_status_anggota';

    protected $fillable = [
        'id_anggota',
        'id_status_keanggotaan',
        'tgl_perubahan',
        'no_sk',
        'alasan',
        'file_sk',
    ];

    protected $casts = [
        'tgl_perubahan' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function statusKeanggotaan()
    {
        return $this->belongsTo(StatusKeanggotaan::class, 'id_status_keanggotaan');
    }
}
