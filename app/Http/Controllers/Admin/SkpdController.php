<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SkpdController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view skpd|create skpd|edit skpd|delete skpd', only: ['index', 'show']),
            new Middleware('permission:create skpd', only: ['create', 'store']),
            new Middleware('permission:edit skpd', only: ['edit', 'update']),
            new Middleware('permission:delete skpd', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skpds = Skpd::latest()->get();
        return view('admin.skpd.index', compact('skpds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.skpd.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaskpd' => 'required|string|max:255|unique:skpds,namaskpd',
        ]);

        Skpd::create($request->all());

        return redirect()->route('admin.skpd.index')
            ->with('success', 'SKPD berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skpd $skpd)
    {
        return view('admin.skpd.edit', compact('skpd'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skpd $skpd)
    {
        $request->validate([
            'namaskpd' => 'required|string|max:255|unique:skpds,namaskpd,' . $skpd->id,
        ]);

        $skpd->update($request->all());

        return redirect()->route('admin.skpd.index')
            ->with('success', 'SKPD berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skpd $skpd)
    {
        $skpd->delete();

        return redirect()->route('admin.skpd.index')
            ->with('success', 'SKPD berhasil dihapus.');
    }
}
