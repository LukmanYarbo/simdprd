<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TunjanganController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view tunjangan|create tunjangan|edit tunjangan|delete tunjangan', only: ['index', 'show']),
            new Middleware('permission:create tunjangan', only: ['create', 'store']),
            new Middleware('permission:edit tunjangan', only: ['edit', 'update']),
            new Middleware('permission:delete tunjangan', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.tunjangan.index');
    }
}
