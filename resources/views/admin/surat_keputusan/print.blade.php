<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Keputusan - {{ $suratKeputusan->no_sk }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
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
            font-size: 15pt;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .header-text h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 5px 0 0 0;
            font-size: 10pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            margin-bottom: 30px;
        }
        .title h3 {
            margin: 0;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title p {
            margin: 5px 0;
        }
        .content {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
        }
        th {
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
            <h1>{{ $pemda->namapemda ?? 'PEMERINTAH DAERAH' }}</h1>
            <h2>DEWAN PERWAKILAN RAKYAT DAERAH</h2>
            <p>{{ $pemda->alamat ?? '' }} {{ $pemda->kabupaten ?? '' }} {{ $pemda->propinsi ?? '' }}</p>
        </div>
    </div>

    <div class="title">
        <h3>SURAT KEPUTUSAN</h3>
        <p>Nomor: {{ $suratKeputusan->no_sk }}</p>
        <p>Tentang: {{ $suratKeputusan->ket_sk ?? $suratKeputusan->alatKelengkapan->nama }}</p>
    </div>

    <div class="content">
        <p>Susunan Anggota {{ $suratKeputusan->alatKelengkapan->nama }} {{ $suratKeputusan->alatKelengkapan->ket }}:</p>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Anggota</th>
                    @if(strtolower($suratKeputusan->alatKelengkapan->nama) === 'komisi')
                        <th>Nama Komisi</th>
                    @endif
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sortedAnggota as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->anggota->nama_anggota }}</td>
                        @if(strtolower($suratKeputusan->alatKelengkapan->nama) === 'komisi')
                            <td>{{ $item->nama_komisi ?? '-' }}</td>
                        @endif
                        <td>{{ $item->jabatanAlatKelengkapan->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: right; width: 300px; float: right;">
        <p>Ditetapkan di: {{ $pemda->kota ?? ($pemda->kabupaten ?? '...') }}</p>
        <p>Pada Tanggal: {{ \Carbon\Carbon::parse($suratKeputusan->tgl_sk)->translatedFormat('d F Y') }}</p>
        <p style="margin-bottom: 70px;"><strong>KETUA DPRD</strong></p>
        <p><strong>( {{ $ketuaDprd ? strtoupper($ketuaDprd->nama_anggota) : '________________________' }} )</strong></p>
    </div>
</body>
</html>
