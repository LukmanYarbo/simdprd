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
                <i class="ti ti-sun fs-5 text-primary" id="theme-icon"></i>
            </button>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle px-2 py-1 rounded-pill bg-body-tertiary border border-secondary-subtle transition-base hover-shadow" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false" style="padding-right: 1.25rem !important;">
                    <div class="position-relative">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff" alt="user" width="34" height="34" class="rounded-circle shadow-sm border border-2 border-white">
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="transform: translate(25%, 25%);"></span>
                    </div>
                    <div class="ms-2 d-none d-md-block text-start" style="line-height: 1.1;">
                        <div class="fw-bold fs-7">{{ Auth::user()->name }}</div>
                        <small class="text-muted" style="font-size: 0.65rem;">Administrator</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 p-2 rounded-4 animate__animated animate__fadeIn animate__faster" aria-labelledby="dropdownUser1" style="min-width: 220px;">
                    <li class="px-3 py-2 mb-2 bg-light rounded-3 d-md-none text-center">
                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                        <small class="text-muted">Administrator</small>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2 d-flex align-items-center" href="{{ route('profile.show') }}">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-2 me-3">
                                <i class="ti ti-user-circle text-primary fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Profil Saya</div>
                                <div class="text-muted extra-small">Detail akun anda</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2 d-flex align-items-center" href="#">
                            <div class="bg-warning bg-opacity-10 p-2 rounded-2 me-3">
                                <i class="ti ti-settings-2 text-warning fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Pengaturan</div>
                                <div class="text-muted extra-small">Kofigurasi sistem</div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-10 my-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 py-2 text-danger d-flex align-items-center w-100">
                                <div class="bg-danger bg-opacity-10 p-2 rounded-2 me-3">
                                    <i class="ti ti-logout text-danger fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold small">Keluar</div>
                                    <div class="text-muted extra-small">Akhiri sesi ini</div>
                                </div>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
