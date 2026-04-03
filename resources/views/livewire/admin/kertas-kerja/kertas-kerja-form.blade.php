<div>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="ti ti-file-analytics text-primary me-2"></i>
                            {{ $isEditMode ? 'Edit' : 'Tambah' }} Kertas Kerja Anggaran</h4>
                        <p class="text-muted small mb-0">Susun dan kalkulasikan formasi pagu anggaran DPRD.</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.kertas-kerja.index') }}"
                            class="btn btn-light rounded-pill px-3 shadow-sm border">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="button" wire:click="store"
                            class="btn btn-primary rounded-pill px-4 shadow-sm transition-base ms-2">
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
                            <input type="number"
                                class="form-control rounded-3 @error('tahun_anggaran') is-invalid @enderror"
                                wire:model="tahun_anggaran">
                            @error('tahun_anggaran') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">Status</label>
                            <select class="form-select rounded-3 @error('status') is-invalid @enderror"
                                wire:model="status">
                                <option value="DRAFT">Draft</option>
                                <option value="FINAL">Final</option>
                            </select>
                            @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <div class="text-end bg-light px-4 py-2 rounded-3 border">
                                <span class="text-muted small fw-bold d-block">Estimasi Total Pagu</span>
                                <h4 class="fw-bold text-success mb-0">Rp {{ number_format($total_pagu, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small py-3 px-3" style="width: 20%">Kategori / Uraian</th>
                                    <th class="small py-3" style="width: 25%">Besaran (Rp)</th>
                                    <th class="small py-3 text-center" style="width: 10%">Orang</th>
                                    <th class="small py-3 text-center" style="width: 15%">Bulan/Kali</th>
                                    <th class="small py-3 text-end pe-3" style="width: 20%">Jumlah (Total)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $currentCategory = ''; @endphp
                                @foreach($rincians as $index => $item)
                                    @if($currentCategory !== $item['kategori'])
                                        <tr class="table-light">
                                            <td colspan="4"
                                                class="fw-bold px-3 text-primary bg-secondary bg-opacity-10 py-2 border-top d-flex align-items-center">
                                                <i class="ti ti-folder text-warning me-2"></i> {{ $item['kategori'] }}
                                            </td>
                                            <td class="text-end fw-bold text-primary bg-secondary bg-opacity-10 py-2 border-top pe-3"
                                                style="font-size: 1.05em;">
                                                {{ number_format($kategori_totals[$item['kategori']] ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @php $currentCategory = $item['kategori']; @endphp
                                    @endif
                                    <tr>
                                        <td class="px-3 position-relative">
                                            <div class="ps-3 border-start border-2 border-primary">
                                                <span class="d-block fw-medium text-dark">{{ $item['jabatan'] }}</span>
                                                <small class="text-muted"
                                                    style="font-size: 11px">{{ $item['uraian'] }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                                <input type="text" oninput="formatRibuan(this)"
                                                    wire:model.live="rincians.{{$index}}.besaran"
                                                    class="form-control border-start-0 text-end fw-medium">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" wire:model.live="rincians.{{$index}}.orang" min="0"
                                                class="form-control form-control-sm text-center">
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" wire:model.live="rincians.{{$index}}.bulan_kali"
                                                    min="0" class="form-control text-center">
                                                <span class="input-group-text bg-light text-muted px-1"
                                                    style="font-size: 10px;">Bln/Kali/Thn</span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-dark pe-3" style="font-size: 1.1em;">
                                            {{ number_format($item['jumlah'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light border-top">
                                <tr>
                                    <th colspan="4" class="text-end py-3">GRAND TOTAL PAGU:</th>
                                    <th class="text-end text-success fs-5 py-3 pe-3">
                                        {{ number_format($total_pagu, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@script
<script>
    window.formatRibuan = function (input) {
        let value = input.value.replace(/[^0-9]/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };
</script>
@endscript