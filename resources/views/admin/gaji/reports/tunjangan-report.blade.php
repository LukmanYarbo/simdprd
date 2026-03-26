<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembayaran Tunjangan - {{ $bulanLabel }} {{ $tahun }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 330mm 215.9mm; /* Legal/F4 Landscape */
            margin: 8mm 12mm;
        }
        body {
            font-family: 'Inter', Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.15;
            color: #000;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h3, .header h4 {
            margin: 1px 0;
            text-transform: uppercase;
            font-weight: 700;
        }
        .header h3 { font-size: 10pt; }
        .header h4 { font-size: 9pt; }
        .header .period { margin-top: 3px; text-decoration: underline; font-size: 9pt; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 0.5pt solid #000; /* Thin solid line for modern feel */
            padding: 3px 6px;
            vertical-align: middle;
        }
        th {
            text-transform: uppercase;
            font-weight: 700;
            text-align: center;
            font-size: 7.5pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        
        .col-no { width: 8px; }
        .col-identitas { width: 35%; }
        .col-tunjangan { width: 15%; }
        .col-potongan { width: 12%; }
        .col-jumlah { width: 12%; }
        .col-ttd { width: 15%; text-align: left !important; }

        .member-name { font-weight: 700; font-size: 8.5pt; }
        .member-jabatan { font-size: 7.5pt; }
        .member-npwp { font-size: 7pt; color: #111; }

        .no-border-top { border-top: none !important; }
        .no-border-bottom { border-bottom: none !important; }

        /* Handle page breaks */
        tr { page-break-inside: avoid; }
        
        .footer-signatures {
            margin-top: 15px;
            width: 100%;
        }
        .sig-container {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }
        .sig-box {
            width: 300px;
            text-align: center;
            font-size: 8.5pt;
        }
        .sig-space { height: 45px; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h3>PEMERINTAH DAERAH {{ strtoupper($pemda->kabupaten ?? 'KABUPATEN BOLAANG MONGONDOW UTARA') }}</h3>
        <h4>DAFTAR PEMBAYARAN TUNJANGAN PERUMAHAN, TUNJANGAN KOMUNIKASI INTENSIF DAN TUNJANGAN TRANSPORTASI</h4>
        <h4>DEWAN PERWAKILAN RAKYAT DAERAH</h4>
        <div class="period font-bold">BULAN {{ strtoupper($bulanLabel) }} {{ $tahun }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-identitas">N A M A<br>J A B A T A N<br>N P W P</th>
                <th class="col-tunjangan">TUN. PERUMAHAN<br>T K I<br>TUN. TRANSPORTASI</th>
                <th class="col-potongan">POT. PPh FINAL<br>15 %</th>
                <th class="col-jumlah">JUMLAH<br>BERSIH</th>
                <th class="col-ttd">TANDA<br>TANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $fmt = function($v) { return number_format($v, 0, ',', '.'); };
                
                $totalPerum = 0; $totalPotPerum = 0; $totalNetPerum = 0;
                $totalTki = 0; $totalPotTki = 0; $totalNetTki = 0;
                $totalTrans = 0; $totalPotTrans = 0; $totalNetTrans = 0;
            @endphp
            @foreach($transaksi as $t)
                @php
                    $a = $t->anggota;
                    
                    // Values
                    $valPerum = $t->tunjangan_perumahan ?? 0;
                    $potPerum = $t->potonganpph_perumahan ?? 0;
                    $netPerum = $valPerum - $potPerum;
                    
                    $valTki = $t->tunjangan_tki ?? 0;
                    $potTki = $t->potonganpph_tki ?? 0;
                    $netTki = $valTki - $potTki;
                    
                    $valTrans = $t->tunjangan_transportasi ?? 0;
                    $potTrans = $t->potonganpph_transportasi ?? 0;
                    $netTrans = $valTrans - $potTrans;
                    
                    // Totals
                    $totalPerum += $valPerum; $totalPotPerum += $potPerum; $totalNetPerum += $netPerum;
                    $totalTki += $valTki; $totalPotTki += $potTki; $totalNetTki += $netTki;
                    $totalTrans += $valTrans; $totalPotTrans += $potTrans; $totalNetTrans += $netTrans;
                @endphp
                
                {{-- Subrow 1: Perumahan --}}
                <tr>
                    <td rowspan="3" class="text-center font-bold no-border-bottom" style="border-bottom-style: dotted;">{{ $no }}</td>
                    <td class="member-name no-border-bottom">{{ strtoupper($a->nama_anggota) }}</td>
                    <td class="text-right no-border-bottom">{{ $fmt($valPerum) }}</td>
                    <td class="text-right no-border-bottom">{{ $fmt($potPerum) }}</td>
                    <td class="text-right font-bold no-border-bottom">{{ $fmt($netPerum) }}</td>
                    <td class="no-border-bottom">{{ $no }}</td>
                </tr>
                {{-- Subrow 2: TKI --}}
                <tr>
                    <td class="member-jabatan no-border-top no-border-bottom">{{ strtoupper($a->jabatan->nama ?? 'ANGGOTA DPRD') }}</td>
                    <td class="text-right no-border-top no-border-bottom">{{ $fmt($valTki) }}</td>
                    <td class="text-right no-border-top no-border-bottom">{{ $fmt($potTki) }}</td>
                    <td class="text-right font-bold no-border-top no-border-bottom">{{ $fmt($netTki) }}</td>
                    <td class="no-border-top no-border-bottom"></td>
                </tr>
                {{-- Subrow 3: Transportasi --}}
                <tr style="border-bottom: 1px solid #000;">
                    <td class="member-npwp no-border-top">{{ $a->no_npwp ?? '-' }}</td>
                    <td class="text-right no-border-top">{{ $fmt($valTrans) }}</td>
                    <td class="text-right no-border-top">{{ $fmt($potTrans) }}</td>
                    <td class="text-right font-bold no-border-top">{{ $fmt($netTrans) }}</td>
                    <td class="no-border-top text-right" style="font-size: 7pt; color: #555;">{{ $a->no_rekening ?? '' }}</td>
                </tr>
                
                @php $no++; @endphp
            @endforeach

            {{-- Totals inside the same table --}}
            <tr class="font-bold">
                <td colspan="2" class="text-center" style="border-top: 2px solid #000;">J U M L A H</td>
                <td class="text-right" style="border-top: 2px solid #000;">
                    {{ $fmt($totalPerum) }}<br>
                    {{ $fmt($totalTki) }}<br>
                    {{ $fmt($totalTrans) }}
                </td>
                <td class="text-right" style="border-top: 2px solid #000;">
                    {{ $fmt($totalPotPerum) }}<br>
                    {{ $fmt($totalPotTki) }}<br>
                    {{ $fmt($totalPotTrans) }}
                </td>
                <td class="text-right font-bold" style="border-top: 2px solid #000;">
                    {{ $fmt($totalNetPerum) }}<br>
                    {{ $fmt($totalNetTki) }}<br>
                    {{ $fmt($totalNetTrans) }}
                </td>
                <td style="border-top: 2px solid #000;"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer-signatures" style="page-break-inside: avoid; margin-top: 20px;">
        @php
            $tgl = $dsbGaji->tanggal_proses ?? now();
            $formattedDate = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('d F Y');
            $kota = strtoupper($pemda->ibu_kota ?? 'BOROKO');
        @endphp
        <div class="sig-container">
            <div class="sig-box">
                <div class="font-bold">Mengetahui,</div>
                <div class="font-bold">SEKRETARIS DPRD</div>
                <div class="sig-space"></div>
                <div class="font-bold" style="text-decoration: underline;">{{ $dsbGaji->nama_pa ?? '..........................' }}</div>
                <div>NIP : {{ $dsbGaji->nip_pa ?? '' }}</div>
            </div>
            
            <div class="sig-box">
                <div>{{ $kota }}, {{ $formattedDate }}</div>
                <div class="font-bold">BENDAHARA PENGELUARAN</div>
                <div class="sig-space"></div>
                <div class="font-bold" style="text-decoration: underline;">{{ $dsbGaji->nama_bendahara ?? '..........................' }}</div>
                <div>NIP : {{ $dsbGaji->nip_bendahara ?? '' }}</div>
            </div>
        </div>
    </div>

</body>
</html>
