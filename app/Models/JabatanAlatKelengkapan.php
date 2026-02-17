<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanAlatKelengkapan extends Model
{
    use HasFactory;

    protected $table = 'jabatan_alat_kelengkapan';

    protected $fillable = ['nama'];
}
