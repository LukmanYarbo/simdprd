@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detail Anggota</h2>
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
            <a href="{{ route('admin.anggota.print', $anggota->id) }}" target="_blank" class="btn btn-outline-info">
                <i class="bi bi-printer me-1"></i> Cetak
            </a>
            <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
        </div>
    </div>

    <livewire:admin.anggota.show :anggota="$anggota" />
</div>
@endsection
