<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;

class DashboardController extends Controller
{
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
            'persen_realisasi' => 0,
            'chart_data' => []
        ];

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
