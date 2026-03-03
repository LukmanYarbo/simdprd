@extends('layouts.admin')

@section('title', 'Data Jabatan ASN')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Data Jabatan ASN', 'icon' => 'bi-briefcase-fill']
]" />
@endsection

<!-- @push('styles')
    
@endpush -->


@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Jabatan ASN</h6>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalJabatanAsn" onclick="resetForm()">
            <i class="bi bi-plus-lg"></i> Tambah Jabatan ASN
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th>No</th>
                            <th>Nama Jabatan</th>
                            <th>SKPD</th>
                            <th>Esselon</th>
                            <th class="text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jabatan as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($jabatan->currentPage() - 1) * $jabatan->perPage() }}</td>
                            <td>{{ $item->nama_jabatan }}</td>
                            <td>{{ $item->skpd->namaskpd ?? '-' }}</td>
                            <td>{{ $item->esselon->nama ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning py-0 px-2" style="font-size: 0.75rem;" onclick="editJabatan({{ $item->id }}, '{{ $item->nama_jabatan }}', '{{ $item->id_esselon }}', '{{ $item->id_skpd }}')">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger py-0 px-2" style="font-size: 0.75rem;" onclick="confirmDelete({{ $item->id }})">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.jabatan-asn.destroy', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data jabatan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $jabatan->links() }}
            </div>
        </div>
        
    </div>



<!-- Modal -->
<div class="modal fade" id="modalJabatanAsn" tabindex="-1" aria-labelledby="modalJabatanAsnLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJabatanAsnLabel">Tambah Jabatan ASN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formJabatanAsn" action="{{ route('admin.jabatan-asn.store') }}" method="POST">
                    @csrf
                    <div id="method-field"></div>
                    
                    <div class="mb-3">
                        <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
                    </div>
                     <div class="mb-3">
                        <label for="id_skpd" class="form-label">SKPD</label>
                        <select class="form-select" id="id_skpd" name="id_skpd">
                            <option value="">Pilih SKPD (Opsional)</option>
                            @foreach($skpd as $s)
                                <option value="{{ $s->id }}">{{ $s->namaskpd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_esselon" class="form-label">Esselon</label>
                        <select class="form-select" id="id_esselon" name="id_esselon" required>
                            <option value="">Pilih Esselon</option>
                            @foreach($esselon as $ess)
                                <option value="{{ $ess->id }}">{{ $ess->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global Modal Instance
    let modalJabatanAsn;

    document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('modalJabatanAsn');
        if(modalEl) {
            modalJabatanAsn = new bootstrap.Modal(modalEl);
        }
    });

    function resetForm() {
        document.getElementById('formJabatanAsn').action = "{{ route('admin.jabatan-asn.store') }}";
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('nama_jabatan').value = '';
        document.getElementById('id_skpd').value = '';
        document.getElementById('id_esselon').value = '';
        document.getElementById('modalJabatanAsnLabel').innerText = 'Tambah Jabatan ASN';
    }

    function editJabatan(id, nama, esselonId, skpdId) {
        // Reset form first to clear any potential issues
        resetForm();
        
        let url = "{{ route('admin.jabatan-asn.update', ':id') }}";
        url = url.replace(':id', id);
        
        document.getElementById('formJabatanAsn').action = url;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('nama_jabatan').value = nama;
        document.getElementById('id_skpd').value = skpdId;
        document.getElementById('id_esselon').value = esselonId;
        document.getElementById('modalJabatanAsnLabel').innerText = 'Edit Jabatan ASN';
        
        if(modalJabatanAsn) {
            modalJabatanAsn.show();
        } else {
            // Fallback if DOMContentLoaded didn't fire or var is lost
            new bootstrap.Modal(document.getElementById('modalJabatanAsn')).show();
        }
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
