<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunjanganUmum extends Model
{
    use HasFactory;
    protected $fillable = [
        'tunjangan_beras',
        'jumlah_beras',
        'tunjangan_anak_persen',
        'tunjangan_istri_persen',
        'status',
    ];
}
