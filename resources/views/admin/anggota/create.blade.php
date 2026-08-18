@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Anggota', 'url' => route('admin.anggota.index'), 'icon' => 'ti ti-users'],
    ['label' => 'Tambah Anggota', 'icon' => 'ti ti-user-plus']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Tambah Anggota</h2>
        <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    <!-- Step Indicator -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body p-4">
            <div class="wizard-stepper">
                <div class="wizard-progress">
                    <div class="wizard-progress-bar" id="wizardProgressBar"></div>
                </div>
                <div class="wizard-steps">
                    <div class="wizard-step active" data-step="1">
                        <div class="wizard-step-bubble">
                            <span class="wizard-step-number">1</span>
                            <span class="wizard-step-check"><i class="ti ti-check"></i></span>
                        </div>
                        <div class="wizard-step-meta">
                            <span class="wizard-step-title">Data Pribadi</span>
                            <span class="wizard-step-desc">Identitas & keluarga</span>
                        </div>
                    </div>
                    <div class="wizard-step" data-step="2">
                        <div class="wizard-step-bubble">
                            <span class="wizard-step-number">2</span>
                            <span class="wizard-step-check"><i class="ti ti-check"></i></span>
                        </div>
                        <div class="wizard-step-meta">
                            <span class="wizard-step-title">Kontak & Alamat</span>
                            <span class="wizard-step-desc">Kontak & domisili</span>
                        </div>
                    </div>
                    <div class="wizard-step" data-step="3">
                        <div class="wizard-step-bubble">
                            <span class="wizard-step-number">3</span>
                            <span class="wizard-step-check"><i class="ti ti-check"></i></span>
                        </div>
                        <div class="wizard-step-meta">
                            <span class="wizard-step-title">Keanggotaan</span>
                            <span class="wizard-step-desc">Jabatan & keuangan</span>
                        </div>
                    </div>
                    <div class="wizard-step" data-step="4">
                        <div class="wizard-step-bubble">
                            <span class="wizard-step-number">4</span>
                            <span class="wizard-step-check"><i class="ti ti-check"></i></span>
                        </div>
                        <div class="wizard-step-meta">
                            <span class="wizard-step-title">Asuransi & Foto</span>
                            <span class="wizard-step-desc">BPJS, tunjangan, foto</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="wizardForm" class="wizard-modern" action="{{ route('admin.anggota.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-lg mb-4">
                <div class="d-flex align-items-center">
                    <i class="ti ti-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali isian Anda pada semua langkah.
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 1: Data Pribadi -->
        <div class="form-step active" id="step1">
            <div class="card wizard-card">
                <div class="card-header wizard-card-header border-0">
                    <div class="wizard-card-icon"><i class="ti ti-person"></i></div>
                    <div>
                        <h5 class="wizard-card-title">Langkah 1: Data Pribadi</h5>
                        <small class="wizard-card-subtitle">Identitas, tempat & tanggal lahir</small>
                    </div>
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
                            <label class="form-label d-block text-secondary small text-uppercase fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="wz-segmented gender" role="group">
                                <input type="radio" class="btn-check" name="jk" id="jk_l" value="L" {{ old('jk') == 'L' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-primary" for="jk_l"><i class="ti ti-gender-male me-1"></i> Laki-laki</label>

                                <input type="radio" class="btn-check" name="jk" id="jk_p" value="P" {{ old('jk') == 'P' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="jk_p"><i class="ti ti-gender-female me-1"></i> Perempuan</label>
                            </div>
                            @error('jk') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                    <button type="button" class="btn btn-gradient px-5 next-btn" data-next="2">Lanjut <i class="ti ti-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 2: Kontak & Alamat -->
        <div class="form-step" id="step2">
            <div class="card wizard-card">
                <div class="card-header wizard-card-header border-0">
                    <div class="wizard-card-icon"><i class="ti ti-geo-alt"></i></div>
                    <div>
                        <h5 class="wizard-card-title">Langkah 2: Kontak & Alamat</h5>
                        <small class="wizard-card-subtitle">Kontak, wilayah & alamat lengkap</small>
                    </div>
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
                    <button type="button" class="btn btn-ghost btn-outline-secondary px-5 prev-btn" data-prev="1"><i class="ti ti-arrow-left me-1"></i> Kembali</button>
                    <button type="button" class="btn btn-gradient px-5 next-btn" data-next="3">Lanjut <i class="ti ti-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 3: Keanggotaan & Keuangan -->
        <div class="form-step" id="step3">
            <div class="card wizard-card">
                <div class="card-header wizard-card-header border-0">
                    <div class="wizard-card-icon"><i class="ti ti-briefcase"></i></div>
                    <div>
                        <h5 class="wizard-card-title">Langkah 3: Keanggotaan & Keuangan</h5>
                        <small class="wizard-card-subtitle">SKPD, jabatan & data keuangan</small>
                    </div>
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
                    <button type="button" class="btn btn-ghost btn-outline-secondary px-5 prev-btn" data-prev="2"><i class="ti ti-arrow-left me-1"></i> Kembali</button>
                    <button type="button" class="btn btn-gradient px-5 next-btn" data-next="4">Lanjut <i class="ti ti-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 4: Asuransi & Tunjangan + Foto -->
        <div class="form-step" id="step4">
            <div class="card wizard-card">
                <div class="card-header wizard-card-header border-0">
                    <div class="wizard-card-icon"><i class="ti ti-shield-check"></i></div>
                    <div>
                        <h5 class="wizard-card-title">Langkah 4: Asuransi & Foto</h5>
                        <small class="wizard-card-subtitle">BPJS, JKK, JKM, tunjangan & foto</small>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block">Foto Anggota</label>
                            <div class="photo-uploader" id="photoUploader" title="Klik atau seret foto di sini">
                                <img id="preview" src="https://ui-avatars.com/api/?name=New+Anggota&background=random&size=200" alt="Foto anggota">
                                <div class="photo-uploader-overlay">
                                    <i class="ti ti-camera"></i>
                                    <span>Klik atau seret foto</span>
                                </div>
                            </div>
                            <input type="file" class="visually-hidden @error('foto_anggota') is-invalid @enderror" id="foto_anggota" name="foto_anggota" accept=".jpg,.jpeg,.png">
                            <div class="form-text small text-muted mt-2">Format JPG/PNG, maks 2 MB</div>
                            @error('foto_anggota')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label d-block">Peserta BPJS?</label>
                                    <div class="wz-segmented mt-1">
                                        <input type="radio" class="btn-check" name="status_bpjs" id="status_bpjs_y" value="Y" {{ old('status_bpjs') == 'Y' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="status_bpjs_y"><i class="ti ti-check me-1"></i> Ya</label>
                                        <input type="radio" class="btn-check" name="status_bpjs" id="status_bpjs_t" value="T" {{ old('status_bpjs', 'T') == 'T' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="status_bpjs_t"><i class="ti ti-x me-1"></i> Tidak</label>
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
                                    <div class="wz-segmented mt-1">
                                        <input type="radio" class="btn-check" name="status_jkk" id="status_jkk_y" value="Y" {{ old('status_jkk') == 'Y' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="status_jkk_y"><i class="ti ti-check me-1"></i> Ya</label>
                                        <input type="radio" class="btn-check" name="status_jkk" id="status_jkk_t" value="T" {{ old('status_jkk', 'T') == 'T' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="status_jkk_t"><i class="ti ti-x me-1"></i> Tidak</label>
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
                                    <div class="wz-segmented mt-1">
                                        <input type="radio" class="btn-check" name="status_jkm" id="status_jkm_y" value="Y" {{ old('status_jkm') == 'Y' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="status_jkm_y"><i class="ti ti-check me-1"></i> Ya</label>
                                        <input type="radio" class="btn-check" name="status_jkm" id="status_jkm_t" value="T" {{ old('status_jkm', 'T') == 'T' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="status_jkm_t"><i class="ti ti-x me-1"></i> Tidak</label>
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
                    <button type="button" class="btn btn-ghost btn-outline-secondary px-5 prev-btn" data-prev="3"><i class="ti ti-arrow-left me-1"></i> Kembali</button>
                    <button type="submit" class="btn btn-gradient btn-lg px-5">
                        <i class="ti ti-save me-1"></i> Simpan Data Anggota
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/anggota-wizard.css') }}?v={{ filemtime(public_path('assets/css/anggota-wizard.css')) }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/anggota.js') }}?v={{ filemtime(public_path('assets/js/anggota.js')) }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new AnggotaWizard({
            validationUrl: "{{ route('admin.anggota.validate-step') }}"
        });
    });
</script>
@endpush
@endsection
