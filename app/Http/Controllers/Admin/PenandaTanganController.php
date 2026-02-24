<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenandaTangan;
use App\Models\Anggota;
use App\Models\PegawaiAsn;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenandaTanganController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view penanda_tangan|create penanda_tangan|edit penanda_tangan|delete penanda_tangan', only: ['index', 'show']),
            new Middleware('permission:create penanda_tangan', only: ['create', 'store']),
            new Middleware('permission:edit penanda_tangan', only: ['edit', 'update']),
            new Middleware('permission:delete penanda_tangan', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penandaTangan = PenandaTangan::with(['skpd', 'anggota', 'pegawaiAsn'])
            ->latest()
            ->paginate(10);

        return view('admin.penanda_tangan.index', compact('penandaTangan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only Ketua DPRD and Wakil Ketua DPRD
        $anggota = Anggota::with('jabatan')
            ->whereHas('jabatan', function ($q) {
                $q->whereIn('nama', ['Ketua DPRD', 'Wakil Ketua DPRD']);
            })
            ->get();

        return view('admin.penanda_tangan.create', compact('anggota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_dokumen'  => 'required|in:Surat Tugas,SPPD,Surat Keputusan',
            'id_anggota'     => 'nullable|exists:anggota,id',
            'id_skpd'        => 'nullable|exists:skpds,id',
            'id_pegawai_asn' => 'nullable|exists:pegawai_asns,id',
        ]);

        PenandaTangan::create($validated);

        return redirect()->route('admin.penanda-tangan.index')
            ->with('success', 'Data Penanda Tangan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenandaTangan $penandaTangan)
    {
        // Only Ketua DPRD and Wakil Ketua DPRD
        $anggota = Anggota::with('jabatan')
            ->whereHas('jabatan', function ($q) {
                $q->whereIn('nama', ['Ketua DPRD', 'Wakil Ketua DPRD']);
            })
            ->get();

        return view('admin.penanda_tangan.edit', compact('penandaTangan', 'anggota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenandaTangan $penandaTangan)
    {
        $validated = $request->validate([
            'jenis_dokumen'  => 'required|in:Surat Tugas,SPPD,Surat Keputusan',
            'id_anggota'     => 'nullable|exists:anggota,id',
            'id_skpd'        => 'nullable|exists:skpds,id',
            'id_pegawai_asn' => 'nullable|exists:pegawai_asns,id',
        ]);

        $penandaTangan->update($validated);

        return redirect()->route('admin.penanda-tangan.index')
            ->with('success', 'Data Penanda Tangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenandaTangan $penandaTangan)
    {
        $penandaTangan->delete();

        return redirect()->route('admin.penanda-tangan.index')
            ->with('success', 'Data Penanda Tangan berhasil dihapus.');
    }

    /**
     * AJAX: Search SKPD for Select2
     */
    public function searchSkpd(Request $request)
    {
        $term = $request->get('q', '');

        $skpds = Skpd::where('namaskpd', 'like', "%{$term}%")
            ->orderBy('namaskpd')
            ->limit(20)
            ->get(['id', 'namaskpd']);

        $results = $skpds->map(fn($s) => ['id' => $s->id, 'text' => $s->namaskpd]);

        return response()->json(['results' => $results]);
    }

    /**
     * AJAX: Search ASN by SKPD for Select2
     */
    public function searchAsn(Request $request)
    {
        $term   = $request->get('q', '');
        $idSkpd = $request->get('id_skpd');

        $query = PegawaiAsn::query()
            ->where('nama', 'like', "%{$term}%");

        if ($idSkpd) {
            $query->where('id_skpd', $idSkpd);
        }

        $asns = $query->orderBy('nama')->limit(20)->get(['id', 'nama', 'nip']);

        $results = $asns->map(fn($a) => [
            'id'   => $a->id,
            'text' => $a->nama . ' (' . $a->nip . ')',
        ]);

        return response()->json(['results' => $results]);
    }
}
