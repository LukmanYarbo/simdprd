<?php

namespace App\Livewire\Admin\Gaji;

use App\Models\Anggota;
use App\Models\TransaksiGaji;
use App\Services\GajiCalculatorService;
use Livewire\Component;

class ProsesGaji extends Component
{
    public int $tahun;
    public string $bulan = '1'; // '1' s/d '12', 'THR', 'G13'
    public string $metodePajak = 'ter'; // 'lapis' atau 'ter'

    public bool $sudahDiproses = false;
    public bool $paramLengkap  = true;
    public array $missingParams = [];
    public ?string $blnThnLabel = null;

    public array $hasilProses = [];

    // State untuk View Detail Pajak
    public array $selectedPajakDetail = [];
    public string $selectedPajakName = '';

    protected GajiCalculatorService $calculator;

    public function boot(GajiCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function mount()
    {
        $this->tahun  = (int) date('Y');
        $this->cekStatus();
    }

    public function updatedBulan(): void
    {
        $this->cekStatus();
    }

    public function updatedMetodePajak(): void
    {
        $this->cekStatus();
    }

    public function updatedTahun(): void
    {
        $this->cekStatus();
    }

    protected function getBlnThn(): string
    {
        if ($this->bulan === 'THR') {
            return 'THR-' . $this->tahun;
        } elseif ($this->bulan === 'G13') {
            return 'G13-' . $this->tahun;
        }
        return $this->bulan . '-' . $this->tahun;
    }

    protected function getBlnThnLabel(): string
    {
        $labels = [
            '1'   => 'Januari',
            '2'   => 'Februari',
            '3'   => 'Maret',
            '4'   => 'April',
            '5'   => 'Mei',
            '6'   => 'Juni',
            '7'   => 'Juli',
            '8'   => 'Agustus',
            '9'   => 'September',
            '10'  => 'Oktober',
            '11'  => 'November',
            '12'  => 'Desember',
            'THR' => 'THR',
            'G13' => 'Gaji Ke-13',
        ];

        $label = $labels[$this->bulan] ?? $this->bulan;
        return $label . ' ' . $this->tahun;
    }

    public function cekStatus(): void
    {
        $blnThn = $this->getBlnThn();
        $this->blnThnLabel = $this->getBlnThnLabel();
        $this->sudahDiproses = TransaksiGaji::where('bln_thn', $blnThn)->exists();
        $this->hasilProses   = [];

        // Cek parameter
        $ok = $this->calculator->loadParameters();
        if (!$ok) {
            $this->paramLengkap = false;
            $this->missingParams = $this->calculator->getMissingParams();
        } else {
            $this->paramLengkap = true;
            $this->missingParams = [];
        }
    }

    public function prosesGaji(): void
    {
        if (!$this->paramLengkap) {
            $this->dispatch('swal', title: 'Parameter Tidak Lengkap', text: 'Lengkapi parameter master terlebih dahulu.', icon: 'error');
            return;
        }

        $blnThn = $this->getBlnThn();

        // Hapus data lama jika ada (re-proses)
        TransaksiGaji::where('bln_thn', $blnThn)->delete();

        // Ambil semua anggota aktif
        $anggotas = Anggota::where('id_status_keanggotaan', 1)
            ->with(['statusKawin'])
            ->get();

        if ($anggotas->isEmpty()) {
            $this->dispatch('swal', title: 'Tidak Ada Data', text: 'Tidak ada anggota aktif untuk diproses.', icon: 'warning');
            return;
        }

        $this->calculator->loadParameters();

        $hasil = [];
        foreach ($anggotas as $anggota) {
            $data = $this->calculator->hitungGaji($anggota, $blnThn, $this->metodePajak);
            TransaksiGaji::create($data);
            
            // Sertakan semua data untuk ditampilkan di tabel
            $data['nama'] = $anggota->nama_anggota;
            $hasil[] = $data;
        }

        $this->hasilProses = $hasil;
        $this->sudahDiproses = true;

        $this->dispatch('swal',
            title: 'Berhasil',
            text: 'Proses gaji ' . $this->getBlnThnLabel() . ' selesai untuk ' . count($hasil) . ' anggota.',
            icon: 'success'
        );
    }

    public function hapusData(): void
    {
        $blnThn = $this->getBlnThn();
        TransaksiGaji::where('bln_thn', $blnThn)->delete();
        $this->sudahDiproses = false;
        $this->hasilProses   = [];
        $this->dispatch('swal', title: 'Berhasil', text: 'Data periode ' . $this->getBlnThnLabel() . ' berhasil dihapus.', icon: 'success');
    }

    public function showPajakDetail(int $index): void
    {
        $ringkasan = $this->getRingkasanData();
        if (isset($ringkasan[$index])) {
            $row = $ringkasan[$index];
            $this->selectedPajakName = $row['nama'] ?? 'Unknown';
            $this->selectedPajakDetail = $row['detail_pajak'] ?? [];
            $this->dispatch('show-pajak-modal');
        }
    }

    protected function getRingkasanData(): array
    {
        return $this->sudahDiproses && empty($this->hasilProses)
            ? TransaksiGaji::where('bln_thn', $this->getBlnThn())
                ->with('anggota')
                ->get()
                ->map(function ($t) {
                    $arr = $t->toArray();
                    $arr['nama'] = $t->anggota->nama_anggota ?? '-';
                    // Fallback to decode detail_pajak JSON if it wasn't automatically casted
                    if (is_string($arr['detail_pajak'] ?? null)) {
                        $arr['detail_pajak'] = json_decode($arr['detail_pajak'], true);
                    }
                    return $arr;
                })->toArray()
            : $this->hasilProses;
    }

    public function render()
    {
        return view('livewire.admin.gaji.proses-gaji', [
            'ringkasan' => $this->getRingkasanData(),
        ]);
    }
}
