<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gaji - {{ $bulanLabel }} {{ $tahun }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 355.6mm 215.9mm; /* Legal Landscape */
            margin: 8mm 10mm;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.3;
            color: #222;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* === HEADER === */
        .report-header {
            text-align: center;
            margin-bottom: 6px;
            padding-bottom: 4px;
        }
        .report-header .pemda-name {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .report-header .report-title {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .report-header .sub-title {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
        }
        .report-header .period {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .sheet-label {
            text-align: right;
            font-weight: 700;
            font-size: 8pt;
            margin-bottom: 4px;
        }

        /* === TABLE === */
        table.gaji-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            table-layout: fixed;
            page-break-inside: auto;
        }
        table.gaji-table thead {
            display: table-header-group;
        }
        table.gaji-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        table.gaji-table th {
            border: 1px solid #555;
            text-align: center;
            font-weight: 700;
            font-size: 7pt;
            text-transform: uppercase;
            vertical-align: middle;
            padding: 2px 2px;
        }
        table.gaji-table td {
            border: 1px solid #555;
            padding: 1px 3px;
            vertical-align: top;
            text-align: right;
            white-space: nowrap;
        }
        
        /* Keep member block together */
        table.gaji-table tbody {
            page-break-inside: avoid;
        }
        .col-no { width: 22px; }
        .col-nama { width: 180px; text-align: left !important; }
        .col-sts { width: 35px; text-align: center !important; }
        .col-num { width: 60px; }
        .col-jlh { width: 65px; }
        .col-pot { width: 55px; }
        .col-bersih { width: 70px; }
        .col-ttd { width: 130px; text-align: center !important; }
        .col-rek { width: 100px; text-align: left !important; }

        td.t-left { text-align: left !important; }
        td.t-center { text-align: center !important; }

        /* Member separator row */
        tr.member-sep td { border-bottom: 2px solid #333; }

        /* Subtotal row */
        tr.subtotal td {
            border-top: 2px solid #333;
            font-weight: 700;
        }

        /* Footer info */
        .page-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 7.5pt;
        }
        .footer-info {
            border: 1px solid #555;
            padding: 3px 8px;
            display: inline-block;
        }

        /* === SIGNATURES === */
        .signatures {
            margin-top: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-box {
            width: 300px;
            text-align: center;
        }
        .sig-space {
            height: 50px;
        }
        .sig-name {
            font-weight: 700;
            text-decoration: underline;
            text-transform: uppercase;
        }

        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="report-header">
        <div class="pemda-name">PEMERINTAH DAERAH KABUPATEN BOLAANG MONGONDOW UTARA</div>
        <div class="report-title">DAFTAR PEMBAYARAN GAJI PIMPINAN DAN ANGGOTA DPRD</div>
        <div class="sub-title">DEWAN PERWAKILAN RAKYAT DAERAH</div>
        <div class="period">BULAN {{ $bulanLabel }} {{ $tahun }}</div>
    </div>



    <table class="gaji-table">
        {{-- === HEADER ROWS === --}}
        <thead>
            <tr>
                <th class="col-no" rowspan="4" style="vertical-align: middle;">No.</th>
                <th class="col-nama" rowspan="2" style="vertical-align: middle;">N A M A</th>
                <th class="col-sts" rowspan="4" style="vertical-align: middle;">STS KAWIN<br><small>JLH ISTRI/ANAK</small><br><small>JLH JIWA</small></th>
                <th colspan="5" style="text-align:center; border-bottom: 2px solid #555;">P E N G H A S I L A N</th>
                <th class="col-jlh" rowspan="4" style="vertical-align: middle;">JLH. KOTOR</th>
                <th colspan="2" style="text-align:center; border-bottom: 2px solid #555;">P O T O N G A N</th>
                <th class="col-bersih" rowspan="4" style="vertical-align: middle;">JUMLAH<br>BERSIH</th>
                <th class="col-ttd" rowspan="2" style="vertical-align: middle;">TANDA<br>TANGAN</th>
            </tr>
            <tr>
                <th class="col-num" style="font-size: 6.5pt;">GAJI POKOK<br>TUN. ISTRI<br>TUN. ANAK<br>TUN. BERAS</th>
                <th class="col-num" style="font-size: 6.5pt;">TUN. JABATAN<br>UANG PAKET<br>TUN. KOMISI</th>
                <th class="col-num" style="font-size: 6.5pt;">TUN. BANMUS<br>TUN. BANGGAR<br>TUN. BALEG<br>TUN. BK</th>
                <th class="col-num" style="font-size: 6.5pt;">TUN. PANSUS<br>TUN. PANJA<br>PEMBULATAN</th>
                <th class="col-num" style="font-size: 6.5pt;">TUN. BPJS 3%<br>TUN. JKM<br>TUN. JKK<br>TUN. PAJAK</th>
                <th class="col-num" style="font-size: 6.5pt;">POT BPJS 3%<br>POT. JKK<br>POT. JKM<br>PAJAK</th>
                <th class="col-pot" style="font-size: 6.5pt;">POT BPJS 1%<br><br>JLH. POT</th>
            </tr>
            <tr>
                <th class="col-nama" style="text-align: center;">J A B A T A N</th>
                <th class="col-num" colspan="5" style="background: #fff; border: none;">&nbsp;</th>
                <th class="col-num" colspan="2" style="background: #fff; border: none;">&nbsp;</th>
                <th class="col-ttd" rowspan="2" style="vertical-align: middle;">NO. REKENING</th>
            </tr>
            <tr>
                <th class="col-nama" style="text-align: center;">NPWP / TGL LAHIR</th>
                <th class="col-num" colspan="5" style="background: #fff; border: none;">&nbsp;</th>
                <th class="col-num" colspan="2" style="background: #fff; border: none;">&nbsp;</th>
                <th class="col-ttd" style="background: #fff; border: none;">&nbsp;</th>
            </tr>
        </thead>
        @php
            $no = 0;
            $totalGajiPokok = 0; $totalTunIstri = 0; $totalTunAnak = 0; $totalTunBeras = 0;
            $totalTunJabatan = 0; $totalUangPaket = 0; $totalTunKomisi = 0;
            $totalTunBanmus = 0; $totalTunBanggar = 0; $totalTunBaleg = 0; $totalTunBk = 0;
            $totalTunPansus = 0; $totalTunPanja = 0; $totalPembulatan = 0;
            $totalTunBpjs = 0; $totalTunJkm = 0; $totalTunJkk = 0; $totalTunPajak = 0;
            $totalJlhKotor = 0;
            $totalPotBpjs = 0; $totalPotJkk = 0; $totalPotJkm = 0; $totalPotPajak = 0;
            $totalPotBpjs2 = 0; $totalJlhPot = 0;
            $totalBersih = 0;
        @endphp
        @foreach($transaksi as $t)
        <tbody>
            @php
                $no++;
                $a = $t->anggota;
                
                // Jabatan abbreviation
                $jabShort = '';
                if ($a->id_status_kawin == 1) $jabShort = 'T'; // Tanpa Istri/Anak (Simplified for logic)
                else $jabShort = $t->status_kawin ?? 'K'; 

                $jabFull = $a->jabatan->nama_jabatan ?? 'ANGGOTA DPRD';
                $stsKawin = $t->status_kawin ?? '-';
                $jlhIs = $t->jumlah_is ?? 0;
                $jlhAnak = $t->jumlah_anak ?? 0;
                $jlhJiwa = $t->jumlah_jiwa ?? 0;

                // Penghasilan mapping
                $gajiPokok = $t->gaji_pokok ?? 0;
                $tunIstri = $t->tunjangan_istri ?? 0;
                $tunAnak = $t->tunjangan_anak ?? 0;
                $tunBeras = $t->tunjangan_beras ?? 0;
                
                $tunJabatan = $t->tunjangan_jabatan ?? 0;
                $uangPaket = $t->tunjangan_paket ?? 0;
                $tunKomisi = $t->tunjangan_komisi ?? 0;
                
                $tunBanmus = $t->tunjangan_banmus ?? 0;
                $tunBanggar = $t->tunjangan_banggar ?? 0;
                $tunBaleg = $t->tunjangan_balegda ?? 0;
                $tunBk = $t->tunjangan_bk ?? 0;
                
                $tunPansus = $t->tunjangan_pansus ?? 0;
                $tunPanja = $t->tunjangan_panja ?? 0;
                $pembulatan = $t->pembulatan ?? 0;
                
                $tunBpjs1 = $t->tunjangan_bpjs ?? 0; // BPJS 3%
                $tunJkm = $t->tunjangan_jkm ?? 0;
                $tunJkk = $t->tunjangan_jkk ?? 0;
                $tunTax = $t->PPH21_Gaji ?? 0;

                $brutto = $t->brutto2 ?? 0; // JLH KOTOR (using brutto2 as requested)

                // Potongan mapping
                $potBpjs1 = $t->potongan_bpjs ?? 0;
                $potJkk = $t->potongan_jkk ?? 0;
                $potJkm = $t->potongan_jkm ?? 0;
                $potTax = $t->potongan_pph21 ?? 0;
                
                $potBpjs2 = $t->potongan_bpjs2 ?? 0;
                $jlhPot = $potBpjs1+$potJkk+$potJkm+$potTax+$potBpjs2 ?? 0;
                
                $jlhBersih = $t->brutto1 ?? 0; // JUMLAH BERSIH (using brutto1 as requested)

                // Totals
                $totalGajiPokok += $gajiPokok; $totalTunIstri += $tunIstri; $totalTunAnak += $tunAnak; $totalTunBeras += $tunBeras;
                $totalTunJabatan += $tunJabatan; $totalUangPaket += $uangPaket; $totalTunKomisi += $tunKomisi;
                $totalTunBanmus += $tunBanmus; $totalTunBanggar += $tunBanggar; $totalTunBaleg += $tunBaleg; $totalTunBk += $tunBk;
                $totalTunPansus += $tunPansus; $totalTunPanja += $tunPanja; $totalPembulatan += $pembulatan;
                $totalTunBpjs += $tunBpjs1; $totalTunJkm += $tunJkm; $totalTunJkk += $tunJkk; $totalTunPajak += $tunTax;
                $totalJlhKotor += $brutto;
                $totalPotBpjs += $potBpjs1; $totalPotJkk += $potJkk; $totalPotJkm += $potJkm; $totalPotPajak += $potTax;
                $totalPotBpjs2 += $potBpjs2; $totalJlhPot += $jlhPot;
                $totalBersih += $jlhBersih;

                $fmt = function($v) { return $v != 0 ? number_format($v, 0, ',', '.') : '0'; };
            @endphp

            {{-- Row 1: Nama, Gaji Pokok, Tun Jabatan, Tun Banmus, Tun Pansus, Tun BPJS 3%, JLH KOTOR, Pot BPJS 3%, Pot BPJS 2%, No --}}
            <tr>
                <td rowspan="4" class="t-center" style="vertical-align:middle; font-weight:700; border-bottom: 2px solid #333;">{{ $no }}.</td>
                <td class="t-left" style="font-weight:700;">{{ strtoupper($a->nama_anggota) }}</td>
                <td class="t-center" style="border-bottom: none;"></td>
                <td>{{ $fmt($gajiPokok) }}</td>
                <td>{{ $fmt($tunJabatan) }}</td>
                <td>{{ $fmt($tunBanmus) }}</td>
                <td>{{ $fmt($tunPansus) }}</td>
                <td>{{ $fmt($tunBpjs1) }}</td>
                <td rowspan="4" style="vertical-align:middle; font-weight:700; border-bottom: 2px solid #333;">{{ $fmt($brutto) }}</td>
                <td>{{ $fmt($potBpjs1) }}</td>
                <td>{{ $fmt($potBpjs2) }}</td>
                <td rowspan="4" style="vertical-align:middle; font-weight:700; border-bottom: 2px solid #333;">{{ $fmt($jlhBersih) }}</td>
                <td class="t-center" style="font-weight:700;">{{ $no }}</td>
            </tr>
            {{-- Row 2: Jabatan, STS Kawin, Tun Istri, Uang Paket, Tun Banggar, Tun Panja, Tun JKM, Pot JKK --}}
            <tr>
                <td class="t-left" style="font-size: 7.5pt;">{{ strtoupper($jabFull) }}</td>
                <td class="t-center" style="vertical-align: middle;">{{ $jabShort }}</td>
                <td>{{ $fmt($tunIstri) }}</td>
                <td>{{ $fmt($uangPaket) }}</td>
                <td>{{ $fmt($tunBanggar) }}</td>
                <td>{{ $fmt($tunPanja) }}</td>
                <td>{{ $fmt($tunJkm) }}</td>
                <td>{{ $fmt($potJkk) }}</td>
                <td></td>
                <td class="t-center" style="border-bottom: none;"></td>
            </tr>
            {{-- Row 3: NPWP, JLH Is/Anak, Tun Anak, Tun Komisi, Tun Baleg, Pembulatan, Tun JKK, Pot JKM --}}
            <tr>
                <td class="t-left" style="font-size: 7pt;">{{ $a->no_npwp ?? '-' }}</td>
                <td class="t-center" style="vertical-align: middle;">{{ $jlhIs }} / {{ $jlhAnak }}</td>
                <td>{{ $fmt($tunAnak) }}</td>
                <td>{{ $fmt($tunKomisi) }}</td>
                <td>{{ $fmt($tunBaleg) }}</td>
                <td>{{ $fmt($pembulatan) }}</td>
                <td>{{ $fmt($tunJkk) }}</td>
                <td>{{ $fmt($potJkm) }}</td>
                <td></td>
                <td class="t-center" style="border-bottom: none;"></td>
            </tr>
            {{-- Row 4: Tgl Lahir, JLH Jiwa, Tun Beras, -, Tun BK, -, Tun Pajak, Pajak, JLH POT, No Rekening --}}
            <tr class="member-sep">
                <td class="t-left" style="font-size: 7pt; border-bottom: 2px solid #333;">{{ $a->tgl_lahir ? $a->tgl_lahir->format('d/m/Y') : '-' }}</td>
                <td class="t-center" style="vertical-align: middle; border-bottom: 2px solid #333;">{{ $jlhJiwa }}</td>
                <td style="border-bottom: 2px solid #333;">{{ $fmt($tunBeras) }}</td>
                <td style="border-bottom: 2px solid #333;">0</td>
                <td style="border-bottom: 2px solid #333;">{{ $fmt($tunBk) }}</td>
                <td style="border-bottom: 2px solid #333;">0</td>
                <td style="border-bottom: 2px solid #333;">{{ $fmt($tunTax) }}</td>
                <td style="border-bottom: 2px solid #333;">{{ $fmt($potTax) }}</td>
                <td style="border-bottom: 2px solid #333; font-weight:700;">{{ $fmt($jlhPot) }}</td>
                <td class="t-left" style="font-size: 6.5pt; vertical-align: middle; border-bottom: 2px solid #333;">{{ $a->no_rekening ?? '-' }}</td>
            </tr>
        </tbody>
        @endforeach

        <tbody>
            {{-- === SUBTOTAL ROWS === --}}
            @php $fmt = function($v) { return $v != 0 ? number_format($v, 0, ',', '.') : '0'; }; @endphp
            <tr class="subtotal">
                <td colspan="2" class="t-left" style="font-weight:700;">JUMLAH HALAMAN INI</td>
                <td class="t-center"></td>
                <td style="font-weight:700;">{{ $fmt($totalGajiPokok) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunJabatan) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunBanmus) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunPansus) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunBpjs) }}</td>
                <td rowspan="4" style="vertical-align:middle; font-weight:700;">{{ $fmt($totalJlhKotor) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalPotBpjs) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalPotBpjs2) }}</td>
                <td rowspan="4" style="vertical-align:middle; font-weight:700;">{{ $fmt($totalBersih) }}</td>
                <td></td>
            </tr>
            <tr class="subtotal" style="border-top: none;">
                <td colspan="2"></td>
                <td class="t-center"></td>
                <td style="font-weight:700;">{{ $fmt($totalTunIstri) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalUangPaket) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunBanggar) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunPanja) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunJkm) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalPotJkk) }}</td>
                <td style="font-weight:700;"></td>
                <td></td>
            </tr>
            <tr class="subtotal" style="border-top: none;">
                <td colspan="2"></td>
                <td class="t-center"></td>
                <td style="font-weight:700;">{{ $fmt($totalTunAnak) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunKomisi) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunBaleg) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalPembulatan) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalTunJkk) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalPotJkm) }}</td>
                <td style="font-weight:700;"></td>
                <td></td>
            </tr>
            <tr class="subtotal" style="border-top: none;">
                <td colspan="2"></td>
                <td class="t-center"></td>
                <td style="font-weight:700;">{{ $fmt($totalTunBeras) }}</td>
                <td></td>
                <td style="font-weight:700;">{{ $fmt($totalTunBk) }}</td>
                <td></td>
                <td style="font-weight:700;">{{ $fmt($totalTunPajak) }}</td>
                <td style="font-weight:700;">{{ $fmt($totalPotPajak) }}</td>
                <td style="font-weight:700; border-top: 1px solid #555;">{{ $fmt($totalJlhPot) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="page-footer">
        <div class="footer-info">
            Jumlah Pegawai = {{ $dsbGaji->jumlah_pegawai ?? $transaksi->count() }} , Jumlah Istri= {{ $dsbGaji->jumlah_is ?? 0 }} , Jumlah Anak = {{ $dsbGaji->jumlah_anak ?? 0 }} , Jumlah Jiwa = {{ $dsbGaji->jumlah_jiwa ?? 0 }}
        </div>
    </div>

    @php
        $tgl = $dsbGaji->tanggal_proses ?? now();
        $formattedDate = \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y');
        $kota = strtoupper($pemda->ibu_kota ?? 'BOROKO');
    @endphp

    <div class="signatures">
        {{-- Sekretaris DPRD di sebelah kiri --}}
        <div class="sig-box">
            <div style="margin-bottom: 2px;">&nbsp;</div>
            <div style="font-weight: 700;">Mengetahui,</div>
            <div style="font-weight: 700;">SEKRETARIS DPRD</div>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $dsbGaji->nama_pa ?? '..........................' }}</div>
            <div>NIP : {{ $dsbGaji->nip_pa ?? '' }}</div>
        </div>

        {{-- Bendahara di sebelah kanan --}}
        <div class="sig-box">
            <div style="margin-bottom: 2px;">{{ $kota }}, {{ strtoupper($formattedDate) }}</div>
            <div style="font-weight: 700;">BENDAHARA PENGELUARAN</div>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $dsbGaji->nama_bendahara ?? '..........................' }}</div>
            <div>NIP : {{ $dsbGaji->nip_bendahara ?? '' }}</div>
        </div>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
