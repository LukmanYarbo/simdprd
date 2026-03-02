<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Anggota - {{ $anggota->nama_anggota }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.5; color: #000; margin: 0; padding: 0; }
        .header { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; height: auto; margin-right: 20px; }
        .header-text { flex-grow: 1; text-align: center; }
        .header-text h1 { margin: 0; font-size: 15pt; text-transform: uppercase; white-space: nowrap; }
        .header-text h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header-text p { margin: 5px 0 0 0; font-size: 10pt; font-style: italic; }
        .title { text-align: center; margin-bottom: 30px; }
        .title h3 { margin: 0; text-decoration: underline; text-transform: uppercase; }
        .content { margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; background-color: #f2f2f2; padding: 5px; border: 1px solid #000; font-size: 11pt; }
        table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11pt; }
        table.info-table td { padding: 4px 8px; vertical-align: top; border: none; }
        table.info-table td:first-child { width: 25%; font-weight: bold; }
        table.info-table td:nth-child(2) { width: 2%; text-align: center; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11pt; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .no-print { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        @media print { .no-print { display: none; } body { padding: 0; } }
        .btn-print { padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .photo-container { float: right; margin: 0 0 15px 15px; border: 1px solid #000; padding: 2px; }
        .photo-container img { width: 113px; height: 151px; object-fit: cover; } /* 3x4 ratio roughly */
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Cetak Sekarang</button>
    </div>

    <div class="header">
        @if($pemda && $pemda->logo_pemda)
            <img src="{{ asset('storage/' . $pemda->logo_pemda) }}" class="logo" alt="Logo">
        @else
            <div class="logo" style="width: 80px; height: 80px; border: 1px solid #000; display:flex; align-items:center; justify-content:center;">LOGO</div>
        @endif
        <div class="header-text">
            <h1>{{ $pemda->namapemda ?? 'PEMERINTAH DAERAH' }}</h1>
            <h2>DEWAN PERWAKILAN RAKYAT DAERAH</h2>
            <p>{{ $pemda->alamat ?? '' }} {{ $pemda->kabupaten ?? '' }} {{ $pemda->propinsi ?? '' }}</p>
        </div>
    </div>

    <div class="title">
        <h3>BIODATA ANGGOTA DPRD</h3>
    </div>

    <div class="content clearfix">
        <div class="photo-container">
            @if($anggota->foto_anggota)
                <img src="{{ asset('storage/' . $anggota->foto_anggota) }}" alt="Foto">
            @else
                <div style="width: 113px; height: 151px; display:flex; align-items:center; justify-content:center; background:#eee;">Pas Foto 3x4</div>
            @endif
        </div>

        <div class="section-title" style="margin-top:0;">I. Keterangan Pribadi</div>
        <table class="info-table">
            <tr><td>Nama Lengkap</td><td>:</td><td>{{ $anggota->nama_anggota }}</td></tr>
            <tr><td>NIK</td><td>:</td><td>{{ $anggota->nik }}</td></tr>
            <tr><td>No. KK</td><td>:</td><td>{{ $anggota->nokk }}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $anggota->tempat_lahir }}, {{ $anggota->tgl_lahir ? $anggota->tgl_lahir->format('d-m-Y') : '' }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $anggota->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
            <tr><td>Agama</td><td>:</td><td>{{ $anggota->agama->nama_agama ?? '-' }}</td></tr>
            <tr><td>Status Perkawinan</td><td>:</td><td>{{ $anggota->statusKawin->status ?? '-' }}</td></tr>
            <tr><td>Alamat Lengkap</td><td>:</td><td>{{ $anggota->alamat_lengkap }}, Desa/Kel: {{ $anggota->desa }}, Kec: {{ $anggota->kec }}, Kab/Kota: {{ $anggota->kab }}, Prov: {{ $anggota->prov }}</td></tr>
            <tr><td>No. Telepon/HP</td><td>:</td><td>{{ $anggota->no_telp }}</td></tr>
            <tr><td>Email</td><td>:</td><td>{{ $anggota->email }}</td></tr>
            <tr><td>No. Rekening</td><td>:</td><td>{{ $anggota->no_rekening ?? '-' }}</td></tr>
            <tr><td>No. NPWP</td><td>:</td><td>{{ $anggota->no_npwp ?? '-' }}</td></tr>
        </table>

        <div class="section-title">II. Riwayat Pendidikan</div>
        @if($anggota->pendidikan && $anggota->pendidikan->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tingkat Pendidikan</th>
                   <th>Nama Institusi</th>
                    <th style="width: 150px;">Tahun Lulus</th>
                    <th style="width: 150px;">No. Ijazah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota->pendidikan->sortByDesc('tahun_lulus') as $index => $pendidikan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $pendidikan->jenisPendidikan->nama }}</td>
                    <td>{{ $pendidikan->tempat_pendidikan }}</td>
                    <td style="text-align: center;">{{ $pendidikan->tahun_lulus }}</td>
                    <td style="text-align: center;">{{ $pendidikan->no_ijazah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada data riwayat pendidikan.</p>
        @endif

        <div class="section-title">III. Data Keluarga</div>
        @if($anggota->keluarga && $anggota->keluarga->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Anggota Keluarga</th>
                    <th>Hubungan</th>
                    <th>Pekerjaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota->keluarga as $index => $kel)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $kel->nama }}</td>
                    <td style="text-align: center;">{{ $kel->ikatanKeluarga->nama }}</td>
                    <td>{{ $kel->pekerjaan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada data keluarga.</p>
        @endif

        <div class="section-title">IV. Riwayat Jabatan DPRD & Alat Kelengkapan</div>
        <table class="info-table">
            <tr><td>Status Keanggotaan</td><td>:</td><td>{{ $anggota->statusKeanggotaan->nama ?? '-' }}</td></tr>
            <tr><td>Fraksi / Jabatan</td><td>:</td><td>{{ $anggota->jabatan->nama_jabatan ?? '-' }}</td></tr>
            <tr><td>Masa Jabatan</td><td>:</td><td>{{ $anggota->tgl_mulai ? $anggota->tgl_mulai->format('d-m-Y') : '' }} s/d {{ $anggota->tgl_berhenti ? $anggota->tgl_berhenti->format('d-m-Y') : 'Sekarang' }}</td></tr>
        </table>
        
        @if($anggota->jabatanAnggota && $anggota->jabatanAnggota->count() > 0)
        <table class="data-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Alat Kelengkapan</th>
                    <th>Jabatan</th>
                    <th>Masa Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota->jabatanAnggota->sortByDesc('tgl_mulai') as $index => $ja)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="padding-left: 10px;">
                        {{ $ja->alatKelengkapan->nama ?? '-' }}
                        @if($ja->nama_komisi)
                            ({{ $ja->nama_komisi }})
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $ja->jabatanAlatKelengkapan->nama ?? '-' }}</td>
                    <td style="text-align: center;">{{ $ja->tgl_mulai ? \Carbon\Carbon::parse($ja->tgl_mulai)->format('d-m-Y') : '-' }} - {{ $ja->tgl_berhenti ? \Carbon\Carbon::parse($ja->tgl_berhenti)->format('d-m-Y') : 'Sekarang' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <br>
        <div style="text-align: right; float: right; width: 250px; page-break-inside: avoid;">
            <p>{{ $pemda->kota ?? ($pemda->kabupaten ?? '...') }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 70px;">Mengetahui,<br><strong>Sekretaris DPRD</strong></p>
            <p><strong>( ________________________ )</strong><br>NIP. .........................</p>
        </div>
    </div>
</body>
</html>
