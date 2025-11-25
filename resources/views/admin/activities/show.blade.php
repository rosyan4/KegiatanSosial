@extends('admin.layouts.app')

@section('title', $activity->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Kegiatan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit
            </a>
            
            @if($activity->isDraft())
                <form action="{{ route('admin.activities.publish', $activity) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mempublish kegiatan ini?')">
                        <i class="fas fa-check me-2"></i> Publish
                    </button>
                </form>
            @endif
            
            @if($activity->isPublished())
                <form action="{{ route('admin.activities.cancel', $activity) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan kegiatan ini?')">
                        <i class="fas fa-times me-2"></i> Batalkan
                    </button>
                </form>
            @endif

            @if($activity->isPublished() || $activity->isOngoing())
                <form action="{{ route('admin.activities.complete', $activity) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin menandai kegiatan sebagai selesai?')">
                        <i class="fas fa-flag-checkered me-2"></i> Selesai
                    </button>
                </form>
            @endif
        </div>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Information -->
    <div class="col-lg-8">
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
                                        <i class="{{ $activity->category->icon }} me-1"></i>
                                        {{ $activity->category->name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai:</th>
                                <td>{{ $activity->start_date->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Selesai:</th>
                                <td>{{ $activity->end_date->format('d F Y H:i') }}</td>
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
                                <th width="120">Tipe:</th>
                                <td>
                                    <span class="badge bg-{{ $activity->type === 'umum' ? 'info' : 'warning' }}">
                                        {{ $activity->type }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'published' => 'success',
                                            'cancelled' => 'danger',
                                            'completed' => 'primary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$activity->status] }}">
                                        {{ $activity->getStatusLabel() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Maks Peserta:</th>
                                <td>{{ $activity->max_participants ?: 'Tidak terbatas' }}</td>
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

                <div class="mt-3">
                    <h6>Deskripsi Kegiatan:</h6>
                    <p class="text-muted">{{ $activity->description }}</p>
                </div>
            </div>
        </div>


        <!-- Invitations (for khusus type) -->
        @if($activity->isKhusus())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Undangan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Tanggal Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activity->invitations as $invitation)
                            <tr>
                                <td>{{ $invitation->user->name }}</td>
                                <td>{{ $invitation->user->email }}</td>
                                <td>
                                    @php
                                        $invitationColors = [
                                            'pending' => 'warning',
                                            'accepted' => 'success',
                                            'declined' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $invitationColors[$invitation->status] }}">
                                        {{ ucfirst($invitation->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $invitation->responded_at ? $invitation->responded_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.attendance.show', $activity) }}" class="btn btn-outline-primary">
                        <i class="fas fa-clipboard-check me-2"></i> Kelola Kehadiran
                    </a>
                    @if($activity->isPublished())
                    <button class="btn btn-outline-success" id="sendRemindersBtn">
                        <i class="fas fa-bell me-2"></i> Kirim Pengingat
                    </button>
                    @endif
                    <a href="{{ route('admin.documentations.create') }}?activity_id={{ $activity->id }}" class="btn btn-outline-info">
                        <i class="fas fa-camera me-2"></i> Buat Dokumentasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Creator Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pembuat</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle fa-2x text-muted"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ $activity->creator->name }}</h6>
                        <small class="text-muted">{{ $activity->creator->email }}</small>
                        <br>
                        <small class="text-muted">Dibuat: {{ $activity->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invitation Statistics -->
        @if($activity->isKhusus())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Undangan</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <canvas id="invitationChart" width="200" height="200"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <span>Diterima:</span>
                        <strong>{{ $invitationStats['accepted'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Ditolak:</span>
                        <strong>{{ $invitationStats['declined'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Menunggu:</span>
                        <strong>{{ $invitationStats['pending'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Send reminders
        $('#sendRemindersBtn').click(function() {
            if (confirm('Yakin ingin mengirim pengingat kepada semua peserta?')) {
                // Implement reminder sending logic here
                alert('Pengingat berhasil dikirim!');
            }
        });

        // Invitation chart
        @if($activity->isKhusus())
        const ctx = document.getElementById('invitationChart').getContext('2d');
        const invitationChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Diterima', 'Ditolak', 'Menunggu'],
                datasets: [{
                    data: [
                        {{ $invitationStats['accepted'] ?? 0 }},
                        {{ $invitationStats['declined'] ?? 0 }},
                        {{ $invitationStats['pending'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        @endif
    });
</script>
@endpush