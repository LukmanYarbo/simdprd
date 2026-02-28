@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-5 fade-in-up">
        <div class="col">
            <h2 class="h3 fw-extrabold text-gradient mb-1">Dashboard Overview</h2>
            <p class="text-secondary fw-medium">Welcome back, <span class="text-primary fw-bold">{{ Auth::user()->name }}</span>! Here's what's happening today.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Stats Card 1 -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.1s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-secondary text-uppercase fw-bold small tracking-wider">Total Users</span>
                        <div class="stats-icon rounded-3 p-2" style="background: rgba(99, 102, 241, 0.15); color: #6366f1;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                    <h2 class="mb-2 fw-extrabold">{{ \App\Models\User::count() }}</h2>
                    <div class="d-flex align-items-center">
                        <span class="text-success small fw-bold me-2"><i class="bi bi-arrow-up-short"></i> 12%</span> 
                        <span class="text-secondary small">active now</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div class="col-12 col-md-6 col-xl-3">
             <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-secondary text-uppercase fw-bold small tracking-wider">Anggota Aktif</span>
                        <div class="stats-icon rounded-3 p-2" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                            <i class="bi bi-person-check-fill fs-4"></i>
                        </div>
                    </div>
                    @php
                        $anggotaAktif = \App\Models\Anggota::whereNull('tgl_berhenti')->count();
                        $totalAnggota = \App\Models\Anggota::count();
                        $persenAktif = $totalAnggota > 0 ? round(($anggotaAktif / $totalAnggota) * 100) : 0;
                    @endphp
                    <h2 class="mb-2 fw-extrabold">{{ $anggotaAktif }}</h2>
                    <div class="d-flex align-items-center">
                        <span class="text-success small fw-bold me-2">{{ $persenAktif }}%</span> 
                        <span class="text-secondary small">capacity active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Card 3 -->
        <div class="col-12 col-md-6 col-xl-3">
             <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.3s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-secondary text-uppercase fw-bold small tracking-wider">AKD Aktif</span>
                        <div class="stats-icon rounded-3 p-2" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                            <i class="bi bi-layers-half fs-4"></i>
                        </div>
                    </div>
                    @php
                        $alatAktif = \App\Models\AlatKelengkapan::whereHas('suratKeputusan', function($q) {
                            $q->where('status', 'A');
                        })->count();
                        $totalAlat = \App\Models\AlatKelengkapan::count();
                        $persenAlat = $totalAlat > 0 ? round(($alatAktif / $totalAlat) * 100) : 0;
                    @endphp
                    <h2 class="mb-2 fw-extrabold">{{ $alatAktif }}</h2>
                    <div class="d-flex align-items-center">
                        <span class="text-warning small fw-bold me-2">{{ $persenAlat }}%</span> 
                        <span class="text-secondary small">established focus</span>
                    </div>
                </div>
            </div>
        </div>

         <!-- Stats Card 4 -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card glass-card border-0 glow-shadow h-100 fade-in-up" style="animation-delay: 0.4s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                         <span class="text-secondary text-uppercase fw-bold small tracking-wider">System Health</span>
                         <div class="stats-icon rounded-3 p-2" style="background: rgba(14, 165, 233, 0.15); color: #0ea5e9;">
                            <i class="bi bi-activity fs-4"></i>
                        </div>
                    </div>
                    <h2 class="mb-2 fw-extrabold text-info">99.9%</h2>
                    <div class="d-flex align-items-center">
                        <span class="text-info small fw-bold me-2">Uptime</span> 
                        <span class="text-secondary small">all systems go</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card glass-card border-0 glow-shadow fade-in-up" style="animation-delay: 0.5s;">
                <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-4 px-4">
                     <h5 class="mb-0 fw-bold text-gradient">Recent System Activity</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-body-tertiary">
                                <tr>
                                    <th class="border-0 ps-4">User</th>
                                    <th class="border-0">Action</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0 pe-4 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" class="rounded-circle me-3" width="36" height="36" alt="">
                                            <div>
                                                <h6 class="mb-0">John Doe</h6>
                                                <small class="text-muted">Editor</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Updated documentation</td>
                                    <td>Oct 24, 2024</td>
                                    <td class="pe-4 text-end"><span class="badge bg-success-subtle text-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                         <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" class="rounded-circle me-3" width="36" height="36" alt="">
                                            <div>
                                                <h6 class="mb-0">Jane Smith</h6>
                                                <small class="text-muted">Admin</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>System update</td>
                                    <td>Oct 23, 2024</td>
                                    <td class="pe-4 text-end"><span class="badge bg-warning-subtle text-warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Alex+Johnson&background=random" class="rounded-circle me-3" width="36" height="36" alt="">
                                            <div>
                                                <h6 class="mb-0">Alex Johnson</h6>
                                                <small class="text-muted">User</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Login attempt</td>
                                    <td>Oct 23, 2024</td>
                                    <td class="pe-4 text-end"><span class="badge bg-danger-subtle text-danger">Failed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top border-white border-opacity-10 py-3 text-center">
                    <a href="#" class="btn btn-link text-primary text-decoration-none fw-semibold small">View All Logs <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-xl-4">
             <div class="card glass-card border-0 glow-shadow fade-in-up" style="animation-delay: 0.6s;">
                <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-4 px-4">
                     <h5 class="mb-0 fw-bold text-gradient">System Performance</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium">CPU Usage</span>
                            <span class="text-muted">45%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                         <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium">Memory Usage</span>
                            <span class="text-muted">68%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 68%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                         <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium">Disk Usage</span>
                            <span class="text-muted">85%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
