<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    use HasFactory;

    protected $table = 'agama';
    protected $fillable = ['nama'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_agama');
    }

    public function pegawaiAsn()
    {
        return $this->hasMany(PegawaiAsn::class, 'id_agama');
    }
}
