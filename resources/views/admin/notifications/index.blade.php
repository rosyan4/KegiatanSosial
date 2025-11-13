@extends('admin.layouts.app')

@section('title', 'Manajemen Notifikasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bell me-2"></i>Manajemen Notifikasi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Buat Notifikasi
            </a>
            @if(Route::has('admin.notifications.bulkRetry'))
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bulkRetryModal">
                <i class="fas fa-redo me-2"></i> Retry Gagal
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bell fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Terkirim</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sent'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Gagal</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Web</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Notification::where('channel', 'web')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-globe fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Email</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Notification::where('channel', 'email')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-envelope fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="d-grid">
                    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Buat Notifikasi Baru
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="d-grid">
                    @if(Route::has('admin.notifications.sendActivityReminders'))
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#sendRemindersModal">
                        <i class="fas fa-calendar-check me-2"></i> Kirim Pengingat Kegiatan
                    </button>
                    @else
                    <button type="button" class="btn btn-warning" disabled>
                        <i class="fas fa-calendar-check me-2"></i> Kirim Pengingat Kegiatan
                    </button>
                    @endif
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="d-grid">
                    @if(Route::has('admin.notifications.sendInvitationReminders'))
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#sendInvitationRemindersModal">
                        <i class="fas fa-envelope me-2"></i> Kirim Pengingat Undangan
                    </button>
                    @else
                    <button type="button" class="btn btn-info" disabled>
                        <i class="fas fa-envelope me-2"></i> Kirim Pengingat Undangan
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Table -->
<div class="card">
    <div class="card-body">
        @if(Route::has('admin.notifications.bulkRetry'))
        <form id="bulkActionForm" method="POST" action="{{ route('admin.notifications.bulkRetry') }}">
            @csrf
        @else
        <form id="bulkActionForm" method="POST" action="#">
            @csrf
        @endif
        
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            @if(Route::has('admin.notifications.bulkRetry'))
                            <th width="30">
                                <input type="checkbox" id="selectAll">
                            </th>
                            @endif
                            <th width="60">#</th>
                            <th>Penerima</th>
                            <th>Judul</th>
                            <th>Tipe</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notification)
                        <tr>
                            @if(Route::has('admin.notifications.bulkRetry'))
                            <td>
                                @if($notification->status === 'failed' && $notification->canBeRetried())
                                <input type="checkbox" name="notifications[]" value="{{ $notification->id }}" 
                                       class="notification-checkbox">
                                @else
                                <input type="checkbox" disabled>
                                @endif
                            </td>
                            @endif
                            <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ $notification->user->name ?? 'System' }}</div>
                                        <small class="text-muted">{{ $notification->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong class="d-block">{{ $notification->title }}</strong>
                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                    {{ $notification->message }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $notification->type }}</span>
                                @if($notification->activity)
                                <br>
                                <small class="text-muted">{{ $notification->activity->title }}</small>
                                @endif
                            </td>
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
                                @if($notification->scheduled_at && $notification->scheduled_at > now())
                                <br>
                                <small class="text-muted">
                                    {{ $notification->scheduled_at->format('d/m H:i') }}
                                </small>
                                @endif
                            </td>
                            <td>
                                {{ $notification->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $notification->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.notifications.show', $notification) }}" 
                                       class="btn btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($notification->status === 'failed' && $notification->canBeRetried() && Route::has('admin.notifications.retry'))
                                    <form action="{{ route('admin.notifications.retry', $notification) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" title="Coba Kirim Ulang"
                                                onclick="return confirm('Coba kirim ulang notifikasi ini?')">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if(Route::has('admin.notifications.destroy'))
                                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Route::has('admin.notifications.bulkRetry') ? 9 : 8 }}" class="text-center py-4">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada notifikasi.</p>
                                <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Buat Notifikasi Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Action Buttons -->
            @if(Route::has('admin.notifications.bulkRetry'))
            <div id="bulkActions" class="mt-3 p-3 bg-light rounded" style="display: none;">
                <div class="d-flex align-items-center">
                    <span class="me-3" id="selectedCount">0 notifikasi dipilih</span>
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="fas fa-redo me-2"></i> Retry Selected
                    </button>
                    <button type="button" id="clearSelection" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                </div>
            </div>
            @endif
        </form>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Menampilkan {{ $notifications->firstItem() ?? 0 }} - {{ $notifications->lastItem() ?? 0 }} dari {{ $notifications->total() }} notifikasi
            </div>
            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Send Reminders Modal -->
@if(Route::has('admin.notifications.sendActivityReminders'))
<div class="modal fade" id="sendRemindersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Pengingat Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.notifications.sendActivityReminders') }}" id="sendRemindersForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="activity_id" class="form-label">Pilih Kegiatan</label>
                        <select class="form-select" id="activity_id" name="activity_id" required>
                            <option value="">Pilih kegiatan...</option>
                            @foreach(\App\Models\Activity::upcoming()->published()->get() as $activity)
                                <option value="{{ $activity->id }}">
                                    {{ $activity->title }} ({{ $activity->start_date->format('d/m/Y H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="minutes_before" class="form-label">Waktu Pengingat (menit sebelum)</label>
                        <input type="number" class="form-control" id="minutes_before" name="minutes_before" 
                               value="60" min="1" max="10080" required>
                        <div class="form-text">Berapa menit sebelum kegiatan dimulai pengingat dikirim</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pengingat akan dikirim ke semua peserta yang diundang (khusus) atau semua warga (umum).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pengingat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Send Invitation Reminders Modal -->
@if(Route::has('admin.notifications.sendInvitationReminders'))
<div class="modal fade" id="sendInvitationRemindersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Pengingat Undangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Aksi ini akan mengirim pengingat kepada semua warga yang memiliki undangan tertunda untuk kegiatan dalam 2 hari ke depan.
                </div>
                
                <div class="mb-3">
                    <strong>Estimasi Pengiriman:</strong>
                    <ul class="mt-2">
                        <li>Undangan tertunda: {{ \App\Models\Invitation::pending()->valid()->whereHas('activity', function($q) {
                            $q->where('start_date', '<=', now()->addDays(2));
                        })->count() }}</li>
                        <li>Channel: Web Notification</li>
                        <li>Waktu: Sekarang</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.notifications.sendInvitationReminders') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" 
                            onclick="return confirm('Yakin ingin mengirim pengingat undangan?')">
                        Kirim Pengingat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Bulk Retry Modal -->
@if(Route::has('admin.notifications.bulkRetry'))
<div class="modal fade" id="bulkRetryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retry Notifikasi Gagal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aksi ini akan mencoba mengirim ulang semua notifikasi yang gagal dan dapat di-retry.
                </div>
                
                <div class="mb-3">
                    <strong>Statistik Notifikasi Gagal:</strong>
                    <ul class="mt-2">
                        <li>Total gagal: {{ $stats['failed'] ?? 0 }}</li>
                        <li>Dapat di-retry: {{ \App\Models\Notification::failed()->where('attempts', '<', 3)->count() }}</li>
                        <li>Maks attempt: 3x</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.notifications.bulkRetry') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="notifications" value="all">
                    <button type="submit" class="btn btn-warning" 
                            onclick="return confirm('Yakin ingin mencoba ulang semua notifikasi yang gagal?')">
                        <i class="fas fa-redo me-2"></i> Retry Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Bulk selection functionality
        @if(Route::has('admin.notifications.bulkRetry'))
        $('#selectAll').change(function() {
            $('.notification-checkbox').prop('checked', this.checked);
            updateBulkActions();
        });

        $('.notification-checkbox').change(function() {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            }
            updateBulkActions();
        });

        function updateBulkActions() {
            const selectedCount = $('.notification-checkbox:checked').length;
            if (selectedCount > 0) {
                $('#selectedCount').text(selectedCount + ' notifikasi dipilih');
                $('#bulkActions').show();
            } else {
                $('#bulkActions').hide();
            }
        }

        $('#clearSelection').click(function() {
            $('.notification-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateBulkActions();
        });

        // Bulk form submission
        $('#bulkActionForm').submit(function(e) {
            const selectedCount = $('.notification-checkbox:checked').length;
            if (selectedCount === 0) {
                e.preventDefault();
                alert('Pilih minimal satu notifikasi terlebih dahulu.');
                return false;
            }
            
            return confirm('Yakin ingin mencoba ulang ' + selectedCount + ' notifikasi yang gagal?');
        });
        @endif

        // Send reminders form validation
        $('#sendRemindersForm').submit(function() {
            const activityId = $('#activity_id').val();
            const minutesBefore = $('#minutes_before').val();
            
            if (!activityId) {
                alert('Pilih kegiatan terlebih dahulu.');
                return false;
            }
            
            if (minutesBefore < 1 || minutesBefore > 10080) {
                alert('Waktu pengingat harus antara 1 - 10080 menit.');
                return false;
            }
            
            return confirm('Yakin ingin mengirim pengingat kegiatan?');
        });
    });
</script>
@endpush