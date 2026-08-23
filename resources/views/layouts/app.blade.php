<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Simpeg DPRD') }} - Dashboard</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('assets/libs/tabler-icons/tabler-icons.min.css') }}">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta-sans/plus-jakarta-sans.css') }}">

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
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar (permission-driven, shared with admin) -->
        @include('layouts.partials.sidebar')

        <!-- Page Content -->
        <div id="content" class="d-flex flex-column min-vh-100 w-100 overflow-hidden">
            @include('layouts.partials.navbar')

            <div class="flex-grow-1">
                <main class="p-4">
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

    @livewireScripts
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // Livewire Swal Listener
            window.addEventListener('swal', function (e) {
                const data = e.detail;
                if (Array.isArray(data)) {
                    Swal.fire(data[0]);
                } else {
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
