@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $query)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hasil Pencarian: "{{ $query }}"</h1>
</div>

<!-- Search Results -->
<div class="row">
    <div class="col-12">
        <!-- Activities Results -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Kegiatan
                    @if($activities->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $activities->total() }} hasil</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($activities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    <a href="{{ route('activities.show', $activity) }}" class="text-decoration-none">
                                        {{ $activity->title }}
                                    </a>
                                </h5>
                                <small class="text-muted">{{ $activity->start_date->format('d M Y') }}</small>
                            </div>
                            <p class="mb-1 text-muted">{{ Str::limit($activity->description, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small>
                                    <span class="badge bg-secondary">{{ $activity->category->name }}</span>
                                    <span class="badge bg-{{ $activity->type === 'umum' ? 'primary' : 'warning' }} ms-1">
                                        {{ $activity->type }}
                                    </span>
                                </small>
                                <small class="text-muted">{{ $activity->location }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $activities->fragment('activities')->links() }}
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada kegiatan yang ditemukan.</p>
                @endif
            </div>
        </div>

        <!-- Documentations Results -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    Dokumentasi
                    @if($documentations->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $documentations->total() }} hasil</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($documentations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($documentations as $documentation)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    <a href="{{ route('documentations.show', $documentation) }}" class="text-decoration-none">
                                        {{ $documentation->title }}
                                    </a>
                                </h5>
                                <small class="text-muted">{{ $documentation->published_at->format('d M Y') }}</small>
                            </div>
                            <p class="mb-1 text-muted">{{ Str::limit($documentation->summary, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small>
                                    @if($documentation->activity)
                                        <span class="badge bg-info">Kegiatan: {{ $documentation->activity->title }}</span>
                                    @endif
                                </small>
                                <small class="text-muted">Oleh: {{ $documentation->creator->name }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $documentations->fragment('docs')->links() }}
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada dokumentasi yang ditemukan.</p>
                @endif
            </div>
        </div>

        <!-- No Results -->
        @if($activities->count() == 0 && $documentations->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada hasil ditemukan</h5>
            <p class="text-muted">Coba gunakan kata kunci yang berbeda atau lebih spesifik</p>
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
        </div>
        @endif
    </div>
</div>
@endsection