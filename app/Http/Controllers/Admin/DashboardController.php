<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Anggota;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view dashboard', only: ['index']),
        ];
    }

    public function index()
    {
        $recentAnggota = Anggota::orderBy('created_at', 'desc')->take(5)->get();

        // Budget Summary
        $currentYear = date('Y');
        $anggaran = \App\Models\Anggaran::with('rincians')->where('tahun_anggaran', $currentYear)->first();

        $budgetSummary = [
            'total_pagu' => 0,
            'total_realisasi' => 0,
            'total_sisa' => 0,
            'realisasi_bulan_berjalan' => 0,
            'persen_realisasi' => 0,
            'chart_data' => []
        ];

        $currentMonth = date('n');
        $currentMonthYear = $currentMonth . '-' . $currentYear;
        $budgetSummary['realisasi_bulan_berjalan'] = \App\Models\JurnalLra::where('bln_thn', $currentMonthYear)
            ->sum(DB::raw('debet - kredit'));

        $monthLabels = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $budgetSummary['label_bulan'] = $monthLabels[$currentMonth] ?? '';


        if ($anggaran) {
            $budgetSummary['total_pagu'] = $anggaran->rincians->sum('jumlah');
            $budgetSummary['total_sisa'] = $anggaran->rincians->sum('sisa_pagu');
            $budgetSummary['total_realisasi'] = $budgetSummary['total_pagu'] - $budgetSummary['total_sisa'];
            $budgetSummary['persen_realisasi'] = $budgetSummary['total_pagu'] > 0 
                ? round(($budgetSummary['total_realisasi'] / $budgetSummary['total_pagu']) * 100, 1) 
                : 0;
            
            $budgetSummary['chart_data'] = $anggaran->rincians->map(function($item) {
                return [
                    'label' => $item->uraian,
                    'realisasi' => $item->jumlah - $item->sisa_pagu,
                    'pagu' => $item->jumlah
                ];
            });
        }

        // Membership Summary
        $membershipSummary = [
            'total_anggota' => Anggota::whereNull('tgl_berhenti')->count(),
            'komisi' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_komisi')->count(),
            'banggar' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_banggar')->count(),
            'banmus' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_banmus')->count(),
            'balegda' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_balegda')->count(),
            'bk' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_bk')->count(),
            'pansus' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_pansus')->count(),
        ];

        return view('admin.dashboard', compact('recentAnggota', 'budgetSummary', 'membershipSummary'));
    }
}
