<div style="min-width: 0; max-width: 100%;">
    <div class="card border-0 shadow-sm mb-4" style="min-width: 0; max-width: 100%;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-cash-coin me-2"></i>Proses Gaji Anggota DPRD</h5>
        </div>
        <div class="card-body">

            {{-- Alert parameter tidak lengkap --}}
            @if(!$paramLengkap)
            <div class="alert alert-danger d-flex align-items-start gap-2">
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

            {{-- Form Pilih Periode --}}
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bulan / Periode</label>
                    <select wire:model.live="bulan" class="form-select">
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
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tahun</label>
                    <input type="number" wire:model.live="tahun" class="form-control" min="2020" max="2099" placeholder="2026">
                </div>
                <div class="col-md-5 d-flex gap-2 flex-wrap">
                    @if($sudahDiproses)
                    <button onclick="confirmProses(true)" class="btn btn-warning" @if(!$paramLengkap) disabled @endif>
                        <i class="bi bi-arrow-clockwise me-1"></i> Proses Ulang
                    </button>
                    <button onclick="confirmHapus()" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Hapus Data
                    </button>
                    @else
                    <button onclick="confirmProses(false)" class="btn btn-primary" @if(!$paramLengkap) disabled @endif>
                        <i class="bi bi-play-fill me-1"></i> Proses Gaji
                    </button>
                    @endif
                </div>
            </div>

            {{-- Alert sudah diproses --}}
            @if($sudahDiproses)
            <div class="alert alert-info d-flex align-items-center gap-2 mt-3 mb-0">
                <i class="bi bi-info-circle-fill"></i>
                <span>Periode <strong>{{ $blnThnLabel }}</strong> sudah diproses. Gunakan <strong>Proses Ulang</strong> untuk memperbarui data.</span>
            </div>
            @endif

        </div>
    </div>

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
                            <th colspan="2" class="table-secondary border">Brutto</th>
                            <th colspan="6" class="table-danger border">Potongan</th>
                            <th colspan="5" class="table-warning border">Rekap</th>
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
                            {{-- Brutto --}}
                            <td class="text-end fw-semibold">{{ number_format($row['brutto1'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['brutto2'], 0, ',', '.') }}</td>
                            {{-- Potongan --}}
                            <td class="text-end text-danger">{{ number_format($row['potongan_pph21'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potonganpph_perumahan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potonganpph_transportasi'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potonganpph_tki'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['potongan_bpjs'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format(($row['potongan_jkk'] ?? 0) + ($row['potongan_jkm'] ?? 0), 0, ',', '.') }}</td>
                            {{-- Rekap --}}
                            <td class="text-end">{{ number_format($row['tunjangan_pph21'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['pembulatan'], 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold text-primary">{{ number_format($row['nilai_gajitunjangan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($row['total_potongan1'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($row['jumlah_bersih'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold text-end">
                        <tr>
                            <td colspan="2" class="text-center">TOTAL</td>
                            @foreach([
                                'gaji_pokok','tunjangan_istri','tunjangan_anak','tunjangan_beras',
                                'tunjangan_jabatan','tunjangan_paket','tunjangan_perumahan',
                                'tunjangan_transportasi','tunjangan_tki',
                                'tunjangan_komisi','tunjangan_banggar','tunjangan_banmus',
                                'tunjangan_balegda','tunjangan_bk','tunjangan_pansus','tunjangan_panja',
                                'tunjangan_bpjs','tunjangan_jkk','tunjangan_jkm',
                                'brutto1','brutto2',
                                'potongan_pph21','potonganpph_perumahan','potonganpph_transportasi',
                                'potonganpph_tki','potongan_bpjs'
                            ] as $col)
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
    </script>
    @endscript
</div>
