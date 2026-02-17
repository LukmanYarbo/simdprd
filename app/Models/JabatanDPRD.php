<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanDPRD extends Model
{
    use HasFactory;

    protected $table = 'jabatan_dprd';
    protected $fillable = ['nama'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_dprd');
    }
}
