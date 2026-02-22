@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">Dashboard</h2>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Stats Card 1 -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted text-uppercase fw-bold small">Total Users</span>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary p-2 rounded">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ \App\Models\User::count() }}</h3>
                    <span class="text-success small fw-bold"><i class="bi bi-arrow-up-short"></i> 12%</span> <span class="text-muted small">since last month</span>
                </div>
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div class="col-12 col-md-6 col-xl-3">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted text-uppercase fw-bold small">Anggota Aktif</span>
                        <div class="icon-box bg-success bg-opacity-10 text-success p-2 rounded">
                            <i class="bi bi-person-check fs-4"></i>
                        </div>
                    </div>
                    @php
                        // Hitung anggota yang belum berhenti (aktif)
                        $anggotaAktif = \App\Models\Anggota::whereNull('tgl_berhenti')->count();
                        $totalAnggota = \App\Models\Anggota::count();
                        $persenAktif = $totalAnggota > 0 ? round(($anggotaAktif / $totalAnggota) * 100) : 0;
                    @endphp
                    <h3 class="mb-1 fw-bold">{{ $anggotaAktif }}</h3>
                    <span class="text-success small fw-bold">{{ $persenAktif }}%</span> <span class="text-muted small">dari total anggota</span>
                </div>
            </div>
        </div>

        <!-- Stats Card 3 -->
        <div class="col-12 col-md-6 col-xl-3">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted text-uppercase fw-bold small">Alat Kelengkapan Aktif</span>
                         <div class="icon-box bg-warning bg-opacity-10 text-warning p-2 rounded">
                            <i class="bi bi-diagram-3 fs-4"></i>
                        </div>
                    </div>
                    @php
                        // Menghitung Alat Kelengkapan yang memiliki Surat Keputusan dengan status Aktif ('A')
                        $alatAktif = \App\Models\AlatKelengkapan::whereHas('suratKeputusan', function($q) {
                            $q->where('status', 'A');
                        })->count();
                        $totalAlat = \App\Models\AlatKelengkapan::count();
                        $persenAlat = $totalAlat > 0 ? round(($alatAktif / $totalAlat) * 100) : 0;
                    @endphp
                    <h3 class="mb-1 fw-bold">{{ $alatAktif }}</h3>
                    <span class="text-warning small fw-bold">{{ $persenAlat }}%</span> <span class="text-muted small">dari total</span>
                </div>
            </div>
        </div>

         <!-- Stats Card 4 -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                         <span class="text-muted text-uppercase fw-bold small">Storage</span>
                         <div class="icon-box bg-info bg-opacity-10 text-info p-2 rounded">
                            <i class="bi bi-hdd fs-4"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold">85%</h3>
                     <span class="text-muted small">1.2GB used of 2GB</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                     <h5 class="mb-0 fw-bold">Recent Activity</h5>
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
            </div>
        </div>
        
        <div class="col-12 col-xl-4">
             <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                     <h5 class="mb-0 fw-bold">System Status</h5>
                </div>
                <div class="card-body">
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
