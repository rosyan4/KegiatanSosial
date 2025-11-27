<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Sistem Manajemen Kegiatan</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #fb923c;
            --secondary: #6b7280;
            --success: #22c55e;
            --info: #0ea5e9;
            --warning: #eab308;
            --danger: #ef4444;
            --dark: #1f2937;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            color: #1a1a1a;
            line-height: 1.6;
            font-size: 15px;
        }

        /* ============================================
           SIDEBAR - FLAT DESIGN
        ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--dark);
            box-shadow: 8px 0 24px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 14px 24px;
            margin: 6px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            font-size: 14.5px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .sidebar .nav-link i {
            width: 22px;
            margin-right: 14px;
            font-size: 17px;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(249, 115, 22, 0.15);
            color: var(--white);
            transform: translateX(4px);
        }

        .sidebar .nav-link:hover i {
            color: var(--primary-light);
            transform: scale(1.1);
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 8px 16px rgba(249, 115, 22, 0.3);
            transform: translateX(4px);
            font-weight: 700;
        }

        .sidebar .nav-link.active i {
            color: var(--white);
        }

        .sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            right: -16px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 32px;
            background: var(--white);
            border-radius: 4px 0 0 4px;
        }

        /* ============================================
           MAIN CONTENT
        ============================================ */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            position: relative;
            background: #f8f9fa;
        }

        /* ============================================
           NAVBAR - FLAT DESIGN
        ============================================ */
        .navbar {
            background: var(--white) !important;
            border: none;
            border-bottom: 2px solid var(--gray-200);
            padding: 16px 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar .navbar-toggler {
            border: 2px solid var(--gray-300);
            padding: 10px 14px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .navbar .navbar-toggler:hover {
            background: var(--gray-100);
            border-color: var(--primary);
        }

        .navbar .nav-link {
            color: var(--gray-700) !important;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .navbar .nav-link:hover {
            background: var(--primary);
            color: var(--white) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .navbar .nav-link i {
            font-size: 17px;
            margin-right: 8px;
        }

        .navbar .dropdown-menu {
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 14px;
            margin-top: 12px;
            min-width: 220px;
            padding: 10px;
            background: var(--white);
        }

        .navbar .dropdown-item {
            padding: 12px 18px;
            font-size: 14px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            margin-bottom: 4px;
            color: var(--gray-700);
            display: flex;
            align-items: center;
        }

        .navbar .dropdown-item i {
            width: 22px;
            text-align: center;
            font-size: 15px;
            margin-right: 10px;
            color: var(--primary);
            transition: all 0.3s;
        }

        .navbar .dropdown-item:hover {
            background: #fff7ed;
            color: var(--primary);
            transform: translateX(4px);
        }

        .navbar .dropdown-item:hover i {
            color: var(--primary);
        }

        .navbar .dropdown-divider {
            margin: 10px 0;
            border-color: var(--gray-200);
            opacity: 0.5;
        }

        .navbar .dropdown-item button {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 0;
            color: inherit;
            font: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        /* ============================================
           STAT CARDS - FLAT DESIGN
        ============================================ */
        .stat-card {
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
            transform: translateY(-8px);
            border-color: var(--primary);
        }

        .stat-card .card-body {
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .border-left-primary {
            border-left: 5px solid var(--primary) !important;
        }

        .border-left-success {
            border-left: 5px solid var(--success) !important;
        }

        .border-left-warning {
            border-left: 5px solid var(--warning) !important;
        }

        .border-left-info {
            border-left: 5px solid var(--info) !important;
        }

        .text-xs {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Outfit', sans-serif;
        }

        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-info { color: var(--info) !important; }
        .text-gray-800 { color: var(--gray-900) !important; font-weight: 700; font-size: 32px; }
        .text-gray-300 { color: var(--gray-300) !important; }

        /* ============================================
           CARDS - FLAT DESIGN
        ============================================ */
        .card {
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            margin-bottom: 28px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #fff7ed;
            border-bottom: 2px solid rgba(249, 115, 22, 0.2);
            padding: 20px 24px;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-header h6 {
            color: var(--gray-900);
            font-weight: 700;
            font-size: 16px;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.3px;
        }

        .card-body {
            padding: 24px;
        }

        /* ============================================
           TABLES - FLAT DESIGN
        ============================================ */
        .table-responsive {
            border-radius: 12px;
            overflow: auto;
            position: relative;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: var(--white);
            border: 2px solid var(--gray-200);
        }

        .table {
            margin-bottom: 0;
            color: #1a1a1a;
            font-size: 14px;
            min-width: 600px;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead {
            background: #fed7aa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table thead th {
            border: none;
            color: #1a1a1a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 16px 18px;
            font-family: 'Outfit', sans-serif;
            white-space: nowrap;
            border-bottom: 2px solid #fb923c;
        }

        .table tbody td {
            padding: 16px 18px;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 500;
            white-space: nowrap;
            color: var(--gray-700);
            background: var(--white);
            transition: all 0.2s ease;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:nth-child(even) td {
            background: var(--gray-50);
        }

        .table-hover tbody tr:hover td {
            background: #fff7ed;
            color: var(--gray-900);
        }

        .table-bordered {
            border: 2px solid var(--gray-200);
        }

        .table td .badge {
            font-size: 11px;
            padding: 6px 12px;
        }

        .table td .btn {
            padding: 6px 12px;
            font-size: 12px;
            margin: 2px;
        }

        /* ============================================
           BADGES - FLAT DESIGN
        ============================================ */
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .bg-success { 
            background: var(--success) !important; 
        }
        .bg-secondary { 
            background: var(--secondary) !important; 
        }
        .bg-primary { 
            background: var(--primary) !important; 
        }
        .bg-warning { 
            background: var(--warning) !important; 
        }
        .bg-info { 
            background: var(--info) !important; 
        }
        .bg-danger { 
            background: var(--danger) !important; 
        }

        /* ============================================
           BUTTONS - FLAT DESIGN
        ============================================ */
        .btn {
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn i {
            font-size: 15px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
            color: var(--white);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        .btn-success:hover {
            background: #16a34a;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning);
            color: var(--white);
        }

        .btn-warning:hover {
            background: #ca8a04;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(234, 179, 8, 0.3);
        }

        .btn-info {
            background: var(--info);
            color: var(--white);
        }

        .btn-info:hover {
            background: #0284c7;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
            color: var(--white);
        }

        .btn-sm {
            padding: 8px 18px;
            font-size: 13px;
        }

        /* ============================================
           ALERTS - FLAT DESIGN
        ============================================ */
        .alert {
            border: 2px solid;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert i {
            font-size: 20px;
            margin-right: 14px;
            width: 22px;
            text-align: center;
        }

        .alert-success {
            background: #d1fae5;
            color: #166534;
            border-color: var(--success);
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-color: var(--danger);
        }

        .btn-close {
            padding: 10px;
            margin-left: auto;
            opacity: 0.6;
            transition: all 0.3s;
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        /* ============================================
           CONTENT SPACING
        ============================================ */
        .container-fluid.p-4 {
            padding: 32px 40px !important;
        }

        /* ============================================
           FORM CONTROLS
        ============================================ */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--gray-300);
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            background: var(--white);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            background: var(--white);
        }

        .form-label {
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
            font-size: 13px;
            letter-spacing: 0.3px;
        }

        /* ============================================
           UTILITIES
        ============================================ */
        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        hr {
            border-top: 2px solid var(--gray-200);
            opacity: 1;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .container-fluid.p-4 {
                padding: 20px 16px !important;
            }

            .stat-card {
                margin-bottom: 20px;
            }

            .card-body {
                padding: 18px;
            }

            .navbar {
                padding: 14px 16px;
            }

            .text-gray-800 {
                font-size: 24px;
            }
            
            .table-responsive {
                border-radius: 8px;
                margin: 0 -16px;
                width: calc(100% + 32px);
                box-shadow: none;
                border: 1px solid var(--gray-200);
            }
            
            .table {
                min-width: 650px;
                font-size: 13px;
            }
            
            .table thead th {
                padding: 12px 14px;
                font-size: 11px;
            }
            
            .table tbody td {
                padding: 12px 14px;
                font-size: 13px;
            }
            
            .btn {
                padding: 10px 18px;
                font-size: 13px;
                width: 100%;
                justify-content: center;
                margin-bottom: 8px;
            }
            
            .btn-group .btn {
                width: auto;
                margin-bottom: 0;
            }
            
            .card-header {
                padding: 16px 18px;
            }
            
            .card-header h6 {
                font-size: 15px;
            }

            .table td .badge {
                font-size: 10px;
                padding: 4px 8px;
            }

            .table td .btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid.p-4 {
                padding: 16px 12px !important;
            }
            
            .navbar {
                padding: 12px 12px;
            }
            
            .navbar .nav-link {
                padding: 8px 12px;
                font-size: 13px;
            }
            
            .stat-card .card-body {
                padding: 18px;
            }
            
            .text-gray-800 {
                font-size: 20px;
            }
        }

        /* ============================================
           SCROLLBAR
        ============================================ */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* ============================================
           PAGE TITLE
        ============================================ */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--gray-900);
        }

        h1 { font-size: 32px; margin-bottom: 12px; }
        h2 { font-size: 26px; }
        h3 { font-size: 22px; }
        h4 { font-size: 19px; }
        h5 { font-size: 17px; }
        h6 { font-size: 15px; }

        @media (max-width: 768px) {
            h1 { font-size: 26px; }
            h2 { font-size: 22px; }
            h3 { font-size: 19px; }
            h4 { font-size: 17px; }
            h5 { font-size: 15px; }
        }

        /* ============================================
           ANIMATIONS
        ============================================ */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .stat-card {
            animation: fadeIn 0.5s ease-out;
        }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
            body {
                background: white;
            }

            .sidebar,
            .navbar,
            .btn,
            .alert {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid var(--gray-300) !important;
                background: white !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                @include('admin.layouts.sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content p-0">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i>{{ auth()->user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                                <i class="fas fa-cog"></i>Pengaturan
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt"></i>Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="container-fluid p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).alert('close');
        });
    }, 5000);

    // Mobile sidebar toggle
    $('.navbar-toggler').on('click', function() {
        $('.sidebar').toggleClass('show');
    });

    // Close sidebar on mobile when clicking outside
    $(document).on('click', function(e) {
        if ($(window).width() <= 768) {
            if (!$(e.target).closest('.sidebar, .navbar-toggler').length) {
                $('.sidebar').removeClass('show');
            }
        }
    });
    
    // Add swipe functionality for tables on mobile
    let startX;
    let scrollLeft;
    let isDown = false;
    
    $('.table-responsive').on('mousedown touchstart', function(e) {
        if ($(window).width() <= 768) {
            isDown = true;
            startX = (e.type === 'mousedown') ? e.pageX - $(this).offset().left : e.touches[0].pageX - $(this).offset().left;
            scrollLeft = $(this).scrollLeft();
        }
    });

    $('.table-responsive').on('mouseleave mouseup touchend', function() {
        isDown = false;
    });

    $('.table-responsive').on('mousemove touchmove', function(e) {
        if (!isDown) return;
        e.preventDefault();
        const x = (e.type === 'mousemove') ? e.pageX - $(this).offset().left : e.touches[0].pageX - $(this).offset().left;
        const walk = (x - startX) * 2;
        $(this).scrollLeft(scrollLeft - walk);
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });

    // Add loading state to buttons on form submit
    $('form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        // Restore button if form validation fails
        setTimeout(function() {
            if (submitBtn.prop('disabled')) {
                submitBtn.prop('disabled', false).html(originalText);
            }
        }, 3000);
    });

    // Confirm delete actions
    $('form[action*="destroy"]').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            form.off('submit').submit();
        }
    });

    // Add animation on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeIn 0.5s ease-out';
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.card, .stat-card').forEach(el => {
        observer.observe(el);
    });

    // Tooltip initialization (if using Bootstrap tooltips)
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Prevent multiple form submissions
    $('form').on('submit', function() {
        const form = $(this);
        if (form.data('submitted') === true) {
            return false;
        } else {
            form.data('submitted', true);
        }
    });

    // Auto-hide alerts with animation
    $('.alert').each(function() {
        const alert = $(this);
        setTimeout(function() {
            alert.fadeOut('slow');
        }, 5000);
    });

    // Handle responsive table scroll indicator
    $('.table-responsive').each(function() {
        const container = $(this);
        const table = container.find('table');
        
        if (table.width() > container.width()) {
            container.addClass('has-scroll');
        }
    });

    // Add active class to current menu item
    const currentUrl = window.location.href;
    $('.sidebar .nav-link').each(function() {
        if ($(this).attr('href') === currentUrl) {
            $(this).addClass('active');
        }
    });
});
    </script>
    
    @stack('scripts')
</body>
</html>