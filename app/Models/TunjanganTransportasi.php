<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TunjanganTransportasi extends Model
{
    protected $fillable = [
        'tgl_berlaku',
        'no_peraturan',
        'nilai_tunjangan_ketua',
        'nilai_tunjangan_wakil',
        'nilai_tunjangan_anggota',
        'file_peraturan',
        'status',
    ];
}
