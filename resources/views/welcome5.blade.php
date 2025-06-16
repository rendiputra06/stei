<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEI - Program Studi Ekonomi Islam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        :root {
            --primary: #1B5E20;
            --secondary: #4CAF50;
            --accent: #81C784;
            --background: #F8F9FA;
            --text: #2C3E50;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
            transform: translateY(30px);
            opacity: 0;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            color: white;
            transform: translateY(30px);
            opacity: 0;
        }

        .program-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateX(-30px);
            opacity: 0;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
        }

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
            padding: 1rem 2rem;
            border-radius: 50px;
        }

        .section-title {
            margin-bottom: 4rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .footer {
            background: var(--primary);
            color: white;
            padding: 5rem 0;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 6rem 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-mosque me-2"></i>STEI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#programs">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Membangun Masa Depan dengan Ekonomi Syariah</h1>
                    <p class="lead mb-5">Gabung dengan program studi Ekonomi Islam dan jadilah bagian dari transformasi ekonomi berbasis syariah di Indonesia.</p>
                    <div class="d-flex gap-3">
                        <a href="#programs" class="btn btn-light">Lihat Program</a>
                        <a href="#contact" class="btn btn-outline-light">Hubungi Kami</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1559067096-49ebca3406aa?ixlib=rb-4.0.3" class="img-fluid rounded-4" alt="Islamic Economics">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Mengapa Memilih Ekonomi Islam?</h2>
                    <p class="lead mb-4">Program studi Ekonomi Islam dirancang untuk mempersiapkan mahasiswa menjadi profesional yang kompeten dalam bidang ekonomi dan keuangan syariah.</p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-book-reader fa-2x text-primary mb-3"></i>
                                <h4>Kurikulum Syariah</h4>
                                <p>Materi pembelajaran berbasis prinsip ekonomi Islam</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-handshake fa-2x text-primary mb-3"></i>
                                <h4>Kerjasama Industri</h4>
                                <p>Partnership dengan lembaga keuangan syariah</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-certificate fa-2x text-primary mb-3"></i>
                                <h4>Sertifikasi</h4>
                                <p>Program sertifikasi profesional industri</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                <h4>Dosen Ahli</h4>
                                <p>Pengajar berpengalaman dari industri</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-5 bg-light">
        <div class="container py-5">
            <h2 class="section-title text-center">Program Studi</h2>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="program-card">
                        <h3>Perbankan Syariah</h3>
                        <p class="lead mb-4">Fokus pada operasional dan manajemen perbankan berbasis syariah</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Manajemen Bank Syariah</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Akuntansi Syariah</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Analisis Pembiayaan</li>
                        </ul>
                    </div>
                    <div class="program-card">
                        <h3>Ekonomi Syariah</h3>
                        <p class="lead mb-4">Pengembangan sistem ekonomi berbasis prinsip Islam</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Ekonomi Islam</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Manajemen Zakat & Wakaf</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Keuangan Syariah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <h3 class="display-4 fw-bold mb-2">95%</h3>
                        <p class="mb-0">Tingkat Penyerapan Kerja</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h3 class="display-4 fw-bold mb-2">50+</h3>
                        <p class="mb-0">Mitra Industri</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h3 class="display-4 fw-bold mb-2">20+</h3>
                        <p class="mb-0">Dosen Praktisi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h3 class="display-4 fw-bold mb-2">1000+</h3>
                        <p class="mb-0">Alumni Sukses</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <h4 class="mb-4">STEI</h4>
                    <p>Program Studi Ekonomi Islam yang mengintegrasikan nilai-nilai Islam dengan pengetahuan ekonomi modern.</p>
                </div>
                <div class="col-lg-4">
                    <h4 class="mb-4">Kontak</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jalan Contoh No. 123</p>
                    <p><i class="fas fa-phone me-2"></i>(123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i>info@stei.ac.id</p>
                </div>
                <div class="col-lg-4">
                    <h4 class="mb-4">Media Sosial</h4>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            gsap.registerPlugin(ScrollTrigger);

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.8)';
                }
            });

            // Hero animations
            gsap.from('.hero-section h1', {
                duration: 1,
                y: 50,
                opacity: 0,
                ease: 'power3.out'
            });

            gsap.from('.hero-section p, .hero-section .btn', {
                duration: 1,
                y: 30,
                opacity: 0,
                stagger: 0.2,
                delay: 0.5,
                ease: 'power3.out'
            });

            // Feature cards animation
            gsap.utils.toArray('.feature-card').forEach(card => {
                gsap.to(card, {
                    scrollTrigger: {
                        trigger: card,
                        start: 'top bottom-=100',
                        toggleActions: 'play none none reverse'
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: 'power3.out'
                });
            });

            // Program cards animation
            gsap.utils.toArray('.program-card').forEach(card => {
                gsap.to(card, {
                    scrollTrigger: {
                        trigger: card,
                        start: 'top bottom-=100',
                        toggleActions: 'play none none reverse'
                    },
                    x: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: 'power3.out'
                });
            });

            // Stats animation
            gsap.utils.toArray('.stat-card').forEach(card => {
                gsap.to(card, {
                    scrollTrigger: {
                        trigger: card,
                        start: 'top bottom-=100',
                        toggleActions: 'play none none reverse'
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: 'power3.out'
                });
            });
        });
    </script>
</body>

</html>