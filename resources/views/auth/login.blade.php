<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name', 'SIMDPRD') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/libs/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta-sans/plus-jakarta-sans.css') }}">
    @vite(['resources/js/app.js'])
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-rgb: 99,102,241;
            --violet: #8b5cf6;
            --fuchsia: #d946ef;
            --cyan: #06b6d4;
            --font: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        * { font-family: var(--font); }
        body {
            min-height: 100vh;
            margin: 0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            position: relative;
            overflow-x: hidden;
        }
        /* Aurora mesh */
        .aurora {
            position: fixed; inset: 0; z-index: -1; overflow: hidden;
            background: radial-gradient(1200px 600px at 10% -10%, rgba(99,102,241,.18), transparent 60%),
                        radial-gradient(900px 600px at 90% 0%, rgba(139,92,246,.16), transparent 60%),
                        radial-gradient(800px 600px at 50% 100%, rgba(217,70,239,.10), transparent 60%),
                        linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }
        .aurora-orb {
            position: absolute; border-radius: 50%; filter: blur(60px); opacity: .55;
            animation: float 12s ease-in-out infinite;
        }
        .orb1 { width: 520px; height: 520px; background: linear-gradient(135deg,#6366f1,#8b5cf6); top: -120px; left: -80px; }
        .orb2 { width: 460px; height: 460px; background: linear-gradient(135deg,#8b5cf6,#d946ef); bottom: -80px; right: -60px; animation-delay: -4s; opacity:.35; }
        .orb3 { width: 380px; height: 380px; background: linear-gradient(135deg,#06b6d4,#6366f1); top: 50%; left: 50%; transform: translate(-50%,-50%); opacity:.18; animation-delay: -8s; }
        @keyframes float { 0%,100%{transform: translateY(0) translateX(0)} 50%{transform: translateY(-18px) translateX(8px)} }

        .auth-shell {
            width: 100%; max-width: 1120px;
            display: grid; grid-template-columns: 1.05fr .95fr;
            background: rgba(255,255,255,.72);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.65);
            border-radius: 2rem;
            box-shadow: 0 20px 60px -12px rgba(15,23,42,.12), 0 8px 24px -8px rgba(15,23,42,.08);
            overflow: hidden;
            animation: cardIn .6s cubic-bezier(.16,1,.3,1);
        }
        @keyframes cardIn { from{opacity:0; transform: translateY(14px) scale(.98)} to{opacity:1; transform: translateY(0) scale(1)} }

        /* Left branding */
        .brand-pane {
            padding: 2.75rem 2.5rem;
            background: linear-gradient(180deg, rgba(99,102,241,.06) 0%, rgba(139,92,246,.04) 100%);
            display: flex; flex-direction: column; position: relative; overflow: hidden;
        }
        .brand-top { display:flex; align-items:center; justify-content:space-between; margin-bottom: 2rem; }
        .brand-logo {
            display:flex; align-items:center; gap:.75rem; text-decoration:none;
        }
        .brand-mark {
            width:42px;height:42px;border-radius: 12px;
            background: linear-gradient(135deg,#6366f1 0%,#4f46e5 100%);
            display:grid; place-items:center; color:#fff; font-size:1.25rem;
            box-shadow: 0 8px 20px rgba(99,102,241,.35);
        }
        .brand-wordmark { font-weight:800; letter-spacing:-.04em; font-size:1.35rem; color:#0f172a; }
        .brand-wordmark span{ background: linear-gradient(135deg,#6366f1,#8b5cf6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }

        .hero-title {
            font-weight:800; line-height:1.05; letter-spacing:-.03em;
            font-size: clamp(1.85rem, 3vw, 2.5rem); color:#0f172a; margin:0 0 .9rem;
        }
        .hero-title em{ font-style:normal; background: linear-gradient(135deg,#6366f1 0%,#8b5cf6 55%,#d946ef 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .hero-desc { color:#64748b; line-height:1.6; font-size:.95rem; margin:0 0 1.75rem; max-width: 28rem; }
        .feature-list { display:grid; gap:.85rem; margin-bottom: 1.75rem; }
        .feature { display:flex; gap:.85rem; align-items:center; }
        .feature-icon {
            width:36px;height:36px;border-radius:10px; display:grid; place-items:center;
            background:#fff; border:1px solid rgba(15,23,42,.06); color:#6366f1; box-shadow: 0 4px 10px rgba(15,23,42,.06);
        }
        .feature b{ font-size:.9rem; color:#0f172a; display:block; line-height:1.2; }
        .feature span{ font-size:.8rem; color:#64748b; }
        .hero-visual {
            margin-top:auto; position:relative; border-radius:1.25rem; overflow:hidden;
            background: linear-gradient(135deg, #fff 0%, #f1f5f9 100%);
            border:1px solid rgba(15,23,42,.06); padding:.75rem;
            box-shadow: 0 12px 30px -10px rgba(15,23,42,.12);
        }
        .hero-visual img{ width:100%; height: 190px; object-fit:cover; border-radius:.9rem; display:block; }
        .hero-visual-caption {
            display:flex; align-items:center; gap:.75rem; padding:.85rem .25rem .25rem;
        }
        .dots{ display:flex; gap:.35rem; }
        .dots i{ width:8px;height:8px;border-radius:50%; display:block; }
        .dots i:nth-child(1){background:#ef4444} .dots i:nth-child(2){background:#f59e0b} .dots i:nth-child(3){background:#22c55e}

        /* Right form pane */
        .form-pane {
            padding: 2rem 2rem 1.75rem;
            background: rgba(255,255,255,.86);
            display:flex; flex-direction:column;
        }
        .tabs {
            display:flex; background:#f1f5f9; padding:4px; border-radius:999px; gap:4px; width:fit-content; margin-bottom:1.5rem;
        }
        .tab-btn {
            padding:.55rem 1.25rem; border-radius:999px; border:none; font-weight:700; font-size:.85rem;
            color:#64748b; background:transparent; cursor:pointer; transition: all .25s ease;
        }
        .tab-btn.active{ background:#fff; color:#0f172a; box-shadow: 0 4px 12px rgba(15,23,42,.08); }
        .pane-title{ font-weight:800; font-size:1.45rem; letter-spacing:-.02em; color:#0f172a; margin:0 0 .35rem; }
        .pane-subtitle{ color:#64748b; font-size:.88rem; margin:0 0 1.35rem; }

        .form-stack{ display:grid; gap:.9rem; }
        .field label{ font-weight:600; font-size:.8rem; color:#334155; letter-spacing:.01em; margin-bottom:.35rem; display:block; }
        .input-wrap{
            display:flex; align-items:center; gap:.6rem;
            background:#fff; border:1px solid #e2e8f0; border-radius: .9rem;
            padding:.15rem .9rem; transition: all .2s ease;
        }
        .input-wrap:focus-within{ border-color:#6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,.12); }
        .input-wrap .ti{ color:#94a3b8; font-size:1.15rem; }
        .input-wrap input{
            flex:1; border:none; outline:none; padding:.72rem 0; font-size:.92rem; font-weight:500; color:#0f172a; background:transparent;
        }
        .input-wrap input::placeholder{ color:#94a3b8; }
        .pw-toggle{ border:none; background:transparent; color:#94a3b8; cursor:pointer; padding:.25rem; border-radius:.5rem; }
        .pw-toggle:hover{ color:#6366f1; background:#f1f5f9; }

        .row-between{ display:flex; align-items:center; justify-content:space-between; gap:1rem; margin:.1rem 0 .2rem; }
        .check{ display:flex; align-items:center; gap:.5rem; font-size:.85rem; color:#475569; }
        .check input{ accent-color:#6366f1; width:16px; height:16px; }
        .link-minor{ font-size:.82rem; font-weight:600; color:#6366f1; text-decoration:none; }
        .link-minor:hover{ text-decoration:underline; }

        .btn-primary-modern{
            width:100%; padding:.85rem 1rem; border-radius: .9rem; border:none; font-weight:700; letter-spacing:.01em;
            color:#fff; background: linear-gradient(135deg,#6366f1 0%,#4f46e5 100%);
            box-shadow: 0 10px 20px -8px rgba(99,102,241,.45);
            display:flex; align-items:center; justify-content:center; gap:.5rem;
            transition: all .2s ease; cursor:pointer;
        }
        .btn-primary-modern:hover{ transform: translateY(-1px); box-shadow: 0 16px 28px -10px rgba(99,102,241,.5); filter:brightness(1.05); }
        .btn-primary-modern:active{ transform: translateY(0); }
        .btn-primary-modern:disabled{ opacity:.7; cursor:not-allowed; transform:none; }

        .alert-modern{
            background: rgba(239,68,68,.07); border:1px solid rgba(239,68,68,.18); color:#b91c1c;
            border-radius:.9rem; padding:.75rem .9rem; font-size:.85rem;
        }
        .alert-modern ul{ margin:0; padding-left:1.1rem; }
        .alert-success{ background: rgba(16,185,129,.08); border-color: rgba(16,185,129,.18); color:#065f46; }

        .divider{ display:flex; align-items:center; gap:.9rem; color:#94a3b8; font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; margin:1.1rem 0 .9rem; }
        .divider::before,.divider::after{ content:""; flex:1; height:1px; background:#e2e8f0; }
        .social-row{ display:grid; grid-template-columns: repeat(3,1fr); gap:.6rem; }
        .btn-social{
            height:44px; border-radius:.9rem; border:1px solid #e2e8f0; background:#fff;
            display:grid; place-items:center; color:#334155; font-size:1.15rem; text-decoration:none;
            transition: all .2s ease;
        }
        .btn-social:hover{ border-color:#cbd5e1; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,23,42,.06); }
        .foot-note{ text-align:center; color:#64748b; font-size:.85rem; margin-top:1.1rem; }
        .foot-note a{ color:#6366f1; font-weight:700; text-decoration:none; }
        .foot-note a:hover{ text-decoration:underline; }
        .legal{ text-align:center; color:#94a3b8; font-size:.72rem; margin-top:1rem; }

        .auth-form{ display:none; animation: fadeUp .35s cubic-bezier(.16,1,.3,1); }
        .auth-form.active{ display:block; }
        @keyframes fadeUp{ from{opacity:0; transform: translateY(6px)} to{opacity:1; transform: translateY(0)} }

        @media (max-width: 980px){
            .auth-shell{ grid-template-columns: 1fr; border-radius:1.5rem; }
            .brand-pane{ padding:1.75rem 1.5rem; }
            .hero-visual{ display:none; }
            .form-pane{ padding:1.5rem 1.25rem 1.25rem; }
            .hero-title{ font-size:1.9rem; }
        }
        /* dark mode */
        [data-bs-theme="dark"] body{ background:#0b0f1a; }
        [data-bs-theme="dark"] .aurora{ background: radial-gradient(1200px 600px at 10% -10%, rgba(99,102,241,.22), transparent 60%), radial-gradient(900px 600px at 90% 0%, rgba(139,92,246,.18), transparent 60%), linear-gradient(180deg, #0b0f1a 0%, #111827 100%); }
        [data-bs-theme="dark"] .auth-shell{ background: rgba(17,24,39,.72); border-color: rgba(255,255,255,.08); }
        [data-bs-theme="dark"] .brand-pane{ background: linear-gradient(180deg, rgba(99,102,241,.10) 0%, rgba(139,92,246,.06) 100%); }
        [data-bs-theme="dark"] .brand-wordmark, [data-bs-theme="dark"] .hero-title, [data-bs-theme="dark"] .pane-title{ color:#f1f5f9; }
        [data-bs-theme="dark"] .hero-desc, [data-bs-theme="dark"] .pane-subtitle, [data-bs-theme="dark"] .foot-note{ color:#94a3b8; }
        [data-bs-theme="dark"] .feature b{ color:#e2e8f0; }
        [data-bs-theme="dark"] .feature-icon{ background:#1e293b; border-color: rgba(255,255,255,.06); }
        [data-bs-theme="dark"] .hero-visual{ background: linear-gradient(135deg,#1e293b,#0f172a); border-color: rgba(255,255,255,.06); }
        [data-bs-theme="dark"] .form-pane{ background: rgba(15,23,42,.82); }
        [data-bs-theme="dark"] .tabs{ background:#1e293b; }
        [data-bs-theme="dark"] .tab-btn{ color:#94a3b8; }
        [data-bs-theme="dark"] .tab-btn.active{ background:#0f172a; color:#f1f5f9; }
        [data-bs-theme="dark"] .field label{ color:#cbd5e1; }
        [data-bs-theme="dark"] .input-wrap{ background:#0b1224; border-color: rgba(255,255,255,.08); }
        [data-bs-theme="dark"] .input-wrap input{ color:#f1f5f9; }
        [data-bs-theme="dark"] .btn-social{ background:#0b1224; border-color: rgba(255,255,255,.08); color:#e2e8f0; }
        [data-bs-theme="dark"] .divider::before,[data-bs-theme="dark"] .divider::after{ background: rgba(255,255,255,.08); }
    </style>
</head>
<body>
    <div class="aurora" aria-hidden="true">
        <div class="aurora-orb orb1"></div>
        <div class="aurora-orb orb2"></div>
        <div class="aurora-orb orb3"></div>
    </div>

    <div class="auth-shell">
        <div class="brand-pane">
            <div class="brand-top">
                <a href="{{ url('/') }}" class="brand-logo">
                    <span class="brand-mark"><i class="ti ti-building-bank"></i></span>
                    <span class="brand-wordmark">SIM<span>DPRD</span></span>
                </a>
                <button class="btn btn-sm btn-light rounded-pill px-3 d-none d-md-inline-flex" id="theme-toggle" type="button" title="Ganti tema">
                    <i class="ti ti-sun" id="theme-icon"></i>
                </button>
            </div>

            <h1 class="hero-title">Sistem Informasi & <em>Penggajian</em> Anggota DPRD</h1>
            <p class="hero-desc">Portal terpadu untuk mengelola data keanggotaan, penggajian, anggaran dan pelaporan — cepat, transparan, dan akuntabel.</p>

            <div class="feature-list">
                <div class="feature">
                    <span class="feature-icon"><i class="ti ti-shield-check"></i></span>
                    <div><b>Keamanan Berlapis</b><span>Role & permission yang terkontrol</span></div>
                </div>
                <div class="feature">
                    <span class="feature-icon"><i class="ti ti-receipt-2"></i></span>
                    <div><b>Payroll Otomatis</b><span>Proses gaji & potongan presisi</span></div>
                </div>
                <div class="feature">
                    <span class="feature-icon"><i class="ti ti-chart-bar"></i></span>
                    <div><b>Realtime Dashboard</b><span>Monitoring anggaran & anggota</span></div>
                </div>
            </div>

            <div class="hero-visual">
                <img src="{{ asset('assets/images/landing/payroll_hero.png') }}" alt="SIMDPRD Illustration" onerror="this.style.display='none'">
                <div class="hero-visual-caption">
                    <span class="dots"><i></i><i></i><i></i></span>
                    <span class="small text-secondary fw-semibold">SIMDPRD • Payroll & Keanggotaan</span>
                    <span class="ms-auto badge bg-success-subtle text-success-emphasis rounded-pill px-2 py-1" style="font-size:.65rem;">LIVE</span>
                </div>
            </div>
        </div>

        <div class="form-pane">
            <div class="tabs" role="tablist">
                <button class="tab-btn {{ (!session('register_error') && ($mode ?? '') !== 'register') ? 'active' : '' }}" data-tab="login" type="button">Masuk</button>
                <button class="tab-btn {{ (session('register_error') || ($mode ?? '') === 'register') ? 'active' : '' }}" data-tab="register" type="button">Daftar</button>
            </div>

            {{-- Login Form --}}
            <div id="pane-login" class="auth-form {{ (!session('register_error') && ($mode ?? '') !== 'register') ? 'active' : '' }}">
                <h2 class="pane-title">Selamat datang kembali</h2>
                <p class="pane-subtitle">Masukkan kredensial untuk melanjutkan ke dashboard</p>

                @if ($errors->any() && !session('register_error'))
                    <div class="alert-modern mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert-modern alert-success mb-3">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="form-stack" id="loginForm">
                    @csrf
                    <div class="field">
                        <label for="login_email">Email kerja</label>
                        <div class="input-wrap">
                            <i class="ti ti-mail"></i>
                            <input id="login_email" type="email" name="email" placeholder="nama@instansi.go.id" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="field">
                        <div class="row-between"><label for="login_password" style="margin:0">Password</label><a href="#" class="link-minor">Lupa password?</a></div>
                        <div class="input-wrap">
                            <i class="ti ti-lock"></i>
                            <input id="login_password" type="password" name="password" placeholder="••••••••" required>
                            <button class="pw-toggle" type="button" data-toggle="login_password" aria-label="Toggle password"><i class="ti ti-eye"></i></button>
                        </div>
                    </div>
                    <div class="row-between">
                        <label class="check"><input type="checkbox" name="remember"> Ingat saya</label>
                        <span class="small text-secondary" style="font-size:.8rem;">Secure login • v2.6</span>
                    </div>
                    <button type="submit" class="btn-primary-modern">
                        <span>Masuk ke Dashboard</span><i class="ti ti-arrow-right"></i>
                    </button>
                </form>

                <div class="divider"><span>atau lanjut dengan</span></div>
                <div class="social-row">
                    <a href="#" class="btn-social" aria-label="Google"><i class="ti ti-brand-google-filled"></i></a>
                    <a href="#" class="btn-social" aria-label="Microsoft"><i class="ti ti-brand-windows"></i></a>
                    <a href="#" class="btn-social" aria-label="Apple"><i class="ti ti-brand-apple-filled"></i></a>
                </div>
                <p class="foot-note">Belum punya akun? <a href="#" data-switch="register">Daftar sekarang</a></p>
            </div>

            {{-- Register Form --}}
            <div id="pane-register" class="auth-form {{ (session('register_error') || ($mode ?? '') === 'register') ? 'active' : '' }}">
                <h2 class="pane-title">Buat akun baru</h2>
                <p class="pane-subtitle">Daftar untuk mendapatkan akses SIMDPRD</p>

                @if ($errors->any() && session('register_error'))
                    <div class="alert-modern mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="form-stack">
                    @csrf
                    <div class="field">
                        <label for="reg_name">Nama lengkap</label>
                        <div class="input-wrap">
                            <i class="ti ti-user"></i>
                            <input id="reg_name" type="text" name="name" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="reg_email">Email</label>
                        <div class="input-wrap">
                            <i class="ti ti-mail"></i>
                            <input id="reg_email" type="email" name="email" placeholder="nama@instansi.go.id" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="reg_password">Password</label>
                        <div class="input-wrap">
                            <i class="ti ti-lock-access"></i>
                            <input id="reg_password" type="password" name="password" placeholder="Minimal 8 karakter" required>
                            <button class="pw-toggle" type="button" data-toggle="reg_password" aria-label="Toggle password"><i class="ti ti-eye"></i></button>
                        </div>
                        <div class="small text-secondary mt-1" style="font-size:.75rem;">Gunakan kombinasi huruf, angka & simbol untuk keamanan.</div>
                    </div>
                    <div class="field">
                        <label for="reg_password_confirmation">Konfirmasi Password</label>
                        <div class="input-wrap">
                            <i class="ti ti-lock-check"></i>
                            <input id="reg_password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required>
                            <button class="pw-toggle" type="button" data-toggle="reg_password_confirmation" aria-label="Toggle password"><i class="ti ti-eye"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary-modern">
                        <span>Buat Akun</span><i class="ti ti-sparkles"></i>
                    </button>
                </form>
                <p class="foot-note">Sudah punya akun? <a href="#" data-switch="login">Masuk di sini</a></p>
            </div>

            <p class="legal">© {{ date('Y') }} SIMDPRD • Sistem Informasi Manajemen DPRD. Hak cipta dilindungi.</p>
        </div>
    </div>

    <script>
        // Tabs
        const tabs = document.querySelectorAll('.tab-btn');
        const panes = { login: document.getElementById('pane-login'), register: document.getElementById('pane-register') };
        function switchTab(name){
            tabs.forEach(b=> b.classList.toggle('active', b.dataset.tab===name));
            Object.entries(panes).forEach(([k,el])=> el.classList.toggle('active', k===name));
            history.replaceState(null,'', name==='register' ? "{{ route('register') }}" : "{{ route('login') }}");
        }
        tabs.forEach(b=> b.addEventListener('click', ()=> switchTab(b.dataset.tab)));
        document.querySelectorAll('[data-switch]').forEach(a=> a.addEventListener('click', e=>{ e.preventDefault(); switchTab(a.dataset.switch); }));

        // Password toggle
        document.querySelectorAll('.pw-toggle').forEach(btn=>{
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-toggle');
                const input = document.getElementById(id);
                const isText = input.type === 'text';
                input.type = isText ? 'password' : 'text';
                btn.innerHTML = isText ? '<i class="ti ti-eye"></i>' : '<i class="ti ti-eye-off"></i>';
            });
        });

        // Theme toggle (reuse localStorage)
        (function(){
            const html = document.documentElement;
            const btn = document.getElementById('theme-toggle');
            const icon = document.getElementById('theme-icon');
            const saved = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-bs-theme', saved);
            function syncIcon(theme){
                if(!icon) return;
                icon.className = theme==='dark' ? 'ti ti-moon-stars' : 'ti ti-sun';
            }
            syncIcon(saved);
            if(btn){
                btn.addEventListener('click', ()=>{
                    const cur = html.getAttribute('data-bs-theme');
                    const next = cur==='dark' ? 'light' : 'dark';
                    html.setAttribute('data-bs-theme', next);
                    localStorage.setItem('theme', next);
                    syncIcon(next);
                });
            }
        })();

        // Submit loading state
        document.querySelectorAll('form').forEach(f=>{
            f.addEventListener('submit', ()=>{
                const btn = f.querySelector('.btn-primary-modern');
                if(btn){ btn.disabled=true; btn.innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'; }
            });
        });
    </script>
</body>
</html>
