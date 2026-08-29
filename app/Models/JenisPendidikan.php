<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPendidikan extends Model
{
    protected $table = 'jenis_pendidikan';
    protected $fillable = ['nama'];

    public function pendidikanAnggota()
    {
        return $this->hasMany(PendidikanAnggota::class, 'id_jenis_pendidikan');
    }
}
