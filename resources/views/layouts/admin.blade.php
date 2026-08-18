<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Simpeg DPRD') }} - Admin Dashboard</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('assets/libs/tabler-icons/tabler-icons.min.css') }}">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta-sans/plus-jakarta-sans.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">

    <!-- Modern Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin-modern.css') }}">

    <!-- Scripts -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
        window.Laravel = {
            storageUrl: "{{ asset('storage') }}"
        };
    </script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    <style>
        .marquee-wrapper {
            overflow: hidden;
            white-space: nowrap;
            display: flex;
            align-items: center;
            width: 100%;
            position: sticky;
            top: 72px;
            /* var(--header-height) */
            z-index: 0;
            /* Stays above cards but below navbar dropdowns (1000+) */
            background: rgba(var(--bs-tertiary-bg-rgb), 0.9) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            animation: scrollerSlide 25s linear infinite;
            animation-delay: 3.5s;
            /* Wait for letters to finish dropping */
        }

        @keyframes scrollerSlide {
            0% {
                transform: translateX(0);
            }

            45% {
                transform: translateX(-100%);
            }

            45.001% {
                transform: translateX(100vw);
            }

            100% {
                transform: translateX(0);
            }
        }

        @keyframes dropLetter {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Page Content -->
        <div id="content" class="d-flex flex-column min-vh-100 w-100 overflow-hidden">
            @include('layouts.partials.navbar')

            <!-- Marquee Running Text with Fade Effect -->
            <div class="marquee-wrapper border-bottom border-white border-opacity-10 bg-white bg-opacity-5"
                style="mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
                <div class="marquee-content py-2 px-3 fw-medium small" style="color: var(--bs-primary);"
                    id="marqueeText">
                    Selamat Datang {{ auth()->check() ? auth()->user()->name : 'Guest' }} di Sistem Informasi Manajemen
                    DPRD
                </div>
            </div>

            <div class="flex-grow-1"> <!-- Added wrapper for main content to push footer -->
                <main class="p-4">
                    @hasSection('breadcrumbs')
                        <div class="mb-4">
                            @yield('breadcrumbs')
                        </div>
                    @endif
                    <div class="fade-in-up">
                        @yield('content')
                    </div>
                </main>
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

    @livewireScripts
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Marquee splitting logic for drop-down letters
            const marqueeEl = document.getElementById('marqueeText');
            if (marqueeEl) {
                const text = marqueeEl.innerText.trim();
                marqueeEl.innerHTML = '';
                text.split('').forEach((char, i) => {
                    let span = document.createElement('span');
                    span.innerText = char === ' ' ? '\u00A0' : char;
                    span.style.display = 'inline-block';
                    span.style.opacity = '0';
                    span.style.transform = 'translateY(-20px)';
                    // Stagger the animation duration based on character index
                    span.style.animation = `dropLetter 0.4s cubic-bezier(0.2, 0.8, 1, 1) forwards ${i * 0.03}s`;
                    marqueeEl.appendChild(span);
                });
            }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#3085d6',
                });
            @endif

            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif

            @if(session('info'))
                Toast.fire({
                    icon: 'info',
                    title: "{{ session('info') }}"
                });
            @endif

            // Digital Clock Logic
            function updateClock() {
                const now = new Date();
                const clockEl = document.querySelector('#digital-clock span');
                const dateEl = document.querySelector('#digital-date span');

                if (clockEl && dateEl) {
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    clockEl.innerText = `${hours}:${minutes}:${seconds}`;

                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    dateEl.innerText = now.toLocaleDateString('id-ID', options);
                }
            }

            setInterval(updateClock, 1000);
            updateClock(); // Initial call

            // Livewire Swal Listener
            window.addEventListener('swal', function (e) {
                const data = e.detail;
                if (Array.isArray(data)) {
                    // For old Livewire 2 style if mixed
                    Swal.fire(data[0]);
                } else {
                    // Livewire 3 style
                    Swal.fire({
                        title: data.title || '',
                        text: data.text || '',
                        icon: data.icon || 'info',
                        timer: data.timer || null,
                        showConfirmButton: data.showConfirmButton ?? true
                    });
                }
            });
        });
    </script>
</body>

</html>