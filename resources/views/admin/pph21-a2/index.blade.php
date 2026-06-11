@extends('layouts.admin')

@section('title', 'Formulir 1721-A2 PPh 21')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Form 1721-A2 PPh 21', 'icon' => 'ti ti-file-text']
]" />
@endsection

@section('content')
<div class="container-fluid py-4" style="min-width: 0; max-width: 100%;">
    {{-- Card Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="ti ti-file-analytics me-2"></i>Filter Cetak Form 1721-A2 PPh 21
            </h5>
            <p class="text-muted small mb-0">Silakan tentukan tahun, rentang bulan (masa pajak), dan opsi pelaporan lainnya untuk memproses Bukti Pemotongan 1721-A2.</p>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pph21-a2.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    {{-- Filter Tahun --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Tahun Gaji</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar text-primary"></i></span>
                            <input type="number" name="tahun" class="form-control bg-light border-start-0 shadow-none" min="2020" max="2099" value="{{ $tahun }}">
                        </div>
                    </div>

                    {{-- Filter Bulan Mulai --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Masa Pajak Mulai</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar-event text-primary"></i></span>
                            <select name="month_start" class="form-select bg-light border-start-0 shadow-none">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $monthStart == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter Bulan Selesai --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Masa Pajak Selesai</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar-event text-primary"></i></span>
                            <select name="month_end" class="form-select bg-light border-start-0 shadow-none">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $monthEnd == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter Anggota --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Anggota DPRD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ti ti-user text-primary"></i></span>
                            <select name="id_anggota" class="form-select bg-light border-start-0 shadow-none">
                                <option value="">-- Semua Anggota --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}" {{ $id_anggota == $m->id ? 'selected' : '' }}>{{ $m->nama_anggota }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Bendahara Penandatangan --}}
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Bendahara Pemotong Pajak</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ti ti-signature text-primary"></i></span>
                            <select name="id_ttd" class="form-select bg-light border-start-0 shadow-none">
                                @foreach($signatories as $sig)
                                    <option value="{{ $sig->id }}" {{ ($defaultTtd && $defaultTtd->id == $sig->id) ? 'selected' : '' }}>
                                        {{ $sig->pegawaiAsn->nama }} ({{ $sig->pegawaiAsn->jabatanAsn->nama_jabatan ?? $sig->pegawaiAsn->ket_jabatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tanggal Cetak --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Tanggal Bukti Potong</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar-check text-primary"></i></span>
                            <input type="date" name="tanggal_cetak" class="form-control bg-light border-start-0 shadow-none" value="{{ request('tanggal_cetak', date('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Checkboxes Opsi Pajak --}}
                    <div class="col-md-4 d-flex align-items-center gap-4 pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="include_thr" id="includeThr" value="1" {{ $includeThr ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="includeThr">Sertakan THR</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="include_g13" id="includeG13" value="1" {{ $includeG13 ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="includeG13">Sertakan Gaji Ke-13</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                        <i class="ti ti-search me-1"></i> Tampilkan Data
                    </button>
                    @if(count($data) > 0)
                        <button type="button" onclick="submitBulkPrint()" class="btn btn-success px-4 rounded-pill shadow-sm">
                            <i class="ti ti-printer me-1"></i> Cetak Massal (Bulk)
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Hasil Kalkulasi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="mb-0 text-success fw-bold">
                <i class="ti ti-table me-2"></i>Hasil Rekapitulasi & Perhitungan PPh 21 A2
                <span class="badge bg-success ms-2 rounded-pill">{{ count($data) }} Anggota</span>
            </h6>
        </div>
        <div class="card-body p-0">
            @if(count($data) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 0.8rem;">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th class="text-start">Nama Anggota / NIP / NPWP</th>
                            <th>Status PTKP</th>
                            <th>Masa Kerja (Bulan)</th>
                            <th class="text-end">Jumlah Bruto</th>
                            <th class="text-end">Biaya Jabatan</th>
                            <th class="text-end">PTKP</th>
                            <th class="text-end">PKP</th>
                            <th class="text-end">PPh Terutang (Psl 17)</th>
                            <th class="text-end">PPh Riil Dipotong</th>
                            <th class="text-end">Selisih</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $idx => $row)
                        <tr>
                            <td class="text-center fw-semibold">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $row['nama'] }}</div>
                                <div class="text-muted small">NIK: {{ $row['nik'] }} | NPWP: {{ $row['npwp'] }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-1">{{ $row['status_ptkp'] }}</span>
                            </td>
                            <td class="text-center fw-semibold text-primary">{{ $row['months_count'] }} bln</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($row['jumlah_bruto'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">Rp {{ number_format($row['biaya_jabatan'], 0, ',', '.') }}</td>
                            <td class="text-end text-muted">Rp {{ number_format($row['ptkp'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($row['pkp'], 0, ',', '.') }}</td>
                            <td class="text-end text-success fw-bold">Rp {{ number_format($row['pph_terutang'], 0, ',', '.') }}</td>
                            <td class="text-end text-primary fw-bold">Rp {{ number_format($row['pph_dipotong'], 0, ',', '.') }}</td>
                            <td class="text-end">
                                @if($row['selisih'] > 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        KB: +Rp {{ number_format(abs($row['selisih']), 0, ',', '.') }}
                                    </span>
                                @elseif($row['selisih'] < 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        LB: -Rp {{ number_format(abs($row['selisih']), 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1">Nihil</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.pph21-a2.print', array_merge(request()->all(), ['id_anggota' => $row['member_id']])) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                    <i class="ti ti-printer me-1"></i> Cetak A2
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold text-end">
                        <tr>
                            <td colspan="4" class="text-center">TOTAL</td>
                            <td>Rp {{ number_format(collect($data)->sum('jumlah_bruto'), 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format(collect($data)->sum('biaya_jabatan'), 0, ',', '.') }}</td>
                            <td>-</td>
                            <td>Rp {{ number_format(collect($data)->sum('pkp'), 0, ',', '.') }}</td>
                            <td class="text-success">Rp {{ number_format(collect($data)->sum('pph_terutang'), 0, ',', '.') }}</td>
                            <td class="text-primary">Rp {{ number_format(collect($data)->sum('pph_dipotong'), 0, ',', '.') }}</td>
                            <td>
                                @php $totalSelisih = collect($data)->sum('selisih'); @endphp
                                @if($totalSelisih > 0)
                                    <span class="text-danger">KB: +Rp {{ number_format(abs($totalSelisih), 0, ',', '.') }}</span>
                                @elseif($totalSelisih < 0)
                                    <span class="text-success">LB: -Rp {{ number_format(abs($totalSelisih), 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">Nihil</span>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="ti ti-file-off fs-1 d-block mb-3 text-secondary"></i>
                <h6 class="fw-bold">Tidak ada data transaksi ditemukan</h6>
                <p class="small mb-0">Pastikan data gaji pada tahun dan periode yang dipilih sudah diproses.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function submitBulkPrint() {
        const form = document.getElementById('filterForm');
        const originalAction = form.action;
        
        // Open bulk print in new tab
        form.action = "{{ route('admin.pph21-a2.print-bulk') }}";
        form.target = "_blank";
        form.submit();
        
        // Restore original form state
        form.action = originalAction;
        form.target = "";
    }
</script>
@endsection
