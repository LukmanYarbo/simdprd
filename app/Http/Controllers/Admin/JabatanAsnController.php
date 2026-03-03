<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JabatanAsn;
use App\Models\Esselon;
use App\Models\Skpd;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class JabatanAsnController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view jabatan_asn|create jabatan_asn|edit jabatan_asn|delete jabatan_asn', only: ['index', 'show']),
            new Middleware('permission:create jabatan_asn', only: ['create', 'store']),
            new Middleware('permission:edit jabatan_asn', only: ['edit', 'update']),
            new Middleware('permission:delete jabatan_asn', only: ['destroy']),
        ];
    }

    public function index()
    {
        $jabatan = JabatanAsn::with(['esselon', 'skpd'])
            ->join('esselons', 'jabatan_asns.id_esselon', '=', 'esselons.id')
            ->orderBy('esselons.id', 'asc')
            ->select('jabatan_asns.*') // Ensure we select jabatan fields to avoid id collision
            ->paginate(10);
        $esselon = Esselon::all();
        $skpd = Skpd::all();
        return view('admin.jabatan_asn.index', compact('jabatan', 'esselon', 'skpd'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'id_esselon' => 'required|exists:esselons,id',
        ]);

        try {
            JabatanAsn::create($request->all());
            return redirect()->route('admin.jabatan-asn.index')->with('success', 'Jabatan ASN berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'id_esselon' => 'required|exists:esselons,id',
        ]);

        try {
            $jabatan = JabatanAsn::findOrFail($id);
            $jabatan->update($request->all());
            return redirect()->route('admin.jabatan-asn.index')->with('success', 'Jabatan ASN berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $jabatan = JabatanAsn::findOrFail($id);
            $jabatan->delete();
            return redirect()->route('admin.jabatan-asn.index')->with('success', 'Jabatan ASN berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function searchBySkpd(Request $request)
    {
        $id_skpd = $request->id_skpd;
        $search = $request->q;

        $query = JabatanAsn::query();

        if ($id_skpd) {
            $query->where('id_skpd', $id_skpd);
        }

        if ($search) {
            $query->where('nama_jabatan', 'LIKE', "%{$search}%");
        }

        $jabatans = $query->limit(20)->get();

        $results = [];
        foreach ($jabatans as $jabatan) {
            $results[] = [
                'id' => $jabatan->id,
                'text' => $jabatan->nama_jabatan
            ];
        }

        return response()->json(['results' => $results]);
    }
}
