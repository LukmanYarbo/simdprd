<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - {{ config('app.name', 'SIMDPRD') }}</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    <style>
        :root {
            --bs-primary: #6366f1;
            --bs-primary-rgb: 99, 102, 241;
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: 1px solid rgba(255, 255, 255, 0.4);
            --glass-backdrop: blur(16px);
        }

        body {
            font-family: var(--font-primary);
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow-x: hidden;
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
            filter: blur(100px);
            opacity: 0.4;
            border-radius: 50%;
        }

        .shape-1 {
            width: 600px;
            height: 600px;
            background: #6366f1;
            top: -15%;
            left: -10%;
        }

        .shape-2 {
            width: 500px;
            height: 500px;
            background: #a855f7;
            bottom: -10%;
            right: -5%;
        }

        .main-container {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
            margin: auto;
        }

        .split-wrapper {
            display: flex;
            flex-direction: row;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 3rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }

        .visual-pane {
            flex: 1.2;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(168, 85, 247, 0.05) 100%);
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            position: relative;
        }

        .form-pane {
            flex: 1;
            padding: 4rem;
            background: rgba(255, 255, 255, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            text-decoration: none;
            display: inline-block;
        }

        .visual-title {
            font-weight: 800;
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: #1e293b;
        }

        .visual-title span {
            color: #6366f1;
        }

        .visual-description {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 3rem;
            max-width: 500px;
        }

        .hero-img-wrapper {
            width: 100%;
            animation: float 6s ease-in-out infinite;
        }

        .hero-img {
            width: 100%;
            max-width: 500px;
            border-radius: 2rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-backdrop);
            -webkit-backdrop-filter: var(--glass-backdrop);
            border: var(--glass-border);
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .auth-title {
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .auth-subtitle {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .input-group-premium {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 1rem;
            padding: 0.25rem 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .input-group-premium:focus-within {
            background: white;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .input-group-premium .ti {
            font-size: 1.25rem;
            color: #94a3b8;
            margin: 0 0.75rem;
        }

        .input-group-premium input {
            border: none;
            background: transparent;
            width: 100%;
            padding: 0.7rem 0.25rem;
            font-weight: 500;
            color: #1e293b;
            outline: none;
        }

        .btn-premium {
            padding: 0.8rem 2rem;
            border-radius: 1rem;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-premium-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .auth-toggle {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #64748b;
        }

        .auth-toggle a {
            color: #6366f1;
            font-weight: 700;
            text-decoration: none;
            margin-left: 0.25rem;
        }

        .auth-toggle a:hover {
            text-decoration: underline;
        }

        .social-divider {
            display: flex;
            align-items: center;
            margin: 2rem 0 1.5rem;
            color: #cbd5e1;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .social-divider::before, .social-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .social-divider span {
            padding: 0 1rem;
        }

        .social-btns {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-social {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #475569;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-social:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .auth-form {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .auth-form.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert-premium {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 1rem;
            color: #dc2626;
            font-size: 0.85rem;
            padding: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .alert-premium ul {
            margin-bottom: 0;
            padding-left: 1rem;
        }

        @media (max-width: 991.98px) {
            .split-wrapper {
                flex-direction: column;
                border-radius: 2rem;
            }
            .visual-pane {
                padding: 3rem 2rem;
                align-items: center;
                text-align: center;
            }
            .form-pane {
                padding: 2.5rem 1.5rem;
            }
            .visual-title {
                font-size: 2rem;
            }
            .visual-description {
                margin-bottom: 2rem;
            }
            .hero-img {
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-gradient-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="main-container">
        <div class="split-wrapper">
            <!-- Left Side: Visual & Content -->
            <div class="visual-pane">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ url('/') }}" class="navbar-brand mb-0">SIMDPRD</a>
                    <button class="btn btn-link nav-link p-0" id="theme-toggle" title="Toggle theme">
                        <i class="ti ti-sun fs-4 text-primary" id="theme-icon"></i>
                    </button>
                </div>
                <h1 class="visual-title">Sistem Informasi & <span>Penggajian</span> Anggota DPRD</h1>
                <p class="visual-description">
                    Selamat datang kembali di portal administrasi terpadu. Masuk untuk mengelola data kepegawaian, memproses penggajian, dan memantau statistik anggota secara real-time.
                </p>
                <div class="hero-img-wrapper d-none d-lg-block">
                    <img src="{{ asset('assets/images/landing/payroll_hero.png') }}" alt="Payroll Illustration" class="hero-img">
                </div>
            </div>

            <!-- Right Side: Auth Card -->
            <div class="form-pane">
                <div class="auth-card">
                    <!-- Login Form -->
                    <div id="login-form" class="auth-form {{ (!session('register_error') && ($mode ?? '') !== 'register') ? 'active' : '' }}">
                        <h2 class="auth-title">Masuk</h2>
                        <p class="auth-subtitle">Gunakan kredensial akun Anda</p>

                        @if ($errors->any() && !session('register_error'))
                            <div class="alert-premium">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-group-premium">
                                    <i class="ti ti-mail"></i>
                                    <input type="email" name="email" placeholder="nama@instansi.id" value="{{ old('email') }}" required autofocus />
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label">Password</label>
                                    <a href="#" class="text-decoration-none" style="font-size: 0.75rem; color: #6366f1; font-weight: 700;">Lupa password?</a>
                                </div>
                                <div class="input-group-premium">
                                    <i class="ti ti-lock"></i>
                                    <input type="password" name="password" placeholder="••••••••" required />
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" id="remember" class="form-check-input" style="cursor: pointer;">
                                <label for="remember" class="form-check-label text-secondary" style="font-size: 0.85rem; cursor: pointer;">Ingat saya</label>
                            </div>

                            <button type="submit" class="btn btn-premium btn-premium-primary">Masuk</button>
                        </form>

                        <div class="auth-toggle">
                            Belum punya akun? <a href="javascript:void(0)" onclick="toggleAuth('signup')">Daftar sekarang</a>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div id="signup-form" class="auth-form {{ (session('register_error') || ($mode ?? '') === 'register') ? 'active' : '' }}">
                        <h2 class="auth-title">Daftar</h2>
                        <p class="auth-subtitle">Buat akun sistem baru</p>

                        @if ($errors->any() && session('register_error'))
                            <div class="alert-premium">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group-premium">
                                    <i class="ti ti-user"></i>
                                    <input type="text" name="name" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-group-premium">
                                    <i class="ti ti-mail"></i>
                                    <input type="email" name="email" placeholder="nama@instansi.id" value="{{ old('email') }}" required />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group-premium">
                                    <i class="ti ti-lock"></i>
                                    <input type="password" name="password" required />
                                </div>
                            </div>

                            <button type="submit" class="btn btn-premium btn-premium-primary">Daftar</button>
                        </form>

                        <div class="auth-toggle">
                            Sudah punya akun? <a href="javascript:void(0)" onclick="toggleAuth('login')">Masuk di sini</a>
                        </div>
                    </div>

                    <div class="social-divider">
                        <span>Layanan lainnya</span>
                    </div>
                    <div class="social-btns">
                        <a href="#" class="btn-social"><i class="ti ti-brand-google"></i></a>
                        <a href="#" class="btn-social"><i class="ti ti-brand-facebook"></i></a>
                        <a href="#" class="btn-social"><i class="ti ti-brand-apple"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAuth(type) {
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            
            if (type === 'signup') {
                loginForm.classList.remove('active');
                signupForm.classList.add('active');
            } else {
                signupForm.classList.remove('active');
                loginForm.classList.add('active');
            }
        }
    </script>
</body>
</html>
