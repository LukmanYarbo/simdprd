<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'id_skpd',
        'nik',
        'nokk',
        'nama_anggota',
        'tempat_lahir',
        'tgl_lahir',
        'id_agama',
        'jk',
        'id_status_kawin',
        'jmlh_istri',
        'jmlh_anak',
        'no_telp',
        'email',
        'no_rekening',
        'no_npwp',
        'prov',
        'kab',
        'kec',
        'desa',
        'alamat_lengkap',
        'id_status_keanggotaan',
        'id_dprd',
        'nama_komisi',
        'tgl_mulai',
        'tgl_berhenti',
        'status_bpjs',
        'no_bpjs',
        'status_jkk',
        'no_jkk',
        'status_jkm',
        'no_jkm',
        'status_tjgn_perum',
        'status_tjgn_transport',
        'foto_anggota',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_mulai' => 'date',
        'tgl_berhenti' => 'date',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'id_skpd');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class , 'id_agama');
    }

    public function statusKawin()
    {
        return $this->belongsTo(StatusKawin::class, 'id_status_kawin', 'kode');
    }

    public function statusKeanggotaan()
    {
        return $this->belongsTo(StatusKeanggotaan::class , 'id_status_keanggotaan');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanDPRD::class , 'id_dprd');
    }

    public function jabatanKomisi()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_komisi');
    }

    public function jabatanBanggar()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_banggar');
    }

    public function jabatanBanmus()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_banmus');
    }

    public function jabatanBalegda()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_balegda');
    }

    public function jabatanBk()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_bk');
    }

    public function jabatanPansus()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_pansus');
    }

    public function jabatanPanja()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class , 'id_panja');
    }

    public function jabatanAnggota()
    {
        return $this->hasMany(JabatanAnggota::class , 'id_anggota');
    }

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class , 'id_anggota');
    }
    public function pendidikan()
    {
        return $this->hasMany(PendidikanAnggota::class , 'id_anggota');
    }

}
