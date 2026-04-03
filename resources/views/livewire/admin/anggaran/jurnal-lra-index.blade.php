<div>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="ti ti-history text-success me-2"></i> Jurnal Realisasi Anggaran (LRA)</h4>
                        <p class="text-muted small mb-0">Riwayat pemotongan anggaran berdasarkan realisasi gaji bulanan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="small text-muted fw-bold mb-1">Cari Keterangan / Item</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0 px-3"><i class="ti ti-search small"></i></span>
                                <input type="text" class="form-control form-control-sm border-0 bg-light rounded-end-3" placeholder="Contoh: Gaji Pokok..." wire:model.live="search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted fw-bold mb-1">Bulan</label>
                            <select class="form-select form-select-sm border-0 bg-light rounded-3" wire:model.live="filterBulan">
                                <option value="">Semua Bulan</option>
                                @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                                <option value="THR">THR</option>
                                <option value="G13">Gaji 13</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted fw-bold mb-1">Tahun Anggaran</label>
                            <select class="form-select form-select-sm border-0 bg-light rounded-3" wire:model.live="filterTahun">
                                <option value="">Semua Tahun</option>
                                @for($y=date('Y'); $y>=2024; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="dropdown w-100">
                                <button class="btn btn-primary btn-sm w-100 rounded-pill dropdown-toggle {{ !$filterTahun ? 'disabled' : '' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-printer me-1"></i> Cetak
                                </button>
                                <ul class="dropdown-menu shadow border-0 rounded-3">
                                    <li>
                                        <a class="dropdown-menu-item dropdown-item small py-2" href="{{ route('admin.jurnal-lra.print-bku', ['bulan' => $filterBulan, 'tahun' => $filterTahun]) }}" target="_blank">
                                            <i class="ti ti-receipt-2 me-2 text-primary"></i> Cetak BKU
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-menu-item dropdown-item small py-2" href="{{ route('admin.jurnal-lra.print-realisasi', ['bulan' => $filterBulan, 'tahun' => $filterTahun]) }}" target="_blank">
                                            <i class="ti ti-chart-bar me-2 text-success"></i> Cetak Realisasi
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive rounded-4 border p-2 mb-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small py-3">Tanggal Ops</th>
                                    <th class="small py-3">Periode</th>
                                    <th class="small py-3">Item Anggaran</th>
                                    <th class="small py-3">Keterangan</th>
                                    <th class="small py-3 text-end">Debet (-)</th>
                                    <th class="small py-3 text-end">Kredit (+)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($journals as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $item->bln_thn }}</span></td>
                                    <td><span class="text-uppercase small fw-bold">{{ str_replace('_', ' ', $item->item_anggaran) }}</span></td>
                                    <td class="small">{{ $item->keterangan }}</td>
                                    <td class="text-end text-danger fw-medium">
                                        {{ $item->debet > 0 ? 'Rp ' . number_format($item->debet, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-end text-success fw-medium">
                                        {{ $item->kredit > 0 ? 'Rp ' . number_format($item->kredit, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted small">Tidak ada data riwayat realisasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $journals->links() }}
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
</style>
@endpush
