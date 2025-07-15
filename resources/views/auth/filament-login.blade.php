@php
    use App\Facades\GlobalSetting;
    $siteName = GlobalSetting::get('site_name', config('app.name'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteName }} - Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        :root {
            --bs-primary: #0d6efd;
            --bs-primary-hover: #0b5ed7;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        
        .form-signin {
            max-width: 430px;
            padding: 15px;
            margin: auto;
        }
        
        .form-signin .card {
            border-radius: 1rem;
            box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1);
            border: none;
            overflow: hidden;
        }
        
        .form-signin .card-body {
            padding: 2.5rem;
        }
        
        .form-signin .form-floating:focus-within {
            z-index: 2;
        }
        
        .app-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
            background-color: var(--bs-primary);
            color: white;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
        
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            font-weight: 500;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--bs-primary-hover);
            border-color: var(--bs-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.25);
        }
        
        .quick-login-card {
            border: 1px solid #eef0f2;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
        }
        
        .quick-login-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.05);
        }
        
        .quick-login-card .bi {
            transition: transform 0.3s ease;
        }
        
        .quick-login-card:hover .bi {
            transform: translateX(3px);
        }
        
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .copyright {
            font-size: 0.8rem;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #6c757d;
        }
        
        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        
        .divider::before {
            margin-right: 1rem;
        }
        
        .divider::after {
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <main class="form-signin w-100">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="app-logo">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h1 class="h4 fw-bold">{{ $siteName }}</h1>
                    <p class="text-muted">Silahkan login untuk melanjutkan</p>
                </div>
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>{{ session('error') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>
                
                @if(app()->environment('local') && count($quickLoginUsers) > 0)
                    <div class="divider">
                        <span>Quick Login</span>
                    </div>
                    
                    <div x-data="{ open: true }">
                        <div x-show="open" class="text-start">
                            <p class="text-muted small fst-italic mb-3">Akses cepat (hanya di lingkungan pengembangan)</p>
                            
                            <div class="quick-login-container">
                                @foreach($quickLoginUsers as $user)
                                    <a href="{{ route('filament.custom.quick-login', $user->id) }}" class="quick-login-card d-flex justify-content-between align-items-center text-decoration-none text-dark">
                                        <div>
                                            <div class="fw-medium">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <i class="bi bi-box-arrow-in-right text-primary"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <p class="mt-4 mb-0 text-muted copyright text-center">&copy; {{ date('Y') }} {{ $siteName }}</p>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html> 