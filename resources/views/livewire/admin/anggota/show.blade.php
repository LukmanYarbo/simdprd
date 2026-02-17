<div>
    <div class="row g-4">
        <!-- Profile Header -->
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="bg-primary py-5"></div>
                <div class="card-body pt-0 px-4">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end mt-n5" style="margin-top: -60px;">
                        <div class="position-relative">
                            @if($anggota->foto_anggota)
                                <img src="{{ asset('storage/' . $anggota->foto_anggota) }}" class="rounded-circle border border-4 border-white shadow" width="120" height="120" alt="">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $anggota->nama_anggota }}&background=random&size=120" class="rounded-circle border border-4 border-white shadow" width="120" height="120" alt="">
                            @endif
                        </div>
                        <div class="ms-md-4 mt-3 mt-md-0 text-center text-md-start flex-grow-1">
                            <h3 class="fw-bold mb-1">{{ $anggota->nama_anggota }}</h3>
                            <p class="text-muted mb-2"><i class="bi bi-person-badge me-2"></i>{{ $anggota->nik }}</p>
                            <span class="badge bg-primary px-3 py-2 fs-6">{{ $anggota->jabatan->nama }}</span>
                            <span class="badge bg-{{ $anggota->statusKeanggotaan->nama == 'Aktif' ? 'success' : 'warning' }} px-3 py-2 fs-6 ms-2">{{ $anggota->statusKeanggotaan->nama }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Lengkap</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">NIK</label>
                            <p class="fw-semibold mb-0">{{ $anggota->nik }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">No. Kartu Keluarga (KK)</label>
                            <p class="fw-semibold mb-0">{{ $anggota->nokk }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Tempat, Tanggal Lahir</label>
                            <p class="fw-semibold mb-0">{{ $anggota->tempat_lahir }}, {{ $anggota->tgl_lahir->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Jenis Kelamin</label>
                            <p class="fw-semibold mb-0">{{ $anggota->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Agama</label>
                            <p class="fw-semibold mb-0">{{ $anggota->agama->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Status Kawin</label>
                            <p class="fw-semibold mb-0">{{ $anggota->statusKawin->nama }} ({{ $anggota->jmlh_istri }} Istri, {{ $anggota->jmlh_anak }} Anak)</p>
                        </div>
                        <hr class="my-2">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Email</label>
                            <p class="fw-semibold mb-0">{{ $anggota->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">No. Telp / HP</label>
                            <p class="fw-semibold mb-0">{{ $anggota->no_telp }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block">Alamat</label>
                            <p class="fw-semibold mb-0">{{ $anggota->alamat_lengkap }}, {{ $anggota->desa }}, {{ $anggota->kec }}, {{ $anggota->kab }}, {{ $anggota->prov }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2"></i>Asuransi & Tunjangan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted border-0 ps-0">BPJS Kesehatan</td>
                                    <td class="border-0 fw-semibold text-end">
                                        @if($anggota->status_bpjs == 'Y')
                                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Aktif ({{ $anggota->no_bpjs }})</span>
                                        @else
                                            <span class="text-muted">Tidak Terdaftar</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">JKK (Jaminan Kecelakaan Kerja)</td>
                                    <td class="fw-semibold text-end">
                                        @if($anggota->status_jkk == 'Y')
                                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Aktif ({{ $anggota->no_jkk }})</span>
                                        @else
                                            <span class="text-muted">Tidak Terdaftar</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">JKM (Jaminan Kematian)</td>
                                    <td class="fw-semibold text-end">
                                        @if($anggota->status_jkm == 'Y')
                                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Aktif ({{ $anggota->no_jkm }})</span>
                                        @else
                                            <span class="text-muted">Tidak Terdaftar</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0">Tunjangan Perumahan</td>
                                    <td class="fw-semibold text-end">
                                        {!! $anggota->status_tjgn_perum == 'Y' ? '<span class="text-success">Menerima</span>' : '<span class="text-muted">Tidak Menerima</span>' !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-0 border-0">Tunjangan Transport</td>
                                    <td class="fw-semibold text-end border-0">
                                        {!! $anggota->status_tjgn_transport == 'Y' ? '<span class="text-success">Menerima</span>' : '<span class="text-muted">Tidak Menerima</span>' !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2"></i>Jabatan Alat Kelengkapan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="border-0 ps-3 rounded-start">Alat Kelengkapan</th>
                                    <th class="border-0">Jabatan</th>
                                    <th class="border-0 rounded-end">SK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($anggota->jabatanAnggota as $ja)
                                <tr>
                                    <td class="ps-3 border-bottom-0">
                                        <div class="fw-semibold">{{ $ja->alatKelengkapan->nama }}</div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">{{ $ja->jabatanAlatKelengkapan->nama }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="small fw-semibold">{{ $ja->suratKeputusan->no_sk }}</div>
                                        <div class="small text-muted">{{ \Carbon\Carbon::parse($ja->suratKeputusan->tgl_sk)->translatedFormat('d F Y') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3 border-bottom-0">
                                        Belum menjabat di alat kelengkapan manapun.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-briefcase me-2"></i>Keanggotaan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Tanggal Mulai</label>
                        <p class="fw-semibold mb-0">{{ $anggota->tgl_mulai->format('d M Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Tanggal Berhenti</label>
                        <p class="fw-semibold mb-0">{{ $anggota->tgl_berhenti ? $anggota->tgl_berhenti->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Nomor Rekening</label>
                        <p class="fw-semibold mb-0">{{ $anggota->no_rekening }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
