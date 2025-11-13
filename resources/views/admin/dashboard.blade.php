@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kegiatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_activities'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Warga</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Proposal Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_proposals'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-lightbulb fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Kegiatan Mendatang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming_activities'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kegiatan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_activities'] as $activity)
                            <tr>
                                <td>{{ $activity->title }}</td>
                                <td>{{ $activity->category->name }}</td>
                                <td>{{ $activity->start_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $activity->status === 'published' ? 'success' : 'secondary' }}">
                                        {{ $activity->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Stats -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistik Kehadiran</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="h4 font-weight-bold text-primary">
                        {{ number_format($stats['attendance_stats']['average_attendance'], 1) }}%
                    </div>
                    <p class="text-muted">Rata-rata Kehadiran</p>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h5 font-weight-bold">{{ $stats['attendance_stats']['total_participants'] }}</div>
                        <small class="text-muted">Total Peserta</small>
                    </div>
                    <div class="col-6">
                        <div class="h5 font-weight-bold">{{ $stats['attendance_stats']['total_events'] }}</div>
                        <small class="text-muted">Total Acara</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Buat Kegiatan
                    </a>
                    <a href="{{ route('admin.proposals.index') }}" class="btn btn-warning">
                        <i class="fas fa-lightbulb me-2"></i> Review Proposal
                    </a>
                    <a href="{{ route('admin.activities.calendar') }}" class="btn btn-info">
                        <i class="fas fa-calendar me-2"></i> Kalender
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection