@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Konfirmasi Hadir</h6>
                        <h3>{{ $attendanceStats['confirmed'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Menunggu Konfirmasi</h6>
                        <h3>{{ $attendanceStats['pending'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Undangan Tertunda</h6>
                        <h3>{{ $pendingInvitations }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Usulan Saya</h6>
                        <h3>{{ $myProposals }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-lightbulb fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Activities -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kegiatan Mendatang</h5>
                <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($upcomingActivities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Status Konfirmasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingActivities as $activity)
                                <tr>
                                    <td>
                                        <strong>{{ $activity->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $activity->category->name }}</small>
                                    </td>
                                    <td>
                                        {{ $activity->start_date->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $activity->start_date->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $activity->location }}</td>
                                    <td>
                                        @php
                                            $userConfirmation = $activity->attendanceConfirmations->first();
                                        @endphp
                                        @if($userConfirmation)
                                            <span class="badge bg-{{ $userConfirmation->status === 'hadir' ? 'success' : ($userConfirmation->status === 'tidak_hadir' ? 'danger' : 'warning') }}">
                                                {{ $userConfirmation->status }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum Konfirmasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada kegiatan mendatang.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection