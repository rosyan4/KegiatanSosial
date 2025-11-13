@extends('layouts.app')

@section('title', $activity->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $activity->title }}</h1>
    <div>
        <span class="badge bg-{{ $activity->type === 'umum' ? 'primary' : 'warning' }} me-2">
            {{ $activity->type }}
        </span>
        <span class="badge bg-secondary">{{ $activity->category->name }}</span>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Activity Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Kegiatan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar me-2 text-primary"></i>Tanggal & Waktu</strong>
                        <p class="mb-1">{{ $activity->start_date->format('l, d F Y') }}</p>
                        <p class="text-muted">{{ $activity->start_date->format('H:i') }} - {{ $activity->end_date->format('H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-map-marker-alt me-2 text-danger"></i>Lokasi</strong>
                        <p>{{ $activity->location }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong><i class="fas fa-align-left me-2 text-info"></i>Deskripsi</strong>
                    <p class="mt-2">{{ $activity->description }}</p>
                </div>

                @if($activity->type === 'khusus' && $isInvited)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Anda diundang khusus untuk kegiatan ini.
                    @if($invitation)
                        Status undangan: <strong class="text-capitalize">{{ $invitation->status }}</strong>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Attendance Confirmation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Konfirmasi Kehadiran</h5>
            </div>
            <div class="card-body">
                @if($activity->type === 'khusus' && !$isInvited)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Kegiatan ini khusus untuk undangan tertentu.
                    </div>
                @else
                    <form action="{{ route('activities.confirm-attendance', $activity) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status Kehadiran</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="status" value="hadir" id="hadir" 
                                    {{ $userConfirmation && $userConfirmation->status === 'hadir' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="hadir">
                                    <i class="fas fa-check me-1"></i>Hadir
                                </label>

                                <input type="radio" class="btn-check" name="status" value="tidak_hadir" id="tidak_hadir"
                                    {{ $userConfirmation && $userConfirmation->status === 'tidak_hadir' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="tidak_hadir">
                                    <i class="fas fa-times me-1"></i>Tidak Hadir
                                </label>

                                <input type="radio" class="btn-check" name="status" value="mungkin" id="mungkin"
                                    {{ $userConfirmation && $userConfirmation->status === 'mungkin' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="mungkin">
                                    <i class="fas fa-question me-1"></i>Mungkin
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="number_of_guests" class="form-label">Jumlah Tamu</label>
                            <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" 
                                min="0" max="5" value="{{ $userConfirmation->number_of_guests ?? 0 }}">
                            <div class="form-text">Jumlah tamu yang akan ikut (maksimal 5 orang)</div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                placeholder="Masukkan catatan jika ada...">{{ $userConfirmation->notes ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Konfirmasi
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Attendance Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Statistik Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <h3>{{ $attendanceStats['total'] ?? 0 }}</h3>
                        <small class="text-muted">Total Konfirmasi</small>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="text-success">
                                <h5>{{ $attendanceStats['hadir'] ?? 0 }}</h5>
                                <small>Hadir</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-danger">
                                <h5>{{ $attendanceStats['tidak_hadir'] ?? 0 }}</h5>
                                <small>Tidak Hadir</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <h5>{{ $attendanceStats['mungkin'] ?? 0 }}</h5>
                                <small>Mungkin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($activity->canUserJoin(Auth::id()))
                        <button class="btn btn-outline-primary" id="checkInBtn">
                            <i class="fas fa-sign-in-alt me-1"></i>Check-In
                        </button>
                        <button class="btn btn-outline-secondary" id="checkOutBtn" disabled>
                            <i class="fas fa-sign-out-alt me-1"></i>Check-Out
                        </button>
                    @endif
                    
                    <a href="{{ route('calendar.index') }}?date={{ $activity->start_date->format('Y-m-d') }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-calendar me-1"></i>Lihat di Kalender
                    </a>
                </div>

                <div id="attendanceStatus" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInBtn = document.getElementById('checkInBtn');
    const checkOutBtn = document.getElementById('checkOutBtn');
    const statusDiv = document.getElementById('attendanceStatus');

    function updateAttendanceStatus() {
        fetch(`{{ route('activities.attendance-status', $activity) }}`)
            .then(response => response.json())
            .then(data => {
                if (data.checked_in) {
                    checkInBtn.disabled = true;
                    checkInBtn.innerHTML = '<i class="fas fa-check me-1"></i>Sudah Check-In';
                    statusDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Sudah check-in pada ${data.check_in_time}
                        </div>
                    `;
                    
                    if (data.can_check_out) {
                        checkOutBtn.disabled = false;
                    }
                }
            });
    }

    if (checkInBtn) {
        checkInBtn.addEventListener('click', function() {
            fetch(`{{ route('attendances.check-in', $activity) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAttendanceStatus();
                    alert('Check-in berhasil!');
                } else {
                    alert(data.message);
                }
            });
        });
    }

    if (checkOutBtn) {
        checkOutBtn.addEventListener('click', function() {
            fetch(`{{ route('attendances.check-out', $activity) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    checkOutBtn.disabled = true;
                    statusDiv.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Check-out berhasil! Durasi: ${data.duration}
                        </div>
                    `;
                } else {
                    alert(data.message);
                }
            });
        });
    }

    // Initial status update
    updateAttendanceStatus();
});
</script>
@endpush