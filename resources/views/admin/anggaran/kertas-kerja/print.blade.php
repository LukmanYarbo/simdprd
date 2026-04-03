<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kertas Kerja Anggaran {{ $kertasKerja->tahun_anggaran }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            font-size: 10pt; 
            color: #1a1a1a; 
            margin: 0; 
            padding: 20px; 
            line-height: 1.4; 
        }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 2px 0; text-transform: uppercase; font-size: 14pt; letter-spacing: 0.5px; }
        .header p { margin: 0; font-size: 10pt; color: #444; }
        
        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-table td.label { width: 160px; font-weight: 600; color: #555; }
        .info-table td.separator { width: 15px; text-align: center; }

        table.main-table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        table.main-table th { 
            background-color: #f8f9fa; 
            border: 1px solid #333; 
            padding: 10px 5px; 
            text-align: center; 
            font-size: 9pt; 
            text-transform: uppercase; 
            font-weight: 700;
        }
        table.main-table td { border: 1px solid #333; padding: 7px 8px; font-size: 9.5pt; word-wrap: break-word; }
        table.main-table tr.category-row { background-color: #f1f3f5; font-weight: 700; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        
        .footer { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .footer table { width: 100%; }
        .footer td { width: 50%; text-align: center; }
        .signature-box { margin-top: 60px; font-weight: 700; text-decoration: underline; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            @page { 
                size: 215mm 330mm; /* F4 / Folio Size */
                margin: 1.5cm 1.2cm; 
            }
            .header { border-bottom-width: 3px; }
            table.main-table { border: 1.5px solid #000; }
            table.main-table th, table.main-table td { border: 1px solid #000; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center; font-family: sans-serif;">
        <button onclick="window.print()" style="padding: 12px 24px; cursor: pointer; background: #0d6efd; color: #fff; border: none; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <i class="ti ti-printer"></i> Cetak Sekarang (F4)
        </button>
        <button onclick="window.close()" style="padding: 12px 24px; cursor: pointer; background: #eaedf1; color: #333; border: 1px solid #ddd; border-radius: 6px; margin-left: 10px;">
            Tutup
        </button>
        <p style="font-size: 11px; color: #666; margin-top: 10px;">Pastikan setting ukuran kertas di Browser adalah <b>Folio/F4</b> atau <b>215 x 330 mm</b></p>
        <hr>
    </div>

    <div class="header">
        <h2>DEWAN PERWAKILAN RAKYAT DAERAH</h2>
        <h2>KERTAS KERJA ESTIMASI PAGU ANGGARAN</h2>
        <p>TAHUN ANGGARAN {{ $kertasKerja->tahun_anggaran }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Tahun Anggaran</td>
            <td class="separator">:</td>
            <td>{{ $kertasKerja->tahun_anggaran }}</td>
        </tr>
        <tr>
            <td class="label">Status Dokumen</td>
            <td class="separator">:</td>
            <td class="fw-bold">{{ $kertasKerja->status }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td class="separator">:</td>
            <td>{{ date('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kategori / Uraian Rincian</th>
                <th style="width: 130px;">Besaran (Rp)</th>
                <th style="width: 60px;">Orang</th>
                <th style="width: 60px;">Bln/Kali</th>
                <th style="width: 150px;">Jumlah Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $currentCategory = ''; 
                $no = 1;
            @endphp
            @foreach($kertasKerja->rincians->sortBy('id') as $rincian)
                @if($currentCategory !== $rincian->kategori)
                    <tr class="category-row">
                        <td colspan="5" style="padding-left: 10px;">{{ $rincian->kategori }}</td>
                        <td class="text-right">
                            @php
                                $subtotal = $kertasKerja->rincians->where('kategori', $rincian->kategori)->sum('jumlah');
                            @endphp
                            {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @php $currentCategory = $rincian->kategori; @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td style="padding-left: 20px;">{{ $rincian->uraian }}</td>
                    <td class="text-right">{{ number_format($rincian->besaran, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $rincian->orang }}</td>
                    <td class="text-center">{{ $rincian->bulan_kali }}</td>
                    <td class="text-right fw-bold">{{ number_format($rincian->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            
            <!-- Total Row at the END of table body -->
            <tr style="background-color: #f8f9fa; font-weight: 800; border-top: 2px solid #000;">
                <td colspan="5" class="text-right" style="padding: 12px;">TOTAL ESTIMASI PAGU ANGGARAN (KESELURUHAN)</td>
                <td class="text-right" style="padding: 12px; font-size: 11pt; border-left: 1.5px solid #000;">
                    {{ number_format($kertasKerja->total_pagu, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td></td>
                <td>
                    <p>{{ date('d F Y') }}</p>
                    <p>Bendahara/Pengelola Keuangan,</p>
                    <div class="signature-box"></div>
                    <p>( ............................................................ )</p>
                    <p>NIP. ........................................................</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
