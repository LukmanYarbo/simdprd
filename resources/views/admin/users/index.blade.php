@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Users', 'icon' => 'ti ti-users']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="modern-page-header fade-in-up">
        <div class="header-left">
            <h2 class="h4">System Users</h2>
            <p>Kelola akun pengguna dan hak akses sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-modern-primary">
            <i class="ti ti-user-plus me-1"></i> Add New User
        </a>
    </div>


    <div class="card glass-card border-0 shadow-lg fade-in-up">
        <div class="card-header py-4 bg-transparent border-bottom border-white border-opacity-10">
            <h5 class="mb-0 fw-bold text-gradient">Manage Access</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">User</th>
                            <th style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Email Address</th>
                            <th style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Role</th>
                            <th style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Joined</th>
                            <th class="text-end pe-4" style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #94a3b8; padding: 1.25rem 1rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random" class="rounded-circle me-3" width="36" height="36" alt="{{ $user->name }}">
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary-subtle text-primary">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
