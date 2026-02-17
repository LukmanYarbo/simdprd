<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JabatanAnggota extends Model
{
    use HasFactory;

    protected $table = 'jabatan_anggota';
    protected $fillable = ['id_alat_kelengkapan', 'id_jabatan_alat_kelengkapan', 'id_anggota', 'id_surat_keputusan'];

    public function alatKelengkapan()
    {
        return $this->belongsTo(AlatKelengkapan::class, 'id_alat_kelengkapan');
    }

    public function suratKeputusan()
    {
        return $this->belongsTo(SuratKeputusan::class, 'id_surat_keputusan');
    }

    // This refers to the position name (Ketua, Anggota, etc.)
    public function jabatanAlatKelengkapan()
    {
        return $this->belongsTo(JabatanAlatKelengkapan::class, 'id_jabatan_alat_kelengkapan');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
