<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PegawaiAsn extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_skpd', 'nip', 'nik', 'nokk', 'nama', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin',
        'id_agama', 'id_status_kawin', 'id_pangkat_golongan', 'id_jabatan', 'ket_jabatan', 'id_status_pegawai',
        'tanggal_mulai_kerja', 'tanggal_berhenti', 'email', 'nohp', 'norek', 'npwp', 'foto', 'id_ttd'
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'id_agama');
    }

    public function statusKawin()
    {
        return $this->belongsTo(StatusKawin::class, 'id_status_kawin', 'kode');
    }

    public function pangkatGolongan()
    {
        return $this->belongsTo(PangkatGolongan::class, 'id_pangkat_golongan');
    }

    public function jabatanAsn()
    {
        return $this->belongsTo(JabatanAsn::class, 'id_jabatan');
    }

    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'id_status_pegawai');
    }
}
