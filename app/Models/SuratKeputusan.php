<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeputusan extends Model
{
    use HasFactory;

    protected $table = 'surat_keputusan';
    protected $fillable = ['no_sk', 'ket_sk', 'tgl_sk', 'file_sk', 'id_alat_kelengkapan', 'status'];

    protected $casts = [
        'tgl_sk' => 'date',
    ];

    public function alatKelengkapan()
    {
        return $this->belongsTo(AlatKelengkapan::class, 'id_alat_kelengkapan');
    }

    public function jabatanAnggota()
    {
        return $this->hasMany(JabatanAnggota::class, 'id_surat_keputusan');
    }
}
