@extends('layouts.app')

@section('title', $documentation->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Documentation Header -->
        <div class="text-center mb-4">
            <h1 class="h2">{{ $documentation->title }}</h1>
            <div class="text-muted mb-3">
                <i class="fas fa-calendar me-1"></i>
                Dipublikasikan pada {{ $documentation->published_at->format('d F Y') }}
                &bull; 
                <i class="fas fa-eye me-1"></i>
                {{ $documentation->view_count }} dilihat
            </div>
            
            @if($documentation->activity)
            <div class="mb-3">
                <a href="{{ route('activities.show', $documentation->activity) }}" 
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Lihat Kegiatan: {{ $documentation->activity->title }}
                </a>
            </div>
            @endif
        </div>

        <!-- Featured Image -->
        @if($documentation->featured_image)
        <div class="card mb-4">
            <img src="{{ Storage::url($documentation->featured_image) }}" 
                 class="card-img-top" alt="{{ $documentation->title }}">
        </div>
        @endif

        <!-- Content -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="documentation-content">
                    {!! $documentation->content !!}
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="card">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <strong><i class="fas fa-user me-1 text-success"></i>Penulis</strong>
                        <p class="mb-0">{{ $documentation->creator->name }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-calendar me-1 text-primary"></i>Tanggal Publikasi</strong>
                        <p class="mb-0">{{ $documentation->published_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-eye me-1 text-info"></i>Dilihat</strong>
                        <p class="mb-0">{{ $documentation->view_count }} kali</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('documentations.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dokumentasi
            </a>
        </div>
    </div>
</div>

<style>
.documentation-content {
    line-height: 1.8;
}

.documentation-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.documentation-content p {
    margin-bottom: 1rem;
}

.documentation-content h2, 
.documentation-content h3, 
.documentation-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
}
</style>
@endsection