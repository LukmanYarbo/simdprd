<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Esselon extends Model
{
    use HasFactory;
    
    protected $fillable = ['nama'];
    
    public function jabatanAsn()
    {
        return $this->hasMany(JabatanAsn::class, 'id_esselon');
    }
}
