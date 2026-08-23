<div>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="ti ti-file-analytics text-primary me-2"></i> Kertas Kerja Anggaran</h4>
                        <p class="text-muted small mb-0">Kelola dan susun kertas kerja prediksi pagu per tahun.</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.kertas-kerja.form') }}" class="btn btn-primary rounded-pill px-4 shadow-sm transition-base">
                            <i class="ti ti-plus me-1"></i> Tambah Kertas Kerja
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="py-3 px-4 fw-bold text-muted small" style="width: 15%">Tahun Anggaran</th>
                                    <th class="py-3 fw-bold text-muted small" style="width: 25%">Total Pagu Estimasi</th>
                                    <th class="py-3 fw-bold text-muted small" style="width: 15%">Item Rincian</th>
                                    <th class="py-3 fw-bold text-muted small">Status</th>
                                    <th class="py-3 px-4 fw-bold text-muted small text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kertasKerjas as $kk)
                                    <tr class="border-bottom">
                                        <td class="px-4">
                                            <span class="fs-5 fw-bold text-dark">{{ $kk->tahun_anggaran }}</span>
                                        </td>
                                        <td>
                                            <span class="fs-6 fw-bold text-success">Rp {{ number_format($kk->total_pagu, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ count($kk->rincians) }} Rincian</span>
                                        </td>
                                        <td>
                                            @if($kk->status === 'FINAL')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                    <i class="ti ti-check me-1"></i> FINAL
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                                    <i class="ti ti-edit me-1"></i> DRAFT
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 text-end">
                                            <div class="btn-group shadow-sm rounded-pill">
                                                <button wire:click="showDetail({{ $kk->id }})" class="btn btn-sm btn-light border" title="Lihat Rincian">
                                                    <i class="ti ti-eye text-primary"></i>
                                                </button>
                                                <a href="{{ route('admin.kertas-kerja.print', $kk->id) }}" target="_blank" class="btn btn-sm btn-light border" title="Cetak Kertas Kerja">
                                                    <i class="ti ti-printer text-info"></i>
                                                </a>
                                                @if($kk->status === 'FINAL')
                                                    <button type="button" 
                                                        onclick="confirmPlot({{ $kk->id }}, {{ $kk->tahun_anggaran }})"
                                                        class="btn btn-sm btn-light border" title="Plot ke Master Anggaran">
                                                        <i class="ti ti-chart-arrows text-success"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.kertas-kerja.form', $kk->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                                    <i class="ti ti-edit text-warning"></i>
                                                </a>
                                                @php
                                                    $isProtected = \App\Livewire\Admin\KertasKerja\DaftarKertasKerja::isTerplotDanFinal((int) $kk->tahun_anggaran, $kk->status);
                                                @endphp
                                                @if($isProtected)
                                                    <button type="button" class="btn btn-sm btn-light border" disabled
                                                        title="Tidak dapat dihapus: berstatus FINAL & sudah ter-plot ke Master Anggaran">
                                                        <i class="ti ti-lock text-secondary"></i>
                                                    </button>
                                                @else
                                                    <button onclick="confirmDelete({{ $kk->id }})" class="btn btn-sm btn-light border" title="Hapus">
                                                        <i class="ti ti-trash text-danger"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="bg-light rounded-circle p-3 mb-3">
                                                    <i class="ti ti-folder-off text-muted fs-1"></i>
                                                </div>
                                                <h6 class="text-muted fw-bold">Belum Ada Kertas Kerja</h6>
                                                <p class="text-muted small mb-0">Klik tombol Tambah Kertas Kerja untuk mulai menyusun.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Kertas Kerja -->
    <div wire:ignore.self class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="detailModalLabel">
                        <div class="bg-white p-2 rounded shadow-sm me-3">
                            <i class="ti ti-file-analytics text-primary"></i>
                        </div>
                        Rincian Kertas Kerja Tahun {{ $detailData?->tahun_anggaran ?? '-' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @if($detailData)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="small py-3 px-4">Kategori Jns</th>
                                        <th class="small py-3">Uraian / Jabatan</th>
                                        <th class="small py-3 text-end">Besaran (Rp)</th>
                                        <th class="small py-3 text-center">Orang</th>
                                        <th class="small py-3 text-center">Bulan/Kali</th>
                                        <th class="small py-3 text-end pe-4">Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $currentCategory = ''; @endphp
                                    @foreach($detailData->rincians->sortBy('id') as $rincian)
                                        @if($currentCategory !== $rincian->kategori)
                                            <tr class="table-light">
                                                <td colspan="6" class="fw-bold px-4 text-primary bg-secondary bg-opacity-10 py-2">
                                                    {{ $rincian->kategori }}
                                                </td>
                                            </tr>
                                            @php $currentCategory = $rincian->kategori; @endphp
                                        @endif
                                    <tr>
                                        <td class="small px-4 text-muted"><i class="ti ti-point-filled me-1"></i> {{ $rincian->kategori }}</td>
                                        <td class="small fw-bold">{{ $rincian->uraian }}</td>
                                        <td class="small text-end">{{ number_format($rincian->besaran, 0, ',', '.') }}</td>
                                        <td class="small text-center">{{ $rincian->orang }}</td>
                                        <td class="small text-center">{{ $rincian->bulan_kali }}</td>
                                        <td class="small text-end pe-4 fw-bold text-dark">{{ number_format($rincian->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5" class="text-end py-3">TOTAL PAGU ESTIMASI</th>
                                        <th class="text-end text-success fs-5 py-3 pe-4">{{ number_format($detailData->total_pagu, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .transition-base { transition: all 0.3s ease; }
    .transition-base:hover { transform: translateY(-2px); }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-detail-modal', () => {
            let detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            detailModal.show();
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Kertas Kerja?',
            text: "Data estimasi anggaran ini akan terhapus permanen beserta seluruh rincian jabatannya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteKertasKerja', { id: id });
            }
        });
    }

    function confirmPlot(id, tahun) {
        Swal.fire({
            title: 'Plot ke Master Anggaran?',
            text: "Apakah Anda yakin ingin mem-plot Kertas Kerja Tahun " + tahun + " ke Master Anggaran? Data anggaran tahun tersebut yang sudah ada akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Plot!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('plotToAnggaran', { id: id });
            }
        });
    }
</script>
@endpush
