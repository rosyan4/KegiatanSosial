<div class="d-flex flex-column flex-shrink-0 p-3 text-white">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
        <i class="fas fa-home me-2"></i>
        <span class="fs-4">Admin Panel</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt me-2"></i> Kegiatan
            </a>
        </li>
        <li>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags me-2"></i> Kategori
            </a>
        </li>
        <li>
            <a href="{{ route('admin.proposals.index') }}" class="nav-link {{ request()->routeIs('admin.proposals.*') ? 'active' : '' }}">
                <i class="fas fa-lightbulb me-2"></i> Proposal
                @if($pendingCount = \App\Models\ActivityProposal::pending()->count())
                    <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check me-2"></i> Kehadiran
            </a>
        </li>
        <li>
            <a href="{{ route('admin.documentations.index') }}" class="nav-link {{ request()->routeIs('admin.documentations.*') ? 'active' : '' }}">
                <i class="fas fa-camera me-2"></i> Dokumentasi
            </a>
        </li>
        <li>
            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell me-2"></i> Notifikasi
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Manajemen User
            </a>
        </li>
    </ul>
</div>