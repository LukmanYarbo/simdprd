<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaSt extends Model
{
    use HasFactory;

    protected $table = 'anggota_st';

    protected $fillable = [
        'id_surat_tugas_anggota',
        'id_anggota',
    ];

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugasAnggota::class, 'id_surat_tugas_anggota');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
