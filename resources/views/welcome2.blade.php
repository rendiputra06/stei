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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-section {
            background-color: #f8f9fa;
            position: relative;
            overflow: hidden;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .navbar {
            background-color: white !important;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #333;
            font-weight: 500;
            padding: 1rem 1.25rem;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #0d6efd;
        }

        .feature-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .program-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .program-card img {
            height: 200px;
            object-fit: cover;
        }

        .swiper {
            width: 100%;
            padding: 2rem 0;
        }

        .stat-card {
            padding: 2rem;
            border-radius: 16px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .btn-primary {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 3px;
            background-color: #0d6efd;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-microchip text-primary me-2"></i>STEI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
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
                    <a href="{{ url('/home') }}" class="btn btn-primary">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section min-vh-100 d-flex align-items-center">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 py-5">
                    <div class="mb-4">
                        <span class="badge bg-primary px-3 py-2 rounded-pill">Terakreditasi</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-4">
                        Bentuk Masa Depan Digital Anda di STEI
                    </h1>
                    <p class="lead text-muted mb-5">
                        Temukan potensi Anda dalam dunia teknologi modern melalui program studi unggulan kami dalam bidang Elektronika dan Informatika
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#programs" class="btn btn-primary btn-lg">
                            Jelajahi Program
                        </a>
                        <a href="#features" class="btn btn-outline-primary btn-lg">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        class="img-fluid rounded-4 shadow-lg" alt="Hero Image">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container py-5">
            <div class="section-header">
                <h2 class="fw-bold">Fitur Unggulan</h2>
                <p class="text-muted">Platform akademik modern untuk pembelajaran yang efektif</p>
            </div>
            <div class="swiper feature-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="feature-card p-4">
                            <div class="feature-icon bg-primary bg-opacity-10">
                                <i class="fas fa-laptop-code text-primary fa-2x"></i>
                            </div>
                            <h4>E-Learning Platform</h4>
                            <p class="text-muted mb-0">
                                Akses materi pembelajaran digital dan interaktif
                            </p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="feature-card p-4">
                            <div class="feature-icon bg-success bg-opacity-10">
                                <i class="fas fa-calendar-check text-success fa-2x"></i>
                            </div>
                            <h4>Smart Schedule</h4>
                            <p class="text-muted mb-0">
                                Manajemen jadwal kuliah dengan notifikasi otomatis
                            </p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="feature-card p-4">
                            <div class="feature-icon bg-danger bg-opacity-10">
                                <i class="fas fa-chart-bar text-danger fa-2x"></i>
                            </div>
                            <h4>Performance Analytics</h4>
                            <p class="text-muted mb-0">
                                Pantau perkembangan akademik secara real-time
                            </p>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-5 bg-light">
        <div class="container py-5">
            <div class="section-header">
                <h2 class="fw-bold">Program Studi</h2>
                <p class="text-muted">Pilih jalur karir teknologi masa depan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="program-card card">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3" class="card-img-top" alt="Teknik Informatika">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Teknik Informatika</h4>
                                <span class="badge bg-primary">S1</span>
                            </div>
                            <p class="card-text text-muted">
                                Kembangkan keahlian dalam pengembangan software dan sistem informasi
                            </p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Web Development</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Mobile Apps</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Cloud Computing</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="program-card card">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3" class="card-img-top" alt="Teknik Elektronika">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Teknik Elektronika</h4>
                                <span class="badge bg-primary">S1</span>
                            </div>
                            <p class="card-text text-muted">
                                Pelajari teknologi elektronik dan sistem embedded modern
                            </p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>IoT Systems</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Robotics</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Smart Devices</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats" class="py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="fas fa-users text-primary fa-2x mb-3"></i>
                        <div class="display-4 fw-bold text-primary">500+</div>
                        <p class="text-muted mb-0">Mahasiswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="fas fa-chalkboard-teacher text-primary fa-2x mb-3"></i>
                        <div class="display-4 fw-bold text-primary">50+</div>
                        <p class="text-muted mb-0">Dosen Ahli</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="fas fa-graduation-cap text-primary fa-2x mb-3"></i>
                        <div class="display-4 fw-bold text-primary">1000+</div>
                        <p class="text-muted mb-0">Alumni Sukses</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <i class="fas fa-building-columns text-primary fa-2x mb-3"></i>
                        <div class="display-4 fw-bold text-primary">20+</div>
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
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-microchip me-2"></i>STEI
                    </h5>
                    <p class="mb-4">
                        Membentuk generasi digital yang inovatif dan berkompeten dalam bidang Elektronika dan Informatika
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
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h5 class="fw-bold mb-4">Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="#features" class="text-white text-decoration-none">Fitur</a></li>
                        <li class="mb-2"><a href="#programs" class="text-white text-decoration-none">Program</a></li>
                        <li class="mb-2"><a href="#stats" class="text-white text-decoration-none">Statistik</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-4">Program Studi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Teknik Informatika</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Teknik Elektronika</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-4">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Jalan Contoh No. 123
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            (123) 456-7890
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            info@stei.ac.id
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} STEI. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        const swiper = new Swiper('.feature-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
            autoplay: {
                delay: 3000,
            },
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
        });
    </script>
</body>

</html>