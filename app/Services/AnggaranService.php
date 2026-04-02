<?php

namespace App\Services;

use App\Models\Anggaran;
use App\Models\JurnalLra;
use App\Models\TransaksiGaji;
use Illuminate\Support\Facades\DB;

class AnggaranService
{
    /**
     * Process budget realization for a specific month/year.
     */
    public function processRealization(string $blnThn)
    {
        // Extract year from bln_thn
        $parts = explode('-', $blnThn);
        $tahun = (int) end($parts);

        $anggaran = Anggaran::where('tahun_anggaran', $tahun)->first();
        if (!$anggaran) {
            \Log::warning("Budget not found for year $tahun. Realization skipped.");
            return;
        }

        // Reverse if already exists for this period
        $this->reverseRealization($blnThn);

        // Summarize TransaksiGaji
        $summary = TransaksiGaji::where('bln_thn', $blnThn)
            ->select(
                DB::raw('SUM(gaji_pokok) as gaji_pokok'),
                DB::raw('SUM(tunjangan_istri + tunjangan_anak) as tunjangan_keluarga'),
                DB::raw('SUM(tunjangan_jabatan) as tunjangan_jabatan'),
                DB::raw('SUM(tunjangan_beras) as tunjangan_beras'),
                DB::raw('SUM(tunjangan_pph21) as tunjangan_pph'),
                DB::raw('SUM(pembulatan) as pembulatan'),
                DB::raw('SUM(tunjangan_paket) as uang_paket'),
                DB::raw('SUM(tunjangan_komisi + tunjangan_banggar + tunjangan_banmus + tunjangan_balegda + tunjangan_bk) as tunjangan_alat_kelengkapan'),
                DB::raw('SUM(tunjangan_pansus + tunjangan_panja) as tunjangan_alat_kelengkapan_lainnya'),
                DB::raw('SUM(tunjangan_perumahan) as tunjangan_perumahan'),
                DB::raw('SUM(tunjangan_reses) as tunjangan_reses'),
                DB::raw('SUM(tunjangan_transportasi) as tunjangan_transportasi'),
                DB::raw('SUM(tunjangan_jkk) as jkk'),
                DB::raw('SUM(tunjangan_jkm) as jkm'),
                DB::raw('SUM(tunjangan_tki) as tunjangan_komunikasi_insentif')
            )
            ->first();

        if (!$summary) return;

        $items = [
            'gaji_pokok', 'tunjangan_keluarga', 'tunjangan_jabatan', 'tunjangan_beras',
            'tunjangan_pph', 'pembulatan', 'uang_paket', 'tunjangan_alat_kelengkapan',
            'tunjangan_alat_kelengkapan_lainnya', 'tunjangan_perumahan', 'tunjangan_reses',
            'tunjangan_transportasi', 'jkk', 'jkm', 'tunjangan_komunikasi_insentif'
        ];

        DB::transaction(function () use ($anggaran, $summary, $items, $blnThn) {
            foreach ($items as $item) {
                $value = (int) ($summary->$item ?? 0);
                if ($value > 0) {
                    // Create Journal Entry
                    JurnalLra::create([
                        'id_anggaran' => $anggaran->id,
                        'bln_thn' => $blnThn,
                        'tanggal' => now(),
                        'keterangan' => "Realisasi Gaji Periode $blnThn - $item",
                        'item_anggaran' => $item,
                        'debet' => $value,
                        'kredit' => 0,
                    ]);

                    // Deduct Budget
                    $anggaran->decrement($item, $value);
                }
            }
        });
    }

    /**
     * Reverse realization for a specific month/year.
     */
    public function reverseRealization(string $blnThn)
    {
        $journals = JurnalLra::where('bln_thn', $blnThn)->get();
        if ($journals->isEmpty()) return;

        DB::transaction(function () use ($journals) {
            foreach ($journals as $journal) {
                $anggaran = Anggaran::find($journal->id_anggaran);
                if ($anggaran) {
                    // Restore budget (increment by debet, decrement by kredit)
                    $restoreValue = $journal->debet - $journal->kredit;
                    $anggaran->increment($journal->item_anggaran, $restoreValue);
                }
                $journal->delete();
            }
        });
    }
}
