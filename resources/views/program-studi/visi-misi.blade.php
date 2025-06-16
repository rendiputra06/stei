@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-4">Visi & Misi</h1>
            <p class="lead text-muted">
                Visi dan misi Program Studi Ekonomi Islam dalam mengembangkan ekonomi syariah di Indonesia
            </p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Visi -->
        <div class="col-lg-6">
            <div class="feature-card h-100 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-eye text-primary fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Visi</h4>
                </div>
                <p class="text-muted">
                    Menjadi program studi unggulan dalam pengembangan ekonomi syariah yang menghasilkan lulusan yang kompeten, berakhlak mulia, dan berdaya saing global pada tahun 2025.
                </p>
            </div>
        </div>

        <!-- Misi -->
        <div class="col-lg-6">
            <div class="feature-card h-100 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-bullseye text-success fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Misi</h4>
                </div>
                <ul class="list-unstyled text-muted">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Menyelenggarakan pendidikan dan pengajaran yang berkualitas dalam bidang ekonomi syariah
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Melaksanakan penelitian yang inovatif dan berkontribusi pada pengembangan ekonomi syariah
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Mengembangkan pengabdian kepada masyarakat dalam bidang ekonomi syariah
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Menjalin kerjasama dengan berbagai pihak untuk meningkatkan kualitas pendidikan
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tujuan -->
        <div class="col-12 mt-4">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-flag text-warning fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Tujuan</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                Menghasilkan lulusan yang kompeten dalam bidang ekonomi syariah
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                Mengembangkan penelitian yang berkontribusi pada pengembangan ekonomi syariah
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                Meningkatkan kualitas pengabdian kepada masyarakat
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-warning me-2"></i>
                                Mengembangkan kerjasama dengan berbagai pihak
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 