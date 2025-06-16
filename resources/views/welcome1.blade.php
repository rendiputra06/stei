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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1986&q=80') center/cover;
            opacity: 0.1;
        }

        .navbar {
            background: rgba(13, 71, 161, 0.95) !important;
            backdrop-filter: blur(10px);
        }

        .feature-card {
            border: none;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 1rem;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            border: none;
            color: white;
            transition: transform 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            color: white;
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50%;
            height: 3px;
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-graduation-cap me-2"></i>STEI
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
                        <a class="nav-link" href="#academic">Program Studi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistik</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/home') }}" class="btn btn-outline-light">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-light">Daftar</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="badge bg-light text-primary mb-3">Institusi Terpercaya</span>
                    <h1 class="display-4 text-white fw-bold mb-4">
                        Raih Masa Depan Cemerlang di STEI
                    </h1>
                    <p class="lead text-white mb-5">
                        Membentuk generasi unggul dalam bidang Elektronika dan Informatika melalui pendidikan berkualitas dan inovatif
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#academic" class="btn btn-light btn-lg px-4">
                            Mulai Kuliah
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title fw-bold">Fitur Unggulan</h2>
                <p class="lead text-muted">Sistem akademik modern untuk kemudahan pembelajaran</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card card h-100 p-4 text-center">
                        <div class="feature-icon bg-primary bg-gradient">
                            <i class="fas fa-graduation-cap text-white fa-2x"></i>
                        </div>
                        <h4 class="mt-4 mb-3">KRS Online</h4>
                        <p class="text-muted mb-0">
                            Sistem pengisian KRS yang mudah dan efisien untuk perencanaan studi yang optimal
                        </p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card card h-100 p-4 text-center">
                        <div class="feature-icon bg-success bg-gradient">
                            <i class="fas fa-calendar-alt text-white fa-2x"></i>
                        </div>
                        <h4 class="mt-4 mb-3">Jadwal Real-time</h4>
                        <p class="text-muted mb-0">
                            Akses jadwal perkuliahan secara real-time dengan notifikasi otomatis
                        </p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card card h-100 p-4 text-center">
                        <div class="feature-icon bg-warning bg-gradient">
                            <i class="fas fa-chart-line text-white fa-2x"></i>
                        </div>
                        <h4 class="mt-4 mb-3">Evaluasi Online</h4>
                        <p class="text-muted mb-0">
                            Sistem evaluasi dosen dan mata kuliah untuk peningkatan kualitas pembelajaran
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Programs -->
    <section id="academic" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title fw-bold">Program Studi</h2>
                    <p class="lead mb-4">Pilih jalur pendidikan yang sesuai dengan passion Anda</p>
                    <div class="accordion" id="programAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#teknikInformatika">
                                    Teknik Informatika
                                </button>
                            </h2>
                            <div id="teknikInformatika" class="accordion-collapse collapse show" data-bs-parent="#programAccordion">
                                <div class="accordion-body">
                                    <p>Program studi yang fokus pada pengembangan software dan sistem informasi</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-primary me-2"></i>Pemrograman Web & Mobile</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Artificial Intelligence</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Database Management</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#teknikElektronika">
                                    Teknik Elektronika
                                </button>
                            </h2>
                            <div id="teknikElektronika" class="accordion-collapse collapse" data-bs-parent="#programAccordion">
                                <div class="accordion-body">
                                    <p>Program studi yang mengembangkan keahlian dalam sistem elektronik</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-primary me-2"></i>Embedded Systems</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Digital Electronics</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>IoT Development</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        class="img-fluid rounded-lg shadow" alt="Academic Programs">
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section id="stats" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card text-center">
                        <div class="display-4 fw-bold text-primary mb-2">500+</div>
                        <p class="text-muted mb-0">Mahasiswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card text-center">
                        <div class="display-4 fw-bold text-primary mb-2">50+</div>
                        <p class="text-muted mb-0">Dosen Profesional</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card text-center">
                        <div class="display-4 fw-bold text-primary mb-2">2</div>
                        <p class="text-muted mb-0">Program Studi</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card text-center">
                        <div class="display-4 fw-bold text-primary mb-2">85%</div>
                        <p class="text-muted mb-0">Tingkat Kelulusan</p>
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
                        <i class="fas fa-graduation-cap me-2"></i>STEI
                    </h5>
                    <p>Sekolah Tinggi Elektronika dan Informatika</p>
                    <div class="d-flex gap-3 mb-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-4">Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="#features" class="text-white text-decoration-none">Fitur</a></li>
                        <li class="mb-2"><a href="#academic" class="text-white text-decoration-none">Program Studi</a></li>
                        <li class="mb-2"><a href="#stats" class="text-white text-decoration-none">Statistik</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-4">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Jalan Contoh No. 123</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>(123) 456-7890</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>info@stei.ac.id</li>
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
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
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