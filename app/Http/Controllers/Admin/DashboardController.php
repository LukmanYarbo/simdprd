<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;

class DashboardController extends Controller
{
    public function index()
    {
        $recentAnggota = Anggota::orderBy('created_at', 'desc')->take(5)->get();
        return view('admin.dashboard', compact('recentAnggota'));
    }
}
