<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PegawaiAsn;
use App\Models\Agama;
use App\Models\StatusKawin;
use App\Models\PangkatGolongan;
use App\Models\JabatanAsn;
use App\Models\StatusPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PegawaiAsnController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view pegawai_asn|create pegawai_asn|edit pegawai_asn|delete pegawai_asn', only: ['index', 'show']),
            new Middleware('permission:create pegawai_asn', only: ['create', 'store']),
            new Middleware('permission:edit pegawai_asn', only: ['edit', 'update']),
            new Middleware('permission:delete pegawai_asn', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawai = PegawaiAsn::with(['skpd', 'agama', 'statusKawin', 'pangkatGolongan', 'jabatanAsn', 'statusPegawai'])
            ->join('jabatan_asns', 'pegawai_asns.id_jabatan', '=', 'jabatan_asns.id')
            ->orderBy('jabatan_asns.id_esselon', 'asc')
            ->orderBy('pegawai_asns.id_pangkat_golongan', 'desc')
            ->select('pegawai_asns.*')
            ->paginate(10);
        return view('admin.pegawai_asn.index', compact('pegawai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agama = Agama::all();
        $statusKawin = StatusKawin::all();
        $pangkatGolongan = PangkatGolongan::all();
        $jabatan = JabatanAsn::all();
        $statusPegawai = StatusPegawai::all();
        
        return view('admin.pegawai_asn.create', compact('agama', 'statusKawin', 'pangkatGolongan', 'jabatan', 'statusPegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_skpd'        => 'nullable|exists:skpds,id',
            'nip'            => 'required|unique:pegawai_asns,nip',
            'nik'            => 'required|unique:pegawai_asns,nik',
            'nokk' => 'nullable|string',
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'id_agama' => 'required|exists:agama,id',
            'id_status_kawin' => 'required|exists:status_kawin,kode',
            'id_pangkat_golongan' => 'required|exists:pangkat_golongans,id',
            'id_jabatan' => 'required|exists:jabatan_asns,id',
            'ket_jabatan' => 'nullable|string',
            'id_status_pegawai' => 'required|exists:status_pegawais,id',
            'tanggal_mulai_kerja' => 'nullable|date',
            'tanggal_berhenti' => 'nullable|date',
            'email' => 'nullable|email|unique:pegawai_asns,email',
            'nohp' => 'nullable|string',
            'norek' => 'nullable|string',
            'npwp' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pegawai-photos', 'public');
        }

        PegawaiAsn::create($validated);

        return redirect()->route('admin.pegawai-asn.index')->with('success', 'Data Pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PegawaiAsn $pegawaiAsn)
    {
        return view('admin.pegawai_asn.show', compact('pegawaiAsn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PegawaiAsn $pegawaiAsn)
    {
        $agama = Agama::all();
        $statusKawin = StatusKawin::all();
        $pangkatGolongan = PangkatGolongan::all();
        $jabatan = JabatanAsn::all();
        $statusPegawai = StatusPegawai::all();

        return view('admin.pegawai_asn.edit', compact('pegawaiAsn', 'agama', 'statusKawin', 'pangkatGolongan', 'jabatan', 'statusPegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PegawaiAsn $pegawaiAsn)
    {
        $validated = $request->validate([
            'id_skpd'        => 'nullable|exists:skpds,id',
            'nip'            => 'required|unique:pegawai_asns,nip,' . $pegawaiAsn->id,
            'nik'            => 'required|unique:pegawai_asns,nik,' . $pegawaiAsn->id,
            'nokk' => 'nullable|string',
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'id_agama' => 'required|exists:agama,id',
            'id_status_kawin' => 'required|exists:status_kawin,kode',
            'id_pangkat_golongan' => 'required|exists:pangkat_golongans,id',
            'id_jabatan' => 'required|exists:jabatan_asns,id',
            'ket_jabatan' => 'nullable|string',
            'id_status_pegawai' => 'required|exists:status_pegawais,id',
            'tanggal_mulai_kerja' => 'nullable|date',
            'tanggal_berhenti' => 'nullable|date',
            'email' => 'nullable|email|unique:pegawai_asns,email,' . $pegawaiAsn->id,
            'nohp' => 'nullable|string',
            'norek' => 'nullable|string',
            'npwp' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($pegawaiAsn->foto) {
                Storage::disk('public')->delete($pegawaiAsn->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pegawai-photos', 'public');
        }

        $pegawaiAsn->update($validated);

        return redirect()->route('admin.pegawai-asn.index')->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PegawaiAsn $pegawaiAsn)
    {
        if ($pegawaiAsn->foto) {
            Storage::disk('public')->delete($pegawaiAsn->foto);
        }
        $pegawaiAsn->delete();

        return redirect()->route('admin.pegawai-asn.index')->with('success', 'Data Pegawai berhasil dihapus.');
    }
}
