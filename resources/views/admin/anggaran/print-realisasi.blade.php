<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Realisasi Anggaran - {{ $labelBulan ?: 'Tahun' }} {{ $tahun }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; font-size: 8pt; color: #000; margin: 0; padding: 10px; line-height: 1.2; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 5px; }
        .header h3 { margin: 0; font-size: 11pt; letter-spacing: 1px; }
        .header h4 { margin: 2px 0; font-size: 9pt; }
        .header p { margin: 0; font-size: 7.5pt; }
        
        .title { text-align: center; margin-bottom: 10px; }
        .title h4 { margin: 0; font-size: 10pt; text-decoration: underline; font-weight: 700; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 3px 5px; word-wrap: break-word; }
        th { background-color: #f8f9fa; font-weight: 700; text-transform: uppercase; font-size: 7.5pt; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        
        .footer { margin-top: 15px; width: 100%; }
        .footer table { border: none !important; }
        .footer td { border: none !important; width: 50%; text-align: center; font-size: 8.5pt; vertical-align: top; }
        .signature-space { height: 45px; }

        @media print {
            @page { size: 215mm 330mm landscape; margin: 0.8cm; }
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>{{ $pemda->namapemda ?? 'PEMERINTAH DAERAH' }}</h3>
        <h4>SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH</h4>
        <p style="margin: 0; font-size: 10pt;">{{ $pemda->alamat ?? '' }}</p>
    </div>

    <div class="title">
        <h4>LAPORAN REALISASI ANGGARAN (LRA)</h4>
        <p style="margin: 5px 0;">Periode: {{ $labelBulan ?: 'Januari s/d Desember' }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Item / Uraian Anggaran</th>
                <th style="width: 150px;">Anggaran (Pagu)</th>
                <th style="width: 150px;">Realisasi</th>
                <th style="width: 150px;">Sisa Anggaran</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalAnggaran = 0;
                $totalRealisasi = 0;
                $totalSisa = 0;
            @endphp
            @foreach($anggaran->rincians as $index => $rincian)
                @php 
                    $realisasi = $realizations[$rincian->kode_item] ?? 0;
                    $sisa = $rincian->besaran - $realisasi;
                    
                    $totalAnggaran += $rincian->besaran;
                    $totalRealisasi += $realisasi;
                    $totalSisa += $sisa;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $rincian->uraian }}</td>
                    <td class="text-end">Rp {{ number_format($rincian->besaran, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($realisasi, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold" style="background-color: #f9f9f9;">
                <td colspan="2" class="text-center">TOTAL</td>
                <td class="text-end">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p class="fw-bold">Pengguna Anggaran</p>
                    <div class="signature-space"></div>
                    <p class="fw-bold">( ............................................ )</p>
                    <p>NIP. .....................................</p>
                </td>
                <td>
                    <p>{{ $pemda->kota ?? 'Kota' }}, {{ date('d F Y') }}</p>
                    <p class="fw-bold">Bendahara Pengeluaran</p>
                    <div class="signature-space"></div>
                    <p class="fw-bold">( ............................................ )</p>
                    <p>NIP. .....................................</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #28a745; color: #fff; border: none; border-radius: 5px;">Cetak Laporan</button>
    </div>
</body>
</html>
