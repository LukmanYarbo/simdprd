@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Settings', 'icon' => 'ti ti-settings-2'],
    ['label' => 'Backup Database', 'icon' => 'ti ti-database']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Backup Database</h2>
            <p class="text-muted small mb-0">Buat salinan seluruh isi database dalam bentuk file SQL.</p>
        </div>
        <div class="col-auto">
            <form action="{{ route('admin.database.backup-create') }}" method="POST" id="backupForm">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm transition-base">
                    <i class="ti ti-file-download me-1"></i>Buat Backup Sekarang
                </button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-body-tertiary text-muted">
                        <tr>
                            <th class="border-0" width="5%">No</th>
                            <th class="border-0">Nama File</th>
                            <th class="border-0">Ukuran (KB)</th>
                            <th class="border-0">Tanggal Dibuat</th>
                            <th class="border-0 text-end pe-4" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $index => $backup)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="fw-semibold">{{ $backup['name'] }}</span></td>
                            <td>{{ number_format($backup['size'], 2) }}</td>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($backup['date'])->locale('id')->translatedFormat('d F Y H:i:s') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.database.backup-download', $backup['name']) }}" class="btn btn-sm btn-outline-success" title="Download">
                                        <i class="ti ti-download"></i>
                                    </a>
                                    <form action="{{ route('admin.database.backup-delete', $backup['name']) }}" method="POST" onsubmit="return confirm('Hapus backup ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada backup. Klik "Buat Backup Sekarang" untuk membuat backup pertama.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        confirmAndProcess(document.getElementById('backupForm'), {
            title: 'Backup Database',
            text: 'Buat salinan seluruh isi database?<br>Proses ini dapat memakan waktu beberapa saat.',
            icon: 'info',
            confirmText: 'Ya, Buat Backup',
            loadingText: 'Sedang membuat backup database...',
        });
    });
</script>
@endpush
