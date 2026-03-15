<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DsbGaji extends Model
{
    protected $table = 'dsb_gaji';

    protected $fillable = [
        'bln_thn',
        'jumlah_jiwa',
        'jumlah_pegawai',
        'jumlah_is',
        'jumlah_anak',
        'jumlah_ketua',
        'jumlah_wakil',
        'jumlah_anggota',
        'jumlah_is_ketua',
        'jumlah_anak_ketua',
        'jumlah_is_wakil',
        'jumlah_anak_wakil',
        'jumlah_is_anggota',
        'jumlah_anak_anggota',
        'nama_pa',
        'nip_pa',
        'golongan_pa',
        'jabatan_pa',
        'nama_bendahara',
        'nip_bendahara',
        'golongan_bendahara',
        'jabatan_bendahara',
        'tanggal_proses',
        'status',
        'alasan_perubahan',
    ];

    /**
     * Get the transaksi gaji records associated with this dsb gaji.
     */
    public function transaksi()
    {
        return $this->hasMany(TransaksiGaji::class, 'bln_thn', 'bln_thn');
    }
}
