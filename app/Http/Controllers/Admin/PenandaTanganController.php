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
            'jenis_dokumen'  => 'required|array',
            'jenis_dokumen.*' => 'in:Surat Tugas,SPPD,Surat Keputusan,Pengajuan Gaji',
            'id_anggota'     => 'nullable|exists:anggota,id',
            'id_skpd'        => 'nullable|exists:skpds,id',
            'id_pegawai_asn' => 'nullable|exists:pegawai_asns,id',
        ]);

        // Check if signee already exists in this SKPD
        $query = PenandaTangan::where('id_skpd', $validated['id_skpd']);

        if ($validated['id_anggota']) {
            $query->where('id_anggota', $validated['id_anggota']);
        } elseif ($validated['id_pegawai_asn']) {
            $query->where('id_pegawai_asn', $validated['id_pegawai_asn']);
        } else {
            $validated['jenis_dokumen'] = implode(',', $validated['jenis_dokumen']);
            PenandaTangan::create($validated);
            return redirect()->route('admin.penanda-tangan.index')
                ->with('success', 'Data Penanda Tangan berhasil ditambahkan.');
        }

        $existing = $query->first();

        if ($existing) {
            $existingJenis = explode(',', $existing->jenis_dokumen);
            $newJenis = array_unique(array_merge($existingJenis, $validated['jenis_dokumen']));
            
            $existing->update([
                'jenis_dokumen' => implode(',', $newJenis)
            ]);
            
            $message = 'Jenis dokumen berhasil ditambahkan ke penanda tangan yang sudah ada.';
        } else {
            $validated['jenis_dokumen'] = implode(',', $validated['jenis_dokumen']);
            PenandaTangan::create($validated);
            $message = 'Data Penanda Tangan berhasil ditambahkan.';
        }

        return redirect()->route('admin.penanda-tangan.index')
            ->with('success', $message);
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
            'jenis_dokumen'  => 'required|array',
            'jenis_dokumen.*' => 'in:Surat Tugas,SPPD,Surat Keputusan,Pengajuan Gaji',
            'id_anggota'     => 'nullable|exists:anggota,id',
            'id_skpd'        => 'nullable|exists:skpds,id',
            'id_pegawai_asn' => 'nullable|exists:pegawai_asns,id',
        ]);

        $validated['jenis_dokumen'] = implode(',', $validated['jenis_dokumen']);

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

        $asns = $query->join('jabatan_asns', 'pegawai_asns.id_jabatan', '=', 'jabatan_asns.id')
            ->orderBy('jabatan_asns.id_esselon', 'asc')
            ->orderBy('pegawai_asns.id_pangkat_golongan', 'desc')
            ->limit(20)
            ->get(['pegawai_asns.id', 'pegawai_asns.nama', 'pegawai_asns.nip']);

        $results = $asns->map(fn($a) => [
            'id'   => $a->id,
            'text' => $a->nama . ' (' . $a->nip . ')',
        ]);

        return response()->json(['results' => $results]);
    }
}
