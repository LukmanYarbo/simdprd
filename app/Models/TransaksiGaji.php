<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiGaji extends Model
{
    use HasFactory;

    protected $table = 'transaksi_gaji';

    protected $fillable = [
        'bln_thn',
        'id_anggota',
        'jumlah_is',
        'jumlah_anak',
        'status_kawin',
        'jumlah_pegawai',
        'jumlah_jiwa',
        'gaji_pokok',
        'tunjangan_anak',
        'tunjangan_istri',
        'tunjangan_beras',
        'tunjangan_paket',
        'tunjangan_jabatan',
        'tunjangan_komisi',
        'tunjangan_banggar',
        'tunjangan_banmus',
        'tunjangan_balegda',
        'tunjangan_bk',
        'tunjangan_pansus',
        'tunjangan_panja',
        'pembulatan',
        'brutto1',
        'brutto2',
        'tunjangan_pph21',
        'tunjangan_bpjs',
        'tunjangan_jkk',
        'tunjangan_jkm',
        'Kategori_TER',
        'Nilai_TER',
        'PPH21_Gaji',
        'PPh21_Tunjangan',
        'potongan_pph21',
        'potongan_bpjs',
        'potongan_bpjs2',
        'potongan_jkk',
        'potongan_jkm',
        'nilai_netto',
        'tunjangan_perumahan',
        'tunjangan_transportasi',
        'tunjangan_tki',
        'tunjangan_reses',
        'potonganpph_perumahan',
        'potonganpph_transportasi',
        'potonganpph_tki',
        'potonganpph_reses',
        'nilai_gajitunjangan',
        'total_potongan1',
        'total_potongan2',
        'jumlah_bersih',
        'detail_pajak',
    ];

    protected $casts = [
        'detail_pajak' => 'array',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
