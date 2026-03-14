@extends('layouts.admin')

@section('title', 'Tambah Tarif Pajak')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Tambah Tarif Pajak" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
        ['label' => 'Tarif Pajak', 'url' => route('admin.tarif-pajak.index'), 'icon' => 'bi-percent'],
        ['label' => 'Tambah Data']
    ]" />

    <div class="card shadow-lg border-0 mb-4 mt-4">
        <div class="card-header py-3 bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-plus-circle me-2"></i>Form Tambah Tarif Pajak PPh 21</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.tarif-pajak.store') }}" method="POST">
                @csrf

                {{-- Section 1: Informasi Peraturan --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="bi bi-file-earmark-text me-2"></i>Informasi Peraturan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="no_peraturan" class="form-label fw-semibold">Nomor Peraturan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_peraturan') is-invalid @enderror"
                                id="no_peraturan" name="no_peraturan" value="{{ old('no_peraturan') }}"
                                placeholder="Contoh: UU No. 7 Tahun 2021 (UU HPP)" required>
                            @error('no_peraturan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tgl_berlaku" class="form-label fw-semibold">Tanggal Berlaku <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tgl_berlaku') is-invalid @enderror"
                                id="tgl_berlaku" name="tgl_berlaku" value="{{ old('tgl_berlaku') }}" required>
                            @error('tgl_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="T" {{ old('status', 'T') === 'T' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="Y" {{ old('status') === 'Y' ? 'selected' : '' }}>Aktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: PTKP --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="bi bi-person-check me-2"></i>Penghasilan Tidak Kena Pajak (PTKP)
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="ptkp" class="form-label fw-semibold">PTKP Dasar / TK-0 (Rp/Tahun) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('ptkp') is-invalid @enderror"
                                    id="ptkp" name="ptkp" value="{{ old('ptkp', 54000000) }}" min="0" required>
                            </div>
                            <div class="form-text text-success fw-semibold" id="fmt_ptkp"></div>
                            @error('ptkp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tambahan_ptkp_istri" class="form-label fw-semibold">Tambahan PTKP Istri (Rp/Tahun) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('tambahan_ptkp_istri') is-invalid @enderror"
                                    id="tambahan_ptkp_istri" name="tambahan_ptkp_istri" value="{{ old('tambahan_ptkp_istri', 4500000) }}" min="0" required>
                            </div>
                            <div class="form-text text-success fw-semibold" id="fmt_istri"></div>
                            @error('tambahan_ptkp_istri') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tambahan_ptkp_tanggungan" class="form-label fw-semibold">Tambahan / Tanggungan (Rp/Tahun) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('tambahan_ptkp_tanggungan') is-invalid @enderror"
                                    id="tambahan_ptkp_tanggungan" name="tambahan_ptkp_tanggungan" value="{{ old('tambahan_ptkp_tanggungan', 4500000) }}" min="0" required>
                            </div>
                            <div class="form-text text-success fw-semibold" id="fmt_tanggungan"></div>
                            @error('tambahan_ptkp_tanggungan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Biaya Jabatan --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="bi bi-briefcase me-2"></i>Biaya Jabatan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="persen_biaya_jabatan" class="form-label fw-semibold">% Biaya Jabatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_biaya_jabatan') is-invalid @enderror"
                                    id="persen_biaya_jabatan" name="persen_biaya_jabatan" value="{{ old('persen_biaya_jabatan', 5) }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('persen_biaya_jabatan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_biaya_jabatan" class="form-label fw-semibold">Maks Biaya Jabatan (Rp/Bulan) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('max_biaya_jabatan') is-invalid @enderror"
                                    id="max_biaya_jabatan" name="max_biaya_jabatan" value="{{ old('max_biaya_jabatan', 500000) }}" min="0" required>
                            </div>
                            <div class="form-text text-success fw-semibold" id="fmt_maxbiaya"></div>
                            @error('max_biaya_jabatan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="alert alert-info py-2 px-3 mb-0 w-100 small">
                                <i class="bi bi-info-circle me-1"></i>
                                Maks/Tahun: <strong id="fmt_maxbiaya_tahun">Rp 6.000.000</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Tarif Lapis Pajak --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-layers me-2"></i>Tarif Lapis Pajak (Progresif)</span>
                        <button type="button" class="btn btn-sm btn-success" onclick="addLapisRow()">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Lapis
                        </button>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="tabelLapis">
                            <thead class="table-primary text-center small">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Penghasilan Dari (Rp)</th>
                                    <th>Penghasilan Sampai (Rp)</th>
                                    <th width="15%">Tarif (%)</th>
                                    <th width="8%"></th>
                                </tr>
                            </thead>
                            <tbody id="lapisBody">
                                {{-- Default rows based on UU HPP 2021 --}}
                                <tr>
                                    <td class="text-center text-muted nomer-lapis">1</td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[0][dari]" value="0" min="0" required></div></td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[0][sampai]" value="60000000" min="0" placeholder="Kosongkan = tak terbatas"></div></td>
                                    <td><div class="input-group input-group-sm"><input type="number" step="0.01" class="form-control" name="lapis[0][persen]" value="5" min="0" max="100" required><span class="input-group-text">%</span></div></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLapisRow(this)"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-muted nomer-lapis">2</td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[1][dari]" value="60000001" min="0" required></div></td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[1][sampai]" value="250000000" min="0"></div></td>
                                    <td><div class="input-group input-group-sm"><input type="number" step="0.01" class="form-control" name="lapis[1][persen]" value="15" min="0" max="100" required><span class="input-group-text">%</span></div></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLapisRow(this)"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-muted nomer-lapis">3</td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[2][dari]" value="250000001" min="0" required></div></td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[2][sampai]" value="500000000" min="0"></div></td>
                                    <td><div class="input-group input-group-sm"><input type="number" step="0.01" class="form-control" name="lapis[2][persen]" value="25" min="0" max="100" required><span class="input-group-text">%</span></div></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLapisRow(this)"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-muted nomer-lapis">4</td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[3][dari]" value="500000001" min="0" required></div></td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[3][sampai]" value="5000000000" min="0"></div></td>
                                    <td><div class="input-group input-group-sm"><input type="number" step="0.01" class="form-control" name="lapis[3][persen]" value="30" min="0" max="100" required><span class="input-group-text">%</span></div></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLapisRow(this)"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-muted nomer-lapis">5</td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[4][dari]" value="5000000001" min="0" required></div></td>
                                    <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[4][sampai]" placeholder="Tak terbatas (kosongkan)"></div></td>
                                    <td><div class="input-group input-group-sm"><input type="number" step="0.01" class="form-control" name="lapis[4][persen]" value="35" min="0" max="100" required><span class="input-group-text">%</span></div></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLapisRow(this)"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Kolom "Sampai" dikosongkan untuk lapis terakhir (penghasilan tak terbatas).</div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.tarif-pajak.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRp(val) {
        if (!val) return '';
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
    }

    function bindFormatting(inputId, displayId) {
        const el = document.getElementById(inputId);
        const disp = document.getElementById(displayId);
        if (!el || !disp) return;
        const update = () => disp.textContent = el.value ? formatRp(el.value) : '';
        el.addEventListener('input', update);
        update();
    }

    bindFormatting('ptkp', 'fmt_ptkp');
    bindFormatting('tambahan_ptkp_istri', 'fmt_istri');
    bindFormatting('tambahan_ptkp_tanggungan', 'fmt_tanggungan');
    bindFormatting('max_biaya_jabatan', 'fmt_maxbiaya');

    // Maks biaya jabatan per tahun
    const maxBiayaEl = document.getElementById('max_biaya_jabatan');
    const maxTahunEl = document.getElementById('fmt_maxbiaya_tahun');
    function updateMaxTahun() {
        const val = parseFloat(maxBiayaEl.value) || 0;
        maxTahunEl.textContent = formatRp(val * 12);
    }
    maxBiayaEl.addEventListener('input', updateMaxTahun);
    updateMaxTahun();

    let lapisCounter = 5;

    function addLapisRow() {
        const idx = lapisCounter++;
        const tbody = document.getElementById('lapisBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center text-muted nomer-lapis"></td>
            <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[${idx}][dari]" min="0" required></div></td>
            <td><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="lapis[${idx}][sampai]" min="0" placeholder="Tak terbatas (kosongkan)"></div></td>
            <td><div class="input-group input-group-sm"><input type="number" step="0.01" class="form-control" name="lapis[${idx}][persen]" min="0" max="100" required><span class="input-group-text">%</span></div></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLapisRow(this)"><i class="bi bi-trash3"></i></button></td>`;
        tbody.appendChild(tr);
        renumberLapis();
    }

    function removeLapisRow(btn) {
        btn.closest('tr').remove();
        renumberLapis();
    }

    function renumberLapis() {
        document.querySelectorAll('.nomer-lapis').forEach((el, i) => el.textContent = i + 1);
    }
</script>
@endpush
