<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendidikanAnggota extends Model
{
    protected $table = 'pendidikan_anggota';

    protected $fillable = [
        'id_anggota',
        'id_jenis_pendidikan',
        'tempat_pendidikan',
        'tahun_masuk',
        'tahun_lulus',
        'no_induk',
        'jurusan',
        'program_studi',
        'fakultas',
        'no_ijazah',
        'file_ijazah',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class , 'id_anggota');
    }

    public function jenisPendidikan()
    {
        return $this->belongsTo(JenisPendidikan::class , 'id_jenis_pendidikan');
    }
}
