<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunjanganKomunikasiIntensif extends Model
{
    use HasFactory;
    protected $fillable = [
        'tgl_berlaku',
        'no_peraturan',
        'nilai_tunjangan_tki',
        'file_peraturan',
        'status',
    ];
}
