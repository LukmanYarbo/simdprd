<div style="min-width: 0; max-width: 100%;">
    {{-- CSS untuk Animasi Loading Modern --}}
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            z-index: 10000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .loader-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 3rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .spinner-modern {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(#00d2ff, #3a7bd5, #00d2ff);
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 10px), #fff 0);
            mask: radial-gradient(farthest-side, transparent calc(100% - 10px), #fff 0);
            animation: spin-modern 1.2s linear infinite;
        }

        @keyframes spin-modern {
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
            background: linear-gradient(to right, #00d2ff, #91eaff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Modern Status Modal Styles */
        .modern-input-group .input-group-text {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }
        .modern-input-group .form-select, .modern-input-group .form-control {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            font-size: 0.95rem;
        }
        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .shadow-primary-subtle { box-shadow: 0 10px 15px -3px rgba(13, 110, 253, 0.2); }
        .animate__zoomIn { animation: zoomIn 0.3s ease-out; }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    {{-- Loading Overlay --}}
    <div wire:loading wire:target="prosesGaji">
        <div class="loading-overlay">
            <div class="loader-box">
                <div class="spinner-modern mb-3 mx-auto"></div>
                <div class="loading-text">Sedang Menghitung Gaji...</div>
                <p class="mb-0 text-white-50 mt-2">Mohon tunggu sebentar, data sedang diproses.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-cash-coin me-2"></i>Proses Gaji Anggota DPRD</h5>
        </div>
        <div class="card-body">
            {{-- Alert parameter tidak lengkap --}}
            @if(!$paramLengkap)
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <strong>Parameter tidak lengkap!</strong> Pastikan data berikut sudah diisi dengan status Aktif (Y):
                    <ul class="mb-0 mt-1">
                        @foreach($missingParams as $m)
                        <li>{{ $m }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Baris 1: Filter Periode & Metode --}}
            <div class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted text-uppercase">Bulan / Periode Gaji</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar3 text-primary"></i></span>
                        <select wire:model.live="bulan" class="form-select bg-light border-start-0 shadow-none">
                            <optgroup label="Gaji Bulanan">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </optgroup>
                            <optgroup label="Gaji Khusus">
                                <option value="THR">THR</option>
                                <option value="G13">Gaji Ke-13</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Tahun</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event text-primary"></i></span>
                        <input type="number" wire:model.live="tahun" class="form-control bg-light border-start-0 shadow-none" min="2020" max="2099" placeholder="2026">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Metode Perhitungan Pajak</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-calculator text-primary"></i></span>
                        <select wire:model.live="metodePajak" class="form-select bg-light border-start-0 shadow-none">
                            <option value="ter">Sistem Baru (TER)</option>
                            <option value="lapis">Lapis Pajak Lama</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Cetak</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-check text-primary"></i></span>
                        <input type="date" wire:model.live="tanggalCetak" class="form-control bg-light border-start-0 shadow-none">
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-10">

            {{-- Baris 2: Status & Kontrol Utama --}}
            <div class="row g-3 align-items-center">
                <div class="col-md-6 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 {{ $sudahDiproses ? (strtoupper($dsbGajiRecord->status ?? 'DRAF') === 'FINAL' ? 'bg-success-subtle' : 'bg-warning-subtle') : 'bg-light' }}">
                            <i class="bi {{ $sudahDiproses ? (strtoupper($dsbGajiRecord->status ?? 'DRAF') === 'FINAL' ? 'bi-shield-fill-check text-success' : 'bi-shield-fill-exclamation text-warning') : 'bi-shield-slash text-muted' }} fs-3"></i>
                        </div>
                        <div>
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1 d-block">Status Dokumen Gaji</label>
                            @if($sudahDiproses && $dsbGajiRecord)
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    @php 
                                        $isFinalStatus = strtoupper($dsbGajiRecord->status ?? '') === 'FINAL';
                                    @endphp
                                    <span class="badge {{ $isFinalStatus ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill shadow-sm">
                                        <i class="bi {{ $isFinalStatus ? 'bi-lock-fill' : 'bi-pencil-square' }} me-1"></i>
                                        {{ strtoupper($dsbGajiRecord->status) }}
                                    </span>
                                    <button wire:click="openStatusModal" class="btn btn-sm btn-link text-decoration-none fw-semibold p-0 ms-1">
                                        <i class="bi bi-pencil-square me-1"></i>Ganti Status
                                    </button>
                                </div>
                                @if($dsbGajiRecord->alasan_perubahan)
                                    <div class="text-muted mt-1 small text-truncate" style="max-width: 300px;" title="{{ $dsbGajiRecord->alasan_perubahan }}">
                                        <i class="bi bi-chat-left-dots me-1"></i>{{ $dsbGajiRecord->alasan_perubahan }}
                                    </div>
                                @endif
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill opacity-50">BELUM DIPROSES</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        @if($sudahDiproses)
                            @php 
                                $isFinal = strtoupper($dsbGajiRecord->status ?? '') === 'FINAL';
                            @endphp
                            <button onclick="confirmProses(true)" class="btn btn-warning px-4 py-2 rounded-pill shadow-sm" @if(!$paramLengkap || $isFinal) disabled @endif @if($isFinal) title="Status FINAL - Tidak dapat diproses ulang" @endif>
                                <i class="bi bi-arrow-clockwise me-1"></i> Proses Ulang
                            </button>
                            <button onclick="confirmHapus()" class="btn btn-outline-danger px-4 py-2 rounded-pill" @if($isFinal) disabled @endif @if($isFinal) title="Status FINAL - Tidak dapat dihapus" @endif>
                                <i class="bi bi-trash3 me-1"></i> Hapus Seluruh Data
                            </button>
                        @else
                            <button onclick="confirmProses(false)" class="btn btn-primary px-5 py-2 rounded-pill shadow-lg shadow-primary-subtle" @if(!$paramLengkap) disabled @endif>
                                <i class="bi bi-lightning-charge-fill me-1"></i> Mulai Proses Gaji Periode Ini
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Alert Detail --}}
            @if($sudahDiproses)
            @php 
                $currStatus = strtoupper($dsbGajiRecord->status ?? 'DRAF');
            @endphp
            <div class="alert {{ $currStatus === 'FINAL' ? 'alert-success border-success-subtle' : 'alert-info border-info-subtle' }} d-flex align-items-center gap-3 mt-4 mb-0 rounded-4">
                <div class="fs-4"><i class="bi {{ $currStatus === 'FINAL' ? 'bi-lock-fill' : 'bi-info-circle-fill' }}"></i></div>
                <div>
                    <div class="fw-bold mb-0">Informasi Periode {{ $blnThnLabel }}</div>
                    <div class="small">
                        Data telah diproses dengan status <strong>{{ $currStatus }}</strong>.
                        @if($currStatus === 'FINAL')
                            Sistem telah mengunci data ini untuk keperluan pelaporan tetap. Kembalikan status ke DRAF jika perlu perbaikan.
                        @else
                            Gunakan tombol <strong>Proses Ulang</strong> jika terdapat perubahan pada parameter master atau pimpinan.
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Kartu Kedua: Pencetakan DSB --}}
    @if($sudahDiproses)
    <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInUp animate__faster">
        <div class="card-body py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                    <i class="bi bi-file-earmark-pdf text-success fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Dokumen Laporan Gaji</h6>
                    <small class="text-muted">Cetak laporan gaji untuk lampiran pengajuan atau arsip pemerintah daerah.</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.transaksi-gaji.daftar-gaji', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-primary px-4 rounded-pill shadow-sm">
                    <i class="bi bi-table me-2"></i> Cetak Daftar Gaji
                </a>
                <a href="{{ route('admin.transaksi-gaji.export-excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-outline-success px-4 rounded-pill shadow-sm">
                    <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
                </a>
                <a href="{{ route('admin.transaksi-gaji.dsb-report', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-success px-4 rounded-pill shadow-sm">
                    <i class="bi bi-printer-fill me-2"></i> Cetak DSB
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabel Hasil --}}
    @if(!empty($ringkasan))
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 text-success fw-bold">
                <i class="bi bi-table me-2"></i>Data Transaksi Gaji — {{ $blnThnLabel }}
                <span class="badge bg-success ms-2">{{ count($ringkasan) }} anggota</span>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover table-bordered align-middle mb-0 table-sm text-nowrap" style="font-size: 0.80rem;">
                    <thead class="text-center">
                        {{-- Row 1: Grup Header --}}
                        <tr class="table-primary">
                            <th rowspan="2" class="align-middle">No</th>
                            <th rowspan="2" class="align-middle text-start" style="min-width:140px">Nama Anggota</th>
                            <th colspan="9" class="table-primary border">Penghasilan Rutin</th>
                            <th colspan="7" class="table-success border">Tunjangan Alat Kelengkapan</th>
                            <th colspan="3" class="table-info border">Asuransi</th>
                            <th colspan="4" class="table-warning border">Detail TER</th>
                            <th colspan="2" class="table-secondary border">Brutto</th>
                            <th colspan="6" class="table-danger border">Potongan</th>
                            <th colspan="5" class="table-primary border">Rekap</th>
                        </tr>
                        {{-- Row 2: Sub-kolom --}}
                        <tr class="table-light">
                            {{-- Penghasilan Rutin (9) --}}
                            <th class="text-end">Gaji Pokok</th>
                            <th class="text-end">Tj. Istri</th>
                            <th class="text-end">Tj. Anak</th>
                            <th class="text-end">Tj. Beras</th>
                            <th class="text-end">Tj. Jabatan</th>
                            <th class="text-end">Uang Paket</th>
                            <th class="text-end">Tj. Perumahan</th>
                            <th class="text-end">Tj. Transport</th>
                            <th class="text-end">Tj. TKI</th>
                            {{-- Alat Kelengkapan (7) --}}
                            <th class="text-end">Komisi</th>
                            <th class="text-end">Banggar</th>
                            <th class="text-end">Banmus</th>
                            <th class="text-end">Balegda</th>
                            <th class="text-end">BK</th>
                            <th class="text-end">Pansus</th>
                            <th class="text-end">Panja</th>
                            {{-- Asuransi (3) --}}
                            <th class="text-end">Tj. BPJS</th>
                            <th class="text-end">Tj. JKK</th>
                            <th class="text-end">Tj. JKM</th>
                            {{-- Detail TER (4) --}}
                            <th class="text-center">Kategori</th>
                            <th class="text-end">Tarif (%)</th>
                            <th class="text-end">PPh Gaji</th>
                            <th class="text-end">PPh Tun</th>
                            {{-- Brutto (2) --}}
                            <th class="text-end">Brutto 1</th>
                            <th class="text-end">Brutto 2</th>
                            {{-- Potongan (6) --}}
                            <th class="text-end">Pot. PPh21</th>
                            <th class="text-end">Pot. PPh Perum</th>
                            <th class="text-end">Pot. PPh Trans</th>
                            <th class="text-end">Pot. PPh TKI</th>
                            <th class="text-end">Pot. BPJS</th>
                            <th class="text-end">Pot. JKK+JKM</th>
                            {{-- Rekap (5) --}}
                            <th class="text-end">Tj. PPh21</th>
                            <th class="text-end">Pembulatan</th>
                            <th class="text-end">Total Pendapatan</th>
                            <th class="text-end">Total Potongan</th>
                            <th class="text-end">Jumlah Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ringkasan as $idx => $row)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ $row['nama'] }}</td>
                            {{-- Penghasilan Rutin --}}
                            <td class="text-end">{{ number_format($row['gaji_pokok'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_istri'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_anak'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_beras'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_jabatan'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_paket'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_perumahan'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_transportasi'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_tki'], 0, ',', '.') }}</td>
                            {{-- Alat Kelengkapan --}}
                            <td class="text-end">{{ number_format($row['tunjangan_komisi'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_banggar'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_banmus'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_balegda'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_bk'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_pansus'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_panja'], 0, ',', '.') }}</td>
                            {{-- Asuransi --}}
                            <td class="text-end">{{ number_format($row['tunjangan_bpjs'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_jkk'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['tunjangan_jkm'], 0, ',', '.') }}</td>
                            {{-- Detail TER --}}
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $row['Kategori_TER'] ?? '-' }}</span></td>
                            <td class="text-end">{{ number_format($row['Nilai_TER'] ?? 0, 2, ',', '.') }}%</td>
                            <td class="text-end">{{ number_format($row['PPH21_Gaji'] ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['PPh21_Tunjangan'] ?? 0, 0, ',', '.') }}</td>
                            {{-- Brutto --}}
                            <td class="text-end fw-semibold">{{ number_format($row['brutto1'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['brutto2'], 0, ',', '.') }}</td>
                            {{-- Potongan --}}
                            <td class="text-end">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button wire:click.prevent="showPajakDetail({{ $idx }})" class="btn btn-sm btn-link p-0 text-info" title="Lihat Perhitungan Pajak">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <span>{{ number_format($row['potongan_pph21'], 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <!-- <td class="text-end text-danger">{{ number_format($row['potongan_pph21'], 0, ',', '.') }}</td> -->
                            <td class="text-end text-danger">{{ number_format($row['potonganpph_perumahan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potonganpph_transportasi'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potonganpph_tki'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potongan_bpjs'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format(($row['potongan_jkk'] ?? 0) + ($row['potongan_jkm'] ?? 0), 0, ',', '.') }}</td>
                            {{-- Rekap --}}
                            <td class="text-end">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button wire:click.prevent="showPajakDetail({{ $idx }})" class="btn btn-sm btn-link p-0 text-info" title="Lihat Perhitungan Pajak">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <span>{{ number_format($row['tunjangan_pph21'], 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="text-end">{{ number_format($row['pembulatan'], 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold text-primary">{{ number_format($row['nilai_gajitunjangan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['total_potongan1'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($row['jumlah_bersih'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold text-end">
                            <td colspan="2" class="text-center">TOTAL</td>
                            @php 
                                $colsBeforeTer = [
                                    'gaji_pokok','tunjangan_istri','tunjangan_anak','tunjangan_beras',
                                    'tunjangan_jabatan','tunjangan_paket','tunjangan_perumahan',
                                    'tunjangan_transportasi','tunjangan_tki',
                                    'tunjangan_komisi','tunjangan_banggar','tunjangan_banmus',
                                    'tunjangan_balegda','tunjangan_bk','tunjangan_pansus','tunjangan_panja',
                                    'tunjangan_bpjs','tunjangan_jkk','tunjangan_jkm'
                                ];
                                $colsAfterTer = [
                                    'brutto1','brutto2',
                                    'potongan_pph21','potonganpph_perumahan','potonganpph_transportasi',
                                    'potonganpph_tki','potongan_bpjs'
                                ];
                            @endphp

                            @foreach($colsBeforeTer as $col)
                                <td>{{ number_format(collect($ringkasan)->sum($col), 0, ',', '.') }}</td>
                            @endforeach

                            {{-- Footer Detail TER (Tarif tidak ditotal, Kategori tidak ditotal) --}}
                            <td>-</td>
                            <td>-</td>
                            <td>{{ number_format(collect($ringkasan)->sum('PPH21_Gaji'), 0, ',', '.') }}</td>
                            <td>{{ number_format(collect($ringkasan)->sum('PPh21_Tunjangan'), 0, ',', '.') }}</td>

                            @foreach($colsAfterTer as $col)
                                <td>{{ number_format(collect($ringkasan)->sum($col), 0, ',', '.') }}</td>
                            @endforeach
                            {{-- Pot. JKK+JKM --}}
                            <td>{{ number_format(collect($ringkasan)->sum(fn($r) => ($r['potongan_jkk'] ?? 0) + ($r['potongan_jkm'] ?? 0)), 0, ',', '.') }}</td>
                            {{-- Tj. PPh21 --}}
                            <td>{{ number_format(collect($ringkasan)->sum('tunjangan_pph21'), 0, ',', '.') }}</td>
                            {{-- Pembulatan --}}
                            <td>{{ number_format(collect($ringkasan)->sum('pembulatan'), 0, ',', '.') }}</td>
                            {{-- Total Pendapatan --}}
                            <td class="text-primary">{{ number_format(collect($ringkasan)->sum('nilai_gajitunjangan'), 0, ',', '.') }}</td>
                            {{-- Total Potongan --}}
                            <td class="text-danger">{{ number_format(collect($ringkasan)->sum('total_potongan1'), 0, ',', '.') }}</td>
                            {{-- Jumlah Bersih --}}
                            <td class="text-success">{{ number_format(collect($ringkasan)->sum('jumlah_bersih'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Detail Pajak --}}
    <div class="modal fade" id="pajakModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="bi bi-calculator me-2"></i>Rincian PPh Pasal 21
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 text-center">
                        <h6 class="fw-semibold mb-0">{{ $selectedPajakName }}</h6>
                        <small class="text-muted">Periode: {{ $blnThnLabel }}</small>
                    </div>

                    @if(!empty($selectedPajakDetail))
                    <table class="table table-sm table-borderless">
                        <tbody>
                            @if(($selectedPajakDetail['metode'] ?? 'lapis') === 'ter')
                            <tr>
                                <td>Total Pendapatan</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($selectedPajakDetail['Total Pendapatan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Kategori PTKP TER <br><small class="text-muted">(Status: {{ $selectedPajakDetail['status_ptkp'] ?? '-' }})</small></td>
                                <td class="text-end fw-bold text-primary">{{ $selectedPajakDetail['kategori_ter'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Persentase TER PMK 168</td>
                                <td class="text-end text-danger">{{ $selectedPajakDetail['persen_ter'] ?? 0 }}%</td>
                            </tr>
                            <tr class="table-primary fw-bold border-top">
                                <td>PPh21 Terutang Sebulan <br><small class="fw-normal">(Total Pendapatan × Persentase TER)</small></td>
                                <td class="text-end text-success fs-5">Rp {{ number_format($selectedPajakDetail['pph_sebulan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @else
                            <tr>
                                <td>Penghasilan Bruto Sebulan</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($selectedPajakDetail['bruto_sebulan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Biaya Jabatan ({{ $selectedPajakDetail['persen_biaya_jab'] ?? 0 }}%) <br><small class="text-muted">Maks. Rp {{ number_format($selectedPajakDetail['max_biaya_jab'] ?? 0, 0, ',', '.') }}</small></td>
                                <td class="text-end text-danger">- Rp {{ number_format($selectedPajakDetail['biaya_jabatan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top">
                                <td>Penghasilan Neto Sebulan</td>
                                <td class="text-end fw-bold">Rp {{ number_format($selectedPajakDetail['neto_sebulan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Penghasilan Neto Setahun <br><small class="text-muted">(Neto Sebulan × 12)</small></td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($selectedPajakDetail['neto_setahun'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>PTKP ({{ $selectedPajakDetail['status_ptkp'] ?? '-' }})</td>
                                <td class="text-end text-danger">- Rp {{ number_format($selectedPajakDetail['nilai_ptkp'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top">
                                <td>PKP setahun</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($selectedPajakDetail['pkp_kotor'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>PKP Pembulatan <br><small class="text-muted">(ke bawah ribuan penuh)</small></td>
                                <td class="text-end fw-bold text-warning">Rp {{ number_format($selectedPajakDetail['pkp_pembulatan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>PPh21 Terutang Setahun</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($selectedPajakDetail['pph_setahun'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-primary fw-bold">
                                <td>PPh21 Terutang Sebulan <br><small class="fw-normal">(PPh21 Setahun / 12)</small></td>
                                <td class="text-end text-success fs-5">Rp {{ number_format($selectedPajakDetail['pph_sebulan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        Memuat detail perhitungan...
                    </div>
                    @endif
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

 
    {{-- Modal Ganti Status (Modern Design) --}}
    @if($showStatusModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__zoomIn animate__faster">
                <div class="modal-header border-0 bg-primary text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Kelola Status Gaji</h5>
                            <small class="opacity-75">Periode {{ $blnThnLabel }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeStatusModal"></button>
                </div>
                
                <form wire:submit.prevent="updateStatus">
                    <div class="modal-body p-4 bg-white">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Status Gaji Baru</label>
                                <div class="input-group modern-input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-check2-circle text-primary"></i></span>
                                    <select wire:model="newStatus" class="form-select bg-light border-start-0 @error('newStatus') is-invalid @enderror">
                                        <option value="DRAF">DRAF (Terbuka untuk Perubahan)</option>
                                        <option value="FINAL">FINAL (Terkunci / Selesai)</option>
                                    </select>
                                </div>
                                @error('newStatus') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
 
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1">Alasan Perubahan Status</label>
                                <div class="input-group modern-input-group">
                                    <span class="input-group-text bg-light border-end-0 align-items-start pt-2"><i class="bi bi-chat-dots text-primary"></i></span>
                                    <textarea wire:model="alasanStatus" class="form-control bg-light border-start-0 @error('alasanStatus') is-invalid @enderror" rows="3" placeholder="Contoh: Selesai verifikasi data bendahara, atau Perbaikan parameter pajak..."></textarea>
                                </div>
                                @error('alasanStatus') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Wajib diisi untuk audit log riwayat status.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-1 bg-white justify-content-between">
                        <button type="button" class="btn btn-light px-4 rounded-pill" wire:click="closeStatusModal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-lg shadow-primary-subtle">
                            <i class="bi bi-send-fill me-1"></i> Perbarui Status Gaji
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
 
    @script
    <script>
        window.confirmProses = (isUlang) => {
            let titleText = isUlang ? 'Proses Ulang Gaji?' : 'Proses Gaji?';
            let msgText = isUlang ? 'Periode ini sudah diproses. Proses ulang dan timpa data lama?' : 'Mulai proses gaji untuk periode ini?';

            Swal.fire({
                title: titleText,
                text: msgText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.prosesGaji();
                }
            });
        };

        window.confirmHapus = () => {
            Swal.fire({
                title: 'Hapus Data Gaji?',
                text: "Data gaji periode ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.hapusData();
                }
            });
        };

        $wire.on('show-pajak-modal', () => {
            const modalEl = document.getElementById('pajakModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    </script>
    @endscript
</div>
