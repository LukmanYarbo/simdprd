<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Agama;
use App\Models\StatusKawin;
use App\Models\StatusKeanggotaan;
use App\Models\Jabatan;
use App\Http\Requests\Admin\StoreAnggotaRequest;
use App\Http\Requests\Admin\UpdateAnggotaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Optimization: Remove ->get() to allow DataTables helper to use server-side pagination
            $query = Anggota::with(['jabatan', 'statusKeanggotaan'])
                ->whereHas('statusKeanggotaan', function($query) {
                    $query->where('id', 1);
                })
                ->select('anggota.*');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_nik', function($row) {
                    $foto = $row->foto_anggota 
                        ? asset('storage/' . $row->foto_anggota) 
                        : "https://ui-avatars.com/api/?name=" . urlencode($row->nama_anggota) . "&background=random";
                    
                    return '
                        <div class="d-flex align-items-center">
                            <a href="'.route('admin.anggota.show', $row->id).'" class="text-decoration-none">
                                <img src="'.$foto.'" class="rounded-circle me-3 border shadow-sm img-hover-zoom" width="45" height="45" alt="" style="cursor: pointer;">
                            </a>
                            <div>
                                <h6 class="mb-0 text-red-500 fw-bold">'.$row->nama_anggota.'</h6>
                                <small class="text-muted"><i class="bi bi-card-text me-1"></i>'.$row->nik.'</small>
                            </div>
                        </div>';
                })
                ->addColumn('status', function($row) {
                    $badgeClass = $row->statusKeanggotaan->nama == 'Aktif' ? 'bg-success' : ($row->statusKeanggotaan->nama == 'Pensiun' ? 'bg-secondary' : 'bg-warning text-dark');
                    return '<span class="badge '.$badgeClass.' px-3 py-2">'.$row->statusKeanggotaan->nama.'</span>';
                })
                ->addColumn('kontak', function($row) {
                    return '
                        <div class="mb-1"><small class="text-muted"><i class="bi bi-envelope me-2 text-primary"></i>'.$row->email.'</small></div>
                        <div><small class="text-muted"><i class="bi bi-telephone me-2 text-success"></i>'.$row->no_telp.'</small></div>';
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="btn-group shadow-sm">
                            <a href="'.route('admin.anggota.edit', $row->id).'" class="btn btn-sm btn-light border-end" title="Edit">
                                <i class="bi bi-pencil-square text-warning"></i>
                            </a>
                            <button type="button" onclick="deleteAnggota('.$row->id.')" class="btn btn-sm btn-light" title="Hapus">
                                <i class="bi bi-trash3-fill text-danger"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['nama_nik', 'status', 'kontak', 'action'])
                ->make(true);
        }

        $stats = [
            'total' => Anggota::count(),
            'aktif' => Anggota::whereHas('statusKeanggotaan', fn($q) => $q->where('nama', 'Aktif'))->count(),
            'nonAktif' => Anggota::whereHas('statusKeanggotaan', fn($q) => $q->whereIn('nama', ['Non-Aktif', 'Pensiun']))->count(),
        ];

        return view('admin.anggota.index', compact('stats'));
    }

    public function validateStep(Request $request)
    {
        $step = $request->input('step');
        $id = $request->input('id');

        $rules = [];

        switch ($step) {
            case 1:
                $rules = [
                    'nik' => ['required', 'string', 'unique:anggota,nik' . ($id ? ',' . $id : '')],
                    'nokk' => ['required', 'string'],
                    'nama_anggota' => ['required', 'string', 'max:255'],
                    'tempat_lahir' => ['required', 'string'],
                    'tgl_lahir' => ['required', 'date'],
                    'id_agama' => ['required', 'exists:agama,id'],
                    'jk' => ['required', 'in:L,P'],
                    'id_status_kawin' => ['required', 'exists:status_kawin,id'],
                    'jmlh_istri' => ['required', 'integer', 'min:0'],
                    'jmlh_anak' => ['required', 'integer', 'min:0'],
                ];
                break;
            case 2:
                $rules = [
                    'no_telp' => ['required', 'string'],
                    'email' => ['required', 'email', 'unique:anggota,email' . ($id ? ',' . $id : '')],
                    'prov' => ['required', 'string'],
                    'kab' => ['required', 'string'],
                    'kec' => ['required', 'string'],
                    'desa' => ['required', 'string'],
                    'alamat_lengkap' => ['required', 'string'],
                ];
                break;
            case 3:
                $rules = [
                    'id_status_keanggotaan' => ['required', 'exists:status_keanggotaan,id'],
                    'id_jabatan' => ['required', 'exists:jabatan,id'],
                    'tgl_mulai' => ['required', 'date'],
                    'tgl_berhenti' => ['nullable', 'date'],
                    'no_rekening' => ['required', 'string'],
                ];
                break;
            case 4:
                $rules = [
                    'status_bpjs' => ['required', 'in:Y,T'],
                    'no_bpjs' => ['nullable', 'string', 'required_if:status_bpjs,Y'],
                    'status_jkk' => ['required', 'in:Y,T'],
                    'no_jkk' => ['nullable', 'string', 'required_if:status_jkk,Y'],
                    'status_jkm' => ['required', 'in:Y,T'],
                    'no_jkm' => ['nullable', 'string', 'required_if:status_jkm,Y'],
                    'status_tjgn_perum' => ['required', 'in:Y,T'],
                    'status_tjgn_transport' => ['required', 'in:Y,T'],
                    'foto_anggota' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
                ];
                break;
        }

        $request->validate($rules);

        return response()->json(['success' => true]);
    }

    public function create()
    {
        $agamas = Agama::all();
        $statusKawins = StatusKawin::all();
        $statusKeanggotaans = StatusKeanggotaan::all();
        $jabatans = Jabatan::all();
        return view('admin.anggota.create', compact('agamas', 'statusKawins', 'statusKeanggotaans', 'jabatans'));
    }

    public function store(StoreAnggotaRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto_anggota')) {
            $validated['foto_anggota'] = $request->file('foto_anggota')->store('foto_anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Anggota $anggota)
    {
        return view('admin.anggota.show', compact('anggota'));
    }

    public function edit(Anggota $anggota)
    {
        $agamas = Agama::all();
        $statusKawins = StatusKawin::all();
        $statusKeanggotaans = StatusKeanggotaan::all();
        $jabatans = Jabatan::all();
        return view('admin.anggota.edit', compact('anggota', 'agamas', 'statusKawins', 'statusKeanggotaans', 'jabatans'));
    }

    public function update(UpdateAnggotaRequest $request, Anggota $anggota)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto_anggota')) {
            if ($anggota->foto_anggota) {
                Storage::disk('public')->delete($anggota->foto_anggota);
            }
            $validated['foto_anggota'] = $request->file('foto_anggota')->store('foto_anggota', 'public');
        }

        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Request $request, Anggota $anggota)
    {
        if ($anggota->foto_anggota) {
            Storage::disk('public')->delete($anggota->foto_anggota);
        }
        $anggota->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Anggota berhasil dihapus.'
            ]);
        }

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
