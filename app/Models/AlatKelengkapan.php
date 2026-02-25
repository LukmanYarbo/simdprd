<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlatKelengkapan extends Model
{
    use HasFactory;

    protected $table = 'alat_kelengkapan';
    protected $fillable = ['nama', 'ket', 'nama_komisi'];

    public function suratKeputusan()
    {
        return $this->hasMany(SuratKeputusan::class, 'id_alat_kelengkapan');
    }

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class, 'id_alat_kelengkapan');
    }
}
