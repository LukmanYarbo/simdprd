@extends('layouts.admin')

@section('title', 'Data Pegawai ASN')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Data Pegawai ASN', 'icon' => 'bi-person-badge-fill']
]" />
@endsection

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pegawai ASN</h6>
             <a href="{{ route('admin.pegawai-asn.create') }}" class="btn btn-sm btn-primary shadow-sm justify-content-end">
            <i class="bi bi-plus-lg text-white-50"></i> Tambah Pegawai
        </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0">No</th>
                            <th class="border-top-0">Foto</th>
                            <th class="border-top-0">NIP / Nama</th>
                            <th class="border-top-0">Jabatan</th>
                            <th class="border-top-0">Pangkat/Gol</th>
                            <th class="text-center border-top-0" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawai as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($pegawai->currentPage() - 1) * $pegawai->perPage() }}</td>
                            <td>
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="bg-body-secondary rounded-circle d-flex align-items-center justify-content-center text-secondary shadow-sm" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-body">{{ $item->nama }}</div>
                                <small class="text-secondary">{{ $item->nip }}</small>
                            </td>
                            <td>
                                <div class="mb-1"><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $item->jabatanAsn->nama_jabatan ?? '-' }}</span></div>
                                <small class="text-muted"><i class="bi bi-circle-fill {{ $item->statusPegawai->nama == 'Aktif' ? 'text-success' : 'text-danger' }} me-1" style="font-size: 8px;"></i>{{ $item->statusPegawai->nama ?? '-' }}</small>
                            </td>
                            <td>{{ $item->pangkatGolongan->pangkat ?? '-' }} <span class="text-muted small">({{ $item->pangkatGolongan->golongan ?? '-' }})</span></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pegawai-asn.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.pegawai-asn.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.pegawai-asn.destroy', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted"> <i class="bi bi-inbox fs-1 d-block mb-2"></i> Tidak ada data pegawai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pegawai->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 1500,
            showConfirmButton: false
        });
    @endif
</script>
@endpush
