@extends('layouts.admin')

@section('title', 'Tarif Pajak')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Tarif Pajak" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
        ['label' => 'Tarif Pajak', 'icon' => 'bi-percent']
    ]" />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="ti ti-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="ti ti-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 mb-4 mt-4">
        <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary"><i class="ti ti-percentage me-2"></i>Daftar Tarif Pajak PPh 21</h6>
            @can('create tarif_pajak')
                @if($hasActive)
                    <button class="btn btn-primary btn-sm px-3 shadow-sm disabled" disabled
                        data-bs-toggle="tooltip" title="Nonaktifkan tarif aktif terlebih dahulu.">
                        <i class="ti ti-plus-lg me-1"></i>Tambah Tarif
                    </button>
                @else
                    <a href="{{ route('admin.tarif-pajak.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="ti ti-plus-lg me-1"></i>Tambah Tarif
                    </a>
                @endif
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary text-muted small">
                        <tr>
                            <th class="px-4" width="4%">No</th>
                            <th>No. Peraturan</th>
                            <th>Tgl Berlaku</th>
                            <th>PTKP Dasar</th>
                            <th>Biaya Jabatan</th>
                            <th>Lapis Pajak</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tarifPajak as $i => $item)
                        <tr>
                            <td class="px-4 text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->no_peraturan }}</div>
                            </td>
                            <td>{{ $item->tgl_berlaku->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="small">
                                    <span class="text-muted">TK/0:</span>
                                    <strong>Rp {{ number_format($item->ptkp, 0, ',', '.') }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    {{ $item->persen_biaya_jabatan }}%
                                    <span class="text-muted">/ maks</span>
                                    Rp {{ number_format($item->max_biaya_jabatan, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                @if($item->lapisPajak->count())
                                    <span class="badge bg-info text-dark">{{ $item->lapisPajak->count() }} Lapis</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status === 'Y')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm">
                                    @can('edit tarif_pajak')
                                        <a href="{{ route('admin.tarif-pajak.edit', $item->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                            <i class="ti ti-pencil text-warning"></i>
                                        </a>
                                    @endcan
                                    @can('delete tarif_pajak')
                                        <button type="button" onclick="confirmDelete('{{ route('admin.tarif-pajak.destroy', $item->id) }}')"
                                            class="btn btn-sm btn-light border {{ $item->status === 'Y' ? 'disabled' : '' }}"
                                            title="{{ $item->status === 'Y' ? 'Tidak bisa hapus tarif aktif' : 'Hapus' }}">
                                            <i class="ti ti-trash text-danger"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="ti ti-inbox display-6 d-block mb-2"></i>
                                Belum ada data tarif pajak.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    function confirmDelete(action) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: 'Tarif pajak ini akan dihapus secara permanen.<br><small class="text-muted">Semua data lapis pajak terkait akan ikut terhapus.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = action;
                form.submit();
            }
        });
    }
</script>
@endpush
