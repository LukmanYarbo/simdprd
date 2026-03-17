<?php

namespace App\Http\Controllers\Admin\Gaji;

use App\Http\Controllers\Controller;
use App\Models\TransaksiGaji;
use App\Models\PenandaTangan;
use App\Models\Anggota;
use App\Models\Pemda;
use App\Models\DsbGaji;
use App\Services\GajiCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DsbGajiController extends Controller
{
    protected $gajiService;

    public function __construct(GajiCalculatorService $gajiService)
    {
        $this->gajiService = $gajiService;
    }

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
            'tunjangan_pph_khusus' => $data->sum('potongan_pph21'),
            'pembulatan' => $data->sum('pembulatan'),
            'tunjangan_paket' => $data->sum('tunjangan_paket'),
            'tunjangan_ak' => $data->sum(function ($item) {
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

            'potongan_pph21' => $data->sum('potongan_pph21') + $data->sum('potonganpph_perumahan') + $data->sum('potonganpph_transportasi') + $data->sum('potonganpph_tki'),
            'potongan_bpjs1' => $data->sum('potongan_bpjs'), // In our mapping bpjs1% is in potongan_bpjs
            'potongan_jkk' => $data->sum('potongan_jkk'),
            'potongan_jkm' => $data->sum('potongan_jkm'),
            'potongan_bpjs3' => $data->sum('potongan_bpjs2'), // Assume bpjs3% in bpjs2
            'potonganpph_perumahan' => $data->sum('potonganpph_perumahan'),
            'potonganpph_transportasi' => $data->sum('potonganpph_transportasi'),
            'potonganpph_tki' => $data->sum('potonganpph_tki'),

            'jumlah_bersih' => 0, // Dihitung di bawah
        ];

        $summary['total_penghasilan'] = array_sum(array_slice($summary, 0, 16));
        $summary['total_potongan'] = array_sum(array_slice($summary, 16, 5));

        // Force consistency between details and net
        $summary['jumlah_bersih'] = $summary['total_penghasilan'] - $summary['total_potongan'];

        $summary['terbilang'] = ucwords(trim($this->terbilang($summary['jumlah_bersih']))) . " Rupiah";

        // Use GajiCalculatorService for details, signatories, and pemda
        $dsbData = $this->gajiService->getDsbGajiData($blnThn);
        $detailsByPosition = $dsbData['detailsByPosition'];
        $bendahara = $dsbData['bendahara'];
        $sekretaris = $dsbData['sekretaris'];
        $pemda = $dsbData['pemda'];

        // Fetch existing dsb_gaji to preserve the user-selected tanggal_proses
        $existingDsb = DsbGaji::where('bln_thn', $blnThn)->first();
        $tanggalTerakhir = $existingDsb->tanggal_proses ?? null;

        // Save/Update to dsb_gaji table with preserved date
        $this->gajiService->saveDsbGaji($blnThn, $tanggalTerakhir);

        // Fetch the fresh record for the report
        $dsbGaji = DsbGaji::where('bln_thn', $blnThn)->first();

        $bulanLabel = $this->getBulanLabel($bulan);

        return view('admin.gaji.reports.dsb-gaji', compact(
            'summary',
            'detailsByPosition',
            'bendahara',
            'sekretaris',
            'pemda',
            'bulanLabel',
            'tahun',
            'dsbGaji'
        ));
    }

    public function daftarGaji(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $blnThn = $bulan . '-' . $tahun;

        $transaksi = TransaksiGaji::where('bln_thn', $blnThn)
            ->with(['anggota', 'anggota.jabatan'])
            ->join('anggota', 'transaksi_gaji.id_anggota', '=', 'anggota.id')
            ->select('transaksi_gaji.*')
            ->orderBy('anggota.id_dprd', 'asc')
            ->orderBy('anggota.id_komisi', 'asc')
            ->orderBy('anggota.nama_komisi', 'asc')
            ->orderBy('anggota.nama_anggota', 'asc')
            ->get();

        if ($transaksi->isEmpty()) {
            return back()->with('error', 'Data gaji untuk periode ini belum diproses.');
        }

        $dsbGaji = DsbGaji::where('bln_thn', $blnThn)->first();
        $bulanLabel = $this->getBulanLabel($bulan);
        $pemda = Pemda::first();

        return view('admin.gaji.reports.daftar-gaji', compact(
            'transaksi',
            'dsbGaji',
            'bulanLabel',
            'tahun',
            'pemda'
        ));
    }

    public function tunjanganReport(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $blnThn = $bulan . '-' . $tahun;

        $transaksi = TransaksiGaji::where('bln_thn', $blnThn)
            ->with(['anggota', 'anggota.jabatan'])
            ->join('anggota', 'transaksi_gaji.id_anggota', '=', 'anggota.id')
            ->select('transaksi_gaji.*')
            ->orderBy('anggota.id_dprd', 'asc')
            ->orderBy('anggota.nama_anggota', 'asc')
            ->get();

        if ($transaksi->isEmpty()) {
            return back()->with('error', 'Data gaji untuk periode ini belum diproses.');
        }

        $dsbGaji = DsbGaji::where('bln_thn', $blnThn)->first();
        $bulanLabel = $this->getBulanLabel($bulan);
        $pemda = Pemda::first();

        return view('admin.gaji.reports.tunjangan-report', compact(
            'transaksi',
            'dsbGaji',
            'bulanLabel',
            'tahun',
            'pemda'
        ));
    }

    public function slipGaji(Request $request, $id)
    {
        $transaksi = TransaksiGaji::with([
            'anggota.jabatan', 
            'anggota.statusKeanggotaan',
            'anggota.jabatanAnggota.alatKelengkapan',
            'anggota.jabatanAnggota.jabatanAlatKelengkapan'
        ])->findOrFail($id);
        
        // Use the existing logic to format month name
        $part = explode('-', $transaksi->bln_thn);
        $bulanNum = $part[0];
        $tahun = $part[1];
        
        $bulanLabel = $this->getBulanLabel($bulanNum);

        $dsbGaji = DsbGaji::where('bln_thn', $transaksi->bln_thn)->first();
        $pemda = Pemda::first();
        
        // Using same terbilang definition as in the class
        $terbilang = ucwords(trim($this->terbilang($transaksi->jumlah_bersih))) . " Rupiah";

        return view('admin.gaji.reports.slip-gaji', compact(
            'transaksi',
            'dsbGaji',
            'pemda',
            'bulanLabel',
            'tahun',
            'terbilang'
        ));
    }

    public function slipGajiBulk(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $blnThn = $bulan . '-' . $tahun;

        $transaksi = TransaksiGaji::where('bln_thn', $blnThn)
            ->with([
                'anggota.jabatan', 
                'anggota.statusKeanggotaan',
                'anggota.jabatanAnggota.alatKelengkapan',
                'anggota.jabatanAnggota.jabatanAlatKelengkapan'
            ])
            ->join('anggota', 'transaksi_gaji.id_anggota', '=', 'anggota.id')
            ->select('transaksi_gaji.*')
            ->orderBy('anggota.id_dprd', 'asc')
            ->orderBy('anggota.nama_anggota', 'asc')
            ->get();

        if ($transaksi->isEmpty()) {
            return back()->with('error', 'Data gaji untuk periode ini belum diproses.');
        }

        $dsbGaji = DsbGaji::where('bln_thn', $blnThn)->first();
        $bulanLabel = $this->getBulanLabel($bulan);
        $pemda = Pemda::first();

        // We'll need a way to calculate terbilang for each slip in the view or pass it as a helper
        $controller = $this;

        return view('admin.gaji.reports.slip-gaji-bulk', compact(
            'transaksi',
            'dsbGaji',
            'bulanLabel',
            'tahun',
            'pemda',
            'controller'
        ));
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

    public function terbilang($nilai)
    {
        $nilai = abs($nilai);
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        }
        else if ($nilai < 20) {
            $temp = $this->terbilang($nilai - 10) . " belas";
        }
        else if ($nilai < 100) {
            $temp = $this->terbilang($nilai / 10) . " puluh" . $this->terbilang($nilai % 10);
        }
        else if ($nilai < 200) {
            $temp = " seratus" . $this->terbilang($nilai - 100);
        }
        else if ($nilai < 1000) {
            $temp = $this->terbilang($nilai / 100) . " ratus" . $this->terbilang($nilai % 100);
        }
        else if ($nilai < 2000) {
            $temp = " seribu" . $this->terbilang($nilai - 1000);
        }
        else if ($nilai < 1000000) {
            $temp = $this->terbilang($nilai / 1000) . " ribu" . $this->terbilang($nilai % 1000);
        }
        else if ($nilai < 1000000000) {
            $temp = $this->terbilang($nilai / 1000000) . " juta" . $this->terbilang($nilai % 1000000);
        }
        else if ($nilai < 1000000000000) {
            $temp = $this->terbilang($nilai / 1000000000) . " milyar" . $this->terbilang(fmod($nilai, 1000000000));
        }
        else if ($nilai < 1000000000000000) {
            $temp = $this->terbilang($nilai / 1000000000000) . " trilyun" . $this->terbilang(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
    public function exportExcel(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $bulanLabel = $this->getBulanLabel($bulan);

        $fileName = 'Daftar_Gaji_' . $bulanLabel . '_' . $tahun . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DaftarGajiExport($bulan, $tahun),
            $fileName
        );
    }
}
