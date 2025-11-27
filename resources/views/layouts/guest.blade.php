<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ====== GLOBAL STYLES ====== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #fb923c;
            --secondary: #6b7280;
            --accent: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            background: #f8f9fa;
            color: var(--text-primary);
        }

        /* ====== WELCOME PAGE LAYOUT ====== */
        body.welcome-page {
            background: #f8f9fa;
            position: relative;
        }

        .welcome-container {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 4rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ====== HERO SECTION ====== */
        .welcome-hero {
            text-align: center;
            margin-bottom: 4rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .welcome-logo {
            width: 100px;
            height: 100px;
            background: #f97316;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.75rem;
            margin: 0 auto 2.5rem;
            border: none;
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.2);
            color: white;
            animation: scaleIn 0.6s ease-out;
        }

        .welcome-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
            color: #1a1a1a;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .welcome-location {
            font-size: 1.15rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        .welcome-location i {
            color: #f97316;
        }

        .welcome-tagline {
            font-size: 1.25rem;
            color: #4b5563;
            line-height: 1.8;
            max-width: 700px;
            margin: 0 auto;
            font-weight: 500;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        /* ====== FEATURES SECTION ====== */
        .welcome-features {
            margin-bottom: 4rem;
            width: 100%;
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #f97316;
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            border-color: #f97316;
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(249, 115, 22, 0.15);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: #fff7ed;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            background: #f97316;
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon i {
            font-size: 2.5rem;
            color: #f97316;
            transition: color 0.4s ease;
        }

        .feature-card:hover .feature-icon i {
            color: white;
        }

        .feature-card h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            font-size: 1rem;
            color: #6b7280;
            line-height: 1.6;
            margin: 0;
        }

        /* ====== CTA SECTION ====== */
        .welcome-cta {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .cta-buttons {
            display: flex;
            gap: 1.25rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-lg {
            padding: 1.125rem 3rem;
            font-size: 1.125rem;
            font-weight: 700;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.875rem;
            letter-spacing: 0.02em;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: #f97316;
            border: 2px solid #f97316;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #ea580c;
            border-color: #ea580c;
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(249, 115, 22, 0.3);
            color: #ffffff;
        }

        .btn-primary:active {
            transform: translateY(-2px);
        }

        .btn-outline {
            background: white;
            border: 2px solid #e5e7eb;
            color: #1a1a1a;
        }

        .btn-outline:hover {
            background: #1a1a1a;
            border-color: #1a1a1a;
            transform: translateY(-4px);
            color: white;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .btn-outline:active {
            transform: translateY(-2px);
        }

        /* ====== FOOTER SECTION ====== */
        .welcome-footer {
            text-align: center;
            color: #6b7280;
            animation: fadeInUp 0.8s ease-out 0.7s both;
        }

        .footer-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: white;
            padding: 0.875rem 1.75rem;
            border-radius: 50px;
            border: 2px solid #e5e7eb;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #1a1a1a;
            transition: all 0.3s ease;
        }

        .footer-badge:hover {
            border-color: #f97316;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.15);
        }

        .footer-badge i {
            color: #f97316;
            font-size: 1.25rem;
        }

        .footer-text {
            font-size: 0.95rem;
            color: #9ca3af;
            margin: 0;
        }

        /* ====== ANIMATIONS ====== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ====== FORM PAGE LAYOUT (Login/Register) ====== */
        body.form-page {
            background: #f8f9fa;
            position: relative;
        }

        .form-layout {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
        }

        /* ====== CARD STYLES ====== */
        .guest-card {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 1;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
        }

        .card-header {
            background: #f97316;
            border-bottom: none;
            padding: 3rem 2.5rem 2.5rem;
            text-align: center;
            position: relative;
        }

        .card-header h4 {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            margin: 0.75rem 0 0 0;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 2.5rem;
            background: #ffffff;
        }

        .card-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.75rem;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        /* ====== FORM ELEMENTS ====== */
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.625rem;
            font-size: 0.9rem;
            letter-spacing: 0.01em;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.875rem 1.125rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #ffffff;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            background: #ffffff;
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .form-control.is-invalid:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #ef4444;
            font-weight: 600;
        }

        /* ====== BUTTONS ====== */
        .btn {
            border-radius: 10px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            letter-spacing: 0.01em;
            border: none;
        }

        .btn-link {
            color: #f97316;
            font-weight: 600;
            text-decoration: none;
            padding: 0;
        }

        .btn-link:hover {
            color: #ea580c;
            text-decoration: none;
        }

        /* ====== ALERTS ====== */
        .alert {
            border-radius: 12px;
            border: 1px solid transparent;
            padding: 1.125rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            border-left: 4px solid;
        }

        .alert-info {
            background: #fff7ed;
            border-color: #f97316;
            color: #c2410c;
        }

        .alert-success {
            background: #f0fdf4;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-warning {
            background: #fffbeb;
            border-color: #f59e0b;
            color: #92400e;
        }

        .alert-danger {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }

        /* ====== CHECKBOX & LINKS ====== */
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: #f97316;
            border-color: #f97316;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            border-color: #f97316;
        }

        .form-check-label {
            margin-left: 0.625rem;
            cursor: pointer;
            user-select: none;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        a {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        a:hover {
            color: #ea580c;
            text-decoration: none;
        }

        /* ====== DIVIDER ====== */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 600;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid #e2e8f0;
        }

        .divider span {
            padding: 0 1.25rem;
        }

        /* ====== BACK LINK ====== */
        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: #1f2937;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1.25rem;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s ease;
            z-index: 10;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .back-link:hover {
            background: #f97316;
            border-color: #f97316;
            transform: translateX(-5px);
            color: white;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 3rem 1.5rem;
            }

            .welcome-title {
                font-size: 2.5rem;
            }

            .welcome-tagline {
                font-size: 1.125rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .feature-card {
                padding: 2rem 1.5rem;
            }

            .feature-icon {
                width: 70px;
                height: 70px;
            }

            .feature-icon i {
                font-size: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }

            .btn-lg {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .welcome-container {
                padding: 2rem 1.25rem;
            }

            .card-body {
                padding: 2rem 1.75rem;
            }

            .card-header {
                padding: 2.5rem 1.75rem 2rem;
            }

            .card-header h4 {
                font-size: 1.5rem;
            }

            .btn {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
            }

            .back-link {
                top: 1rem;
                left: 1rem;
                padding: 0.625rem 1rem;
                font-size: 0.85rem;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .welcome-logo {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .card-icon {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }

            .btn-lg {
                padding: 1rem 2.5rem;
                font-size: 1.05rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="@if(Request::is('/')) welcome-page @else form-page @endif">
    @if(Request::is('/'))
        <!-- Welcome Page Layout -->
        @yield('content')
    @else
        <!-- Form Pages Layout (Login/Register) -->
        <div class="form-layout">
            @if(!Request::is('/'))
                <a href="{{ url('/') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            @endif

            <div class="guest-card">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>@yield('title', config('app.name', 'Laravel'))</h4>
                        <p>@yield('subtitle', 'Sistem Informasi Kegiatan Sosial RT')</p>
                    </div>
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>