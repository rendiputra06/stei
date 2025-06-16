@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-4">Profil Lulusan</h1>
            <p class="lead text-muted">
                Kompetensi dan karakteristik lulusan Program Studi Ekonomi Islam yang siap bersaing di dunia kerja
            </p>
        </div>
    </div>

    <!-- Profil Umum -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-user-graduate text-primary fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Profil Umum</h4>
                </div>
                <p class="text-muted">
                    Lulusan Program Studi Ekonomi Islam diharapkan menjadi sarjana ekonomi yang memiliki kompetensi dalam bidang ekonomi syariah, berakhlak mulia, dan mampu mengaplikasikan ilmu pengetahuan dalam pengembangan ekonomi syariah di Indonesia.
                </p>
            </div>
        </div>
    </div>

    <!-- Kompetensi Utama -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-tasks text-success fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Kompetensi Utama</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Pengetahuan</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Memahami konsep dan prinsip ekonomi syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Menguasai teori dan praktik perbankan syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Memahami regulasi dan fatwa ekonomi syariah
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Keterampilan</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Menganalisis masalah ekonomi syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Mengelola lembaga keuangan syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Mengembangkan produk keuangan syariah
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Karakteristik Lulusan -->
    <div class="row">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-star text-warning fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Karakteristik Lulusan</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Profesional</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Berintegritas tinggi
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Bertanggung jawab
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Berorientasi pada hasil
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Akademis</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Berpikir kritis
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Inovatif
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Berwawasan global
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Sosial</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Berakhlak mulia
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Peduli sosial
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Berjiwa kepemimpinan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 