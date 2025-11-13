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
    /* Sidebar Styles - Konsisten dengan Layout */
    .sidebar {
        background-color: #ffffff !important;
        border-right: 1px solid var(--border-color, #e2e8f0);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .sidebar .nav {
        gap: 0.25rem;
    }

    .sidebar .nav-item {
        margin-bottom: 0.125rem;
    }

    .sidebar .nav-link {
        color: #1e293b !important;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
        color: #64748b !important;
        transition: all 0.2s ease;
    }

    .sidebar .nav-link span {
        flex: 1;
        color: #1e293b !important;
    }

    .sidebar .nav-link:hover {
        background-color: #f1f5f9;
        color: var(--primary-color, #3b82f6) !important;
        transform: translateX(4px);
    }

    .sidebar .nav-link:hover i {
        color: var(--primary-color, #3b82f6) !important;
    }

    .sidebar .nav-link:hover span {
        color: var(--primary-color, #3b82f6) !important;
    }

    .sidebar .nav-link.active {
        background-color: #eff6ff;
        color: var(--primary-color, #3b82f6) !important;
        font-weight: 600;
        border-left: 4px solid var(--primary-color, #3b82f6);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    }

    .sidebar .nav-link.active i {
        color: var(--primary-color, #3b82f6) !important;
    }

    .sidebar .nav-link.active span {
        color: var(--primary-color, #3b82f6) !important;
    }

    .sidebar .badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        margin-left: auto;
    }

    .sidebar .badge.rounded-pill {
        min-width: 20px;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            border-right: none;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
        }

        .sidebar .nav-link {
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }

        .sidebar .nav-link i {
            font-size: 0.95rem;
        }
    }
</style>