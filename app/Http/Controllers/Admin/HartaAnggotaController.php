<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\HartaAnggota;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HartaAnggotaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view harta|create harta|edit harta|delete harta', only: ['index', 'edit']),
            new Middleware('permission:create harta', only: ['store']),
            new Middleware('permission:edit harta', only: ['update']),
            new Middleware('permission:delete harta', only: ['destroy']),
        ];
    }
    public function index($anggotaId)
    {
        $anggota = Anggota::findOrFail($anggotaId);
        $harta = HartaAnggota::where('id_anggota', $anggotaId)->latest()->get();

        return response()->json([
            'anggota' => $anggota,
            'harta' => $harta,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggota,id',
            'jenis_harta' => 'required|string',
            'nama_harta' => 'required|string',
            'tahun_perolehan' => 'required|integer|min:1900|max:2099',
            'harga_perolehan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        HartaAnggota::create($validated);

        return response()->json([
            'success' => 'Data harta berhasil ditambahkan.'
        ]);
    }

    public function edit($id)
    {
        $harta = HartaAnggota::findOrFail($id);
        return response()->json($harta);
    }

    public function update(Request $request, $id)
    {
        $harta = HartaAnggota::findOrFail($id);

        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggota,id',
            'jenis_harta' => 'required|string',
            'nama_harta' => 'required|string',
            'tahun_perolehan' => 'required|integer|min:1900|max:2099',
            'harga_perolehan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $harta->update($validated);

        return response()->json([
            'success' => 'Data harta berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $harta = HartaAnggota::findOrFail($id);
        $harta->delete();

        return response()->json([
            'success' => 'Data harta berhasil dihapus.'
        ]);
    }
}
