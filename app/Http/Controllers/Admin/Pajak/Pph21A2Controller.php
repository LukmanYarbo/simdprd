<?php

namespace App\Http\Controllers\Admin\Pajak;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\TransaksiGaji;
use App\Models\TarifPajak;
use App\Models\PenandaTangan;
use App\Models\PegawaiAsn;
use App\Models\Pemda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pph21A2Controller extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $monthStart = (int) $request->get('month_start', 1);
        $monthEnd = (int) $request->get('month_end', 12);
        $id_anggota = $request->get('id_anggota');
        $includeThr = $request->has('tahun') ? $request->boolean('include_thr') : true;
        $includeG13 = $request->has('tahun') ? $request->boolean('include_g13') : true;
        $id_ttd = $request->get('id_ttd');

        // Fetch active TarifPajak
        $tarifPajak = TarifPajak::where('status', 'Y')->with('lapisPajak')->first();

        // Fetch all active members for dropdown
        $members = Anggota::where('id_status_keanggotaan', 1)->orderBy('nama_anggota')->get();

        // Fetch penanda tangan
        $signatories = PenandaTangan::with(['pegawaiAsn.jabatanAsn'])->get();
        
        // Find default bendahara
        $defaultTtd = $id_ttd ? PenandaTangan::find($id_ttd) : $signatories->filter(function($sig) {
            $jabatan = strtolower($sig->pegawaiAsn->jabatanAsn->nama_jabatan ?? '');
            $ket = strtolower($sig->pegawaiAsn->ket_jabatan ?? '');
            return str_contains($jabatan, 'bendahara') || str_contains($ket, 'bendahara');
        })->first();

        if (!$defaultTtd && $signatories->isNotEmpty()) {
            $defaultTtd = $signatories->first();
        }

        // Fetch Pemda
        $pemda = Pemda::first();

        // Process calculations for the table view
        $data = [];
        
        $queryMembers = Anggota::where('id_status_keanggotaan', 1);
        if ($id_anggota) {
            $queryMembers->where('id', $id_anggota);
        }
        $selectedMembers = $queryMembers->orderBy('id_dprd', 'asc')
            ->orderBy('id_komisi', 'asc')
            ->orderBy('nama_komisi', 'asc')
            ->orderBy('nama_anggota', 'asc')
            ->get();

        foreach ($selectedMembers as $member) {
            $calc = $this->calculateA2ForMember($member, $tahun, $monthStart, $monthEnd, $includeThr, $includeG13, $tarifPajak);
            if ($calc['has_data']) {
                $data[] = $calc;
            }
        }

        return view('admin.pph21-a2.index', compact(
            'tahun', 'monthStart', 'monthEnd', 'id_anggota', 'includeThr', 'includeG13',
            'members', 'signatories', 'defaultTtd', 'pemda', 'data', 'tarifPajak'
        ));
    }

    public function print(Request $request, $id_anggota)
    {
        $member = Anggota::findOrFail($id_anggota);
        $tahun = $request->get('tahun', date('Y'));
        $monthStart = (int) $request->get('month_start', 1);
        $monthEnd = (int) $request->get('month_end', 12);
        $includeThr = $request->has('tahun') ? $request->boolean('include_thr') : true;
        $includeG13 = $request->has('tahun') ? $request->boolean('include_g13') : true;
        $id_ttd = $request->get('id_ttd');
        $tanggalCetak = $request->get('tanggal_cetak', date('Y-m-d'));

        $tarifPajak = TarifPajak::where('status', 'Y')->with('lapisPajak')->first();
        $pemda = Pemda::first();
        $ttd = PenandaTangan::with(['pegawaiAsn.pangkatGolongan', 'pegawaiAsn.jabatanAsn'])->find($id_ttd);

        if (!$ttd) {
            $ttd = PenandaTangan::with(['pegawaiAsn.pangkatGolongan', 'pegawaiAsn.jabatanAsn'])->first();
        }

        $calc = $this->calculateA2ForMember($member, $tahun, $monthStart, $monthEnd, $includeThr, $includeG13, $tarifPajak);

        $bulanLabels = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $masaPajak = sprintf('%02d - %02d', $monthStart, $monthEnd);
        $tanggalCetakLabel = Carbon::parse($tanggalCetak)->locale('id')->translatedFormat('d F Y');

        return view('admin.pph21-a2.print', compact(
            'member', 'tahun', 'monthStart', 'monthEnd', 'calc', 'pemda', 'ttd', 'masaPajak', 'tanggalCetakLabel'
        ));
    }

    public function printBulk(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $monthStart = (int) $request->get('month_start', 1);
        $monthEnd = (int) $request->get('month_end', 12);
        $id_anggota = $request->get('id_anggota');
        $includeThr = $request->has('tahun') ? $request->boolean('include_thr') : true;
        $includeG13 = $request->has('tahun') ? $request->boolean('include_g13') : true;
        $id_ttd = $request->get('id_ttd');
        $tanggalCetak = $request->get('tanggal_cetak', date('Y-m-d'));

        $tarifPajak = TarifPajak::where('status', 'Y')->with('lapisPajak')->first();
        $pemda = Pemda::first();
        $ttd = PenandaTangan::with(['pegawaiAsn.pangkatGolongan', 'pegawaiAsn.jabatanAsn'])->find($id_ttd);

        if (!$ttd) {
            $ttd = PenandaTangan::with(['pegawaiAsn.pangkatGolongan', 'pegawaiAsn.jabatanAsn'])->first();
        }

        $queryMembers = Anggota::where('id_status_keanggotaan', 1);
        if ($id_anggota) {
            $queryMembers->where('id', $id_anggota);
        }
        $selectedMembers = $queryMembers->orderBy('id_dprd', 'asc')
            ->orderBy('id_komisi', 'asc')
            ->orderBy('nama_komisi', 'asc')
            ->orderBy('nama_anggota', 'asc')
            ->get();

        $data = [];
        foreach ($selectedMembers as $member) {
            $calc = $this->calculateA2ForMember($member, $tahun, $monthStart, $monthEnd, $includeThr, $includeG13, $tarifPajak);
            if ($calc['has_data']) {
                $data[] = [
                    'member' => $member,
                    'calc' => $calc
                ];
            }
        }

        $masaPajak = sprintf('%02d - %02d', $monthStart, $monthEnd);
        $tanggalCetakLabel = Carbon::parse($tanggalCetak)->locale('id')->translatedFormat('d F Y');

        return view('admin.pph21-a2.print-bulk', compact(
            'tahun', 'monthStart', 'monthEnd', 'data', 'pemda', 'ttd', 'masaPajak', 'tanggalCetakLabel'
        ));
    }

    private function calculateA2ForMember(Anggota $member, $tahun, $monthStart, $monthEnd, $includeThr, $includeG13, $tarifPajak)
    {
        // Get all matching transactions for the member in the selected year
        $transactions = TransaksiGaji::where('id_anggota', $member->id)
            ->where('bln_thn', 'like', "%-{$tahun}")
            ->get();

        // Filter transactions based on selection
        $filtered = $transactions->filter(function($trx) use ($monthStart, $monthEnd, $includeThr, $includeG13) {
            $parts = explode('-', $trx->bln_thn);
            $prefix = $parts[0];
            
            if ($prefix === 'THR') {
                return $includeThr;
            }
            if ($prefix === 'G13') {
                return $includeG13;
            }
            
            $month = (int) $prefix;
            return $month >= $monthStart && $month <= $monthEnd;
        });

        if ($filtered->isEmpty()) {
            return [
                'has_data' => false,
                'member_id' => $member->id,
                'nama' => $member->nama_anggota,
                'npwp' => $member->no_npwp ?? '-',
            ];
        }

        // Sum components
        $gajiPokok = $filtered->sum('gaji_pokok');
        $tunjanganIstri = $filtered->sum('tunjangan_istri');
        $tunjanganAnak = $filtered->sum('tunjangan_anak');
        $tunjanganKeluarga = $gajiPokok + $tunjanganIstri + $tunjanganAnak;
        
        $tunjanganBeras = $filtered->sum('tunjangan_beras');
        $tunjanganPPh = $filtered->sum('tunjangan_pph21');
        
        // Sum all other allowances
        $tunjanganJabatan = $filtered->sum('tunjangan_jabatan');
        $tunjanganLain = $filtered->sum('tunjangan_paket') +
            $filtered->sum('tunjangan_komisi') +
            $filtered->sum('tunjangan_banggar') +
            $filtered->sum('tunjangan_banmus') +
            $filtered->sum('tunjangan_balegda') +
            $filtered->sum('tunjangan_bk') +
            $filtered->sum('tunjangan_pansus') +
            $filtered->sum('tunjangan_panja') +
            $filtered->sum('tunjangan_perumahan') +
            $filtered->sum('tunjangan_transportasi') +
            $filtered->sum('tunjangan_tki') +
            $filtered->sum('tunjangan_reses');

        // Premi asuransi paid by employer
        $premiAsuransi = $filtered->sum('tunjangan_bpjs') +
            $filtered->sum('tunjangan_jkk') +
            $filtered->sum('tunjangan_jkm');

        // Total gross
        $jumlahBruto = $tunjanganKeluarga + $tunjanganBeras + $tunjanganPPh + $tunjanganJabatan + $tunjanganLain + $premiAsuransi;

        // Get status from the latest monthly transaction in the period
        $latestMonthly = $filtered->filter(function($trx) {
            $prefix = explode('-', $trx->bln_thn)[0];
            return is_numeric($prefix);
        })->sortByDesc(function($trx) {
            return (int) explode('-', $trx->bln_thn)[0];
        })->first();

        // Fallback to member details if no monthly transaction is present (highly unlikely but safe)
        $statusKawin = $latestMonthly ? $latestMonthly->status_kawin : ($member->statusKawin ? $member->statusKawin->kode : 'TK');
        $jumlahIs = $latestMonthly ? $latestMonthly->jumlah_is : ($member->jmlh_istri ?? 0);
        $jumlahAnak = $latestMonthly ? $latestMonthly->jumlah_anak : ($member->jmlh_anak ?? 0);

        // Deductions
        $biayaJabatanPersen = $tarifPajak ? ($tarifPajak->persen_biaya_jabatan / 100) : 0.05;
        $maxBiayaJabatanBulan = $tarifPajak ? $tarifPajak->max_biaya_jabatan : 500000;
        
        // Count number of monthly transactions in the filter to determine the cap
        $monthsCount = $filtered->filter(function($trx) {
            return is_numeric(explode('-', $trx->bln_thn)[0]);
        })->count();
        if ($monthsCount === 0) $monthsCount = 1; // Safeguard

        $biayaJabatanCap = $monthsCount * $maxBiayaJabatanBulan;
        $biayaJabatan = min($jumlahBruto * $biayaJabatanPersen, $biayaJabatanCap);

        // BPJS employee contribution
        $iuranPensiun = $filtered->sum('potongan_bpjs');
        $jumlahPengurangan = $biayaJabatan + $iuranPensiun;

        // Net income
        $netto = max(0, $jumlahBruto - $jumlahPengurangan);

        // PTKP Calculation
        $ptkpBase = $tarifPajak ? $tarifPajak->ptkp : 54000000;
        $ptkpPlus = $tarifPajak ? $tarifPajak->tambahan_ptkp_tanggungan : 4500000;
        
        $dependentsCount = ($statusKawin === 'K' ? 1 : 0) + min($jumlahAnak, 3);
        $ptkp = $ptkpBase + ($dependentsCount * $ptkpPlus);

        // Taxable Income (PKP)
        $pkpKotor = $netto - $ptkp;
        $pkp = max(0, floor($pkpKotor / 1000) * 1000);

        // PPh 21 Terutang (Article 17)
        $lapisPajak = $tarifPajak ? $tarifPajak->lapisPajak : collect();
        $pphTerutang = $this->calculateArticle17Tax($pkp, $lapisPajak);

        // PPh 21 Actually Withheld (Sum from monthly + THR + G13)
        $pphDipotong = $filtered->sum('potongan_pph21') +
            $filtered->sum('potonganpph_perumahan') +
            $filtered->sum('potonganpph_transportasi') +
            $filtered->sum('potonganpph_tki') +
            $filtered->sum('potonganpph_reses');

        $selisih = $pphTerutang - $pphDipotong;

        return [
            'has_data' => true,
            'member_id' => $member->id,
            'nama' => $member->nama_anggota,
            'npwp' => $member->no_npwp ?? '-',
            'nik' => $member->nik ?? '-',
            'alamat' => $member->alamat_lengkap ?? '-',
            'jenis_kelamin' => $member->jk ?? 'L',
            'status_ptkp' => ($statusKawin === 'K' ? 'K' : 'TK') . '/' . min($jumlahAnak, 3),
            'pangkat' => $member->pangkatGolongan ? ($member->pangkatGolongan->pangkat . ' / ' . $member->pangkatGolongan->golongan) : '-',
            'jabatan' => $member->jabatan ? $member->jabatan->nama : 'ANGGOTA DPRD',
            
            // Financials
            'gaji_pokok' => $gajiPokok,
            'tunjangan_istri' => $tunjanganIstri,
            'tunjangan_anak' => $tunjanganAnak,
            'tunjangan_keluarga' => $tunjanganKeluarga,
            'tunjangan_beras' => $tunjanganBeras,
            'tunjangan_pph' => $tunjanganPPh,
            'tunjangan_jabatan' => $tunjanganJabatan,
            'tunjangan_lain' => $tunjanganLain,
            'premi_asuransi' => $premiAsuransi,
            'jumlah_bruto' => $jumlahBruto,
            
            // Deductions
            'biaya_jabatan' => $biayaJabatan,
            'iuran_pensiun' => $iuranPensiun,
            'jumlah_pengurangan' => $jumlahPengurangan,
            
            // Tax Calculations
            'netto' => $netto,
            'neto' => $netto,
            'ptkp' => $ptkp,
            'pkp' => $pkp,
            'pph_terutang' => $pphTerutang,
            'pph_dipotong' => $pphDipotong,
            'selisih' => $selisih,
            
            // Metadata
            'months_count' => $monthsCount,
            'status_kawin' => $statusKawin,
            'jumlah_is' => $jumlahIs,
            'jumlah_anak' => $jumlahAnak,
        ];
    }

    private function calculateArticle17Tax($pkp, $lapisPajak)
    {
        if ($pkp <= 0 || $lapisPajak->isEmpty()) {
            return 0;
        }

        $tax = 0;
        $remaining = $pkp;

        foreach ($lapisPajak as $lapis) {
            $dari = $lapis->dari;
            $sampai = $lapis->sampai;
            $persen = $lapis->persen;

            if ($remaining <= 0) {
                break;
            }

            if ($sampai === null) {
                $taxableAmount = $remaining;
            } else {
                $range = $sampai - $dari;
                $taxableAmount = min($remaining, $range);
            }

            $tax += $taxableAmount * ($persen / 100);
            $remaining -= $taxableAmount;
        }

        return floor($tax);
    }
}
