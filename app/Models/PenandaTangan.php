<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenandaTangan extends Model
{
    use HasFactory;

    protected $table = 'penanda_tangan';

    protected $fillable = [
        'id_skpd',
        'id_anggota',
        'id_pegawai_asn',
        'jenis_dokumen',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function pegawaiAsn()
    {
        return $this->belongsTo(PegawaiAsn::class, 'id_pegawai_asn');
    }
}
