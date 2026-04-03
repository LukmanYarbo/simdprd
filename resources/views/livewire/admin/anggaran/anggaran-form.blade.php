<div>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="ti ti-report-money text-primary me-2"></i> {{ $isEditMode ? 'Edit' : 'Tambah' }} Anggaran</h4>
                        <p class="text-muted small mb-0">Atur pagu anggaran dari rincian kebutuhan setahun.</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.anggaran.index') }}" class="btn btn-light rounded-pill px-3 shadow-sm border">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="button" wire:click="store" class="btn btn-primary rounded-pill px-4 shadow-sm transition-base ms-2">
                            <i class="ti ti-device-floppy me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">Tahun Anggaran</label>
                            <input type="number" class="form-control rounded-3 @error('tahun_anggaran') is-invalid @enderror" wire:model="tahun_anggaran">
                            @error('tahun_anggaran') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">Status</label>
                            <select class="form-select rounded-3 @error('status') is-invalid @enderror" wire:model="status">
                                <option value="DRAFT">Draft</option>
                                <option value="FINAL">Final</option>
                            </select>
                            @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <div class="text-end bg-light px-4 py-2 rounded-3 border">
                                <span class="text-muted small fw-bold d-block">Total Pagu Anggaran</span>
                                <h4 class="fw-bold text-success mb-0">Rp {{ number_format($total_pagu, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small py-3 px-3" style="width: 40%">Uraian Item</th>
                                    <th class="small py-3" style="width: 50%">Total Pagu Setahun (Rp) / Besaran</th>
                                    <th class="small py-3 text-center" style="width: 10%"><i class="ti ti-settings"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rincians as $index => $item)
                                <tr>
                                    <td class="px-3">
                                        <input type="text" class="form-control form-control-sm" wire:model="rincians.{{$index}}.uraian" placeholder="Nama item/uraian">
                                        @error('rincians.'.$index.'.uraian') <span class="text-danger" style="font-size: 10px">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-end-0">Rp</span>
                                            <input type="text" oninput="formatRibuan(this)" wire:model.live="rincians.{{$index}}.besaran" class="form-control border-start-0">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" wire:click="removeRow({{ $index }})" class="btn btn-sm btn-outline-danger btn-icon-only rounded-circle" title="Hapus Baris" tabindex="-1">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" wire:click="addRow" class="btn btn-sm btn-light border text-primary rounded-pill px-3">
                            <i class="ti ti-plus me-1"></i> Tambah Baris Rincian
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .btn-icon-only { width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
</style>
@endpush

@script
<script>
    window.formatRibuan = function(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };
</script>
@endscript
