@extends('layouts.app')

@section('title', 'Riwayat Kehadiran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Kehadiran</h1>
</div>

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $attendanceStats['total'] }}</h3>
                <p class="mb-0">Total Konfirmasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $attendanceStats['hadir'] }}</h3>
                <p class="mb-0">Hadir</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>{{ $attendanceStats['tidak_hadir'] }}</h3>
                <p class="mb-0">Tidak Hadir</p>
            </div>
        </div>
    </div>
</div>

<!-- Attendance History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Konfirmasi Kehadiran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Jumlah Tamu</th>
                        <th>Waktu Konfirmasi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($confirmations as $confirmation)
                    <tr>
                        <td>
                            <strong>{{ $confirmation->activity->title }}</strong>
                        </td>
                        <td>
                            {{ $confirmation->activity->start_date->format('d M Y') }}
                            <br>
                            <small class="text-muted">{{ $confirmation->activity->start_date->format('H:i') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $confirmation->activity->category->name }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $confirmation->status === 'hadir' ? 'success' : ($confirmation->status === 'tidak_hadir' ? 'danger' : 'warning') }}">
                                {{ $confirmation->status }}
                            </span>
                        </td>
                        <td>
                            @if($confirmation->number_of_guests > 0)
                                <span class="badge bg-info">{{ $confirmation->number_of_guests }} tamu</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $confirmation->confirmed_at->format('d M Y H:i') }}
                        </td>
                        <td>
                            @if($confirmation->notes)
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" 
                                    title="{{ $confirmation->notes }}">
                                    <i class="fas fa-sticky-note"></i>
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada riwayat kehadiran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $confirmations->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
@endpush