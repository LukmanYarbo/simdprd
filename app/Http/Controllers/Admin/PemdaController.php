<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pemda;
use App\Models\PegawaiAsn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PemdaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view pemda|create pemda|edit pemda|delete pemda', only: ['index', 'show']),
            new Middleware('permission:create pemda', only: ['create', 'store']),
            new Middleware('permission:edit pemda', only: ['edit', 'update']),
            new Middleware('permission:delete pemda', only: ['destroy']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemda = Pemda::with('sekda')->latest()->get();
        return view('admin.pemda.index', compact('pemda'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawai = PegawaiAsn::with(['jabatanAsn', 'pangkatGolongan'])
            ->join('jabatan_asns', 'pegawai_asns.id_jabatan', '=', 'jabatan_asns.id')
            ->orderBy('jabatan_asns.id_esselon', 'asc')
            ->orderBy('pegawai_asns.id_pangkat_golongan', 'desc')
            ->select('pegawai_asns.*')
            ->get();
        return view('admin.pemda.create', compact('pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namapemda' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'propinsi' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'nama_bupati' => 'required|string|max:255',
            'jabatan_bupati' => 'required|string|max:255',
            'judul_bupati' => 'required|string|max:255',
            'nama_wakil_bupati' => 'required|string|max:255',
            'jabatan_wakil_bupati' => 'required|string|max:255',
            'judul_wakil_bupati' => 'required|string|max:255',
            'id_sekda' => 'nullable|exists:pegawai_asns,id',
            'logo_pemda' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo_pemda')) {
            $path = $request->file('logo_pemda')->store('pemda-logos', 'public');
            $validated['logo_pemda'] = $path;
        }

        Pemda::create($validated);

        return redirect()->route('admin.pemda.index')->with('success', 'Data Pemda berhasil ditambahkan.');
    }

    public function getPegawaiDetails($id)
    {
        $pegawai = PegawaiAsn::with(['jabatanAsn', 'pangkatGolongan'])->find($id);
        
        if (!$pegawai) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        
        return response()->json([
            'nip' => $pegawai->nip,
            'jabatan' => $pegawai->jabatanAsn->nama_jabatan ?? '-',
            'pangkat' => $pegawai->pangkatGolongan->pangkat ?? '-', // Adjust field name based on PangkatGolongan model
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pemda = Pemda::findOrFail($id);
        $pegawai = PegawaiAsn::with(['jabatanAsn', 'pangkatGolongan'])
            ->join('jabatan_asns', 'pegawai_asns.id_jabatan', '=', 'jabatan_asns.id')
            ->orderBy('jabatan_asns.id_esselon', 'asc')
            ->orderBy('pegawai_asns.id_pangkat_golongan', 'desc')
            ->select('pegawai_asns.*')
            ->get();
        return view('admin.pemda.edit', compact('pemda', 'pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemda = Pemda::findOrFail($id);

        $validated = $request->validate([
            'namapemda' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'propinsi' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'nama_bupati' => 'required|string|max:255',
            'jabatan_bupati' => 'required|string|max:255',
            'judul_bupati' => 'required|string|max:255',
            'nama_wakil_bupati' => 'required|string|max:255',
            'jabatan_wakil_bupati' => 'required|string|max:255',
            'judul_wakil_bupati' => 'required|string|max:255',
            'id_sekda' => 'nullable|exists:pegawai_asns,id',
            'logo_pemda' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo_pemda')) {
            // Delete old logo
            if ($pemda->logo_pemda) {
                Storage::disk('public')->delete($pemda->logo_pemda);
            }
            $path = $request->file('logo_pemda')->store('pemda-logos', 'public');
            $validated['logo_pemda'] = $path;
        }

        $pemda->update($validated);

        return redirect()->route('admin.pemda.index')->with('success', 'Data Pemda berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemda = Pemda::findOrFail($id);
        if ($pemda->logo_pemda) {
            Storage::disk('public')->delete($pemda->logo_pemda);
        }
        $pemda->delete();

        return redirect()->route('admin.pemda.index')->with('success', 'Data Pemda berhasil dihapus.');
    }
}
