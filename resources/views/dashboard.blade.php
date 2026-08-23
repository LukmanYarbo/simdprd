@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4 fade-in-up">
        <div class="col">
            <h2 class="h3 fw-extrabold text-gradient mb-1">Dashboard</h2>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <p class="text-secondary fw-medium mb-0 me-1">Selamat Datang, <span
                        class="text-primary fw-bold">{{ auth()->user()->name }}</span></p>
                @foreach($roles as $roleName)
                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis text-uppercase">
                        <i class="ti ti-shield-check me-1"></i>{{ $roleName }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Profile & Status Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up">
                <div class="card-body d-flex flex-column justify-content-center p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon rounded-3 p-3 me-3"
                            style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                            <i class="ti ti-user-circle fs-2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h4>
                            <p class="text-secondary mb-0 small">Anda login sebagai
                                <strong>{{ ucfirst($roles->first() ?? 'User') }}</strong>. Menu dan informasi di bawah
                                menyesuaikan hak akses role Anda.</p>
                        </div>
                    </div>
                    <hr class="border-secondary-subtle">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-mail text-primary fs-5 me-2"></i>
                                <div>
                                    <div class="text-secondary extra-small text-uppercase fw-bold">Email</div>
                                    <div class="small fw-semibold">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-calendar-check text-success fs-5 me-2"></i>
                                <div>
                                    <div class="text-secondary extra-small text-uppercase fw-bold">Bergabung Sejak</div>
                                    <div class="small fw-semibold">{{ auth()->user()->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anggaran Summary Card (permission-aware) -->
        @can('view anggaran')
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary text-uppercase fw-bold small tracking-wider">Realisasi Anggaran
                            {{ $budgetSummary['tahun'] }}</span>
                        <div class="stats-icon rounded-3 p-2"
                            style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="ti ti-chart-arrows fs-4"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-extrabold text-success">Rp {{ number_format($budgetSummary['total_realisasi'], 0, ',', '.') }}</h3>
                    <div class="d-flex align-items-center mb-3">
                        <span class="text-success small fw-bold me-2">{{ $budgetSummary['persen_realisasi'] }}%</span>
                        <span class="text-secondary small">dari pagu Rp {{ number_format($budgetSummary['total_pagu'], 0, ',', '.') }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success rounded-pill" role="progressbar"
                            style="width: {{ $budgetSummary['persen_realisasi'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="border-left: 4px solid #22c55e !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                    <div class="stats-icon rounded-circle p-2 mx-auto mb-3"
                        style="background: rgba(34, 197, 94, 0.1); color: #22c55e; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-shield-check fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Status Akun</h5>
                    <span class="badge bg-success-subtle text-success-emphasis w-fit mx-auto">Aktif</span>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <!-- Keanggotaan Stats (permission-aware) -->
    @if($totalAnggota !== null)
    <div class="row mb-3 fade-in-up">
        <div class="col">
            <h5 class="fw-bold text-gradient mb-0"><i class="ti ti-users me-2"></i>Ringkasan Keanggotaan</h5>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up">
                <div class="card-body p-3 text-center">
                    <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                        style="background: rgba(99, 102, 241, 0.1); color: #6366f1; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-users fs-5"></i>
                    </div>
                    <h4 class="mb-0 fw-extrabold">{{ $totalAnggota }}</h4>
                    <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                        style="font-size: 0.7rem;">Total Anggota</span>
                </div>
            </div>
        </div>
        @foreach([
            'komisi' => ['Komisi', 'ti-building', '#10b981'],
            'banggar' => ['Banggar', 'ti-coins', '#0ea5e9'],
            'banmus' => ['Banmus', 'ti-calendar-event', '#f59e0b'],
            'balegda' => ['Bapemperda', 'ti-gavel', '#ef4444'],
            'bk' => ['Badan Kehormatan', 'ti-shield-check', '#6b7280'],
        ] as $key => [$label, $icon, $color])
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: {{ $color }}1a; color: {{ $color }}; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="{{ $icon }} fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary[$key] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">{{ $label }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Menu Akses Cepat (permission-driven) -->
    <div class="row mb-3 fade-in-up">
        <div class="col">
            <h5 class="fw-bold text-gradient mb-0"><i class="ti ti-layout-grid me-2"></i>Menu Akses Anda</h5>
            <p class="text-secondary small mb-0">Daftar modul yang dapat Anda akses sesuai role &amp; permission.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach($quickMenus as $menu)
            <div class="col-6 col-md-4 col-xl-2">
                <a href="{{ route($menu['route']) }}"
                    class="text-decoration-none d-block h-100 quick-menu-card">
                    <div class="card glass-card border-0 glow-shadow h-100 transition-base hover-lift">
                        <div class="card-body p-3 text-center">
                            <div class="stats-icon rounded-3 p-2 mx-auto mb-2"
                                style="background: {{ $menu['color'] }}1a; color: {{ $menu['color'] }}; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                <i class="{{ $menu['icon'] }} fs-4"></i>
                            </div>
                            <span class="fw-semibold small text-body">{{ $menu['label'] }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    @if($quickMenus->isEmpty())
    <div class="alert alert-light border text-center py-5">
        <i class="ti ti-lock display-6 d-block mb-3 text-muted"></i>
        <h6 class="fw-bold text-muted">Belum ada menu yang tersedia</h6>
        <p class="text-muted small mb-0">Role Anda belum memiliki izin untuk mengakses modul apa pun.
            Silakan hubungi administrator.</p>
    </div>
    @endif

    <!-- Recent Anggota (permission-aware) -->
    @if(auth()->user()->can('view anggota') && $recentAnggota->isNotEmpty())
    <div class="row mb-3 fade-in-up">
        <div class="col">
            <h5 class="fw-bold text-gradient mb-0"><i class="ti ti-clock me-2"></i>Anggota Terbaru</h5>
        </div>
    </div>
    <div class="card glass-card border-0 glow-shadow mb-4 fade-in-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;"></th>
                        <th class="py-3">Nama Anggota</th>
                        <th class="py-3">Jabatan</th>
                        <th class="pe-4 py-3 text-end">Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAnggota as $anggota)
                    <tr>
                        <td class="ps-4">
                            <img src="{{ $anggota->foto_anggota ? asset('storage/' . $anggota->foto_anggota) : 'https://ui-avatars.com/api/?name=' . urlencode($anggota->nama_anggota) . '&background=6366f1&color=fff' }}"
                                alt="{{ $anggota->nama_anggota }}" width="36" height="36"
                                class="rounded-circle object-fit-cover">
                        </td>
                        <td class="fw-semibold">{{ $anggota->nama_anggota }}</td>
                        <td class="text-secondary small">{{ $anggota->jabatan->nama_dprd ?? '-' }}</td>
                        <td class="pe-4 text-end text-secondary small">{{ $anggota->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .quick-menu-card:hover .card {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.25);
    }

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
</style>
@endpush
