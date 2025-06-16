@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-4">Prospek Karir</h1>
            <p class="lead text-muted">
                Peluang karir yang menjanjikan di berbagai sektor ekonomi syariah
            </p>
        </div>
    </div>

    <!-- Peluang Karir -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-briefcase text-primary fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Peluang Karir</h4>
                </div>
                <p class="text-muted">
                    Lulusan Program Studi Ekonomi Islam memiliki peluang karir yang luas di berbagai sektor ekonomi syariah, baik di sektor perbankan, keuangan, maupun sektor lainnya. Berikut adalah beberapa peluang karir yang dapat dijalani oleh lulusan:
                </p>
            </div>
        </div>
    </div>

    <!-- Sektor Perbankan -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-landmark text-success fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Sektor Perbankan</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Bank Syariah</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Relationship Manager
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Credit Analyst
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Sharia Compliance Officer
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Unit Usaha Syariah</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Product Development
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Risk Management
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Marketing Syariah
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sektor Keuangan -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-chart-line text-warning fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Sektor Keuangan</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Pasar Modal</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Investment Analyst
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Portfolio Manager
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Sharia Stock Analyst
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Asuransi</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Underwriter Syariah
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Claims Adjuster
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    Actuary Syariah
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sektor Lainnya -->
    <div class="row">
        <div class="col-12">
            <div class="feature-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="fas fa-building text-info fa-2x"></i>
                    </div>
                    <h4 class="ms-3 mb-0">Sektor Lainnya</h4>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Lembaga Zakat</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Fund Manager
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Program Development
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Social Investment
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Konsultan</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Sharia Consultant
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Business Development
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Financial Advisor
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <h5 class="mb-3">Akademisi</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Dosen
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Peneliti
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-info me-2"></i>
                                    Trainer
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