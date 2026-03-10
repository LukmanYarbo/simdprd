<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\IkatanKeluarga;
use App\Models\StatusKawin;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KeluargaController extends Controller
{
    public function index($id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);
        $keluarga = Keluarga::with(['ikatanKeluarga', 'statusKawin'])
            ->where('id_anggota', $id_anggota)
            ->get();
        
        $ikatanKeluarga = IkatanKeluarga::all();
        $statusKawin = StatusKawin::all();

        return response()->json([
            'anggota' => $anggota,
            'keluarga' => $keluarga,
            'ikatan_keluarga' => $ikatanKeluarga,
            'status_kawin' => $statusKawin
        ]);
    }

    public function store(Request $request)
    {
        $age = Carbon::parse($request->tgl_lahir)->age;
        
        $rules = [
            'id_anggota' => 'required|exists:anggota,id',
            'id_ikatan_keluarga' => 'required|exists:ikatan_keluarga,id',
            'nik' => 'required|numeric|unique:keluarga_anggota,nik',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|in:L,P',
            'id_status_kawin' => 'required|exists:status_kawin,id',
            'pekerjaan' => 'required|string|max:255',
            'status_anak' => 'nullable|in:AK,AA',
            'status_tunjangan' => 'required|in:Y,T',
            'no_sk_pengadilan' => 'nullable|string|max:255',
            'file_surat_ket' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if (in_array($request->status_anak, ['AK', 'AA']) && $age >= 21) {
            $rules['file_surat_ket'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('file_surat_ket')) {
            $validated['file_surat_ket'] = $request->file('file_surat_ket')->store('surat_keterangan_anak', 'public');
        }

        Keluarga::create($validated);

        $this->syncAnggotaFamilyCounters($request->id_anggota);

        return response()->json(['success' => 'Data keluarga berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $keluarga = Keluarga::findOrFail($id);
        return response()->json($keluarga);
    }

    public function update(Request $request, $id)
    {
        $keluarga = Keluarga::findOrFail($id);
        $age = Carbon::parse($request->tgl_lahir)->age;

        $rules = [
            'id_ikatan_keluarga' => 'required|exists:ikatan_keluarga,id',
            'nik' => 'required|numeric|unique:keluarga_anggota,nik,' . $id,
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|in:L,P',
            'id_status_kawin' => 'required|exists:status_kawin,id',
            'pekerjaan' => 'required|string|max:255',
            'status_anak' => 'nullable|in:AK,AA',
            'status_tunjangan' => 'required|in:Y,T',
            'no_sk_pengadilan' => 'nullable|string|max:255',
            'file_surat_ket' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if (in_array($request->status_anak, ['AK', 'AA']) && $age >= 21) {
            if (!$keluarga->file_surat_ket && !$request->hasFile('file_surat_ket')) {
                $rules['file_surat_ket'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            }
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('file_surat_ket')) {
            if ($keluarga->file_surat_ket) {
                Storage::disk('public')->delete($keluarga->file_surat_ket);
            }
            $validated['file_surat_ket'] = $request->file('file_surat_ket')->store('surat_keterangan_anak', 'public');
        }

        $keluarga->update($validated);
        
        $this->syncAnggotaFamilyCounters($keluarga->id_anggota);

        return response()->json(['success' => 'Data keluarga berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $keluarga = Keluarga::findOrFail($id);
        $id_anggota = $keluarga->id_anggota;
        
        if ($keluarga->file_surat_ket) {
            Storage::disk('public')->delete($keluarga->file_surat_ket);
        }
        $keluarga->delete();
        
        $this->syncAnggotaFamilyCounters($id_anggota);
        
        return response()->json(['success' => 'Data keluarga berhasil dihapus.']);
    }

    private function syncAnggotaFamilyCounters($id_anggota)
    {
        $jmlhIstri = Keluarga::where('id_anggota', $id_anggota)
            ->where('status_tunjangan', 'Y')
            ->whereHas('ikatanKeluarga', function($q) {
                $q->whereIn('nama', ['Suami', 'Istri']);
            })->count();

        $jmlhAnak = Keluarga::where('id_anggota', $id_anggota)
            ->where('status_tunjangan', 'Y')
            ->whereHas('ikatanKeluarga', function($q) {
                $q->where('nama', 'Anak');
            })->count();

        Anggota::where('id', $id_anggota)->update([
            'jmlh_istri' => $jmlhIstri,
            'jmlh_anak' => $jmlhAnak
        ]);
    }
}
