@extends('layouts.app')

@section('title', 'Daftar Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Kegiatan</h1>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('activities.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="umum" {{ request('type') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="khusus" {{ request('type') == 'khusus' ? 'selected' : '' }}>Khusus</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activities Grid -->
<div class="row">
    @foreach($activities as $activity)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-hover h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="badge bg-{{ $activity->type === 'umum' ? 'primary' : 'warning' }}">
                    {{ $activity->type }}
                </span>
                <small class="text-muted">{{ $activity->category->name }}</small>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $activity->title }}</h5>
                <p class="card-text text-muted small">{{ Str::limit($activity->description, 100) }}</p>
                
                <div class="mb-2">
                    <i class="fas fa-calendar me-1 text-primary"></i>
                    <small>{{ $activity->start_date->format('d M Y H:i') }}</small>
                </div>
                <div class="mb-2">
                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                    <small>{{ $activity->location }}</small>
                </div>
                <div class="mb-3">
                    <i class="fas fa-users me-1 text-success"></i>
                    <small>{{ $activity->confirmed_count }} Peserta Hadir</small>
                </div>

                @php
                    $userConfirmation = $activity->attendanceConfirmations->first();
                @endphp
                @if($userConfirmation)
                    <div class="mb-2">
                        <span class="badge bg-{{ $userConfirmation->status === 'hadir' ? 'success' : ($userConfirmation->status === 'tidak_hadir' ? 'danger' : 'warning') }}">
                            Status: {{ $userConfirmation->status }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-primary">Detail & Konfirmasi</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $activities->links() }}
</div>

@if($activities->count() == 0)
<div class="text-center py-5">
    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Tidak ada kegiatan ditemukan</h5>
</div>
@endif
@endsection