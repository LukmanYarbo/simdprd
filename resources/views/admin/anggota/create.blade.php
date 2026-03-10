@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Anggota', 'url' => route('admin.anggota.index'), 'icon' => 'bi-people'],
    ['label' => 'Tambah Anggota', 'icon' => 'bi-person-plus']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Tambah Anggota</h2>
        <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    <!-- Step Indicator -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body p-4 pt-5 pb-5">
            <div class="step-header">
                <div class="step-item active" data-step="1">
                    1 <span class="step-label">Data Pribadi</span>
                </div>
                <div class="step-item" data-step="2">
                    2 <span class="step-label">Kontak & Alamat</span>
                </div>
                <div class="step-item" data-step="3">
                    3 <span class="step-label">Keanggotaan</span>
                </div>
                <div class="step-item" data-step="4">
                    4 <span class="step-label">Asuransi & Foto</span>
                </div>
            </div>
        </div>
    </div>

    <form id="wizardForm" action="{{ route('admin.anggota.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-lg mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali isian Anda pada semua langkah.
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 1: Data Pribadi -->
        <div class="form-step active" id="step1">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person me-2"></i>Langkah 1: Data Pribadi</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" required>
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nokk" class="form-label">No. Kartu Keluarga (KK)</label>
                            <input type="text" class="form-control @error('nokk') is-invalid @enderror" id="nokk" name="nokk" value="{{ old('nokk') }}" required>
                            @error('nokk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="nama_anggota" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama_anggota') is-invalid @enderror" id="nama_anggota" name="nama_anggota" value="{{ old('nama_anggota') }}" required>
                            @error('nama_anggota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required>
                            @error('tgl_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jk" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('jk') is-invalid @enderror" id="jk" name="jk" required>
                                <option value="" disabled selected>Pilih JK</option>
                                <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_agama" class="form-label">Agama</label>
                            <select class="form-select @error('id_agama') is-invalid @enderror" id="id_agama" name="id_agama" required>
                                <option value="" disabled selected>Pilih Agama</option>
                                @foreach($agamas as $agama)
                                    <option value="{{ $agama->id }}" {{ old('id_agama') == $agama->id ? 'selected' : '' }}>{{ $agama->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_status_kawin" class="form-label">Status Perkawinan</label>
                            <select class="form-select @error('id_status_kawin') is-invalid @enderror" id="id_status_kawin" name="id_status_kawin" required>
                                <option value="" disabled selected>Pilih Status</option>
                                @foreach($statusKawins as $sk)
                                    <option value="{{ $sk->kode }}" {{ old('id_status_kawin') == $sk->kode ? 'selected' : '' }}>{{ $sk->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_status_kawin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="jmlh_istri" class="form-label">Jml Istri/Suami</label>
                            <input type="number" class="form-control @error('jmlh_istri') is-invalid @enderror" id="jmlh_istri" name="jmlh_istri" value="{{ old('jmlh_istri', 0) }}" readonly tabindex="-1">
                            <div class="form-text small text-muted">Otomatis dari form Keluarga</div>
                            @error('jmlh_istri')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="jmlh_anak" class="form-label">Jml Anak</label>
                            <input type="number" class="form-control @error('jmlh_anak') is-invalid @enderror" id="jmlh_anak" name="jmlh_anak" value="{{ old('jmlh_anak', 0) }}" readonly tabindex="-1">
                            <div class="form-text small text-muted">Otomatis dari form Keluarga</div>
                            @error('jmlh_anak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-4 text-end">
                    <button type="button" class="btn btn-primary px-5 next-btn" data-next="2">Next <i class="bi bi-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 2: Kontak & Alamat -->
        <div class="form-step" id="step2">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Langkah 2: Kontak & Alamat</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="no_telp" class="form-label">No. Telp / HP</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
                            @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="prov" class="form-label">Provinsi</label>
                            <select class="form-select @error('prov') is-invalid @enderror" id="prov" name="prov" required>
                                <option value="" selected disabled>Pilih Provinsi</option>
                            </select>
                            @error('prov')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kab" class="form-label">Kabupaten / Kota</label>
                            <select class="form-select @error('kab') is-invalid @enderror" id="kab" name="kab" required disabled>
                                <option value="" selected disabled>Pilih Kabupaten/Kota</option>
                            </select>
                            @error('kab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kec" class="form-label">Kecamatan</label>
                            <select class="form-select @error('kec') is-invalid @enderror" id="kec" name="kec" required disabled>
                                <option value="" selected disabled>Pilih Kecamatan</option>
                            </select>
                            @error('kec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="desa" class="form-label">Desa / Kelurahan</label>
                            <select class="form-select @error('desa') is-invalid @enderror" id="desa" name="desa" required disabled>
                                <option value="" selected disabled>Pilih Desa/Kelurahan</option>
                            </select>
                            @error('desa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="alamat_lengkap" class="form-label">Alamat Lengkap (Jl / Dusun / RT / RW)</label>
                            <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" id="alamat_lengkap" name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap') }}</textarea>
                            @error('alamat_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-5 prev-btn" data-prev="1"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                    <button type="button" class="btn btn-primary px-5 next-btn" data-next="3">Next <i class="bi bi-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 3: Keanggotaan & Keuangan -->
        <div class="form-step" id="step3">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-briefcase me-2"></i>Langkah 3: Keanggotaan & Keuangan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="id_skpd" class="form-label">SKPD</label>
                            <select class="form-select @error('id_skpd') is-invalid @enderror" id="id_skpd" name="id_skpd">
                                <option value="" disabled selected>Pilih SKPD</option>
                                @foreach($skpds as $skpd)
                                    <option value="{{ $skpd->id }}" {{ old('id_skpd') == $skpd->id ? 'selected' : '' }}>{{ $skpd->namaskpd }}</option>
                                @endforeach
                            </select>
                            @error('id_skpd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_status_keanggotaan" class="form-label">Status Keanggotaan</label>
                            <select class="form-select @error('id_status_keanggotaan') is-invalid @enderror" id="id_status_keanggotaan" name="id_status_keanggotaan" required>
                                <option value="" disabled selected>Pilih Status</option>
                                @foreach($statusKeanggotaans as $sk)
                                    <option value="{{ $sk->id }}" {{ old('id_status_keanggotaan') == $sk->id ? 'selected' : '' }}>{{ $sk->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_status_keanggotaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="id_dprd" class="form-label">Jabatan</label>
                            <select class="form-select @error('id_dprd') is-invalid @enderror" id="id_dprd" name="id_dprd" required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                @foreach($jabatans as $j)
                                    <option value="{{ $j->id }}" {{ old('id_dprd') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_dprd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control @error('tgl_mulai') is-invalid @enderror" id="tgl_mulai" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required>
                            @error('tgl_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tgl_berhenti" class="form-label">Tanggal Berhenti (Opsional)</label>
                            <input type="date" class="form-control @error('tgl_berhenti') is-invalid @enderror" id="tgl_berhenti" name="tgl_berhenti" value="{{ old('tgl_berhenti') }}">
                            @error('tgl_berhenti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="no_rekening" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control @error('no_rekening') is-invalid @enderror" id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}" required>
                            @error('no_rekening')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="no_npwp" class="form-label">Nomor NPWP</label>
                            <input type="text" class="form-control @error('no_npwp') is-invalid @enderror" id="no_npwp" name="no_npwp" value="{{ old('no_npwp') }}" placeholder="00.000.000.0-000.000">
                            @error('no_npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-5 prev-btn" data-prev="2"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                    <button type="button" class="btn btn-primary px-5 next-btn" data-next="4">Next <i class="bi bi-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 4: Asuransi & Tunjangan + Foto -->
        <div class="form-step" id="step4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-shield-check me-2"></i>Langkah 4: Asuransi & Foto</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block">Foto Anggota</label>
                            <div class="mb-3">
                                <img id="preview" src="https://ui-avatars.com/api/?name=New+Anggota&background=random&size=180" class="rounded border shadow-lg" width="180" height="180" title="Klik untuk ganti foto">
                            </div>
                            <input type="file" class="form-control form-control-sm @error('foto_anggota') is-invalid @enderror" id="foto_anggota" name="foto_anggota">
                            @error('foto_anggota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label d-block">Peserta BPJS?</label>
                                    <div class="d-flex gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_bpjs" id="status_bpjs_y" value="Y" {{ old('status_bpjs') == 'Y' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_bpjs_y">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_bpjs" id="status_bpjs_t" value="T" {{ old('status_bpjs', 'T') == 'T' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_bpjs_t">Tidak</label>
                                        </div>
                                    </div>
                                    @error('status_bpjs')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="no_bpjs_wrapper" style="{{ old('status_bpjs') == 'Y' ? '' : 'visibility: hidden; pointer-events: none;' }}">
                                    <label for="no_bpjs" class="form-label">No. BPJS</label>
                                    <input type="text" class="form-control @error('no_bpjs') is-invalid @enderror" id="no_bpjs" name="no_bpjs" value="{{ old('no_bpjs') }}">
                                    @error('no_bpjs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Peserta JKK?</label>
                                    <div class="d-flex gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jkk" id="status_jkk_y" value="Y" {{ old('status_jkk') == 'Y' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_jkk_y">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jkk" id="status_jkk_t" value="T" {{ old('status_jkk', 'T') == 'T' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_jkk_t">Tidak</label>
                                        </div>
                                    </div>
                                    @error('status_jkk')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="no_jkk_wrapper" style="{{ old('status_jkk') == 'Y' ? '' : 'visibility: hidden; pointer-events: none;' }}">
                                    <label for="no_jkk" class="form-label">No. JKK</label>
                                    <input type="text" class="form-control @error('no_jkk') is-invalid @enderror" id="no_jkk" name="no_jkk" value="{{ old('no_jkk') }}">
                                    @error('no_jkk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Peserta JKM?</label>
                                    <div class="d-flex gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jkm" id="status_jkm_y" value="Y" {{ old('status_jkm') == 'Y' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_jkm_y">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jkm" id="status_jkm_t" value="T" {{ old('status_jkm', 'T') == 'T' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_jkm_t">Tidak</label>
                                        </div>
                                    </div>
                                    @error('status_jkm')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="no_jkm_wrapper" style="{{ old('status_jkm') == 'Y' ? '' : 'visibility: hidden; pointer-events: none;' }}">
                                    <label for="no_jkm" class="form-label">No. JKM</label>
                                    <input type="text" class="form-control @error('no_jkm') is-invalid @enderror" id="no_jkm" name="no_jkm" value="{{ old('no_jkm') }}">
                                    @error('no_jkm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status_tjgn_perum" class="form-label">Tunjangan Perumahan?</label>
                                    <select class="form-select @error('status_tjgn_perum') is-invalid @enderror" id="status_tjgn_perum" name="status_tjgn_perum" required>
                                        <option value="T" {{ old('status_tjgn_perum') == 'T' ? 'selected' : '' }}>Tidak</option>
                                        <option value="Y" {{ old('status_tjgn_perum') == 'Y' ? 'selected' : '' }}>Ya</option>
                                    </select>
                                    @error('status_tjgn_perum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status_tjgn_transport" class="form-label">Tunjangan Transport?</label>
                                    <select class="form-select @error('status_tjgn_transport') is-invalid @enderror" id="status_tjgn_transport" name="status_tjgn_transport" required>
                                        <option value="T" {{ old('status_tjgn_transport') == 'T' ? 'selected' : '' }}>Tidak</option>
                                        <option value="Y" {{ old('status_tjgn_transport') == 'Y' ? 'selected' : '' }}>Ya</option>
                                    </select>
                                    @error('status_tjgn_transport')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-5 prev-btn" data-prev="3"><i class="bi bi-arrow-left me-1"></i> Previous</button>
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-save me-1"></i> Simpan Data Anggota
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('assets/js/anggota.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new AnggotaWizard({
            validationUrl: "{{ route('admin.anggota.validate-step') }}"
        });
    });
</script>
@endpush
@endsection
