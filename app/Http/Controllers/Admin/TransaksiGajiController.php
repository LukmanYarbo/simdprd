<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TransaksiGajiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view transaksi_gaji', only: ['index']),
        ];
    }

    public function index()
    {
        return view('admin.transaksi-gaji.index');
    }
}
