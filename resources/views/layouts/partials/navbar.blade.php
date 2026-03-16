<nav class="navbar navbar-expand-lg px-4 py-3 sticky-top">
    <div class="d-flex align-items-center w-100 justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-link link-body-emphasis p-0 me-3" id="sidebarCollapse">
                <i class="ti ti-menu-2 fs-3"></i>
            </button>
            
            @php
                $pemda = \App\Models\Pemda::first();
            @endphp
            @if($pemda)
            <div class="d-flex align-items-center ms-2 px-3 py-1">
                @if($pemda->logo_pemda)
                    <img src="{{ Storage::url($pemda->logo_pemda) }}" alt="Logo {{ $pemda->namapemda }}" width="28" height="28" class="object-fit-contain me-2">
                @else
                    <i class="ti ti-building-bank text-primary fs-5 me-2"></i>
                @endif
                <span class="fw-bold fs-6 text-gradient">{{ $pemda->namapemda }}</span>
            </div>
            @endif
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Theme Toggle -->
            <button class="btn btn-link nav-link link-body-emphasis" id="theme-toggle" title="Toggle theme">
                <i class="ti ti-moon-filled fs-5" id="theme-icon"></i>
            </button>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle px-3 py-2 rounded-pill bg-body-tertiary border border-secondary-subtle transition-base" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff" alt="user" width="32" height="32" class="rounded-circle me-2 shadow-sm">
                    <span class="d-none d-md-inline fw-semibold me-1">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end glass-card border-0 shadow-lg mt-3 p-2" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profile.show') }}"><i class="ti ti-user-circle me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="#"><i class="ti ti-settings-wide-connected me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider opacity-10"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 py-2 text-danger"><i class="ti ti-logout me-2"></i>Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
