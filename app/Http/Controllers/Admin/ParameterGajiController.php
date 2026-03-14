<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParameterGaji;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ParameterGajiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view parameter_gaji|create parameter_gaji|edit parameter_gaji|delete parameter_gaji', only: ['index', 'show']),
            new Middleware('permission:create parameter_gaji', only: ['create', 'store']),
            new Middleware('permission:edit parameter_gaji', only: ['edit', 'update']),
            new Middleware('permission:delete parameter_gaji', only: ['destroy']),
        ];
    }

    public function index()
    {
        $parameterGaji = ParameterGaji::latest()->get();
        $hasActive = ParameterGaji::active()->exists();
        return view('admin.parameter_gaji.index', compact('parameterGaji', 'hasActive'));
    }

    public function create()
    {
        // Block creation if an active parameter exists
        if (ParameterGaji::active()->exists()) {
            return redirect()->route('admin.parameter-gaji.index')
                ->with('error', 'Tidak dapat menambahkan peraturan baru selagi ada peraturan yang masih aktif (Status = Y). Nonaktifkan peraturan yang aktif terlebih dahulu.');
        }

        return view('admin.parameter_gaji.create');
    }

    public function store(Request $request)
    {
        // Double-check: prevent store if active exists
        if (ParameterGaji::active()->exists()) {
            return redirect()->route('admin.parameter-gaji.index')
                ->with('error', 'Tidak dapat menambahkan peraturan baru selagi ada peraturan yang masih aktif.');
        }

        $validated = $request->validate([
            'no_peraturan'          => 'required|string|max:255',
            'tgl_berlaku'           => 'required|date',
            'gajipokok_ketua'       => 'required|numeric|min:0',
            'persen_gapokwakil'     => 'required|numeric|min:0|max:100',
            'persen_gapokanggota'   => 'required|numeric|min:0|max:100',
            'persen_tunjabketua'    => 'required|numeric|min:0',
            'persen_tunjabwakil'    => 'required|numeric|min:0',
            'persen_tunjabanggota'  => 'required|numeric|min:0',
            'persen_tunketua_aleg'  => 'required|numeric|min:0|max:100',
            'persen_tunwakil_aleg'  => 'required|numeric|min:0|max:100',
            'persen_tunsek_aleg'    => 'required|numeric|min:0|max:100',
            'persen_tunanggota_aleg'=> 'required|numeric|min:0|max:100',
            'persen_uangpaket'     => 'required|numeric|min:0|max:100',
            'status'                => 'required|in:Y,T',
            'file'                  => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($validated['no_peraturan']);
            $filename = $noPeraturanSlug . '_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $validated['file'] = $file->storeAs('parameter-gaji', $filename, 'public');
        }

        ParameterGaji::create($validated);

        return redirect()->route('admin.parameter-gaji.index')
            ->with('success', 'Parameter Gaji berhasil ditambahkan.');
    }

    public function edit(ParameterGaji $parameterGaji)
    {
        return view('admin.parameter_gaji.edit', compact('parameterGaji'));
    }

    public function update(Request $request, ParameterGaji $parameterGaji)
    {
        $validated = $request->validate([
            'no_peraturan'          => 'required|string|max:255',
            'tgl_berlaku'           => 'required|date',
            'gajipokok_ketua'       => 'required|numeric|min:0',
            'persen_gapokwakil'     => 'required|numeric|min:0|max:100',
            'persen_gapokanggota'   => 'required|numeric|min:0|max:100',
            'persen_tunjabketua'    => 'required|numeric|min:0',
            'persen_tunjabwakil'    => 'required|numeric|min:0',
            'persen_tunjabanggota'  => 'required|numeric|min:0',
            'persen_tunketua_aleg'  => 'required|numeric|min:0|max:100',
            'persen_tunwakil_aleg'  => 'required|numeric|min:0|max:100',
            'persen_tunsek_aleg'    => 'required|numeric|min:0|max:100',
            'persen_tunanggota_aleg'=> 'required|numeric|min:0|max:100',
            'persen_uangpaket'     => 'required|numeric|min:0|max:100',
            'status'                => 'required|in:Y,T',
            'file'                  => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file')) {
            if ($parameterGaji->file) {
                Storage::disk('public')->delete($parameterGaji->file);
            }
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $noPeraturanSlug = Str::slug($validated['no_peraturan'] ?? $parameterGaji->no_peraturan);
            $filename = $noPeraturanSlug . '_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
            $validated['file'] = $file->storeAs('parameter-gaji', $filename, 'public');
        }

        $parameterGaji->update($validated);

        return redirect()->route('admin.parameter-gaji.index')
            ->with('success', 'Parameter Gaji berhasil diperbarui.');
    }

    public function destroy(ParameterGaji $parameterGaji)
    {
        if ($parameterGaji->status == 'Y') {
            return redirect()->route('admin.parameter-gaji.index')
                ->with('error', 'Tidak dapat menghapus peraturan yang masih aktif. Nonaktifkan terlebih dahulu.');
        }

        if ($parameterGaji->file) {
            Storage::disk('public')->delete($parameterGaji->file);
        }

        $parameterGaji->delete();

        return redirect()->route('admin.parameter-gaji.index')
            ->with('success', 'Parameter Gaji berhasil dihapus.');
    }
}
