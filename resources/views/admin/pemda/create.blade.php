@extends('layouts.admin')

@section('title', 'Tambah Data Pemda')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Tambah Data Pemda" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
        ['label' => 'Data Pemda', 'url' => route('admin.pemda.index'), 'icon' => 'bi-building'],
        ['label' => 'Tambah Data']
    ]" />

    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Pemda</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pemda.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <h5 class="mb-3 text-gray-800 border-bottom pb-2">Identitas Pemda</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="namapemda" class="form-label">Nama Pemda</label>
                        <input type="text" class="form-control @error('namapemda') is-invalid @enderror" id="namapemda" name="namapemda" value="{{ old('namapemda') }}" required>
                        @error('namapemda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="logo_pemda" class="form-label">Logo Pemda</label>
                        <input type="file" class="form-control @error('logo_pemda') is-invalid @enderror" id="logo_pemda" name="logo_pemda" accept="image/*">
                        @error('logo_pemda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text" class="form-control @error('kota') is-invalid @enderror" id="kota" name="kota" value="{{ old('kota') }}" required>
                        @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="kabupaten" class="form-label">Kabupaten</label>
                        <input type="text" class="form-control @error('kabupaten') is-invalid @enderror" id="kabupaten" name="kabupaten" value="{{ old('kabupaten') }}" required>
                        @error('kabupaten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="propinsi" class="form-label">Propinsi</label>
                        <input type="text" class="form-control @error('propinsi') is-invalid @enderror" id="propinsi" name="propinsi" value="{{ old('propinsi') }}" required>
                        @error('propinsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="kode_pos" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required>
                        @error('kode_pos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mb-3 mt-4 text-gray-800 border-bottom pb-2">Kepala Daerah</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="nama_bupati" class="form-label">Nama Bupati</label>
                        <input type="text" class="form-control @error('nama_bupati') is-invalid @enderror" id="nama_bupati" name="nama_bupati" value="{{ old('nama_bupati') }}" required>
                        @error('nama_bupati') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="jabatan_bupati" class="form-label">Jabatan Bupati</label>
                        <input type="text" class="form-control @error('jabatan_bupati') is-invalid @enderror" id="jabatan_bupati" name="jabatan_bupati" value="{{ old('jabatan_bupati') }}" required>
                        @error('jabatan_bupati') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="judul_bupati" class="form-label">Judul/Sebutan Bupati</label>
                        <input type="text" class="form-control @error('judul_bupati') is-invalid @enderror" id="judul_bupati" name="judul_bupati" value="{{ old('judul_bupati') }}" required>
                        @error('judul_bupati') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="nama_wakil_bupati" class="form-label">Nama Wakil Bupati</label>
                        <input type="text" class="form-control @error('nama_wakil_bupati') is-invalid @enderror" id="nama_wakil_bupati" name="nama_wakil_bupati" value="{{ old('nama_wakil_bupati') }}" required>
                        @error('nama_wakil_bupati') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="jabatan_wakil_bupati" class="form-label">Jabatan Wakil Bupati</label>
                        <input type="text" class="form-control @error('jabatan_wakil_bupati') is-invalid @enderror" id="jabatan_wakil_bupati" name="jabatan_wakil_bupati" value="{{ old('jabatan_wakil_bupati') }}" required>
                        @error('jabatan_wakil_bupati') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="judul_wakil_bupati" class="form-label">Judul/Sebutan Wakil Bupati</label>
                        <input type="text" class="form-control @error('judul_wakil_bupati') is-invalid @enderror" id="judul_wakil_bupati" name="judul_wakil_bupati" value="{{ old('judul_wakil_bupati') }}" required>
                        @error('judul_wakil_bupati') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mb-3 mt-4 text-gray-800 border-bottom pb-2">Sekretaris Daerah</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_sekda" class="form-label">Pilih Sekda (dari Data Pegawai ASN)</label>
                        <select class="form-select select2" id="id_sekda" name="id_sekda">
                            <option value="">Pilih Pegawai</option>
                            @foreach($pegawai as $p)
                                <option value="{{ $p->id }}" {{ old('id_sekda') == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->nip }})</option>
                            @endforeach
                        </select>
                        @error('id_sekda') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="row mb-3 p-3 bg-body-tertiary rounded mx-1">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">NIP</label>
                        <input type="text" class="form-control-plaintext" id="preview_nip" readonly value="-">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Jabatan</label>
                        <input type="text" class="form-control-plaintext" id="preview_jabatan" readonly value="-">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pangkat/Golongan</label>
                        <input type="text" class="form-control-plaintext" id="preview_pangkat" readonly value="-">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.pemda.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Pegawai",
            allowClear: true
        });

        $('#id_sekda').on('change', function() {
            var id = $(this).val();
            if(id) {
                // Fetch details
                fetch(`/admin/pemda/pegawai-details/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.error) {
                            console.error(data.error);
                            return;
                        }
                        $('#preview_nip').val(data.nip);
                        $('#preview_jabatan').val(data.jabatan);
                        $('#preview_pangkat').val(data.pangkat);
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                $('#preview_nip').val('-');
                $('#preview_jabatan').val('-');
                $('#preview_pangkat').val('-');
            }
        });
    });
</script>
@endpush
