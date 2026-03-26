<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SIMDPRD') }} - Sistem Informasi Manajemen DPRD</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('assets/libs/tabler-icons/tabler-icons.min.css') }}">
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta-sans/plus-jakarta-sans.css') }}">

    <!-- Bootstrap CSS (Replaced by Vite) -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- Theme Selection Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-modern.css') }}">
</head>
<body>
    <div class="bg-gradient-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">SIMDPRD</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <!-- Theme Toggle -->
                <button class="btn btn-link nav-link p-0 icon-hover-rotate" id="theme-toggle" title="Toggle theme">
                    <i class="ti ti-sun fs-4 text-primary" id="theme-icon"></i>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-premium btn-premium-primary">
                            <i class="ti ti-layout-dashboard-filled me-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-premium btn-premium-outline">
                            <i class="ti ti-login-2 me-2"></i>Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-premium btn-premium-primary">
                                <i class="ti ti-user-plus-filled me-2"></i>Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="hero-title">
                        Sistim Informasi & <span>Penggajian</span> Anggota DPRD
                    </h1>
                    <p class="hero-subtitle">
                        Solusi terpadu manajemen data kepegawaian, penggajian, dan administrasi Anggota Dewan Perwakilan Rakyat Daerah dengan teknologi modern yang transparan dan akuntabel.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('login') }}" class="btn btn-premium btn-premium-primary">
                            Mulai Sekarang <i class="ti ti-arrow-narrow-right ms-2 icon-hover-bounce"></i>
                        </a>
                        <a href="#features" class="btn btn-premium btn-premium-outline">
                            Pelajari Selengkapnya <i class="ti ti-info-circle ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('assets/images/landing/payroll_hero.png') }}" alt="Payroll System Animation" class="hero-image">
                        
                        <!-- Floating Badges -->
                        <div class="floating-badge badge-payroll">
                            <div class="badge-icon">
                                <i class="ti ti-receipt-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold underline">Payroll Otomatis</h6>
                                <small class="text-secondary">Efisien & Akurat</small>
                            </div>
                        </div>

                        <div class="floating-badge badge-members">
                            <div class="badge-icon">
                                <i class="ti ti-users-group text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Manajemen Anggota</h6>
                                <small class="text-secondary">Terintegrasi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Essential Scripts -->
    <!-- Scripts included via Vite -->
</body>
</html>
