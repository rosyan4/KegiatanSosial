@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 mb-0">Notifikasi Saya</h1>

    @if($notifications->whereNull('read_at')->count() > 0)
        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Daftar Notifikasi</h5>
    </div>

    <div class="card-body p-0">

        @if($notifications->count() > 0)

        <div class="list-group list-group-flush">

            @foreach($notifications as $notification)
            <div class="list-group-item list-group-item-action d-flex align-items-start py-3
                {{ $notification->isUnread() ? 'bg-light border-start border-4 border-primary' : '' }}"
                
                onclick="handleNotificationClick({{ $notification->id }}, {{ $notification->isUnread() ? 'true' : 'false' }})"
                data-notification-id="{{ $notification->id }}"
                style="cursor:pointer;"
            >

                {{-- LEFT --}}
                <div class="flex-grow-1">

                    <div class="d-flex align-items-center mb-1">

                        {{-- ICON --}}
                        @switch($notification->type)
                            @case('activity_reminder')
                                <i class="fas fa-clock text-warning me-2"></i>
                                @break

                            @case('new_activity')
                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                @break

                            @case('invitation')
                                <i class="fas fa-handshake text-purple me-2"></i>
                                @break

                            @default
                                <i class="fas fa-bell text-primary me-2"></i>
                        @endswitch

                        {{-- TITLE --}}
                        <strong class="text-dark me-2">{{ $notification->title }}</strong>

                        {{-- UNREAD --}}
                        @if($notification->isUnread())
                            <span class="badge bg-danger">Baru</span>
                        @endif
                    </div>

                    {{-- MESSAGE --}}
                    <p class="text-muted small mb-2">
                        {{ $notification->message }}
                    </p>

                    {{-- ACTIVITY --}}
                    @if(optional($notification->activity)->title)
                        <div class="small mb-2">
                            <span class="badge bg-primary mb-1">
                                <i class="fas fa-calendar me-1"></i>{{ $notification->activity->title }}
                            </span>

                            @if($notification->activity->start_date)
                                <span class="badge bg-success ms-1 mb-1">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $notification->activity->start_date->translatedFormat('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- META --}}
                    <div class="small text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $notification->created_at->translatedFormat('d M Y H:i') }}

                        <span class="mx-2">•</span>

                        <i class="fas fa-broadcast-tower me-1"></i>
                        {{ $notification->channel }}

                        @if(isset($notification->data['minutes_before']))
                            <span class="badge bg-warning text-dark ms-2">
                                <i class="fas fa-hourglass-half me-1"></i>
                                {{ $notification->data['minutes_before'] }} menit sebelum
                            </span>
                        @endif
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="ps-3 text-end">
                    @if($notification->isUnread())
                        <small class="text-primary fw-semibold">Klik untuk baca</small>
                    @else
                        <small class="text-muted">
                            <i class="fas fa-check text-success me-1"></i> Dibaca
                        </small>
                    @endif
                </div>

            </div>
            @endforeach

        </div>

        {{-- PAGINATION --}}
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>

        @else

        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
            <h5 class="text-muted mb-1">Tidak ada notifikasi</h5>
            <p class="text-muted small">Anda belum memiliki notifikasi saat ini.</p>
        </div>

        @endif

    </div>
</div>


{{-- SCRIPT --}}
<script>
    function handleNotificationClick(notificationId, isUnread) {
        if (isUnread) markNotificationAsRead(notificationId);
    }

    async function markNotificationAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            if (!data.success) return;

            const el = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (!el) return;

            el.classList.remove('bg-light', 'border-start', 'border-primary');

            const badge = el.querySelector('.badge.bg-danger');
            if (badge) badge.remove();

            const status = el.querySelector('.text-primary');
            if (status)
                status.outerHTML = `<small class="text-muted"><i class="fas fa-check text-success me-1"></i>Dibaca</small>`;

        } catch (e) {
            console.error(e);
        }
    }
</script>

@endsection
