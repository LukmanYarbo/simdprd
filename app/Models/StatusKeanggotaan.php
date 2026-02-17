<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKeanggotaan extends Model
{
    use HasFactory;

    protected $table = 'status_keanggotaan';
    protected $fillable = ['nama'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_status_keanggotaan');
    }
}
