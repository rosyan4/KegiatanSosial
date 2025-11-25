@extends('layouts.app')

@section('title', 'Dokumentasi Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dokumentasi Kegiatan</h1>
</div>

<div class="row">
    @foreach($docs as $documentation)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-hover h-100 shadow-sm border-0">

            {{-- Featured Image --}}
            @if($documentation->featured_image)
            <img src="{{ Storage::url($documentation->featured_image) }}" 
                 class="card-img-top" 
                 alt="{{ $documentation->title }}"
                 style="height: 200px; object-fit: cover;">
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                 style="height: 200px;">
                <i class="fas fa-image fa-3x text-muted"></i>
            </div>
            @endif

            <div class="card-body">
                <h5 class="card-title">{{ $documentation->title }}</h5>

                <p class="card-text text-muted small">
                    {{ \Illuminate\Support\Str::limit($documentation->summary, 100) }}
                </p>

                <div class="mb-2">
                    <i class="fas fa-calendar me-1 text-primary"></i>
                    <small>Kegiatan: {{ $documentation->activity->title ?? '-' }}</small>
                </div>

                <div class="mb-2">
                    <i class="fas fa-user me-1 text-success"></i>
                    <small>Oleh: {{ $documentation->creator->name ?? 'Tidak diketahui' }}</small>
                </div>

                <div class="mb-3">
                    <i class="fas fa-eye me-1 text-info"></i>
                    <small>{{ $documentation->view_count }} dilihat</small>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        {{ $documentation->created_at->format('d M Y') }}
                    </small>

                    <a href="{{ route('documentations.show', $documentation) }}" 
                       class="btn btn-sm btn-outline-primary">
                        Baca Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $docs->links() }}
</div>

@if($docs->count() == 0)
<div class="text-center py-5">
    <i class="fas fa-photo-video fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Belum ada dokumentasi</h5>
    <p class="text-muted">Dokumentasi kegiatan akan ditampilkan di sini</p>
</div>
@endif
@endsection
