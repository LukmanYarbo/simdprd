<?php

namespace App\Exports;

use App\Models\TransaksiGaji;
use App\Models\DsbGaji;
use App\Models\Pemda;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DaftarGajiExport implements FromView, WithStyles, WithColumnWidths
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $blnThn = $this->bulan . '-' . $this->tahun;

        $transaksi = TransaksiGaji::where('bln_thn', $blnThn)
            ->with(['anggota', 'anggota.jabatan'])
            ->join('anggota', 'transaksi_gaji.id_anggota', '=', 'anggota.id')
            ->orderBy('anggota.id_dprd', 'asc')
            ->orderBy('anggota.nama_anggota', 'asc')
            ->select('transaksi_gaji.*')
            ->get();

        $dsbGaji = DsbGaji::where('bln_thn', $blnThn)->first();
        $pemda = Pemda::first();
        
        $bulanLabel = $this->getBulanLabel($this->bulan);

        return view('admin.gaji.reports.daftar-gaji-excel', [
            'transaksi' => $transaksi,
            'dsbGaji' => $dsbGaji,
            'bulanLabel' => $bulanLabel,
            'tahun' => $this->tahun,
            'pemda' => $pemda
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        // Approximately 30 columns for the flat layout
        $widths = [
            'A' => 5,   // No
            'B' => 30,  // Nama
            'C' => 20,  // Jabatan
            'D' => 12,  // STS
            'E' => 15,  // GP
        ];
        
        // F to AD (approx 30 columns)
        foreach (range('F', 'Z') as $col) {
            $widths[$col] = 13;
        }
        foreach (range('A', 'D') as $col) {
            $widths['A'.$col] = 13;
        }

        return $widths;
    }

    private function getBulanLabel($bulan)
    {
        $bulan = (int)$bulan;
        $labels = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL', 
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS', 
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        return $labels[$bulan] ?? '';
    }
}
