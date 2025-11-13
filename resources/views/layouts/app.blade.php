<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Manajemen Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary-color: #0f172a;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --success-light: #34d399;
            --danger-color: #ef4444;
            --danger-light: #f87171;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --sidebar-bg: #ffffff;
            --content-bg: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            --text-primary: #0f172a;
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

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
            box-shadow: var(--shadow-md);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            color: #ffffff !important;
        }

        .navbar-brand i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            padding: 0.5rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .navbar-brand:hover i {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
            transform: scale(1.05);
            transition: all 0.3s ease;
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
            color: var(--primary-color);
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .dropdown-item.text-danger i {
            color: var(--danger-color);
        }

        .dropdown-item.text-danger:hover {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
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

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            font-weight: 600;
            padding: 1.125rem 1.5rem;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            border-left: 5px solid;
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
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left-color: var(--success-color);
        }

        .alert-success i {
            background-color: var(--success-color);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left-color: var(--danger-color);
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
            border-color: var(--primary-color);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 2px solid var(--border-light);
            font-weight: 700;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            padding: 1.25rem 1.5rem;
        }

        /* Badge Styling */
        .badge {
            border-radius: 8px;
            padding: 0.45em 0.85em;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: 0.3px;
        }

        .badge-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3);
        }

        .badge-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .badge-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .badge-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #0891b2 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
        }

        .badge-attendance {
            font-size: 0.825em;
            font-weight: 600;
            padding: 0.45em 0.85em;
        }

        /* Buttons Enhancement */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            transition: all 0.3s ease;
            border: none;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(30, 64, 175, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
            color: #ffffff;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: #ffffff;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            color: #ffffff;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #0891b2 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.3);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        }

        /* Table Styling */
        .table {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border-light);
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
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
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
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
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
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
            color: var(--primary-color);
            font-size: 2.25rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
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
            background: linear-gradient(to bottom, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
        }

        /* Loading State */
        .fade {
            transition: opacity 0.3s ease-in-out;
        }

        /* Selection Color */
        ::selection {
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.2) 0%, rgba(59, 130, 246, 0.2) 100%);
            color: var(--text-primary);
        }

        /* Utility Classes */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        }

        .bg-light-primary {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%) !important;
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