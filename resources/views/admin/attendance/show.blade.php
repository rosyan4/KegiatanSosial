@extends('admin.layouts.app')

@section('title', 'Detail Kehadiran - ' . $activity->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Kehadiran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            @if(Route::has('admin.attendance.manualCheckIn'))
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manualCheckinModal">
                <i class="fas fa-user-plus me-2"></i> Check-in Manual
            </button>
            @endif
            
            @if(Route::has('admin.attendance.markAttendance'))
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                <i class="fas fa-edit me-2"></i> Catat Kehadiran
            </button>
            @endif
            
            @if($activity->isCompleted() && Route::has('admin.attendance.exportAttendance'))
            <a href="{{ route('admin.attendance.exportAttendance', $activity) }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i> Export
            </a>
            @endif
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Activity Header -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>{{ $activity->title }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">Kategori:</th>
                        <td>
                            <span class="badge" style="background-color: {{ $activity->category->color }}; color: white;">
                                {{ $activity->category->name }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td>{{ $activity->start_date->format('d F Y H:i') }} - {{ $activity->end_date->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi:</th>
                        <td>{{ $activity->location }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">Status:</th>
                        <td>
                            @if($activity->isCompleted())
                                <span class="badge bg-success">Selesai</span>
                            @elseif($activity->isOngoing())
                                <span class="badge bg-warning">Berlangsung</span>
                            @else
                                <span class="badge bg-info">Akan datang</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tipe:</th>
                        <td>
                            <span class="badge bg-{{ $activity->type === 'umum' ? 'info' : 'warning' }}">
                                {{ $activity->type }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Konfirmasi:</th>
                        <td>
                            @if($activity->requires_attendance_confirmation)
                                <span class="badge bg-info">Diperlukan</span>
                            @else
                                <span class="badge bg-secondary">Tidak diperlukan</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Konfirmasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['confirmed'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['hadir'] }}</div>
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
                            Tidak Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['tidak_hadir'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                            Mungkin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['mungkin'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-question-circle fa-2x text-gray-300"></i>
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
                            Check-in</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['checked_in'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
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
                            Terverifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Attendance Confirmations -->
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Konfirmasi Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Tamu</th>
                                @if(Route::has('admin.attendance.updateConfirmation'))
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($confirmations as $confirmation)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle text-muted me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ $confirmation->user->name }}</div>
                                            <small class="text-muted">{{ $confirmation->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'hadir' => 'success',
                                            'tidak_hadir' => 'danger',
                                            'mungkin' => 'warning'
                                        ];
                                        $statusLabels = [
                                            'hadir' => 'Hadir',
                                            'tidak_hadir' => 'Tidak Hadir',
                                            'mungkin' => 'Mungkin'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$confirmation->status] }}">
                                        {{ $statusLabels[$confirmation->status] }}
                                    </span>
                                </td>
                                <td>
                                    @if($confirmation->confirmed_at)
                                        {{ $confirmation->confirmed_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($confirmation->number_of_guests > 0)
                                        <span class="badge bg-info">{{ $confirmation->number_of_guests }} tamu</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @if(Route::has('admin.attendance.updateConfirmation'))
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editConfirmationModal"
                                            data-confirmation-id="{{ $confirmation->id }}"
                                            data-user-name="{{ $confirmation->user->name }}"
                                            data-current-status="{{ $confirmation->status }}"
                                            data-current-notes="{{ $confirmation->notes }}"
                                            data-current-guests="{{ $confirmation->number_of_guests }}"
                                            title="Edit Konfirmasi">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ Route::has('admin.attendance.updateConfirmation') ? 5 : 4 }}" class="text-center py-3">
                                    <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada konfirmasi kehadiran.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Logs -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Log Check-in/out</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Durasi</th>
                                @if(Route::has('admin.attendance.verifyAttendance') || Route::has('admin.attendance.manualCheckOut'))
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle text-muted me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ $log->user->name }}</div>
                                            <small class="text-muted">{{ $log->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $log->status === 'hadir' ? 'success' : ($log->status === 'terlambat' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                    @if($log->is_verified)
                                        <br>
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Terverifikasi
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($log->check_in_time)
                                        {{ $log->check_in_time->format('d/m/Y H:i') }}
                                        <br>
                                        <small class="text-muted">{{ $log->check_in_method }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->check_out_time)
                                        {{ $log->check_out_time->format('d/m/Y H:i') }}
                                        <br>
                                        <small class="text-muted">{{ $log->check_out_method }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->check_in_time && $log->check_out_time)
                                        <span class="badge bg-info">{{ $log->getDurationFormatted() }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @if(Route::has('admin.attendance.verifyAttendance') || Route::has('admin.attendance.manualCheckOut'))
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if(!$log->is_verified && Route::has('admin.attendance.verifyAttendance'))
                                        <form action="{{ route('admin.attendance.verifyAttendance', $log) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Verifikasi"
                                                    onclick="return confirm('Verifikasi kehadiran {{ $log->user->name }}?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($log->check_in_time && !$log->check_out_time && $activity->isOngoing() && Route::has('admin.attendance.manualCheckOut'))
                                        <form action="{{ route('admin.attendance.manualCheckOut', $log) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info" title="Check-out"
                                                    onclick="return confirm('Check-out {{ $log->user->name }}?')">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ (Route::has('admin.attendance.verifyAttendance') || Route::has('admin.attendance.manualCheckOut')) ? 6 : 5 }}" class="text-center py-3">
                                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada log check-in/out.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
            <form method="POST" action="{{ route('admin.attendance.manualCheckIn', $activity) }}">
                @csrf
                <div class="modal-body">
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

<!-- Mark Attendance Modal -->
@if(Route::has('admin.attendance.markAttendance'))
<div class="modal fade" id="markAttendanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catat Kehadiran Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.attendance.markAttendance', $activity) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mark_user_id" class="form-label">Pilih Peserta <span class="text-danger">*</span></label>
                        <select class="form-select" id="mark_user_id" name="user_id" required>
                            <option value="">Pilih peserta...</option>
                            @foreach(\App\Models\User::warga()->active()->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Kehadiran <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mark_notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="mark_notes" name="notes" rows="2" placeholder="Catatan kehadiran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kehadiran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Edit Confirmation Modal -->
@if(Route::has('admin.attendance.updateConfirmation'))
<div class="modal fade" id="editConfirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Konfirmasi Kehadiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editConfirmationForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="confirmation_id" id="edit_confirmation_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Peserta</label>
                        <p class="form-control-plaintext fw-bold" id="edit_user_name"></p>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="hadir">Hadir</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                            <option value="mungkin">Mungkin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_number_of_guests" class="form-label">Jumlah Tamu</label>
                        <input type="number" class="form-control" id="edit_number_of_guests" name="number_of_guests" min="0" value="0">
                        <div class="form-text">Jumlah tamu yang ikut (jika ada)</div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Konfirmasi</button>
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
        // Edit Confirmation Modal
        @if(Route::has('admin.attendance.updateConfirmation'))
        $('#editConfirmationModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const confirmationId = button.data('confirmation-id');
            const userName = button.data('user-name');
            const currentStatus = button.data('current-status');
            const currentNotes = button.data('current-notes');
            const currentGuests = button.data('current-guests');
            
            const modal = $(this);
            modal.find('#edit_user_name').text(userName);
            modal.find('#edit_status').val(currentStatus);
            modal.find('#edit_notes').val(currentNotes || '');
            modal.find('#edit_number_of_guests').val(currentGuests || 0);
            modal.find('#edit_confirmation_id').val(confirmationId);
            
            // Update form action
            const form = modal.find('#editConfirmationForm');
            form.attr('action', '{{ route("admin.attendance.updateConfirmation", "") }}/' + confirmationId);
        });

        // Reset forms when modals are hidden
        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });
        @endif
    });
</script>
@endpush