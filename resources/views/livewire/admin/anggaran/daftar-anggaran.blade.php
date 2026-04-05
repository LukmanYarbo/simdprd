<div>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="ti ti-report-money text-primary me-2"></i> Master Anggaran</h4>
                        <p class="text-muted small mb-0">Kelola pagu anggaran tahunan untuk realisasi gaji anggota DPRD.</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.anggaran.form') }}" wire:navigate class="btn btn-primary rounded-pill px-4 shadow-sm transition-base">
                            <i class="ti ti-plus me-1"></i> Tambah Anggaran
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="table-responsive rounded-4 shadow-sm border p-3 bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small py-3">Tahun Anggaran</th>
                            <th class="small py-3 text-end">Total Pagu</th>
                            <th class="small py-3 text-end">Realisasi</th>
                            <th class="small py-3 text-end">Sisa Anggaran</th>
                            <th class="small py-3 text-center">Status</th>
                            <th class="small py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggarans as $item)
                        <tr>
                            <td class="fw-bold text-primary">{{ $item->tahun_anggaran }}</td>
                            <td class="text-end">Rp {{ number_format($item->total_pagu, 0, ',', '.') }}</td>
                            @php
                                $realisasi = ($item->total_debet ?? 0) - ($item->total_kredit ?? 0);
                                $sisa = $item->total_pagu - $realisasi;
                            @endphp
                            <td class="text-end text-danger fw-medium">Rp {{ number_format($realisasi, 0, ',', '.') }}</td>
                            <td class="text-end text-success fw-bold">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->status == 'FINAL')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2">Final</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-2">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button wire:click="showDetail({{ $item->id }})" class="btn btn-icon-only btn-sm btn-outline-success" title="Detail Anggaran"><i class="ti ti-eye"></i></button>
                                    <button wire:click="showHistory({{ $item->id }})" class="btn btn-icon-only btn-sm btn-outline-info" title="Riwayat Perubahan"><i class="ti ti-history"></i></button>
                                    @if($item->status == 'FINAL')
                                    <button wire:click="openStatusModal({{ $item->id }})" class="btn btn-icon-only btn-sm btn-outline-warning" title="Buka Kunci (Set Draft)"><i class="ti ti-lock-open"></i></button>
                                    <button class="btn btn-icon-only btn-sm btn-outline-secondary" title="Tidak dapat diedit (FINAL)" disabled><i class="ti ti-pencil"></i></button>
                                    <button class="btn btn-icon-only btn-sm btn-outline-secondary" title="Tidak dapat dihapus (FINAL)" disabled><i class="ti ti-trash"></i></button>
                                    @else
                                    <a href="{{ route('admin.anggaran.form', $item->id) }}" wire:navigate class="btn btn-icon-only btn-sm btn-outline-primary" title="Edit"><i class="ti ti-pencil"></i></a>
                                    <button onclick="confirmDelete({{ $item->id }})" class="btn btn-icon-only btn-sm btn-outline-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted small">Belum ada data anggaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Anggaran -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-list-details text-success me-2"></i> Rincian Pagu Anggaran Tahun {{ $detailData ? $detailData->tahun_anggaran : '' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @if($detailData)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 m-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small py-3 px-4">Item Uraian</th>
                                    <th class="small py-3 text-end">Total Pagu (Rp)</th>
                                    <th class="small py-3 text-end">Realisasi (Rp)</th>
                                    <th class="small py-3 text-end pe-4">Sisa Pagu (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detailData->rincians as $rincian)
                                <tr>
                                    <td class="small px-4 fw-bold">{{ $rincian->uraian }}</td>
                                    <td class="small text-end fw-bold text-dark">{{ number_format($rincian->jumlah, 0, ',', '.') }}</td>
                                    <td class="small text-end fw-bold text-danger">{{ number_format($rincian->jumlah - $rincian->sisa_pagu, 0, ',', '.') }}</td>
                                    <td class="small text-end pe-4 fw-bold {{ $rincian->sisa_pagu < ($rincian->jumlah * 0.2) ? 'text-danger' : 'text-primary' }}">
                                        {{ number_format($rincian->sisa_pagu, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th class="text-end py-3">GRAND TOTAL</th>
                                    <th class="text-end text-success fs-5 py-3">{{ number_format($detailData->total_pagu, 0, ',', '.') }}</th>
                                    <th class="text-end text-danger fs-5 py-3">{{ number_format($detailData->total_pagu - $detailData->rincians->sum('sisa_pagu'), 0, ',', '.') }}</th>
                                    <th class="text-end text-primary fs-5 py-3 pe-4">{{ number_format($detailData->rincians->sum('sisa_pagu'), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-top border-light bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Unlock Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-lock-open text-warning me-2"></i> Buka Kunci Anggaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning mb-4 rounded-3 border-0 small">
                        <i class="ti ti-info-circle me-1"></i> Data anggaran yang berstatus <strong>FINAL</strong> akan diubah kembali menjadi <strong>DRAFT</strong> agar bisa diedit.<br>Harap masukkan alasan perubahan ini untuk riwayat.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Alasan Perubahan Status</label>
                        <textarea class="form-control rounded-3 @error('alasan_perubahan') is-invalid @enderror" wire:model="alasan_perubahan" rows="3" placeholder="Contoh: Mengubah nominal tunjangan komunikasi..."></textarea>
                        @error('alasan_perubahan') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer border-top border-light bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning btn-sm rounded-pill px-4" wire:click="updateStatus" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="updateStatus"><i class="ti ti-check me-1"></i> Simpan & Buka Kunci</span>
                        <span wire:loading wire:target="updateStatus"><i class="ti ti-loader spin me-1"></i> Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal History -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-history text-info me-2"></i> Riwayat Perubahan Anggaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 m-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small py-3 px-4">Waktu</th>
                                    <th class="small py-3">Pengguna</th>
                                    <th class="small py-3">Perubahan Status</th>
                                    <th class="small py-3 pe-4">Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historyData as $history)
                                <tr>
                                    <td class="small px-4">{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="small">{{ $history->user->name ?? 'Sistem' }}</td>
                                    <td class="small">
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $history->status_sebelumnya }}</span> 
                                        <i class="ti ti-arrow-right mx-1"></i> 
                                        <span class="badge bg-warning-subtle text-warning">{{ $history->status_baru }}</span>
                                    </td>
                                    <td class="small pe-4">{{ $history->alasan_perubahan }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted small">Belum ada riwayat perubahan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-top border-light bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Tutup</button>
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

@script
<script>
    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Hapus Anggaran?',
            text: "Data anggaran tahun ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.call('deleteAnggaran', id);
            }
        });
    };

    let statusModalInstance;
    let historyModalInstance;
    let detailModalInstance;

    $wire.on('show-status-modal', () => {
        if (!statusModalInstance) {
            statusModalInstance = new bootstrap.Modal(document.getElementById('statusModal'));
        }
        statusModalInstance.show();
    });

    $wire.on('hide-status-modal', () => {
        if (statusModalInstance) {
            statusModalInstance.hide();
        }
    });

    $wire.on('show-history-modal', () => {
        if (!historyModalInstance) {
            historyModalInstance = new bootstrap.Modal(document.getElementById('historyModal'));
        }
        historyModalInstance.show();
    });

    $wire.on('show-detail-modal', () => {
        if (!detailModalInstance) {
            detailModalInstance = new bootstrap.Modal(document.getElementById('detailModal'));
        }
        detailModalInstance.show();
    });
</script>
@endscript
