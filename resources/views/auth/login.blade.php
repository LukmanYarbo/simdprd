<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMDPRD</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-5">
                <div class="text-center mb-4 text-white">
                    <img src="{{ asset('assets/images/logos/light-logo.svg') }}" alt="Logo DPRD" class="img-fluid mb-3" style="max-height: 80px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">
                    <h2 class="fw-bold mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">SIMDPRD</h2>
                    <p class="text-white-50">Sistem Informasi Manajemen DPRD</p>
                </div>

                <div class="login-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Welcome Back</h3>
                            <p class="text-muted">Please sign in to continue</p>
                        </div>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
                            </div>
                            <div class="text-center">
                                <a href="#" class="text-white-50 text-decoration-none small">Forgot password?</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4 text-white-50">
                    <small>&copy; {{ date('Y') }} SIMDPRD. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
