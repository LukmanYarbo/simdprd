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
            new Middleware('permission:view surat_keputusan|create surat_keputusan|edit surat_keputusan|delete surat_keputusan', only: ['index', 'show', 'strukturAll']),
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
                        return '<button type="button" onclick="previewPdf(\''.addslashes($url).'\', \''.addslashes($row->no_sk).'\')" class="btn btn-sm btn-premium text-danger border-danger-subtle"><i class="ti ti-file-type-pdf me-1"></i>Lihat Dokumen</button>';
                    }
                    return '<span class="text-muted small">-</span>';
                })
                ->addColumn('action', function($row){
                    $user = auth()->user();
                    $btn = '<div class="d-flex gap-2 justify-content-end">';
                    if($user->can('view surat_keputusan')){
                        $btn .= '<a href="'.route('admin.surat-keputusan.print', $row->id).'" target="_blank" class="btn-icon-modern" title="Cetak"><i class="ti ti-printer"></i></a>';
                        $btn .= '<button type="button" class="btn-icon-modern text-success btn-struktur" data-id="'.$row->id.'" title="Struktur Organisasi"><i class="ti ti-sitemap"></i></button>';
                    }
                    if($user->can('edit surat_keputusan')){
                        $btn .= '<button type="button" class="btn-icon-modern text-info btn-members" data-id="'.$row->id.'" title="Kelola Anggota"><i class="ti ti-users"></i></button>';
                        $btn .= '<button type="button" class="btn-icon-modern text-primary btn-edit" data-id="'.$row->id.'" title="Edit"><i class="ti ti-edit"></i></button>';
                    }
                    if($user->can('delete surat_keputusan')){
                        $btn .= '<button type="button" onclick="deleteItem('.$row->id.')" class="btn-icon-modern text-danger" title="Hapus"><i class="ti ti-trash"></i></button>';
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

    /**
     * Struktur organisasi SK aktif untuk setiap Alat Kelengkapan DPRD.
     */
    public function strukturAll()
    {
        $alatKelengkapans = AlatKelengkapan::orderBy('id')->get();

        $data = $alatKelengkapans->map(function ($ak) {
            $isKomisi = strtolower($ak->nama) === 'komisi';

            $sk = SuratKeputusan::with(['jabatanAnggota.anggota', 'jabatanAnggota.jabatanAlatKelengkapan'])
                ->where('id_alat_kelengkapan', $ak->id)
                ->where('status', 'A')
                ->first();

            return [
                'id' => $ak->id,
                'nama' => $ak->nama,
                'ket' => $ak->ket,
                'is_komisi' => $isKomisi,
                'surat_keputusan' => $sk ? [
                    'id' => $sk->id,
                    'no_sk' => $sk->no_sk,
                    'tgl_sk' => \Carbon\Carbon::parse($sk->tgl_sk)->locale('id')->translatedFormat('d F Y'),
                    'jumlah_anggota' => $sk->jabatanAnggota->count(),
                    'anggota' => $this->sortAnggotaByKomisiDanJabatan($sk->jabatanAnggota, $isKomisi)
                        ->map(function ($ja) {
                            return [
                                'id' => $ja->id,
                                'nama_anggota' => $ja->anggota->nama_anggota ?? '-',
                                'jabatan' => $ja->jabatanAlatKelengkapan->nama ?? '-',
                                'nama_komisi' => $ja->nama_komisi,
                            ];
                        })->values(),
                ] : null,
            ];
        })->values();

        return response()->json($data);
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
            $file = $request->file('file_sk');
            $extension = $file->getClientOriginalExtension();
            $filename = 'surat_keputusan_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $input['file_sk'] = $file->storeAs('files_sk', $filename, 'public');
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
            $file = $request->file('file_sk');
            $extension = $file->getClientOriginalExtension();
            $filename = 'surat_keputusan_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $input['file_sk'] = $file->storeAs('files_sk', $filename, 'public');
        }

        if ($suratKeputusan->status == 'A' && $request->status == 'T') {
            $namaAlatKelengkapan = strtolower($suratKeputusan->alatKelengkapan->nama ?? '');
            $anggotaField = '';
            
            switch ($namaAlatKelengkapan) {
                case 'komisi': $anggotaField = 'id_komisi'; break;
                case 'banggar': $anggotaField = 'id_banggar'; break;
                case 'banmus': $anggotaField = 'id_banmus'; break;
                case 'bk': $anggotaField = 'id_bk'; break;
                case 'balegda': $anggotaField = 'id_balegda'; break;
                case 'pansus': $anggotaField = 'id_pansus'; break;
                case 'panja': $anggotaField = 'id_panja'; break;
            }

            if ($anggotaField) {
                $jabatanAnggotaIds = JabatanAnggota::where('id_surat_keputusan', $suratKeputusan->id)
                    ->pluck('id_anggota')
                    ->toArray();

                if (!empty($jabatanAnggotaIds)) {
                    $updateData = [$anggotaField => null];
                    if ($namaAlatKelengkapan === 'komisi') {
                        $updateData['nama_komisi'] = null;
                    }
                    \App\Models\Anggota::whereIn('id', $jabatanAnggotaIds)->update($updateData);
                }
            }
        }

        $suratKeputusan->update($input);

        return response()->json(['success' => 'Surat Keputusan berhasil diperbarui.']);
    }

    public function destroy(SuratKeputusan $suratKeputusan)
    {
        $memberCount = JabatanAnggota::where('id_surat_keputusan', $suratKeputusan->id)->count();
        if ($memberCount > 0) {
            return response()->json(['error' => 'Gagal! SK yang memiliki anggota tidak dapat dihapus.'], 422);
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
        $isPimpinan = strtolower($suratKeputusan->alatKelengkapan->nama ?? '') === 'pimpinan dprd';
        $anggota = $this->sortAnggotaByKomisiDanJabatan($anggota, $isKomisi);

        $existingMemberIds = $anggota->pluck('id_anggota')->toArray();
        $allAnggota = Anggota::select('id', 'nama_anggota', 'nik')
            ->where('id_status_keanggotaan', 1)
            ->when($isPimpinan, function ($q) {
                // SK Pimpinan DPRD hanya boleh menganggotakan Ketua/Wakil DPRD (id_dprd 1 atau 2)
                $q->whereIn('id_dprd', [1, 2]);
            })
            ->whereNotIn('id', $existingMemberIds)
            ->when($isKomisi, function ($q) {
                // Exclude Ketua DPRD and Wakil Ketua DPRD for Komisi-type SK
                $q->whereDoesntHave('jabatan', function ($jq) {
                    $jq->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(nama)'), ['ketua dprd', 'wakil ketua dprd']);
                });
            })
            ->orderBy('nama_anggota')
            ->get();
        $jabatanAlatKelengkapan = JabatanAlatKelengkapan::orderBy('id')
            ->get()
            ->unique('nama')
            ->values();

        // For Komisi: collect all distinct nama_komisi used in any Komisi SK
        $namaKomisiList = [];
        if ($isKomisi) {
            $namaKomisiList = JabatanAnggota::whereHas('suratKeputusan.alatKelengkapan', function($q) {
                    $q->whereRaw('LOWER(nama) = ?', ['komisi']);
                })
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
        if ($query->exists()) {
            return response()->json(['errors' => ['id_anggota' => ['Anggota ini sudah terdaftar dalam SK ini.']]], 422);
        }

        // Validate Position Limits (Ketua, Wakil, Sekretaris)
        // Jika batas terlampaui, kirim konfirmasi ke client (bukan tolak langsung).
        // Client akan menampilkan dialog konfirmasi; jika user setuju, request
        // dikirim ulang dengan parameter force=1.
        $jabatan = JabatanAlatKelengkapan::findOrFail($request->id_jabatan_alat_kelengkapan);
        $limitMessage = null;

        if (in_array($jabatan->nama, ['Ketua', 'Wakil', 'Sekretaris'])) {
            $namaAlatKelengkapan = strtolower($suratKeputusan->alatKelengkapan->nama ?? '');
            $isBanggarBanmus = in_array($namaAlatKelengkapan, ['banggar', 'banmus']);

            // For Wakil in Banggar/Banmus: quota = number of "Wakil Ketua DPRD" members
            if ($jabatan->nama === 'Wakil' && $isBanggarBanmus) {
                $wakilKuota = \App\Models\Anggota::where('id_status_keanggotaan', 1)
                    ->whereHas('jabatan', function ($q) {
                        $q->whereRaw('LOWER(nama) LIKE ?', ['%wakil ketua dprd%']);
                    })
                    ->count();

                $wakilTerisi = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
                    ->where('id_jabatan_alat_kelengkapan', $request->id_jabatan_alat_kelengkapan)
                    ->count();

                if ($wakilTerisi >= $wakilKuota) {
                    $limitMessage = "Jumlah Wakil sudah mencapai batas standar ($wakilTerisi/$wakilKuota). Batas disesuaikan dengan jumlah Wakil Ketua DPRD.";
                }
            } else {
                $posQuery = JabatanAnggota::where('id_surat_keputusan', $request->id_surat_keputusan)
                    ->where('id_jabatan_alat_kelengkapan', $request->id_jabatan_alat_kelengkapan);
                if ($isKomisi) {
                    $posQuery->where('nama_komisi', $request->nama_komisi);
                }

                if ($posQuery->exists()) {
                    $pemegang = $posQuery->with('anggota')->first()?->anggota;
                    $scope = $isKomisi ? "di Komisi '{$request->nama_komisi}'" : 'dalam SK ini';
                    $limitMessage = "Jabatan $jabatan->nama sudah terisi oleh " . ($pemegang->nama_anggota ?? 'anggota lain') . " $scope.";
                }
            }
        }

        if ($limitMessage && ! $request->boolean('force')) {
            return response()->json([
                'requires_confirmation' => true,
                'message' => $limitMessage . ' Tetap tambahkan anggota ini dengan jabatan yang sama?',
            ], 422);
        }

        JabatanAnggota::create([
            'id_surat_keputusan' => $request->id_surat_keputusan,
            'id_anggota' => $request->id_anggota,
            'id_jabatan_alat_kelengkapan' => $request->id_jabatan_alat_kelengkapan,
            'id_alat_kelengkapan' => $suratKeputusan->id_alat_kelengkapan,
            'nama_komisi' => $isKomisi ? $request->nama_komisi : null,
        ]);

        // Sinkronisasi khusus SK Pimpinan DPRD:
        // jabatan Ketua -> anggota.id_dprd = 1, jabatan Wakil -> anggota.id_dprd = 2
        if (strtolower($suratKeputusan->alatKelengkapan->nama ?? '') === 'pimpinan dprd') {
            $dprdValue = match ($jabatan->nama) {
                'Ketua' => 1,
                'Wakil' => 2,
                default => null,
            };
            if ($dprdValue) {
                \App\Models\Anggota::where('id', $request->id_anggota)->update(['id_dprd' => $dprdValue]);
            }
        }

        $namaAlatKelengkapan = strtolower($suratKeputusan->alatKelengkapan->nama ?? '');
        $anggotaField = '';
        
        switch ($namaAlatKelengkapan) {
            case 'komisi': $anggotaField = 'id_komisi'; break;
            case 'banggar': $anggotaField = 'id_banggar'; break;
            case 'banmus': $anggotaField = 'id_banmus'; break;
            case 'bk': $anggotaField = 'id_bk'; break;
            case 'balegda': $anggotaField = 'id_balegda'; break;
            case 'pansus': $anggotaField = 'id_pansus'; break;
            case 'panja': $anggotaField = 'id_panja'; break;
        }

        if ($suratKeputusan->status === 'A' && $anggotaField) {
            $updateData = [$anggotaField => $request->id_jabatan_alat_kelengkapan];
            if ($namaAlatKelengkapan === 'komisi') {
                $updateData['nama_komisi'] = $request->nama_komisi;
            }
            \App\Models\Anggota::where('id', $request->id_anggota)->update($updateData);
        }

        return response()->json(['success' => 'Anggota berhasil ditambahkan.']);
    }

    public function destroyAnggota($id)
    {
        $jabatanAnggota = JabatanAnggota::with(['suratKeputusan.alatKelengkapan', 'jabatanAlatKelengkapan'])->findOrFail($id);

        $namaAlatKelengkapan = strtolower($jabatanAnggota->suratKeputusan->alatKelengkapan->nama ?? '');

        // Kembalikan id_dprd ke 3 (Anggota DPRD) bila anggota dihapus dari SK Pimpinan DPRD
        if ($namaAlatKelengkapan === 'pimpinan dprd') {
            $dprdValue = match (strtolower($jabatanAnggota->jabatanAlatKelengkapan->nama ?? '')) {
                'ketua' => 1,
                'wakil' => 2,
                default => null,
            };
            if ($dprdValue) {
                \App\Models\Anggota::where('id', $jabatanAnggota->id_anggota)
                    ->where('id_dprd', $dprdValue)
                    ->update(['id_dprd' => 3]);
            }
        }
        $anggotaField = '';
        
        switch ($namaAlatKelengkapan) {
            case 'komisi': $anggotaField = 'id_komisi'; break;
            case 'banggar': $anggotaField = 'id_banggar'; break;
            case 'banmus': $anggotaField = 'id_banmus'; break;
            case 'bk': $anggotaField = 'id_bk'; break;
            case 'balegda': $anggotaField = 'id_balegda'; break;
            case 'pansus': $anggotaField = 'id_pansus'; break;
            case 'panja': $anggotaField = 'id_panja'; break;
        }

        if ($anggotaField) {
            $updateData = [$anggotaField => null];
            if ($namaAlatKelengkapan === 'komisi') {
                $updateData['nama_komisi'] = null;
            }
            // Nullify the field only if it currently points to the same jabatan.
            \App\Models\Anggota::where('id', $jabatanAnggota->id_anggota)
                ->where($anggotaField, $jabatanAnggota->id_jabatan_alat_kelengkapan)
                ->update($updateData);
        }

        $jabatanAnggota->delete();
        
        return response()->json(['success' => 'Anggota berhasil dihapus.']);
    }

    /**
     * Sort SK members by nama_komisi (Komisi type only), then by position order:
     * Ketua, Wakil, Sekretaris, Anggota. Members with the same position are sorted by name.
     */
    private function sortAnggotaByKomisiDanJabatan($anggota, bool $isKomisi)
    {
        return $anggota->sort(function($a, $b) use ($isKomisi) {
            if ($isKomisi) {
                $komisiA = $a->nama_komisi ?? '';
                $komisiB = $b->nama_komisi ?? '';
                if ($komisiA !== $komisiB) {
                    return strcasecmp($komisiA, $komisiB);
                }
            }

            $order = ['Ketua' => 1, 'Wakil' => 2, 'Sekretaris' => 3, 'Anggota' => 4];

            $nameA = $a->jabatanAlatKelengkapan->nama ?? '';
            $nameB = $b->jabatanAlatKelengkapan->nama ?? '';

            $valA = $order[$nameA] ?? 99;
            $valB = $order[$nameB] ?? 99;

            if ($valA == $valB) {
                return strcmp($a->anggota->nama_anggota ?? '', $b->anggota->nama_anggota ?? '');
            }

            return $valA - $valB;
        })->values();
    }

    public function print($id)
    {
        $suratKeputusan = SuratKeputusan::with(['alatKelengkapan', 'jabatanAnggota.anggota', 'jabatanAnggota.jabatanAlatKelengkapan'])
            ->findOrFail($id);
        
        $pemda = Pemda::first();
        
        $isKomisi = strtolower($suratKeputusan->alatKelengkapan->nama ?? '') === 'komisi';

        // Custom sort for members: by nama_komisi (Komisi only), then Ketua, Wakil, Sekretaris, Anggota
        $sortedAnggota = $this->sortAnggotaByKomisiDanJabatan($suratKeputusan->jabatanAnggota, $isKomisi);

        // Fetch Ketua DPRD for signature
        $ketuaDprd = \App\Models\Anggota::whereHas('jabatan', function($q) {
            $q->whereIn('nama', ['Ketua DPRD', 'KETUA DPRD']);
        })->whereHas('statusKeanggotaan', function($q) {
            $q->where('id', 1); // Aktif
        })->first();

        return view('admin.surat_keputusan.print', compact('suratKeputusan', 'pemda', 'sortedAnggota', 'ketuaDprd'));
    }
}
