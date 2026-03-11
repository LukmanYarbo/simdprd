<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TransaksiGajiController extends Controller
{
    public function index()
    {
        return view('admin.transaksi-gaji.index');
    }
}
