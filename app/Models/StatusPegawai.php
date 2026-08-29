<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPegawai extends Model
{
    protected $table = 'status_pegawais';
    protected $fillable = ['nama'];

    public function pegawaiAsn()
    {
        return $this->hasMany(PegawaiAsn::class, 'id_status_pegawai');
    }
}
