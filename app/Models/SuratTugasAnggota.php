<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugasAnggota extends Model
{
    use HasFactory;

    protected $table = 'surat_tugas_anggota';

    protected $fillable = [
        'no_surat_tugas',
        'uraian',
        'tempat_asal',
        'tempat_tujuan',
        'tanggal_berangkat',
        'tanggal_balik',
        'lama_hari',
        'tanggal_ditetapkan',
        'id_anggota_penandatangan',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_balik' => 'date',
        'tanggal_ditetapkan' => 'date',
    ];

    public function penandatangan()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota_penandatangan');
    }

    public function anggotaSt()
    {
        return $this->hasMany(AnggotaSt::class, 'id_surat_tugas_anggota');
    }
}
