<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleForm extends Component
{
    public ?Role $role = null;
    public string $name = '';
    public array $permissions = [];
    public bool $selectAll = false;

    public function mount(?Role $role = null)
    {
        $this->role = $role;

        // Initialize form fields if editing
        if ($this->role && $this->role->exists) {
            $this->name = $this->role->name;
            $this->permissions = $this->role->permissions->pluck('name')->toArray();
        }
        
        $this->updateSelectAllState();
    }

    public function updatedPermissions()
    {
        $this->updateSelectAllState();
    }

    public function toggleAll()
    {
        if ($this->selectAll) {
            $this->permissions = Permission::pluck('name')->toArray();
        } else {
            $this->permissions = [];
        }
    }

    private function updateSelectAllState()
    {
        $totalPermissions = Permission::count();
        $this->selectAll = count($this->permissions) === $totalPermissions && $totalPermissions > 0;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->role?->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ];
    }

    public function save()
    {
        $this->validate();

        // Create or Update Role
        if ($this->role && $this->role->exists) {
            $this->role->update(['name' => $this->name]);
            $this->role->syncPermissions($this->permissions);
            session()->flash('success', 'Role updated successfully.');
        } else {
            $newRole = Role::create(['name' => $this->name]);
            if (!empty($this->permissions)) {
                $newRole->syncPermissions($this->permissions);
            }
            session()->flash('success', 'Role created successfully.');
        }

        return redirect()->route('admin.roles.index');
    }

    public function render()
    {
        $allPermissions = Permission::all();
        $permissionGroups = $allPermissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? end($parts) : 'Other';
        });

        return view('livewire.admin.roles.role-form', [
            'permissionGroups' => $permissionGroups
        ]);
    }
}
