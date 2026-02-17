<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKeputusan;
use App\Models\AlatKelengkapan;
use App\Models\Anggota;
use App\Models\JabatanAnggota;
use App\Models\JabatanAlatKelengkapan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SuratKeputusanController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view surat_keputusan|create surat_keputusan|edit surat_keputusan|delete surat_keputusan', only: ['index', 'show']),
            new Middleware('permission:create surat_keputusan', only: ['create', 'store']),
            new Middleware('permission:edit surat_keputusan', only: ['edit', 'update', 'getAnggota', 'storeAnggota', 'destroyAnggota']),
            new Middleware('permission:delete surat_keputusan', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratKeputusan::with('alatKelengkapan')->withCount('jabatanAnggota')->orderBy('id_alat_kelengkapan')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_anggota', function($row){
                    return $row->jabatan_anggota_count . ' Orang';
                })
                ->editColumn('tgl_sk', function($row){
                    return \Carbon\Carbon::parse($row->tgl_sk)->locale('id')->translatedFormat('d F Y');
                })
                ->addColumn('file_download', function($row){
                    if($row->file_sk){
                        return '<a href="'.asset('storage/'.$row->file_sk).'" target="_blank" class="btn btn-sm btn-info text-white"><i class="bi bi-download"></i> Unduh</a>';
                    }
                    return '-';
                })
                ->addColumn('action', function($row){
                    $user = auth()->user();
                    \Illuminate\Support\Facades\Log::info('User: ' . $user->email . ' | Role: ' . $user->getRoleNames() . ' | Can Edit SK: ' . ($user->can('edit surat_keputusan') ? 'Yes' : 'No') . ' | Can Delete SK: ' . ($user->can('delete surat_keputusan') ? 'Yes' : 'No'));
                    
                    $btn = '<div class="btn-group shadow-sm">';
                    if($user->can('edit surat_keputusan')){
                        $btn .= '<button type="button" class="btn btn-sm btn-info text-white border-end btn-members" data-id="'.$row->id.'" title="Kelola Anggota"><i class="bi bi-people-fill"></i></button>';
                        $btn .= '<button type="button" class="btn btn-sm btn-light border-end btn-edit" data-id="'.$row->id.'" title="Edit"><i class="bi bi-pencil-square text-warning"></i></button>';
                    }
                    if($user->can('delete surat_keputusan')){
                        $btn .= '<button type="button" onclick="deleteItem('.$row->id.')" class="btn btn-sm btn-light" title="Hapus"><i class="bi bi-trash3-fill text-danger"></i></button>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['file_download', 'action'])
                ->make(true);
        }
        $alatKelengkapans = AlatKelengkapan::all();
        return view('admin.surat_keputusan.index', compact('alatKelengkapans'));
    }

    public function create()
    {
        $alatKelengkapans = AlatKelengkapan::all();
        return view('admin.surat_keputusan.create', compact('alatKelengkapans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_sk' => 'required|string|max:255',
            'ket_sk' => 'nullable|string',
            'tgl_sk' => 'required|date',
            'file_sk' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'id_alat_kelengkapan' => 'required|exists:alat_kelengkapan,id',
            'status' => 'required|in:A,T',
        ]);

        if ($request->status == 'A') {
            $existingActive = SuratKeputusan::where('id_alat_kelengkapan', $request->id_alat_kelengkapan)
                ->where('status', 'A')
                ->exists();
            
            if ($existingActive) {
                return response()->json(['errors' => ['status' => ['Hanya boleh ada satu SK aktif untuk Alat Kelengkapan ini.']]], 422);
            }
        }

        $input = $request->all();

        if ($request->hasFile('file_sk')) {
            $input['file_sk'] = $request->file('file_sk')->store('files_sk', 'public');
        }

        SuratKeputusan::create($input);

        return response()->json(['success' => 'Surat Keputusan berhasil ditambahkan.']);
    }

    public function show(SuratKeputusan $suratKeputusan)
    {
        return view('admin.surat_keputusan.show', compact('suratKeputusan'));
    }

    public function edit(SuratKeputusan $suratKeputusan)
    {
        return response()->json($suratKeputusan);
    }

    public function update(Request $request, SuratKeputusan $suratKeputusan)
    {
        $request->validate([
            'no_sk' => 'required|string|max:255',
            'ket_sk' => 'nullable|string',
            'tgl_sk' => 'required|date',
            'file_sk' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'id_alat_kelengkapan' => 'required|exists:alat_kelengkapan,id',
            'status' => 'required|in:A,T',
        ]);

        if ($request->status == 'A') {
            $existingActive = SuratKeputusan::where('id_alat_kelengkapan', $request->id_alat_kelengkapan)
                ->where('status', 'A')
                ->where('id', '!=', $suratKeputusan->id)
                ->exists();
            
            if ($existingActive) {
                return response()->json(['errors' => ['status' => ['Hanya boleh ada satu SK aktif untuk Alat Kelengkapan ini.']]], 422);
            }
        }

        $input = $request->all();

        if ($request->hasFile('file_sk')) {
            if ($suratKeputusan->file_sk) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($suratKeputusan->file_sk);
            }
            $input['file_sk'] = $request->file('file_sk')->store('files_sk', 'public');
        }

        $suratKeputusan->update($input);

        return response()->json(['success' => 'Surat Keputusan berhasil diperbarui.']);
    }

    public function destroy(SuratKeputusan $suratKeputusan)
    {
        if ($suratKeputusan->status == 'A') {
            $memberCount = JabatanAnggota::where('id_surat_keputusan', $suratKeputusan->id)->count();
            if ($memberCount > 0) {
                return response()->json(['error' => 'Gagal! SK Aktif yang memiliki anggota tidak dapat dihapus.'], 422);
            }
        }

        if ($suratKeputusan->file_sk) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($suratKeputusan->file_sk);
        }
        $suratKeputusan->delete();

        return response()->json(['success'=>'Surat Keputusan berhasil dihapus.']);
    }

    public function getAnggota($id)
    {
        $suratKeputusan = SuratKeputusan::with('alatKelengkapan')->findOrFail($id);
        $anggota = JabatanAnggota::with(['anggota', 'jabatanAlatKelengkapan'])
            ->where('id_surat_keputusan', $id)
            ->get();
        
        $existingMemberIds = $anggota->pluck('id_anggota')->toArray();
        $allAnggota = Anggota::select('id', 'nama_anggota', 'nik')
            ->where('id_status_keanggotaan', 1)
            ->whereNotIn('id', $existingMemberIds)
            ->orderBy('nama_anggota')
            ->get();
        $jabatanAlatKelengkapan = JabatanAlatKelengkapan::all();

        return response()->json([
            'surat_keputusan' => $suratKeputusan,
            'existing_anggota' => $anggota,
            'all_anggota' => $allAnggota,
            'jabatan_options' => $jabatanAlatKelengkapan
        ]);
    }

    public function storeAnggota(Request $request)
    {
        $request->validate([
            'id_surat_keputusan' => 'required|exists:surat_keputusan,id',
            'id_anggota' => 'required|exists:anggota,id',
            'id_jabatan_alat_kelengkapan' => 'required|exists:jabatan_alat_kelengkapan,id',
        ]);

        $suratKeputusan = SuratKeputusan::findOrFail($request->id_surat_keputusan);

        // Check if member already exists in this SK
        $exists = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
            ->where('id_anggota', $request->id_anggota)
            ->exists();

        if ($exists) {
            return response()->json(['errors' => ['id_anggota' => ['Anggota ini sudah ada dalam SK ini.']]], 422);
        }

        // Validate Unique Positions (Ketua, Wakil, Sekretaris)
        $jabatan = JabatanAlatKelengkapan::findOrFail($request->id_jabatan_alat_kelengkapan);
        if (in_array($jabatan->nama, ['Ketua', 'Wakil', 'Sekretaris'])) {
            $positionExists = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
                ->where('id_jabatan_alat_kelengkapan', $request->id_jabatan_alat_kelengkapan)
                ->exists();
            
            if ($positionExists) {
                return response()->json(['errors' => ['id_jabatan_alat_kelengkapan' => ["Jabatan $jabatan->nama sudah terisi dalam SK ini."]]], 422);
            }
        }

        JabatanAnggota::create([
            'id_surat_keputusan' => $request->id_surat_keputusan,
            'id_anggota' => $request->id_anggota,
            'id_jabatan_alat_kelengkapan' => $request->id_jabatan_alat_kelengkapan,
            'id_alat_kelengkapan' => $suratKeputusan->id_alat_kelengkapan,
        ]);

        return response()->json(['success' => 'Anggota berhasil ditambahkan.']);
    }

    public function destroyAnggota($id)
    {
        $jabatanAnggota = JabatanAnggota::findOrFail($id);
        $jabatanAnggota->delete();
        return response()->json(['success' => 'Anggota berhasil dihapus.']);
    }
}
