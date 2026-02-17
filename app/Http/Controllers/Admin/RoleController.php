<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['permissions', 'users'])->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        // Group permissions by their prefix (e.g., 'view users' -> 'users')
        $permissionGroups = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? end($parts) : 'Other';
        });
        
        return view('admin.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        // Group permissions by their prefix
        $permissionGroups = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? end($parts) : 'Other';
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'Cannot delete the admin role.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
