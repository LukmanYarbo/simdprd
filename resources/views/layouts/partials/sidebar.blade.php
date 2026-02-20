<nav id="sidebar" class="bg-dark text-white d-flex flex-column">
    <div class="sidebar-header border-bottom border-secondary d-flex align-items-center justify-content-between">
        <h4 class="mb-0 overflow-hidden text-nowrap">SIMDPRD</h4>
        <button class="btn btn-link text-white p-0 d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg fs-4"></i>
        </button>
    </div>
    <ul class="list-unstyled components mb-0 p-2 flex-grow-1">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link rounded {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="#masterDataSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.alat-kelengkapan.*') || request()->routeIs('admin.surat-keputusan.*') || request()->routeIs('admin.jabatan-asn.*') || request()->routeIs('admin.skpd.*') || request()->routeIs('admin.pemda.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-database me-2"></i> Master Data</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.alat-kelengkapan.*') || request()->routeIs('admin.surat-keputusan.*') || request()->routeIs('admin.jabatan-asn.*') || request()->routeIs('admin.skpd.*') || request()->routeIs('admin.pemda.*') ? 'show' : '' }} list-unstyled ps-3" id="masterDataSubmenu">
                <li>
                    <a href="{{ route('admin.pemda.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.pemda.*') ? 'active' : '' }}">
                        <i class="bi bi-building me-2"></i> Data Pemda
                    </a>
                </li>
                @can('view alat_kelengkapan')
                <li>
                    <a href="{{ route('admin.alat-kelengkapan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.alat-kelengkapan.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3 me-2"></i> Alat Kelengkapan
                    </a>
                </li>
                @endcan
                @can('view surat_keputusan')
                <li>
                    <a href="{{ route('admin.surat-keputusan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.surat-keputusan.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text me-2"></i> Surat Keputusan
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('admin.jabatan-asn.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.jabatan-asn.*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase me-2"></i> Jabatan ASN
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.skpd.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.skpd.*') ? 'active' : '' }}">
                        <i class="bi bi-building me-2"></i> Data SKPD
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#inputDataSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.anggota.*') || request()->routeIs('admin.pegawai-asn.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-pencil-square me-2"></i> Input Data</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.anggota.*') || request()->routeIs('admin.pegawai-asn.*') ? 'show' : '' }} list-unstyled ps-3" id="inputDataSubmenu">
                @can('view anggota')
                <li>
                    <a href="{{ route('admin.anggota.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Data Anggota
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('admin.pegawai-asn.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.pegawai-asn.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Pegawai ASN
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="sidebar-heading">System</li>
        @if(auth()->user()->can('view users') || auth()->user()->can('view roles'))
        <li>
            <a href="#settingsSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }}">
                <span><i class="bi bi-gear me-2"></i> Settings</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'show' : '' }} list-unstyled ps-3" id="settingsSubmenu">
                @can('view users')
                <li>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i> Users
                    </a>
                </li>
                @endcan
                @can('view roles')
                <li>
                    <a href="{{ route('admin.roles.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock me-2"></i> Role Management
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
    </ul>
    <div class="p-2 border-top border-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link rounded btn btn-link text-white text-decoration-none w-100 text-start">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </button>
        </form>
    </div>
</nav>
