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

        .hero-section {
            position: relative;
            overflow: hidden;
            padding: 120px 0;
            background: radial-gradient(circle at 30% -20%, #818cf8, #6366f1);
        }

        .hero-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: 30px 30px;
            background-image: linear-gradient(rgba(255, 255, 255, .1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .1) 1px, transparent 1px);
            opacity: 0.2;
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

        .program-card {
            border: none;
            border-radius: 20px;
            background: white;
            transition: all 0.3s ease;
        }

        .program-card:hover {
            transform: scale(1.02);
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

        .stat-card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
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

        .tech-stack {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 2rem;
        }

        .tech-item {
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--dark);
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .tech-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="cursor-glow d-none d-lg-block"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-mosque text-primary me-2"></i>STEI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
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
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tech">Kompetensi</a>
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

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-grid"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <span class="badge bg-white text-primary px-3 py-2 mb-4">
                        Program Studi Terakreditasi A
                    </span>
                    <h1 class="display-4 fw-bold mb-4">
                        Membangun Ekonomi Syariah untuk Indonesia
                    </h1>
                    <p class="lead opacity-90 mb-5">
                        Wujudkan impian Anda menjadi profesional ekonomi syariah melalui program studi unggulan kami.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('program-studi.index') }}" class="btn btn-light btn-lg">
                            Program Studi
                        </a>
                        <a href="#tech" class="btn btn-outline-light btn-lg">
                            Kompetensi
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1559067096-49ebca3406aa?ixlib=rb-4.0.3"
                            class="img-fluid rounded-4 shadow-lg" alt="Islamic Economics">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-10 rounded-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container py-5">
            <h2 class="section-title display-5 fw-bold mb-5">Fitur Unggulan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('program-studi.kurikulum') }}" class="text-decoration-none">
                        <div class="feature-card h-100 p-4" data-tippy-content="Pembelajaran berbasis syariah yang komprehensif">
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="fas fa-book-reader text-primary fa-2x"></i>
                                </div>
                                <h4 class="ms-3 mb-0 text-dark">Kurikulum Syariah</h4>
                            </div>
                            <p class="text-muted mb-0">
                                Kurikulum terintegrasi dengan nilai-nilai Islam dan standar industri keuangan syariah
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('program-studi.prospek-karir') }}" class="text-decoration-none">
                        <div class="feature-card h-100 p-4" data-tippy-content="Praktik langsung di industri keuangan syariah">
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="fas fa-handshake text-success fa-2x"></i>
                                </div>
                                <h4 class="ms-3 mb-0 text-dark">Praktik Industri</h4>
                            </div>
                            <p class="text-muted mb-0">
                                Kesempatan magang di lembaga keuangan syariah terkemuka
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('program-studi.profil-lulusan') }}" class="text-decoration-none">
                        <div class="feature-card h-100 p-4" data-tippy-content="Sertifikasi profesional">
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="fas fa-certificate text-warning fa-2x"></i>
                                </div>
                                <h4 class="ms-3 mb-0 text-dark">Sertifikasi</h4>
                            </div>
                            <p class="text-muted mb-0">
                                Program sertifikasi profesional dari lembaga nasional dan internasional
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-5 bg-light">
        <div class="container py-5">
            <h2 class="section-title display-5 fw-bold mb-5">Konsentrasi Program</h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="program-card p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-landmark text-primary fa-2x"></i>
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-1">Perbankan Syariah</h4>
                                <span class="badge bg-primary">S1</span>
                            </div>
                        </div>
                        <p class="text-muted mb-4">
                            Fokus pada manajemen dan operasional perbankan berbasis syariah
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="tech-item">Manajemen Bank Syariah</span>
                            <span class="tech-item">Akuntansi Syariah</span>
                            <span class="tech-item">Fiqh Muamalah</span>
                            <span class="tech-item">Islamic Finance</span>
                        </div>
                        <a href="{{ route('program-studi.kurikulum') }}" class="btn btn-gradient">Detail Program</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="program-card p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-chart-line text-primary fa-2x"></i>
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-1">Ekonomi Syariah</h4>
                                <span class="badge bg-primary">S1</span>
                            </div>
                        </div>
                        <p class="text-muted mb-4">
                            Pengembangan sistem ekonomi berbasis prinsip syariah
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="tech-item">Ekonomi Islam</span>
                            <span class="tech-item">Keuangan Syariah</span>
                            <span class="tech-item">Manajemen Zakat</span>
                            <span class="tech-item">Wakaf Produktif</span>
                        </div>
                        <a href="{{ route('program-studi.kurikulum') }}" class="btn btn-gradient">Detail Program</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Competency Section -->
    <section id="tech" class="py-5">
        <div class="container py-5">
            <h2 class="section-title display-5 fw-bold mb-5">Kompetensi Lulusan</h2>
            <p class="text-muted text-center mb-4">
                Kompetensi yang akan Anda kuasai setelah lulus
            </p>
            <div class="tech-stack">
                <div class="tech-item">
                    <i class="fas fa-calculator text-primary me-2"></i>Analisis Keuangan Syariah
                </div>
                <div class="tech-item">
                    <i class="fas fa-balance-scale text-success me-2"></i>Hukum Ekonomi Syariah
                </div>
                <div class="tech-item">
                    <i class="fas fa-hand-holding-usd text-warning me-2"></i>Manajemen Investasi
                </div>
                <div class="tech-item">
                    <i class="fas fa-chart-pie text-danger me-2"></i>Analisis Pasar Modal
                </div>
                <div class="tech-item">
                    <i class="fas fa-coins text-success me-2"></i>Manajemen Zakat
                </div>
                <div class="tech-item">
                    <i class="fas fa-building text-dark me-2"></i>Perbankan Syariah
                </div>
                <div class="tech-item">
                    <i class="fas fa-file-invoice-dollar text-primary me-2"></i>Asuransi Syariah
                </div>
                <div class="tech-item">
                    <i class="fas fa-hands-helping text-info me-2"></i>Wakaf Produktif
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
                            <a href="#home" class="text-white text-decoration-none opacity-75">Beranda</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('program-studi.index') }}" class="text-white text-decoration-none opacity-75">Program Studi</a>
                        </li>
                        <li class="mb-2">
                            <a href="#features" class="text-white text-decoration-none opacity-75">Fitur</a>
                        </li>
                        <li class="mb-2">
                            <a href="#tech" class="text-white text-decoration-none opacity-75">Kompetensi</a>
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
</body>

</html>