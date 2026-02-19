@extends('layouts.admin')

@section('title', 'Edit SKPD')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.skpd.index') }}">SKPD</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit SKPD</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.skpd.update', $skpd->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="namaskpd" class="form-label">Nama SKPD <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('namaskpd') is-invalid @enderror" id="namaskpd" name="namaskpd" value="{{ old('namaskpd', $skpd->namaskpd) }}" required autofocus>
                        @error('namaskpd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.skpd.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
