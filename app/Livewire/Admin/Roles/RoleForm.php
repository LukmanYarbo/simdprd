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

    /** Grup yang sedang terbuka (dikelola di server agar tidak tertutup saat re-render) */
    public array $openGroups = [];

    /** Kata kunci pencarian permission */
    public string $search = '';

    public function mount(?Role $role = null)
    {
        $this->role = $role;

        // Initialize form fields if editing
        if ($this->role && $this->role->exists) {
            $this->name = $this->role->name;
            $this->permissions = $this->role->permissions->pluck('name')->toArray();
        }

        // Buka semua grup secara default agar mudah dipilih
        $this->openGroups = $this->getPermissionGroups(false)->keys()->all();

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

    public function toggleGroup(string $group): void
    {
        if (in_array($group, $this->openGroups)) {
            $this->openGroups = array_values(array_diff($this->openGroups, [$group]));
        } else {
            $this->openGroups[] = $group;
        }
    }

    public function expandAllGroups(): void
    {
        $this->openGroups = $this->getPermissionGroups(false)->keys()->all();
    }

    public function collapseAllGroups(): void
    {
        $this->openGroups = [];
    }

    /**
     * Centang / hapus seluruh permission dalam satu grup.
     */
    public function toggleGroupPermissions(string $group): void
    {
        $names = $this->getPermissionGroups(false)->get($group, collect())->pluck('name')->all();

        if (empty($names)) {
            return;
        }

        $selectedCount = count(array_intersect($names, $this->permissions));

        if ($selectedCount === count($names)) {
            $this->permissions = array_values(array_diff($this->permissions, $names));
        } else {
            $this->permissions = array_values(array_unique(array_merge($this->permissions, $names)));
        }

        $this->updateSelectAllState();
    }

    /**
     * Jumlah permission grup yang sedang tercentang.
     */
    public function groupSelectedCount(string $group): int
    {
        $names = $this->getPermissionGroups(false)->get($group, collect())->pluck('name')->all();

        return count(array_intersect($names, $this->permissions));
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

    private function getPermissionGroups(bool $applySearch = true)
    {
        $query = Permission::query();

        if ($applySearch && trim($this->search) !== '') {
            $query->where('name', 'like', '%' . trim($this->search) . '%');
        }

        return $query->get()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? $parts[count($parts) - 1] : 'Other';
        })->sortKeys();
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
        return view('livewire.admin.roles.role-form', [
            'permissionGroups' => $this->getPermissionGroups(),
        ]);
    }
}
