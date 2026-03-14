<?php

namespace App\Http\Controllers\Admin\Gaji;

use App\Http\Controllers\Controller;
use App\Models\TransaksiGaji;
use App\Models\PenandaTangan;
use App\Models\Anggota;
use App\Models\Pemda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DsbGajiController extends Controller
{
    public function report(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $blnThn = $bulan . '-' . $tahun;

        $data = TransaksiGaji::where('bln_thn', $blnThn)->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Data gaji untuk periode ini belum diproses.');
        }

        // Aggregate income and deductions
        $summary = [
            'gaji_pokok' => $data->sum('gaji_pokok'),
            'tunjangan_istri' => $data->sum('tunjangan_istri'),
            'tunjangan_anak' => $data->sum('tunjangan_anak'),
            'tunjangan_jabatan' => $data->sum('tunjangan_jabatan'),
            'tunjangan_beras' => $data->sum('tunjangan_beras'),
            'tunjangan_pph_khusus' => $data->sum('PPH21_Gaji'),
            'pembulatan' => $data->sum('pembulatan'),
            'tunjangan_paket' => $data->sum('tunjangan_paket'),
            'tunjangan_ak' => $data->sum(function($item) {
                return $item->tunjangan_komisi + $item->tunjangan_banggar + $item->tunjangan_banmus + 
                       $item->tunjangan_balegda + $item->tunjangan_bk + $item->tunjangan_pansus + 
                       $item->tunjangan_panja;
            }),
            'tunjangan_perumahan' => $data->sum('tunjangan_perumahan'),
            'tunjangan_tki' => $data->sum('tunjangan_tki'),
            'tunjangan_transportasi' => $data->sum('tunjangan_transportasi'),
            'tunjangan_reses' => $data->sum('tunjangan_reses'),
            'tunjangan_jkk' => $data->sum('tunjangan_jkk'),
            'tunjangan_jkm' => $data->sum('tunjangan_jkm'),
            'tunjangan_bpjs' => $data->sum('tunjangan_bpjs'),
            
            'potongan_pph21' => $data->sum('potongan_pph21'),
            'potongan_bpjs1' => $data->sum('potongan_bpjs'), // In our mapping bpjs1% is in potongan_bpjs
            'potongan_jkk' => $data->sum('potongan_jkk'),
            'potongan_jkm' => $data->sum('potongan_jkm'),
            'potongan_bpjs3' => $data->sum('potongan_bpjs2'), // Assume bpjs3% in bpjs2
            
            'jumlah_bersih' => 0, // Dihitung di bawah
        ];

        $summary['total_penghasilan'] = array_sum(array_slice($summary, 0, 16));
        $summary['total_potongan'] = array_sum(array_slice($summary, 16, 5));
        
        // Force consistency between details and net
        $summary['jumlah_bersih'] = $summary['total_penghasilan'] - $summary['total_potongan'];
        
        $summary['terbilang'] = ucwords(trim($this->terbilang($summary['jumlah_bersih']))) . " Rupiah";

        // Group by position (Ketua, Wakil Ketua, Anggota)
        $detailsByPosition = TransaksiGaji::where('bln_thn', $blnThn)
            ->join('anggota', 'transaksi_gaji.id_anggota', '=', 'anggota.id')
            ->select(
                DB::raw('CASE WHEN anggota.id_dprd = 1 THEN "KETUA" 
                             WHEN anggota.id_dprd = 2 THEN "WAKIL KETUA" 
                             ELSE "ANGGOTA" END as jabatan_group'),
                'anggota.id_dprd',
                DB::raw('COUNT(*) as pegawai'),
                DB::raw('SUM(transaksi_gaji.jumlah_is) as istri'),
                DB::raw('SUM(transaksi_gaji.jumlah_anak) as anak'),
                DB::raw('SUM(transaksi_gaji.jumlah_jiwa) as jiwa')
            )
            ->groupBy('anggota.id_dprd', 'jabatan_group')
            ->orderBy('anggota.id_dprd', 'asc')
            ->get();

        // Signatories
        $bendahara = PenandaTangan::where('jenis_dokumen', 'like', '%Pengajuan Gaji%')
            ->orWhere('jenis_dokumen', 'like', '%pengesahan gaji%')
            ->with(['pegawaiAsn.jabatanAsn', 'pegawaiAsn.pangkatGolongan'])
            ->get()
            ->filter(function($item) {
                return str_contains(strtolower($item->pegawaiAsn->jabatanAsn->nama_jabatan ?? ''), 'bendahara');
            })->first();

        $sekretaris = PenandaTangan::where('jenis_dokumen', 'like', '%Pengajuan Gaji%')
            ->orWhere('jenis_dokumen', 'like', '%pengesahan gaji%')
            ->with(['pegawaiAsn.jabatanAsn', 'pegawaiAsn.pangkatGolongan'])
            ->get()
            ->filter(function($item) {
                return str_contains(strtolower($item->pegawaiAsn->jabatanAsn->nama_jabatan ?? ''), 'sekretaris dprd');
            })->first();

        $pemda = Pemda::first();

        $bulanLabel = $this->getBulanLabel($bulan);

        return view('admin.gaji.reports.dsb-gaji', compact('summary', 'detailsByPosition', 'bendahara', 'sekretaris', 'pemda', 'bulanLabel', 'tahun'));
    }

    private function getBulanLabel($bulan)
    {
        $bulan = (int)$bulan;
        $labels = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL', 
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS', 
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        return $labels[$bulan] ?? '';
    }

    private function terbilang($nilai) {
        $nilai = abs($nilai);
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->terbilang($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->terbilang($nilai / 10) . " puluh" . $this->terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->terbilang($nilai / 100) . " ratus" . $this->terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->terbilang($nilai / 1000) . " ribu" . $this->terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->terbilang($nilai / 1000000) . " juta" . $this->terbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->terbilang($nilai / 1000000000) . " milyar" . $this->terbilang(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->terbilang($nilai / 1000000000000) . " trilyun" . $this->terbilang(fmod($nilai, 1000000000000));
        }     
        return $temp;
    }
}
