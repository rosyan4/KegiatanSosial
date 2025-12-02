<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Manajemen Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --secondary: #6b7280;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --sidebar-bg: #ffffff;
            --content-bg: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e5e7eb;
            --border-light: #f1f5f9;
            --text-primary: #1a1a1a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 24px rgba(0, 0, 0, 0.1);
            --sidebar-width: 280px;
            --navbar-height: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--content-bg);
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 15px;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: var(--primary) !important;
            box-shadow: var(--shadow-md);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: var(--navbar-height);
        }

        .navbar .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
            padding: 0;
        }

        .navbar-brand i {
            font-size: 1.25rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.4rem;
            border-radius: 8px;
        }

        /* Sidebar Toggle Button - Hidden on Desktop */
        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #ffffff;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            margin: 0;
            list-style: none;
        }

        .nav-item.dropdown {
            display: flex;
            align-items: center;
            position: relative;
        }

        .nav-link {
            font-weight: 600;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            color: rgba(255, 255, 255, 0.95) !important;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            white-space: nowrap;
            height: 42px;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link:focus {
            outline: none;
        }

        .nav-link i {
            margin-right: 0.4rem;
        }

        .nav-link .user-name {
            display: inline;
        }

        /* Dropdown Toggle - Remove default caret */
        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.3em;
            vertical-align: middle;
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-left: 0.3em solid transparent;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem !important;
            min-width: 200px;
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            transform: none !important;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
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

        .dropdown-item.text-danger i {
            color: var(--danger-color);
        }

        .dropdown-item.text-danger:hover {
            background: #fef2f2;
            color: var(--danger-color) !important;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
        }

        .dropdown-item button {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            color: inherit;
            cursor: pointer;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
        }

        /* Layout Container */
        .layout-container {
            padding-top: var(--navbar-height);
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar-col {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background-color: var(--sidebar-bg);
            border-right: 2px solid var(--border-light);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1020;
        }

        .sidebar {
            padding: 1.5rem 1rem;
        }

        .sidebar-header {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--border-light);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .sidebar-menu a i {
            width: 24px;
            margin-right: 0.75rem;
            text-align: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .sidebar-menu a:hover {
            background: #fff7ed;
            color: var(--primary);
        }

        .sidebar-menu a.active {
            background: var(--primary);
            color: #ffffff;
        }

        .sidebar-menu a.active i {
            color: #ffffff;
        }

        /* Main Content */
        .content-col {
            margin-left: var(--sidebar-width);
            min-height: calc(100vh - var(--navbar-height));
            background-color: var(--content-bg);
        }

        .content-wrapper {
            padding: 1.5rem 1rem;
        }

        /* Sidebar Overlay - Hidden by default */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--navbar-height));
            background: rgba(0, 0, 0, 0.5);
            z-index: 1010;
        }

        /* Alert */
        .alert {
            border: 2px solid;
            border-radius: 12px;
            font-weight: 600;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .alert i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            padding: 0.5rem;
            border-radius: 8px;
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-color: var(--success-color);
        }

        .alert-success i {
            background-color: var(--success-color);
            color: #ffffff;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-color: var(--danger-color);
        }

        .alert-danger i {
            background-color: var(--danger-color);
            color: #ffffff;
        }

        /* Cards */
        .card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--card-bg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1rem;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 2px solid var(--border-light);
            font-weight: 700;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            padding: 1rem 1.25rem;
            font-size: 1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Badge */
        .badge {
            border-radius: 6px;
            padding: 0.4em 0.75em;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .badge-success {
            background: var(--success-color);
            color: #ffffff;
        }

        .badge-danger {
            background: var(--danger-color);
            color: #ffffff;
        }

        .badge-warning {
            background: var(--warning-color);
            color: #ffffff;
        }

        .badge-info {
            background: var(--info-color);
            color: #ffffff;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.65rem 1.25rem;
            border: none;
            min-height: 42px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            color: #ffffff;
        }

        .btn-success {
            background: var(--success-color);
            color: #ffffff;
        }

        .btn-success:hover {
            background: #059669;
            color: #ffffff;
        }

        .btn-danger {
            background: var(--danger-color);
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: #ffffff;
        }

        .btn-warning {
            background: var(--warning-color);
            color: #ffffff;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-info {
            background: var(--info-color);
            color: #ffffff;
        }

        .btn-info:hover {
            background: #0891b2;
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.85rem;
            min-height: 36px;
        }

        /* Table */
        .table-responsive {
            border-radius: 12px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 0;
            border: 2px solid var(--border-light);
        }

        .table thead {
            background: var(--primary);
            color: #ffffff;
        }

        .table thead th {
            border: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 0.9rem 1rem;
            font-family: 'Outfit', sans-serif;
            white-space: nowrap;
        }

        .table tbody tr {
            background-color: #ffffff;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 0.75rem 1rem;
            font-weight: 500;
            background-color: #ffffff;
            font-size: 0.95rem;
            min-height: 44px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
            background-color: #ffffff;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* Headers */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--text-primary);
        }

        h1 {
            color: var(--primary);
            font-size: 1.75rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        h3 {
            font-size: 1.25rem;
        }

        h4 {
            font-size: 1.1rem;
        }

        /* List Groups */
        .list-group-item {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 0.5rem;
            font-weight: 500;
            padding: 0.9rem 1rem;
        }

        /* Utility Classes */
        .text-primary {
            color: var(--primary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Desktop: Sidebar visible, toggle hidden */
        @media (min-width: 992px) {
            .sidebar-toggle {
                display: none;
            }
        }

        /* Mobile: Sidebar as overlay */
        @media (max-width: 991px) {
            body {
                font-size: 14px;
            }

            .navbar .container-fluid {
                padding: 0 0.75rem;
            }

            .navbar-brand {
                font-size: 1rem;
                gap: 0.4rem;
            }

            .navbar-brand i {
                font-size: 1rem;
                padding: 0.35rem;
            }

            .navbar-brand span {
                display: inline;
            }

            .sidebar-toggle {
                padding: 0.4rem 0.65rem;
                margin-right: 0.5rem;
                min-width: 38px;
                height: 38px;
                font-size: 1.1rem;
            }

            .nav-link {
                font-size: 0.85rem;
                padding: 0.4rem 0.75rem !important;
                height: 38px;
            }

            .nav-link .user-name {
                display: none;
            }

            .nav-link i.fa-user-circle {
                margin-right: 0;
                font-size: 1.3rem;
            }

            .dropdown-toggle::after {
                margin-left: 0.2em;
                border-top: 0.25em solid;
                border-right: 0.25em solid transparent;
                border-left: 0.25em solid transparent;
            }

            .dropdown-menu {
                min-width: 180px;
                margin-top: 0.25rem !important;
                right: 0 !important;
                left: auto !important;
            }

            .dropdown-item {
                padding: 0.6rem 0.85rem;
                font-size: 0.85rem;
            }

            .dropdown-item i {
                width: 18px;
                font-size: 0.9rem;
            }

            .sidebar-col {
                left: -280px;
                transition: left 0.3s ease;
                box-shadow: var(--shadow-lg);
            }

            .sidebar-col.active {
                left: 0;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .content-col {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 1rem 0.75rem;
            }

            .card-header {
                padding: 0.85rem 1rem;
                font-size: 0.95rem;
            }

            .card-body {
                padding: 1rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            h2 {
                font-size: 1.3rem;
            }

            h3 {
                font-size: 1.15rem;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }

            .btn-sm {
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }

            .table thead th {
                padding: 0.75rem 0.75rem;
                font-size: 0.75rem;
            }

            .table tbody td {
                padding: 0.75rem 0.75rem;
                font-size: 0.85rem;
            }

            .alert {
                padding: 0.85rem;
                font-size: 0.85rem;
            }

            .alert i {
                font-size: 1.1rem;
                min-width: 32px;
                height: 32px;
                padding: 0.4rem;
            }

            .dropdown-menu {
                min-width: 180px;
            }

            .badge {
                font-size: 0.75rem;
                padding: 0.35em 0.65em;
            }

            .content-wrapper {
                padding: 0.75rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .navbar .container-fluid {
                padding: 0 0.5rem;
            }

            .navbar-brand {
                font-size: 0.95rem;
            }

            .navbar-brand span {
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .sidebar-toggle {
                padding: 0.35rem 0.6rem;
                margin-right: 0.4rem;
                min-width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .nav-link {
                padding: 0.35rem 0.65rem !important;
                height: 36px;
            }

            .nav-link i.fa-user-circle {
                font-size: 1.2rem;
            }

            .dropdown-toggle::after {
                margin-left: 0.15em;
                border-top: 0.22em solid;
                border-right: 0.22em solid transparent;
                border-left: 0.22em solid transparent;
            }

            .dropdown-menu {
                min-width: 160px;
                margin-top: 0.25rem !important;
                font-size: 0.8rem;
            }

            .dropdown-item {
                padding: 0.55rem 0.75rem;
                font-size: 0.8rem;
            }

            .dropdown-item i {
                width: 16px;
                font-size: 0.85rem;
                margin-right: 0.4rem;
            }

            h1 {
                font-size: 1.35rem;
            }

            .card-header {
                font-size: 0.9rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.6rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Scrollbar untuk Sidebar */
        .sidebar-col::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-col::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar-col::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        /* Scrollbar untuk Content */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        /* Selection */
        ::selection {
            background: rgba(249, 115, 22, 0.2);
            color: var(--text-primary);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-3">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Portal Warga</span>
            </a>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <span class="user-name">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-id-card"></i>Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger w-100">
                                    <i class="fas fa-sign-out-alt"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar Overlay (Mobile Only) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Layout Container -->
    <div class="layout-container">
        <!-- Sidebar Column (Fixed) -->
        <div class="sidebar-col" id="sidebar">
            <div class="sidebar">
                <div class="sidebar-header">
                    <i class="fas fa-compass me-2"></i>Menu Navigasi
                </div>
                
                <!-- Sidebar menu -->
                @include('components.sidebar')
            </div>
        </div>

        <!-- Content Column -->
        <div class="content-col">
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
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle Script (Mobile Only)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                // Toggle sidebar (mobile only)
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                });
            }

            if (sidebarOverlay) {
                // Close sidebar when clicking overlay
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }

            // Close sidebar when clicking menu item on mobile
            if (window.innerWidth < 992) {
                const sidebarLinks = sidebar.querySelectorAll('.sidebar-menu a');
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
        });
    </script>
    
    @stack('scripts')
</body>
</html>