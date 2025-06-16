<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STEI - Program Studi Ekonomi Islam</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tippy.js -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />

    <style>
        :root {
            --primary: #00695c;
            --secondary: #4db6ac;
            --dark: #1e293b;
            --light: #f8fafc;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--light);
        }

        .navbar {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            transition: 0.5s;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-gradient {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            position: relative;
            z-index: 1;
            overflow: hidden;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            z-index: -1;
        }

        .btn-gradient:hover::before {
            width: 100%;
        }

        .section-title {
            display: inline-block;
            position: relative;
            z-index: 1;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40%;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .cursor-glow {
            width: 20px;
            height: 20px;
            background: var(--primary);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.3;
            transition: transform 0.2s ease;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="cursor-glow d-none d-lg-block"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-mosque text-primary me-2"></i>STEI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}#home">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            Program Studi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('program-studi.index') }}">Program Studi Ekonomi Islam</a></li>
                            <li><a class="dropdown-item" href="{{ route('program-studi.visi-misi') }}">Visi & Misi</a></li>
                            <li><a class="dropdown-item" href="{{ route('program-studi.kurikulum') }}">Kurikulum</a></li>
                            <li><a class="dropdown-item" href="{{ route('program-studi.profil-lulusan') }}">Profil Lulusan</a></li>
                            <li><a class="dropdown-item" href="{{ route('program-studi.prospek-karir') }}">Prospek Karir</a></li>
                            <li><a class="dropdown-item" href="{{ route('program-studi.dosen') }}">Dosen</a></li>
                            <li><a class="dropdown-item" href="{{ route('program-studi.fasilitas') }}">Fasilitas</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}#tech">Kompetensi</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/home') }}" class="btn btn-gradient">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-gradient">Daftar</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-microchip me-2"></i>STEI
                    </h4>
                    <p class="mb-4 opacity-75">
                        Institusi pendidikan tinggi yang berfokus pada pengembangan teknologi informasi dan elektronika
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white opacity-75 hover-opacity-100">
                            <i class="fab fa-facebook-f fa-lg"></i>
                        </a>
                        <a href="#" class="text-white opacity-75 hover-opacity-100">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-white opacity-75 hover-opacity-100">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-white opacity-75 hover-opacity-100">
                            <i class="fab fa-linkedin-in fa-lg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h5 class="fw-bold mb-4">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ url('/') }}" class="text-white text-decoration-none opacity-75">Beranda</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('program-studi.index') }}" class="text-white text-decoration-none opacity-75">Program Studi</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ url('/') }}#features" class="text-white text-decoration-none opacity-75">Fitur</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ url('/') }}#tech" class="text-white text-decoration-none opacity-75">Kompetensi</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-4">Kontak</h5>
                    <ul class="list-unstyled opacity-75">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Jalan Contoh No. 123
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            (123) 456-7890
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            info@stei.ac.id
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-4">Newsletter</h5>
                    <p class="opacity-75 mb-4">
                        Dapatkan informasi terbaru tentang program studi dan kegiatan kampus
                    </p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center opacity-75">
                <small>&copy; {{ date('Y') }} STEI. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Tippy.js tooltips
        tippy('[data-tippy-content]', {
            theme: 'light',
            animation: 'shift-away',
        });

        // Cursor glow effect
        document.addEventListener('DOMContentLoaded', function() {
            const cursor = document.querySelector('.cursor-glow');

            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX - 10 + 'px';
                cursor.style.top = e.clientY - 10 + 'px';
            });

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.8)';
                    navbar.style.boxShadow = 'none';
                }
            });

            // Add active class to current navigation item
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html> 