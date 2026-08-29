<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gaji TER - {{ $bulanLabel }} {{ $tahun }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 330mm 215.9mm;
            margin: 7mm 5mm 8mm 5mm;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Arial, sans-serif;
            font-size: 7.5pt;
            line-height: 1.3;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            background: #fff;
        }
        .report-header { text-align: center; margin-bottom: 5px; padding-bottom: 4px; border-bottom: 1.5px solid #0f172a; }
        .report-header .pemda-name { font-size: 10pt; font-weight: 800; letter-spacing: .6px; text-transform: uppercase; color:#0f172a; }
        .report-header .report-title { font-size: 9.5pt; font-weight: 800; text-transform: uppercase; color:#4f46e5; letter-spacing:.4px; margin-top:1px; }
        .report-header .sub-title { font-size: 8pt; font-weight: 700; text-transform: uppercase; color:#334155; }
        .report-header .period { font-size: 8.5pt; font-weight: 800; text-transform: uppercase; margin-top:2px; }
        .report-header .ter-badge { display:inline-block; margin-top:4px; background:#4f46e5; color:#fff; font-size:6.5pt; font-weight:800; letter-spacing:.5px; padding:2px 10px; border-radius:999px; }
        .sheet-meta { display:flex; justify-content:space-between; align-items:center; font-size:7pt; color:#475569; margin:4px 0 5px; }
        .sheet-meta b { color:#0f172a; }

        table.gaji-table { width:100%; border-collapse:collapse; font-size:7pt; table-layout:fixed; }
        table.gaji-table th { border:0.6px solid #1e293b; text-align:center; font-weight:700; font-size:6.8pt; text-transform:uppercase; vertical-align:middle; padding:3px 2px; background:#f1f5f9; color:#0f172a; letter-spacing:.2px; }
        table.gaji-table th.group-ter { background:#eef2ff; color:#4338ca; border-color:#4338ca; }
        table.gaji-table th.group-pot { background:#fef2f2; color:#991b1b; }
        table.gaji-table th.group-peng { background:#f0fdf4; color:#14532d; }
        table.gaji-table td { border:0.5px dotted #64748b; padding:2px 4px; vertical-align:top; text-align:right; white-space:nowrap; font-size:7pt; }
        table.gaji-table tbody td { background:#fff; }
        table.gaji-table tbody tr:nth-child(8n+1) td,
        table.gaji-table tbody tr:nth-child(8n+2) td,
        table.gaji-table tbody tr:nth-child(8n+3) td,
        table.gaji-table tbody tr:nth-child(8n+4) td { background:#fafbff; }

        .col-no { width:22px; }
        .col-nama { width:162px; text-align:left !important; }
        .col-sts { width:34px; text-align:center !important; }
        .col-num { width:58px; }
        .col-ter-cat { width:42px; text-align:center !important; font-weight:700; color:#4338ca !important; }
        .col-ter-tarif { width:44px; text-align:center !important; color:#4338ca !important; }
        .col-ter-pph { width:62px; font-weight:700; background:#eef2ff !important; }
        .col-jlh { width:66px; font-weight:700; background:#f8fafc !important; }
        .col-pot { width:56px; }
        .col-bersih { width:68px; font-weight:800; background:#f0fdf4 !important; }
        .col-ttd { width:112px; text-align:center !important; font-size:6.8pt; }
        .col-rek { width:96px; text-align:left !important; font-size:6.5pt; }

        td.t-left { text-align:left !important; }
        td.t-center { text-align:center !important; }
        .no-border-top { border-top:none !important; }
        .no-border-bottom { border-bottom:none !important; }
        tr.member-sep td { border-bottom:1.2px solid #0f172a !important; }
        tr.subtotal td { background:#f1f5f9 !important; font-weight:700; border:0.6px solid #1e293b !important; }
        .badge-ter { display:inline-block; font-size:6.5pt; font-weight:800; padding:1px 5px; border-radius:999px; border:1px solid #4338ca; background:#fff; color:#4338ca; }
        .badge-ter.B { background:#fef3c7; color:#92400e; border-color:#f59e0b; }
        .badge-ter.C { background:#fee2e2; color:#991b1b; border-color:#ef4444; }

        .page-footer { margin-top:8px; display:flex; justify-content:space-between; font-size:7pt; color:#334155; }
        .footer-info { border:0.6px solid #64748b; padding:4px 8px; border-radius:6px; background:#f8fafc; }
        .signatures { margin-top:14px; width:100%; display:flex; justify-content:space-between; page-break-inside:avoid; }
        .sig-box { width:280px; text-align:center; font-size:7.5pt; }
        .sig-space { height:52px; }
        .sig-name { font-weight:800; text-decoration:underline; text-transform:uppercase; color:#0f172a; }
        @media print { body { margin:0; } .no-print{display:none;} }
    </style>
</head>
<body>
    @php
        $totalGajiPokok=0; $totalTunIstri=0; $totalTunAnak=0; $totalTunBeras=0;
        $totalTunJabatan=0; $totalUangPaket=0; $totalTunKomisi=0;
        $totalTunBanmus=0; $totalTunBanggar=0; $totalTunBaleg=0; $totalTunBk=0;
        $totalTunPansus=0; $totalTunPanja=0; $totalPembulatan=0;
        $totalTunBpjs=0; $totalTunJkm=0; $totalTunJkk=0; $totalTunTki=0; $totalTunPerum=0; $totalTunTrans=0;
        $totalJlhKotor=0; $totalPphTer=0; $totalPotBpjs=0; $totalPotJkk=0; $totalPotJkm=0; $totalPotPajak=0; $totalPotBpjs2=0; $totalJlhPot=0; $totalBersih=0;
        $fmt = function($v){ return $v!=0 ? number_format($v,0,',','.') : '0'; };
        $no=0;
    @endphp

    <div class="report-header">
        <div class="pemda-name">PEMERINTAH DAERAH {{ strtoupper($pemda->kabupaten ?? 'KABUPATEN BOLAANG MONGONDOW UTARA') }}</div>
        <div class="report-title">DAFTAR PEMBAYARAN GAJI PIMPINAN DAN ANGGOTA DPRD</div>
        <div class="sub-title">DEWAN PERWAKILAN RAKYAT DAERAH — SISTEM TER</div>
        <div class="period">BULAN {{ $bulanLabel }} {{ $tahun }}</div>
        <div class="ter-badge">TARIF EFEKTIF RATA-RATA (TER) — PMK 168 TAHUN 2023</div>
    </div>
    <div class="sheet-meta">
        <span>Metode Pajak: <b>Sistem TER</b> &nbsp;|&nbsp; Brutto = Penghasilan Kotor &nbsp;|&nbsp; PPh21 = Brutto × Tarif TER</span>
        <span>LEMBARAN : <b>I</b> &nbsp;|&nbsp; Halaman <b>1</b></span>
    </div>

    <table class="gaji-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="4">No.</th>
                <th class="col-nama no-border-bottom" rowspan="2">N A M A</th>
                <th class="col-sts" rowspan="4">STS<br><small style="font-size:5.5pt;">IS/AN</small><br><small style="font-size:5.5pt;">JIWA</small></th>
                <th colspan="5" class="group-peng" style="border-bottom:none;">P E N G H A S I L A N</th>
                <th class="col-jlh" rowspan="4">JLH.<br>KOTOR<br><small>BRUTTO</small></th>
                <th colspan="3" class="group-ter" style="border-bottom:none;">SISTEM TER</th>
                <th colspan="2" class="group-pot" style="border-bottom:none;">P O T O N G A N</th>
                <th class="col-bersih" rowspan="4">JUMLAH<br>BERSIH</th>
                <th class="col-ttd" rowspan="2">TANDA<br>TANGAN</th>
            </tr>
            <tr>
                <th class="col-num" style="font-size:6pt; border-bottom:none;">GAJI POKOK<br>TUN. ISTRI<br>TUN. ANAK<br>TUN. BERAS</th>
                <th class="col-num" style="font-size:6pt; border-bottom:none;">TUN. JABATAN<br>UANG PAKET<br>TUN. KOMISI</th>
                <th class="col-num" style="font-size:6pt; border-bottom:none;">TUN. BANMUS<br>TUN. BANGGAR<br>TUN. BALEG<br>TUN. BK</th>
                <th class="col-num" style="font-size:6pt; border-bottom:none;">TUN. PANSUS<br>TUN. PANJA<br>PEMBULATAN<br><small style="color:#94a3b8;">-</small></th>
                <th class="col-num" style="font-size:6pt; border-bottom:none;">TUN. PERUM<br>TUN. TRANS<br>TUN. TKI<br>TUN. BPJS/JKK/JKM</th>
                <th class="col-ter-cat" style="font-size:6pt; border-bottom:none;">KATEGORI<br>TER</th>
                <th class="col-ter-tarif" style="font-size:6pt; border-bottom:none;">TARIF<br>TER (%)</th>
                <th class="col-ter-pph" style="font-size:6pt; border-bottom:none;">PPh21<br>TER</th>
                <th class="col-num" style="font-size:6pt; border-bottom:none;">POT BPJS<br>POT JKK<br>POT JKM<br>PAJAK TER</th>
                <th class="col-pot" style="font-size:6pt; border-bottom:none;">POT BPJS 1%<br><br>JLH. POT</th>
            </tr>
            <tr>
                <th class="col-nama no-border-top no-border-bottom" style="text-align:center; font-size:6.5pt;">J A B A T A N</th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th style="border-top:none; border-bottom:none;"></th>
                <th class="col-ttd" rowspan="2" style="vertical-align:middle; font-size:6.5pt;">NO. REKENING</th>
            </tr>
            <tr>
                <th class="col-nama no-border-top" style="text-align:center; font-size:6.5pt;">NPWP / TGL LAHIR</th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
                <th style="border-top:none;"></th>
            </tr>
        </thead>

        @foreach($transaksi as $t)
            @php
                $no++; $a=$t->anggota;
                $jabShort = $a->id_status_kawin==1 ? 'T' : ($t->status_kawin ?? 'K');
                $jabFull = $a->jabatan->nama_jabatan ?? 'ANGGOTA DPRD';
                $stsKawin = $t->status_kawin ?? '-';
                $jlhIs=$t->jumlah_is??0; $jlhAnak=$t->jumlah_anak??0; $jlhJiwa=$t->jumlah_jiwa??0;
                $gajiPokok=$t->gaji_pokok??0; $tunIstri=$t->tunjangan_istri??0; $tunAnak=$t->tunjangan_anak??0; $tunBeras=$t->tunjangan_beras??0;
                $tunJabatan=$t->tunjangan_jabatan??0; $uangPaket=$t->tunjangan_paket??0; $tunKomisi=$t->tunjangan_komisi??0;
                $tunBanmus=$t->tunjangan_banmus??0; $tunBanggar=$t->tunjangan_banggar??0; $tunBaleg=$t->tunjangan_balegda??0; $tunBk=$t->tunjangan_bk??0;
                $tunPansus=$t->tunjangan_pansus??0; $tunPanja=$t->tunjangan_panja??0; $pembulatan=$t->pembulatan??0;
                $tunPerum=$t->tunjangan_perumahan??0; $tunTrans=$t->tunjangan_transportasi??0; $tunTki=$t->tunjangan_tki??0;
                $tunBpjs=$t->tunjangan_bpjs??0; $tunJkm=$t->tunjangan_jkm??0; $tunJkk=$t->tunjangan_jkk??0;
                $brutto=$t->brutto1 ?? $t->nilai_gajitunjangan ?? 0;
                $kategori=$t->Kategori_TER ?? '-'; $tarif=$t->Nilai_TER ?? 0;
                $pphTer=$t->tunjangan_pph21 ?? $t->potongan_pph21 ?? 0;
                $potBpjs=$t->potongan_bpjs??0; $potJkk=$t->potongan_jkk??0; $potJkm=$t->potongan_jkm??0; $potTax=$pphTer;
                $potBpjs2=$t->potongan_bpjs2??0; $jlhPot=$potBpjs+$potJkk+$potJkm+$potTax+$potBpjs2;
                $jlhBersih=$t->jumlah_bersih ?? ($brutto - ($t->PPh21_Tunjangan ?? $pphTer) );
                // Totals
                $totalGajiPokok+=$gajiPokok; $totalTunIstri+=$tunIstri; $totalTunAnak+=$tunAnak; $totalTunBeras+=$tunBeras;
                $totalTunJabatan+=$tunJabatan; $totalUangPaket+=$uangPaket; $totalTunKomisi+=$tunKomisi;
                $totalTunBanmus+=$tunBanmus; $totalTunBanggar+=$tunBanggar; $totalTunBaleg+=$tunBaleg; $totalTunBk+=$tunBk;
                $totalTunPansus+=$tunPansus; $totalTunPanja+=$tunPanja; $totalPembulatan+=$pembulatan;
                $totalTunPerum+=$tunPerum; $totalTunTrans+=$tunTrans; $totalTunTki+=$tunTki; $totalTunBpjs+=$tunBpjs; $totalTunJkm+=$tunJkm; $totalTunJkk+=$tunJkk;
                $totalJlhKotor+=$brutto; $totalPphTer+=$pphTer; $totalPotBpjs+=$potBpjs; $totalPotJkk+=$potJkk; $totalPotJkm+=$potJkm; $totalPotPajak+=$potTax; $totalPotBpjs2+=$potBpjs2; $totalJlhPot+=$jlhPot; $totalBersih+=$jlhBersih;
            @endphp
            <tbody>
                <tr>
                    <td rowspan="4" class="t-center" style="vertical-align:middle; font-weight:800; border-bottom:1.2px solid #0f172a;">{{ $no }}.</td>
                    <td class="t-left no-border-bottom" style="font-weight:700; font-size:7pt;">{{ strtoupper($a->nama_anggota) }}</td>
                    <td class="t-center no-border-bottom"></td>
                    <td class="no-border-bottom">{{ $fmt($gajiPokok) }}</td>
                    <td class="no-border-bottom">{{ $fmt($tunJabatan) }}</td>
                    <td class="no-border-bottom">{{ $fmt($tunBanmus) }}</td>
                    <td class="no-border-bottom">{{ $fmt($tunPansus) }}</td>
                    <td class="no-border-bottom">{{ $fmt($tunPerum) }}</td>
                    <td rowspan="4" style="vertical-align:middle; font-weight:800; background:#f8fafc; border-bottom:1.2px solid #0f172a;">{{ $fmt($brutto) }}</td>
                    <td rowspan="4" class="t-center" style="vertical-align:middle; border-bottom:1.2px solid #0f172a;"><span class="badge-ter {{ $kategori }}">{{ $kategori }}</span></td>
                    <td rowspan="4" class="t-center" style="vertical-align:middle; border-bottom:1.2px solid #0f172a; color:#4338ca; font-weight:700;">{{ $tarif!=0 ? number_format($tarif,2,',','.') . '%' : '0%' }}</td>
                    <td rowspan="4" style="vertical-align:middle; font-weight:800; background:#eef2ff; border-bottom:1.2px solid #0f172a;">{{ $fmt($pphTer) }}</td>
                    <td class="no-border-bottom">{{ $fmt($potBpjs) }}</td>
                    <td class="no-border-bottom">{{ $fmt($potBpjs2) }}</td>
                    <td rowspan="4" style="vertical-align:middle; font-weight:800; background:#f0fdf4; border-bottom:1.2px solid #0f172a;">{{ $fmt($jlhBersih) }}</td>
                    <td class="t-center no-border-bottom" style="font-weight:700;">{{ $no }}</td>
                </tr>
                <tr>
                    <td class="t-left no-border-top no-border-bottom" style="font-size:6.8pt; color:#334155;">{{ strtoupper($jabFull) }}</td>
                    <td class="t-center no-border-top no-border-bottom" style="vertical-align:middle; font-weight:700;">{{ $jabShort }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunIstri) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($uangPaket) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunBanggar) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunPanja) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunTrans) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($potJkk) }}</td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="t-center no-border-top no-border-bottom"></td>
                </tr>
                <tr>
                    <td class="t-left no-border-top no-border-bottom" style="font-size:6.5pt; color:#475569;">{{ $a->no_npwp ?? '-' }}</td>
                    <td class="t-center no-border-top no-border-bottom" style="vertical-align:middle; font-size:6.5pt;">{{ $jlhIs }} / {{ $jlhAnak }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunAnak) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunKomisi) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunBaleg) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($pembulatan) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($tunTki) }}</td>
                    <td class="no-border-top no-border-bottom">{{ $fmt($potJkm) }}</td>
                    <td class="no-border-top no-border-bottom"></td>
                    <td class="t-center no-border-top"></td>
                </tr>
                <tr class="member-sep">
                    <td class="t-left no-border-top" style="font-size:6.5pt; color:#475569; border-bottom:1.2px solid #0f172a;">{{ $a->tgl_lahir ? $a->tgl_lahir->format('d/m/Y') : '-' }}</td>
                    <td class="t-center no-border-top" style="vertical-align:middle; font-weight:700; border-bottom:1.2px solid #0f172a;">{{ $jlhJiwa }}</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a;">{{ $fmt($tunBeras) }}</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a; color:#94a3b8;">0</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a;">{{ $fmt($tunBk) }}</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a; color:#94a3b8;">0</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a;">{{ $fmt($tunBpjs+$tunJkk+$tunJkm) }}</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a; font-weight:700; color:#991b1b;">{{ $fmt($potTax) }}</td>
                    <td class="no-border-top" style="border-bottom:1.2px solid #0f172a; font-weight:700;">{{ $fmt($jlhPot) }}</td>
                    <td class="t-left" style="font-size:6pt; vertical-align:middle; border-bottom:1.2px solid #0f172a;">{{ $a->no_rekening ?? '-' }}</td>
                </tr>
            </tbody>
        @endforeach
        <tbody>
            <tr class="subtotal">
                <td colspan="2" class="t-left">JUMLAH</td>
                <td class="t-center"></td>
                <td>{{ $fmt($totalGajiPokok) }}</td>
                <td>{{ $fmt($totalTunJabatan) }}</td>
                <td>{{ $fmt($totalTunBanmus) }}</td>
                <td>{{ $fmt($totalTunPansus) }}</td>
                <td>{{ $fmt($totalTunPerum) }}</td>
                <td rowspan="4" style="vertical-align:middle;">{{ $fmt($totalJlhKotor) }}</td>
                <td rowspan="4" class="t-center" style="vertical-align:middle;">—</td>
                <td rowspan="4" class="t-center" style="vertical-align:middle;">—</td>
                <td rowspan="4" style="vertical-align:middle; background:#eef2ff;">{{ $fmt($totalPphTer) }}</td>
                <td>{{ $fmt($totalPotBpjs) }}</td>
                <td>{{ $fmt($totalPotBpjs2) }}</td>
                <td rowspan="4" style="vertical-align:middle; background:#f0fdf4;">{{ $fmt($totalBersih) }}</td>
                <td></td>
            </tr>
            <tr class="subtotal">
                <td colspan="2"></td>
                <td class="t-center"></td>
                <td>{{ $fmt($totalTunIstri) }}</td>
                <td>{{ $fmt($totalUangPaket) }}</td>
                <td>{{ $fmt($totalTunBanggar) }}</td>
                <td>{{ $fmt($totalTunPanja) }}</td>
                <td>{{ $fmt($totalTunTrans) }}</td>
                <td>{{ $fmt($totalPotJkk) }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="subtotal">
                <td colspan="2"></td>
                <td class="t-center"></td>
                <td>{{ $fmt($totalTunAnak) }}</td>
                <td>{{ $fmt($totalTunKomisi) }}</td>
                <td>{{ $fmt($totalTunBaleg) }}</td>
                <td>{{ $fmt($totalPembulatan) }}</td>
                <td>{{ $fmt($totalTunTki) }}</td>
                <td>{{ $fmt($totalPotJkm) }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="subtotal">
                <td colspan="2"></td>
                <td class="t-center"></td>
                <td>{{ $fmt($totalTunBeras) }}</td>
                <td style="color:#94a3b8;">0</td>
                <td>{{ $fmt($totalTunBk) }}</td>
                <td style="color:#94a3b8;">0</td>
                <td>{{ $fmt($totalTunBpjs+$totalTunJkk+$totalTunJkm) }}</td>
                <td>{{ $fmt($totalPotPajak) }}</td>
                <td>{{ $fmt($totalJlhPot) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="page-footer">
        <div class="footer-info">
            Jumlah Pegawai = {{ $dsbGaji->jumlah_pegawai ?? $transaksi->count() }} , Istri= {{ $dsbGaji->jumlah_is ?? 0 }} , Anak = {{ $dsbGaji->jumlah_anak ?? 0 }} , Jiwa = {{ $dsbGaji->jumlah_jiwa ?? 0 }} &nbsp;|&nbsp; Sistem TER
        </div>
        <div class="footer-info" style="background:#eef2ff; border-color:#4338ca; color:#4338ca;">
            Total PPh TER: <b>Rp {{ $fmt($totalPphTer) }}</b> &nbsp;|&nbsp; Netto: <b>Rp {{ $fmt($totalBersih) }}</b>
        </div>
    </div>

    @php $tgl=$dsbGaji->tanggal_proses ?? now(); $formattedDate=\Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('d F Y'); $kota=strtoupper($pemda->ibu_kota ?? 'BOROKO'); @endphp
    <div class="signatures">
        <div class="sig-box">
            <div style="font-weight:700;">Mengetahui,</div>
            <div style="font-weight:700;">SEKRETARIS DPRD</div>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $dsbGaji->nama_pa ?? '..........................' }}</div>
            <div>NIP : {{ $dsbGaji->nip_pa ?? '' }}</div>
        </div>
        <div class="sig-box">
            <div>{{ $kota }}, {{ strtoupper($formattedDate) }}</div>
            <div style="font-weight:700;">BENDAHARA PENGELUARAN</div>
            <div class="sig-space"></div>
            <div class="sig-name">{{ $dsbGaji->nama_bendahara ?? '..........................' }}</div>
            <div>NIP : {{ $dsbGaji->nip_bendahara ?? '' }}</div>
        </div>
    </div>

    <script>window.onload=function(){window.print();};</script>
</body>
</html>
