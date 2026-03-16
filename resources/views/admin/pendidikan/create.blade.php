@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Tambah Pendidikan</h2>
            <p class="text-muted mb-0">Anggota: {{ $anggota->nama_anggota }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.pendidikan.index', $anggota->id) }}" class="btn btn-secondary shadow-sm">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pendidikan.store', $anggota->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tingkat Pendidikan <span class="text-danger">*</span></label>
                        <select name="id_jenis_pendidikan" class="form-select @error('id_jenis_pendidikan') is-invalid @enderror" required>
                            <option value="">Pilih Tingkat Pendidikan</option>
                            @foreach($jenisPendidikan as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_jenis_pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Institusi / Tempat Pendidikan <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_pendidikan" class="form-control @error('tempat_pendidikan') is-invalid @enderror" required>
                        @error('tempat_pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control @error('tahun_masuk') is-invalid @enderror" placeholder="Ex: 2010">
                        @error('tahun_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="form-control @error('tahun_lulus') is-invalid @enderror" placeholder="Ex: 2014">
                        @error('tahun_lulus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Induk / NISN / NIM</label>
                        <input type="text" name="no_induk" class="form-control @error('no_induk') is-invalid @enderror">
                        @error('no_induk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror">
                        @error('jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control @error('program_studi') is-invalid @enderror">
                        @error('program_studi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fakultas</label>
                        <input type="text" name="fakultas" class="form-control @error('fakultas') is-invalid @enderror">
                        @error('fakultas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Ijazah</label>
                        <input type="text" name="no_ijazah" class="form-control @error('no_ijazah') is-invalid @enderror">
                        @error('no_ijazah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">File Ijazah (PDF/JPG, Max 2MB)</label>
                        <input type="file" name="file_ijazah" class="form-control @error('file_ijazah') is-invalid @enderror">
                        @error('file_ijazah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 text-end">
                        <button type="reset" class="btn btn-light border me-2">Reset</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-save me-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
