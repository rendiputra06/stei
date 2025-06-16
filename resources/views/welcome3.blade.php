<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STEI - Sekolah Tinggi Elektronika dan Informatika</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #60a5fa;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Manrope', sans-serif;
            scroll-behavior: smooth;
        }

        .navbar {
            transition: all 0.3s ease;
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            position: relative;
            overflow: hidden;
        }

        .hero-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
        }

        .hero-circles div {
            position: absolute;
            border: 6px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero-circles div:nth-child(1) {
            width: 600px;
            height: 600px;
            top: -300px;
            right: -200px;
        }

        .hero-circles div:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -200px;
            left: -100px;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            background: var(--primary-color);
            color: white;
        }

        .program-card {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
        }

        .program-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .program-card .card-img-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.8));
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 40%;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translate(0, 0px);
            }

            50% {
                transform: translate(0, 15px);
            }

            100% {
                transform: translate(0, -0px);
            }
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-university me-2 text-primary"></i>STEI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#programs">Program Studi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistik</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/home') }}" class="btn btn-gradient">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-gradient">Daftar Sekarang</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section d-flex align-items-center">
        <div class="hero-circles">
            <div></div>
            <div></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="animate__animated animate__fadeInUp">
                        <h1 class="display-4 fw-bold text-white mb-4">
                            Membangun Masa Depan Digital Indonesia
                        </h1>
                        <p class="lead text-white opacity-75 mb-5">
                            Jadilah bagian dari revolusi teknologi dengan program studi unggulan kami dalam bidang Elektronika dan Informatika
                        </p>
                        <div class="d-flex gap-3">
                            <a href="#programs" class="btn btn-light btn-lg">
                                Mulai Perjalanan
                            </a>
                            <a href="#features" class="btn btn-outline-light btn-lg">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3"
                        class="img-fluid floating animate__animated animate__fadeInRight"
                        alt="Hero Image">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title fw-bold display-5">Fitur Unggulan</h2>
                <p class="text-muted">Sistem pembelajaran modern untuk kesuksesan akademik</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card animate__animated animate__fadeInUp">
                        <div class="feature-icon">
                            <i class="fas fa-laptop-code fa-2x"></i>
                        </div>
                        <h4>E-Learning System</h4>
                        <p class="text-muted mb-0">
                            Platform pembelajaran digital interaktif dengan akses 24/7
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        <div class="feature-icon bg-success">
                            <i class="fas fa-book-reader fa-2x"></i>
                        </div>
                        <h4>Digital Library</h4>
                        <p class="text-muted mb-0">
                            Akses ribuan sumber belajar digital dari mana saja
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                        <div class="feature-icon bg-warning">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h4>Progress Tracking</h4>
                        <p class="text-muted mb-0">
                            Monitor perkembangan akademik secara real-time
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title fw-bold display-5">Program Studi</h2>
                <p class="text-muted">Pilih program studi yang sesuai dengan minat Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="program-card card text-white animate__animated animate__fadeInLeft">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3"
                            class="card-img" alt="Teknik Informatika">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4">
                            <h3 class="card-title fw-bold mb-2">Teknik Informatika</h3>
                            <p class="card-text mb-3">
                                Kuasai pengembangan software dan teknologi informasi modern
                            </p>
                            <a href="#" class="btn btn-light">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="program-card card text-white animate__animated animate__fadeInRight">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3"
                            class="card-img" alt="Teknik Elektronika">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4">
                            <h3 class="card-title fw-bold mb-2">Teknik Elektronika</h3>
                            <p class="card-text mb-3">
                                Pelajari sistem elektronik dan teknologi embedded terkini
                            </p>
                            <a href="#" class="btn btn-light">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-card animate__animated animate__fadeInUp">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <div class="display-4 fw-bold text-gradient">500+</div>
                        <p class="text-muted mb-0">Mahasiswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                        <div class="display-4 fw-bold text-gradient">50+</div>
                        <p class="text-muted mb-0">Dosen Profesional</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                        <i class="fas fa-medal fa-3x text-primary mb-3"></i>
                        <div class="display-4 fw-bold text-gradient">85%</div>
                        <p class="text-muted mb-0">Tingkat Kelulusan</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                        <i class="fas fa-building fa-3x text-primary mb-3"></i>
                        <div class="display-4 fw-bold text-gradient">30+</div>
                        <p class="text-muted mb-0">Mitra Industri</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-university me-2"></i>STEI
                    </h4>
                    <p class="mb-4 opacity-75">
                        Membangun generasi digital yang inovatif dan berkompeten untuk masa depan Indonesia
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h5 class="fw-bold mb-4">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home" class="text-white text-decoration-none opacity-75">Beranda</a></li>
                        <li class="mb-2"><a href="#features" class="text-white text-decoration-none opacity-75">Fitur</a></li>
                        <li class="mb-2"><a href="#programs" class="text-white text-decoration-none opacity-75">Program</a></li>
                        <li class="mb-2"><a href="#stats" class="text-white text-decoration-none opacity-75">Statistik</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-4">Program Studi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none opacity-75">Teknik Informatika</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none opacity-75">Teknik Elektronika</a></li>
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
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });

        // Add active class to current navigation item
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Animate elements on scroll
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.animate__animated');
                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    if (elementTop < windowHeight - 100) {
                        const animationClass = element.classList[1];
                        element.classList.add(animationClass);
                    }
                });
            };

            window.addEventListener('scroll', animateOnScroll);
        });
    </script>
</body>

</html>