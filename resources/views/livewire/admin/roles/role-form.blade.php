<div class="role-form-modern">
    <form wire:submit.prevent="save">
        {{-- ===== Info Role ===== --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rf-icon bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                        <i class="ti ti-shield fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $role && $role->exists ? 'Edit Role' : 'Role Baru' }}</h5>
                        <small class="text-muted">Tentukan nama role dan hak aksesnya</small>
                    </div>
                </div>
                <label for="name" class="form-label fw-semibold small text-uppercase text-muted">Nama Role</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-user-shield text-primary"></i></span>
                    <input type="text"
                        class="form-control bg-light border-start-0 shadow-none @error('name') is-invalid @enderror"
                        id="name" wire:model.defer="name"
                        placeholder="Contoh: operator, supervisor..." required>
                </div>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ===== Toolbar Permissions ===== --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="ti ti-key me-1"></i>Hak Akses (Permissions)</h6>
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">
                            {{ count($permissions) }} terpilih
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary rounded-start-pill" wire:click="expandAllGroups" title="Buka semua grup">
                                <i class="ti ti-chevrons-down me-1"></i>Buka Semua
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-end-pill" wire:click="collapseAllGroups" title="Tutup semua grup">
                                <i class="ti ti-chevrons-up me-1"></i>Tutup Semua
                            </button>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="selectAllSwitch" wire:model.live="selectAll" wire:click="toggleAll">
                            <label class="form-check-label small fw-semibold" for="selectAllSwitch">Pilih Semua</label>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0 shadow-none" wire:model.live.debounce.300ms="search" placeholder="Cari permission... (contoh: edit anggota)">
                    @if(trim($search) !== '')
                        <button type="button" class="btn btn-outline-secondary border-start-0" wire:click="$set('search', '')" title="Bersihkan pencarian">
                            <i class="ti ti-x"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div class="card-body px-4 pb-4 pt-0">
                <div class="row g-3">
                    @foreach($permissionGroups as $groupName => $permissionsList)
                        @php
                            $isOpen = in_array($groupName, $openGroups);
                            $selectedCount = $this->groupSelectedCount($groupName);
                            $totalCount = $permissionsList->count();
                            $allChecked = $selectedCount === $totalCount;
                        @endphp
                        <div class="col-lg-4 col-md-6 col-12" wire:key="group-{{ $loop->index }}-{{ md5($groupName) }}">
                            <div class="perm-card h-100 {{ $selectedCount > 0 ? 'perm-card-active' : '' }}">
                                <button type="button" class="perm-card-header w-100 border-0 bg-transparent p-3 d-flex justify-content-between align-items-center"
                                    wire:click="toggleGroup('{{ $groupName }}')"
                                    aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                                    <div class="text-start d-flex align-items-center gap-2 min-w-0">
                                        <span class="rf-dot {{ $selectedCount > 0 ? 'bg-success' : 'bg-secondary-subtle' }}"></span>
                                        <span class="fw-bold text-capitalize text-truncate">{{ $groupName }}</span>
                                        <span class="badge rounded-pill {{ $allChecked ? 'bg-success' : ($selectedCount > 0 ? 'bg-warning text-dark' : 'bg-secondary-subtle text-secondary') }}">
                                            {{ $selectedCount }}/{{ $totalCount }}
                                        </span>
                                    </div>
                                    <i class="ti {{ $isOpen ? 'ti-chevron-up' : 'ti-chevron-down' }} text-muted"></i>
                                </button>

                                @if($isOpen)
                                    <div class="px-3 pb-3">
                                        <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 mb-2 small fw-semibold {{ $allChecked ? 'text-danger' : 'text-primary' }}"
                                            wire:click="toggleGroupPermissions('{{ $groupName }}')">
                                            <i class="ti {{ $allChecked ? 'ti-square-x' : 'ti-checks' }} me-1"></i>
                                            {{ $allChecked ? 'Hapus Semua Grup Ini' : 'Pilih Semua Grup Ini' }}
                                        </button>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($permissionsList as $permission)
                                                <div class="perm-pill" wire:key="perm-{{ $permission['id'] }}">
                                                    <input class="btn-check" type="checkbox"
                                                        wire:model.live="permissions"
                                                        value="{{ $permission['name'] }}"
                                                        id="perm_{{ $permission['id'] }}"
                                                        autocomplete="off">
                                                    <label class="btn btn-sm perm-pill-btn {{ explode(' ', $permission['name'])[0] }}" for="perm_{{ $permission['id'] }}">
                                                        {{ $permission['name'] }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($permissionGroups->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="ti ti-search-off fs-1 d-block mb-2 opacity-50"></i>
                        Tidak ada permission yang cocok dengan "<strong>{{ $search }}</strong>".
                    </div>
                @endif

                @error('permissions')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ===== Actions ===== --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-light rounded-pill px-4 fw-semibold">Batal</a>
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                <i class="ti ti-device-floppy me-1"></i> {{ $role && $role->exists ? 'Update Role' : 'Create Role' }}
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .perm-card {
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: .9rem;
        box-shadow: 0 1px 2px rgba(16,24,40,.04);
        transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease;
    }
    .perm-card:hover { box-shadow: 0 6px 18px rgba(16,24,40,.08); }
    .perm-card-active {
        border-color: #c7d7fe;
        background: linear-gradient(180deg, #f8faff 0%, #fff 100%);
    }
    .perm-card-header:hover { background: #f8f9fb !important; cursor: pointer; }
    .rf-icon { transition: transform .25s ease; }
    .rf-icon:hover { transform: scale(1.08); }
    .rf-dot {
        width: 8px; height: 8px; border-radius: 50%;
        display: inline-block; flex-shrink: 0;
    }

    /* Permission pills */
    .perm-pill .btn-check + .perm-pill-btn {
        border: 1px solid #e5e7eb;
        color: #475569;
        background: #f8fafc;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
        padding: .35rem .85rem;
        user-select: none;
        transition: all .2s ease;
    }
    .perm-pill .btn-check + .perm-pill-btn:hover {
        border-color: #93b4fd;
        color: #2563eb;
        transform: translateY(-1px);
    }
    .perm-pill .btn-check:checked + .perm-pill-btn {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 10px -2px rgba(37,99,235,.45);
    }

    /* Action-based accent on hover */
    .perm-pill .btn-check + .perm-pill-btn.view:hover { border-color: #34d399; color: #059669; }
    .perm-pill .btn-check + .perm-pill-btn.create:hover,
    .perm-pill .btn-check + .perm-pill-btn.add:hover { border-color: #60a5fa; color: #2563eb; }
    .perm-pill .btn-check + .perm-pill-btn.edit:hover,
    .perm-pill .btn-check + .perm-pill-btn.update:hover { border-color: #fbbf24; color: #d97706; }
    .perm-pill .btn-check + .perm-pill-btn.delete:hover,
    .perm-pill .btn-check + .perm-pill-btn.hapus:hover { border-color: #f87171; color: #dc2626; }
</style>
@endpush
