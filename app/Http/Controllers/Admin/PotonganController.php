<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PotonganController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view potongan|create potongan|edit potongan|delete potongan', only: ['index', 'show']),
            new Middleware('permission:create potongan', only: ['create', 'store']),
            new Middleware('permission:edit potongan', only: ['edit', 'update']),
            new Middleware('permission:delete potongan', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.potongan.index');
    }
}
