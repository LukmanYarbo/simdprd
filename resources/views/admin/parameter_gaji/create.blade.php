@extends('layouts.admin')

@section('title', 'Tambah Parameter Gaji')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Tambah Parameter Gaji" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
        ['label' => 'Parameter Gaji', 'url' => route('admin.parameter-gaji.index'), 'icon' => 'ti ti-calculator'],
        ['label' => 'Tambah Data']
    ]" />

    <div class="modern-form-card mb-4 mt-2">
        <div class="form-card-header">
            <div class="icon-box"><i class="ti ti-plus"></i></div>
            <div class="header-text"><h5>Form Tambah Parameter Gaji</h5><small>Konfigurasi aturan dan tunjangan gaji DPRD</small></div>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.parameter-gaji.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Section 1: Informasi Peraturan --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="ti ti-file-description me-2"></i>Informasi Peraturan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="no_peraturan" class="form-label fw-semibold">Nomor Peraturan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_peraturan') is-invalid @enderror" id="no_peraturan" name="no_peraturan" value="{{ old('no_peraturan') }}" placeholder="Contoh: PP No.18 Tahun 2017" required>
                            @error('no_peraturan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tgl_berlaku" class="form-label fw-semibold">Tanggal Berlaku <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tgl_berlaku') is-invalid @enderror" id="tgl_berlaku" name="tgl_berlaku" value="{{ old('tgl_berlaku') }}" required>
                            @error('tgl_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Y" {{ old('status') == 'Y' ? 'selected' : '' }}>Aktif</option>
                                <option value="T" {{ old('status', 'T') == 'T' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="file" class="form-label fw-semibold">File Peraturan (PDF)</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf">
                            <div class="form-text">Upload file PDF (maks. 5MB)</div>
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: Gaji Pokok --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="ti ti-cash me-2"></i>Gaji Pokok
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="gajipokok_ketua" class="form-label fw-semibold">Gaji Pokok Ketua (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('gajipokok_ketua') is-invalid @enderror" id="gajipokok_ketua" name="gajipokok_ketua" value="{{ old('gajipokok_ketua') }}" min="0" oninput="updateCalculations()" required>
                            </div>
                            <div class="form-text text-success fw-semibold" id="result_gajipokok_ketua"></div>
                            @error('gajipokok_ketua') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="persen_gapokwakil" class="form-label fw-semibold">% Gaji Pokok Wakil <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_gapokwakil') is-invalid @enderror" id="persen_gapokwakil" name="persen_gapokwakil" value="{{ old('persen_gapokwakil') }}" min="0" max="100" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_gapokwakil">Rp 0</span></div>
                            @error('persen_gapokwakil') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="persen_gapokanggota" class="form-label fw-semibold">% Gaji Pokok Anggota <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_gapokanggota') is-invalid @enderror" id="persen_gapokanggota" name="persen_gapokanggota" value="{{ old('persen_gapokanggota') }}" min="0" max="100" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_gapokanggota">Rp 0</span></div>
                            @error('persen_gapokanggota') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Tunjangan Jabatan --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="ti ti-briefcase me-2"></i>Tunjangan Jabatan
                        <small class="text-muted fw-normal fs-6">(dari masing-masing Gaji Pokok)</small>
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="persen_tunjabketua" class="form-label fw-semibold">% Tunjangan Ketua <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunjabketua') is-invalid @enderror" id="persen_tunjabketua" name="persen_tunjabketua" value="{{ old('persen_tunjabketua') }}" min="0" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunjabketua">Rp 0</span></div>
                            @error('persen_tunjabketua') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="persen_tunjabwakil" class="form-label fw-semibold">% Tunjangan Wakil <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunjabwakil') is-invalid @enderror" id="persen_tunjabwakil" name="persen_tunjabwakil" value="{{ old('persen_tunjabwakil') }}" min="0" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunjabwakil">Rp 0</span></div>
                            @error('persen_tunjabwakil') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="persen_tunjabanggota" class="form-label fw-semibold">% Tunjangan Anggota <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunjabanggota') is-invalid @enderror" id="persen_tunjabanggota" name="persen_tunjabanggota" value="{{ old('persen_tunjabanggota') }}" min="0" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunjabanggota">Rp 0</span></div>
                            @error('persen_tunjabanggota') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 4: Tunjangan Alat Kelengkapan --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="ti ti-layers me-2"></i>Tunjangan Alat Kelengkapan
                        <small class="text-muted fw-normal fs-6">(dari masing-masing Gaji Pokok)</small>
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="persen_tunketua_aleg" class="form-label fw-semibold">% Ketua <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunketua_aleg') is-invalid @enderror" id="persen_tunketua_aleg" name="persen_tunketua_aleg" value="{{ old('persen_tunketua_aleg') }}" min="0" max="100" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunketua_aleg">Rp 0</span></div>
                            @error('persen_tunketua_aleg') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="persen_tunwakil_aleg" class="form-label fw-semibold">% Wakil <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunwakil_aleg') is-invalid @enderror" id="persen_tunwakil_aleg" name="persen_tunwakil_aleg" value="{{ old('persen_tunwakil_aleg') }}" min="0" max="100" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunwakil_aleg">Rp 0</span></div>
                            @error('persen_tunwakil_aleg') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="persen_tunsek_aleg" class="form-label fw-semibold">% Sekretaris <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunsek_aleg') is-invalid @enderror" id="persen_tunsek_aleg" name="persen_tunsek_aleg" value="{{ old('persen_tunsek_aleg') }}" min="0" max="100" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunsek_aleg">Rp 0</span></div>
                            @error('persen_tunsek_aleg') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="persen_tunanggota_aleg" class="form-label fw-semibold">% Anggota <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_tunanggota_aleg') is-invalid @enderror" id="persen_tunanggota_aleg" name="persen_tunanggota_aleg" value="{{ old('persen_tunanggota_aleg') }}" min="0" max="100" oninput="updateCalculations()" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text text-success fw-semibold">= <span id="result_tunanggota_aleg">Rp 0</span></div>
                            @error('persen_tunanggota_aleg') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 5: Uang Paket --}}
                <div class="border rounded-3 p-4 mb-4 bg-light bg-opacity-50">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <i class="ti ti-wallet me-2"></i>Uang Paket
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="persen_uangpaket" class="form-label fw-semibold">% Uang Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('persen_uangpaket') is-invalid @enderror" id="persen_uangpaket" name="persen_uangpaket" value="{{ old('persen_uangpaket') }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('persen_uangpaket') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.parameter-gaji.index') }}" class="btn-modern-ghost">
                        <i class="ti ti-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn-modern-primary">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function updateCalculations() {
        const gajipokok = parseFloat(document.getElementById('gajipokok_ketua').value) || 0;
        const persenWakil = parseFloat(document.getElementById('persen_gapokwakil').value) || 0;
        const persenAnggota = parseFloat(document.getElementById('persen_gapokanggota').value) || 0;

        const gajiWakil = gajipokok * persenWakil / 100;
        const gajiAnggota = gajipokok * persenAnggota / 100;

        document.getElementById('result_gajipokok_ketua').textContent = gajipokok > 0 ? formatRupiah(gajipokok) : '';
        document.getElementById('result_gapokwakil').textContent = formatRupiah(gajiWakil);
        document.getElementById('result_gapokanggota').textContent = formatRupiah(gajiAnggota);

        const persenTunjabKetua = parseFloat(document.getElementById('persen_tunjabketua').value) || 0;
        const persenTunjabWakil = parseFloat(document.getElementById('persen_tunjabwakil').value) || 0;
        const persenTunjabAnggota = parseFloat(document.getElementById('persen_tunjabanggota').value) || 0;

        document.getElementById('result_tunjabketua').textContent = formatRupiah(gajipokok * persenTunjabKetua / 100);
        document.getElementById('result_tunjabwakil').textContent = formatRupiah(gajiWakil * persenTunjabWakil / 100);
        document.getElementById('result_tunjabanggota').textContent = formatRupiah(gajiAnggota * persenTunjabAnggota / 100);

        const tunjabKetua = gajipokok * persenTunjabKetua / 100;
        const tunjabWakil = gajiWakil * persenTunjabWakil / 100;
        const tunjabAnggota = gajiAnggota * persenTunjabAnggota / 100;

        const persenAlegKetua = parseFloat(document.getElementById('persen_tunketua_aleg').value) || 0;
        const persenAlegWakil = parseFloat(document.getElementById('persen_tunwakil_aleg').value) || 0;
        const persenAlegSek = parseFloat(document.getElementById('persen_tunsek_aleg').value) || 0;
        const persenAlegAnggota = parseFloat(document.getElementById('persen_tunanggota_aleg').value) || 0;

        document.getElementById('result_tunketua_aleg').textContent = formatRupiah(tunjabKetua * persenAlegKetua / 100);
        document.getElementById('result_tunwakil_aleg').textContent = formatRupiah(tunjabKetua * persenAlegWakil / 100);
        document.getElementById('result_tunsek_aleg').textContent = formatRupiah(tunjabKetua * persenAlegSek / 100);
        document.getElementById('result_tunanggota_aleg').textContent = formatRupiah(tunjabKetua * persenAlegAnggota / 100);
    }

    // Run on page load in case of old() values
    document.addEventListener('DOMContentLoaded', updateCalculations);
</script>
@endpush
