@extends('layouts.admin')

@section('title', 'Tambah Pegawai ASN')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Pegawai ASN', 'url' => route('admin.pegawai-asn.index'), 'icon' => 'ti ti-user-shield'],
    ['label' => 'Tambah Pegawai', 'icon' => 'ti ti-plus']
]" />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}" />
    <style>.select2-container { width: 100% !important; }</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pegawai ASN</h1>
        <a href="{{ route('admin.pegawai-asn.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header py-3  border-bottom">
            <h6 class="m-0 font-weight-bold text-primary"><i class="ti ti-user-plus me-2"></i>Form Tambah Pegawai</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.pegawai-asn.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <!-- Data Pribadi -->
                    <div class="col-lg-6">
                        <div class="p-3 bg-body-tertiary rounded-3 h-100">
                            <h5 class="mb-3 text-primary border-bottom pb-2"><i class="ti ti-person-vcard me-2"></i>Data Pribadi</h5>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" placeholder="NIP" required>
                                <label for="nip">NIP <span class="text-danger">*</span></label>
                                @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="NIK" required>
                                <label for="nik">NIK <span class="text-danger">*</span></label>
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nokk') is-invalid @enderror" id="nokk" name="nokk" value="{{ old('nokk') }}" placeholder="No KK">
                                <label for="nokk">No. Kartu Keluarga</label>
                                @error('nokk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" required>
                                <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir" required>
                                        <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" placeholder="Tanggal Lahir" required>
                                        <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                        @error('tgl_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block text-secondary small text-uppercase fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="jenis_kelamin" id="jk_l" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary" for="jk_l"><i class="ti ti-gender-male me-1"></i> Laki-laki</label>
                                    
                                    <input type="radio" class="btn-check" name="jenis_kelamin" id="jk_p" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger" for="jk_p"><i class="ti ti-gender-female me-1"></i> Perempuan</label>
                                </div>
                                @error('jenis_kelamin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select @error('id_agama') is-invalid @enderror" id="id_agama" name="id_agama" required>
                                            <option value="">Pilih...</option>
                                            @foreach($agama as $a)
                                                <option value="{{ $a->id }}" {{ old('id_agama') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                                            @endforeach
                                        </select>
                                        <label for="id_agama">Agama <span class="text-danger">*</span></label>
                                        @error('id_agama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select @error('id_status_kawin') is-invalid @enderror" id="id_status_kawin" name="id_status_kawin" required>
                                            <option value="">Pilih...</option>
                                            @foreach($statusKawin as $s)
                                                <option value="{{ $s->kode }}" {{ old('id_status_kawin') == $s->kode ? 'selected' : '' }}>{{ $s->nama }}</option>
                                            @endforeach
                                        </select>
                                        <label for="id_status_kawin">Status Kawin <span class="text-danger">*</span></label>
                                        @error('id_status_kawin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Kepegawaian & Kontak -->
                    <div class="col-lg-6">
                        <div class="p-3 bg-body-tertiary rounded-3 h-100">
                            <h5 class="mb-3 text-primary border-bottom pb-2"><i class="ti ti-briefcase me-2"></i>Kepegawaian & Kontak</h5>

                            {{-- SKPD (Select2 AJAX) --}}
                            <div class="mb-3">
                                <label for="id_skpd" class="form-label">SKPD</label>
                                <select class="form-select @error('id_skpd') is-invalid @enderror" id="id_skpd" name="id_skpd" style="width:100%">
                                    <option value="">-- Cari & Pilih SKPD --</option>
                                </select>
                                @error('id_skpd') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="id_jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('id_jabatan') is-invalid @enderror" id="id_jabatan" name="id_jabatan" style="width: 100%" required>
                                    <option value="">-- Pilih SKPD Terlebih Dahulu --</option>
                                </select>
                                @error('id_jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('ket_jabatan') is-invalid @enderror" id="ket_jabatan" name="ket_jabatan" value="{{ old('ket_jabatan') }}" placeholder="Keterangan Jabatan">
                                <label for="ket_jabatan">Keterangan Jabatan</label>
                                @error('ket_jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select @error('id_status_pegawai') is-invalid @enderror" id="id_status_pegawai" name="id_status_pegawai" required>
                                    <option value="">Pilih Status...</option>
                                    @foreach($statusPegawai as $sp)
                                        <option value="{{ $sp->id }}" {{ old('id_status_pegawai') == $sp->id ? 'selected' : '' }}>{{ $sp->nama }}</option>
                                    @endforeach
                                </select>
                                <label for="id_status_pegawai">Status Pegawai <span class="text-danger">*</span></label>
                                @error('id_status_pegawai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select @error('id_pangkat_golongan') is-invalid @enderror" id="id_pangkat_golongan" name="id_pangkat_golongan" required>
                                    <option value="">Pilih Pangkat/Golongan...</option>
                                    @foreach($pangkatGolongan as $pg)
                                        <option value="{{ $pg->id }}" {{ old('id_pangkat_golongan') == $pg->id ? 'selected' : '' }}>{{ $pg->pangkat }} - {{ $pg->golongan }}</option>
                                    @endforeach
                                </select>
                                <label for="id_pangkat_golongan">Pangkat / Golongan <span class="text-danger">*</span></label>
                                @error('id_pangkat_golongan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="date" class="form-control @error('tanggal_mulai_kerja') is-invalid @enderror" id="tanggal_mulai_kerja" name="tanggal_mulai_kerja" value="{{ old('tanggal_mulai_kerja') }}">
                                        <label for="tanggal_mulai_kerja">Tanggal Mulai Kerja</label>
                                        @error('tanggal_mulai_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="date" class="form-control @error('tanggal_berhenti') is-invalid @enderror" id="tanggal_berhenti" name="tanggal_berhenti" value="{{ old('tanggal_berhenti') }}">
                                        <label for="tanggal_berhenti">Tanggal Berhenti</label>
                                        @error('tanggal_berhenti') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                <label for="email">Email</label>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nohp') is-invalid @enderror" id="nohp" name="nohp" value="{{ old('nohp') }}" placeholder="No HP">
                                <label for="nohp">No. HP</label>
                                @error('nohp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('norek') is-invalid @enderror" id="norek" name="norek" value="{{ old('norek') }}" placeholder="No Rekening">
                                        <label for="norek">No. Rekening</label>
                                        @error('norek') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" value="{{ old('npwp') }}" placeholder="NPWP">
                                        <label for="npwp">NPWP</label>
                                        @error('npwp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label text-secondary small text-uppercase fw-bold">Foto Profil</label>
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                                <div class="form-text">Format: jpg, jpeg, png. Max: 2MB</div>
                                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="reset" class="btn btn-outline-secondary px-4"><i class="ti ti-arrow-counterclockwise me-2"></i>Reset</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="ti ti-save me-2"></i>Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Jabatan Select2 with AJAX filtering
        $('#id_jabatan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Jabatan...',
            allowClear: true,
            ajax: {
                url: '{{ route("admin.jabatan-asn.search-by-skpd") }}',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term || '',
                        id_skpd: $('#id_skpd').val()
                    };
                },
                processResults: function(data) {
                    return { results: data.results };
                },
                cache: true
            }
        });

        // Reset Jabatan when SKPD changes
        $('#id_skpd').on('change', function() {
            $('#id_jabatan').val(null).trigger('change');
            if ($(this).val()) {
                $('#id_jabatan').select2('open');
            }
        });

        // SKPD Select2 AJAX
        $('#id_skpd').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Cari & Pilih SKPD --',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route("admin.penanda-tangan.search-skpd") }}',
                dataType: 'json',
                delay: 300,
                data: function(params) { return { q: params.term || '' }; },
                processResults: function(data) { return { results: data.results }; },
                cache: true
            }
        });
    });
</script>
@endpush
