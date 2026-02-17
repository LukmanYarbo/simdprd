@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[['label' => 'Anggota', 'icon' => 'bi-people']]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Manajemen Anggota</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white text-opacity-75 small fw-bold text-uppercase">Total Anggota</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 bg-success text-white overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-person-check-fill fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white text-opacity-75 small fw-bold text-uppercase">Anggota Aktif</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['aktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-dark bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-person-x-fill fs-3 text-dark"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-dark text-opacity-75 small fw-bold text-uppercase">Non-Aktif / Pensiun</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['nonAktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-muted">Daftar Anggota</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="anggota-table">
                    <thead class="bg-light text-muted">
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
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .stats-icon i {
        line-height: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end pe-4'},
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
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
