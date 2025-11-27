<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Manajemen Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #fb923c;
            --secondary: #6b7280;
            --accent: #ef4444;
            --success-color: #10b981;
            --success-light: #34d399;
            --danger-color: #ef4444;
            --danger-light: #f87171;
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
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 24px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.06);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.12), 0 10px 16px rgba(0, 0, 0, 0.08);
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
        }

        /* Navbar Styling - Modern Flat Design */
        .navbar {
            background: var(--primary) !important;
            box-shadow: var(--shadow-md);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: none;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            color: #ffffff !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: translateX(3px);
        }

        .navbar-brand i {
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover i {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 600;
            border-radius: 10px;
            padding: 0.625rem 1.25rem !important;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.95) !important;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .nav-link i {
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-xl);
            border-radius: 14px;
            padding: 0.625rem;
            margin-top: 0.625rem;
            min-width: 240px;
            background-color: #ffffff;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 0.75rem 1.125rem;
            font-weight: 600;
            font-size: 0.925rem;
            transition: all 0.2s ease;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .dropdown-item i {
            width: 24px;
            text-align: center;
            font-size: 1rem;
            color: var(--primary);
        }

        .dropdown-item:hover {
            background: #fff7ed;
            color: var(--primary);
            transform: translateX(3px);
        }

        .dropdown-item.text-danger i {
            color: var(--danger-color);
        }

        .dropdown-item.text-danger:hover {
            background: #fef2f2;
            color: var(--danger-color) !important;
        }

        .dropdown-divider {
            margin: 0.625rem 0;
            border-color: var(--border-light);
            opacity: 0.6;
        }

        /* Sidebar Styling */
        .sidebar {
            min-height: calc(100vh - 60px);
            background-color: var(--sidebar-bg);
            border-right: 2px solid var(--border-light);
            padding: 2rem 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        /* Main Content Area */
        .ms-sm-auto {
            background-color: var(--content-bg);
            min-height: calc(100vh - 60px);
        }

        .px-4.py-4 {
            padding: 2.5rem 3rem !important;
        }

        /* Alert Styling - Flat Design */
        .alert {
            border: 2px solid;
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            font-weight: 600;
            padding: 1.125rem 1.5rem;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
        }

        .alert i {
            font-size: 1.35rem;
            margin-right: 1rem;
            padding: 0.625rem;
            border-radius: 10px;
            width: 44px;
            height: 44px;
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
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-color: var(--danger-color);
        }

        .alert-danger i {
            background-color: var(--danger-color);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-close {
            filter: none;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg) scale(1.15);
        }

        /* Card Hover Effect */
        .card {
            border: 2px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            background-color: var(--card-bg);
            box-shadow: var(--shadow-sm);
        }

        .card-hover {
            transition: all 0.3s ease;
            background-color: var(--card-bg);
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 2px solid var(--border-light);
            font-weight: 700;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            padding: 1.25rem 1.5rem;
        }

        /* Badge Styling - Flat Design */
        .badge {
            border-radius: 8px;
            padding: 0.45em 0.85em;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: 0.3px;
        }

        .badge-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
        }

        .badge-success {
            background: var(--success-color);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .badge-danger {
            background: var(--danger-color);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .badge-warning {
            background: var(--warning-color);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .badge-info {
            background: var(--info-color);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
        }

        .badge-attendance {
            font-size: 0.825em;
            font-weight: 600;
            padding: 0.45em 0.85em;
        }

        /* Buttons Enhancement - Flat Design */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            transition: all 0.3s ease;
            border: none;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            border: 2px solid var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.3);
            color: #ffffff;
        }

        .btn-success {
            background: var(--success-color);
            color: #ffffff;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: #ffffff;
        }

        .btn-danger {
            background: var(--danger-color);
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            color: #ffffff;
        }

        .btn-warning {
            background: var(--warning-color);
            color: #ffffff;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-info {
            background: var(--info-color);
            color: #ffffff;
        }

        .btn-info:hover {
            background: #0891b2;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        }

        /* Table Styling - Flat Design */
        .table {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border-light);
        }

        .table thead {
            background: var(--primary);
            color: #ffffff;
        }

        .table thead th {
            border: none;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 1.25rem 1.5rem;
            font-family: 'Outfit', sans-serif;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.005);
        }

        .table tbody td {
            padding: 1.125rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
            font-weight: 500;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        .table-striped tbody tr:nth-of-type(odd):hover {
            background: #f1f5f9;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid var(--border-color);
            padding: 0.925rem 1.375rem;
            transition: all 0.3s ease;
            font-weight: 500;
            background-color: #ffffff;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            background-color: #ffffff;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            letter-spacing: 0.2px;
        }

        /* Page Headers */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        h1 {
            color: var(--primary);
            font-size: 2.25rem;
        }

        h2 {
            font-size: 1.875rem;
        }

        h3 {
            font-size: 1.625rem;
        }

        /* List Groups */
        .list-group-item {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 0.625rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .list-group-item:hover {
            border-color: var(--primary);
            background: #fff7ed;
            transform: translateX(6px);
            box-shadow: var(--shadow-md);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .sidebar {
                min-height: auto;
                border-right: none;
                border-bottom: 2px solid var(--border-light);
            }

            .px-4.py-4 {
                padding: 2rem 1.5rem !important;
            }

            .dropdown-menu {
                min-width: 220px;
            }

            h1 {
                font-size: 1.875rem;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Loading State */
        .fade {
            transition: opacity 0.3s ease-in-out;
        }

        /* Selection Color */
        ::selection {
            background: rgba(249, 115, 22, 0.2);
            color: var(--text-primary);
        }

        /* Utility Classes */
        .text-primary {
            color: var(--primary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .border-primary {
            border-color: var(--primary) !important;
        }

        .bg-primary {
            background: var(--primary) !important;
        }

        .bg-light-primary {
            background: #fff7ed !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-home me-2"></i>Portal Warga
            </a>
            
            <div class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-id-card me-2"></i>Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4 py-4">
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
    @stack('scripts')
</body>
</html>