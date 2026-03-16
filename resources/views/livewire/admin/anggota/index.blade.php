<div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text  border-end-0">
                    <i class="ti ti-search text-muted"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama atau NIK...">
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4">Nama / NIK</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Kontak</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $item)
                        <tr wire:key="anggota-{{ $item->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($item->foto_anggota)
                                        <img src="{{ asset('storage/' . $item->foto_anggota) }}" class="rounded-circle me-3" width="40" height="40" alt="">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ $item->nama_anggota }}&background=random" class="rounded-circle me-3" width="40" height="40" alt="">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $item->nama_anggota }}</h6>
                                        <small class="text-muted">{{ $item->nik }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->jabatan->nama }}</td>
                            <td>
                                <span class="badge {{ $item->statusKeanggotaan->nama == 'Aktif' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $item->statusKeanggotaan->nama }}
                                </span>
                            </td>
                            <td>
                                <div><small><i class="ti ti-envelope me-1"></i>{{ $item->email }}</small></div>
                                <div><small><i class="ti ti-telephone me-1"></i>{{ $item->no_telp }}</small></div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.anggota.show', $item) }}" class="btn btn-icon-only btn-sm btn-outline-info" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.anggota.edit', $item) }}" class="btn btn-icon-only btn-sm btn-outline-primary" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus anggota ini?" class="btn btn-icon-only btn-sm btn-outline-danger" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data anggota.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3">
            {{ $anggota->links() }}
        </div>
    </div>
</div>
