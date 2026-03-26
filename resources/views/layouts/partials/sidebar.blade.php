<nav id="sidebar" class="bg-body text-body d-flex flex-column">
    <div class="sidebar-header border-bottom border-secondary-subtle d-flex align-items-center justify-content-between p-4">
        <h4 class="mb-0 overflow-hidden text-nowrap text-gradient">SIMDPRD</h4>
        <button class="btn btn-link link-body-emphasis p-0 d-lg-none" id="sidebarClose">
            <i class="ti ti-x fs-4"></i>
        </button>
    </div>
    <ul class="list-unstyled components mb-0 p-2 flex-grow-1">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link rounded {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard me-2"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="#masterDataSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.alat-kelengkapan.*') || request()->routeIs('admin.surat-keputusan.*') || request()->routeIs('admin.jabatan-asn.*') || request()->routeIs('admin.skpd.*') || request()->routeIs('admin.pemda.*') || request()->routeIs('admin.surat-tugas.*') || request()->routeIs('admin.penanda-tangan.*') ? 'true' : 'false' }}">
                <span><i class="ti ti-folders me-2"></i> Master Data</span>
                <i class="ti ti-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.alat-kelengkapan.*') || request()->routeIs('admin.surat-keputusan.*') || request()->routeIs('admin.jabatan-asn.*') || request()->routeIs('admin.skpd.*') || request()->routeIs('admin.pemda.*') || request()->routeIs('admin.surat-tugas.*') || request()->routeIs('admin.penanda-tangan.*') ? 'show' : '' }} list-unstyled ps-3" id="masterDataSubmenu">
                <li>
                    <a href="{{ route('admin.pemda.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.pemda.*') ? 'active' : '' }}">
                        <i class="ti ti-building-monument me-2"></i> Data Pemda
                    </a>
                </li>
                @can('view alat_kelengkapan')
                <li>
                    <a href="{{ route('admin.alat-kelengkapan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.alat-kelengkapan.*') ? 'active' : '' }}">
                        <i class="ti ti-components me-2"></i> Alat Kelengkapan
                    </a>
                </li>
                @endcan
                @can('view surat_keputusan')
                <li>
                    <a href="{{ route('admin.surat-keputusan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.surat-keputusan.*') ? 'active' : '' }}">
                        <i class="ti ti-certificate me-2"></i> Surat Keputusan
                    </a>
                </li>
                @endcan
                @can('view jabatan_asn')
                <li>
                    <a href="{{ route('admin.jabatan-asn.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.jabatan-asn.*') ? 'active' : '' }}">
                        <i class="ti ti-briefcase me-2"></i> Jabatan ASN
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('admin.skpd.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.skpd.*') ? 'active' : '' }}">
                        <i class="ti ti-building-skyscraper me-2"></i> Data SKPD
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.surat-tugas.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.surat-tugas.*') ? 'active' : '' }}">
                        <i class="ti ti-file-text me-2"></i> Surat Tugas Anggota
                    </a>
                </li>
                @can('view penanda_tangan')
                <li>
                    <a href="{{ route('admin.penanda-tangan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.penanda-tangan.*') ? 'active' : '' }}">
                        <i class="ti ti-signature me-2"></i> Penanda Tangan
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('admin.anggota-status.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.anggota-status.*') ? 'active' : '' }}">
                        <i class="ti ti-user-edit me-2"></i> Perubahan Status Anggota
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#masterGajiSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.parameter-gaji.*') || request()->routeIs('admin.tarif-pajak.*') || request()->routeIs('admin.tunjangan.*') || request()->routeIs('admin.potongan.*') ? 'true' : 'false' }}">
                <span><i class="ti ti-wallet me-2"></i>Gaji dan Tunjangan</span>
                <i class="ti ti-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.parameter-gaji.*') || request()->routeIs('admin.tarif-pajak.*') || request()->routeIs('admin.tunjangan.*') || request()->routeIs('admin.potongan.*') ? 'show' : '' }} list-unstyled ps-3" id="masterGajiSubmenu">
                @can('view parameter_gaji')
                <li>
                    <a href="{{ route('admin.parameter-gaji.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.parameter-gaji.*') ? 'active' : '' }}">
                        <i class="ti ti-calculator me-2"></i> Parameter Gaji
                    </a>
                </li>
                @endcan
                @can('view tarif_pajak')
                <li>
                    <a href="{{ route('admin.tarif-pajak.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.tarif-pajak.*') ? 'active' : '' }}">
                        <i class="ti ti-percentage me-2"></i> Tarif Pajak
                    </a>
                </li>
                @endcan
                @can('view tunjangan')
                <li>
                    <a href="{{ route('admin.tunjangan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.tunjangan.*') ? 'active' : '' }}">
                        <i class="ti ti-businessplan me-2"></i> Data Tunjangan
                    </a>
                </li>
                @endcan
                @can('view potongan')
                <li>
                    <a href="{{ route('admin.potongan.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.potongan.*') ? 'active' : '' }}">
                        <i class="ti ti-scissors me-2"></i> Data Potongan
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        <li>
            <a href="{{ route('admin.transaksi-gaji.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.transaksi-gaji.*') ? 'active' : '' }}">
                <i class="ti ti-coins me-2"></i> Proses Gaji
            </a>
        </li>

        <li>
            <a href="#inputDataSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.anggota.*') || request()->routeIs('admin.pegawai-asn.*') ? 'true' : 'false' }}">
                <span><i class="ti ti-database-import me-2"></i> Input Data</span>
                <i class="ti ti-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.anggota.*') || request()->routeIs('admin.pegawai-asn.*') ? 'show' : '' }} list-unstyled ps-3" id="inputDataSubmenu">
                @can('view anggota')
                <li>
                    <a href="{{ route('admin.anggota.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                        <i class="ti ti-user-check me-2"></i> Data Anggota
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('admin.pegawai-asn.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.pegawai-asn.*') ? 'active' : '' }}">
                        <i class="ti ti-shield-lock me-2"></i> Pegawai ASN
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="sidebar-heading">System</li>
        @if(auth()->user()->can('view users') || auth()->user()->can('view roles'))
        <li>
            <a href="#settingsSubmenu" class="sidebar-link rounded d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }}">
                <span><i class="ti ti-settings-2 me-2"></i> Settings</span>
                <i class="ti ti-chevron-down small"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'show' : '' }} list-unstyled ps-3" id="settingsSubmenu">
                @can('view users')
                <li>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="ti ti-users-group me-2"></i> Users
                    </a>
                </li>
                @endcan
                @can('view roles')
                <li>
                    <a href="{{ route('admin.roles.index') }}" class="sidebar-link rounded {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="ti ti-shield-check me-2"></i> Role Management
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
    </ul>
    <div class="p-2 border-top border-secondary-subtle">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link rounded btn btn-link text-decoration-none w-100 text-start" style="color: inherit;">
                <i class="ti ti-door-exit me-2"></i> Logout
            </button>
        </form>
    </div>
</nav>
