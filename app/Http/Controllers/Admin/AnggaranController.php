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

    public function form($id = null)
    {
        return view('admin.anggaran.form', compact('id'));
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
        $totalPagu = $anggaran->total_pagu;

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

    public function printRealisasi(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $anggaran = Anggaran::with('rincians')->where('tahun_anggaran', $tahun)->firstOrFail();
        
        // Fetch all journal entries for this period (cumulative up to the selected month)
        $query = JurnalLra::where('bln_thn', 'like', '%-' . $tahun);
        
        // If bulan is selected, we usually want to see realization UP TO that month for a cumulative report
        // But the user might want just that month. Usually 'Realisasi' is cumulative to date.
        // Let's implement cumulative by default if no clear instruction.
        if ($bulan) {
            // This is a bit tricky since months are strings like '1', '2', ..., '12', 'THR', 'G13'
            // For now, let's just use the filters from the list.
            // If the user filtered by month, let's show realization for THAT month only as per BKU logic.
            $query->where('bln_thn', 'like', $bulan . '-%');
        }

        $realizations = $query->select('item_anggaran', DB::raw('SUM(debet - kredit) as total_realisasi'))
            ->groupBy('item_anggaran')
            ->get()
            ->pluck('total_realisasi', 'item_anggaran');

        $pemda = Pemda::first();
        $labelBulan = $this->getLabelBulan($bulan);

        return view('admin.anggaran.print-realisasi', compact('anggaran', 'realizations', 'pemda', 'tahun', 'labelBulan'));
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
