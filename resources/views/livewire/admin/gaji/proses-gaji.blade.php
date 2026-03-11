<div>
    <div class="card border-0 shadow-sm mb-4">
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
                    <button wire:click="prosesGaji" wire:confirm="Periode ini sudah diproses. Proses ulang dan timpa data lama?"
                            class="btn btn-warning" @if(!$paramLengkap) disabled @endif>
                        <i class="bi bi-arrow-clockwise me-1"></i> Proses Ulang
                    </button>
                    <button onclick="confirmHapus()" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Hapus Data
                    </button>
                    @else
                    <button wire:click="prosesGaji" wire:confirm="Mulai proses gaji untuk periode {{ $blnThnLabel }}?"
                            class="btn btn-primary" @if(!$paramLengkap) disabled @endif>
                        <i class="bi bi-play-fill me-1"></i> Proses Gaji
                    </button>
                    @endif
                    <div wire:loading wire:target="prosesGaji" class="d-flex align-items-center text-secondary ms-1">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        Memproses...
                    </div>
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

    {{-- Tabel Ringkasan Hasil --}}
    @if(!empty($ringkasan))
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 text-success fw-bold">
                <i class="bi bi-table me-2"></i>Ringkasan Hasil — {{ $blnThnLabel }}
                <span class="badge bg-success ms-2">{{ count($ringkasan) }} anggota</span>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Anggota</th>
                            <th class="text-end">Gaji Pokok</th>
                            <th class="text-end">Tunjangan Jabatan</th>
                            <th class="text-end">Jumlah Brutto</th>
                            <th class="text-end">Pot. PPh21</th>
                            <th class="text-end pe-4">Jumlah Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ringkasan as $idx => $row)
                        <tr>
                            <td class="ps-4">{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ $row['nama'] }}</td>
                            <td class="text-end">Rp {{ number_format($row['gaji_pokok'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($row['tunjangan_jabatan'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($row['brutto1'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">Rp {{ number_format($row['potongan_pph21'], 0, ',', '.') }}</td>
                            <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($row['jumlah_bersih'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold border-top">
                        <tr>
                            <td colspan="2" class="ps-4">Total</td>
                            <td class="text-end">Rp {{ number_format(collect($ringkasan)->sum('gaji_pokok'), 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format(collect($ringkasan)->sum('tunjangan_jabatan'), 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format(collect($ringkasan)->sum('brutto1'), 0, ',', '.') }}</td>
                            <td class="text-end text-danger">Rp {{ number_format(collect($ringkasan)->sum('potongan_pph21'), 0, ',', '.') }}</td>
                            <td class="text-end pe-4 text-success">Rp {{ number_format(collect($ringkasan)->sum('jumlah_bersih'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
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
