@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
     ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Anggota', 'icon' => 'ti ti-users']]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Manajemen Anggota</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.anggota.create') }}" class="btn premium-gradient border-0 glow-shadow px-4 py-2 rounded-pill transition-base">
                <i class="ti ti-plus me-2"></i>Tambah Anggota
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card glass-card border-0 glow-shadow h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon rounded-4 p-3 me-3" style="background: rgba(99, 102, 241, 0.15); color: #6366f1;">
                            <i class="ti ti-users fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-secondary small fw-bold text-uppercase tracking-wider">Total Anggota</p>
                            <h3 class="mb-0 fw-extrabold text-gradient">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card glass-card border-0 glow-shadow h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon rounded-4 p-3 me-3" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                            <i class="ti ti-user-check fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-secondary small fw-bold text-uppercase tracking-wider">Anggota Aktif</p>
                            <h3 class="mb-0 fw-extrabold text-success">{{ $stats['aktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card glass-card border-0 glow-shadow h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon rounded-4 p-3 me-3" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                            <i class="ti ti-user-off fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-secondary small fw-bold text-uppercase tracking-wider">Non-Aktif</p>
                            <h3 class="mb-0 fw-extrabold text-warning">{{ $stats['nonAktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card glass-card border-0 shadow-lg fade-in-up">
        <div class="card-header py-4 bg-transparent border-bottom border-white border-opacity-10">
            <h5 class="mb-0 fw-bold text-gradient">Daftar Anggota</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="anggota-table">
                    <thead class="bg-body-tertiary text-muted">
                        <tr>
                            <th class="border-0">No</th>
                            <th class="border-0">Nama / NIK</th>
                            <th class="border-0">Jabatan</th>
                          
                            <th class="border-0">Status</th>
                            <th class="border-0">Kontak</th>
                            <th class="border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.anggota.partials.modal-keluarga')

@include('admin.anggota.partials.modal-pendidikan')
@include('admin.anggota.partials.modal-harta')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/css/dataTables.bootstrap5.min.css') }}">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
        margin-left: 0;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.4rem 1rem;
        background-color: #f8f9fa;
        margin-left: 10px;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.4rem 2rem 0.4rem 1rem;
        background-color: #f8f9fa;
    }
    #anggota-table thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.1em;
        color: #94a3b8;
        padding: 1.25rem 1rem;
    }
    .stats-icon i {
        line-height: 1;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    [data-bs-theme="dark"] .glass-card {
        background: rgba(15, 23, 42, 0.6);
    }
</style>
@endpush

@prepend('scripts')
<script src="{{ asset('assets/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
@endprepend

@push('scripts')

<script>
$(function() {
    var table = $('#anggota-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.anggota.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_nik', name: 'nama_anggota'},
            {data: 'jabatan.nama', name: 'jabatan.nama'},
          
            {data: 'status', name: 'statusKeanggotaan.nama'},
            {data: 'kontak', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end pe-4', render: function(data, type, row) {
                var $btn = $(data);
                $btn.prepend('<button type="button" class="btn-icon-modern text-success btn-harta" data-id="' + row.id + '" title="Kelola Harta"><i class="ti ti-wallet"></i></button>');
                $btn.prepend('<button type="button" class="btn-icon-modern text-info btn-pendidikan" data-id="' + row.id + '" title="Kelola Pendidikan"><i class="ti ti-school"></i></button>');
                return $btn[0].outerHTML;
            }},
        ],
        order: [], // Disable initial sort to respect server-side ordering
        language: {
            url: "{{ asset('assets/libs/datatables/i18n/id.json') }}",
            search: "_INPUT_",
            searchPlaceholder: "Cari data..."
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm justify-content-end');
        }
    });

    window.deleteAnggota = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data anggota ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.anggota.index') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw();
                        Swal.fire(
                            'Terhapus!',
                            response.message || 'Data anggota berhasil dihapus.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.status === 403) {
                            errorMessage = 'Anda tidak memiliki hak akses untuk menghapus data ini.';
                        }
                        
                        Swal.fire(
                            'Gagal!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            }
        });
    }


});
</script>
@endpush



