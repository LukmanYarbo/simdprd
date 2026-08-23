@extends('layouts.admin')

@section('breadcrumbs')
    <x-breadcrumbs />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-5 fade-in-up">
            <div class="col">
                <h2 class="h3 fw-extrabold text-gradient mb-1">Dashboard Overview</h2>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <p class="text-secondary fw-medium mb-0 me-1">Selamat Datang, <span
                            class="text-primary fw-bold">{{ Auth::user()->name }}</span></p>
                    @foreach(Auth::user()->getRoleNames() as $roleName)
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis text-uppercase">
                            <i class="ti ti-shield-check me-1"></i>{{ $roleName }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        @can('view anggaran')
        <div class="row g-4 mb-4">
            <!-- Stats Card 1
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.1s;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="text-secondary text-uppercase fw-bold small tracking-wider">Total Users</span>
                                        <div class="stats-icon rounded-3 p-2"
                                            style="background: rgba(99, 102, 241, 0.15); color: #6366f1;">
                                            <i class="ti ti-users-group fs-4"></i>
                                        </div>
                                    </div>
                                    <h2 class="mb-2 fw-extrabold">{{ \App\Models\User::count() }}</h2>
                                    <div class="d-flex align-items-center">
                                        <span class="text-success small fw-bold me-2"><i class="ti ti-trending-up"></i> 12%</span>
                                        <span class="text-secondary small">active now</span>
                                    </div>
                                </div>
                            </div>
                        </div> -->

            <!-- Budget Card: Total Pagu -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up"
                    style="animation-delay: 0.2s; border-left: 4px solid #6366f1 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary text-uppercase fw-bold small tracking-wider">Pagu Anggaran
                                {{ date('Y') }}</span>
                            <div class="stats-icon rounded-3 p-2"
                                style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                <i class="ti ti-wallet fs-4"></i>
                            </div>
                        </div>
                        <h3 class="mb-2 fw-extrabold">Rp {{ number_format($budgetSummary['total_pagu'], 0, ',', '.') }}</h3>
                        <div class="d-flex align-items-center">
                            <span class="text-secondary small">Total alokasi tahun ini</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Card: Realisasi -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up"
                    style="animation-delay: 0.3s; border-left: 4px solid #10b981 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary text-uppercase fw-bold small tracking-wider">Realisasi</span>
                            <div class="stats-icon rounded-3 p-2"
                                style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="ti ti-chart-arrows fs-4"></i>
                            </div>
                        </div>
                        <h3 class="mb-2 fw-extrabold text-success">Rp
                            {{ number_format($budgetSummary['total_realisasi'], 0, ',', '.') }}
                        </h3>
                        <div class="d-flex align-items-center">
                            <span class="text-success small fw-bold me-2">{{ $budgetSummary['persen_realisasi'] }}%</span>
                            <span class="text-secondary small">dari total pagu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Card: Realisasi Bulan Berjalan -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up"
                    style="animation-delay: 0.4s; border-left: 4px solid #6366f1 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary text-uppercase fw-bold small tracking-wider">Realisasi Bulan Ini</span>
                            <div class="stats-icon rounded-3 p-2"
                                style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                <i class="ti ti-calendar-stats fs-4"></i>
                            </div>
                        </div>
                        <h3 class="mb-2 fw-extrabold text-primary">Rp
                            {{ number_format($budgetSummary['realisasi_bulan_berjalan'], 0, ',', '.') }}
                        </h3>
                        <div class="d-flex align-items-center">
                            <span class="text-secondary small">Bulan {{ $budgetSummary['label_bulan'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Card: Sisa -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up"
                    style="animation-delay: 0.5s; border-left: 4px solid #f59e0b !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary text-uppercase fw-bold small tracking-wider">Sisa Pagu</span>
                            <div class="stats-icon rounded-3 p-2"
                                style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="ti ti-receipt-2 fs-4"></i>
                            </div>
                        </div>
                        <h3 class="mb-2 fw-extrabold text-warning">Rp
                            {{ number_format($budgetSummary['total_sisa'], 0, ',', '.') }}
                        </h3>
                        <div class="d-flex align-items-center">
                            <span class="text-secondary small">Tersedia untuk digunakan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-8">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.5s;">
                    <div
                        class="card-header bg-transparent border-bottom border-white border-opacity-10 py-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-gradient">Analisis Realisasi Anggaran Per Item</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-secondary text-decoration-none dropdown-toggle"
                                type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('admin.jurnal-lra.index') }}">Lihat Jurnal
                                        LRA</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div id="budgetBarChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.6s;">
                    <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-4 px-4">
                        <h5 class="mb-0 fw-bold text-gradient">Status Keseluruhan</h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div id="budgetDonutChart" style="min-height: 300px;"></div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small fw-medium">Persentase Terpakai</span>
                                <span class="fw-bold small">{{ $budgetSummary['persen_realisasi'] }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success rounded-pill" role="progressbar"
                                    style="width: {{ $budgetSummary['persen_realisasi'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view anggota')
        <!-- Membership & AKD Section -->
        <div class="row mb-4 fade-in-up" style="animation-delay: 0.7s;">
            <div class="col">
                <h5 class="fw-bold text-gradient mb-3"><i class="ti ti-users me-2"></i>Informasi Keanggotaan & Alat
                    Kelengkapan Dewan</h5>
            </div>
        </div>

        <div class="row g-3 mb-5">
            <!-- Total Anggota Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.8s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(99, 102, 241, 0.1); color: #6366f1; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-users fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['total_anggota'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Total Anggota</span>
                    </div>
                </div>
            </div>

            <!-- Komisi Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.9s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-buildingfs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['komisi'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Komisi</span>
                    </div>
                </div>
            </div>

            <!-- Banmus Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 1.0s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-calendar-event fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['banmus'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Banmus</span>
                    </div>
                </div>
            </div>

            <!-- Banggar Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 1.1s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-coins fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['banggar'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Banggar</span>
                    </div>
                </div>
            </div>

            <!-- Bapemperda Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 1.2s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-gavel fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['balegda'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Bapemperda</span>
                    </div>
                </div>
            </div>

            <!-- BK Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 1.3s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(107, 114, 128, 0.1); color: #6b7280; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-shield-check fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['bk'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Badan Kehormatan</span>
                    </div>
                </div>
            </div>


        </div>
        <div class="row mb-4 fade-in-up" style="animation-delay: 0.7s;">
            <div class="col">
                <h5 class="fw-bold text-gradient mb-3"><i class="ti ti-users me-2"></i>Informasi Keanggotaan & Alat
                    Kelengkapan Lainya</h5>
            </div>
        </div>

        <div class="row g-3 mb-5">
            <!-- Pansus Card -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 1.4s;">
                    <div class="card-body p-3 text-center">
                        <div class="stats-icon rounded-circle p-2 mx-auto mb-2"
                            style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-list-check fs-5"></i>
                        </div>
                        <h4 class="mb-0 fw-extrabold">{{ $membershipSummary['pansus'] }}</h4>
                        <span class="text-secondary small fw-bold text-uppercase tracking-tighter"
                            style="font-size: 0.7rem;">Pansus</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @if(!auth()->user()->can('view anggaran') && !auth()->user()->can('view anggota'))
        <div class="alert alert-light border text-center py-5">
            <i class="ti ti-lock display-6 d-block mb-3 text-muted"></i>
            <h6 class="fw-bold text-muted">Belum ada informasi yang dapat ditampilkan</h6>
            <p class="text-muted small mb-0">Role Anda belum memiliki izin untuk melihat data anggaran maupun keanggotaan.
                Silakan hubungi administrator.</p>
        </div>
        @endif

    </div>
@endsection

@push('scripts')
    @can('view anggaran')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($budgetSummary['chart_data']);

            // Bar Chart
            const barOptions = {
                series: [{
                    name: 'Pagu',
                    data: chartData.map(item => item.pagu)
                }, {
                    name: 'Realisasi',
                    data: chartData.map(item => item.realisasi)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        borderRadius: 4
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: chartData.map(item => item.label),
                    labels: {
                        style: { colors: '#64748b' },
                        rotate: -45,
                        trim: true
                    }
                },
                yaxis: {
                    title: { text: 'Rupiah' },
                    labels: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                        },
                        style: { colors: '#64748b' }
                    }
                },
                fill: {
                    opacity: 1,
                    colors: ['#6366f1', '#10b981']
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                },
                colors: ['#6366f1', '#10b981']
            };

            const barChart = new ApexCharts(document.querySelector("#budgetBarChart"), barOptions);
            barChart.render();

            // Donut Chart
            const donutOptions = {
                series: [{{ $budgetSummary['total_realisasi'] }}, {{ $budgetSummary['total_sisa'] }}],
                labels: ['Realisasi', 'Sisa Pagu'],
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#10b981', '#f59e0b'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Pagu',
                                    formatter: function (w) {
                                        return "Rp " + new Intl.NumberFormat('id-ID').format({{ $budgetSummary['total_pagu'] }});
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                }
            };

            const donutChart = new ApexCharts(document.querySelector("#budgetDonutChart"), donutOptions);
            donutChart.render();
        });
    </script>
    @endcan
@endpush