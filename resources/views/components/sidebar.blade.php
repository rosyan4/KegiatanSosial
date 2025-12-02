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

<ul class="sidebar-menu">
    <li>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('activities.index') }}" class="{{ request()->routeIs('activities.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <span>Kegiatan</span>
        </a>
    </li>
    <li>
        <a href="{{ route('calendar.index') }}" class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}">
            <i class="fas fa-calendar"></i>
            <span>Kalender</span>
        </a>
    </li>
    <li>
        <a href="{{ route('attendances.my') }}" class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i>
            <span>Riwayat Hadir</span>
        </a>
    </li>
    <li>
        <a href="{{ route('invitations.index') }}" class="{{ request()->routeIs('invitations.*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i>
            <span>Undangan</span>
            @if($pendingInvitationsCount > 0)
                <span class="badge badge-danger ms-auto">{{ $pendingInvitationsCount }}</span>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ route('proposals.index') }}" class="{{ request()->routeIs('proposals.*') ? 'active' : '' }}">
            <i class="fas fa-lightbulb"></i>
            <span>Usulan Kegiatan</span>
        </a>
    </li>
    <li>
        <a href="{{ route('documentations.index') }}" class="{{ request()->routeIs('documentations.*') ? 'active' : '' }}">
            <i class="fas fa-photo-video"></i>
            <span>Dokumentasi</span>
        </a>
    </li>
    <li>
        <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span>Notifikasi</span>
        </a>
    </li>
    <li>
        <a href="{{ route('reports.attendance') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
    </li>
</ul>