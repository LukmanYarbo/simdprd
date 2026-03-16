@extends('layouts.admin')


@section('title', 'Data Pemda')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Data Pemda" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
        ['label' => 'Data Pemda', 'icon' => 'ti ti-building-community']
    ]" />

    <div class="card shadow mb-4 mt-4">

    
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pemda</h6>
            <a href="{{ route('admin.pemda.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm transition-base">
                <i class="ti ti-plus me-1"></i> Tambah Data Pemda
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th>No</th>
                            <th>Nama Pemda</th>
                            <th>Bupati</th>
                            <th>Sekda</th>
                            <th>Kota/Kab</th>
                            <th class="text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemda as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->namapemda }}</td>
                            <td>
                                <div><strong>{{ $item->nama_bupati }}</strong></div>
                                <small class="text-muted">{{ $item->jabatan_bupati }}</small>
                            </td>
                            <td>
                                @if($item->sekda)
                                    <div><strong>{{ $item->sekda->nama }}</strong></div>
                                    <small class="text-muted">{{ $item->sekda->jabatanAsn->nama_jabatan ?? '-' }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->kota }} / {{ $item->kabupaten }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.pemda.edit', $item->id) }}" class="btn btn-icon-only btn-sm btn-outline-primary" title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </a>
                                <button class="btn btn-icon-only btn-sm btn-outline-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.pemda.destroy', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data Pemda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
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
</script>
@endpush
