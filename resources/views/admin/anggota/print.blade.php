<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Anggota - {{ $anggota->nama_anggota }}</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: legal; margin: 1cm 1.5cm; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            font-size: 10pt; 
            line-height: 1.2; 
            color: #1a202c; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact;
        }
        
        .header { 
            display: flex; 
            align-items: center; 
            border-bottom: 2.5px solid #000; 
            padding-bottom: 12px; 
            margin-bottom: 25px;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            border-bottom: 0.8px solid #000;
        }

        .logo { width: 75px; height: auto; margin-right: 25px; }
        
        .header-text { flex-grow: 1; text-align: center; }
        .header-text h1 { 
            font-family: 'Outfit', sans-serif;
            margin: 0; 
            font-size: 13pt; 
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase; 
            color: #000;
            white-space: nowrap;
        }
        .header-text h2 { 
            font-family: 'Outfit', sans-serif;
            margin: 2px 0 0 0; 
            font-size: 13pt; 
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase; 
            color: #000;
        }
        .header-text p { 
            margin: 6px 0 0 0; 
            font-size: 9pt; 
            font-weight: 400;
            color: #4a5568;
        }

        .title { text-align: center; margin-bottom: 35px; }
        .title h3 { 
            font-family: 'Outfit', sans-serif;
            margin: 0; 
            font-size: 12pt;
            font-weight: 700;
            text-decoration: underline; 
            text-transform: uppercase; 
            letter-spacing: 0.1em;
        }

        .section-title { 
            font-family: 'Outfit', sans-serif;
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 25px; 
            margin-bottom: 12px; 
            background-color: #f8fafc; 
            padding: 6px 10px; 
            border-left: 4px solid #000;
            font-size: 9.5pt;
            color: #1e293b;
        }

        table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.info-table td { padding: 5px 8px; vertical-align: top; border: none; font-size: 10pt; }
        table.info-table td:first-child { width: 28%; font-weight: 600; color: #475569; }
        table.info-table td:nth-child(2) { width: 3%; text-align: center; color: #94a3b8; }
        table.info-table td:last-child { color: #1e293b; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 10px 12px; font-size: 9.5pt; }
        table.data-table th { 
            background-color: #f1f5f9; 
            font-weight: 700; 
            text-align: center; 
            text-transform: uppercase;
            font-size: 8.5pt;
            letter-spacing: 0.05em;
            color: #475569;
        }

        .signature-box {
            text-align: right; 
            float: right; 
            width: 250px; 
            page-break-inside: avoid;
            margin-top: 40px;
        }
        .signature-box p { margin: 0; }
        .signature-date { margin-bottom: 15px; }
        .signature-title { margin-bottom: 80px; font-weight: 600; }
        .signature-name { font-weight: 700; text-decoration: underline; }

        .no-print { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        @media print { 
            .no-print { display: none; } 
            body { padding: 0; }
            .section-title { -webkit-print-color-adjust: exact; background-color: #f8fafc !important; }
            table.data-table th { background-color: #f1f5f9 !important; }
        }
        .btn-print { 
            padding: 12px 24px; 
            background: #0f172a; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transition: all 0.2s;
        }
        .btn-print:hover { background: #1e293b; transform: translateY(-1px); }
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

    <div class="content">

        <div class="section-title" style="margin-top:0;">I. Keterangan Pribadi</div>
        <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
            <tr>
                <td style="vertical-align:top; padding-right:12px;">
                    <table class="info-table">
                        <tr><td>Nama Lengkap</td><td>:</td><td>{{ $anggota->nama_anggota }}</td></tr>
                        <tr><td>NIK</td><td>:</td><td>{{ $anggota->nik }}</td></tr>
                        <tr><td>No. KK</td><td>:</td><td>{{ $anggota->nokk }}</td></tr>
                        <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $anggota->tempat_lahir }}, {{ $anggota->tgl_lahir ? $anggota->tgl_lahir->format('d-m-Y') : '' }}</td></tr>
                        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $anggota->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                        <tr><td>Agama</td><td>:</td><td>{{ $anggota->agama->nama ?? '-' }}</td></tr>
                        <tr><td>Status Perkawinan</td><td>:</td><td>{{ $anggota->statusKawin->nama ?? '-' }}</td></tr>
                        <tr><td>Alamat Lengkap</td><td>:</td><td>{{ $anggota->alamat_lengkap }}, Desa/Kel: {{ $anggota->desa }}, Kec: {{ $anggota->kec }}, Kab/Kota: {{ $anggota->kab }}, Prov: {{ $anggota->prov }}</td></tr>
                        <tr><td>No. Telepon/HP</td><td>:</td><td>{{ $anggota->no_telp }}</td></tr>
                        <tr><td>Email</td><td>:</td><td>{{ $anggota->email }}</td></tr>
                        <tr><td>No. Rekening</td><td>:</td><td>{{ $anggota->no_rekening ?? '-' }}</td></tr>
                        <tr><td>No. NPWP</td><td>:</td><td>{{ $anggota->no_npwp ?? '-' }}</td></tr>
                    </table>
                </td>
                <td style="vertical-align:top; width:125px; text-align:center;">
                    <div style="border:1px solid #000; padding:2px; display:inline-block;">
                        @if($anggota->foto_anggota)
                            <img src="{{ asset('storage/' . $anggota->foto_anggota) }}" alt="Foto" style="width:113px; height:151px; object-fit:cover; display:block;">
                        @else
                            <div style="width:113px; height:151px; display:flex; align-items:center; justify-content:center; background:#eee; font-size:9pt;">Pas Foto<br>3×4</div>
                        @endif
                    </div>
                </td>
            </tr>
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
            <tr><td>Fraksi / Jabatan</td><td>:</td><td>{{ $anggota->jabatan->nama ?? '-' }}</td></tr>
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

        <div class="section-title">V. Data Harta Anggota</div>
        @if($anggota->harta && $anggota->harta->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 25%;">Jenis Harta</th>
                    <th>Nama / Rincian Harta</th>
                    <th style="width: 120px;">Tahun</th>
                    <th style="width: 180px;">Harga Perolehan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota->harta->sortByDesc('tahun_perolehan') as $index => $h)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $h->jenis_harta }}</td>
                    <td>
                        {{ $h->nama_harta }}
                        @if($h->keterangan)
                            <br><small style="color: #64748b;">{{ $h->keterangan }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $h->tahun_perolehan }}</td>
                    <td style="text-align: right;">{{ number_format($h->harga_perolehan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Total Keseluruhan</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($anggota->harta->sum('harga_perolehan'), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <p>Tidak ada data harta yang tercatat.</p>
        @endif

        <div class="signature-box">
            <p class="signature-date">{{ $pemda->kota ?? ($pemda->kabupaten ?? '...') }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="signature-title">Mengetahui,<br>Sekretaris DPRD</p>
            <p class="signature-name">( ________________________ )</p>
            <p>NIP. .........................</p>
        </div>
    </div>
</body>
</html>
