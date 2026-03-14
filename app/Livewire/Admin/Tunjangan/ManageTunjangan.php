<?php

namespace App\Livewire\Admin\Tunjangan;

use App\Models\TunjanganUmum;
use App\Models\TunjanganTransportasi;
use App\Models\TunjanganPerumahan;
use App\Models\TunjanganKomunikasiIntensif;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageTunjangan extends Component
{
    use WithFileUploads;

    public $activeTab = 'umum';

    // Validation flags for UI
    public $isEditMode = false;

    // -------------------------------------------------------------------------------- //
    // PROPERTIES: Tunjangan Umum
    // -------------------------------------------------------------------------------- //
    public $tu_id;
    public $tu_tunjangan_beras;
    public $tu_jumlah_beras;
    public $tu_tunjangan_anak_persen;
    public $tu_tunjangan_istri_persen;
    public $tu_status = 'Y';

    // -------------------------------------------------------------------------------- //
    // PROPERTIES: Tunjangan Transportasi
    // -------------------------------------------------------------------------------- //
    public $tt_id;
    public $tt_tgl_berlaku;
    public $tt_no_peraturan;
    public $tt_nilai_tunjangan_ketua;
    public $tt_nilai_tunjangan_wakil;
    public $tt_nilai_tunjangan_anggota;
    public $tt_file_peraturan;
    public $tt_file_peraturan_old;
    public $tt_status = 'Y';

    // -------------------------------------------------------------------------------- //
    // PROPERTIES: Tunjangan Perumahan
    // -------------------------------------------------------------------------------- //
    public $tp_id;
    public $tp_tgl_berlaku;
    public $tp_no_peraturan;
    public $tp_nilai_tunjangan_ketua;
    public $tp_nilai_tunjangan_wakil;
    public $tp_nilai_tunjangan_anggota;
    public $tp_file_peraturan;
    public $tp_file_peraturan_old;
    public $tp_status = 'Y';

    // -------------------------------------------------------------------------------- //
    // PROPERTIES: Tunjangan Komunikasi Intensif
    // -------------------------------------------------------------------------------- //
    public $tki_id;
    public $tki_tgl_berlaku;
    public $tki_no_peraturan;
    public $tki_nilai_tunjangan_tki;
    public $tki_file_peraturan;
    public $tki_file_peraturan_old;
    public $tki_status = 'Y';

    protected $listeners = ['deleteUmum', 'deleteTransportasi', 'deletePerumahan', 'deleteKomp'];

    // -------------------------------------------------------------------------------- //
    // RENDER & TABS
    // -------------------------------------------------------------------------------- //
    public function render()
    {
        return view('livewire.admin.tunjangan.manage-tunjangan', [
            'dataUmum' => TunjanganUmum::latest()->get(),
            'dataTransportasi' => TunjanganTransportasi::latest()->get(),
            'dataPerumahan' => TunjanganPerumahan::latest()->get(),
            'dataKomunikasi' => TunjanganKomunikasiIntensif::latest()->get(),
        ]);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetForms();
    }

    public function resetForms()
    {
        $this->isEditMode = false;
        $this->resetErrorBag();
        
        // Umum
        $this->reset(['tu_id', 'tu_tunjangan_beras', 'tu_jumlah_beras', 'tu_tunjangan_anak_persen', 'tu_tunjangan_istri_persen']);
        $this->tu_status = 'Y';
        // Transportasi
        $this->reset(['tt_id', 'tt_tgl_berlaku', 'tt_no_peraturan', 'tt_nilai_tunjangan_ketua', 'tt_nilai_tunjangan_wakil', 'tt_nilai_tunjangan_anggota', 'tt_file_peraturan', 'tt_file_peraturan_old']);
        $this->tt_status = 'Y';
        // Perumahan
        $this->reset(['tp_id', 'tp_tgl_berlaku', 'tp_no_peraturan', 'tp_nilai_tunjangan_ketua', 'tp_nilai_tunjangan_wakil', 'tp_nilai_tunjangan_anggota', 'tp_file_peraturan', 'tp_file_peraturan_old']);
        $this->tp_status = 'Y';
        // Komunikasi
        $this->reset(['tki_id', 'tki_tgl_berlaku', 'tki_no_peraturan', 'tki_nilai_tunjangan_tki', 'tki_file_peraturan', 'tki_file_peraturan_old']);
        $this->tki_status = 'Y';
    }

    // -------------------------------------------------------------------------------- //
    // CRUD: Umum
    // -------------------------------------------------------------------------------- //
    public function storeUmum()
    {
        $this->tu_tunjangan_beras = str_replace([',', '.'], '', $this->tu_tunjangan_beras);

        if ($this->tu_status === 'Y' && TunjanganUmum::where('status', 'Y')->exists()) {
            $this->addError('tu_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tu_tunjangan_beras' => 'required|numeric',
            'tu_jumlah_beras' => 'required|numeric',
            'tu_tunjangan_anak_persen' => 'required|numeric',
            'tu_tunjangan_istri_persen' => 'required|numeric',
            'tu_status' => 'required|in:Y,T',
        ]);

        TunjanganUmum::create([
            'tunjangan_beras' => $this->tu_tunjangan_beras,
            'jumlah_beras' => $this->tu_jumlah_beras,
            'tunjangan_anak_persen' => $this->tu_tunjangan_anak_persen,
            'tunjangan_istri_persen' => $this->tu_tunjangan_istri_persen,
            'status' => $this->tu_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Umum ditambahkan!', icon: 'success');
    }

    public function editUmum($id)
    {
        $data = TunjanganUmum::findOrFail($id);
        $this->tu_id = $data->id;
        $this->tu_tunjangan_beras = number_format((int) $data->tunjangan_beras, 0, '', ',');
        $this->tu_jumlah_beras = $data->jumlah_beras;
        $this->tu_tunjangan_anak_persen = $data->tunjangan_anak_persen;
        $this->tu_tunjangan_istri_persen = $data->tunjangan_istri_persen;
        $this->tu_status = $data->status;
        $this->isEditMode = true;
    }

    public function updateUmum()
    {
        $this->tu_tunjangan_beras = str_replace([',', '.'], '', $this->tu_tunjangan_beras);

        if ($this->tu_status === 'Y' && TunjanganUmum::where('status', 'Y')->where('id', '!=', $this->tu_id)->exists()) {
            $this->addError('tu_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tu_tunjangan_beras' => 'required|numeric',
            'tu_jumlah_beras' => 'required|numeric',
            'tu_tunjangan_anak_persen' => 'required|numeric',
            'tu_tunjangan_istri_persen' => 'required|numeric',
            'tu_status' => 'required|in:Y,T',
        ]);

        $data = TunjanganUmum::findOrFail($this->tu_id);
        $data->update([
            'tunjangan_beras' => $this->tu_tunjangan_beras,
            'jumlah_beras' => $this->tu_jumlah_beras,
            'tunjangan_anak_persen' => $this->tu_tunjangan_anak_persen,
            'tunjangan_istri_persen' => $this->tu_tunjangan_istri_persen,
            'status' => $this->tu_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Umum diperbarui!', icon: 'success');
    }

    public function deleteUmum($id)
    {
        TunjanganUmum::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Terhapus', text: 'Data berhasil dihapus', icon: 'success');
    }

    // -------------------------------------------------------------------------------- //
    // CRUD: Transportasi
    // -------------------------------------------------------------------------------- //
    public function storeTransportasi()
    {
        $this->tt_nilai_tunjangan_ketua = str_replace([',', '.'], '', $this->tt_nilai_tunjangan_ketua);
        $this->tt_nilai_tunjangan_wakil = str_replace([',', '.'], '', $this->tt_nilai_tunjangan_wakil);
        $this->tt_nilai_tunjangan_anggota = str_replace([',', '.'], '', $this->tt_nilai_tunjangan_anggota);

        if ($this->tt_status === 'Y' && TunjanganTransportasi::where('status', 'Y')->exists()) {
            $this->addError('tt_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tt_tgl_berlaku' => 'required|date',
            'tt_no_peraturan' => 'required|string',
            'tt_nilai_tunjangan_ketua' => 'required|numeric',
            'tt_nilai_tunjangan_wakil' => 'required|numeric',
            'tt_nilai_tunjangan_anggota' => 'required|numeric',
            'tt_file_peraturan' => 'nullable|file|mimes:pdf|max:2048',
            'tt_status' => 'required|in:Y,T',
        ]);

        $path = null;
        if ($this->tt_file_peraturan) {
            $extension = $this->tt_file_peraturan->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($this->tt_no_peraturan);
            $filename = $noPeraturanSlug . '_tunjangan_transportasi_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $path = $this->tt_file_peraturan->storeAs('tunjangan/transportasi', $filename, 'public');
        }

        TunjanganTransportasi::create([
            'tgl_berlaku' => $this->tt_tgl_berlaku,
            'no_peraturan' => $this->tt_no_peraturan,
            'nilai_tunjangan_ketua' => $this->tt_nilai_tunjangan_ketua,
            'nilai_tunjangan_wakil' => $this->tt_nilai_tunjangan_wakil,
            'nilai_tunjangan_anggota' => $this->tt_nilai_tunjangan_anggota,
            'file_peraturan' => $path,
            'status' => $this->tt_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Transportasi ditambahkan!', icon: 'success');
    }

    public function editTransportasi($id)
    {
        $data = TunjanganTransportasi::findOrFail($id);
        $this->tt_id = $data->id;
        $this->tt_tgl_berlaku = $data->tgl_berlaku;
        $this->tt_no_peraturan = $data->no_peraturan;
        $this->tt_nilai_tunjangan_ketua = number_format((int) $data->nilai_tunjangan_ketua, 0, '', ',');
        $this->tt_nilai_tunjangan_wakil = number_format((int) $data->nilai_tunjangan_wakil, 0, '', ',');
        $this->tt_nilai_tunjangan_anggota = number_format((int) $data->nilai_tunjangan_anggota, 0, '', ',');
        $this->tt_file_peraturan_old = $data->file_peraturan;
        $this->tt_status = $data->status;
        $this->isEditMode = true;
    }

    public function updateTransportasi()
    {
        $this->tt_nilai_tunjangan_ketua = str_replace([',', '.'], '', $this->tt_nilai_tunjangan_ketua);
        $this->tt_nilai_tunjangan_wakil = str_replace([',', '.'], '', $this->tt_nilai_tunjangan_wakil);
        $this->tt_nilai_tunjangan_anggota = str_replace([',', '.'], '', $this->tt_nilai_tunjangan_anggota);

        if ($this->tt_status === 'Y' && TunjanganTransportasi::where('status', 'Y')->where('id', '!=', $this->tt_id)->exists()) {
            $this->addError('tt_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tt_tgl_berlaku' => 'required|date',
            'tt_no_peraturan' => 'required|string',
            'tt_nilai_tunjangan_ketua' => 'required|numeric',
            'tt_nilai_tunjangan_wakil' => 'required|numeric',
            'tt_nilai_tunjangan_anggota' => 'required|numeric',
            'tt_file_peraturan' => 'nullable|file|mimes:pdf|max:2048',
            'tt_status' => 'required|in:Y,T',
        ]);

        $data = TunjanganTransportasi::findOrFail($this->tt_id);
        $path = $data->file_peraturan;

        if ($this->tt_file_peraturan) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $extension = $this->tt_file_peraturan->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($this->tt_no_peraturan);
            $filename = $noPeraturanSlug . '_tunjangan_transportasi_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $path = $this->tt_file_peraturan->storeAs('tunjangan/transportasi', $filename, 'public');
        }

        $data->update([
            'tgl_berlaku' => $this->tt_tgl_berlaku,
            'no_peraturan' => $this->tt_no_peraturan,
            'nilai_tunjangan_ketua' => $this->tt_nilai_tunjangan_ketua,
            'nilai_tunjangan_wakil' => $this->tt_nilai_tunjangan_wakil,
            'nilai_tunjangan_anggota' => $this->tt_nilai_tunjangan_anggota,
            'file_peraturan' => $path,
            'status' => $this->tt_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Transportasi diperbarui!', icon: 'success');
    }

    public function deleteTransportasi($id)
    {
        $data = TunjanganTransportasi::findOrFail($id);
        if ($data->file_peraturan && Storage::disk('public')->exists($data->file_peraturan)) {
            Storage::disk('public')->delete($data->file_peraturan);
        }
        $data->delete();
        $this->dispatch('swal', title: 'Terhapus', text: 'Data berhasil dihapus', icon: 'success');
    }

    // -------------------------------------------------------------------------------- //
    // CRUD: Perumahan
    // -------------------------------------------------------------------------------- //
    public function storePerumahan()
    {
        $this->tp_nilai_tunjangan_ketua = str_replace([',', '.'], '', $this->tp_nilai_tunjangan_ketua);
        $this->tp_nilai_tunjangan_wakil = str_replace([',', '.'], '', $this->tp_nilai_tunjangan_wakil);
        $this->tp_nilai_tunjangan_anggota = str_replace([',', '.'], '', $this->tp_nilai_tunjangan_anggota);

        if ($this->tp_status === 'Y' && TunjanganPerumahan::where('status', 'Y')->exists()) {
            $this->addError('tp_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tp_tgl_berlaku' => 'required|date',
            'tp_no_peraturan' => 'required|string',
            'tp_nilai_tunjangan_ketua' => 'required|numeric',
            'tp_nilai_tunjangan_wakil' => 'required|numeric',
            'tp_nilai_tunjangan_anggota' => 'required|numeric',
            'tp_file_peraturan' => 'nullable|file|mimes:pdf|max:2048',
            'tp_status' => 'required|in:Y,T',
        ]);

        $path = null;
        if ($this->tp_file_peraturan) {
            $extension = $this->tp_file_peraturan->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($this->tp_no_peraturan);
            $filename = $noPeraturanSlug . '_tunjangan_perumahan_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $path = $this->tp_file_peraturan->storeAs('tunjangan/perumahan', $filename, 'public');
        }

        TunjanganPerumahan::create([
            'tgl_berlaku' => $this->tp_tgl_berlaku,
            'no_peraturan' => $this->tp_no_peraturan,
            'nilai_tunjangan_ketua' => $this->tp_nilai_tunjangan_ketua,
            'nilai_tunjangan_wakil' => $this->tp_nilai_tunjangan_wakil,
            'nilai_tunjangan_anggota' => $this->tp_nilai_tunjangan_anggota,
            'file_peraturan' => $path,
            'status' => $this->tp_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Perumahan ditambahkan!', icon: 'success');
    }

    public function editPerumahan($id)
    {
        $data = TunjanganPerumahan::findOrFail($id);
        $this->tp_id = $data->id;
        $this->tp_tgl_berlaku = $data->tgl_berlaku;
        $this->tp_no_peraturan = $data->no_peraturan;
        $this->tp_nilai_tunjangan_ketua = number_format((int) $data->nilai_tunjangan_ketua, 0, '', ',');
        $this->tp_nilai_tunjangan_wakil = number_format((int) $data->nilai_tunjangan_wakil, 0, '', ',');
        $this->tp_nilai_tunjangan_anggota = number_format((int) $data->nilai_tunjangan_anggota, 0, '', ',');
        $this->tp_file_peraturan_old = $data->file_peraturan;
        $this->tp_status = $data->status;
        $this->isEditMode = true;
    }

    public function updatePerumahan()
    {
        $this->tp_nilai_tunjangan_ketua = str_replace([',', '.'], '', $this->tp_nilai_tunjangan_ketua);
        $this->tp_nilai_tunjangan_wakil = str_replace([',', '.'], '', $this->tp_nilai_tunjangan_wakil);
        $this->tp_nilai_tunjangan_anggota = str_replace([',', '.'], '', $this->tp_nilai_tunjangan_anggota);

        if ($this->tp_status === 'Y' && TunjanganPerumahan::where('status', 'Y')->where('id', '!=', $this->tp_id)->exists()) {
            $this->addError('tp_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tp_tgl_berlaku' => 'required|date',
            'tp_no_peraturan' => 'required|string',
            'tp_nilai_tunjangan_ketua' => 'required|numeric',
            'tp_nilai_tunjangan_wakil' => 'required|numeric',
            'tp_nilai_tunjangan_anggota' => 'required|numeric',
            'tp_file_peraturan' => 'nullable|file|mimes:pdf|max:2048',
            'tp_status' => 'required|in:Y,T',
        ]);

        $data = TunjanganPerumahan::findOrFail($this->tp_id);
        $path = $data->file_peraturan;

        if ($this->tp_file_peraturan) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $extension = $this->tp_file_peraturan->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($this->tp_no_peraturan);
            $filename = $noPeraturanSlug . '_tunjangan_perumahan_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $path = $this->tp_file_peraturan->storeAs('tunjangan/perumahan', $filename, 'public');
        }

        $data->update([
            'tgl_berlaku' => $this->tp_tgl_berlaku,
            'no_peraturan' => $this->tp_no_peraturan,
            'nilai_tunjangan_ketua' => $this->tp_nilai_tunjangan_ketua,
            'nilai_tunjangan_wakil' => $this->tp_nilai_tunjangan_wakil,
            'nilai_tunjangan_anggota' => $this->tp_nilai_tunjangan_anggota,
            'file_peraturan' => $path,
            'status' => $this->tp_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Perumahan diperbarui!', icon: 'success');
    }

    public function deletePerumahan($id)
    {
        $data = TunjanganPerumahan::findOrFail($id);
        if ($data->file_peraturan && Storage::disk('public')->exists($data->file_peraturan)) {
            Storage::disk('public')->delete($data->file_peraturan);
        }
        $data->delete();
        $this->dispatch('swal', title: 'Terhapus', text: 'Data berhasil dihapus', icon: 'success');
    }

    // -------------------------------------------------------------------------------- //
    // CRUD: Komunikasi Intensif
    // -------------------------------------------------------------------------------- //
    public function storeKomunikasi()
    {
        $this->tki_nilai_tunjangan_tki = str_replace([',', '.'], '', $this->tki_nilai_tunjangan_tki);

        if ($this->tki_status === 'Y' && TunjanganKomunikasiIntensif::where('status', 'Y')->exists()) {
            $this->addError('tki_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tki_tgl_berlaku' => 'required|date',
            'tki_no_peraturan' => 'required|string',
            'tki_nilai_tunjangan_tki' => 'required|numeric',
            'tki_file_peraturan' => 'nullable|file|mimes:pdf|max:2048',
            'tki_status' => 'required|in:Y,T',
        ]);

        $path = null;
        if ($this->tki_file_peraturan) {
            $extension = $this->tki_file_peraturan->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($this->tki_no_peraturan);
            $filename = $noPeraturanSlug . '_tunjangan_komunikasi_intensif_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $path = $this->tki_file_peraturan->storeAs('tunjangan/komunikasi', $filename, 'public');
        }

        TunjanganKomunikasiIntensif::create([
            'tgl_berlaku' => $this->tki_tgl_berlaku,
            'no_peraturan' => $this->tki_no_peraturan,
            'nilai_tunjangan_tki' => $this->tki_nilai_tunjangan_tki,
            'file_peraturan' => $path,
            'status' => $this->tki_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Komunikasi ditambahkan!', icon: 'success');
    }

    public function editKomunikasi($id)
    {
        $data = TunjanganKomunikasiIntensif::findOrFail($id);
        $this->tki_id = $data->id;
        $this->tki_tgl_berlaku = $data->tgl_berlaku;
        $this->tki_no_peraturan = $data->no_peraturan;
        $this->tki_nilai_tunjangan_tki = number_format((int) $data->nilai_tunjangan_tki, 0, '', ',');
        $this->tki_file_peraturan_old = $data->file_peraturan;
        $this->tki_status = $data->status;
        $this->isEditMode = true;
    }

    public function updateKomunikasi()
    {
        $this->tki_nilai_tunjangan_tki = str_replace([',', '.'], '', $this->tki_nilai_tunjangan_tki);

        if ($this->tki_status === 'Y' && TunjanganKomunikasiIntensif::where('status', 'Y')->where('id', '!=', $this->tki_id)->exists()) {
            $this->addError('tki_status', 'Hanya boleh ada 1 tunjangan yang aktif.');
            return;
        }

        $this->validate([
            'tki_tgl_berlaku' => 'required|date',
            'tki_no_peraturan' => 'required|string',
            'tki_nilai_tunjangan_tki' => 'required|numeric',
            'tki_file_peraturan' => 'nullable|file|mimes:pdf|max:2048',
            'tki_status' => 'required|in:Y,T',
        ]);

        $data = TunjanganKomunikasiIntensif::findOrFail($this->tki_id);
        $path = $data->file_peraturan;

        if ($this->tki_file_peraturan) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $extension = $this->tki_file_peraturan->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($this->tki_no_peraturan);
            $filename = $noPeraturanSlug . '_tunjangan_komunikasi_intensif_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $path = $this->tki_file_peraturan->storeAs('tunjangan/komunikasi', $filename, 'public');
        }

        $data->update([
            'tgl_berlaku' => $this->tki_tgl_berlaku,
            'no_peraturan' => $this->tki_no_peraturan,
            'nilai_tunjangan_tki' => $this->tki_nilai_tunjangan_tki,
            'file_peraturan' => $path,
            'status' => $this->tki_status,
        ]);

        $this->resetForms();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data Tunjangan Komunikasi diperbarui!', icon: 'success');
    }

    public function deleteKomp($id)
    {
        $data = TunjanganKomunikasiIntensif::findOrFail($id);
        if ($data->file_peraturan && Storage::disk('public')->exists($data->file_peraturan)) {
            Storage::disk('public')->delete($data->file_peraturan);
        }
        $data->delete();
        $this->dispatch('swal', title: 'Terhapus', text: 'Data berhasil dihapus', icon: 'success');
    }
}
