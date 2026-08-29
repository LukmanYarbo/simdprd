@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Penanda Tangan', 'url' => route('admin.penanda-tangan.index'), 'icon' => 'ti ti-signature'],
    ['label' => 'Tambah', 'icon' => 'ti ti-plus']
]" />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}" />
    <style>.select2-container { width: 100% !important; }</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="modern-page-header">
        <div class="header-left">
            <h2 class="h4">Tambah Penanda Tangan</h2>
            <p>Tetapkan penanda tangan per dokumen & SKPD</p>
        </div>
        <a href="{{ route('admin.penanda-tangan.index') }}" class="btn-modern-ghost"><i class="ti ti-arrow-left"></i> Kembali</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-form-card">
                <div class="form-card-header">
                    <div class="icon-box"><i class="ti ti-signature"></i></div>
                    <div class="header-text"><h5>Form Penanda Tangan</h5><small>Pilih SKPD lalu sesuaikan penanda tangan</small></div>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('admin.penanda-tangan.store') }}" method="POST">
                        @csrf
                        <div class="form-section">
                            <div class="section-title"><i class="ti ti-building"></i> SKPD Penanda Tangan</div>
                            <div class="modern-input">
                                <label for="id_skpd" class="form-label">SKPD <span class="text-danger">*</span></label>
                                <select name="id_skpd" id="id_skpd" class="form-select @error('id_skpd') is-invalid @enderror" required>
                                    <option value="">-- Cari & Pilih SKPD --</option>
                                </select>
                                @error('id_skpd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div id="container-anggota" class="form-section" style="display: none;">
                            <div class="section-title"><i class="ti ti-users"></i> Anggota DPRD</div>
                            <div class="modern-input">
                                <label for="id_anggota" class="form-label">Anggota DPRD (Ketua/Wakil) <span class="text-danger">*</span></label>
                                <select name="id_anggota" id="id_anggota" class="form-select @error('id_anggota') is-invalid @enderror">
                                    <option value="">-- Pilih Anggota --</option>
                                    @foreach($anggota as $a)
                                        <option value="{{ $a->id }}" {{ old('id_anggota') == $a->id ? 'selected' : '' }}>{{ $a->nama_anggota }} — {{ $a->jabatan->nama ?? '-' }}</option>
                                    @endforeach
                                </select>
                                @error('id_anggota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div id="container-asn" class="form-section" style="display: none;">
                            <div class="section-title"><i class="ti ti-shield"></i> Pegawai ASN</div>
                            <div class="modern-input">
                                <label for="id_pegawai_asn" class="form-label">Penanda Tangan ASN <span class="text-danger">*</span></label>
                                <select name="id_pegawai_asn" id="id_pegawai_asn" class="form-select @error('id_pegawai_asn') is-invalid @enderror">
                                    <option value="">-- Pilih ASN --</option>
                                </select>
                                @error('id_pegawai_asn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title"><i class="ti ti-file-text"></i> Jenis Dokumen</div>
                            <div class="modern-input">
                                <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                                <select name="jenis_dokumen[]" id="jenis_dokumen" class="form-select @error('jenis_dokumen') is-invalid @enderror" multiple required>
                                    @foreach(['Surat Tugas','SPPD','Surat Keputusan','Pengajuan Gaji'] as $jenis)
                                        <option value="{{ $jenis }}" {{ is_array(old('jenis_dokumen')) && in_array($jenis, old('jenis_dokumen')) ? 'selected' : '' }}>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Dapat memilih lebih dari satu jenis dokumen.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.penanda-tangan.index') }}" class="btn-modern-ghost">Batal</a>
                            <button type="submit" class="btn-modern-primary"><i class="ti ti-device-floppy"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        function toggleInputs() {
            var skpdText = $('#id_skpd').select2('data')[0] ? $('#id_skpd').select2('data')[0].text : '';
            var skpdVal = $('#id_skpd').val();
            if (skpdVal) {
                if (skpdText === 'Dewan Perwakilan Rakyat Daerah') {
                    $('#container-anggota').show(); $('#container-asn').hide(); $('#id_pegawai_asn').val(null).trigger('change');
                } else {
                    $('#container-anggota').hide(); $('#container-asn').show(); $('#id_anggota').val('').trigger('change');
                }
            } else { $('#container-anggota').hide(); $('#container-asn').hide(); }
        }
        $('#jenis_dokumen').select2({ theme: 'bootstrap-5', placeholder: '-- Pilih Jenis Dokumen --', allowClear: true });
        $('#id_skpd').select2({
            theme: 'bootstrap-5', placeholder: '-- Cari & Pilih SKPD --', allowClear: true, minimumInputLength: 0,
            ajax: { url: '{{ route("admin.penanda-tangan.search-skpd") }}', dataType: 'json', delay: 300, data: params=>({q: params.term || ''}), processResults: data=>({results: data.results}), cache: true }
        }).on('change', toggleInputs);
        $('#id_pegawai_asn').select2({
            theme: 'bootstrap-5', placeholder: '-- Pilih ASN --', allowClear: true, minimumInputLength: 0,
            ajax: { url: '{{ route("admin.penanda-tangan.search-asn") }}', dataType: 'json', delay: 300, data: params=>({q: params.term || '', id_skpd: $('#id_skpd').val()}), processResults: data=>({results: data.results}), cache: false }
        });
        toggleInputs();
        $('#id_skpd').on('select2:select', toggleInputs);
    });
</script>
@endpush
