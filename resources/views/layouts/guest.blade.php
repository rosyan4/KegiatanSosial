<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #0f172a;
            --accent: #f59e0b;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            background: var(--bg-light);
            color: var(--text-primary);
        }

        /* ====== WELCOME PAGE LAYOUT ====== */
        body.welcome-page {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            position: relative;
        }

        body.welcome-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.08) 0%, transparent 50%);
            z-index: 0;
        }

        body.welcome-page::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
            z-index: 0;
            opacity: 0.4;
        }

        .welcome-container {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 3rem 1.5rem;
        }

        .welcome-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            max-width: 1300px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        /* ====== WELCOME LEFT SECTION ====== */
        .welcome-left {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            padding: 5rem 4rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            z-index: 0;
        }

        .welcome-left-content {
            position: relative;
            z-index: 1;
        }

        .welcome-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.25rem;
            margin-bottom: 2.5rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            letter-spacing: -0.03em;
            background: linear-gradient(to right, #ffffff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-description {
            font-size: 1.1rem;
            line-height: 1.75;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .feature-list li:hover {
            transform: translateX(5px);
        }

        .feature-list li i {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(245, 158, 11, 0.1) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.25rem;
            font-size: 0.875rem;
            flex-shrink: 0;
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fbbf24;
        }

        /* ====== WELCOME RIGHT SECTION ====== */
        .welcome-right {
            padding: 5rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .welcome-right-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .welcome-right-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .welcome-right-header p {
            font-size: 1rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.6;
        }

        .welcome-footer {
            text-align: center;
            margin-top: 2.5rem;
            padding-top: 2.5rem;
            border-top: 2px solid #f1f5f9;
        }

        .welcome-footer p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .welcome-footer i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* ====== FORM PAGE LAYOUT (Login/Register) ====== */
        body.form-page {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            position: relative;
        }

        body.form-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 40%, rgba(59, 130, 246, 0.08) 0%, transparent 60%),
                radial-gradient(circle at 70% 70%, rgba(245, 158, 11, 0.06) 0%, transparent 60%);
            z-index: 0;
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
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            background: #ffffff;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            border-bottom: none;
            padding: 3rem 2.5rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
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
            color: rgba(255, 255, 255, 0.85);
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
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.75rem;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
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
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(30, 64, 175, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
            color: #ffffff;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            padding: 0;
        }

        .btn-link:hover {
            color: var(--primary-dark);
            text-decoration: none;
        }

        .btn-action {
            width: 100%;
            padding: 1rem 1.75rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            letter-spacing: 0.01em;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            box-shadow: 0 4px 14px rgba(30, 64, 175, 0.3);
        }

        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            background: white;
            border: 2px solid var(--primary);
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.3);
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
            background: #eff6ff;
            border-color: #3b82f6;
            color: #1e40af;
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
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
            border-color: var(--primary);
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
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        a:hover {
            color: var(--primary-dark);
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
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: all 0.3s ease;
            z-index: 10;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 992px) {
            .welcome-grid {
                grid-template-columns: 1fr;
                max-width: 600px;
            }

            .welcome-left {
                padding: 4rem 3rem;
            }

            .welcome-right {
                padding: 4rem 3rem;
            }

            .welcome-title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-container {
                padding: 1.5rem 1rem;
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

            .welcome-left,
            .welcome-right {
                padding: 3rem 2rem;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .welcome-description {
                font-size: 1rem;
            }

            .welcome-logo {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .card-icon {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
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