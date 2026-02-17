@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Roles</h2>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Role
        </a>
    </div>


    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th>Users Count</th>
                            <th class="text-end pe-4">Action</th>
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
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($role->name !== 'admin')
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
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
