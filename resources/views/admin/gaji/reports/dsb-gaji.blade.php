<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSB Gaji - {{ $bulanLabel }} {{ $tahun }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 330mm 215.9mm; /* F4 Landscape */
            margin: 10mm 15mm;
        }
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.25;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h4 {
            margin: 2px 0;
            text-transform: uppercase;
            font-size: 11pt;
            letter-spacing: 1px;
        }
        .title-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 8pt;
        }
        .main-content {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }
        .left-panel {
            width: 55%;
        }
        .right-panel {
            width: 40%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-data td {
            padding: 1px 4px;
        }
        .table-summary {
            margin-bottom: 20px;
        }
        .table-summary th, .table-summary td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 8pt;
        }
        .table-summary th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: 700;
        }
        .border-top {
            border-top: 1.5px solid #000;
        }
        .signature-section {
            margin-top: 30px;
        }
        .signature-box {
            text-align: center;
            margin-bottom: 30px;
        }
        .signature-space {
            height: 60px;
        }
        .small {
            font-size: 8pt;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 10px; text-align: right;">
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h4>GAJI DSB UNTUK PARA KETUA, WAKIL KETUA DAN ANGGOTA DPRD</h4>
        <h4>{{ $pemda->kabupaten ?? 'KABUPATEN BOLAANG MONGONDOW UTARA' }}</h4>
        <h4 class="fw-bold">BULAN {{ $bulanLabel }} {{ $tahun }}</h4>
    </div>

    <div class="title-section">
        <div>KEMENTERIAN DALAM NEGERI REPUBLIK INDONESIA | PEMERINTAH DAERAH {{ $pemda->kabupaten ?? 'KABUPATEN BOLAANG MONGONDOW UTARA' }}</div>
        <div class="fw-bold">GAJI INDUK</div>
    </div>

    <div class="main-content">
        <!-- Kolom Kiri: Penghasilan, Potongan, Netto -->
        <div class="left-panel">
            <div class="fw-bold mb-1">PENGHASILAN : M.A</div>
            <table class="table-data">
                <tr><td>1. GAJI POKOK</td><td>Rp.</td><td class="text-right">{{ number_format($summary['gaji_pokok'], 0, ',', '.') }}</td></tr>
                <tr><td>2. TUNJANGAN ISTRI</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_istri'], 0, ',', '.') }}</td></tr>
                <tr><td>3. TUNJANGAN ANAK</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_anak'], 0, ',', '.') }}</td></tr>
                <tr><td>4. TUNJANGAN JABATAN</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_jabatan'], 0, ',', '.') }}</td></tr>
                <tr><td>5. TUNJANGAN BERAS</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_beras'], 0, ',', '.') }}</td></tr>
                <tr><td>6. TUNJANGAN PPh KHUSUS</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_pph_khusus'], 0, ',', '.') }}</td></tr>
                <tr><td>7. PEMBULATAN</td><td>Rp.</td><td class="text-right">{{ number_format($summary['pembulatan'], 0, ',', '.') }}</td></tr>
                <tr><td>8. UANG PAKET</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_paket'], 0, ',', '.') }}</td></tr>
                <tr><td>9. TUNJANGAN ALAT KELENGKAPAN</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_ak'], 0, ',', '.') }}</td></tr>
                <tr><td>10. TUNJANGAN LAINNYA</td><td>Rp.</td><td class="text-right">0</td></tr>
                <tr><td>11. TUNJANGAN PERUMAHAN</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_perumahan'], 0, ',', '.') }}</td></tr>
                <tr><td>12. TUNJANGAN KOMUNIKASI</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_tki'], 0, ',', '.') }}</td></tr>
                <tr><td>13. TUNJANGAN TRANSPORTASI</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_transportasi'], 0, ',', '.') }}</td></tr>
                <tr><td>14. TUNJANGAN RESES</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_reses'], 0, ',', '.') }}</td></tr>
                <tr><td>15. TUNJANGAN JKK</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_jkk'], 0, ',', '.') }}</td></tr>
                <tr><td>16. TUNJANGAN JKM</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_jkm'], 0, ',', '.') }}</td></tr>
                <tr><td>17. TUNJANGAN BPJS 4 %</td><td>Rp.</td><td class="text-right">{{ number_format($summary['tunjangan_bpjs'], 0, ',', '.') }}</td></tr>
                <tr class="fw-bold border-top">
                    <td>JUMLAH PENGHASILAN</td><td>Rp.</td><td class="text-right">{{ number_format($summary['total_penghasilan'], 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="fw-bold mt-2">POTONGAN</div>
            <table class="table-data">
                <tr><td>1. PPh Pasal 21</td><td>Rp.</td><td class="text-right">{{ number_format($summary['potongan_pph21'], 0, ',', '.') }}</td></tr>
                <tr><td>2. Iuran BPJS 1 %</td><td>Rp.</td><td class="text-right">{{ number_format($summary['potongan_bpjs1'], 0, ',', '.') }}</td></tr>
                <tr><td>3. Iuran JKK</td><td>Rp.</td><td class="text-right">{{ number_format($summary['potongan_jkk'], 0, ',', '.') }}</td></tr>
                <tr><td>4. Iuran JKM</td><td>Rp.</td><td class="text-right">{{ number_format($summary['potongan_jkm'], 0, ',', '.') }}</td></tr>
                <tr><td>5. Iuran BPJS 3 %</td><td>Rp.</td><td class="text-right">{{ number_format($summary['potongan_bpjs3'], 0, ',', '.') }}</td></tr>
                <tr class="fw-bold border-top">
                    <td>JUMLAH POTONGAN</td><td>Rp.</td><td class="text-right">{{ number_format($summary['total_potongan'], 0, ',', '.') }}</td>
                </tr>
            </table>

            <table class="table-data mt-2 border-top">
                <tr class="fw-bold" style="font-size: 10pt;">
                    <td style="width: 140px;">JUMLAH BERSIH :</td><td style="width: 30px;">Rp.</td><td class="text-right">{{ number_format($summary['jumlah_bersih'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="small fw-bold">Terbilang :</td><td colspan="2" class="small fw-bold" style="font-style: italic; padding-left: 4px;">{{ $summary['terbilang'] }}</td>
                </tr>
            </table>
        </div>

        <!-- Kolom Kanan: Summary Table, Lampiran, Tanda Tangan -->
        <div class="right-panel">
            <table class="table-summary">
                <thead>
                    <tr>
                        <th>JABATAN</th>
                        <th>PEG</th>
                        <th>I/S</th>
                        <th>ANAK</th>
                        <th>JIWA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">KETUA</td>
                        <td>{{ $dsbGaji->jumlah_ketua ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_is_ketua ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_anak_ketua ?? 0 }}</td>
                        <td>{{ ($dsbGaji->jumlah_ketua ?? 0) + ($dsbGaji->jumlah_is_ketua ?? 0) + ($dsbGaji->jumlah_anak_ketua ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td class="text-start">WAKIL KETUA</td>
                        <td>{{ $dsbGaji->jumlah_wakil ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_is_wakil ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_anak_wakil ?? 0 }}</td>
                        <td>{{ ($dsbGaji->jumlah_wakil ?? 0) + ($dsbGaji->jumlah_is_wakil ?? 0) + ($dsbGaji->jumlah_anak_wakil ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td class="text-start">ANGGOTA</td>
                        <td>{{ $dsbGaji->jumlah_anggota ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_is_anggota ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_anak_anggota ?? 0 }}</td>
                        <td>{{ ($dsbGaji->jumlah_anggota ?? 0) + ($dsbGaji->jumlah_is_anggota ?? 0) + ($dsbGaji->jumlah_anak_anggota ?? 0) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td>TOTAL</td>
                        <td>{{ $dsbGaji->jumlah_pegawai ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_is ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_anak ?? 0 }}</td>
                        <td>{{ $dsbGaji->jumlah_jiwa ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="small fw-bold mt-2">
                LAMPIRAN :<br>
                HARAP SP2D DI TERBITKAN ATAS NAMA
            </div>
            
            <div class="fw-bold mt-1" style="font-size: 9pt;">
                BENDAHARA PENGELUARAN<br>
                DEWAN PERWAKILAN RAKYAT DAERAH
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    @php
                        $tgl = $dsbGaji->tanggal_proses ?? now();
                        $formattedDate = \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y');
                    @endphp
                    <div>BOROKO, {{ strtoupper($formattedDate) }}</div>
                    <div class="fw-bold">BENDAHARA PENGELUARAN</div>
                    <div class="signature-space"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ $dsbGaji->nama_bendahara ?? '..........................' }}</div>
                    <div class="small">{{ $dsbGaji->golongan_bendahara ?? '' }}</div>
                    <div class="small">NIP : {{ $dsbGaji->nip_bendahara ?? '' }}</div>
                    
                </div>
                
                <div class="signature-box mt-3">
                    <div class="fw-bold">Mengetahui / Menyetujui,</div>
                    <div class="fw-bold">SEKRETARIS DPRD</div>
                    <div class="signature-space"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ $dsbGaji->nama_pa ?? '..........................' }}</div>
                    <div class="small">{{ $dsbGaji->golongan_pa ?? '' }}</div>
                    <div class="small">NIP : {{ $dsbGaji->nip_pa ?? '' }}</div>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
