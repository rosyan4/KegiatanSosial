@extends('admin.layouts.app')

@section('title', 'Manajemen Dokumentasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-camera me-2"></i>Manajemen Dokumentasi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.documentations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Buat Dokumentasi
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" class="form-control" id="search" name="search" 
                    value="{{ request('search') }}" placeholder="Cari judul atau konten...">
            </div>

            <div class="col-md-4">
                <label for="activity_id" class="form-label">Kegiatan</label>
                <select class="form-select" id="activity_id" name="activity_id">
                    <option value="">Semua Kegiatan</option>
                    @foreach(\App\Models\Activity::completed()->with('category')->get() as $activity)
                        <option value="{{ $activity->id }}" 
                            {{ request('activity_id') == $activity->id ? 'selected' : '' }}>
                            {{ $activity->title }} ({{ $activity->category->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Documentations Grid -->
<div class="card">
    <div class="card-body">
        @if($documentations->count() > 0)
        <div class="row">

            @foreach($documentations as $documentation)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 documentation-card">

                    <!-- Featured Image -->
                    @php
                        $imageUrl = $documentation->featured_image 
                                    ? Storage::url($documentation->featured_image) 
                                    : null;
                    @endphp

                    @if($imageUrl)
                        <img 
                            src="{{ $imageUrl }}" 
                            class="img-fluid w-100 card-img-top" 
                            alt="{{ $documentation->title }}"
                            style="height: 200px; object-fit: cover;"
                        >
                    @else
                        <div 
                            class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                            style="height: 200px;"
                        >
                            <i class="fas fa-camera fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">

                        <!-- Activity Info -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $documentation->activity->title ?? 'Tidak ada kegiatan' }}
                            </small>
                        </div>

                        <!-- Title -->
                        <h5 class="card-title text-truncate" title="{{ $documentation->title }}">
                            {{ $documentation->title }}
                        </h5>

                        <!-- Summary -->
                        <p class="card-text flex-grow-1">
                            @if($documentation->summary)
                                {{ Str::limit($documentation->summary, 100) }}
                            @else
                                {{ Str::limit(strip_tags($documentation->content), 100) }}
                            @endif
                        </p>

                        <!-- Gallery Info -->
                        @if($documentation->gallery_images && count($documentation->gallery_images) > 0)
                        <div class="mb-2">
                            <small class="text-info">
                                <i class="fas fa-images me-1"></i>
                                {{ count($documentation->gallery_images) }} foto
                            </small>
                        </div>
                        @endif

                        <!-- Metadata -->
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $documentation->creator->name ?? 'Admin' }}
                                </small>
                                <small class="text-muted">
                                    {{ $documentation->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100">
                            <a href="{{ route('admin.documentations.show', $documentation) }}" 
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.documentations.edit', $documentation) }}" 
                                class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.documentations.destroy', $documentation) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus dokumentasi ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach

        </div>

        @else
        <div class="text-center py-5">
            <i class="fas fa-camera fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum Ada Dokumentasi</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'activity_id']))
                    Tidak ada dokumentasi yang sesuai dengan filter.
                @else
                    Mulai buat dokumentasi pertama untuk kegiatan yang telah selesai.
                @endif
            </p>
            <a href="{{ route('admin.documentations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i> Buat Dokumentasi Pertama
            </a>
            @if(request()->hasAny(['search', 'activity_id']))
            <a href="{{ route('admin.documentations.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                <i class="fas fa-times me-2"></i> Reset Filter
            </a>
            @endif
        </div>
        @endif

        <!-- Pagination -->
        @if($documentations->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $documentations->links() }}
        </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
.documentation-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e0e0e0;
}
.documentation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.card-img-top {
    border-bottom: 1px solid #e0e0e0;
}
</style>
@endpush
