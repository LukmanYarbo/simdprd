<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifPajak;
use App\Models\TarifLapisPajak;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TarifPajakController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view tarif_pajak|create tarif_pajak|edit tarif_pajak|delete tarif_pajak', only: ['index']),
            new Middleware('permission:create tarif_pajak', only: ['create', 'store']),
            new Middleware('permission:edit tarif_pajak', only: ['edit', 'update']),
            new Middleware('permission:delete tarif_pajak', only: ['destroy']),
        ];
    }

    public function index()
    {
        $tarifPajak = TarifPajak::with('lapisPajak')->latest()->get();
        $hasActive  = TarifPajak::active()->exists();
        return view('admin.tarif_pajak.index', compact('tarifPajak', 'hasActive'));
    }

    public function create()
    {
        if (TarifPajak::active()->exists()) {
            return redirect()->route('admin.tarif-pajak.index')
                ->with('error', 'Tidak dapat menambahkan tarif baru selagi ada tarif yang masih aktif (Status = Y). Nonaktifkan terlebih dahulu.');
        }
        return view('admin.tarif_pajak.create');
    }

    public function store(Request $request)
    {
        if (TarifPajak::active()->exists()) {
            return redirect()->route('admin.tarif-pajak.index')
                ->with('error', 'Tidak dapat menambahkan tarif baru selagi ada tarif yang masih aktif.');
        }

        $validated = $request->validate([
            'no_peraturan'              => 'required|string|max:255',
            'tgl_berlaku'               => 'required|date',
            'ptkp'                      => 'required|numeric|min:0',
            'tambahan_ptkp_istri'       => 'required|numeric|min:0',
            'tambahan_ptkp_tanggungan'  => 'required|numeric|min:0',
            'persen_biaya_jabatan'      => 'required|numeric|min:0|max:100',
            'max_biaya_jabatan'         => 'required|numeric|min:0',
            'status'                    => 'required|in:Y,T',
            'lapis'                     => 'nullable|array',
            'lapis.*.dari'              => 'required_with:lapis|numeric|min:0',
            'lapis.*.sampai'            => 'nullable|numeric|min:0',
            'lapis.*.persen'            => 'required_with:lapis|numeric|min:0|max:100',
        ]);

        $tarif = TarifPajak::create([
            'no_peraturan'              => $validated['no_peraturan'],
            'tgl_berlaku'               => $validated['tgl_berlaku'],
            'ptkp'                      => $validated['ptkp'],
            'tambahan_ptkp_istri'       => $validated['tambahan_ptkp_istri'],
            'tambahan_ptkp_tanggungan'  => $validated['tambahan_ptkp_tanggungan'],
            'persen_biaya_jabatan'      => $validated['persen_biaya_jabatan'],
            'max_biaya_jabatan'         => $validated['max_biaya_jabatan'],
            'status'                    => $validated['status'],
        ]);

        if (!empty($validated['lapis'])) {
            foreach ($validated['lapis'] as $lapis) {
                TarifLapisPajak::create([
                    'id_tarif_pajak' => $tarif->id,
                    'dari'           => $lapis['dari'],
                    'sampai'         => $lapis['sampai'] ?? null,
                    'persen'         => $lapis['persen'],
                ]);
            }
        }

        return redirect()->route('admin.tarif-pajak.index')
            ->with('success', 'Tarif Pajak berhasil ditambahkan.');
    }

    public function edit(TarifPajak $tarifPajak)
    {
        $tarifPajak->load('lapisPajak');
        return view('admin.tarif_pajak.edit', compact('tarifPajak'));
    }

    public function update(Request $request, TarifPajak $tarifPajak)
    {
        $validated = $request->validate([
            'no_peraturan'              => 'required|string|max:255',
            'tgl_berlaku'               => 'required|date',
            'ptkp'                      => 'required|numeric|min:0',
            'tambahan_ptkp_istri'       => 'required|numeric|min:0',
            'tambahan_ptkp_tanggungan'  => 'required|numeric|min:0',
            'persen_biaya_jabatan'      => 'required|numeric|min:0|max:100',
            'max_biaya_jabatan'         => 'required|numeric|min:0',
            'status'                    => 'required|in:Y,T',
            'lapis'                     => 'nullable|array',
            'lapis.*.dari'              => 'required_with:lapis|numeric|min:0',
            'lapis.*.sampai'            => 'nullable|numeric|min:0',
            'lapis.*.persen'            => 'required_with:lapis|numeric|min:0|max:100',
        ]);

        $tarifPajak->update([
            'no_peraturan'              => $validated['no_peraturan'],
            'tgl_berlaku'               => $validated['tgl_berlaku'],
            'ptkp'                      => $validated['ptkp'],
            'tambahan_ptkp_istri'       => $validated['tambahan_ptkp_istri'],
            'tambahan_ptkp_tanggungan'  => $validated['tambahan_ptkp_tanggungan'],
            'persen_biaya_jabatan'      => $validated['persen_biaya_jabatan'],
            'max_biaya_jabatan'         => $validated['max_biaya_jabatan'],
            'status'                    => $validated['status'],
        ]);

        // Sync lapis pajak: delete all then re-insert
        $tarifPajak->lapisPajak()->delete();
        if (!empty($validated['lapis'])) {
            foreach ($validated['lapis'] as $lapis) {
                TarifLapisPajak::create([
                    'id_tarif_pajak' => $tarifPajak->id,
                    'dari'           => $lapis['dari'],
                    'sampai'         => $lapis['sampai'] ?? null,
                    'persen'         => $lapis['persen'],
                ]);
            }
        }

        return redirect()->route('admin.tarif-pajak.index')
            ->with('success', 'Tarif Pajak berhasil diperbarui.');
    }

    public function destroy(TarifPajak $tarifPajak)
    {
        if ($tarifPajak->status === 'Y') {
            return redirect()->route('admin.tarif-pajak.index')
                ->with('error', 'Tidak dapat menghapus tarif yang masih aktif. Nonaktifkan terlebih dahulu.');
        }

        $tarifPajak->delete(); // Cascade deletes lapisPajak
        return redirect()->route('admin.tarif-pajak.index')
            ->with('success', 'Tarif Pajak berhasil dihapus.');
    }
}
