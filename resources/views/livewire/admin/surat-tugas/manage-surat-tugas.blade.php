<div>
    <style>
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .form-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--bs-primary);
            border-bottom: 2px solid var(--bs-primary-bg-subtle);
            padding-bottom: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .bg-soft-primary { background-color: rgba(13, 110, 253, 0.05); }
        .bg-soft-info { background-color: rgba(13, 202, 240, 0.05); }
        .table-custom thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            color: #6c757d;
            border-top: none;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .badge-route {
            background: linear-gradient(45deg, var(--bs-primary), var(--bs-info));
            padding: 0.5em 1em;
            border-radius: 50px;
            font-weight: 500;
        }
        .modal-premium .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        .modal-premium .modal-header {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-bottom: 1px solid #eee;
        }
        .st-member-list-item {
            border-left: 4px solid var(--bs-primary);
            background: #fff;
            transition: transform 0.2s;
        }
        .st-member-list-item:hover {
            transform: translateX(5px);
        }
        /* Select2 Premium Overrides */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px !important;
            padding: 0.375rem 0.75rem !important;
            border: 1px solid #dee2e6 !important;
        }
    </style>

    <div class="row g-4">
        {{-- Left: Form Section --}}
        <div class="col-xl-4">
            <div class="card card-custom h-100">
                <div class="card-header bg-white pt-4 pb-0 px-4 border-0">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-pencil-square text-primary me-2"></i>
                        {{ $isEditMode ? 'Edit Surat Tugas' : 'Input Surat Tugas' }}
                    </h5>
                    <p class="text-muted small mt-1">Lengkapi informasi detail perjalanan dinas anggota.</p>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                        {{-- Administration Section --}}
                        <div class="form-section-title">
                            <i class="bi bi-info-circle me-1"></i> Administrasi
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nomor Surat Tugas</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control border-start-0 @error('no_surat_tugas') is-invalid @enderror" 
                                    wire:model="no_surat_tugas" placeholder="000/ST/2026">
                            </div>
                            @error('no_surat_tugas') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tanggal Ditetapkan</label>
                            <input type="date" class="form-control form-control-sm @error('tanggal_ditetapkan') is-invalid @enderror" 
                                wire:model="tanggal_ditetapkan">
                            @error('tanggal_ditetapkan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        {{-- Details Section --}}
                        <div class="form-section-title mt-4">
                            <i class="bi bi-geo-alt me-1"></i> Detail Perjalanan
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Uraian / Tugas</label>
                            <textarea class="form-control form-control-sm @error('uraian') is-invalid @enderror" 
                                wire:model="uraian" rows="3" placeholder="Deskripsikan tujuan atau kegiatan..."></textarea>
                            @error('uraian') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Asal</label>
                                <input type="text" class="form-control form-control-sm @error('tempat_asal') is-invalid @enderror" 
                                    wire:model="tempat_asal">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Tujuan</label>
                                <input type="text" class="form-control form-control-sm @error('tempat_tujuan') is-invalid @enderror" 
                                    wire:model="tempat_tujuan">
                            </div>
                        </div>

                        <div class="row g-2 mb-3 px-3 py-2 rounded bg-soft-primary border border-primary-subtle border-opacity-25">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Berangkat</label>
                                <input type="date" class="form-control form-control-sm" wire:model.live="tanggal_berangkat">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Kembali</label>
                                <input type="date" class="form-control form-control-sm" wire:model.live="tanggal_balik">
                            </div>
                            <div class="col-12 mt-2">
                                <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-primary-subtle">
                                    <span class="small fw-bold text-primary">Durasi:</span>
                                    <span class="badge bg-primary rounded-pill">{{ $lama_hari }} Hari</span>
                                </div>
                            </div>
                        </div>

                        {{-- Signatory Section --}}
                        <div class="form-section-title mt-4">
                            <i class="bi bi-person-check me-1"></i> Penandatangan
                        </div>
                        <div class="mb-4">
                            <select class="form-select form-select-sm @error('id_anggota_penandatangan') is-invalid @enderror" 
                                wire:model="id_anggota_penandatangan">
                                <option value="">-- Pilih Penandatangan --</option>
                                @foreach($penandatanganOptions as $opt)
                                    @if($opt->anggota)
                                        <option value="{{ $opt->id_anggota }}">{{ $opt->anggota->nama_anggota }} ({{ $opt->anggota->jabatan->nama ?? '-' }})</option>
                                    @elseif($opt->pegawaiAsn)
                                        <option value="{{ $opt->id_pegawai_asn }}">{{ $opt->pegawaiAsn->nama }} (ASN)</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('id_anggota_penandatangan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">
                                <i class="bi bi-save2-fill me-2"></i> {{ $isEditMode ? 'Perbarui Data' : 'Simpan Data' }}
                            </button>
                            @if($isEditMode)
                                <button type="button" wire:click="resetFields" class="btn btn-outline-secondary rounded-3 px-4">Batal</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: List Section --}}
        <div class="col-xl-8">
            <div class="card card-custom h-100">
                <div class="card-header bg-white pt-4 px-4 border-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-0">Daftar Surat Tugas</h5>
                        <p class="text-muted small mt-1 mb-0">Managemen dan pengelolaan data surat tugas anggota.</p>
                    </div>
                    <div class="ms-auto" style="min-width: 300px;">
                        <div class="input-group input-group-sm rounded-pill border bg-light overflow-hidden px-2 py-1">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control bg-transparent border-0 shadow-none" 
                                placeholder="Cari nomor surat atau uraian..." wire:model.live="search">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">No. Surat & Info</th>
                                    <th>Uraian / Kegiatan</th>
                                    <th>Tujuan & Waktu</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataSuratTugas as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $item->no_surat_tugas }}</div>
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Ditetapkan: {{ $item->tanggal_ditetapkan->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate small" style="max-width: 250px;" title="{{ $item->uraian }}">
                                            {{ $item->uraian }}
                                        </div>
                                        <div class="small text-primary fw-medium mt-1">
                                            <i class="bi bi-person-fill me-1"></i> {{ $item->penandatangan->nama_anggota ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge bg-light text-dark border border-secondary-subtle px-2 py-1 mb-1">
                                            {{ $item->tempat_asal }} <i class="bi bi-arrow-right mx-1"></i> {{ $item->tempat_tujuan }}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-clock-history me-1"></i>
                                            {{ $item->tanggal_berangkat->format('d M') }} - {{ $item->tanggal_balik->format('d M Y') }}
                                            <span class="fw-bold fs-xsmall ms-1">({{ $item->lama_hari }} Hari)</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('admin.surat-tugas.print', $item->id) }}" target="_blank" 
                                                class="btn btn-action btn-soft-info border-info-subtle text-info shadow-none" 
                                                title="Preview Surat Tugas">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <button wire:click="openManageAnggota({{ $item->id }})" 
                                                class="btn btn-action btn-soft-info border-info-subtle text-info shadow-none" 
                                                title="Kelola Anggota">
                                                <i class="bi bi-people-fill"></i>
                                            </button>
                                            <button wire:click="edit({{ $item->id }})" 
                                                class="btn btn-action btn-soft-primary border-primary-subtle text-primary shadow-none" 
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button onclick="confirmDeleteSuratTugas({{ $item->id }})" 
                                                class="btn btn-action btn-light border text-danger shadow-none" 
                                                title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-clipboard2-x fs-1 text-muted opacity-25"></i>
                                            <p class="text-muted mt-2">Belum ada data surat tugas yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3 px-4">
                    {{ $dataSuratTugas->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Manage Anggota - Premium Redesign --}}
    <div wire:ignore.self class="modal fade modal-premium" id="modalManageAnggota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header pt-4 px-4 pb-3">
                    <div>
                        <h5 class="fw-bold text-primary mb-0">
                            <i class="bi bi-people-fill me-2 fs-4"></i> Kelola Anggota Surat Tugas
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Daftarkan anggota yang bertugas untuk surat tugas ini.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        {{-- Assignment Form --}}
                        <div class="col-lg-5 p-4 bg-light border-end">
                            @if($selectedST)
                                <div class="card card-custom border bg-white mb-4">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 bg-soft-primary rounded-3 me-3">
                                                <i class="bi bi-file-earmark-text-fill text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted fw-bold">NO. SURAT TUGAS</div>
                                                <div class="fw-bold text-dark">{{ $selectedST->no_surat_tugas }}</div>
                                            </div>
                                        </div>
                                        <hr class="my-3 opacity-10">
                                        <div class="small">
                                            <span class="text-muted">Kegiatan:</span>
                                            <p class="mb-0 fw-medium">{{ $selectedST->uraian }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-custom border bg-white shadow-none">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                                            <span class="p-1 bg-primary rounded me-2"></span> Tambah Anggota
                                        </h6>
                                        
                                        <div class="mb-4">
                                            <label class="form-label small fw-bold text-muted">Pilih Nama Anggota</label>
                                            <div id="select2-container-anggota">
                                                <select class="form-select select2-premium" id="select2-anggota" data-placeholder="Ketik Nama Anggota..." wire:key="select2-anggota-{{ count($anggotaOptions) }}">
                                                    <option value=""></option>
                                                    @foreach($anggotaOptions as $ang)
                                                        <option value="{{ $ang->id }}">
                                                            {{ $ang->nama_anggota }} ({{ $ang->jabatan->nama ?? '-' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('st_anggota_id') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                                        </div>

                                        <button type="button" wire:click="addAnggota" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm fw-bold">
                                            <i class="bi bi-person-plus-fill me-2"></i> Tambahkan ke Daftar
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Final List --}}
                        <div class="col-lg-7 p-4 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Anggota Terdaftar ({{ count($currentMembers) }})</h6>
                                <span class="text-muted small italic">Klik ikon silang untuk menghapus</span>
                            </div>

                            <div class="row g-3" style="max-height: 450px; overflow-y: auto;">
                                @forelse($currentMembers as $member)
                                    <div class="col-md-6">
                                        <div class="st-member-list-item p-3 border rounded shadow-sm d-flex justify-content-between align-items-start h-100">
                                            <div>
                                                <div class="fw-bold text-dark small">{{ $member->anggota->nama_anggota }}</div>
                                                <div class="small text-muted mt-1">{{ $member->anggota->jabatan->nama ?? 'Anggota' }}</div>
                                            </div>
                                            <button wire:click="removeAnggota({{ $member->id }})" 
                                                class="btn btn-link text-danger p-0 border-0 shadow-none ms-2">
                                                <i class="bi bi-x-circle-fill shadow-sm rounded-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 py-5 text-center bg-light rounded-4 border border-dashed text-muted">
                                        <div class="py-3">
                                            <i class="bi bi-people fs-1 opacity-25"></i>
                                            <p class="mt-2 small">Belum ada anggota yang didaftarkan.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.confirmDeleteSuratTugas = function(id) {
        Swal.fire({
            title: 'Hapus Surat Tugas?',
            text: "Data ini akan dihapus secara permanen beserta data anggota yang terkait.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batalkan',
            customClass: {
                popup: 'rounded-4 border-0 shadow',
                confirmButton: 'rounded-pill px-4',
                cancelButton: 'rounded-pill px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteSuratTugas', id);
            }
        });
    };

    window.addEventListener('swal', event => {
        const detail = event.detail[0] || event.detail;
        Swal.fire({
            title: detail.title,
            text: detail.text,
            icon: detail.icon,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        initSelect2();
        
        window.addEventListener('open-modal-manage-anggota', () => {
            $('#modalManageAnggota').modal('show');
            setTimeout(() => {
                initSelect2();
            }, 250);
        });

        window.addEventListener('refresh-select2', () => {
            if ($('#select2-anggota').data('select2')) {
                $('#select2-anggota').select2('destroy');
            }
            setTimeout(() => {
                initSelect2();
            }, 50);
        });
    });

    function initSelect2() {
        $('#select2-anggota').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalManageAnggota'),
            width: '100%',
            allowClear: true
        }).on('change', function (e) {
            @this.set('st_anggota_id', e.target.value);
        });
    }

    // Handle Livewire DOM updates
    document.addEventListener('livewire:navigated', () => {
        initSelect2();
    });
</script>
@endpush

