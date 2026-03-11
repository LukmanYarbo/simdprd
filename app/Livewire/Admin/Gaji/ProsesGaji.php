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

    public bool $sudahDiproses = false;
    public bool $paramLengkap  = true;
    public array $missingParams = [];
    public ?string $blnThnLabel = null;

    public array $hasilProses = [];

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
            $data = $this->calculator->hitungGaji($anggota, $blnThn);
            TransaksiGaji::create($data);
            $hasil[] = [
                'nama'         => $anggota->nama_anggota,
                'gaji_pokok'   => $data['gaji_pokok'],
                'tunjangan_jabatan' => $data['tunjangan_jabatan'],
                'brutto1'      => $data['brutto1'],
                'potongan_pph21' => $data['potongan_pph21'],
                'jumlah_bersih' => $data['jumlah_bersih'],
            ];
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

    public function render()
    {
        $ringkasan = $this->sudahDiproses && empty($this->hasilProses)
            ? TransaksiGaji::where('bln_thn', $this->getBlnThn())
                ->with('anggota')
                ->get()
                ->map(fn($t) => [
                    'nama'              => $t->anggota->nama_anggota ?? '-',
                    'gaji_pokok'        => $t->gaji_pokok,
                    'tunjangan_jabatan' => $t->tunjangan_jabatan,
                    'brutto1'           => $t->brutto1,
                    'potongan_pph21'    => $t->potongan_pph21,
                    'jumlah_bersih'     => $t->jumlah_bersih,
                ])->toArray()
            : $this->hasilProses;

        return view('livewire.admin.gaji.proses-gaji', [
            'ringkasan' => $ringkasan,
        ]);
    }
}
