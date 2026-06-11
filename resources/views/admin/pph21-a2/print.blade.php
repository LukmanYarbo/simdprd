<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir 1721-A2 PPh 21 - {{ $member->nama_anggota }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.25;
            margin: 20px;
            background-color: #fff;
        }

        .no-print {
            text-align: right;
            margin-bottom: 20px;
        }

        .no-print button {
            padding: 8px 18px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 10pt;
        }

        .no-print button:hover {
            background-color: #0b5ed7;
        }

        /* Form Structure */
        .form-container {
            border: 2px solid #000;
            padding: 15px;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
        }

        .form-header {
            display: flex;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header-logo {
            width: 15%;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            border-right: 1px solid #000;
            padding-right: 5px;
            font-size: 8pt;
        }

        .header-title {
            width: 65%;
            padding: 0 10px;
            text-align: center;
            border-right: 1px solid #000;
        }

        .header-title h2 {
            font-size: 11pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .header-title p {
            font-size: 7.5pt;
            margin: 0;
        }

        .header-meta {
            width: 20%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
        }

        .header-meta div {
            margin-bottom: 4px;
        }

        .meta-box {
            border: 1px solid #000;
            padding: 4px;
            font-size: 12pt;
            letter-spacing: 2px;
            display: inline-block;
            margin-top: 5px;
        }

        .section-title {
            background-color: #e5e5e5;
            border: 1px solid #000;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 9.5pt;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .info-table, .calc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .info-label {
            width: 25%;
            font-weight: bold;
        }

        .info-colon {
            width: 2%;
        }

        .info-value {
            width: 73%;
            border-bottom: 1px dashed #777;
        }

        .calc-table th, .calc-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            vertical-align: middle;
        }

        .calc-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 8.5pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .bg-light-grey {
            background-color: #fafafa;
        }

        .signatures-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 45%;
            border: 1px solid #000;
            padding: 10px;
            font-size: 8.5pt;
        }

        .sig-title {
            font-weight: bold;
            margin-bottom: 50px;
            text-align: center;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
        }

        .sig-detail {
            text-align: center;
            font-size: 8pt;
            color: #333;
        }

        .form-footer-note {
            font-size: 7.5pt;
            margin-top: 15px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-style: italic;
            color: #444;
        }

        /* Print Media Styles */
        @media print {
            body {
                margin: 0;
                background-color: #fff;
                font-size: 8pt;
            }

            .no-print {
                display: none;
            }

            .form-container {
                border: 1px solid #000;
                padding: 10px;
                max-width: 100%;
                box-shadow: none;
            }
            
            /* Fit to page height */
            @page {
                size: portrait;
                margin: 0.8cm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()"><i class="ti ti-printer"></i> Cetak Formulir 1721-A2</button>
    </div>

    @php
        $fmt = function($v) {
            return number_format($v, 0, ',', '.');
        };
    @endphp

    <div class="form-container">
        {{-- Header Form --}}
        <div class="form-header">
            <div class="header-logo">
                <div style="margin-top: 10px;">DEPARTEMEN KEUANGAN RI</div>
                <div style="font-size: 7pt; font-weight: normal; margin-top: 5px;">DIREKTORAT JENDERAL PAJAK</div>
            </div>
            <div class="header-title">
                <h2>BUKTI PEMOTONGAN PAJAK PENGHASILAN PASAL 21 (A2)</h2>
                <p>BAGI PEGAWAI NEGERI SIPIL, ANGGOTA TNI, ANGGOTA POLRI, PEJABAT NEGARA, DAN PENSIUNANNYA</p>
                <div style="margin-top: 10px; font-weight: bold; font-size: 9pt;">
                    NOMOR: 1.1 - {{ $masaPajak }} - {{ sprintf('%04d', $member->id) }}
                </div>
            </div>
            <div class="header-meta">
                <div>FORMULIR 1721-A2</div>
                <div>TAHUN PAJAK</div>
                <div class="meta-box">{{ $tahun }}</div>
            </div>
        </div>

        {{-- Bagian A: Instansi Pemerintah --}}
        <div class="section-title">A. INSTANSI PEMERINTAH / PEMBUAT BUKTI PEMOTONGAN</div>
        <table class="info-table">
            <tr>
                <td class="info-label">1. NPWP Instansi</td>
                <td class="info-colon">:</td>
                <td class="info-value fw-bold">{{ $pemda->npwp ?? '00.000.000.0-000.000' }}</td>
            </tr>
            <tr>
                <td class="info-label">2. Nama Instansi</td>
                <td class="info-colon">:</td>
                <td class="info-value fw-bold">DEWAN PERWAKILAN RAKYAT DAERAH (DPRD) {{ strtoupper($pemda->kabupaten ?? '') }}</td>
            </tr>
        </table>

        {{-- Bagian B: Identitas Pegawai/Pejabat Negara --}}
        <div class="section-title">B. PEGAWAI / PENERIMA PENGHASILAN YANG DIPOTONG JABATANNYA</div>
        <table class="info-table">
            <tr>
                <td class="info-label">1. N P W P</td>
                <td class="info-colon">:</td>
                <td class="info-value fw-bold">{{ $calc['npwp'] }}</td>
            </tr>
            <tr>
                <td class="info-label">2. N I K / No. Paspor</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $calc['nik'] }}</td>
            </tr>
            <tr>
                <td class="info-label">3. Nama Lengkap</td>
                <td class="info-colon">:</td>
                <td class="info-value fw-bold">{{ strtoupper($calc['nama']) }}</td>
            </tr>
            <tr>
                <td class="info-label">4. Pangkat / Golongan</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $calc['pangkat'] }}</td>
            </tr>
            <tr>
                <td class="info-label">5. Nama Jabatan</td>
                <td class="info-colon">:</td>
                <td class="info-value fw-bold">{{ strtoupper($calc['jabatan']) }}</td>
            </tr>
            <tr>
                <td class="info-label">6. Jenis Kelamin</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $calc['jenis_kelamin'] == 'P' || $calc['jenis_kelamin'] == 'Perempuan' ? 'Perempuan' : 'Laki-Laki' }}</td>
            </tr>
            <tr>
                <td class="info-label">7. Status / Tanggungan PTKP</td>
                <td class="info-colon">:</td>
                <td class="info-value fw-bold">
                    {{ $calc['status_ptkp'] }}
                    <span style="font-weight: normal; font-size: 8pt; margin-left: 15px;">
                        ( Status: {{ $calc['status_kawin'] == 'K' ? 'Kawin' : 'Tidak Kawin' }}, Tanggungan: {{ min($calc['jumlah_anak'], 3) }} )
                    </span>
                </td>
            </tr>
        </table>

        {{-- Bagian C: Rincian Penghasilan dan Perhitungan PPh --}}
        <div class="section-title">C. RINCIAN PENGHASILAN DAN PENGHITUNGAN PPh PASAL 21</div>
        <table class="calc-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="60%">URAIAN PENGHASILAN & PERHITUNGAN</th>
                    <th width="35%">JUMLAH (RUPIAH)</th>
                </tr>
            </thead>
            <tbody>
                {{-- Bruto --}}
                <tr class="bg-light-grey fw-bold">
                    <td colspan="2">I. PENGHASILAN BRUTO</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center">1.</td>
                    <td>Gaji Pokok / Uang Representasi</td>
                    <td class="text-right">Rp {{ $fmt($calc['gaji_pokok']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">2.</td>
                    <td>Tunjangan Istri / Suami</td>
                    <td class="text-right">Rp {{ $fmt($calc['tunjangan_istri']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">3.</td>
                    <td>Tunjangan Anak</td>
                    <td class="text-right">Rp {{ $fmt($calc['tunjangan_anak']) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">4.</td>
                    <td>Jumlah Gaji dan Tunjangan Keluarga (1 + 2 + 3)</td>
                    <td class="text-right">Rp {{ $fmt($calc['tunjangan_keluarga']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">5.</td>
                    <td>Tunjangan Perbaikan Penghasilan</td>
                    <td class="text-right">Rp 0</td>
                </tr>
                <tr>
                    <td class="text-center">6.</td>
                    <td>Tunjangan Jabatan / Tunjangan Lain-lain (AK, Perumahan, Transportasi, TKI)</td>
                    <td class="text-right">Rp {{ $fmt($calc['tunjangan_jabatan'] + $calc['tunjangan_lain']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">7.</td>
                    <td>Tunjangan Beras</td>
                    <td class="text-right">Rp {{ $fmt($calc['tunjangan_beras']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">8.</td>
                    <td>Tunjangan Khusus</td>
                    <td class="text-right">Rp 0</td>
                </tr>
                <tr>
                    <td class="text-center">9.</td>
                    <td>Tunjangan Pajak Penghasilan (PPh 21 ditunjang)</td>
                    <td class="text-right">Rp {{ $fmt($calc['tunjangan_pph']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">10.</td>
                    <td>Premi Asuransi yang dibayar Pemberi Kerja (BPJS Kesehatan, JKK, JKM)</td>
                    <td class="text-right">Rp {{ $fmt($calc['premi_asuransi']) }}</td>
                </tr>
                <tr class="fw-bold bg-light-grey" style="border-top: 2px solid #000;">
                    <td class="text-center">11.</td>
                    <td>JUMLAH PENGHASILAN BRUTO (4 + 5 + 6 + 7 + 8 + 9 + 10)</td>
                    <td class="text-right">Rp {{ $fmt($calc['jumlah_bruto']) }}</td>
                </tr>

                {{-- Pengurangan --}}
                <tr class="bg-light-grey fw-bold">
                    <td colspan="2">II. PENGURANGAN</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center">12.</td>
                    <td>Biaya Jabatan (5% dari Bruto, maks. Rp 500.000 / bln berjalan)</td>
                    <td class="text-right text-danger">Rp {{ $fmt($calc['biaya_jabatan']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">13.</td>
                    <td>Iuran Pensiun / Iuran THT / BPJS Mandiri Pegawai</td>
                    <td class="text-right text-danger">Rp {{ $fmt($calc['iuran_pensiun']) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">14.</td>
                    <td>Jumlah Pengurangan (12 + 13)</td>
                    <td class="text-right text-danger">Rp {{ $fmt($calc['jumlah_pengurangan']) }}</td>
                </tr>

                {{-- Perhitungan PPh --}}
                <tr class="bg-light-grey fw-bold">
                    <td colspan="2">III. PENGHITUNGAN PPh PASAL 21</td>
                    <td></td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">15.</td>
                    <td>Penghasilan Neto (11 - 14)</td>
                    <td class="text-right">Rp {{ $fmt($calc['netto']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">16.</td>
                    <td>Penghasilan Neto Masa Sebelumnya (Mutasi Masuk)</td>
                    <td class="text-right">Rp 0</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">17.</td>
                    <td>Penghasilan Neto untuk Penghitungan PPh Pasal 21 (Setahun/Disetahunkan)</td>
                    <td class="text-right">Rp {{ $fmt($calc['netto']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">18.</td>
                    <td>Penghasilan Tidak Kena Pajak (PTKP)</td>
                    <td class="text-right text-danger">Rp {{ $fmt($calc['ptkp']) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">19.</td>
                    <td>Penghasilan Kena Pajak (PKP) Setahun (17 - 18, dibulatkan ke bawah ribuan penuh)</td>
                    <td class="text-right text-primary">Rp {{ $fmt($calc['pkp']) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">20.</td>
                    <td>PPh Pasal 21 atas Penghasilan Kena Pajak Setahun (Tarif Lapis Pasal 17)</td>
                    <td class="text-right text-success">Rp {{ $fmt($calc['pph_terutang']) }}</td>
                </tr>
                <tr>
                    <td class="text-center">21.</td>
                    <td>PPh Pasal 21 yang telah dipotong/dilunasi Masa Sebelumnya</td>
                    <td class="text-right">Rp 0</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">22.</td>
                    <td>PPh Pasal 21 Terutang (20 - 21)</td>
                    <td class="text-right text-success">Rp {{ $fmt($calc['pph_terutang']) }}</td>
                </tr>
                <tr class="fw-bold bg-light-grey">
                    <td class="text-center">23.</td>
                    <td>PPh Pasal 21 yang Telah Dipotong dan Dilunasi (Riil Bulanan)</td>
                    <td class="text-right text-primary">Rp {{ $fmt($calc['pph_dipotong']) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td class="text-center">24.</td>
                    <td>Selisih PPh 21 Kurang / (Lebih) Bayar (22 - 23)</td>
                    <td class="text-right {{ $calc['selisih'] > 0 ? 'text-danger' : ($calc['selisih'] < 0 ? 'text-success' : '') }}">
                        Rp {{ $fmt($calc['selisih']) }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Signatures --}}
        <div class="signatures-container">
            <div class="sig-box" style="visibility: hidden;">
                {{-- Placeholder to balance layout --}}
            </div>
            
            <div class="sig-box">
                <div style="margin-bottom: 5px;">
                    Dikeluarkan di: {{ strtoupper($pemda->ibu_kota ?? 'BOROKO') }}
                </div>
                <div style="margin-bottom: 15px;">
                    Tanggal: {{ $tanggalCetakLabel }}
                </div>
                <div class="sig-title">
                    BENDAHARA PENGELUARAN SKPD
                </div>
                <div class="sig-name">
                    {{ strtoupper($ttd ? $ttd->pegawaiAsn->nama : '..........................') }}
                </div>
                <div class="sig-detail">
                    Pangkat/Gol: {{ $ttd && $ttd->pegawaiAsn->pangkatGolongan ? ($ttd->pegawaiAsn->pangkatGolongan->pangkat . ' - ' . $ttd->pegawaiAsn->pangkatGolongan->golongan) : '' }}
                </div>
                <div class="sig-detail">
                    NIP: {{ $ttd ? $ttd->pegawaiAsn->nip : '..........................' }}
                </div>
            </div>
        </div>

        <div class="form-footer-note">
            Catatan: Bukti Pemotongan Pajak Penghasilan Pasal 21 ini sah apabila ditandatangani oleh Bendahara Pengeluaran SKPD Pemotong Pajak. Dokumen dicetak secara otomatis dari SIMDPRD.
        </div>
    </div>

</body>
</html>
