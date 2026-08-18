@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5 fade-in-up">
        <h2 class="h3 fw-extrabold text-gradient mb-0">Security Roles</h2>
        <a href="{{ route('admin.roles.create') }}" class="btn premium-gradient border-0 glow-shadow px-4 py-2 rounded-pill transition-base">
            <i class="ti ti-shield-lock-fill me-2"></i> Add New Role
        </a>
    </div>


    <div class="card glass-card border-0 shadow-lg fade-in-up">
        <div class="card-header py-4 bg-transparent border-bottom border-white border-opacity-10">
            <h5 class="mb-0 fw-bold text-gradient">Role Configuration</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">No</th>
                            <th style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Role Name</th>
                            <th style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Permissions</th>
                            <th style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Users</th>
                            <th class="text-end pe-4" style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>
                            <td>
                                <span class="fw-semibold">{{ ucfirst($role->name) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">{{ $role->permissions_count }} Permissions</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $role->users_count }} Users</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    @if($role->name !== 'admin')
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
