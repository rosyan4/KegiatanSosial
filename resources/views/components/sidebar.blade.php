@php
// Safe way to get pending invitations count
try {
    $pendingInvitationsCount = \App\Models\Invitation::forUser(Auth::id())
        ->where('status', 'pending')
        ->count();
} catch (Exception $e) {
    $pendingInvitationsCount = 0;
}
@endphp

<div class="col-md-3 col-lg-2 sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" 
                   href="{{ route('activities.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Kegiatan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}" 
                   href="{{ route('calendar.index') }}">
                    <i class="fas fa-calendar"></i>
                    <span>Kalender</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}" 
                   href="{{ route('attendances.my') }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Riwayat Hadir</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('invitations.*') ? 'active' : '' }}" 
                   href="{{ route('invitations.index') }}">
                    <i class="fas fa-envelope"></i>
                    <span>Undangan</span>
                    @if($pendingInvitationsCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $pendingInvitationsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('proposals.*') ? 'active' : '' }}" 
                   href="{{ route('proposals.index') }}">
                    <i class="fas fa-lightbulb"></i>
                    <span>Usulan Kegiatan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('documentations.*') ? 'active' : '' }}" 
                   href="{{ route('documentations.index') }}">
                    <i class="fas fa-photo-video"></i>
                    <span>Dokumentasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" 
                   href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                   href="{{ route('reports.attendance') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Sidebar Styles - Modern Flat Design Matching Guest */
    .sidebar {
        background-color: #ffffff !important;
        border-right: 2px solid #f1f5f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .sidebar .nav {
        gap: 0.375rem;
    }

    .sidebar .nav-item {
        margin-bottom: 0.25rem;
    }

    .sidebar .nav-link {
        color: #1a1a1a !important;
        font-weight: 600;
        font-size: 0.925rem;
        padding: 0.875rem 1.25rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.875rem;
        position: relative;
        border: 2px solid transparent;
    }

    .sidebar .nav-link i {
        width: 22px;
        text-align: center;
        font-size: 1.1rem;
        color: #6b7280 !important;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link span {
        flex: 1;
        color: #1a1a1a !important;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover {
        background-color: #fff7ed;
        color: #f97316 !important;
        transform: translateX(5px);
        border-color: transparent;
    }

    .sidebar .nav-link:hover i {
        color: #f97316 !important;
        transform: scale(1.1);
    }

    .sidebar .nav-link:hover span {
        color: #f97316 !important;
    }

    .sidebar .nav-link.active {
        background-color: #fff7ed;
        color: #f97316 !important;
        font-weight: 700;
        border-left: 4px solid #f97316;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15);
        transform: translateX(2px);
    }

    .sidebar .nav-link.active i {
        color: #f97316 !important;
    }

    .sidebar .nav-link.active span {
        color: #f97316 !important;
    }

    .sidebar .badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.3rem 0.55rem;
        margin-left: auto;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .sidebar .badge.rounded-pill {
        min-width: 22px;
        text-align: center;
    }

    .sidebar .badge.bg-danger {
        background-color: #ef4444 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            border-right: none;
            border-bottom: 2px solid #f1f5f9;
        }

        .sidebar .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .sidebar .nav-link i {
            font-size: 1rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            transform: translateX(3px);
        }
    }

    /* Smooth scroll behavior */
    .sidebar .position-sticky {
        position: sticky;
        top: 76px;
        max-height: calc(100vh - 76px);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #f97316 #f1f5f9;
    }

    .sidebar .position-sticky::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar .position-sticky::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .sidebar .position-sticky::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }

    .sidebar .position-sticky::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }
</style>