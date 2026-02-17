<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkatanKeluarga extends Model
{
    use HasFactory;

    protected $table = 'ikatan_keluarga';
    protected $fillable = ['nama'];

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'id_ikatan_keluarga');
    }
}
