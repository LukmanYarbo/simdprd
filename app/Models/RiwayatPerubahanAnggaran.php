<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPerubahanAnggaran extends Model
{
    use HasFactory;

    protected $table = 'riwayat_perubahan_anggarans';
    protected $guarded = [];

    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class, 'id_anggaran');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
