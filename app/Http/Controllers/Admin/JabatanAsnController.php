<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JabatanAsn;
use App\Models\Esselon;
use Illuminate\Support\Facades\DB;

class JabatanAsnController extends Controller
{
    public function index()
    {
        $jabatan = JabatanAsn::with('esselon')
            ->join('esselons', 'jabatan_asns.id_esselon', '=', 'esselons.id')
            ->orderBy('esselons.id', 'asc')
            ->select('jabatan_asns.*') // Ensure we select jabatan fields to avoid id collision
            ->paginate(10);
        $esselon = Esselon::all();
        return view('admin.jabatan_asn.index', compact('jabatan', 'esselon'));
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
}
