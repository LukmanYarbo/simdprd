@extends('layouts.admin')

@section('title', 'Daftar SKPD')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'SKPD', 'icon' => 'ti ti-building-community']
]" />
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar SKPD</h6>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm transition-base" data-bs-toggle="modal" data-bs-target="#createSkpdModal">
            <i class="ti ti-plus me-1"></i> Tambah SKPD
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
                            <div class="d-flex gap-1">
                                <button type="button" 
                                        class="btn btn-icon-only btn-sm btn-outline-primary btn-edit" 
                                        data-id="{{ $skpd->id }}" 
                                        data-nama="{{ $skpd->namaskpd }}" 
                                        title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form action="{{ route('admin.skpd.destroy', $skpd->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon-only btn-sm btn-outline-danger" title="Hapus">
                                        <i class="ti ti-trash"></i>
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
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold text-primary" id="createSkpdModalLabel">
                    <i class="ti ti-plus me-2"></i>Tambah SKPD Baru
                </h5>
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
                <div class="modal-footer border-top-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm transition-base">
                        <i class="ti ti-device-floppy me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit SKPD -->
<div class="modal fade" id="editSkpdModal" tabindex="-1" aria-labelledby="editSkpdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold text-primary" id="editSkpdModalLabel">
                    <i class="ti ti-pencil me-2"></i>Edit SKPD
                </h5>
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
                <div class="modal-footer border-top-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm transition-base">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                    </button>
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
