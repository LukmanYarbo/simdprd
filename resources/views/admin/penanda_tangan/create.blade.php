@extends('layouts.admin')

@section('title', 'Tambah Penanda Tangan')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Penanda Tangan', 'url' => route('admin.penanda-tangan.index'), 'icon' => 'bi-pen-fill'],
    ['label' => 'Tambah', 'icon' => 'bi-plus-circle-fill']
]" />
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container { width: 100% !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Penanda Tangan
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penanda-tangan.store') }}" method="POST">
                        @csrf

                        {{-- Jenis Dokumen --}}
                        <div class="mb-4">
                            <label for="jenis_dokumen" class="form-label fw-semibold">
                                Jenis Dokumen <span class="text-danger">*</span>
                            </label>
                            <select name="jenis_dokumen" id="jenis_dokumen"
                                class="form-select @error('jenis_dokumen') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis Dokumen --</option>
                                @foreach(['Surat Tugas', 'SPPD', 'Surat Keputusan'] as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_dokumen') == $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Anggota DPRD --}}
                        <div class="mb-4">
                            <label for="id_anggota" class="form-label fw-semibold">
                                Anggota DPRD
                                <small class="text-muted fw-normal">(Ketua / Wakil Ketua DPRD)</small>
                            </label>
                            <select name="id_anggota" id="id_anggota"
                                class="form-select @error('id_anggota') is-invalid @enderror">
                                <option value="">-- Pilih Anggota --</option>
                                @foreach($anggota as $a)
                                    <option value="{{ $a->id }}" {{ old('id_anggota') == $a->id ? 'selected' : '' }}>
                                        {{ $a->nama_anggota }} — {{ $a->jabatan->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_anggota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- SKPD (Select2 AJAX) --}}
                        <div class="mb-4">
                            <label for="id_skpd" class="form-label fw-semibold">
                                SKPD Penanda Tangan ASN
                            </label>
                            <select name="id_skpd" id="id_skpd"
                                class="form-select @error('id_skpd') is-invalid @enderror">
                                <option value="">-- Cari & Pilih SKPD --</option>
                            </select>
                            @error('id_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Pilih SKPD terlebih dahulu untuk memuat daftar ASN.</div>
                        </div>

                        {{-- Pegawai ASN (Select2 AJAX, dependent on SKPD) --}}
                        <div class="mb-4">
                            <label for="id_pegawai_asn" class="form-label fw-semibold">
                                Penanda Tangan ASN
                            </label>
                            <select name="id_pegawai_asn" id="id_pegawai_asn"
                                class="form-select @error('id_pegawai_asn') is-invalid @enderror">
                                <option value="">-- Pilih SKPD dahulu --</option>
                            </select>
                            @error('id_pegawai_asn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.penanda-tangan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {

        // === Select2: SKPD (AJAX search) ===
        $('#id_skpd').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Cari & Pilih SKPD --',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route("admin.penanda-tangan.search-skpd") }}',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { q: params.term || '' };
                },
                processResults: function (data) {
                    return { results: data.results };
                },
                cache: true
            }
        });

        // === Select2: Pegawai ASN (AJAX search, filtered by SKPD) ===
        var $asnSelect = $('#id_pegawai_asn').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih SKPD dahulu --',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route("admin.penanda-tangan.search-asn") }}',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term || '',
                        id_skpd: $('#id_skpd').val()
                    };
                },
                processResults: function (data) {
                    return { results: data.results };
                },
                cache: false
            }
        });

        // When SKPD changes, reset ASN selection and update placeholder
        $('#id_skpd').on('change', function () {
            $asnSelect.val(null).trigger('change');
            var skpdSelected = $(this).val();
            $asnSelect.data('select2').options.options.placeholder = skpdSelected
                ? '-- Cari ASN berdasarkan SKPD terpilih --'
                : '-- Pilih SKPD dahulu --';
        });
    });
</script>
@endpush
