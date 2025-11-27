@extends('layouts.guest')

@section('title', 'Selamat Datang')

@section('content')
<div class="welcome-container">
    <!-- Hero Section -->
    <div class="welcome-hero">
        <div class="welcome-logo">
            <i class="fas fa-users"></i>
        </div>
        
        <h1 class="welcome-title">
            Sistem Informasi Kegiatan Sosial RT 04
        </h1>
        
        <div class="welcome-location">
            <i class="fas fa-map-marker-alt"></i>
            <span>Kelurahan Mudung Laut, Kecamatan Pelayangan, Kota Jambi</span>
        </div>
        
        <p class="welcome-tagline">
            Platform digital untuk mengelola kegiatan sosial RT dengan mudah, transparan, dan efisien
        </p>
    </div>

    <!-- Features Section -->
    <div class="welcome-features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Kelola Kegiatan</h3>
                <p>Atur dan pantau kegiatan sosial dengan mudah</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Manajemen Warga</h3>
                <p>Data warga terorganisir dan terstruktur</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Laporan Transparan</h3>
                <p>Pelaporan kegiatan yang jelas dan akurat</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="welcome-cta">
        @auth
            @if (Auth::user()->role === 'admin')
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard Admin
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            @endif
        @else
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-user-plus"></i>
                        Daftar
                    </a>
                @endif
            </div>
        @endauth
    </div>

    <!-- Footer -->
    <div class="welcome-footer">
        <div class="footer-badge">
            <i class="fas fa-shield-alt"></i>
            <span>Platform Aman & Terpercaya</span>
        </div>
        <p class="footer-text">© 2024 RT 04 Mudung Laut. All rights reserved.</p>
    </div>
</div>
@endsection