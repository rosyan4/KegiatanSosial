<div class="d-flex flex-column flex-shrink-0 p-3 sidebar-content">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-4 text-decoration-none">
            <div class="sidebar-logo">
                <i class="fas fa-home"></i>
            </div>
            <div class="sidebar-title">
                <span class="fs-4 fw-bold">Admin Panel</span>
            </div>
        </a>
    </div>
    
    <hr class="sidebar-divider">
    
    <!-- Navigation Menu -->
    <ul class="nav nav-pills flex-column mb-auto sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span class="nav-text">Kegiatan</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <span class="nav-text">Kategori</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.proposals.index') }}" class="nav-link {{ request()->routeIs('admin.proposals.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <span class="nav-text">Proposal</span>
                @if($pendingCount = \App\Models\ActivityProposal::pending()->count())
                    <span class="sidebar-badge">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <span class="nav-text">Kehadiran</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.documentations.index') }}" class="nav-link {{ request()->routeIs('admin.documentations.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-camera"></i>
                </div>
                <span class="nav-text">Dokumentasi</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <span class="nav-text">Notifikasi</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span class="nav-text">Manajemen User</span>
            </a>
        </li>
    </ul>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer mt-auto">
        <hr class="sidebar-divider">
        <div class="d-flex align-items-center text-muted">
            <i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>
            <small>Sistem Aktif</small>
        </div>
    </div>
</div>

<style>
    /* Sidebar Content Styles */
    .sidebar-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 24px 0 !important;
    }

    /* Sidebar Header */
    .sidebar-header {
        padding: 0 24px 16px 24px;
    }

    .sidebar-logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }

    .sidebar-logo i {
        color: var(--white);
        font-size: 18px;
    }

    .sidebar-title span {
        color: var(--white);
        font-family: 'Poppins', sans-serif;
        display: block;
        line-height: 1.2;
    }

    .sidebar-title small {
        font-size: 11px;
        opacity: 0.7;
        font-weight: 500;
    }

    /* Sidebar Divider */
    .sidebar-divider {
        border-color: rgba(255, 255, 255, 0.15) !important;
        margin: 16px 24px;
        opacity: 0.5;
    }

    /* Navigation Styles */
    .sidebar-nav {
        padding: 0 16px;
    }

    .sidebar-nav .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        padding: 14px 16px !important;
        margin: 4px 8px !important;
        border-radius: 12px;
        display: flex;
        align-items: center;
        font-size: 14.5px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
        background: transparent;
    }

    .sidebar-nav .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: linear-gradient(90deg, rgba(249,115,22,0.2) 0%, rgba(249,115,22,0.1) 100%);
        transition: width 0.3s ease;
    }

    .sidebar-nav .nav-link:hover {
        background: rgba(249, 115, 22, 0.15) !important;
        color: var(--white) !important;
        transform: translateX(4px);
    }

    .sidebar-nav .nav-link:hover::before {
        width: 100%;
    }

    .sidebar-nav .nav-link.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
        color: var(--white) !important;
        box-shadow: 0 8px 16px rgba(249, 115, 22, 0.3);
        transform: translateX(4px);
    }

    .sidebar-nav .nav-link.active::after {
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

    /* Navigation Icons */
    .nav-icon {
        width: 22px;
        margin-right: 14px;
        font-size: 17px;
        text-align: center;
        position: relative;
        z-index: 1;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s;
    }

    .sidebar-nav .nav-link.active .nav-icon,
    .sidebar-nav .nav-link:hover .nav-icon {
        color: var(--white);
        transform: scale(1.1);
    }

    .nav-text {
        position: relative;
        z-index: 1;
        flex: 1;
    }

    /* Badge Styles */
    .sidebar-badge {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: var(--white);
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 12px;
        min-width: 20px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        position: relative;
        z-index: 1;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }
        50% {
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.5);
        }
        100% {
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }
    }

    /* Sidebar Footer */
    .sidebar-footer {
        padding: 0 24px;
    }

    .sidebar-footer small {
        font-size: 11px;
        font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .sidebar-content {
            padding: 20px 0 !important;
        }
        
        .sidebar-header {
            padding: 0 20px 16px 20px;
        }
        
        .sidebar-nav {
            padding: 0 12px;
        }
        
        .sidebar-nav .nav-link {
            padding: 12px 14px !important;
            margin: 3px 6px !important;
            font-size: 14px;
        }
        
        .nav-icon {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }
        
        .sidebar-logo {
            width: 36px;
            height: 36px;
        }
        
        .sidebar-logo i {
            font-size: 16px;
        }
        
        .sidebar-title span {
            font-size: 18px;
        }
    }

    /* Smooth Transitions */
    .sidebar-nav .nav-link,
    .nav-icon,
    .sidebar-badge {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Active State Enhancements */
    .sidebar-nav .nav-link.active {
        position: relative;
        z-index: 2;
    }

    /* Hover Effects for Non-active Items */
    .sidebar-nav .nav-link:not(.active):hover {
        background: rgba(255, 255, 255, 0.08) !important;
    }
</style>