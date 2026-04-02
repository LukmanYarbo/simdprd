<div>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="ti ti-report-money text-primary me-2"></i> Master Anggaran</h4>
                        <p class="text-muted small mb-0">Kelola pagu anggaran tahunan untuk realisasi gaji anggota DPRD.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="ti ti-{{ $isEditMode ? 'edit' : 'plus' }} me-1"></i>
                        {{ $isEditMode ? 'Edit' : 'Tambah' }} Anggaran
                    </h6>
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Tahun Anggaran</label>
                            <input type="number" class="form-control form-control-sm rounded-3 @error('tahun_anggaran') is-invalid @enderror" wire:model="tahun_anggaran" placeholder="Contoh: 2026">
                            @error('tahun_anggaran') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="accordion accordion-flush mb-3" id="formAnggaran">
                            <!-- Group 1: Dasar -->
                            <div class="accordion-item bg-transparent">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed px-0 py-2 small fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#groupDasar">
                                        Komponen Dasar
                                    </button>
                                </h2>
                                <div id="groupDasar" class="accordion-collapse collapse" data-bs-parent="#formAnggaran">
                                    <div class="accordion-body px-0 pt-2 pb-0">
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Gaji Pokok / Uang Representasi</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="gaji_pokok">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan Keluarga</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_keluarga">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan Jabatan</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_jabatan">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan Beras</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_beras">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan PPh</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_pph">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Pembulatan</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="pembulatan">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Uang Paket</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="uang_paket">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Group 2: AKD -->
                            <div class="accordion-item bg-transparent">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed px-0 py-2 small fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#groupAKD">
                                        Alat Kelengkapan
                                    </button>
                                </h2>
                                <div id="groupAKD" class="accordion-collapse collapse" data-bs-parent="#formAnggaran">
                                    <div class="accordion-body px-0 pt-2 pb-0">
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunj. Alat Kelengkapan</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_alat_kelengkapan">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunj. AK Lainnya (Pansus/Panja)</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_alat_kelengkapan_lainnya">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Group 3: Fasilitas -->
                            <div class="accordion-item bg-transparent">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed px-0 py-2 small fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#groupFasilitas">
                                        Fasilitas & Reses
                                    </button>
                                </h2>
                                <div id="groupFasilitas" class="accordion-collapse collapse" data-bs-parent="#formAnggaran">
                                    <div class="accordion-body px-0 pt-2 pb-0">
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan Perumahan</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_perumahan">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan Transportasi</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_transportasi">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Tunjangan Reses</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_reses">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Group 4: Lainnya -->
                            <div class="accordion-item bg-transparent">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed px-0 py-2 small fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#groupLainnya">
                                        Lain-lain
                                    </button>
                                </h2>
                                <div id="groupLainnya" class="accordion-collapse collapse" data-bs-parent="#formAnggaran">
                                    <div class="accordion-body px-0 pt-2 pb-0">
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">JKK / JKM</label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="jkk" placeholder="JKK">
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="jkm" placeholder="JKM">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Komunikasi Insentif (TKI)</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="tunjangan_komunikasi_insentif">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Uang Jasa Pengabdian</label>
                                            <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3" wire:model="uang_jasa_pengabdian">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Status</label>
                            <select class="form-select form-select-sm rounded-3" wire:model="status">
                                <option value="DRAFT">Draft</option>
                                <option value="FINAL">Final / Aktif</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill py-2 shadow-sm transition-base">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Anggaran
                            </button>
                            @if($isEditMode)
                            <button type="button" class="btn btn-light btn-sm rounded- pill py-2" wire:click="resetFields">Batal</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="table-responsive rounded-4 shadow-sm border p-3 bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small py-3">Tahun</th>
                            <th class="small py-3">Pagu Gaji Pokok</th>
                            <th class="small py-3">Pagu Lainnya (Sum)</th>
                            <th class="small py-3">Status</th>
                            <th class="small py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggarans as $item)
                        <tr>
                            <td class="fw-bold text-primary">{{ $item->tahun_anggaran }}</td>
                            <td>Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="small">
                                <span class="text-muted">Total: Rp 
                                {{ number_format(
                                    $item->tunjangan_keluarga + $item->tunjangan_jabatan + $item->tunjangan_beras + 
                                    $item->tunjangan_pph + $item->pembulatan + $item->uang_paket + 
                                    $item->tunjangan_alat_kelengkapan + $item->tunjangan_alat_kelengkapan_lainnya + 
                                    $item->tunjangan_perumahan + $item->uang_jasa_pengabdian + 
                                    $item->tunjangan_reses + $item->tunjangan_transportasi + 
                                    $item->jkk + $item->jkm + $item->tunjangan_komunikasi_insentif, 0, ',', '.') 
                                }}
                                </span>
                            </td>
                            <td>
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
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-icon-only btn-sm btn-outline-primary" title="Edit"><i class="ti ti-pencil"></i></button>
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-report-money text-success me-2"></i> Rincian Pagu Anggaran {{ $detailData ? $detailData->tahun_anggaran : '' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @if($detailData)
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-primary"><i class="ti ti-id me-1"></i> Komponen Dasar</h6>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Gaji Pokok / Uang Rep.</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->gaji_pokok, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunjangan Keluarga</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_keluarga, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunjangan Jabatan</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_jabatan, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunjangan Beras</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_beras, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunjangan PPh</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_pph, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 border-0">
                                    <span>Uang Paket & Pembulatan</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->uang_paket + $detailData->pembulatan, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-primary"><i class="ti ti-building me-1"></i> Alat Kelengkapan & Reses</h6>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunj. Alat Kelengkapan</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_alat_kelengkapan, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunj. AKD Lainnya</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_alat_kelengkapan_lainnya, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 border-0">
                                    <span>Tunjangan Reses</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_reses, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-primary"><i class="ti ti-home me-1"></i> Fasilitas Perumahan & Transp.</h6>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Tunjangan Perumahan</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_perumahan, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 border-0">
                                    <span>Tunjangan Transportasi</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_transportasi, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-primary"><i class="ti ti-settings me-1"></i> Tunjangan Lainnya</h6>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>JKK & JKM (Asuransi)</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->jkk + $detailData->jkm, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>Komunikasi Insentif (TKI)</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->tunjangan_komunikasi_insentif, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 border-0">
                                    <span>Uang Jasa Pengabdian</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($detailData->uang_jasa_pengabdian, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold">TOTAL PAGU TAHUNAN</span>
                        <span class="h5 fw-bold text-success mb-0">Rp {{ number_format(
                                $detailData->gaji_pokok + $detailData->tunjangan_keluarga + $detailData->tunjangan_jabatan + $detailData->tunjangan_beras + 
                                $detailData->tunjangan_pph + $detailData->pembulatan + $detailData->uang_paket + 
                                $detailData->tunjangan_alat_kelengkapan + $detailData->tunjangan_alat_kelengkapan_lainnya + 
                                $detailData->tunjangan_perumahan + $detailData->uang_jasa_pengabdian + 
                                $detailData->tunjangan_reses + $detailData->tunjangan_transportasi + 
                                $detailData->jkk + $detailData->jkm + $detailData->tunjangan_komunikasi_insentif, 0, ',', '.') 
                            }}</span>
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
    .accordion-button:not(.collapsed) { background-color: transparent; color: inherit; box-shadow: none; }
</style>
@endpush

@script
<script>
    window.formatRibuan = function(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

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
