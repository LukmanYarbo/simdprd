<div class="p-0">
    {{-- Header Decoration --}}
    <div class="position-relative overflow-hidden bg-primary text-white rounded-bottom-4 mb-4 shadow-sm" style="height: 200px;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.9) 0%, rgba(13, 202, 240, 0.9) 100%);"></div>
        
        {{-- Animated Background Shapes --}}
        <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 300px; height: 300px; top: -100px; right: -50px;"></div>
        <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 200px; height: 200px; bottom: -50px; left: 10%;"></div>

        <div class="position-relative container-fluid h-100 d-flex flex-column justify-content-center px-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="fw-bold mb-1"><i class="bi bi-person-badge-fill me-2"></i>Perubahan Status Anggota</h2>
                    <p class="lead mb-0 opacity-75">Kelola riwayat dan transisi status keanggotaan secara profesional.</p>
                </div>
                <div class="col-md-4">
                    <div class="glass-search p-1 rounded-pill">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent text-white opacity-75">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 bg-transparent text-white placeholder-white" placeholder="Cari Nama atau NIK anggota...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="row g-4">
            {{-- Main Table Section --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold border-start border-primary border-4 ps-3">Daftar Anggota DPRD</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $anggota->total() }} Total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-0">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Profil Anggota</th>
                                        <th class="text-uppercase small fw-bold text-muted text-center">Jabatan</th>
                                        <th class="text-uppercase small fw-bold text-muted text-center">Status</th>
                                        <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-muted">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota as $item)
                                    <tr wire:key="row-{{ $item->id }}" class="row-hover-effect">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-container me-3 shadow-sm rounded-circle p-1 bg-white">
                                                    @if($item->foto_anggota)
                                                        <img src="{{ asset('storage/' . $item->foto_anggota) }}" class="rounded-circle" width="45" height="45" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                                            {{ substr($item->nama_anggota, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $item->nama_anggota }}</div>
                                                    <div class="text-muted small"><i class="bi bi-credit-card-2-front me-1"></i>{{ $item->nik }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="small fw-medium text-secondary">{{ $item->jabatan->nama ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusColor = match($item->statusKeanggotaan->id ?? 0) {
                                                    1 => 'bg-success',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusColor }} bg-opacity-10 text-{{ str_replace('bg-','',$statusColor) }} border border-{{ str_replace('bg-','',$statusColor) }} border-opacity-25 px-3 py-2 rounded-pill shadow-xs">
                                                {{ $item->statusKeanggotaan->nama }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" wire:click="openModal({{ $item->id }})" class="btn btn-white btn-sm border shadow-sm px-3 rounded-pill btn-hover-primary">
                                                <i class="bi bi-gear-wide-connected me-1"></i> Transisi Status
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                            <p class="text-muted">Tidak ada data anggota ditemukan.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($anggota->hasPages())
                    <div class="card-footer bg-white border-top-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $anggota->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- History / Audit Trail Sidebar Section --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-warning"></i>Riwayat Terbaru</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline">
                            @forelse($history as $h)
                            <div class="timeline-item pb-4 position-relative">
                                @if(!$loop->last)
                                <div class="timeline-line position-absolute h-100 border-start border-2 opacity-10 ms-2" style="top: 25px;"></div>
                                @endif
                                <div class="d-flex align-items-start position-relative">
                                    <div class="timeline-dot rounded-circle bg-primary shadow-sm border border-white border-3 position-relative z-1" style="width: 18px; height: 18px; margin-top: 5px;"></div>
                                    <div class="ms-3 flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold text-dark small">{{ $h->anggota->nama_anggota }}</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $h->tgl_perubahan->format('d/m/Y') }}</small>
                                        </div>
                                        <div class="bg-light rounded-3 p-2 border border-secondary border-opacity-10">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-arrow-right-circle text-primary small"></i>
                                                <span class="badge bg-white text-dark border small shadow-xs">{{ $h->statusKeanggotaan->nama }}</span>
                                            </div>
                                            @if($h->no_sk)
                                            <small class="d-block text-muted" style="font-size: 0.7rem;"><i class="bi bi-file-earmark-text me-1"></i>SK: {{ $h->no_sk }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 text-muted small">
                                <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat aktivitas.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Styling --}}
    @if($isModalOpen)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden animate__animated animate__zoomIn animate__faster">
                <div class="modal-header border-0 bg-primary text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Kelola Transisi Status</h5>
                            <small class="opacity-75">Perbarui data keanggotaan DPRD</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>
                
                @if($selectedMember)
                <div class="modal-member-preview bg-light p-3 border-bottom d-flex align-items-center">
                    <img src="{{ $selectedMember->foto_anggota ? asset('storage/'.$selectedMember->foto_anggota) : 'https://ui-avatars.com/api/?name='.urlencode($selectedMember->nama_anggota).'&background=random' }}" class="rounded-circle me-3 shadow-sm border border-white border-2" width="50" height="50">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $selectedMember->nama_anggota }}</h6>
                        <small class="text-muted"><span class="badge bg-secondary-subtle text-secondary rounded-pill fw-normal px-2">Status Saat Ini: {{ $selectedMember->statusKeanggotaan->nama }}</span></small>
                    </div>
                </div>
                @endif

                <form wire:submit.prevent="saveStatusChange">
                    <div class="modal-body p-4 bg-white">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Status Keanggotaan Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-check2-circle"></i></span>
                                    <select wire:model="id_status_keanggotaan" class="form-select bg-light border-start-0 @error('id_status_keanggotaan') is-invalid @enderror">
                                        <option value="">Pilih Status Baru...</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('id_status_keanggotaan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Tanggal Perubahan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" wire:model="tgl_perubahan" class="form-control bg-light border-start-0 @error('tgl_perubahan') is-invalid @enderror">
                                </div>
                                @error('tgl_perubahan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Referensi Nomor SK</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash"></i></span>
                                    <input type="text" wire:model="no_sk" class="form-control bg-light border-start-0 @error('no_sk') is-invalid @enderror" placeholder="Isi jika ada SK terkait">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Catatan / Alasan</label>
                                <textarea wire:model="alasan" class="form-control bg-light @error('alasan') is-invalid @enderror" rows="2" placeholder="Catatan tambahan perubahan status..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Lampiran SK (Opsional)</label>
                                <div class="upload-zone rounded-3 p-2 bg-light border border-dashed text-center position-relative">
                                    <input type="file" wire:model="file_sk" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" style="z-index: 2;">
                                    <div class="d-flex align-items-center justify-content-center py-1">
                                        <i class="bi bi-cloud-arrow-up fs-4 text-primary me-2"></i>
                                        <span class="small text-muted">{{ $file_sk ? $file_sk->getClientOriginalName() : 'Klik atau seret file ke sini' }}</span>
                                    </div>
                                    <div wire:loading wire:target="file_sk" class="position-absolute top-50 start-50 translate-middle bg-light w-100 h-100 d-flex align-items-center justify-content-center rounded-3">
                                        <div class="spinner-border spinner-border-sm text-primary me-2"></div><span class="small fw-bold">Mengunggah...</span>
                                    </div>
                                </div>
                                @error('file_sk') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-1 bg-white justify-content-between">
                        <button type="button" class="btn btn-light px-4 rounded-pill" wire:click="closeModal">Kembali</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-lg shadow-primary-subtle">
                            <i class="bi bi-send-fill me-1"></i> Perbarui Status Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Additional Styles --}}
    <style>
        .rounded-bottom-4 { border-bottom-left-radius: 2rem !important; border-bottom-right-radius: 2rem !important; }
        .glass-search { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.3s ease; }
        .glass-search:focus-within { background: rgba(255, 255, 255, 0.3); box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .placeholder-white::placeholder { color: rgba(255, 255, 255, 0.7) !important; }
        .row-hover-effect { transition: all 0.2s ease; }
        .row-hover-effect:hover { background-color: rgba(13, 110, 253, 0.02) !important; transform: translateX(5px); }
        .btn-hover-primary:hover { background-color: var(--bs-primary) !important; color: white !important; border-color: var(--bs-primary) !important; }
        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .cursor-pointer { cursor: pointer; }
        .border-dashed { border-style: dashed !important; }
        .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        
        /* Timeline styling */
        .timeline-dot { margin-left: -9px; }
        .timeline-item { position: relative; }
        
        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate__zoomIn { animation: zoomIn 0.3s ease-out; }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</div>
