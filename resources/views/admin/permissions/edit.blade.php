@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Permissions', 'url' => route('admin.permissions.index'), 'icon' => 'ti ti-key'],
    ['label' => 'Edit Permission', 'icon' => 'ti ti-edit']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="modern-page-header">
        <div class="header-left">
            <h2 class="h4">Edit Permission</h2>
            <p>Perbarui nama permission: <strong>{{ $permission->name }}</strong></p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="btn-modern-ghost"><i class="ti ti-arrow-left"></i> Kembali</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="modern-form-card">
                <div class="form-card-header">
                    <div class="icon-box" style="background: linear-gradient(135deg,#f59e0b,#d97706);"><i class="ti ti-edit"></i></div>
                    <div class="header-text">
                        <h5>Edit Permission</h5>
                        <small>Perbarui nama hak akses</small>
                    </div>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modern-input">
                            <label for="name" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.permissions.index') }}" class="btn-modern-ghost">Batal</a>
                            <button type="submit" class="btn-modern-primary" style="background: linear-gradient(135deg,#f59e0b,#d97706);"><i class="ti ti-check"></i> Update Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
