<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\IkatanKeluarga;
use App\Models\StatusKawin;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'id_anggota' => 'required|exists:anggota,id',
            'id_ikatan_keluarga' => 'required|exists:ikatan_keluarga,id',
            'nik' => 'required|numeric|unique:keluarga,nik',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|in:L,P',
            'id_status_kawin' => 'required|exists:status_kawin,id',
            'pekerjaan' => 'required|string|max:255',
            'status_anak' => 'nullable|in:AK,AA',
            'status_tunjangan' => 'required|in:Y,T',
            'no_sk_pengadilan' => 'nullable|string|max:255',
        ]);

        Keluarga::create($request->all());

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

        $request->validate([
            'id_ikatan_keluarga' => 'required|exists:ikatan_keluarga,id',
            'nik' => 'required|numeric|unique:keluarga,nik,' . $id,
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|in:L,P',
            'id_status_kawin' => 'required|exists:status_kawin,id',
            'pekerjaan' => 'required|string|max:255',
            'status_anak' => 'nullable|in:AK,AA',
            'status_tunjangan' => 'required|in:Y,T',
            'no_sk_pengadilan' => 'nullable|string|max:255',
        ]);

        $keluarga->update($request->all());

        return response()->json(['success' => 'Data keluarga berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        Keluarga::destroy($id);
        return response()->json(['success' => 'Data keluarga berhasil dihapus.']);
    }
}
