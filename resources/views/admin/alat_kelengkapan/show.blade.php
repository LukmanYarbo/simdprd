@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Alat Kelengkapan', 'url' => route('admin.alat-kelengkapan.index'), 'icon' => 'bi-diagram-3'],
    ['label' => 'Detail', 'icon' => 'bi-eye']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <!-- Not explicitly requested but good to have empty file if not used or simple details -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header  py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Detail Alat Kelengkapan</h5>
                    <a href="{{ route('admin.alat-kelengkapan.index') }}" class="btn btn-sm btn-light border">Kembali</a>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Nama</th>
                            <td>{{ $alatKelengkapan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $alatKelengkapan->ket ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
