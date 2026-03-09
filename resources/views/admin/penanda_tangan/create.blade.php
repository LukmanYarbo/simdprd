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

                        {{-- SKPD (Select2 AJAX) --}}
                        <div class="mb-4">
                            <label for="id_skpd" class="form-label fw-semibold">
                                SKPD Penanda Tangan <span class="text-danger">*</span>
                            </label>
                            <select name="id_skpd" id="id_skpd"
                                class="form-select @error('id_skpd') is-invalid @enderror" required>
                                <option value="">-- Cari & Pilih SKPD --</option>
                            </select>
                            @error('id_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Section: Anggota DPRD (Hidden by default) --}}
                        <div id="container-anggota" class="mb-4" style="display: none;">
                            <label for="id_anggota" class="form-label fw-semibold">
                                Anggota DPRD
                                <small class="text-muted fw-normal">(Ketua / Wakil Ketua DPRD)</small> <span class="text-danger">*</span>
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

                        {{-- Section: Pegawai ASN (Hidden by default) --}}
                        <div id="container-asn" class="mb-4" style="display: none;">
                            <label for="id_pegawai_asn" class="form-label fw-semibold">
                                Penanda Tangan ASN <span class="text-danger">*</span>
                            </label>
                            <select name="id_pegawai_asn" id="id_pegawai_asn"
                                class="form-select @error('id_pegawai_asn') is-invalid @enderror">
                                <option value="">-- Pilih ASN --</option>
                            </select>
                            @error('id_pegawai_asn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Jenis Dokumen (Bottom) --}}
                        <div class="mb-4">
                            <label for="jenis_dokumen" class="form-label fw-semibold">
                                Jenis Dokumen <span class="text-danger">*</span>
                            </label>
                            <select name="jenis_dokumen[]" id="jenis_dokumen"
                                class="form-select @error('jenis_dokumen') is-invalid @enderror" multiple required>
                                @foreach(['Surat Tugas', 'SPPD', 'Surat Keputusan','Pengajuan Gaji'] as $jenis)
                                    <option value="{{ $jenis }}" {{ is_array(old('jenis_dokumen')) && in_array($jenis, old('jenis_dokumen')) ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Anda dapat memilih lebih dari satu jenis dokumen.</div>
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

        function toggleInputs() {
            var skpdText = $('#id_skpd').select2('data')[0] ? $('#id_skpd').select2('data')[0].text : '';
            var skpdVal = $('#id_skpd').val();

            if (skpdVal) {
                if (skpdText === 'Dewan Perwakilan Rakyat Daerah') {
                    $('#container-anggota').show();
                    $('#container-asn').hide();
                    $('#id_pegawai_asn').val(null).trigger('change');
                } else {
                    $('#container-anggota').hide();
                    $('#container-asn').show();
                    $('#id_anggota').val('').trigger('change');
                }
            } else {
                $('#container-anggota').hide();
                $('#container-asn').hide();
            }
        }

        // === Select2: Jenis Dokumen (Multiple) ===
        $('#jenis_dokumen').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Jenis Dokumen --',
            allowClear: true
        });

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
        }).on('change', function() {
            toggleInputs();
        });

        // === Select2: Pegawai ASN (AJAX search, filtered by SKPD) ===
        var $asnSelect = $('#id_pegawai_asn').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih ASN --',
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

        // Initial check
        toggleInputs();

        // Also check when Select2 is initialized/loaded (for old input)
        $('#id_skpd').on('select2:select', function() {
            toggleInputs();
        });
    });
</script>
@endpush
