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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
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
            --sidebar-width: 260px;
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
           NAVBAR - Mobile First
        ============================================ */
        .top-navbar {
            background: var(--primary) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.2rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.4rem;
            border-radius: 8px;
        }

        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #ffffff;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            margin-right: 1rem;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .nav-link-user {
            font-weight: 600;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            color: rgba(255, 255, 255, 0.95) !important;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .nav-link-user:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link-user i {
            margin-right: 0.4rem;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            min-width: 200px;
            position: absolute !important;
            z-index: 1050;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            color: var(--primary);
            margin-right: 0.5rem;
        }

        .dropdown-item:hover {
            background: #fff7ed;
            color: var(--primary);
        }

        .dropdown-item button {
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

        .dropdown-divider {
            margin: 0.5rem 0;
        }

        /* ============================================
           SIDEBAR - Mobile Collapsible
        ============================================ */
        .sidebar-wrapper {
            position: fixed;
            top: 60px;
            left: -260px;
            width: var(--sidebar-width);
            height: calc(100vh - 60px);
            background-color: var(--dark);
            box-shadow: 8px 0 24px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            z-index: 1020;
            overflow-y: auto;
        }

        .sidebar-wrapper.active {
            left: 0;
        }

        .sidebar-wrapper::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
        }

        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .sidebar {
            padding: 1.5rem 1rem;
        }

        .sidebar-header {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.85rem 1rem;
            margin: 0.35rem 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link i {
            width: 22px;
            margin-right: 0.75rem;
            font-size: 1rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .nav-link:hover {
            background: rgba(249, 115, 22, 0.15);
            color: var(--white);
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: var(--white);
            font-weight: 700;
        }

        .sidebar .nav-link.active i {
            color: var(--white);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 60px;
            left: 0;
            width: 100%;
            height: calc(100vh - 60px);
            background: rgba(0, 0, 0, 0.5);
            z-index: 1010;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ============================================
           MAIN CONTENT
        ============================================ */
        .main-content {
            margin-left: 0;
            min-height: calc(100vh - 60px);
            background: #f8f9fa;
        }

        .content-wrapper {
            padding: 1.5rem 1rem;
        }

        /* ============================================
           STAT CARDS
        ============================================ */
        .stat-card {
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }

        .stat-card .card-body {
            padding: 1.25rem;
        }

        .border-left-primary {
            border-left: 4px solid var(--primary) !important;
        }

        .border-left-success {
            border-left: 4px solid var(--success) !important;
        }

        .border-left-warning {
            border-left: 4px solid var(--warning) !important;
        }

        .border-left-info {
            border-left: 4px solid var(--info) !important;
        }

        .text-xs {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-family: 'Outfit', sans-serif;
        }

        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-info { color: var(--info) !important; }
        .text-gray-800 { color: var(--gray-900) !important; font-weight: 700; font-size: 1.75rem; }
        .text-gray-300 { color: var(--gray-300) !important; }

        /* ============================================
           CARDS
        ============================================ */
        .card {
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: #fff7ed;
            border-bottom: 2px solid rgba(249, 115, 22, 0.2);
            padding: 1rem 1.25rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-header h6 {
            color: var(--gray-900);
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* ============================================
           TABLES - Mobile Responsive
        ============================================ */
        .table-responsive {
            border-radius: 12px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: var(--white);
            border: 2px solid var(--gray-200);
        }

        .table {
            margin-bottom: 0;
            color: #1a1a1a;
            font-size: 0.9rem;
            width: 100%;
        }

        .table thead {
            background: #fed7aa;
        }

        .table thead th {
            border: none;
            color: #1a1a1a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 0.9rem 1rem;
            font-family: 'Outfit', sans-serif;
            white-space: nowrap;
            border-bottom: 2px solid #fb923c;
        }

        .table tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 500;
            white-space: nowrap;
            color: var(--gray-700);
            background: var(--white);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:nth-child(even) td {
            background: var(--gray-50);
        }

        .table-hover tbody tr:hover td {
            background: #fff7ed;
        }

        .table td .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.75em;
        }

        .table td .btn {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            margin: 0.15rem;
        }

        /* ============================================
           BADGES
        ============================================ */
        .badge {
            padding: 0.4em 0.75em;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        .bg-success { background: var(--success) !important; }
        .bg-secondary { background: var(--secondary) !important; }
        .bg-primary { background: var(--primary) !important; }
        .bg-warning { background: var(--warning) !important; }
        .bg-info { background: var(--info) !important; }
        .bg-danger { background: var(--danger) !important; }

        /* ============================================
           BUTTONS - Touch Friendly
        ============================================ */
        .btn {
            border-radius: 8px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn i {
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        .btn-success:hover {
            background: #16a34a;
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning);
            color: var(--white);
        }

        .btn-warning:hover {
            background: #ca8a04;
        }

        .btn-info {
            background: var(--info);
            color: var(--white);
        }

        .btn-info:hover {
            background: #0284c7;
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #dc2626;
            color: var(--white);
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.85rem;
            min-height: 36px;
        }

        /* ============================================
           ALERTS
        ============================================ */
        .alert {
            border: 2px solid;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .alert i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            min-width: 24px;
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
            padding: 0.5rem;
            margin-left: auto;
            opacity: 0.6;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* ============================================
           FORM CONTROLS - Touch Friendly
        ============================================ */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--gray-300);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            background: var(--white);
            min-height: 44px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
            background: var(--white);
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* ============================================
           UTILITIES
        ============================================ */
        .font-weight-bold {
            font-weight: 700 !important;
        }

        hr {
            border-top: 2px solid var(--gray-200);
            opacity: 1;
        }

        /* ============================================
           HEADERS
        ============================================ */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--gray-900);
        }

        h1 { font-size: 1.75rem; margin-bottom: 1rem; }
        h2 { font-size: 1.5rem; }
        h3 { font-size: 1.25rem; }
        h4 { font-size: 1.1rem; }
        h5 { font-size: 1rem; }
        h6 { font-size: 0.95rem; }

        /* ============================================
           DESKTOP - Show Sidebar Permanently
        ============================================ */
        @media (min-width: 992px) {
            .sidebar-wrapper {
                left: 0;
                position: fixed;
                top: 0;
                height: 100vh;
                box-shadow: 8px 0 24px rgba(0, 0, 0, 0.1);
            }

            .sidebar-toggle {
                display: none;
            }

            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: var(--sidebar-width);
            }

            .top-navbar {
                margin-left: var(--sidebar-width);
            }
        }

        /* ============================================
           MOBILE RESPONSIVE
        ============================================ */
        @media (max-width: 991px) {
            body {
                font-size: 14px;
            }

            .content-wrapper {
                padding: 1rem 0.75rem;
            }

            .stat-card .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 0.85rem 1rem;
            }

            .card-header h6 {
                font-size: 0.95rem;
            }

            .card-body {
                padding: 1rem;
            }

            h1 { font-size: 1.5rem; }
            h2 { font-size: 1.3rem; }
            h3 { font-size: 1.15rem; }

            .text-gray-800 {
                font-size: 1.5rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th {
                padding: 0.75rem 0.75rem;
                font-size: 0.7rem;
            }

            .table tbody td {
                padding: 0.75rem 0.75rem;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }

            .btn-sm {
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }

            .alert {
                padding: 0.85rem;
                font-size: 0.85rem;
            }

            /* Stack buttons vertically on mobile */
            .btn-group-mobile {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-group-mobile .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 0.75rem 0.5rem;
            }

            .navbar-brand {
                font-size: 1.1rem;
            }

            h1 { font-size: 1.35rem; }

            .card-header {
                font-size: 0.9rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.6rem 0.5rem;
                font-size: 0.8rem;
            }

            .text-gray-800 {
                font-size: 1.35rem;
            }

            .stat-card .card-body {
                padding: 0.85rem;
            }
        }

        /* ============================================
           SCROLLBAR
        ============================================ */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
            body {
                background: white;
            }

            .sidebar-wrapper,
            .top-navbar,
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
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-3">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-shield-alt"></i>
                <span>Admin Panel</span>
            </a>
            
            <div class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link-user dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
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
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar-wrapper" id="sidebar">
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-compass me-2"></i>Menu Admin
            </div>
            @include('admin.layouts.sidebar')
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            });

            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close sidebar when clicking menu item on mobile
            if (window.innerWidth < 992) {
                const sidebarLinks = sidebar.querySelectorAll('.nav-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
            }

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);

            // Confirm delete actions
            $('form[action*="destroy"]').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    form.off('submit').submit();
                }
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
        });
    </script>
    
    @stack('scripts')
</body>
</html>