<?php

namespace App\Livewire\Admin\SuratTugas;

use App\Models\SuratTugasAnggota;
use App\Models\Anggota;
use App\Models\AnggotaSt;
use App\Models\JabatanDPRD;
use App\Models\PenandaTangan;
use App\Models\Skpd;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ManageSuratTugas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $id_surat_tugas;
    public $no_surat_tugas;
    public $uraian;
    public $tempat_asal;
    public $tempat_tujuan;
    public $tanggal_berangkat;
    public $tanggal_balik;
    public $lama_hari = 0;
    public $tanggal_ditetapkan;
    public $id_anggota_penandatangan;
    
    // Member Management Props
    public $selectedSuratTugasId;
    public $st_anggota_id;

    public $isEditMode = false;
    public $search = '';

    protected $listeners = ['deleteSuratTugas'];

    public function updated($propertyName)
    {
        if ($propertyName === 'tanggal_berangkat' || $propertyName === 'tanggal_balik') {
            $this->calculateLamaHari();
        }
    }

    public function calculateLamaHari()
    {
        if ($this->tanggal_berangkat && $this->tanggal_balik) {
            $start = Carbon::parse($this->tanggal_berangkat);
            $end = Carbon::parse($this->tanggal_balik);
            
            if ($end->gte($start)) {
                $this->lama_hari = $start->diffInDays($end) + 1;
            } else {
                $this->lama_hari = 0;
            }
        }
    }

    public function render()
    {
        $data = SuratTugasAnggota::with('penandatangan')
            ->where('no_surat_tugas', 'like', '%' . $this->search . '%')
            ->orWhere('uraian', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $skpdDprd = Skpd::where('namaskpd', 'Dewan Perwakilan Rakyat Daerah')->first();
        
        $penandatanganOptions = PenandaTangan::with(['anggota.jabatan', 'pegawaiAsn'])
            ->when($skpdDprd, function($q) use ($skpdDprd) {
                $q->where('id_skpd', $skpdDprd->id);
            })
            ->where('jenis_dokumen', 'like', '%Surat Tugas%')
            ->get();

        $allJabatan = JabatanDPRD::all();
        
        $addedMemberIds = $this->selectedSuratTugasId ? 
            AnggotaSt::where('id_surat_tugas_anggota', $this->selectedSuratTugasId)->pluck('id_anggota')->toArray() : [];

        $anggotaOptions = Anggota::with(['jabatan', 'jabatanKomisi'])
            ->whereNotIn('id', $addedMemberIds)
            ->orderBy('id_dprd', 'asc')
            ->orderBy('id_komisi', 'asc')
            ->get();

        $currentMembers = [];
        $selectedST = null;
        if ($this->selectedSuratTugasId) {
            $selectedST = SuratTugasAnggota::find($this->selectedSuratTugasId);
            $currentMembers = AnggotaSt::where('id_surat_tugas_anggota', $this->selectedSuratTugasId)
                ->select('anggota_st.*')
                ->join('anggota', 'anggota_st.id_anggota', '=', 'anggota.id')
                ->orderBy('anggota.id_dprd', 'asc')
                ->with('anggota.jabatan')
                ->get();
        }

        return view('livewire.admin.surat-tugas.manage-surat-tugas', [
            'dataSuratTugas' => $data,
            'penandatanganOptions' => $penandatanganOptions,
            'allJabatan' => $allJabatan,
            'anggotaOptions' => $anggotaOptions,
            'currentMembers' => $currentMembers,
            'selectedST' => $selectedST,
        ]);
    }

    public function resetFields()
    {
        $this->reset([
            'id_surat_tugas', 'no_surat_tugas', 'uraian', 'tempat_asal', 
            'tempat_tujuan', 'tanggal_berangkat', 'tanggal_balik', 
            'lama_hari', 'tanggal_ditetapkan', 'id_anggota_penandatangan'
        ]);
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'no_surat_tugas' => 'required|unique:surat_tugas_anggota,no_surat_tugas',
            'tempat_asal' => 'required',
            'tempat_tujuan' => 'required',
            'tanggal_berangkat' => 'required|date',
            'tanggal_balik' => 'required|date|after_or_equal:tanggal_berangkat',
            'lama_hari' => 'required|numeric|min:1',
            'tanggal_ditetapkan' => 'required|date',
            'id_anggota_penandatangan' => 'required|exists:anggota,id',
        ]);

        SuratTugasAnggota::create([
            'no_surat_tugas' => $this->no_surat_tugas,
            'uraian' => $this->uraian,
            'tempat_asal' => $this->tempat_asal,
            'tempat_tujuan' => $this->tempat_tujuan,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_balik' => $this->tanggal_balik,
            'lama_hari' => $this->lama_hari,
            'tanggal_ditetapkan' => $this->tanggal_ditetapkan,
            'id_anggota_penandatangan' => $this->id_anggota_penandatangan,
        ]);

        $this->resetFields();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Surat Tugas berhasil disimpan!', icon: 'success');
    }

    public function edit($id)
    {
        $data = SuratTugasAnggota::findOrFail($id);
        $this->id_surat_tugas = $data->id;
        $this->no_surat_tugas = $data->no_surat_tugas;
        $this->uraian = $data->uraian;
        $this->tempat_asal = $data->tempat_asal;
        $this->tempat_tujuan = $data->tempat_tujuan;
        $this->tanggal_berangkat = $data->tanggal_berangkat->format('Y-m-d');
        $this->tanggal_balik = $data->tanggal_balik->format('Y-m-d');
        $this->lama_hari = $data->lama_hari;
        $this->tanggal_ditetapkan = $data->tanggal_ditetapkan->format('Y-m-d');
        $this->id_anggota_penandatangan = $data->id_anggota_penandatangan;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'no_surat_tugas' => 'required|unique:surat_tugas_anggota,no_surat_tugas,' . $this->id_surat_tugas,
            'tempat_asal' => 'required',
            'tempat_tujuan' => 'required',
            'tanggal_berangkat' => 'required|date',
            'tanggal_balik' => 'required|date|after_or_equal:tanggal_berangkat',
            'lama_hari' => 'required|numeric|min:1',
            'tanggal_ditetapkan' => 'required|date',
            'id_anggota_penandatangan' => 'required|exists:anggota,id',
        ]);

        $data = SuratTugasAnggota::findOrFail($this->id_surat_tugas);
        $data->update([
            'no_surat_tugas' => $this->no_surat_tugas,
            'uraian' => $this->uraian,
            'tempat_asal' => $this->tempat_asal,
            'tempat_tujuan' => $this->tempat_tujuan,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_balik' => $this->tanggal_balik,
            'lama_hari' => $this->lama_hari,
            'tanggal_ditetapkan' => $this->tanggal_ditetapkan,
            'id_anggota_penandatangan' => $this->id_anggota_penandatangan,
        ]);

        $this->resetFields();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Surat Tugas berhasil diperbarui!', icon: 'success');
    }

    public function deleteSuratTugas($id)
    {
        SuratTugasAnggota::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Terhapus', text: 'Data berhasil dihapus', icon: 'success');
    }

    // Member Management Methods
    public function openManageAnggota($id)
    {
        $this->selectedSuratTugasId = $id;
        $this->reset(['st_anggota_id']);
        $this->dispatch('open-modal-manage-anggota');
    }

    public function addAnggota()
    {
        $this->validate([
            'st_anggota_id' => 'required|exists:anggota,id',
        ], [
            'st_anggota_id.required' => 'Pilih anggota terlebih dahulu.',
        ]);

        // Check for duplicates
        if (AnggotaSt::where('id_surat_tugas_anggota', $this->selectedSuratTugasId)
            ->where('id_anggota', $this->st_anggota_id)->exists()) {
            $this->addError('st_anggota_id', 'Anggota sudah ditambahkan.');
            return;
        }

        AnggotaSt::create([
            'id_surat_tugas_anggota' => $this->selectedSuratTugasId,
            'id_anggota' => $this->st_anggota_id,
        ]);

        $this->reset(['st_anggota_id']);
        $this->dispatch('swal', title: 'Berhasil', text: 'Anggota ditambahkan ke surat tugas', icon: 'success');
        $this->dispatch('refresh-select2');
    }

    public function removeAnggota($id)
    {
        AnggotaSt::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Anggota dihapus dari surat tugas', icon: 'success');
    }
}
