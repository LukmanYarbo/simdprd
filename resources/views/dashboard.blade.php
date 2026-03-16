@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">Dashboard</h2>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}
                <span class="badge bg-primary-subtle text-primary ms-1">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</span>
            </p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Welcome Card -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded me-3">
                            <i class="ti ti-user-circle fs-2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                            <p class="text-muted mb-0">Anda login sebagai <strong>{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</strong>.</p>
                        </div>
                    </div>
                    <hr>
                    <p class="text-muted mb-0">
                        <i class="ti ti-info-circle me-1"></i>
                        Halaman ini adalah dashboard utama Anda. Fitur tambahan akan segera tersedia.
                    </p>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random&size=100" class="rounded-circle mb-3" width="80" height="80" alt="">
                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small mb-2">{{ Auth::user()->email }}</p>
                    <span class="badge bg-primary">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</span>
                    <hr>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Bergabung sejak</span>
                        <span class="fw-semibold">{{ Auth::user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info -->
    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success p-2 rounded me-3">
                        <i class="ti ti-check-circle fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Status Akun</h6>
                        <span class="text-success small">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info p-2 rounded me-3">
                        <i class="ti ti-shield-check fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Role</h6>
                        <span class="text-info small">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning p-2 rounded me-3">
                        <i class="ti ti-users fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Jumlah Anggota</h6>
                        <span class="text-muted small">{{ \App\Models\Anggota::count() }} Orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
