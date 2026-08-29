@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Permissions', 'url' => route('admin.permissions.index'), 'icon' => 'ti ti-key'],
    ['label' => 'Tambah Permission', 'icon' => 'ti ti-plus']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="modern-page-header">
        <div class="header-left">
            <h2 class="h4">Tambah Permission</h2>
            <p>Tambahkan hak akses baru untuk sistem</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="btn-modern-ghost"><i class="ti ti-arrow-left"></i> Kembali</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="modern-form-card">
                <div class="form-card-header">
                    <div class="icon-box"><i class="ti ti-key"></i></div>
                    <div class="header-text">
                        <h5>Form Permission</h5>
                        <small>Gunakan huruf kecil dengan spasi, contoh "view users"</small>
                    </div>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                        @csrf
                        <div class="modern-input">
                            <label for="name" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. view users, edit posts" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Format disarankan lowercase dengan spasi.</div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.permissions.index') }}" class="btn-modern-ghost">Batal</a>
                            <button type="submit" class="btn-modern-primary"><i class="ti ti-device-floppy"></i> Simpan Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
