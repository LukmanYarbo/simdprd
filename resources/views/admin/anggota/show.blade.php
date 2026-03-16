@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detail Anggota</h2>
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.anggota.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.anggota.print', $anggota->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                <i class="ti ti-printer"></i> Cetak
            </a>
            <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i> Edit
            </a>
        </div>
    </div>

    <livewire:admin.anggota.show :anggota="$anggota" />
</div>
@endsection
