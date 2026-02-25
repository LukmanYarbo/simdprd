@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Surat Keputusan', 'url' => route('admin.surat-keputusan.index'), 'icon' => 'bi-file-earmark-text'],
    ['label' => 'Detail', 'icon' => 'bi-eye']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header  py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Detail Surat Keputusan</h5>
                    <a href="{{ route('admin.surat-keputusan.index') }}" class="btn btn-sm btn-light border">Kembali</a>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">No. SK</th>
                            <td>{{ $suratKeputusan->no_sk }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal SK</th>
                            <td>{{ $suratKeputusan->tgl_sk->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Alat Kelengkapan</th>
                            <td>{{ $suratKeputusan->alatKelengkapan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($suratKeputusan->status == 'A')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $suratKeputusan->ket_sk ?? '-' }}</td>
                        </tr>
                         <tr>
                            <th>File SK</th>
                            <td>
                                @if($suratKeputusan->file_sk)
                                    <a href="{{ asset('storage/' . $suratKeputusan->file_sk) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download me-1"></i> Unduh File
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
