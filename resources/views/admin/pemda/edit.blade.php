@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Data Pemda', 'url' => route('admin.pemda.index'), 'icon' => 'ti ti-building-community'],
    ['label' => 'Edit Data', 'icon' => 'ti ti-edit']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="modern-page-header">
        <div class="header-left">
            <h2 class="h4">Edit Data Pemda</h2>
            <p>Perbarui informasi pemerintah daerah</p>
        </div>
        <a href="{{ route('admin.pemda.index') }}" class="btn-modern-ghost"><i class="ti ti-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('admin.pemda.update', $pemda->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="modern-form-card mb-4">
            <div class="form-card-header">
                <div class="icon-box"><i class="ti ti-building"></i></div>
                <div class="header-text"><h5>Identitas Pemda</h5><small>Edit informasi dasar</small></div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6 modern-input">
                        <label for="namapemda" class="form-label">Nama Pemda <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('namapemda') is-invalid @enderror" id="namapemda" name="namapemda" value="{{ old('namapemda', $pemda->namapemda) }}" required>
                        @error('namapemda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 modern-input">
                        <label for="logo_pemda" class="form-label">Logo Pemda</label>
                        <input type="file" class="form-control @error('logo_pemda') is-invalid @enderror" id="logo_pemda" name="logo_pemda" accept="image/*">
                        @if($pemda->logo_pemda)
                            <div class="mt-2"><img src="{{ Storage::url($pemda->logo_pemda) }}" alt="Logo Pemda" class="img-thumbnail" style="max-height: 100px; border-radius:.75rem;"></div>
                        @endif
                        @error('logo_pemda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 modern-input">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pemda->alamat) }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3 modern-input"><label for="kota" class="form-label">Kota <span class="text-danger">*</span></label><input type="text" class="form-control @error('kota') is-invalid @enderror" id="kota" name="kota" value="{{ old('kota', $pemda->kota) }}" required>@error('kota')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-3 modern-input"><label for="kabupaten" class="form-label">Kabupaten <span class="text-danger">*</span></label><input type="text" class="form-control @error('kabupaten') is-invalid @enderror" id="kabupaten" name="kabupaten" value="{{ old('kabupaten', $pemda->kabupaten) }}" required>@error('kabupaten')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-3 modern-input"><label for="propinsi" class="form-label">Provinsi <span class="text-danger">*</span></label><input type="text" class="form-control @error('propinsi') is-invalid @enderror" id="propinsi" name="propinsi" value="{{ old('propinsi', $pemda->propinsi) }}" required>@error('propinsi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-3 modern-input"><label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label><input type="text" class="form-control @error('kode_pos') is-invalid @enderror" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $pemda->kode_pos) }}" required>@error('kode_pos')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                </div>
            </div>
        </div>

        <div class="modern-form-card mb-4">
            <div class="form-card-header">
                <div class="icon-box" style="background: linear-gradient(135deg,#10b981,#059669);"><i class="ti ti-crown"></i></div>
                <div class="header-text"><h5>Kepala Daerah</h5><small>Data Bupati & Wakil Bupati</small></div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4 modern-input"><label for="nama_bupati" class="form-label">Nama Bupati <span class="text-danger">*</span></label><input type="text" class="form-control @error('nama_bupati') is-invalid @enderror" id="nama_bupati" name="nama_bupati" value="{{ old('nama_bupati', $pemda->nama_bupati) }}" required>@error('nama_bupati')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4 modern-input"><label for="jabatan_bupati" class="form-label">Jabatan Bupati <span class="text-danger">*</span></label><input type="text" class="form-control @error('jabatan_bupati') is-invalid @enderror" id="jabatan_bupati" name="jabatan_bupati" value="{{ old('jabatan_bupati', $pemda->jabatan_bupati) }}" required>@error('jabatan_bupati')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4 modern-input"><label for="judul_bupati" class="form-label">Sebutan Bupati <span class="text-danger">*</span></label><input type="text" class="form-control @error('judul_bupati') is-invalid @enderror" id="judul_bupati" name="judul_bupati" value="{{ old('judul_bupati', $pemda->judul_bupati) }}" required>@error('judul_bupati')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4 modern-input"><label for="nama_wakil_bupati" class="form-label">Nama Wakil Bupati <span class="text-danger">*</span></label><input type="text" class="form-control @error('nama_wakil_bupati') is-invalid @enderror" id="nama_wakil_bupati" name="nama_wakil_bupati" value="{{ old('nama_wakil_bupati', $pemda->nama_wakil_bupati) }}" required>@error('nama_wakil_bupati')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4 modern-input"><label for="jabatan_wakil_bupati" class="form-label">Jabatan Wakil Bupati <span class="text-danger">*</span></label><input type="text" class="form-control @error('jabatan_wakil_bupati') is-invalid @enderror" id="jabatan_wakil_bupati" name="jabatan_wakil_bupati" value="{{ old('jabatan_wakil_bupati', $pemda->jabatan_wakil_bupati) }}" required>@error('jabatan_wakil_bupati')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4 modern-input"><label for="judul_wakil_bupati" class="form-label">Sebutan Wakil Bupati <span class="text-danger">*</span></label><input type="text" class="form-control @error('judul_wakil_bupati') is-invalid @enderror" id="judul_wakil_bupati" name="judul_wakil_bupati" value="{{ old('judul_wakil_bupati', $pemda->judul_wakil_bupati) }}" required>@error('judul_wakil_bupati')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                </div>
            </div>
        </div>

        <div class="modern-form-card mb-4">
            <div class="form-card-header">
                <div class="icon-box" style="background: linear-gradient(135deg,#0ea5e9,#0284c7);"><i class="ti ti-shield"></i></div>
                <div class="header-text"><h5>Sekretaris Daerah</h5><small>Pilih Sekda dari data Pegawai ASN</small></div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6 modern-input">
                        <label for="id_sekda" class="form-label">Pilih Sekda</label>
                        <select class="form-select select2" id="id_sekda" name="id_sekda">
                            <option value="">Pilih Pegawai</option>
                            @foreach($pegawai as $p)
                                <option value="{{ $p->id }}" {{ old('id_sekda', $pemda->id_sekda) == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->nip }})</option>
                            @endforeach
                        </select>
                        @error('id_sekda') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="row g-3 mt-1 p-3 rounded-3 mx-0" style="background: var(--bs-tertiary-bg); border:1px solid var(--bs-border-color);">
                    <div class="col-md-4"><label class="form-label fw-bold small">NIP</label><input type="text" class="form-control-plaintext" id="preview_nip" readonly value="-"></div>
                    <div class="col-md-4"><label class="form-label fw-bold small">Jabatan</label><input type="text" class="form-control-plaintext" id="preview_jabatan" readonly value="-"></div>
                    <div class="col-md-4"><label class="form-label fw-bold small">Pangkat/Golongan</label><input type="text" class="form-control-plaintext" id="preview_pangkat" readonly value="-"></div>
                </div>
            </div>
            <div class="form-card-footer">
                <a href="{{ route('admin.pemda.index') }}" class="btn-modern-ghost">Batal</a>
                <button type="submit" class="btn-modern-primary" style="background: linear-gradient(135deg,#f59e0b,#d97706);"><i class="ti ti-check"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap-5', placeholder: "Pilih Pegawai", allowClear: true });
        function fetchPegawaiDetails(id) {
            if(id) {
                fetch(`/admin/pemda/pegawai-details/${id}`).then(r=>r.json()).then(data=>{
                    if(data.error) return;
                    $('#preview_nip').val(data.nip); $('#preview_jabatan').val(data.jabatan); $('#preview_pangkat').val(data.pangkat);
                });
            } else { $('#preview_nip').val('-'); $('#preview_jabatan').val('-'); $('#preview_pangkat').val('-'); }
        }
        var initialId = $('#id_sekda').val(); if(initialId) fetchPegawaiDetails(initialId);
        $('#id_sekda').on('change', function(){ fetchPegawaiDetails($(this).val()); });
    });
</script>
@endpush
