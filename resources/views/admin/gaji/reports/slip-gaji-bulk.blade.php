<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Slip Gaji - {{ $bulanLabel }} {{ $tahun }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/reports/slip-gaji.css') }}">
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right; padding: 10px;">
        <button onclick="window.print()" style="padding: 6px 16px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold;">Cetak Seluruh Slip Gaji</button>
    </div>

    @foreach($transaksi as $t)
    <div class="slip-wrapper">
        @php
            $fmt = function($v) { return number_format($v, 0, ',', '.'); };
            
            $a = $t->anggota;
            
            // Formatter for status sipil
            $jlhIs = $t->jumlah_is ?? 0;
            $jlhAnak = $t->jumlah_anak ?? 0;
            $statusKawin = $t->status_kawin ?? 'K'; 
            $statusSipilLabel = "Pria Kawin Tidak Punya Anak"; 
            if ($statusKawin == 'K' && $jlhAnak == 0) $statusSipilLabel = "Pria Kawin Tidak Punya Anak";
            else if ($statusKawin == 'K' && $jlhAnak > 0) $statusSipilLabel = "Pria Kawin Beranak $jlhAnak";
            else if ($statusKawin == 'TK') $statusSipilLabel = "Tidak Kawin / Lajang";
            else $statusSipilLabel = "-";
            
            $statusSipilFull = "PK / 1 - 0  {$statusSipilLabel}"; 

            // Incomes
            $gajiPokok = $t->gaji_pokok ?? 0;
            $tunIstri = $t->tunjangan_istri ?? 0;
            $tunAnak = $t->tunjangan_anak ?? 0;
            $tunBeras = $t->tunjangan_beras ?? 0;
            $uangPaket = $t->tunjangan_paket ?? 0;
            $tunJabatan = $t->tunjangan_jabatan ?? 0;
            $tunKomisi = $t->tunjangan_komisi ?? 0;
            $tunBanmus = $t->tunjangan_banmus ?? 0;
            $tunBanggar = $t->tunjangan_banggar ?? 0;
            $tunBK = $t->tunjangan_bk ?? 0;
            $tunBaleg = $t->tunjangan_balegda ?? 0;
            $tunPansus = $t->tunjangan_pansus ?? 0;
            $tunPanja = $t->tunjangan_panja ?? 0;
            $pembulatan = $t->pembulatan ?? 0;
            $tunPphKhusus = $t->PPH21_Gaji ?? 0; 
            $tunBpjs4 = $t->tunjangan_bpjs ?? 0; 
            $tunJkk = $t->tunjangan_jkk ?? 0;
            $tunJkm = $t->tunjangan_jkm ?? 0;

            $jumlahPenghasilan = $gajiPokok + $tunIstri + $tunAnak + $tunBeras + $uangPaket + $tunJabatan + $tunKomisi + $tunBanmus + $tunBanggar + $tunBK + $tunBaleg + $tunPansus + $tunPanja + $pembulatan + $tunPphKhusus + $tunBpjs4 + $tunJkk + $tunJkm;

            // Allowances (Tunjangan II)
            $tunPerumahan = $t->tunjangan_perumahan ?? 0;
            $tunTki = $t->tunjangan_tki ?? 0;
            $tunTransportasi = $t->tunjangan_transportasi ?? 0;
            $tunReses = $t->tunjangan_reses ?? 0;

            $jumlahTunjangan = $tunPerumahan + $tunTki + $tunTransportasi + $tunReses;

            // Deductions
            $potPphPerum = $t->potonganpph_perumahan ?? 0;
            $potPphTki = $t->potonganpph_tki ?? 0;
            $potPphTrans = $t->potonganpph_transportasi ?? 0;
            $potPphReses = 0; 
            
            // Helper for Alat Kelengkapan
            $jabatanKomisi = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 2);
            $jabatanBanmus = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 3);
            $jabatanBanggar = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 4);
            $jabatanBk = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 5);
            $jabatanBalegda = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 6);
            $jabatanPansus = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 7);
            $jabatanPanja = $a->jabatanAnggota->firstWhere('id_alat_kelengkapan', 8);
            
            $displayKomisi = $jabatanKomisi ? ($jabatanKomisi->jabatanAlatKelengkapan->nama . ($jabatanKomisi->nama_komisi ? ' Komisi ' . $jabatanKomisi->nama_komisi : ' Komisi')) : '-';
            $displayBanmus = $jabatanBanmus ? ($jabatanBanmus->jabatanAlatKelengkapan->nama . ' Badan Musyawarah') : '-';
            $displayBanggar = $jabatanBanggar ? ($jabatanBanggar->jabatanAlatKelengkapan->nama . ' Badan Anggaran') : '-';
            $displayBk = $jabatanBk ? ($jabatanBk->jabatanAlatKelengkapan->nama . ' Badan Kehormatan') : '-';
            $displayBalegda = $jabatanBalegda ? ($jabatanBalegda->jabatanAlatKelengkapan->nama . ' Badan Legislasi Daerah') : '-';
            $displayPansus = $jabatanPansus ? ($jabatanPansus->jabatanAlatKelengkapan->nama . ' Panitia Khusus') : '-';
            $displayPanja = $jabatanPanja ? ($jabatanPanja->jabatanAlatKelengkapan->nama . ' Panitia Kerja') : '-';
            
            $potBpjs1 = $t->potongan_bpjs ?? 0;
            $potJkk = $t->potongan_jkk ?? 0;
            $potJkm = $t->potongan_jkm ?? 0;
            $potPphKhusus = $tunPphKhusus; 
            $potBpjs4 = $t->potongan_bpjs2 ?? 0; 

            $jumlahPotongan = $potPphPerum + $potPphTki + $potPphTrans + $potPphReses + $potBpjs1 + $potJkk + $potJkm + $potPphKhusus + $potBpjs4;
            
            $jumlahKotorTotal = $jumlahPenghasilan + $jumlahTunjangan;
            $jumlahKotor = $t->nilai_gajitunjangan ?? $jumlahKotorTotal;
            $potonganTotal = ($t->potongan_bpjs ?? 0) + ($t->potongan_jkk ?? 0) + ($t->potongan_jkm ?? 0) + ($t->potongan_pph21 ?? 0) + ($t->potongan_bpjs2 ?? 0) + ($t->potonganpph_perumahan ?? 0) + ($t->potonganpph_transportasi ?? 0) + ($t->potonganpph_tki ?? 0); 
            
            $jumlahPotonganView = $potonganTotal;
            $jumlahNetto = $t->jumlah_bersih ?? ($jumlahKotor - $potonganTotal); 
            
            $kota = strtoupper($pemda->ibu_kota ?? 'BOROKO');
            $tglDikeluarkan = \Carbon\Carbon::parse($dsbGaji->tanggal_proses ?? now())->locale('id')->translatedFormat('d F Y');
            
            // Use controller helper for terbilang
            $terbilang = ucwords(trim($controller->terbilang($jumlahNetto))) . " Rupiah";
        @endphp

        <div class="header">
            <h3>PEMERINTAH DAERAH {{ strtoupper($pemda->kabupaten ?? 'KABUPATEN BOLAANG MONGONDOW UTARA') }}</h3>
            <h4>DEWAN PERWAKILAN RAKYAT DAERAH</h4>
            <h4>SURAT KETERANGAN PENGHASILAN</h4>
        </div>

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
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"/>
                    </svg>
                @endif
            </div>
        </div>

        <div class="content-columns">
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

            <div class="col-right">
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
                    <tr>
                        <td class="col-desc">- Pot. PPh 21 Gaji Induk</td>
                        <td class="col-curr">Rp</td>
                        <td class="col-val">{{ $fmt($t->potongan_pph21 ?? 0) }}</td>
                    </tr>
                    <tr class="subtotal-row" style="border-bottom: 2px solid #000;">
                        <td>Jumlah Potongan</td>
                        <td>Rp</td>
                        <td class="col-val">{{ $fmt($jumlahPotonganView) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <table class="item-table" style="width: 100%;">
            <tr class="netto-row">
                <td style="width: 25%;">Jumlah Bersih</td>
                <td style="width: 5%; font-weight: bold;">Rp</td>
                <td style="width: 25%; font-weight: bold; font-size: 11pt;" class="text-right">{{ $fmt($jumlahNetto) }}</td>
                <td style="width: 45%;"></td> 
            </tr>
            <tr class="terbilang-row">
                <td style="font-weight: bold;">Terbilang</td>
                <td colspan="3" class="terbilang-text">{{ $terbilang }}</td>
            </tr>
        </table>

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
    </div>
    @endforeach

</body>
</html>
