<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JabatanAsn extends Model
{
    use HasFactory;
    
    protected $fillable = ['nama_jabatan', 'id_esselon'];
    
    public function esselon()
    {
        return $this->belongsTo(Esselon::class, 'id_esselon');
    }
}
