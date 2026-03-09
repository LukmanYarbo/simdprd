<div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">Data Potongan</h5>
            <button wire:click="create" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Potongan
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tunjangan BPJS (%)</th>
                            <th>Potongan BPJS (%)</th>
                            <th>Maksimal Potongan BPJS</th>
                            <th>JKK (%)</th>
                            <th>JKM (%)</th>
                            <th>Maks JKK/JKM</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($potongans as $index => $item)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td>{{ $item->tunjangan_bpjs }}%</td>
                            <td>{{ $item->potongan_bpjs }}%</td>
                            <td>Rp {{ number_format($item->maksimal_potongan_bpjs, 0, ',', '.') }}</td>
                            <td>{{ $item->jkk }}%</td>
                            <td>{{ $item->jkm }}%</td>
                            <td>Rp {{ number_format($item->maks_jkkjkm, 0, ',', '.') }}</td>
                            <td class="text-end pe-4">
                                <button wire:click="edit({{ $item->id }})" class="btn btn-outline-primary btn-sm me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirmDelete({{ $item->id }})" class="btn btn-outline-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data potongan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="modalPotongan" tabindex="-1" aria-labelledby="modalPotonganLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPotonganLabel">{{ $isEditMode ? 'Edit' : 'Tambah' }} Potongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tunjangan BPJS (%)</label>
                            <input type="number" step="0.01" wire:model="tunjangan_bpjs" class="form-control @error('tunjangan_bpjs') is-invalid @enderror" placeholder="0.00">
                            @error('tunjangan_bpjs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Potongan BPJS (%)</label>
                            <input type="number" step="0.01" wire:model="potongan_bpjs" class="form-control @error('potongan_bpjs') is-invalid @enderror" placeholder="0.00">
                            @error('potongan_bpjs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maksimal Potongan BPJS (Rupiah)</label>
                            <input type="number" wire:model="maksimal_potongan_bpjs" class="form-control @error('maksimal_potongan_bpjs') is-invalid @enderror" placeholder="0">
                            @error('maksimal_potongan_bpjs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JKK (%)</label>
                            <input type="number" step="0.01" wire:model="jkk" class="form-control @error('jkk') is-invalid @enderror" placeholder="0.00">
                            @error('jkk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JKM (%)</label>
                            <input type="number" step="0.01" wire:model="jkm" class="form-control @error('jkm') is-invalid @enderror" placeholder="0.00">
                            @error('jkm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maksimal JKK dan JKM (Rupiah)</label>
                            <input type="number" wire:model="maks_jkkjkm" class="form-control @error('maks_jkkjkm') is-invalid @enderror" placeholder="0">
                            @error('maks_jkkjkm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Update' : 'Simpan' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('openModal', () => {
            const modal = new bootstrap.Modal(document.getElementById('modalPotongan'));
            modal.show();
        });

        $wire.on('closeModal', () => {
            const modalElement = document.getElementById('modalPotongan');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        });

        window.confirmDelete = (id) => {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('deletePotongan', { id: id });
                }
            });
        }
    </script>
    @endscript
</div>
