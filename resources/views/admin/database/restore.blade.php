@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Settings', 'icon' => 'ti ti-settings-2'],
    ['label' => 'Restore Database', 'icon' => 'ti ti-database']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="h4 fw-bold mb-1">Restore Database</h2>
    <p class="text-muted small mb-4">Pulihkan isi database dari file backup SQL.</p>

    <div class="alert alert-warning d-flex align-items-start small" role="alert">
        <i class="ti ti-alert-triangle me-2 mt-1"></i>
        <div>Restore akan <strong>menimpa seluruh data</strong> yang ada saat ini (termasuk tabel yang dipertahankan saat Kosongkan Database) dengan isi file backup. Pastikan Anda memiliki backup terbaru sebelum melanjutkan.</div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-folder-open me-2 text-primary"></i>Restore dari Backup Tersimpan</h5>
                </div>
                <div class="card-body">
                    @if($backups->isNotEmpty())
                    <form action="{{ route('admin.database.restore-execute') }}" method="POST" id="restoreExistingForm">
                        @csrf
                        <input type="hidden" name="source" value="existing">
                        <div class="mb-3">
                            <label for="backup_file" class="form-label">Pilih File Backup</label>
                            <select class="form-select @error('backup_file') is-invalid @enderror" id="backup_file" name="backup_file" required>
                                <option value="">-- Pilih file backup --</option>
                                @foreach($backups as $backup)
                                <option value="{{ $backup['name'] }}">{{ $backup['name'] }} ({{ number_format($backup['size'], 2) }} KB - {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->locale('id')->translatedFormat('d F Y H:i:s') }})</option>
                                @endforeach
                            </select>
                            @error('backup_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="confirmation_existing" class="form-label">Ketik <strong>RESTORE</strong> untuk mengonfirmasi</label>
                            <input type="text" class="form-control @error('confirmation') is-invalid @enderror" id="confirmation_existing" name="confirmation" placeholder="RESTORE" required>
                            @error('confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 transition-base">
                            <i class="ti ti-database-import me-1"></i>Restore Database
                        </button>
                    </form>
                    @else
                    <p class="small text-muted mb-0">Belum ada backup tersimpan. Buat backup terlebih dahulu di menu <a href="{{ route('admin.database.backup') }}">Backup Database</a>.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-upload me-2 text-success"></i>Restore dari Upload File</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.database.restore-execute') }}" method="POST" enctype="multipart/form-data" id="restoreUploadForm">
                        @csrf
                        <input type="hidden" name="source" value="upload">
                        <div class="mb-3">
                            <label for="uploaded_file" class="form-label">Pilih File Backup (.sql / .txt)</label>
                            <input type="file" class="form-control @error('uploaded_file') is-invalid @enderror" id="uploaded_file" name="uploaded_file" accept=".sql,.txt" required>
                            @error('uploaded_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="confirmation_upload" class="form-label">Ketik <strong>RESTORE</strong> untuk mengonfirmasi</label>
                            <input type="text" class="form-control @error('confirmation') is-invalid @enderror" id="confirmation_upload" name="confirmation" placeholder="RESTORE" required>
                            @error('confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success rounded-pill px-4 transition-base">
                            <i class="ti ti-upload me-1"></i>Upload & Restore
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
        confirmAndProcess(document.getElementById('restoreExistingForm'), {
            title: 'Restore Database',
            text: 'Restore database dari file backup terpilih?<br><span class="text-danger fw-semibold">Seluruh data saat ini akan ditimpa!</span>',
            icon: 'warning',
            confirmText: 'Ya, Restore',
            loadingText: 'Sedang me-restore database...',
        });
        confirmAndProcess(document.getElementById('restoreUploadForm'), {
            title: 'Restore Database',
            text: 'Restore database dari file yang diupload?<br><span class="text-danger fw-semibold">Seluruh data saat ini akan ditimpa!</span>',
            icon: 'warning',
            confirmText: 'Ya, Restore',
            loadingText: 'Sedang me-restore database...',
        });
    });
</script>
@endpush
