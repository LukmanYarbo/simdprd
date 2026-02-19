<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemda extends Model
{
    use HasFactory;

    protected $fillable = [
        'namapemda', 'alamat', 'kota', 'kabupaten', 'propinsi', 'kode_pos',
        'nama_bupati', 'jabatan_bupati', 'judul_bupati',
        'nama_wakil_bupati', 'jabatan_wakil_bupati', 'judul_wakil_bupati',
        'id_sekda', 'logo_pemda'
    ];

    public function sekda()
    {
        return $this->belongsTo(PegawaiAsn::class, 'id_sekda');
    }
}
