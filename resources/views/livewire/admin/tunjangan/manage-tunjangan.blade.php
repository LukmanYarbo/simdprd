<div>
    <div class="card shadow mb-4 border-0 rounded-4">
        <div class="card-header py-3 border-bottom-0 pt-4 pb-0 px-4">
            <ul class="nav nav-pills nav-fill gap-2 p-1 small bg-body-tertiary rounded-5 shadow-sm" id="tunjanganTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5 py-2 {{ $activeTab === 'umum' ? 'active fw-bold shadow-sm' : 'text-body' }}" wire:click="switchTab('umum')" type="button">
                        <i class="bi bi-wallet2 me-1"></i> Umum
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5 py-2 {{ $activeTab === 'transportasi' ? 'active fw-bold shadow-sm' : 'text-body' }}" wire:click="switchTab('transportasi')" type="button">
                        <i class="bi bi-car-front-fill me-1"></i> Transportasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5 py-2 {{ $activeTab === 'perumahan' ? 'active fw-bold shadow-sm' : 'text-body' }}" wire:click="switchTab('perumahan')" type="button">
                        <i class="bi bi-house-door-fill me-1"></i> Perumahan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5 py-2 {{ $activeTab === 'komunikasi_intensif' ? 'active fw-bold shadow-sm' : 'text-body' }}" wire:click="switchTab('komunikasi_intensif')" type="button">
                        <i class="bi bi-telephone-fill me-1"></i> Komunikasi Intensif
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="tunjanganTabsContent">
                
                {{-- TAB: UMUM --}}
                @if($activeTab === 'umum')
                <div class="row fade-in">
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm bg-body-tertiary rounded-4">
                            <div class="card-body p-4">
                                <h6 class="font-weight-bold text-primary mb-3">{{ $isEditMode ? 'Edit' : 'Tambah' }} Tunjangan Umum</h6>
                                <form wire:submit.prevent="{{ $isEditMode ? 'updateUmum' : 'storeUmum' }}">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Tunjangan Beras (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tu_tunjangan_beras') is-invalid @enderror" wire:model="tu_tunjangan_beras">
                                        @error('tu_tunjangan_beras') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Jumlah Beras (Kg)</label>
                                        <input type="number" class="form-control form-control-sm rounded-3 @error('tu_jumlah_beras') is-invalid @enderror" wire:model="tu_jumlah_beras">
                                        @error('tu_jumlah_beras') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-bold">Tunj. Anak (%)</label>
                                            <input type="number" class="form-control form-control-sm rounded-3 @error('tu_tunjangan_anak_persen') is-invalid @enderror" wire:model="tu_tunjangan_anak_persen">
                                            @error('tu_tunjangan_anak_persen') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-bold">Tunj. Istri (%)</label>
                                            <input type="number" class="form-control form-control-sm rounded-3 @error('tu_tunjangan_istri_persen') is-invalid @enderror" wire:model="tu_tunjangan_istri_persen">
                                            @error('tu_tunjangan_istri_persen') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">Status</label>
                                        <select class="form-select form-select-sm rounded-3 @error('tu_status') is-invalid @enderror" wire:model="tu_status">
                                            <option value="Y">Aktif</option>
                                            <option value="T">Tidak Aktif</option>
                                        </select>
                                        @error('tu_status') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-3">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
                                        @if($isEditMode)
                                        <button type="button" class="btn btn-light btn-sm rounded-3" wire:click="resetForms">Batal</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="table-responsive rounded-4 shadow-sm border p-3 bg-body-tertiary">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small text-muted py-3">Tunj. Beras</th>
                                        <th class="small text-muted py-3">Jml Beras</th>
                                        <th class="small text-muted py-3">Anak / Istri</th>
                                        <th class="small text-muted py-3">Status</th>
                                        <th class="small text-muted py-3 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($dataUmum as $item)
                                    <tr>
                                        <td class="fw-medium">Rp {{ number_format($item->tunjangan_beras, 0, ',', '.') }}</td>
                                        <td>{{ $item->jumlah_beras }} Kg</td>
                                        <td>{{ $item->tunjangan_anak_persen }}% / {{ $item->tunjangan_istri_persen }}%</td>
                                        <td>
                                            @if($item->status == 'Y')
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click="editUmum({{ $item->id }})" class="btn btn-sm btn-light text-primary rounded-circle"><i class="bi bi-pencil-square"></i></button>
                                            <button onclick="confirmLivewireDelete('umum', {{ $item->id }})" class="btn btn-sm btn-light text-danger rounded-circle"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted small">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- TAB: TRANSPORTASI --}}
                @if($activeTab === 'transportasi')
                <div class="row fade-in">
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm bg-body-tertiary rounded-4">
                            <div class="card-body p-4">
                                <h6 class="font-weight-bold text-primary mb-3">{{ $isEditMode ? 'Edit' : 'Tambah' }} Transportasi</h6>
                                <form wire:submit.prevent="{{ $isEditMode ? 'updateTransportasi' : 'storeTransportasi' }}">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Tgl Berlaku</label>
                                        <input type="date" class="form-control form-control-sm rounded-3 @error('tt_tgl_berlaku') is-invalid @enderror" wire:model="tt_tgl_berlaku">
                                        @error('tt_tgl_berlaku') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">No Peraturan</label>
                                        <input type="text" class="form-control form-control-sm rounded-3 @error('tt_no_peraturan') is-invalid @enderror" wire:model="tt_no_peraturan">
                                        @error('tt_no_peraturan') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan Ketua (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tt_nilai_tunjangan_ketua') is-invalid @enderror" wire:model="tt_nilai_tunjangan_ketua">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan Wakil (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tt_nilai_tunjangan_wakil') is-invalid @enderror" wire:model="tt_nilai_tunjangan_wakil">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan Anggota (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tt_nilai_tunjangan_anggota') is-invalid @enderror" wire:model="tt_nilai_tunjangan_anggota">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">File Peraturan (PDF)</label>
                                        <input type="file" accept=".pdf" class="form-control form-control-sm rounded-3 @error('tt_file_peraturan') is-invalid @enderror" wire:model="tt_file_peraturan">
                                        @if($tt_file_peraturan_old)
                                            <a href="{{ Storage::url($tt_file_peraturan_old) }}" target="_blank" class="small mt-1 d-block"><i class="bi bi-file-earmark-pdf"></i> Lihat File Saat Ini</a>
                                        @endif
                                        <div wire:loading wire:target="tt_file_peraturan" class="small text-info mt-1">Mengunggah...</div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">Status</label>
                                        <select class="form-select form-select-sm rounded-3 @error('tt_status') is-invalid @enderror" wire:model="tt_status">
                                            <option value="Y">Aktif</option>
                                            <option value="T">Tidak Aktif</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-3">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
                                        @if($isEditMode)
                                        <button type="button" class="btn btn-light btn-sm rounded-3" wire:click="resetForms">Batal</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="table-responsive rounded-4 shadow-sm border p-3 bg-body-tertiary">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small text-muted py-3">Peraturan</th>
                                        <th class="small text-muted py-3">Nilai (Ketua/Wakil/Anggota)</th>
                                        <th class="small text-muted py-3">Status</th>
                                        <th class="small text-muted py-3 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($dataTransportasi as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $item->no_peraturan }}</div>
                                            <div class="small text-muted">{{ \Carbon\Carbon::parse($item->tgl_berlaku)->format('d M Y') }}</div>
                                            @if($item->file_peraturan)
                                                <a href="{{ Storage::url($item->file_peraturan) }}" target="_blank" class="badge bg-body-tertiary text-primary border text-decoration-none mt-1"><i class="bi bi-file-pdf"></i> PDF</a>
                                            @endif
                                        </td>
                                        <td class="small">
                                            Ketua: Rp {{ number_format($item->nilai_tunjangan_ketua, 0, ',', '.') }}<br>
                                            Wakil: Rp {{ number_format($item->nilai_tunjangan_wakil, 0, ',', '.') }}<br>
                                            Anggota: Rp {{ number_format($item->nilai_tunjangan_anggota, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->status == 'Y')
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click="editTransportasi({{ $item->id }})" class="btn btn-sm btn-light text-primary rounded-circle"><i class="bi bi-pencil-square"></i></button>
                                            <button onclick="confirmLivewireDelete('transportasi', {{ $item->id }})" class="btn btn-sm btn-light text-danger rounded-circle"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted small">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- TAB: PERUMAHAN --}}
                @if($activeTab === 'perumahan')
                <div class="row fade-in">
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm bg-body-tertiary rounded-4">
                            <div class="card-body p-4">
                                <h6 class="font-weight-bold text-primary mb-3">{{ $isEditMode ? 'Edit' : 'Tambah' }} Perumahan</h6>
                                <form wire:submit.prevent="{{ $isEditMode ? 'updatePerumahan' : 'storePerumahan' }}">
                                    <!-- Samakan dengan form transportasi -->
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Tgl Berlaku</label>
                                        <input type="date" class="form-control form-control-sm rounded-3 @error('tp_tgl_berlaku') is-invalid @enderror" wire:model="tp_tgl_berlaku">
                                        @error('tp_tgl_berlaku') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">No Peraturan</label>
                                        <input type="text" class="form-control form-control-sm rounded-3 @error('tp_no_peraturan') is-invalid @enderror" wire:model="tp_no_peraturan">
                                        @error('tp_no_peraturan') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan Ketua (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tp_nilai_tunjangan_ketua') is-invalid @enderror" wire:model="tp_nilai_tunjangan_ketua">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan Wakil (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tp_nilai_tunjangan_wakil') is-invalid @enderror" wire:model="tp_nilai_tunjangan_wakil">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan Anggota (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tp_nilai_tunjangan_anggota') is-invalid @enderror" wire:model="tp_nilai_tunjangan_anggota">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">File Peraturan (PDF)</label>
                                        <input type="file" accept=".pdf" class="form-control form-control-sm rounded-3 @error('tp_file_peraturan') is-invalid @enderror" wire:model="tp_file_peraturan">
                                        @if($tp_file_peraturan_old)
                                            <a href="{{ Storage::url($tp_file_peraturan_old) }}" target="_blank" class="small mt-1 d-block"><i class="bi bi-file-earmark-pdf"></i> Lihat File Saat Ini</a>
                                        @endif
                                        <div wire:loading wire:target="tp_file_peraturan" class="small text-info mt-1">Mengunggah...</div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">Status</label>
                                        <select class="form-select form-select-sm rounded-3 @error('tp_status') is-invalid @enderror" wire:model="tp_status">
                                            <option value="Y">Aktif</option>
                                            <option value="T">Tidak Aktif</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-3">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
                                        @if($isEditMode)
                                        <button type="button" class="btn btn-light btn-sm rounded-3" wire:click="resetForms">Batal</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="table-responsive rounded-4 shadow-sm border p-3 bg-body-tertiary">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small text-muted py-3">Peraturan</th>
                                        <th class="small text-muted py-3">Nilai (Ketua/Wakil/Anggota)</th>
                                        <th class="small text-muted py-3">Status</th>
                                        <th class="small text-muted py-3 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($dataPerumahan as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $item->no_peraturan }}</div>
                                            <div class="small text-muted">{{ \Carbon\Carbon::parse($item->tgl_berlaku)->format('d M Y') }}</div>
                                            @if($item->file_peraturan)
                                                <a href="{{ Storage::url($item->file_peraturan) }}" target="_blank" class="badge bg-body-tertiary text-primary border text-decoration-none mt-1"><i class="bi bi-file-pdf"></i> PDF</a>
                                            @endif
                                        </td>
                                        <td class="small">
                                            Ketua: Rp {{ number_format($item->nilai_tunjangan_ketua, 0, ',', '.') }}<br>
                                            Wakil: Rp {{ number_format($item->nilai_tunjangan_wakil, 0, ',', '.') }}<br>
                                            Anggota: Rp {{ number_format($item->nilai_tunjangan_anggota, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->status == 'Y')
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click="editPerumahan({{ $item->id }})" class="btn btn-sm btn-light text-primary rounded-circle"><i class="bi bi-pencil-square"></i></button>
                                            <button onclick="confirmLivewireDelete('perumahan', {{ $item->id }})" class="btn btn-sm btn-light text-danger rounded-circle"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted small">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- TAB: KOMUNIKASI INTENSIF --}}
                @if($activeTab === 'komunikasi_intensif')
                <div class="row fade-in">
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm bg-body-tertiary rounded-4">
                            <div class="card-body p-4">
                                <h6 class="font-weight-bold text-primary mb-3">{{ $isEditMode ? 'Edit' : 'Tambah' }} Komunikasi Intensif</h6>
                                <form wire:submit.prevent="{{ $isEditMode ? 'updateKomunikasi' : 'storeKomunikasi' }}">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Tgl Berlaku</label>
                                        <input type="date" class="form-control form-control-sm rounded-3 @error('tki_tgl_berlaku') is-invalid @enderror" wire:model="tki_tgl_berlaku">
                                        @error('tki_tgl_berlaku') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">No Peraturan</label>
                                        <input type="text" class="form-control form-control-sm rounded-3 @error('tki_no_peraturan') is-invalid @enderror" wire:model="tki_no_peraturan">
                                        @error('tki_no_peraturan') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Nilai Tunjangan TKI (Rp)</label>
                                        <input type="text" oninput="formatRibuan(this)" class="form-control form-control-sm rounded-3 @error('tki_nilai_tunjangan_tki') is-invalid @enderror" wire:model="tki_nilai_tunjangan_tki">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">File Peraturan (PDF)</label>
                                        <input type="file" accept=".pdf" class="form-control form-control-sm rounded-3 @error('tki_file_peraturan') is-invalid @enderror" wire:model="tki_file_peraturan">
                                        @if($tki_file_peraturan_old)
                                            <a href="{{ Storage::url($tki_file_peraturan_old) }}" target="_blank" class="small mt-1 d-block"><i class="bi bi-file-earmark-pdf"></i> Lihat File Saat Ini</a>
                                        @endif
                                        <div wire:loading wire:target="tki_file_peraturan" class="small text-info mt-1">Mengunggah...</div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">Status</label>
                                        <select class="form-select form-select-sm rounded-3 @error('tki_status') is-invalid @enderror" wire:model="tki_status">
                                            <option value="Y">Aktif</option>
                                            <option value="T">Tidak Aktif</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-3">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
                                        @if($isEditMode)
                                        <button type="button" class="btn btn-light btn-sm rounded-3" wire:click="resetForms">Batal</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="table-responsive rounded-4 shadow-sm border p-3 bg-body-tertiary">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small text-muted py-3">Peraturan</th>
                                        <th class="small text-muted py-3">Nilai Tunjangan</th>
                                        <th class="small text-muted py-3">Status</th>
                                        <th class="small text-muted py-3 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($dataKomunikasi as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $item->no_peraturan }}</div>
                                            <div class="small text-muted">{{ \Carbon\Carbon::parse($item->tgl_berlaku)->format('d M Y') }}</div>
                                            @if($item->file_peraturan)
                                                <a href="{{ Storage::url($item->file_peraturan) }}" target="_blank" class="badge bg-body-tertiary text-primary border text-decoration-none mt-1"><i class="bi bi-file-pdf"></i> PDF</a>
                                            @endif
                                        </td>
                                        <td class="fw-medium text-primary">Rp {{ number_format($item->nilai_tunjangan_tki, 0, ',', '.') }}</td>
                                        <td>
                                            @if($item->status == 'Y')
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button wire:click="editKomunikasi({{ $item->id }})" class="btn btn-sm btn-light text-primary rounded-circle"><i class="bi bi-pencil-square"></i></button>
                                            <button onclick="confirmLivewireDelete('komp', {{ $item->id }})" class="btn btn-sm btn-light text-danger rounded-circle"><i class="bi bi-trash-fill"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted small">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                
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
    window.formatRibuan = function(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    window.addEventListener('swal', event => {
        const detail = event.detail[0] || event.detail; // Livewire v3 sometimes nests details
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

    window.confirmLivewireDelete = function(type, id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if(type === 'umum') $wire.call('deleteUmum', id);
                else if(type === 'transportasi') $wire.call('deleteTransportasi', id);
                else if(type === 'perumahan') $wire.call('deletePerumahan', id);
                else if(type === 'komp') $wire.call('deleteKomp', id);
            }
        });
    };
</script>
@endscript
