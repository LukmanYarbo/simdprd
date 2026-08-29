<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKawin extends Model
{
    use HasFactory;

    protected $table = 'status_kawin';
    protected $fillable = ['nama'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_status_kawin', 'kode');
    }

    public function pegawaiAsn()
    {
        return $this->hasMany(PegawaiAsn::class, 'id_status_kawin', 'kode');
    }

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'id_status_kawin', 'id');
    }
}
