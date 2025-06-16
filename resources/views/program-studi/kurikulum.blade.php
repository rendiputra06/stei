@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-4">Kurikulum</h1>
            <p class="lead text-muted">
                Kurikulum terintegrasi yang dirancang untuk menghasilkan lulusan yang kompeten di bidang ekonomi syariah
            </p>
        </div>
    </div>

    <!-- Informasi Umum -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-info-circle text-primary fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Informasi Umum</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Program Studi: Ekonomi Islam
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Gelar: Sarjana Ekonomi (S.E.)
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Akreditasi: A
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Lama Studi: 8 Semester
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Total SKS: 144
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Jenjang: S1
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Struktur Kurikulum -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-book text-success fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Struktur Kurikulum</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="6">1</td>
                                <td>EKI101</td>
                                <td>Pengantar Ekonomi Islam</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI102</td>
                                <td>Fiqh Muamalah</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI103</td>
                                <td>Matematika Ekonomi</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI104</td>
                                <td>Pengantar Akuntansi</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI105</td>
                                <td>Bahasa Inggris</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>EKI106</td>
                                <td>Pendidikan Agama Islam</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td rowspan="6">2</td>
                                <td>EKI201</td>
                                <td>Ekonomi Mikro Islam</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI202</td>
                                <td>Ekonomi Makro Islam</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI203</td>
                                <td>Akuntansi Syariah</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI204</td>
                                <td>Statistik Ekonomi</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>EKI205</td>
                                <td>Bahasa Arab</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>EKI206</td>
                                <td>Kewarganegaraan</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Konsentrasi -->
    <div class="row">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-graduation-cap text-warning fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Konsentrasi</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Perbankan Syariah</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Manajemen Bank Syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Operasional Bank Syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Produk Bank Syariah
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Keuangan Syariah</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Pasar Modal Syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Asuransi Syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Manajemen Investasi Syariah
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