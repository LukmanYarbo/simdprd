@extends('layouts.admin')

@section('title', 'Detail Pegawai ASN')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pegawai ASN</h1>
        <a href="{{ route('admin.pegawai-asn.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-5">
                    <div class="mb-4 position-relative d-inline-block">
                        @if($pegawaiAsn->foto)
                            <img src="{{ asset('storage/' . $pegawaiAsn->foto) }}" alt="Foto Pegawai" class="rounded-circle shadow-lg img-thumbnail" style="width: 180px; height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-body-secondary rounded-circle d-flex align-items-center justify-content-center text-secondary mx-auto shadow-sm" style="width: 180px; height: 180px; font-size: 5rem;">
                                <i class="ti ti-person-fill"></i>
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 bg-{{ $pegawaiAsn->jenis_kelamin == 'L' ? 'primary' : 'danger' }} border border-white rounded-circle p-2" title="{{ $pegawaiAsn->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}">
                            <i class="ti ti-gender-{{ $pegawaiAsn->jenis_kelamin == 'L' ? 'male' : 'female' }} text-white"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold text-body mb-1">{{ $pegawaiAsn->nama }}</h4>
                    <p class="text-primary fw-medium mb-1">{{ $pegawaiAsn->jabatanAsn->nama_jabatan ?? '-' }}</p>
                    <p class="text-secondary small mb-4">NIP. {{ $pegawaiAsn->nip }}</p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pegawai-asn.edit', $pegawaiAsn->id) }}" class="btn btn-primary">
                            <i class="ti ti-pencil me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3  border-bottom d-flex align-items-center">
                    <i class="ti ti-info-circle-fill text-primary me-2 fs-5"></i>
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Lengkap</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary" style="width: 30%">NIK</th>
                                    <td class="py-3">{{ $pegawaiAsn->nik }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">No. Kartu Keluarga</th>
                                    <td class="py-3">{{ $pegawaiAsn->nokk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Tempat, Tanggal Lahir</th>
                                    <td class="py-3">{{ $pegawaiAsn->tempat_lahir }}, {{ $pegawaiAsn->tgl_lahir }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Jenis Kelamin</th>
                                    <td class="py-3">
                                        @if($pegawaiAsn->jenis_kelamin == 'L')
                                            <span class="badge bg-primary-subtle text-primary"><i class="ti ti-gender-male me-1"></i> Laki-laki</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger"><i class="ti ti-gender-female me-1"></i> Perempuan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Agama</th>
                                    <td class="py-3">{{ $pegawaiAsn->agama->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Status Perkawinan</th>
                                    <td class="py-3">{{ $pegawaiAsn->statusKawin->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Pangkat / Golongan</th>
                                    <td class="py-3">{{ $pegawaiAsn->pangkatGolongan->pangkat ?? '-' }} <span class="badge bg-secondary ms-1">{{ $pegawaiAsn->pangkatGolongan->golongan ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Penanda Tangan Dokumen</th>
                                    <td class="py-3">
                                        @if($pegawaiAsn->id_ttd == 'Y')
                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-circle-check me-1"></i> Ya</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary"><i class="ti ti-circle-x me-1"></i> Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">Tanggal Mulai Kerja</th>
                                    <td class="py-3">{{ $pegawaiAsn->tanggal_mulai_kerja ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header py-3  border-bottom d-flex align-items-center">
                    <i class="ti ti-envelope-paper-fill text-success me-2 fs-5"></i>
                    <h6 class="m-0 font-weight-bold text-success">Kontak & Finansial</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary" style="width: 30%">Email</th>
                                    <td class="py-3">{{ $pegawaiAsn->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">No. HP</th>
                                    <td class="py-3">{{ $pegawaiAsn->nohp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">No. Rekening</th>
                                    <td class="py-3">{{ $pegawaiAsn->norek ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 bg-body-tertiary">NPWP</th>
                                    <td class="py-3">{{ $pegawaiAsn->npwp ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
