@extends('layouts.admin')

@section('title', 'Data Penanda Tangan')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Penanda Tangan', 'icon' => 'bi-pen-fill']
]" />
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penanda Tangan</h6>
            @can('create penanda_tangan')
            <a href="{{ route('admin.penanda-tangan.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-plus-lg text-white-50"></i> Tambah Penanda Tangan
            </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="penandaTanganTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0">No</th>
                            <th class="border-top-0">Jenis Dokumen</th>
                            <th class="border-top-0">SKPD</th>
                            <th class="border-top-0">Anggota DPRD</th>
                            <th class="border-top-0">Penanda Tangan ASN</th>
                            <th class="text-center border-top-0" style="width: 12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penandaTangan as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($penandaTangan->currentPage() - 1) * $penandaTangan->perPage() }}</td>
                            <td>
                                @php
                                    $badgeClass = match($item->jenis_dokumen) {
                                        'Surat Tugas'     => 'bg-success-subtle text-success border-success-subtle',
                                        'SPPD'            => 'bg-warning-subtle text-warning border-warning-subtle',
                                        'Surat Keputusan' => 'bg-primary-subtle text-primary border-primary-subtle',
                                        default           => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                    };
                                @endphp
                                <span class="badge border {{ $badgeClass }}">{{ $item->jenis_dokumen }}</span>
                            </td>
                            <td>{{ $item->skpd->namaskpd ?? '<span class="text-muted">-</span>' }}</td>
                            <td>
                                @if($item->anggota)
                                    <div class="fw-semibold text-body">{{ $item->anggota->nama_anggota }}</div>
                                    <small class="text-muted">{{ $item->anggota->jabatan->nama ?? '-' }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->pegawaiAsn)
                                    <div class="fw-semibold text-body">{{ $item->pegawaiAsn->nama }}</div>
                                    <small class="text-muted">{{ $item->pegawaiAsn->nip }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @can('edit penanda_tangan')
                                    <a href="{{ route('admin.penanda-tangan.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan
                                    @can('delete penanda_tangan')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.penanda-tangan.destroy', $item->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data penanda tangan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $penandaTangan->links() }}
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
