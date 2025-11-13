@extends('layouts.app')

@section('title', 'Detail Undangan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Undangan</h1>
    <span class="badge bg-{{ $invitation->status === 'accepted' ? 'success' : ($invitation->status === 'declined' ? 'danger' : 'warning') }}">
        Status: {{ $invitation->status }}
    </span>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Invitation Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Kegiatan</h5>
            </div>
            <div class="card-body">
                <h4 class="text-primary">{{ $invitation->activity->title }}</h4>
                <p class="text-muted">{{ $invitation->activity->description }}</p>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar me-2 text-primary"></i>Tanggal & Waktu</strong>
                        <p class="mb-1">{{ $invitation->activity->start_date->format('l, d F Y') }}</p>
                        <p class="text-muted">{{ $invitation->activity->start_date->format('H:i') }} - {{ $invitation->activity->end_date->format('H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-map-marker-alt me-2 text-danger"></i>Lokasi</strong>
                        <p>{{ $invitation->activity->location }}</p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-tag me-2 text-info"></i>Kategori</strong>
                        <p>{{ $invitation->activity->category->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-user me-2 text-success"></i>Pengundang</strong>
                        <p>{{ $invitation->activity->creator->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Form -->
        @if($invitation->status === 'pending')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Konfirmasi Undangan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Silakan konfirmasi apakah Anda dapat hadir dalam kegiatan ini.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('invitations.accept', $invitation) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>Terima Undangan
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('invitations.decline', $invitation) }}" method="POST" class="d-grid">
                            @csrf
                            <div class="mb-2">
                                <textarea name="decline_reason" class="form-control" rows="2" 
                                    placeholder="Alasan tidak dapat hadir (opsional)"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i>Tolak Undangan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-{{ $invitation->status === 'accepted' ? 'success' : 'danger' }}">
            <i class="fas fa-{{ $invitation->status === 'accepted' ? 'check-circle' : 'times-circle' }} me-2"></i>
            Anda telah {{ $invitation->status === 'accepted' ? 'menerima' : 'menolak' }} undangan ini.
            @if($invitation->decline_reason)
                <br><strong>Alasan:</strong> {{ $invitation->decline_reason }}
            @endif
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('activities.show', $invitation->activity) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Detail Kegiatan
                    </a>
                    
                    @if($invitation->status === 'accepted')
                        <a href="{{ route('activities.confirm-attendance', $invitation->activity) }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-check me-1"></i>Konfirmasi Kehadiran
                        </a>
                    @endif

                    <a href="{{ route('calendar.index') }}?date={{ $invitation->activity->start_date->format('Y-m-d') }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-calendar me-1"></i>Lihat di Kalender
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Statistik Kegiatan</h5>
            </div>
            <div class="card-body">
                @php
                    $stats = $invitation->activity->getAttendanceStats();
                @endphp
                <div class="text-center">
                    <div class="mb-3">
                        <h3>{{ $stats['total'] ?? 0 }}</h3>
                        <small class="text-muted">Total Konfirmasi</small>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="text-success">
                                <h5>{{ $stats['hadir'] ?? 0 }}</h5>
                                <small>Hadir</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-danger">
                                <h5>{{ $stats['tidak_hadir'] ?? 0 }}</h5>
                                <small>Tidak Hadir</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <h5>{{ $stats['mungkin'] ?? 0 }}</h5>
                                <small>Mungkin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection