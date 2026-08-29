<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanAlatKelengkapan extends Model
{
    use HasFactory;

    protected $table = 'jabatan_alat_kelengkapan';

    protected $fillable = ['nama'];

    public function jabatanAnggota()
    {
        return $this->hasMany(JabatanAnggota::class, 'id_jabatan_alat_kelengkapan');
    }
}
