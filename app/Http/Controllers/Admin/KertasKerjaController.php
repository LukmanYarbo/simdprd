<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KertasKerjaController extends Controller
{
    public function index()
    {
        return view('admin.anggaran.kertas-kerja.index');
    }

    public function form($id = null)
    {
        return view('admin.anggaran.kertas-kerja.form', compact('id'));
    }

    public function print($id)
    {
        $kertasKerja = \App\Models\KertasKerja::with('rincians')->findOrFail($id);
        return view('admin.anggaran.kertas-kerja.print', compact('kertasKerja'));
    }
}
