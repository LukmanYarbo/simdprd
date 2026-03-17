<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SIMDPRD') }} - Sistem Informasi Manajemen DPRD</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #6366f1;
            --bs-primary-rgb: 99, 102, 241;
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: 1px solid rgba(255, 255, 255, 0.4);
            --glass-backdrop: blur(12px);
        }

        body {
            font-family: var(--font-primary);
            background-color: #f8fafc;
            color: #0f172a;
            overflow-x: hidden;
        }

        .navbar {
            background: var(--glass-bg) !important;
            backdrop-filter: var(--glass-backdrop);
            -webkit-backdrop-filter: var(--glass-backdrop);
            border-bottom: var(--glass-border);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-section {
            padding: 10rem 0 6rem;
            position: relative;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -2px;
        }

        .hero-title span {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            max-width: 600px;
        }

        .btn-premium {
            padding: 0.8rem 2rem;
            border-radius: 0.8rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-premium-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-premium-outline {
            background: transparent;
            color: #6366f1;
            border: 2px solid #6366f1;
        }

        .btn-premium-outline:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: translateY(-2px);
        }

        .hero-image-wrapper {
            position: relative;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .hero-image {
            width: 100%;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .floating-badge {
            position: absolute;
            background: var(--glass-bg);
            backdrop-filter: var(--glass-backdrop);
            border: var(--glass-border);
            padding: 1rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .badge-payroll {
            top: 20%;
            left: -10%;
        }

        .badge-members {
            bottom: 20%;
            right: -5%;
        }

        .badge-icon {
            width: 3rem;
            height: 3rem;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border-radius: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .bg-gradient-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.4;
            border-radius: 50%;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: #6366f1;
            top: -10%;
            left: -5%;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: #a855f7;
            bottom: 10%;
            right: -5%;
        }

        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .hero-section {
                padding: 7rem 0 4rem;
                text-align: center;
            }
            .hero-subtitle {
                margin-left: auto;
                margin-right: auto;
            }
            .hero-image-wrapper {
                margin-top: 4rem;
            }
            .floating-badge {
                display: none;
            }
        }
    </style>
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
                <button class="btn btn-link nav-link p-0" id="theme-toggle" title="Toggle theme">
                    <i class="ti ti-sun fs-4 text-primary" id="theme-icon"></i>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-premium btn-premium-primary">
                            <i class="ti ti-layout-dashboard me-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-premium btn-premium-outline">
                            <i class="ti ti-login me-2"></i>Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-premium btn-premium-primary">
                                <i class="ti ti-user-plus me-2"></i>Register
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
                            Mulai Sekarang <i class="ti ti-arrow-right ms-2"></i>
                        </a>
                        <a href="#features" class="btn btn-premium btn-premium-outline">
                            Pelajari Selengkapnya
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('assets/images/landing/payroll_hero.png') }}" alt="Payroll System Animation" class="hero-image">
                        
                        <!-- Floating Badges -->
                        <div class="floating-badge badge-payroll">
                            <div class="badge-icon">
                                <i class="ti ti-receipt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold underline">Payroll Otomatis</h6>
                                <small class="text-secondary">Efisien & Akurat</small>
                            </div>
                        </div>

                        <div class="floating-badge badge-members">
                            <div class="badge-icon">
                                <i class="ti ti-users"></i>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
