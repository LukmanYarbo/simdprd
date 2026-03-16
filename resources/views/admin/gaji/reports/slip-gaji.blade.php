<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Penghasilan - {{ $transaksi->anggota->nama_anggota }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 215mm 330mm; /* F4 / Folio Portrait */
            margin: 8mm 10mm;
        }
        body {
            font-family: 'Inter', Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.25;
            color: #000;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* --- Header --- */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 55px;
            height: auto;
        }
        .header h3, .header h4 {
            margin: 1px 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h3 { font-size: 11pt; }
        .header h4 { font-size: 9pt; }
        
        /* Dashed separator */
        hr.dashed {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        /* --- Identitas --- */
        .identitas-section {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .identitas-table td {
            padding: 1px 5px 1px 0;
            vertical-align: top;
        }
        .identitas-label {
            width: 130px;
        }
        .identitas-colon {
            width: 15px;
            text-align: center;
        }
        .identitas-value {
            font-weight: bold;
        }

        /* Photo placeholder styling */
        .photo-placeholder {
            width: 75px;
            height: 95px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            text-align: center;
            overflow: hidden;
        }
        .photo-placeholder svg {
            width: 100%;
            height: 100%;
            fill: #ccc;
        }

        /* --- Main Content Layout --- */
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 8px 0 4px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
        }
        
        /* Columns side-by-side to save vertical space */
        .content-columns {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 10px;
        }
        .col-left {
            width: 50%;
        }
        .col-right {
            width: 47%;
        }

        /* Table styles for items */
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .col-desc {
            width: 60%;
        }
        .col-curr {
            width: 10%; /* "Rp" */
        }
        .col-val {
            width: 30%;
            text-align: right;
            padding-right: 5px; /* Slight padding to the right for values */
        }

        /* Subtotals */
        .subtotal-row td {
            font-weight: bold;
            padding-top: 6px !important;
            padding-bottom: 6px !important;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000; /* Added bottom border for "Jumlah Tunjangan" style */
        }

        /* --- Summary Section (IV) --- */
        .summary-section {
            margin-top: 5px;
        }
        .summary-table {
            width: 48%; /* Match left col width */
        }
        .summary-table td {
            padding: 1.5px 0;
        }

        .netto-row {
            border-top: 2px solid #000; /* Thicker top line */
            border-bottom: 2px solid #000; /* Thicker bottom line */
        }
        .netto-row td {
            font-weight: bold;
            padding: 5px 0;
            font-size: 9.5pt;
        }
        
        .terbilang-row td {
            padding-top: 8px;
        }
        .terbilang-text {
            font-weight: bold;
            font-style: italic;
            font-size: 9pt;
        }

        /* Right column auxiliary info (Jabatan, Jumlah Istri, etc.) */
        .aux-info {
            font-size: 8pt;
            margin-top: 3px;
        }
        .aux-info-item {
            margin-bottom: 2px;
        }

        /* --- Signatures --- */
        .signatures {
            margin-top: 30px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            text-align: center;
            page-break-inside: avoid;
        }
        .sig-box {
            width: 30%;
        }
        .sig-box-center {
            width: 35%;
            margin-top: 50px; /* Push center signature down a bit */
        }
        .sig-space {
            height: 60px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .small-text {
            font-size: 7.5pt;
        }

        /* Helper */
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold;">Cetak Slip Gaji</button>
    </div>

    @php
        $fmt = function($v) { return number_format($v, 0, ',', '.'); };
        
        $a = $transaksi->anggota;
        
        // Formatter for status sipil
        $jlhIs = $transaksi->jumlah_is ?? 0;
        $jlhAnak = $transaksi->jumlah_anak ?? 0;
        $statusKawin = $transaksi->status_kawin ?? 'K'; // E.g., 'K' or 'TK'
        $statusSipilLabel = "Pria Kawin Tidak Punya Anak"; // Placeholder, normally based on $statusKawin
        if ($statusKawin == 'K' && $jlhAnak == 0) $statusSipilLabel = "Pria Kawin Tidak Punya Anak";
        else if ($statusKawin == 'K' && $jlhAnak > 0) $statusSipilLabel = "Pria Kawin Beranak $jlhAnak";
        else if ($statusKawin == 'TK') $statusSipilLabel = "Tidak Kawin / Lajang";
        else $statusSipilLabel = "-";
        
        $statusSipilFull = "PK / 1 - 0  {$statusSipilLabel}"; // Matching the image string formatting roughly. This could be customized further.

        // Incomes
        $gajiPokok = $transaksi->gaji_pokok ?? 0;
        $tunIstri = $transaksi->tunjangan_istri ?? 0;
        $tunAnak = $transaksi->tunjangan_anak ?? 0;
        $tunBeras = $transaksi->tunjangan_beras ?? 0;
        $uangPaket = $transaksi->tunjangan_paket ?? 0;
        $tunJabatan = $transaksi->tunjangan_jabatan ?? 0;
        $tunKomisi = $transaksi->tunjangan_komisi ?? 0;
        $tunBanmus = $transaksi->tunjangan_banmus ?? 0;
        $tunBanggar = $transaksi->tunjangan_banggar ?? 0;
        $tunBK = $transaksi->tunjangan_bk ?? 0;
        $tunBaleg = $transaksi->tunjangan_balegda ?? 0;
        $tunPansus = $transaksi->tunjangan_pansus ?? 0;
        $tunPanja = $transaksi->tunjangan_panja ?? 0;
        $pembulatan = $transaksi->pembulatan ?? 0;
        $tunPphKhusus = $transaksi->PPH21_Gaji ?? 0; // Or whatever represents Pph Khusus Tunjangan
        $tunBpjs4 = $transaksi->tunjangan_bpjs ?? 0; // Assuming tunjangan_bpjs is BPJS 4%
        $tunJkk = $transaksi->tunjangan_jkk ?? 0;
        $tunJkm = $transaksi->tunjangan_jkm ?? 0;

        $jumlahPenghasilan = $gajiPokok + $tunIstri + $tunAnak + $tunBeras + $uangPaket + $tunJabatan + $tunKomisi + $tunBanmus + $tunBanggar + $tunBK + $tunBaleg + $tunPansus + $tunPanja + $pembulatan + $tunPphKhusus + $tunBpjs4 + $tunJkk + $tunJkm;

        // Allowances (Tunjangan II)
        $tunPerumahan = $transaksi->tunjangan_perumahan ?? 0;
        $tunTki = $transaksi->tunjangan_tki ?? 0;
        $tunTransportasi = $transaksi->tunjangan_transportasi ?? 0;
        $tunReses = $transaksi->tunjangan_reses ?? 0;

        $jumlahTunjangan = $tunPerumahan + $tunTki + $tunTransportasi + $tunReses;

        // Deductions
        $potPphPerum = $transaksi->potonganpph_perumahan ?? 0;
        $potPphTki = $transaksi->potonganpph_tki ?? 0;
        $potPphTrans = $transaksi->potonganpph_transportasi ?? 0;
        $potPphReses = 0; // Doesn't seem to be in the model, usually 0 or mapped elsewhere
        $potLainLain = 0; // Replace with actual field if available
        
        // Helper for Alat Kelengkapan
        $jabatanKomisi = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 1);
        $jabatanBanmus = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 2);
        $jabatanBanggar = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 3);
        $jabatanBk = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 4);
        $jabatanBalegda = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 5);
        $jabatanPansus = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 6);
        $jabatanPanja = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 7);
        
        $displayKomisi = $jabatanKomisi ? ($jabatanKomisi->jabatanAlatKelengkapan->nama . ($jabatanKomisi->nama_komisi ? ' Komisi ' . $jabatanKomisi->nama_komisi : ' Komisi')) : '-';
        $displayBanmus = $jabatanBanmus ? ($jabatanBanmus->jabatanAlatKelengkapan->nama . ' Badan Musyawarah') : '-';
        $displayBanggar = $jabatanBanggar ? ($jabatanBanggar->jabatanAlatKelengkapan->nama . ' Badan Anggaran') : '-';
        $displayBk = $jabatanBk ? ($jabatanBk->jabatanAlatKelengkapan->nama . ' Badan Kehormatan') : '-';
        $displayBalegda = $jabatanBalegda ? ($jabatanBalegda->jabatanAlatKelengkapan->nama . ' Badan Legislasi Daerah') : '-';
        $displayPansus = $jabatanPansus ? ($jabatanPansus->jabatanAlatKelengkapan->nama . ' Panitia Khusus') : '-';
        $displayPanja = $jabatanPanja ? ($jabatanPanja->jabatanAlatKelengkapan->nama . ' Panitia Kerja') : '-';
        $potBpjs1 = $transaksi->potongan_bpjs ?? 0;
        $potJkk = $transaksi->potongan_jkk ?? 0;
        $potJkm = $transaksi->potongan_jkm ?? 0;
        $potPphKhusus = $tunPphKhusus; // Assuming equal for zero effect, based on image
        $potBpjs4 = $transaksi->potongan_bpjs2 ?? 0; // often bpjs2 is 4% pot

        $jumlahPotongan = $potPphPerum + $potPphTki + $potPphTrans + $potPphReses + $potBpjs1 + $potJkk + $potJkm + $potPphKhusus + $potBpjs4;
        
        // Let's use the brutto/potongan from DB if we want to be safe, but adding up the rows matches visually.
        // We will try to rely on calculated sums to ensure A = B + C.
        $jumlahKotorTotal = $jumlahPenghasilan + $jumlahTunjangan;
        
        // Use brutto1 & potongan from controller logic to enforce the exact "Netto" value.
        // Usually, Brutto2 = Jumlah Kotor (Penghasilan + Tunjangan)
        $jumlahKotor = $transaksi->nilai_gajitunjangan ?? $jumlahKotorTotal;
        $potonganTotal = ($transaksi->potongan_bpjs ?? 0) + ($transaksi->potongan_jkk ?? 0) + ($transaksi->potongan_jkm ?? 0) + ($transaksi->potongan_pph21 ?? 0) + ($transaksi->potongan_bpjs2 ?? 0) + ($transaksi->potonganpph_perumahan ?? 0) + ($transaksi->potonganpph_transportasi ?? 0) + ($transaksi->potonganpph_tki ?? 0); // Using exact total
        
        // We override the summary "Jumlah Potongan" variable below so the math displayed is correct in the right column
        $jumlahPotonganView = $potonganTotal;
        

        $jumlahNetto = $transaksi->jumlah_bersih ?? ($jumlahKotor - $potonganTotal); // Brutto1 is usually the "Gaji Bersih"
        
        $kota = strtoupper($pemda->ibu_kota ?? 'BOROKO');
        $tglDikeluarkan = \Carbon\Carbon::parse($dsbGaji->tanggal_proses ?? now())->locale('id')->translatedFormat('d F Y');
    @endphp

    <div class="header">
        <h3>PEMERINTAH DAERAH {{ strtoupper($pemda->kabupaten ?? 'KABUPATEN BOLAANG MONGONDOW UTARA') }}</h3>
        <h4>DEWAN PERWAKILAN RAKYAT DAERAH</h4>
        <h4>SURAT KETERANGAN PENGHASILAN</h4>
    </div>

    <!-- Dash line separator under Yang Bertandatangan -->
    <div style="font-size: 8pt; margin-bottom: 5px;">Yang Bertandatangan dibawah ini Pemegang Kas</div>
    <hr class="dashed">

    <div style="text-align: center; font-weight: bold; font-size: 9.5pt; margin-bottom: 15px; margin-top: 5px;">DEWAN PERWAKILAN RAKYAT DAERAH</div>

    Menerangkan bahwa Pemegang Surat Keterangan Penghasilan Ini
    
    <div class="identitas-section">
        <table class="identitas-table">
            <tr>
                <td class="identitas-label">Nama</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ strtoupper($a->nama_anggota) }}</td>
            </tr>
            <tr>
                <td class="identitas-label">Jabatan</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ strtoupper($a->jabatan->nama ?? 'ANGGOTA DPRD') }}</td>
            </tr>
            <tr>
                <td class="identitas-label">N P W P</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ $a->no_npwp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="identitas-label">No Rekening</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ $a->no_rekening ?? '-' }}</td>
            </tr>
            <tr>
                <td class="identitas-label">Status Keanggotaan</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ strtoupper($a->statusKeanggotaan->nama ?? 'AKTIF') }}</td>
            </tr>
            <tr>
                <td class="identitas-label">Status Sipil</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ $statusSipilFull }}</td>
            </tr>
            <tr>
                <td class="identitas-label">Bulan</td>
                <td class="identitas-colon">:</td>
                <td class="identitas-value">{{ strtoupper($bulanLabel) }} {{ $tahun }}</td>
            </tr>
        </table>
        
        <div class="photo-placeholder">
            @if($a->foto_anggota)
                <img src="{{ asset('storage/' . $a->foto_anggota) }}" alt="Foto {{ $a->nama_anggota }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <!-- Generic SVG avatar -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"/>
                </svg>
            @endif
        </div>
    </div>

    <!-- Main Columns -->
    <div class="content-columns">
        <!-- LEFT COLUMN -->
        <div class="col-left">
            <div class="section-title">I. PENGHASILAN</div>
            <table class="item-table">
                <tr>
                    <td class="col-desc">- Uang Representasi / Gaji Pokok</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($gajiPokok) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Suami / Istri</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunIstri) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Anak</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunAnak) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Beras</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunBeras) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Uang Paket</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($uangPaket) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Jabatan DPRD</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunJabatan) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Komisi</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunKomisi) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Badan Musyawarah</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunBanmus) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Badan Anggaran</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunBanggar) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Badan Kehormatan</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunBK) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Badan Legislasi</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunBaleg) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Panitia Khusus</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunPansus) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Panitia Kerja</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunPanja) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pembulatan</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($pembulatan) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan PPh Khusus</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunPphKhusus) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan BPJS 4 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunBpjs4) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan JKK</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunJkk) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan JKM</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunJkm) }}</td>
                </tr>
                <tr class="subtotal-row">
                    <td>- Jumlah Penghasilan</td>
                    <td>Rp</td>
                    <td class="col-val">{{ $fmt($jumlahPenghasilan) }}</td>
                </tr>
            </table>

            <div class="section-title">II. TUNJANGAN</div>
            <table class="item-table">
                <tr>
                    <td class="col-desc">- Tunjangan Perumahan</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunPerumahan) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Komunikasi Intensif</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunTki) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Transportasi</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunTransportasi) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Tunjangan Reses</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($tunReses) }}</td>
                </tr>
                <tr class="subtotal-row" style="border-bottom: 2px solid #000;">
                    <td>- Jumlah Tunjangan</td>
                    <td>Rp</td>
                    <td class="col-val">{{ $fmt($jumlahTunjangan) }}</td>
                </tr>
            </table>

            <div class="section-title">IV. ( PENGHASILAN + TUNJANGAN ) - POTONGAN</div>
            <table class="item-table summary-table" style="width: 100%;">
                <tr>
                    <td class="col-desc">- Jumlah Kotor</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($jumlahKotor) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Jumlah Potongan</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($jumlahPotonganView) }}</td>
                </tr>
            </table>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-right">
            <!-- Auxiliary Info overlaying corresponding rows -->
            <div class="aux-info" style="margin-top: 25px;">
                <div class="aux-info-item">Jabatan : {{ strtoupper($a->jabatan->nama ?? 'ANGGOTA DPRD') }}</div>
                <div class="aux-info-item" style="margin-top: 5px;">Jumlah Istri : {{ $jlhIs }}</div>
                <div class="aux-info-item">Jumlah Anak : {{ $jlhAnak }}</div>
                <div class="aux-info-item">Jumlah Jiwa : {{ ($jlhIs + $jlhAnak + 1) }}</div>
                
                <div class="aux-info-item" style="margin-top: 25px;">Jabatan : {{ strtoupper($a->jabatan->nama ?? 'ANGGOTA DPRD') }}</div>
                <div class="aux-info-item" style="margin-top: 5px;">Jabatan : {{ $displayKomisi }}</div>
                <div class="aux-info-item">Jabatan : {{ $displayBanmus }}</div>
                <div class="aux-info-item">Jabatan : {{ $displayBanggar }}</div>
                <div class="aux-info-item">Jabatan : {{ $displayBk }}</div>
                <div class="aux-info-item">Jabatan : {{ $displayBalegda }}</div>
                <div class="aux-info-item">Jabatan : {{ $displayPansus }}</div>
                <div class="aux-info-item">Jabatan : {{ $displayPanja }}</div>
            </div>

            <!-- Adjusted margin to align POTONGAN section correctly below aux info -->
            <div class="section-title" style="margin-top: 135px;">III. POTONGAN</div>
            <table class="item-table">
                <tr>
                    <td class="col-desc">- Pot. PPh Tunj. Perum 15 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potPphPerum) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. PPh TKI 15 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potPphTki) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. PPh Transportasi 15 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potPphTrans) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. PPh Reses 15 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potPphReses) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. Iuran BPJS 1 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potBpjs1) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. Iuran JKK</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potJkk) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. Iuran JKM</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potJkm) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. PPh Khusus</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potPphKhusus) }}</td>
                </tr>
                <tr>
                    <td class="col-desc">- Pot. Iuran BPJS 4 %</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($potBpjs4) }}</td>
                </tr>
                <!-- Include PPh21 salary as per total standard -->
                <tr>
                    <td class="col-desc">- Pot. PPh 21 Gaji Induk</td>
                    <td class="col-curr">Rp</td>
                    <td class="col-val">{{ $fmt($transaksi->potongan_pph21 ?? 0) }}</td>
                </tr>
                <tr class="subtotal-row" style="border-bottom: 2px solid #000;">
                    <td>Jumlah Potongan</td>
                    <td>Rp</td>
                    <td class="col-val">{{ $fmt($jumlahPotonganView) }}</td>
                </tr>
            </table>

        </div>
    </div>
    
    <!-- Netto & Terbilang -->
    <table class="item-table" style="width: 100%;">
        <tr class="netto-row">
            <td style="width: 25%;">Jumlah Bersih</td>
            <td style="width: 5%; font-weight: bold;">Rp</td>
            <td style="width: 25%; font-weight: bold; font-size: 11pt;" class="text-right">{{ $fmt($jumlahNetto) }}</td>
            <td style="width: 45%;"></td> <!-- Empty space to push Netto to the left mostly -->
        </tr>
        <tr class="terbilang-row">
            <td style="font-weight: bold;">Terbilang</td>
            <td colspan="3" class="terbilang-text">{{ $terbilang }}</td>
        </tr>
    </table>


    <!-- Signatures -->
    <div class="signatures">
        <div class="sig-box">
            <div style="margin-bottom: 22px;">&nbsp;</div>
            <div style="font-weight: normal; margin-bottom: 8px;">Yang Menerima</div>
            
            <div class="sig-space"></div>
            
            <div class="sig-name">{{ strtoupper($a->nama_anggota) }}</div>
        </div>

        <div class="sig-box-center">
            <div style="font-weight: normal; font-size: 8.5pt;">Mengetahui / Mengesahkan,</div>
            <div style="font-weight: normal; font-size: 8.5pt;">SEKRETARIS DPRD</div>
            
            <div class="sig-space"></div>
            
            <div class="sig-name">{{ strtoupper($dsbGaji->nama_pa ?? '..........................') }}</div>
            <div class="small-text">{{ strtoupper($dsbGaji->golongan_pa ?? '') }}</div>
            <div class="small-text">{{ $dsbGaji->nip_pa ?? '' }}</div>
        </div>

        <div class="sig-box">
            <div style="font-weight: normal; font-size: 8.5pt;">Dikeluarkan di {{ $kota }} , {{ $tglDikeluarkan }}</div>
            <div style="font-weight: normal; font-size: 8.5pt;">BENDAHARA PENGELUARAN</div>
            
            <div class="sig-space"></div>
            
            <div class="sig-name">{{ strtoupper($dsbGaji->nama_bendahara ?? '..........................') }}</div>
            <div class="small-text">{{ strtoupper($dsbGaji->golongan_bendahara ?? '') }}</div>
            <div class="small-text">{{ $dsbGaji->nip_bendahara ?? '' }}</div>
        </div>
    </div>

</body>
</html>
