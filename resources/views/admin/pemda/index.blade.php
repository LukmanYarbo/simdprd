@extends('layouts.admin')


@section('title', 'Data Pemda')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Data Pemda" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
        ['label' => 'Data Pemda', 'icon' => 'ti ti-building-community']
    ]" />

    <div class="glass-table-card mt-4">
        <div class="table-card-header">
            <h5><span class="icon-box-sm d-inline-grid place-items-center rounded-3 p-2 me-1" style="background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff;"><i class="ti ti-building-community"></i></span> Daftar Pemda</h5>
            <a href="{{ route('admin.pemda.create') }}" class="btn-modern-primary btn-sm">
                <i class="ti ti-plus me-1"></i> Tambah Data Pemda
            </a>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="bg-body-tertiary text-uppercase" style="font-size:.72rem; letter-spacing:.06em; color:#64748b;">
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
                                <div class="table-actions">
                                    <a href="{{ route('admin.pemda.edit', $item->id) }}" class="btn-action-sk edit" data-tip="Edit" aria-label="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <button class="btn-action-sk delete" data-tip="Hapus" aria-label="Hapus" onclick="confirmDelete({{ $item->id }})">
                                        <i class="ti ti-trash-x"></i>
                                    </button>
                                </div>
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
