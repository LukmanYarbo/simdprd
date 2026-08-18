@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Settings', 'icon' => 'ti ti-settings-2'],
    ['label' => 'Kosongkan Database', 'icon' => 'ti ti-database']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="h4 fw-bold mb-1">Kosongkan Database</h2>
    <p class="text-muted small mb-4">Kosongkan seluruh isi tabel database menggunakan metode TRUNCATE.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-alert-triangle me-2"></i>Perhatian!</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Tindakan ini akan mengosongkan <strong>{{ $tables->count() }} tabel</strong> di database menggunakan metode TRUNCATE.</li>
                        <li>Tabel <strong>users</strong>, <strong>roles</strong>, <strong>permissions</strong> beserta tabel pivot terkait <strong>tidak akan dihapus</strong> agar akun tetap bisa login.</li>
                        <li>Data lainnya yang dihapus <strong>tidak dapat dikembalikan</strong> kecuali Anda memiliki file backup.</li>
                        <li>Setelah dikosongkan, gunakan tombol <strong>Seed Ulang Data Dasar</strong> untuk mengembalikan data referensi awal.</li>
                    </ul>
                    <div class="mt-3">
                        <a class="small fw-semibold text-decoration-none" data-bs-toggle="collapse" href="#tableListCollapse" role="button" aria-expanded="false" aria-controls="tableListCollapse">
                            <i class="ti ti-list-details me-1"></i>Tampilkan daftar tabel yang akan dikosongkan ({{ $tables->count() }})
                        </a>
                        <div class="collapse mt-2" id="tableListCollapse">
                            <div class="d-flex flex-wrap gap-1 small">
                                @foreach($tables as $table)
                                <span class="badge text-bg-light border">{{ $table }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-trash me-2 text-danger"></i>Form Kosongkan Database</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.database.truncate-execute') }}" method="POST" id="truncateForm">
                        @csrf
                        <div class="mb-3">
                            <label for="confirmation" class="form-label">Ketik <strong>KOSONGKAN</strong> untuk mengonfirmasi</label>
                            <input type="text" class="form-control @error('confirmation') is-invalid @enderror" id="confirmation" name="confirmation" placeholder="KOSONGKAN" required>
                            @error('confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 transition-base">
                            <i class="ti ti-trash me-1"></i>Kosongkan Database
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-refresh me-2 text-success"></i>Seed Ulang Data Dasar</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Mengembalikan data dasar aplikasi (roles, permissions, user, dan data referensi) setelah database dikosongkan.</p>
                    <form action="{{ route('admin.database.seed') }}" method="POST" id="seedForm">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 transition-base">
                            <i class="ti ti-refresh me-1"></i>Seed Ulang Data Dasar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        confirmAndProcess(document.getElementById('truncateForm'), {
            title: 'Kosongkan Database',
            text: 'Yakin ingin mengosongkan database?<br><span class="text-danger fw-semibold">Tindakan ini tidak dapat dibatalkan!</span>',
            icon: 'warning',
            confirmText: 'Ya, Kosongkan',
            loadingText: 'Sedang mengosongkan database...',
        });
        confirmAndProcess(document.getElementById('seedForm'), {
            title: 'Seed Ulang Data Dasar',
            text: 'Jalankan seeder untuk mengembalikan data dasar aplikasi?<br>Data yang sudah ada akan diperbarui/dipertahankan.',
            icon: 'info',
            confirmText: 'Ya, Seed Ulang',
            loadingText: 'Sedang meng-seed ulang data dasar...',
        });
    });
</script>
@endpush
