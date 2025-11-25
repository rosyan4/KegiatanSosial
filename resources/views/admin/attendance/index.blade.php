@extends('admin.layouts.app')

@section('title', 'Manajemen Kehadiran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kehadiran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        @if(Route::has('admin.attendance.reports'))
        <a href="{{ route('admin.attendance.reports') }}" class="btn btn-info">
            <i class="fas fa-chart-bar me-2"></i> Laporan Kehadiran
        </a>
        @endif
    </div>
</div>

<!-- Activities with Attendance Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kegiatan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Konfirmasi</th>
                        <th>Check-in/out</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    @php
                        $confirmations = $activity->attendanceConfirmations;
                        $logs = $activity->attendanceLogs;
                        
                        $confirmedCount = $confirmations->count();
                        $presentCount = $confirmations->where('status', 'hadir')->count();
                        $absentCount = $confirmations->where('status', 'tidak_hadir')->count();
                        $maybeCount = $confirmations->where('status', 'mungkin')->count();
                        
                        $checkedInCount = $logs->whereNotNull('check_in_time')->count();
                        $checkedOutCount = $logs->whereNotNull('check_out_time')->count();
                        $verifiedCount = $logs->where('is_verified', true)->count();
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $activity->title }}</strong>
                            @if($activity->requires_attendance_confirmation)
                                <br>
                                <small class="text-info">
                                    <i class="fas fa-clipboard-check me-1"></i>
                                    Perlu konfirmasi
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $activity->category->color }}; color: white;">
                                {{ $activity->category->name }}
                            </span>
                        </td>
                        <td>
                            {{ $activity->start_date->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">
                                {{ $activity->getStatusLabel() }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex flex-column small">
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $presentCount }} Hadir
                                </span>
                                <span class="text-danger">
                                    <i class="fas fa-times-circle me-1"></i>
                                    {{ $absentCount }} Tidak hadir
                                </span>
                                <span class="text-warning">
                                    <i class="fas fa-question-circle me-1"></i>
                                    {{ $maybeCount }} Mungkin
                                </span>
                                <span class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $confirmedCount }} Total
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column small">
                                <span class="text-primary">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    {{ $checkedInCount }} Check-in
                                </span>
                                <span class="text-info">
                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    {{ $checkedOutCount }} Check-out
                                </span>
                                <span class="text-success">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ $verifiedCount }} Terverifikasi
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($activity->isCompleted())
                                <span class="badge bg-success">Selesai</span>
                            @elseif($activity->isOngoing())
                                <span class="badge bg-warning">Berlangsung</span>
                            @elseif($activity->isUpcoming())
                                <span class="badge bg-info">Akan datang</span>
                            @else
                                <span class="badge bg-secondary">{{ $activity->getStatusLabel() }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if(Route::has('admin.attendance.showActivity'))
                                <a href="{{ route('admin.attendance.showActivity', $activity) }}" class="btn btn-info" title="Detail Kehadiran">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                @if(($activity->isOngoing() || $activity->isCompleted()) && Route::has('admin.attendance.manualCheckIn'))
                                <button type="button" class="btn btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#manualCheckinModal"
                                        data-activity-id="{{ $activity->id }}"
                                        data-activity-title="{{ $activity->title }}"
                                        title="Check-in Manual">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                @endif

                                @if($activity->isCompleted() && Route::has('admin.attendance.exportAttendance'))
                                <a href="{{ route('admin.attendance.exportAttendance', $activity) }}" class="btn btn-success" title="Export Data">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data kehadiran.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>

<!-- Manual Check-in Modal -->
@if(Route::has('admin.attendance.manualCheckIn'))
<div class="modal fade" id="manualCheckinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check-in Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.attendance.manualCheckIn', ['activity' => ':activity_id']) }}" id="manualCheckinForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="activity_id" id="modal_activity_id">
                    
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Pilih Peserta <span class="text-danger">*</span></label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Pilih peserta...</option>
                            @foreach(\App\Models\User::warga()->active()->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="check_in_time" class="form-label">Waktu Check-in</label>
                        <input type="datetime-local" class="form-control" id="check_in_time" name="check_in_time" 
                               value="{{ now()->format('Y-m-d\TH:i') }}">
                        <div class="form-text">Kosongkan untuk menggunakan waktu sekarang</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Check-in</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Manual Check-in Modal
        @if(Route::has('admin.attendance.manualCheckIn'))
        $('#manualCheckinModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const activityId = button.data('activity-id');
            const activityTitle = button.data('activity-title');
            
            const modal = $(this);
            modal.find('.modal-title').text('Check-in Manual - ' + activityTitle);
            modal.find('#modal_activity_id').val(activityId);
            
            // Update form action
            const form = modal.find('#manualCheckinForm');
            const action = form.attr('action').replace(':activity_id', activityId);
            form.attr('action', action);
        });

        // Reset form when modal is hidden
        $('#manualCheckinModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });
        @endif
    });
</script>
@endpush