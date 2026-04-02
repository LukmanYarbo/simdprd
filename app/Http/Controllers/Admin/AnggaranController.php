<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggaran;
use App\Models\JurnalLra;
use App\Models\Pemda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggaranController extends Controller
{
    public function index()
    {
        return view('admin.anggaran.index');
    }

    public function jurnalIndex()
    {
        return view('admin.anggaran.jurnal');
    }

    public function printBku(Request $request)
    {
        $bulan = $request->bulan; // e.g. '1', 'THR'
        $tahun = $request->tahun;
        
        $blnThnSearch = ($bulan ? $bulan : '') . '-' . $tahun;
        
        $anggaran = Anggaran::where('tahun_anggaran', $tahun)->firstOrFail();
        
        // Sum total pagu
        $totalPagu = $anggaran->gaji_pokok + $anggaran->tunjangan_keluarga + $anggaran->tunjangan_jabatan + 
                     $anggaran->tunjangan_beras + $anggaran->tunjangan_pph + $anggaran->pembulatan + 
                     $anggaran->uang_paket + $anggaran->tunjangan_alat_kelengkapan + 
                     $anggaran->tunjangan_alat_kelengkapan_lainnya + $anggaran->tunjangan_perumahan + 
                     $anggaran->uang_jasa_pengabdian + $anggaran->tunjangan_reses + 
                     $anggaran->tunjangan_transportasi + $anggaran->jkk + $anggaran->jkm + 
                     $anggaran->tunjangan_komunikasi_insentif;

        // Calculate cumulative realization BEFORE this month
        // We need to decide the order of months. 1, 2, ..., 12, THR, G13?
        // For simplicity, let's just sum all journals for the same year that came before current journals
        // But better is to just fetch ALL journals for that year and filter/sort in PHP or SQL.
        
        $allJournalsOfYear = JurnalLra::where('bln_thn', 'like', '%-' . $tahun)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $filteredJournals = [];
        $saldoAwal = $totalPagu;
        $foundCurrentMonth = false;

        foreach ($allJournalsOfYear as $journal) {
            $isMatch = false;
            if ($bulan) {
                if (str_starts_with($journal->bln_thn, $bulan . '-')) {
                    $isMatch = true;
                }
            } else {
                // If no month selected, we show all year? Usually BKU is monthly.
                $isMatch = true;
            }

            if ($isMatch) {
                $foundCurrentMonth = true;
                $filteredJournals[] = $journal;
            } else {
                if (!$foundCurrentMonth) {
                    // Cumulative before the month
                    $saldoAwal -= ($journal->debet - $journal->kredit);
                }
            }
        }

        $pemda = Pemda::first();
        $labelBulan = $this->getLabelBulan($bulan);

        return view('admin.anggaran.print-bku', compact('filteredJournals', 'saldoAwal', 'totalPagu', 'pemda', 'tahun', 'labelBulan'));
    }

    protected function getLabelBulan($bulan)
    {
        $labels = [
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
            'THR' => 'Tunjangan Hari Raya (THR)', 'G13' => 'Gaji Ke-13'
        ];
        return $labels[$bulan] ?? '';
    }
}
