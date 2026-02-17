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

class SuratKeputusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratKeputusan::with('alatKelengkapan')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('file_download', function($row){
                    if($row->file_sk){
                        return '<a href="'.asset('storage/'.$row->file_sk).'" target="_blank" class="btn btn-sm btn-info text-white"><i class="bi bi-download"></i> Unduh</a>';
                    }
                    return '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group shadow-sm">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info text-white border-end btn-members" data-id="'.$row->id.'" title="Kelola Anggota"><i class="bi bi-people-fill"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-light border-end btn-edit" data-id="'.$row->id.'" title="Edit"><i class="bi bi-pencil-square text-warning"></i></button>';
                    $btn .= '<button type="button" onclick="deleteItem('.$row->id.')" class="btn btn-sm btn-light" title="Hapus"><i class="bi bi-trash3-fill text-danger"></i></button>';
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
        if ($suratKeputusan->file_sk) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($suratKeputusan->file_sk);
        }
        $suratKeputusan->delete();

        return response()->json(['success'=>'Surat Keputusan berhasil dihapus.']);
    }
}
