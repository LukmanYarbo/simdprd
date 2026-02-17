@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create Role</h2>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block mb-3">Permissions</label>
                            <div class="row g-3">
                                @foreach($permissionGroups as $groupName => $permissions)
                                <div class="col-md-12">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-transparent border-bottom">
                                            <h6 class="mb-0 fw-bold text-capitalize">{{ $groupName }} Permissions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($permissions as $permission)
                                                <div class="col-md-4 col-sm-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                                            {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Create Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
