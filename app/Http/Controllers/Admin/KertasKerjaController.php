<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KertasKerjaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view kertas_kerja|create kertas_kerja|edit kertas_kerja', only: ['index', 'form', 'print']),
        ];
    }

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
