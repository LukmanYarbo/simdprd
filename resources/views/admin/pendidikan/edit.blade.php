@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Edit Pendidikan</h2>
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
            <form action="{{ route('admin.pendidikan.update', $pendidikan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tingkat Pendidikan <span class="text-danger">*</span></label>
                        <select name="id_jenis_pendidikan" class="form-select @error('id_jenis_pendidikan') is-invalid @enderror" required>
                            <option value="">Pilih Tingkat Pendidikan</option>
                            @foreach($jenisPendidikan as $jenis)
                                <option value="{{ $jenis->id }}" {{ $pendidikan->id_jenis_pendidikan == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_jenis_pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Institusi / Tempat Pendidikan <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_pendidikan" class="form-control @error('tempat_pendidikan') is-invalid @enderror" value="{{ old('tempat_pendidikan', $pendidikan->tempat_pendidikan) }}" required>
                        @error('tempat_pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control @error('tahun_masuk') is-invalid @enderror" value="{{ old('tahun_masuk', $pendidikan->tahun_masuk) }}" placeholder="Ex: 2010">
                        @error('tahun_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="form-control @error('tahun_lulus') is-invalid @enderror" value="{{ old('tahun_lulus', $pendidikan->tahun_lulus) }}" placeholder="Ex: 2014">
                        @error('tahun_lulus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Induk / NISN / NIM</label>
                        <input type="text" name="no_induk" class="form-control @error('no_induk') is-invalid @enderror" value="{{ old('no_induk', $pendidikan->no_induk) }}">
                        @error('no_induk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" value="{{ old('jurusan', $pendidikan->jurusan) }}">
                        @error('jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control @error('program_studi') is-invalid @enderror" value="{{ old('program_studi', $pendidikan->program_studi) }}">
                        @error('program_studi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fakultas</label>
                        <input type="text" name="fakultas" class="form-control @error('fakultas') is-invalid @enderror" value="{{ old('fakultas', $pendidikan->fakultas) }}">
                        @error('fakultas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Ijazah</label>
                        <input type="text" name="no_ijazah" class="form-control @error('no_ijazah') is-invalid @enderror" value="{{ old('no_ijazah', $pendidikan->no_ijazah) }}">
                        @error('no_ijazah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">File Ijazah (PDF/JPG, Max 2MB)</label>
                        <input type="file" name="file_ijazah" class="form-control @error('file_ijazah') is-invalid @enderror">
                        @if($pendidikan->file_ijazah)
                            <div class="form-text text-success"><i class="ti ti-check-circle-fill"></i> File saat ini: <a href="{{ asset('storage/'.$pendidikan->file_ijazah) }}" target="_blank">Lihat</a></div>
                        @endif
                        @error('file_ijazah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary"><i class="ti ti-save me-2"></i>Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
