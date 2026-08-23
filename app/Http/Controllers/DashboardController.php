<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private array $quickMenus = [
        ['perm' => 'view anggota', 'route' => 'admin.anggota.index', 'label' => 'Data Anggota', 'icon' => 'ti-user-check', 'color' => '#6366f1'],
        ['perm' => 'view anggota', 'route' => 'admin.anggota-status.index', 'label' => 'Status Anggota', 'icon' => 'ti-user-edit', 'color' => '#8b5cf6'],
        ['perm' => 'view surat_keputusan', 'route' => 'admin.surat-keputusan.index', 'label' => 'Surat Keputusan', 'icon' => 'ti-certificate', 'color' => '#f59e0b'],
        ['perm' => 'view alat_kelengkapan', 'route' => 'admin.alat-kelengkapan.index', 'label' => 'Alat Kelengkapan', 'icon' => 'ti-components', 'color' => '#10b981'],
        ['perm' => 'view skpd', 'route' => 'admin.skpd.index', 'label' => 'Data SKPD', 'icon' => 'ti-building-skyscraper', 'color' => '#0ea5e9'],
        ['perm' => 'view pemda', 'route' => 'admin.pemda.index', 'label' => 'Data Pemda', 'icon' => 'ti-building-monument', 'color' => '#ec4899'],
        ['perm' => 'view parameter_gaji', 'route' => 'admin.parameter-gaji.index', 'label' => 'Parameter Gaji', 'icon' => 'ti-calculator', 'color' => '#14b8a6'],
        ['perm' => 'view transaksi_gaji', 'route' => 'admin.transaksi-gaji.index', 'label' => 'Proses Gaji', 'icon' => 'ti-coins', 'color' => '#eab308'],
        ['perm' => 'view kertas_kerja', 'route' => 'admin.kertas-kerja.index', 'label' => 'Kertas Kerja', 'icon' => 'ti-file-analytics', 'color' => '#06b6d4'],
        ['perm' => 'view anggaran', 'route' => 'admin.anggaran.index', 'label' => 'Master Anggaran', 'icon' => 'ti-wallet', 'color' => '#10b981'],
        ['perm' => 'view jurnal_lra', 'route' => 'admin.jurnal-lra.index', 'label' => 'Jurnal LRA', 'icon' => 'ti-history', 'color' => '#f97316'],
        ['perm' => 'view users', 'route' => 'admin.users.index', 'label' => 'Users', 'icon' => 'ti-users-group', 'color' => '#6366f1'],
        ['perm' => 'view roles', 'route' => 'admin.roles.index', 'label' => 'Role Management', 'icon' => 'ti-shield-check', 'color' => '#ef4444'],
    ];

    public function index()
    {
        $user = auth()->user();
        $roles = $user->getRoleNames();

        $data = [
            'roles' => $roles,
            'totalAnggota' => null,
            'recentAnggota' => collect(),
            'membershipSummary' => [],
            'budgetSummary' => null,
            'quickMenus' => collect($this->quickMenus)->filter(fn ($menu) => $user->can($menu['perm']))->values(),
        ];

        if ($user->can('view anggota')) {
            $data['totalAnggota'] = Anggota::whereNull('tgl_berhenti')->count();
            $data['recentAnggota'] = Anggota::with(['jabatan', 'agama'])->orderBy('created_at', 'desc')->take(5)->get();

            $data['membershipSummary'] = [
                'komisi' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_komisi')->count(),
                'banggar' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_banggar')->count(),
                'banmus' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_banmus')->count(),
                'balegda' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_balegda')->count(),
                'bk' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_bk')->count(),
                'pansus' => Anggota::whereNull('tgl_berhenti')->whereNotNull('id_pansus')->count(),
            ];
        }

        if ($user->can('view anggaran')) {
            $currentYear = date('Y');
            $anggaran = \App\Models\Anggaran::with('rincians')->where('tahun_anggaran', $currentYear)->first();

            $budget = [
                'tahun' => $currentYear,
                'total_pagu' => 0,
                'total_realisasi' => 0,
                'total_sisa' => 0,
                'persen_realisasi' => 0,
            ];

            if ($anggaran) {
                $budget['total_pagu'] = $anggaran->rincians->sum('jumlah');
                $budget['total_sisa'] = $anggaran->rincians->sum('sisa_pagu');
                $budget['total_realisasi'] = $budget['total_pagu'] - $budget['total_sisa'];
                $budget['persen_realisasi'] = $budget['total_pagu'] > 0
                    ? round(($budget['total_realisasi'] / $budget['total_pagu']) * 100, 1)
                    : 0;
            }

            $data['budgetSummary'] = $budget;
        }

        return view('dashboard', $data);
    }
}
