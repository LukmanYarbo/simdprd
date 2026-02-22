<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.defer="name" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label mb-0">Permissions</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="selectAllSwitch" wire:model.live="selectAll" wire:click="toggleAll">
                    <label class="form-check-label small" for="selectAllSwitch">Select All</label>
                </div>
            </div>
            <div class="row g-3">
                @foreach($permissionGroups as $groupName => $permissionsList)
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($groupName) }}" aria-expanded="false" aria-controls="collapse-{{ Str::slug($groupName) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-capitalize">{{ $groupName }} Permissions</h6>
                                <i class="bi bi-chevron-down small text-muted"></i>
                            </div>
                        </div>
                        <div id="collapse-{{ Str::slug($groupName) }}" class="collapse collapse-card">
                            <div class="card-body">
                                <div class="row">
                                    @foreach($permissionsList as $permission)
                                    <div class="col-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="permissions" value="{{ $permission['name'] }}" id="perm_{{ $permission['id'] }}">
                                            <label class="form-check-label small" for="perm_{{ $permission['id'] }}">
                                                {{ $permission['name'] }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
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
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> {{ $role && $role->exists ? 'Update Role' : 'Create Role' }}
                </button>
        </div>
    </form>
</div>
