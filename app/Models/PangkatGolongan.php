<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PangkatGolongan extends Model
{
    protected $table = 'pangkat_golongans';
    protected $fillable = ['pangkat', 'golongan'];

    public function pegawaiAsn()
    {
        return $this->hasMany(PegawaiAsn::class, 'id_pangkat_golongan');
    }
}
