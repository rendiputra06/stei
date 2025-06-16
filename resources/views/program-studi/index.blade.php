@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-4">Program Studi Ekonomi Islam</h1>
            <p class="lead text-muted">
                Program studi yang mengintegrasikan prinsip-prinsip ekonomi Islam dengan teknologi modern untuk menghasilkan lulusan yang kompeten di bidang keuangan syariah.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('program-studi.visi-misi') }}" class="text-decoration-none">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-bullseye text-primary fa-2x"></i>
                        </div>
                        <h4 class="ms-3 mb-0 text-dark">Visi & Misi</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Visi dan misi program studi dalam mengembangkan ekonomi syariah di Indonesia
                    </p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('program-studi.kurikulum') }}" class="text-decoration-none">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-book text-success fa-2x"></i>
                        </div>
                        <h4 class="ms-3 mb-0 text-dark">Kurikulum</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Kurikulum terintegrasi dengan nilai-nilai Islam dan standar industri keuangan syariah
                    </p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('program-studi.profil-lulusan') }}" class="text-decoration-none">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-user-graduate text-warning fa-2x"></i>
                        </div>
                        <h4 class="ms-3 mb-0 text-dark">Profil Lulusan</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Kompetensi dan karakteristik lulusan program studi ekonomi Islam
                    </p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('program-studi.prospek-karir') }}" class="text-decoration-none">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-briefcase text-info fa-2x"></i>
                        </div>
                        <h4 class="ms-3 mb-0 text-dark">Prospek Karir</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Peluang karir dan pengembangan profesional di bidang ekonomi syariah
                    </p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('program-studi.dosen') }}" class="text-decoration-none">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="fas fa-chalkboard-teacher text-danger fa-2x"></i>
                        </div>
                        <h4 class="ms-3 mb-0 text-dark">Dosen</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Tim pengajar yang kompeten dan berpengalaman di bidang ekonomi syariah
                    </p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('program-studi.fasilitas') }}" class="text-decoration-none">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                            <i class="fas fa-building text-secondary fa-2x"></i>
                        </div>
                        <h4 class="ms-3 mb-0 text-dark">Fasilitas</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Fasilitas pembelajaran dan pendukung untuk menunjang proses belajar mengajar
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- Statistik Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12 text-center mb-5">
            <h2 class="section-title display-5 fw-bold">Statistik Program Studi</h2>
        </div>
        <div class="col-md-3">
            <div class="text-center">
                <div class="display-4 fw-bold text-primary mb-2">500+</div>
                <p class="text-muted">Mahasiswa Aktif</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center">
                <div class="display-4 fw-bold text-success mb-2">20+</div>
                <p class="text-muted">Dosen</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center">
                <div class="display-4 fw-bold text-warning mb-2">1000+</div>
                <p class="text-muted">Alumni</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center">
                <div class="display-4 fw-bold text-info mb-2">50+</div>
                <p class="text-muted">Mitra Industri</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12 text-center">
            <div class="bg-primary bg-opacity-10 rounded-4 p-5">
                <h2 class="display-5 fw-bold mb-4">Bergabung Bersama Kami</h2>
                <p class="lead text-muted mb-4">
                    Jadilah bagian dari generasi muda yang akan mengembangkan ekonomi syariah di Indonesia
                </p>
                <a href="{{ route('register') }}" class="btn btn-gradient btn-lg">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush 