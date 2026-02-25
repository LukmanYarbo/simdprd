<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratTugasController extends Controller
{
    public function index()
    {
        return view('admin.surat-tugas.index');
    }

    public function print($id)
    {
        $suratTugas = \App\Models\SuratTugasAnggota::with(['penandatangan', 'anggotaSt.anggota.jabatan'])->findOrFail($id);
        $pemda = \App\Models\Pemda::first();
        
        // Members list (already sorted in the view by seniority if we use the right logic, 
        // but let's sort here too for consistency)
        $members = $suratTugas->anggotaSt
            ->sortBy(function($item) {
                return $item->anggota->id_dprd ?? 999;
            })->values();

        return view('admin.surat-tugas.print', compact('suratTugas', 'pemda', 'members'));
    }
}
