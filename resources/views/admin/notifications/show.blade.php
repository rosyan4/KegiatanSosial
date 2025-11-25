@extends('admin.layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bell me-2"></i>Detail Notifikasi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Notification Details -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $notification->title }}</h4>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-muted">Pesan:</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $notification->message }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="120">Tipe:</th>
                                <td>
                                    <span class="badge bg-info">{{ $notification->type }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Channel:</th>
                                <td>
                                    @php
                                        $channelColors = [
                                            'web' => 'primary',
                                            'email' => 'success',
                                            'whatsapp' => 'success'
                                        ];
                                        $channelIcons = [
                                            'web' => 'fas fa-globe',
                                            'email' => 'fas fa-envelope',
                                            'whatsapp' => 'fab fa-whatsapp'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $channelColors[$notification->channel] ?? 'secondary' }}">
                                        <i class="{{ $channelIcons[$notification->channel] ?? 'fas fa-bell' }} me-1"></i>
                                        {{ ucfirst($notification->channel) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'sent' => 'success',
                                            'failed' => 'danger',
                                            'read' => 'info'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'fas fa-clock',
                                            'sent' => 'fas fa-check',
                                            'failed' => 'fas fa-times',
                                            'read' => 'fas fa-eye'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$notification->status] ?? 'secondary' }}">
                                        <i class="{{ $statusIcons[$notification->status] ?? 'fas fa-bell' }} me-1"></i>
                                        {{ ucfirst($notification->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="120">Attempts:</th>
                                <td>{{ $notification->attempts ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Max Attempts:</th>
                                <td>{{ $notification->max_attempts ?? 3 }}</td>
                            </tr>
                            <tr>
                                <th>Penerima:</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle text-muted me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ $notification->user->name ?? 'System' }}</div>
                                            <small class="text-muted">{{ $notification->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($notification->activity)
                <div class="mt-4">
                    <h6 class="text-muted">Terkait Kegiatan:</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">{{ $notification->activity->title }}</h6>
                            <p class="card-text mb-1">
                                <i class="fas fa-calendar me-2"></i>
                                {{ $notification->activity->start_date->format('d F Y H:i') }}
                            </p>
                            <p class="card-text mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $notification->activity->location }}
                            </p>
                            @if(Route::has('admin.activities.show'))
                            <a href="{{ route('admin.activities.show', $notification->activity) }}" 
                               class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-external-link-alt me-1"></i> Lihat Kegiatan
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timeline & Actions -->
    <div class="col-lg-4">
        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $notification->created_at ? 'active' : '' }}">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Dibuat</h6>
                            <small class="text-muted">
                                {{ $notification->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>

                    @if($notification->scheduled_at)
                    <div class="timeline-item {{ $notification->scheduled_at ? 'active' : '' }}">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6>Dijadwalkan</h6>
                            <small class="text-muted">
                                {{ $notification->scheduled_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    @endif

                    @if($notification->sent_at)
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>Terkirim</h6>
                            <small class="text-muted">
                                {{ $notification->sent_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    @endif

                    @if($notification->read_at)
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6>Dibaca</h6>
                            <small class="text-muted">
                                {{ $notification->read_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    @endif

                    @if($notification->failed_at)
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6>Gagal</h6>
                            <small class="text-muted">
                                {{ $notification->failed_at->format('d M Y H:i') }}
                            </small>
                            @if($notification->failure_reason)
                            <br>
                            <small class="text-danger">{{ $notification->failure_reason }}</small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($notification->status === 'failed' && $notification->canBeRetried() && Route::has('admin.notifications.retry'))
                    <form action="{{ route('admin.notifications.retry', $notification) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100" 
                                onclick="return confirm('Coba kirim ulang notifikasi ini?')">
                            <i class="fas fa-redo me-2"></i> Coba Ulang
                        </button>
                    </form>
                    @endif

                    @if(Route::has('admin.notifications.destroy'))
                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Yakin ingin menghapus notifikasi ini?')">
                            <i class="fas fa-trash me-2"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i>Metadata</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="100"><strong>ID:</strong></td>
                        <td>#{{ $notification->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diupdate:</strong></td>
                        <td>{{ $notification->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($notification->sent_at)
                    <tr>
                        <td><strong>Terkirim:</strong></td>
                        <td>{{ $notification->sent_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($notification->read_at)
                    <tr>
                        <td><strong>Dibaca:</strong></td>
                        <td>{{ $notification->read_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-content {
        padding-left: 10px;
    }
    .timeline-item:not(.active) .timeline-marker {
        background-color: #dee2e6 !important;
    }
    .timeline-item:not(.active) .timeline-content {
        color: #6c757d;
    }
</style>
@endpush