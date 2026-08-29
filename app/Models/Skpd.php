<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    protected $table = 'skpds';
    protected $fillable = ['namaskpd'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_skpd');
    }

    public function pegawaiAsn()
    {
        return $this->hasMany(PegawaiAsn::class, 'id_skpd');
    }

    public function jabatanAsn()
    {
        return $this->hasMany(JabatanAsn::class, 'id_skpd');
    }

    public function penandaTangan()
    {
        return $this->hasMany(PenandaTangan::class, 'id_skpd');
    }
}
