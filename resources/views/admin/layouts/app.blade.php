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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #64748b;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --sidebar: #1e293b;
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-700: #334155;
            --gray-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
            font-size: 15px;
        }

        /* ============================================
           SIDEBAR
        ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--sidebar);
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 4px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: var(--white);
        }

        /* ============================================
           MAIN CONTENT
        ============================================ */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ============================================
           NAVBAR
        ============================================ */
        .navbar {
            background: var(--white) !important;
            border-bottom: 1px solid var(--gray-200);
            padding: 16px 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar .navbar-toggler {
            border: 1px solid var(--gray-300);
            padding: 8px 12px;
        }

        .navbar .nav-link {
            color: var(--gray-700) !important;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .navbar .nav-link:hover {
            background: var(--gray-100);
            color: var(--primary) !important;
        }

        .navbar .nav-link i {
            font-size: 16px;
            margin-right: 6px;
        }

        .navbar .dropdown-menu {
            border: 1px solid var(--gray-200);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 8px;
            min-width: 200px;
            padding: 6px;
        }

        .navbar .dropdown-item {
            padding: 10px 16px;
            font-size: 14px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
            color: var(--gray-700);
            display: flex;
            align-items: center;
        }

        .navbar .dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 14px;
            margin-right: 8px;
            color: var(--gray-700);
        }

        .navbar .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .navbar .dropdown-item:hover i {
            color: var(--primary);
        }

        .navbar .dropdown-item:last-child {
            margin-bottom: 0;
        }

        .navbar .dropdown-divider {
            margin: 6px 0;
            border-color: var(--gray-200);
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
           STAT CARDS
        ============================================ */
        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .stat-card .card-body {
            padding: 20px;
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
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
        }

        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-info { color: var(--info) !important; }
        .text-gray-800 { color: var(--gray-900) !important; font-weight: 700; }
        .text-gray-300 { color: var(--gray-300) !important; }

        /* ============================================
           CARDS
        ============================================ */
        .card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 16px 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-header h6 {
            color: var(--gray-900);
            font-weight: 600;
            font-size: 15px;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .card-body {
            padding: 20px;
        }

        /* ============================================
           TABLES
        ============================================ */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            color: var(--gray-700);
            font-size: 14px;
        }

        .table thead {
            background: var(--gray-100);
        }

        .table thead th {
            border: none;
            color: var(--gray-900);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            font-family: 'Poppins', sans-serif;
        }

        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 500;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr {
            transition: all 0.2s;
        }

        .table-hover tbody tr:hover {
            background: var(--gray-50);
        }

        .table-bordered {
            border: 1px solid var(--gray-200);
        }

        /* ============================================
           BADGES
        ============================================ */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .bg-success { background-color: var(--success) !important; }
        .bg-secondary { background-color: var(--secondary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .bg-warning { background-color: var(--warning) !important; }
        .bg-info { background-color: var(--info) !important; }
        .bg-danger { background-color: var(--danger) !important; }

        /* ============================================
           BUTTONS
        ============================================ */
        .btn {
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn i {
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(30, 64, 175, 0.3);
            color: var(--white);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning);
            color: var(--white);
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        }

        .btn-info {
            background: var(--info);
            color: var(--white);
        }

        .btn-info:hover {
            background: #0891b2;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(6, 182, 212, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
            color: var(--white);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 13px;
        }

        /* ============================================
           ALERTS
        ============================================ */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 14px;
            border-left: 4px solid;
        }

        .alert i {
            font-size: 18px;
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: var(--danger);
        }

        .btn-close {
            padding: 8px;
            margin-left: auto;
        }

        /* ============================================
           CONTENT SPACING
        ============================================ */
        .container-fluid.p-4 {
            padding: 24px 32px !important;
        }

        /* ============================================
           FORM CONTROLS
        ============================================ */
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid var(--gray-300);
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 6px;
            font-size: 13px;
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
            border-top: 1px solid var(--gray-200);
            opacity: 1;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .container-fluid.p-4 {
                padding: 16px !important;
            }

            .stat-card {
                margin-bottom: 16px;
            }

            .card-body {
                padding: 16px;
            }

            .navbar {
                padding: 12px 16px;
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
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-700);
        }

        /* ============================================
           PAGE TITLE
        ============================================ */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--gray-900);
        }

        h1 { font-size: 28px; margin-bottom: 8px; }
        h2 { font-size: 24px; }
        h3 { font-size: 20px; }
        h4 { font-size: 18px; }
        h5 { font-size: 16px; }
        h6 { font-size: 14px; }

        /* ============================================
           SPACING ADJUSTMENTS
        ============================================ */
        .mb-1 { margin-bottom: 8px !important; }
        .mb-2 { margin-bottom: 12px !important; }
        .mb-3 { margin-bottom: 16px !important; }
        .mb-4 { margin-bottom: 24px !important; }
        .mb-5 { margin-bottom: 32px !important; }

        .mt-1 { margin-top: 8px !important; }
        .mt-2 { margin-top: 12px !important; }
        .mt-3 { margin-top: 16px !important; }
        .mt-4 { margin-top: 24px !important; }
        .mt-5 { margin-top: 32px !important; }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
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
        });
    </script>
    
    @stack('scripts')
</body>
</html>