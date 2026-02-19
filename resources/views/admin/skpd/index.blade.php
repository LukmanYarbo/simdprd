@extends('layouts.admin')

@section('title', 'Daftar SKPD')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'SKPD', 'icon' => 'bi-building-fill']
]" />
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar SKPD</h6>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSkpdModal">
            <i class="bi bi-plus-lg"></i> Tambah SKPD
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="skpdTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama SKPD</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($skpds as $index => $skpd)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $skpd->namaskpd }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" 
                                        class="btn btn-warning btn-sm py-0 px-2 btn-edit" 
                                        data-id="{{ $skpd->id }}" 
                                        data-nama="{{ $skpd->namaskpd }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil-square" style="font-size: 0.8rem;"></i>
                                </button>
                                <form action="{{ route('admin.skpd.destroy', $skpd->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm py-0 px-2" title="Hapus">
                                        <i class="bi bi-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create SKPD -->
<div class="modal fade" id="createSkpdModal" tabindex="-1" aria-labelledby="createSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSkpdModalLabel">Tambah SKPD Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.skpd.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namaskpd" class="form-label">Nama SKPD <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('namaskpd') is-invalid @enderror" id="namaskpd" name="namaskpd" value="{{ old('namaskpd') }}" required>
                        @error('namaskpd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit SKPD -->
<div class="modal fade" id="editSkpdModal" tabindex="-1" aria-labelledby="editSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkpdModalLabel">Edit SKPD</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSkpdForm" action="#" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id" value="{{ old('id') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_namaskpd" class="form-label">Nama SKPD <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('namaskpd') is-invalid @enderror" id="edit_namaskpd" name="namaskpd" value="{{ old('namaskpd') }}" required>
                        @error('namaskpd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#skpdTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                }
            });

            // Handle Edit Button Click
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var url = "{{ route('admin.skpd.update', ':id') }}";
                url = url.replace(':id', id);

                $('#editSkpdForm').attr('action', url);
                $('#edit_id').val(id);
                $('#edit_namaskpd').val(nama);
                
                // Clear errors
                $('#edit_namaskpd').removeClass('is-invalid');
                $('#editSkpdForm .invalid-feedback').remove();

                var editModal = new bootstrap.Modal(document.getElementById('editSkpdModal'));
                editModal.show();
            });

            @if($errors->any())
                @if(old('_method') === 'PUT')
                    var editId = "{{ old('id') }}";
                    var actionUrl = "{{ route('admin.skpd.update', ':id') }}";
                    actionUrl = actionUrl.replace(':id', editId);
                    $('#editSkpdForm').attr('action', actionUrl);
                    
                    var editModal = new bootstrap.Modal(document.getElementById('editSkpdModal'));
                    editModal.show();
                @else
                    var createModal = new bootstrap.Modal(document.getElementById('createSkpdModal'));
                    createModal.show();
                @endif
            @endif
        });
    </script>
@endpush
