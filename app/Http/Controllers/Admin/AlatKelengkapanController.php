<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatKelengkapan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AlatKelengkapanController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view alat_kelengkapan|create alat_kelengkapan|edit alat_kelengkapan|delete alat_kelengkapan', only: ['index', 'show']),
            new Middleware('permission:create alat_kelengkapan', only: ['create', 'store']),
            new Middleware('permission:edit alat_kelengkapan', only: ['edit', 'update']),
            new Middleware('permission:delete alat_kelengkapan', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AlatKelengkapan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group shadow-sm">';
                    if(auth()->user()->can('edit alat_kelengkapan')){
                        $btn .= '<button type="button" class="btn btn-sm btn-light border-end btn-edit" data-id="'.$row->id.'" title="Edit"><i class="bi bi-pencil-square text-warning"></i></button>';
                    }
                    if(auth()->user()->can('delete alat_kelengkapan')){
                        $btn .= '<button type="button" onclick="deleteItem('.$row->id.')" class="btn btn-sm btn-light" title="Hapus"><i class="bi bi-trash3-fill text-danger"></i></button>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.alat_kelengkapan.index');
    }

    public function create()
    {
        return view('admin.alat_kelengkapan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ket' => 'nullable|string',
            'nama_komisi' => 'nullable|string|max:255',
        ]);

        // Only save nama_komisi if nama is 'Komisi' (case-insensitive)
        $data = $request->only(['nama', 'ket']);
        if (strtolower($request->nama) === 'komisi') {
            $data['nama_komisi'] = $request->nama_komisi;
        }

        AlatKelengkapan::create($data);

        return response()->json(['success' => 'Alat Kelengkapan berhasil ditambahkan.']);
    }

    public function show(AlatKelengkapan $alatKelengkapan)
    {
        return view('admin.alat_kelengkapan.show', compact('alatKelengkapan'));
    }

    public function edit(AlatKelengkapan $alatKelengkapan)
    {
        return response()->json($alatKelengkapan);
    }

    public function update(Request $request, AlatKelengkapan $alatKelengkapan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ket' => 'nullable|string',
            'nama_komisi' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['nama', 'ket']);
        if (strtolower($request->nama) === 'komisi') {
            $data['nama_komisi'] = $request->nama_komisi;
        } else {
            $data['nama_komisi'] = null;
        }

        $alatKelengkapan->update($data);

        return response()->json(['success' => 'Alat Kelengkapan berhasil diperbarui.']);
    }

    public function destroy(AlatKelengkapan $alatKelengkapan)
    {
        $alatKelengkapan->delete();

        return response()->json(['success'=>'Alat Kelengkapan berhasil dihapus.']);
    }
}
