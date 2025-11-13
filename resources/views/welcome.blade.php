@extends('layouts.guest')

@section('title', 'Selamat Datang')

@section('content')
<div class="welcome-container">
    <div class="welcome-grid">
        <!-- LEFT SECTION: Informasi -->
        <div class="welcome-left">
            <div class="welcome-left-content">
                <div class="welcome-logo">
                    <i class="fas fa-users"></i>
                </div>
                
                <h1 class="welcome-title">
                    Sistem Informasi<br>Kegiatan Sosial RT
                </h1>
                
                <p class="welcome-description">
                    Platform digital untuk mengelola kegiatan sosial warga RT secara modern, transparan, dan efisien.
                </p>
                
                <ul class="feature-list">
                    <li>
                        <i class="fas fa-check"></i>
                        <span>Kelola kegiatan dengan mudah</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span>Catat kehadiran digital</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span>Laporan real-time</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- RIGHT SECTION: Actions -->
        <div class="welcome-right">
            <div class="welcome-right-header">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk atau daftar untuk melanjutkan</p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-action btn-primary-gradient">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Buka Dashboard Admin</span>
                        </a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn btn-action btn-primary-gradient">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Buka Dashboard</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-action btn-primary-gradient mb-3">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </a>

                    @if (Route::has('register'))
                        <div class="divider">
                            <span>atau</span>
                        </div>

                        <a href="{{ route('register') }}" class="btn btn-action btn-outline-gradient">
                            <i class="fas fa-user-plus"></i>
                            <span>Daftar Sekarang</span>
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Footer -->
            <div class="welcome-footer">
                <p>
                    <i class="fas fa-shield-alt"></i>
                    <span>Platform aman & terpercaya</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection