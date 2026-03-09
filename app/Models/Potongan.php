<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Potongan extends Model
{
    protected $fillable = [
        'tunjangan_bpjs',
        'potongan_bpjs',
        'maksimal_potongan_bpjs',
        'jkk',
        'jkm',
        'maks_jkkjkm',
    ];

    protected $casts = [
        'tunjangan_bpjs' => 'decimal:2',
        'potongan_bpjs' => 'decimal:2',
        'maksimal_potongan_bpjs' => 'decimal:2',
        'jkk' => 'decimal:2',
        'jkm' => 'decimal:2',
        'maks_jkkjkm' => 'decimal:2',
    ];
}
