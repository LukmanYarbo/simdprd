<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white d-flex flex-column">
            <div class="p-4 border-bottom border-secondary">
                <h4 class="mb-0">SIMDPRD</h4>
            </div>
            <ul class="list-unstyled components mb-0 p-2 flex-grow-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="sidebar-link rounded">
                        <i class="ti ti-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                </li>
            </ul>
            <div class="p-2 border-top border-secondary">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link rounded btn btn-link text-white text-decoration-none w-100 text-start">
                        <i class="ti ti-logout me-2"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content" class="d-flex flex-column min-vh-100">
            <nav class="navbar navbar-expand-lg border-bottom px-4 py-3">
                <div class="d-flex align-items-center w-100 justify-content-between">
                    <div></div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Theme Toggle -->
                        <button class="btn btn-link nav-link" id="theme-toggle" title="Toggle theme">
                            <i class="ti ti-moon-filled fs-5" id="theme-icon"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random" alt="user" width="32" height="32" class="rounded-circle me-2">
                                <strong>{{ Auth::user()->name }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="ti ti-person me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>

            <footer class="bg-body-tertiary text-center text-lg-start mt-auto py-3 border-top">
                <div class="container-fluid text-center">
                    <span class="text-muted">© {{ date('Y') }} SIMDPRD. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
