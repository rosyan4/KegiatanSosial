@extends('admin.layouts.app')

@section('title', 'Detail Kehadiran - ' . $activity->title)

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold">Detail Kehadiran</h1>

        <div>
            <div class="btn-group me-2">
                @if(Route::has('admin.attendance.manualCheckIn'))
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manualCheckinModal">
                    <i class="fas fa-user-plus me-1"></i> Check-in Manual
                </button>
                @endif

                @if(Route::has('admin.attendance.markAttendance'))
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                    <i class="fas fa-edit me-1"></i> Catat Kehadiran
                </button>
                @endif

                @if($activity->isCompleted() && Route::has('admin.attendance.exportAttendance'))
                <a href="{{ route('admin.attendance.exportAttendance', $activity) }}" class="btn btn-success">
                    <i class="fas fa-download me-1"></i> Export
                </a>
                @endif
            </div>

            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ACTIVITY INFO --}}
    <div class="card shadow-sm rounded-3 mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>{{ $activity->title }}</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="130">Kategori:</th>
                            <td>
                                <span class="badge px-3 py-2"
                                      style="background-color: {{ $activity->category->color }}; color:white;">
                                    {{ $activity->category->name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal:</th>
                            <td>{{ $activity->start_date->format('d F Y H:i') }} – {{ $activity->end_date->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi:</th>
                            <td>{{ $activity->location }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="130">Status:</th>
                            <td>
                                @if($activity->isCompleted())
                                    <span class="badge bg-success px-3 py-2">Selesai</span>
                                @elseif($activity->isOngoing())
                                    <span class="badge bg-warning text-dark px-3 py-2">Berlangsung</span>
                                @else
                                    <span class="badge bg-info text-dark px-3 py-2">Akan Datang</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tipe:</th>
                            <td>
                                <span class="badge bg-{{ $activity->type === 'umum' ? 'info text-dark' : 'warning text-dark' }} px-3 py-2">
                                    {{ ucfirst($activity->type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Konfirmasi:</th>
                            <td>
                                <span class="badge bg-{{ $activity->requires_attendance_confirmation ? 'info text-dark' : 'secondary' }} px-3 py-2">
                                    {{ $activity->requires_attendance_confirmation ? 'Diperlukan' : 'Tidak Diperlukan' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- KONFIRMASI KEHADIRAN --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-3">
                <div class="card-header fw-bold">
                    <i class="fas fa-clipboard-check me-2"></i> Konfirmasi Kehadiran
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr style="background-color:white; color:black;">
                                    <th>Peserta</th>
                                    <th>Status</th>
                                    <th>Waktu Konfirmasi</th>
                                    <th>Tamu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($confirmations as $confirmation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-muted me-2 fa-lg"></i>
                                            <div>
                                                <span class="fw-bold">{{ $confirmation->user->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $confirmation->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @php
                                            $color = $confirmation->status === 'hadir' ? 'success'
                                                    : ($confirmation->status === 'tidak_hadir' ? 'danger' : 'warning');
                                            $label = str_replace('_',' ', ucfirst($confirmation->status));
                                        @endphp
                                        <span class="badge bg-{{ $color }} px-3 py-2">{{ $label }}</span>
                                        @if($confirmation->is_verified)
                                            <br>
                                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Terverifikasi</small>
                                        @endif
                                    </td>

                                    <td>{{ $confirmation->confirmed_at ? $confirmation->confirmed_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ $confirmation->number_of_guests > 0 ? $confirmation->number_of_guests . ' orang' : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                        <p class="mb-0">Tidak ada data konfirmasi.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- LOG CHECK-IN/OUT --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-3">
                <div class="card-header fw-bold">
                    <i class="fas fa-history me-2"></i> Log Check-in/out
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr style="background-color:white; color:black;">
                                    <th>Peserta</th>
                                    <th>Status</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Durasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-muted me-2 fa-lg"></i>
                                            <div>
                                                <span class="fw-bold">{{ $log->user->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $log->status === 'hadir' ? 'success'
                                                               : ($log->status === 'terlambat' ? 'warning text-dark' : 'danger') }} px-3 py-2">
                                            {{ ucfirst($log->status) }}
                                        </span>

                                        @if($log->is_verified)
                                            <br>
                                            <small class="text-success"><i class="fas fa-check-circle me-1"></i> Terverifikasi</small>
                                        @endif
                                    </td>

                                    <td>{{ $log->check_in_time ? $log->check_in_time->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ $log->check_out_time ? $log->check_out_time->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ $log->check_out_time && $log->check_in_time ? $log->getDurationFormatted() : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-history fa-2x mb-2"></i>
                                        <p>Belum ada log.</p>
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

</div>

{{-- MODAL EDIT KONFIRMASI (tidak diubah logikanya) --}}
@if(Route::has('admin.attendance.updateConfirmation'))
<div class="modal fade" id="editConfirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editConfirmationForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-1"></i> Edit Konfirmasi</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_confirmation_id" name="confirmation_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Peserta:</label>
                        <p class="fw-bold mb-0" id="edit_user_name"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="hadir">Hadir</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                            <option value="mungkin">Mungkin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Tamu:</label>
                        <input type="number" class="form-control" id="edit_number_of_guests" name="number_of_guests" min="0" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan:</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Simpan Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    @if(Route::has('admin.attendance.updateConfirmation'))
    $('#editConfirmationModal').on('show.bs.modal', function (event) {
        const btn = $(event.relatedTarget);
        const confirmationId = btn.data('confirmation-id');
        const userName = btn.data('user-name');
        const currentStatus = btn.data('current-status');
        const notes = btn.data('current-notes');
        const guests = btn.data('current-guests');

        const modal = $(this);
        modal.find('#edit_user_name').text(userName);
        modal.find('#edit_status').val(currentStatus);
        modal.find('#edit_notes').val(notes ?? '');
        modal.find('#edit_number_of_guests').val(guests ?? 0);
        modal.find('#edit_confirmation_id').val(confirmationId);

        const url = '{{ route("admin.attendance.updateConfirmation", "__id__") }}'.replace("__id__", confirmationId);
        modal.find('#editConfirmationForm').attr('action', url);
    });

    $('.modal').on('hidden.bs.modal', function () {
        const form = $(this).find('form')[0];
        if (form) form.reset();
    });
    @endif
});
</script>
@endpush

@endsection
