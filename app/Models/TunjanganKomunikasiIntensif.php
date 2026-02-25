<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TunjanganKomunikasiIntensif extends Model
{
    protected $fillable = [
        'tgl_berlaku',
        'no_peraturan',
        'nilai_tunjangan_tki',
        'file_peraturan',
        'status',
    ];
}
