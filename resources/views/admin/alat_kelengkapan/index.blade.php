@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[['label' => 'Alat Kelengkapan', 'icon' => 'bi-diagram-3']]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Alat Kelengkapan DPRD</h2>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-primary shadow-sm btn-add">
                <i class="bi bi-plus-lg me-2"></i>Tambah Baru
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="alat-kelengkapan-table">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="border-0" width="5%">No</th>
                            <th class="border-0">Nama</th>
                            <th class="border-0">Keterangan</th>
                            <th class="border-0 text-end pe-4" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAlatKelengkapan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Alat Kelengkapan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formAlatKelengkapan">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-4">
                        <label for="ket" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="ket" name="ket" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
    var table = $('#alat-kelengkapan-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.alat-kelengkapan.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'ket', name: 'ket'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end pe-4'},
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
        }
    });

    const modal = new bootstrap.Modal(document.getElementById('modalAlatKelengkapan'));
    
    function openModal() {
        $('#formAlatKelengkapan')[0].reset();
        $('#id').val('');
        $('#modalTitle').text('Tambah Alat Kelengkapan');
        $('#btnSave').text('Simpan');
        $('.is-invalid').removeClass('is-invalid');
        modal.show();
    }

    function editItem(id) {
        $.get("{{ route('admin.alat-kelengkapan.index') }}" + '/' + id + '/edit', function(data) {
            $('#id').val(data.id);
            $('#nama').val(data.nama);
            $('#ket').val(data.ket);
            $('#modalTitle').text('Edit Alat Kelengkapan');
            $('#btnSave').text('Simpan Perubahan');
            $('.is-invalid').removeClass('is-invalid');
            modal.show();
        });
    }

    // Event Delegation
    $(document).on('click', '.btn-add', function() {
        openModal();
    });

    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        editItem(id);
    });

    $('#formAlatKelengkapan').submit(function(e) {
        e.preventDefault();
        var id = $('#id').val();
        var url = id ? "{{ route('admin.alat-kelengkapan.index') }}" + '/' + id : "{{ route('admin.alat-kelengkapan.store') }}";
        var method = id ? 'PUT' : 'POST';
        
        $.ajax({
            data: $(this).serialize() + (id ? '&_method=PUT' : ''),
            url: url,
            type: "POST",
            dataType: 'json',
            success: function(data) {
                modal.hide();
                table.draw();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.success,
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(data) {
                if(data.status === 422) {
                    var errors = data.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#'+key).addClass('is-invalid');
                        $('#'+key).next('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan pada server.', 'error');
                }
            }
        });
    });

    window.deleteItem = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.alat-kelengkapan.index') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw();
                        Swal.fire('Terhapus!', response.success, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    }
});
</script>
@endpush
