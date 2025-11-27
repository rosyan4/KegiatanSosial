@extends('layouts.app')

@section('title', 'Riwayat Kehadiran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Kehadiran</h1>
</div>

<!-- Attendance History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Konfirmasi Kehadiran</h5>
    </div>
    <div class="card-body">

        <!-- Statistik -->
        <div class="mb-3">
            <span class="badge bg-primary">Total: {{ $attendanceStats['total'] }}</span>
            <span class="badge bg-success">Hadir: {{ $attendanceStats['hadir'] }}</span>
            <span class="badge bg-danger">Tidak Hadir: {{ $attendanceStats['tidak_hadir'] }}</span>
        </div>

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
                        @php
                            $activity = $confirmation->activity; // bisa null jika activity dihapus
                        @endphp

                        <tr>
                            <!-- Judul -->
                            <td>
                                <strong>{{ $activity->title ?? 'Kegiatan Tidak Ada' }}</strong>
                            </td>

                            <!-- Tanggal -->
                            <td>
                                @if($activity && $activity->start_date)
                                    {{ $activity->start_date->format('d M Y') }} <br>
                                    <small class="text-muted">
                                        {{ $activity->start_date->format('H:i') }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Kategori -->
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $activity?->category?->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="badge bg-{{ $confirmation->getStatusColor() }}">
                                    {{ $confirmation->getStatusLabel() }}
                                </span>
                            </td>

                            <!-- Jumlah Tamu -->
                            <td>
                                @if($confirmation->number_of_guests > 0)
                                    <span class="badge bg-info">
                                        {{ $confirmation->number_of_guests }} tamu
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Waktu Konfirmasi -->
                            <td>
                                @if($confirmation->confirmed_at)
                                    {{ $confirmation->confirmed_at->format('d M Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Catatan -->
                            <td>
                                @if($confirmation->notes)
                                    <button class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="tooltip"
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
                                <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
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
// bootstrap 5 tooltip initialization
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
