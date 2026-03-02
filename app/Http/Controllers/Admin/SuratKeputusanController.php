<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKeputusan;
use App\Models\AlatKelengkapan;
use App\Models\Anggota;
use App\Models\JabatanAnggota;
use App\Models\JabatanAlatKelengkapan;
use App\Models\Pemda;
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
                        $url = asset('storage/'.$row->file_sk);
                        $noSk = htmlspecialchars($row->no_sk, ENT_QUOTES);
                        return '<button type="button" onclick="previewPdf(\''.addslashes($url).'\', \''.addslashes($row->no_sk).'\')" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Lihat Dokumen</button>';
                    }
                    return '<span class="text-muted small">-</span>';
                })
                ->addColumn('action', function($row){
                    $user = auth()->user();
                    \Illuminate\Support\Facades\Log::info('User: ' . $user->email . ' | Role: ' . $user->getRoleNames() . ' | Can Edit SK: ' . ($user->can('edit surat_keputusan') ? 'Yes' : 'No') . ' | Can Delete SK: ' . ($user->can('delete surat_keputusan') ? 'Yes' : 'No'));
                    
                    $btn = '<div class="btn-group shadow-sm">';
                    if($user->can('view surat_keputusan')){
                        $btn .= '<a href="'.route('admin.surat-keputusan.print', $row->id).'" target="_blank" class="btn btn-sm btn-dark text-white border-end" title="Cetak"><i class="bi bi-printer"></i></a>';
                    }
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
        
        $isKomisi = strtolower($suratKeputusan->alatKelengkapan->nama ?? '') === 'komisi';

        $existingMemberIds = $anggota->pluck('id_anggota')->toArray();
        $allAnggota = Anggota::select('id', 'nama_anggota', 'nik')
            ->where('id_status_keanggotaan', 1)
            ->whereNotIn('id', $existingMemberIds)
            ->when($isKomisi, function ($q) {
                // Exclude Ketua DPRD and Wakil Ketua DPRD for Komisi-type SK
                $q->whereDoesntHave('jabatan', function ($jq) {
                    $jq->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(nama)'), ['ketua dprd', 'wakil ketua dprd']);
                });
            })
            ->orderBy('nama_anggota')
            ->get();
        $jabatanAlatKelengkapan = JabatanAlatKelengkapan::all();

        // For Komisi: collect all distinct nama_komisi already used in this SK
        $namaKomisiList = [];
        if ($isKomisi) {
            $namaKomisiList = JabatanAnggota::where('id_surat_keputusan', $id)
                ->whereNotNull('nama_komisi')
                ->distinct()
                ->pluck('nama_komisi')
                ->toArray();
        }

        return response()->json([
            'surat_keputusan' => $suratKeputusan,
            'existing_anggota' => $anggota,
            'all_anggota' => $allAnggota,
            'jabatan_options' => $jabatanAlatKelengkapan,
            'is_komisi' => $isKomisi,
            'nama_komisi_list' => $namaKomisiList,
        ]);
    }

    public function storeAnggota(Request $request)
    {
        $suratKeputusan = SuratKeputusan::with('alatKelengkapan')->findOrFail($request->id_surat_keputusan);
        $isKomisi = strtolower($suratKeputusan->alatKelengkapan->nama ?? '') === 'komisi';

        $request->validate([
            'id_surat_keputusan' => 'required|exists:surat_keputusan,id',
            'id_anggota' => 'required|exists:anggota,id',
            'id_jabatan_alat_kelengkapan' => 'required|exists:jabatan_alat_kelengkapan,id',
            'nama_komisi' => $isKomisi ? 'required|string|max:100' : 'nullable|string|max:100',
        ]);

        // Check if member already exists in this SK
        $query = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
            ->where('id_anggota', $request->id_anggota);
        if ($isKomisi) {
            $query->where('nama_komisi', $request->nama_komisi);
        }
        if ($query->exists()) {
            return response()->json(['errors' => ['id_anggota' => ['Anggota ini sudah terdaftar' . ($isKomisi ? ' di Komisi ini.' : ' dalam SK ini.')]]], 422);
        }

        // Validate Position Limits (Ketua, Wakil, Sekretaris)
        $jabatan = JabatanAlatKelengkapan::findOrFail($request->id_jabatan_alat_kelengkapan);
        if (in_array($jabatan->nama, ['Ketua', 'Wakil', 'Sekretaris'])) {
            $namaAlatKelengkapan = strtolower($suratKeputusan->alatKelengkapan->nama ?? '');
            $isBanggarBanmus = in_array($namaAlatKelengkapan, ['banggar', 'banmus']);

            // For Wakil in Banggar/Banmus: quota = number of "Wakil Ketua DPRD" members
            if ($jabatan->nama === 'Wakil' && $isBanggarBanmus) {
                // Count total anggota whose jabatan DPRD name contains "Wakil Ketua DPRD" (case-insensitive)
                $wakilKuota = \App\Models\Anggota::where('id_status_keanggotaan', 1)
                    ->whereHas('jabatan', function ($q) {
                        $q->whereRaw('LOWER(nama) LIKE ?', ['%wakil ketua dprd%']);
                    })
                    ->count();

                // Count how many Wakil already exist in this SK
                $wakilTerisi = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
                    ->where('id_jabatan_alat_kelengkapan', $request->id_jabatan_alat_kelengkapan)
                    ->count();

                if ($wakilTerisi >= $wakilKuota) {
                    return response()->json(['errors' => ['id_jabatan_alat_kelengkapan' => [
                        "Jabatan Wakil sudah penuh ($wakilTerisi/$wakilKuota). Kuota disesuaikan dengan jumlah Wakil Ketua DPRD."
                    ]]], 422);
                }
            } else {
                // Default: max 1 for Ketua & Sekretaris, and Wakil on non-Banggar/Banmus
                $posQuery = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
                    ->where('id_jabatan_alat_kelengkapan', $request->id_jabatan_alat_kelengkapan);
                if ($isKomisi) {
                    $posQuery->where('nama_komisi', $request->nama_komisi);
                }
                if ($posQuery->exists()) {
                    $scope = $isKomisi ? "di Komisi '{$request->nama_komisi}'" : 'dalam SK ini';
                    return response()->json(['errors' => ['id_jabatan_alat_kelengkapan' => ["Jabatan $jabatan->nama sudah terisi $scope."]]], 422);
                }
            }
        }

        JabatanAnggota::create([
            'id_surat_keputusan' => $request->id_surat_keputusan,
            'id_anggota' => $request->id_anggota,
            'id_jabatan_alat_kelengkapan' => $request->id_jabatan_alat_kelengkapan,
            'id_alat_kelengkapan' => $suratKeputusan->id_alat_kelengkapan,
            'nama_komisi' => $isKomisi ? $request->nama_komisi : null,
        ]);

        return response()->json(['success' => 'Anggota berhasil ditambahkan.']);
    }

    public function destroyAnggota($id)
    {
        $jabatanAnggota = JabatanAnggota::findOrFail($id);
        $jabatanAnggota->delete();
        return response()->json(['success' => 'Anggota berhasil dihapus.']);
    }

    public function print($id)
    {
        $suratKeputusan = SuratKeputusan::with(['alatKelengkapan', 'jabatanAnggota.anggota', 'jabatanAnggota.jabatanAlatKelengkapan'])
            ->findOrFail($id);
        
        $pemda = Pemda::first();
        
        $isKomisi = strtolower($suratKeputusan->alatKelengkapan->nama ?? '') === 'komisi';

        // Custom sort for members based on position name
        // For Komisi: sort by nama_komisi first, then Ketua, Wakil, Sekretaris, then Anggota
        // For others: Ketua, Wakil, Sekretaris, then Anggota
        $sortedAnggota = $suratKeputusan->jabatanAnggota->sort(function($a, $b) use ($isKomisi) {
            if ($isKomisi) {
                $komisiA = $a->nama_komisi ?? '';
                $komisiB = $b->nama_komisi ?? '';
                if ($komisiA !== $komisiB) {
                    return strcasecmp($komisiA, $komisiB);
                }
            }

            $order = ['Ketua' => 1, 'Wakil' => 2, 'Sekretaris' => 3, 'Anggota' => 4];
            
            $nameA = $a->jabatanAlatKelengkapan->nama;
            $nameB = $b->jabatanAlatKelengkapan->nama;
            
            $valA = $order[$nameA] ?? 99;
            $valB = $order[$nameB] ?? 99;
            
            if ($valA == $valB) {
                return strcmp($a->anggota->nama_anggota, $b->anggota->nama_anggota);
            }
            
            return $valA - $valB;
        });

        // Fetch Ketua DPRD for signature
        $ketuaDprd = \App\Models\Anggota::whereHas('jabatan', function($q) {
            $q->whereIn('nama', ['Ketua DPRD', 'KETUA DPRD']);
        })->whereHas('statusKeanggotaan', function($q) {
            $q->where('id', 1); // Aktif
        })->first();

        return view('admin.surat_keputusan.print', compact('suratKeputusan', 'pemda', 'sortedAnggota', 'ketuaDprd'));
    }
}
