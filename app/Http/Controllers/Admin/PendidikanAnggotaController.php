<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\JenisPendidikan;
use App\Models\PendidikanAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendidikanAnggotaController extends Controller
{
    public function index($id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);
        $pendidikan = PendidikanAnggota::where('id_anggota', $id_anggota)
            ->with('jenisPendidikan')
            ->orderBy('tahun_lulus', 'desc')
            ->get();

        $jenisPendidikan = JenisPendidikan::all();

        if (request()->ajax()) {
            return response()->json([
                'anggota' => $anggota,
                'pendidikan' => $pendidikan,
                'jenis_pendidikan' => $jenisPendidikan
            ]);
        }

        // Keep view fallback or redirect if accessed directly without ajax
        return redirect()->route('admin.anggota.index');
    }

    public function create($id_anggota)
    {
        // Not used in modal approach
        return abort(404);
    }

    public function store(Request $request, $id_anggota)
    {
        $request->validate([
            'id_jenis_pendidikan' => 'required',
            'tempat_pendidikan' => 'required',
            'tahun_masuk' => 'nullable|digits:4',
            'tahun_lulus' => 'nullable|digits:4',
            'file_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['id_anggota'] = $id_anggota;

        if ($request->hasFile('file_ijazah')) {
            $data['file_ijazah'] = $request->file('file_ijazah')->store('ijazah', 'public');
        }

        PendidikanAnggota::create($data);

        return response()->json(['success' => 'Data pendidikan berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $pendidikan = PendidikanAnggota::findOrFail($id);
        return response()->json($pendidikan);
    }

    public function update(Request $request, $id)
    {
        $pendidikan = PendidikanAnggota::findOrFail($id);

        $request->validate([
            'id_jenis_pendidikan' => 'required',
            'tempat_pendidikan' => 'required',
            'tahun_masuk' => 'nullable|digits:4',
            'tahun_lulus' => 'nullable|digits:4',
            'file_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_ijazah')) {
            if ($pendidikan->file_ijazah) {
                Storage::disk('public')->delete($pendidikan->file_ijazah);
            }
            $data['file_ijazah'] = $request->file('file_ijazah')->store('ijazah', 'public');
        }

        $pendidikan->update($data);

        return response()->json(['success' => 'Data pendidikan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $pendidikan = PendidikanAnggota::findOrFail($id);
        if ($pendidikan->file_ijazah) {
            Storage::disk('public')->delete($pendidikan->file_ijazah);
        }
        $pendidikan->delete();

        return response()->json(['success' => 'Data pendidikan berhasil dihapus']);
    }
}
