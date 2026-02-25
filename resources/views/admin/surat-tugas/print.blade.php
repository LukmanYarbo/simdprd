<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $suratTugas->no_surat_tugas }}</title>
    <style>
        @page {
            size: F4;
            margin: 0; /* Removing page margin suppresses browser headers/footers */
        }
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 1.5cm; /* Using padding on body instead of @page margin */
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
        }
        .header-text h1 {
            margin: 0;
            font-size: 14pt; /* Reduced from 16pt */
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .header-text h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 5px 0 0 0;
            font-size: 10pt;
        }
        .title {
            text-align: center;
            margin-bottom: 30px;
        }
        .title h3 {
            margin: 0;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 14pt;
        }
        .title p {
            margin: 5px 0;
            font-weight: bold;
        }
        .section-label {
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table td {
            vertical-align: top;
            padding: 5px 0;
        }
        .member-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            table-layout: auto;
        }
        .member-table th, .member-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }
        .member-table th {
            text-align: center;
            background-color: #f2f2f2;
        }
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        .btn-print {
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 40px;
            width: 100%;
            break-inside: avoid; /* Ensures signature block doesn't split */
        }
        .signature-box {
            float: right;
        
            width: 300px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Cetak Sekarang</button>
    </div>

    <div class="header">
        @if($pemda && $pemda->logo_pemda)
            <img src="{{ asset('storage/' . $pemda->logo_pemda) }}" class="logo" alt="Logo">
        @endif
        <div class="header-text">
            <h1>{{ trim(preg_replace('/\s+/', ' ', str_replace('.jpg', '', $pemda->namapemda ?? 'PEMERINTAH DAERAH'))) }}</h1>
            <h2>DEWAN PERWAKILAN RAKYAT DAERAH</h2>
            <p>{{ $pemda->alamat ?? '' }} {{ $pemda->kabupaten ?? '' }} {{ $pemda->propinsi ?? '' }}</p>
        </div>
    </div>

    <div class="title">
        <h3>SURAT TUGAS</h3>
        <p>Nomor: {{ $suratTugas->no_surat_tugas }}</p>
    </div>

    <div class="content">
        <p>Berdasarkan kebutuhan pelaksanaan tugas dan fungsi Dewan Perwakilan Rakyat Daerah, maka dengan ini Pimpinan DPRD menugaskan kepada:</p>

        <table class="member-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Anggota</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $item)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $item->anggota->nama_anggota }}</strong>
                        </td>
                        <td>{{ $item->anggota->jabatan->nama ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <p>Untuk melaksanakan perjalanan dinas dengan rincian sebagai berikut:</p>
            <table class="content-table">
                <tr>
                    <td style="width: 150px;">Maksud Perjalanan</td>
                    <td style="width: 20px;">:</td>
                    <td>{{ $suratTugas->uraian }}</td>
                </tr>
                <tr>
                    <td>Tempat Asal</td>
                    <td>:</td>
                    <td>{{ $suratTugas->tempat_asal }}</td>
                </tr>
                <tr>
                    <td>Tempat Tujuan</td>
                    <td>:</td>
                    <td>{{ $suratTugas->tempat_tujuan }}</td>
                </tr>
                <tr>
                    <td>Lama Perjalanan</td>
                    <td>:</td>
                    <td>{{ $suratTugas->lama_hari }} ({{ \App\Helpers\Terbilang::make($suratTugas->lama_hari) }}) hari</td>
                </tr>
                <tr>
                    <td>Tanggal Berangkat</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($suratTugas->tanggal_berangkat)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Tanggal Kembali</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($suratTugas->tanggal_balik)->translatedFormat('d F Y') }}</td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 20px;">Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh tanggung jawab.</p>
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>Ditetapkan di: {{ $pemda->kota ?? ($pemda->kabupaten ?? '...') }}</p>
            <p>Pada Tanggal: {{ \Carbon\Carbon::parse($suratTugas->tanggal_ditetapkan)->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 10px; margin-bottom: 70px;">
                <strong>{{ strtoupper($suratTugas->penandatangan->jabatan->nama ?? 'PIMPINAN DPRD') }}</strong>
            </p>
            <p><strong>( {{ strtoupper($suratTugas->penandatangan->nama_anggota) }} )</strong></p>
        </div>
    </div>
</body>
</html>
