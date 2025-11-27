@extends('admin.layouts.app')

@section('title', 'Manajemen Kehadiran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kehadiran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.attendance.reports') }}" class="btn btn-info">
            <i class="fas fa-chart-bar me-2"></i> Laporan Kehadiran
        </a>
    </div>
</div>

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
                        <th class="text-center">Aksi</th>
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

                        <td><strong>{{ $activity->title }}</strong></td>

                        <td>
                            @if($activity->category)
                                <span class="badge" style="background-color: {{ $activity->category->color }}; color:white;">
                                    {{ $activity->category->name }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Tanpa Kategori</span>
                            @endif
                        </td>

                        <td>
                            {{ $activity->start_date->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $activity->getStatusLabel() }}</small>
                        </td>

                        <td>
                            <div class="d-flex flex-column small">
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i> {{ $presentCount }} Hadir</span>
                                <span class="text-danger"><i class="fas fa-times-circle me-1"></i> {{ $absentCount }} Tidak hadir</span>
                                <span class="text-warning"><i class="fas fa-question-circle me-1"></i> {{ $maybeCount }} Mungkin</span>
                                <span class="text-muted"><i class="fas fa-clock me-1"></i> {{ $confirmedCount }} Total</span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-column small">
                                <span class="text-primary"><i class="fas fa-sign-in-alt me-1"></i> {{ $checkedInCount }} Check-in</span>
                                <span class="text-info"><i class="fas fa-sign-out-alt me-1"></i> {{ $checkedOutCount }} Check-out</span>
                                <span class="text-success"><i class="fas fa-shield-alt me-1"></i> {{ $verifiedCount }} Terverifikasi</span>
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

                        <!-- 🔥 Tombol Aksi FIX + Hapus Riwayat -->
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.attendance.show', $activity) }}" class="btn btn-info" title="Lihat Detail Kehadiran">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Hapus riwayat kehadiran -->
                                <form action="{{ route('admin.attendance.deleteHistory', $activity) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat kehadiran untuk kegiatan ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" title="Hapus Riwayat">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada data.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>

@endsection
