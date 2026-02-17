<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluarga';
    protected $fillable = [
        'id_anggota',
        'id_ikatan_keluarga',
        'id_status_kawin',
        'nik',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jk',
        'pekerjaan',
        'status_anak',
        'status_tunjangan',
        'no_sk_pengadilan',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function ikatanKeluarga()
    {
        return $this->belongsTo(IkatanKeluarga::class, 'id_ikatan_keluarga');
    }

    public function statusKawin()
    {
        return $this->belongsTo(StatusKawin::class, 'id_status_kawin');
    }
}
