@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Riwayat Pendidikan</h2>
            <p class="text-muted mb-0">Anggota: {{ $anggota->nama_anggota }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary shadow-sm me-2">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('admin.pendidikan.create', $anggota->id) }}" class="btn btn-primary shadow-sm">
                <i class="ti ti-plus-lg me-2"></i>Tambah Pendidikan
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary text-muted">
                        <tr>
                            <th>Tingkat</th>
                            <th>Nama Institusi</th>
                            <th>Tahun Masuk</th>
                            <th>Tahun Lulus</th>
                            <th>Jurusan/Prodi</th>
                            <th>Ijazah</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendidikan as $item)
                        <tr>
                            <td>{{ $item->jenisPendidikan->nama }}</td>
                            <td>{{ $item->tempat_pendidikan }}</td>
                            <td>{{ $item->tahun_masuk ?? '-' }}</td>
                            <td>{{ $item->tahun_lulus ?? '-' }}</td>
                            <td>
                                {{ $item->jurusan ?? '-' }}
                                @if($item->program_studi) <br><small class="text-muted">{{ $item->program_studi }}</small> @endif
                            </td>
                            <td>
                                @if($item->file_ijazah)
                                <a href="{{ asset('storage/'.$item->file_ijazah) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-file-earmark-pdf"></i> Lihat
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pendidikan.edit', $item->id) }}" class="btn btn-sm btn-light border-end text-warning">
                                    <i class="ti ti-pencil"></i>
                                </a>
                                <form action="{{ route('admin.pendidikan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pendidikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
