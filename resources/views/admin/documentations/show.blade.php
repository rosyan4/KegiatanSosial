@extends('admin.layouts.app')

@section('title', $documentation->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-camera me-2"></i>Detail Dokumentasi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.documentations.edit', $documentation) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit
            </a>
        </div>
        <a href="{{ route('admin.documentations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Featured Image -->
        @if($documentation->featured_image)
        <div class="card mb-4">
            <div class="card-body p-0">
                <img 
                    src="{{ Storage::url($documentation->featured_image) }}" 
                    class="img-fluid w-100 rounded-top" 
                    alt="{{ $documentation->title }}"
                    style="max-height: 400px; object-fit: cover;"
                >
            </div>
        </div>
        @endif

        <!-- Documentation Content -->
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="card-title h3 mb-3">{{ $documentation->title }}</h1>
                
                @if($documentation->summary)
                <div class="alert alert-light border">
                    <h5 class="alert-heading">Ringkasan:</h5>
                    <p class="mb-0">{{ $documentation->summary }}</p>
                </div>
                @endif

                <div class="documentation-content">
                    {!! nl2br(e($documentation->content)) !!}
                </div>
            </div>
        </div>

        <!-- Gallery Section -->
        @if($documentation->gallery_images && count($documentation->gallery_images) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-images me-2"></i>Galeri Foto
                    <span class="badge bg-primary ms-2">{{ count($documentation->gallery_images) }} foto</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3" id="gallery">
                    @foreach($documentation->gallery_images as $index => $image)
                    <div class="col-md-4 col-sm-6">
                        <div class="gallery-item position-relative">
                            <img src="{{ Storage::url($image) }}"  
                                 class="img-thumbnail w-100"
                                 style="height: 200px; object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#galleryModal"
                                 data-image-src="{{ Storage::url($image) }}"
                                 data-image-index="{{ $index }}">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Activity Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kegiatan</h5>
            </div>
            <div class="card-body">
                @if($documentation->activity)
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="100"><strong>Kegiatan:</strong></td>
                        <td>{{ $documentation->activity->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kategori:</strong></td>
                        <td>
                            <span class="badge" style="background-color: {{ $documentation->activity->category->color }}; color: white;">
                                {{ $documentation->activity->category->name }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>{{ $documentation->activity->start_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi:</strong></td>
                        <td>{{ $documentation->activity->location }}</td>
                    </tr>
                </table>

                @if(Route::has('admin.activities.show'))
                <a href="{{ route('admin.activities.show', $documentation->activity) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-external-link-alt me-1"></i> Lihat Kegiatan
                </a>
                @endif

                @else
                <p class="text-muted mb-0">Tidak ada informasi kegiatan terkait.</p>
                @endif
            </div>
        </div>

        <!-- Metadata -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i>Metadata</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="120"><strong>Dibuat oleh:</strong></td>
                        <td>{{ $documentation->creator->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $documentation->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diupdate:</strong></td>
                        <td>{{ $documentation->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gambar Utama:</strong></td>
                        <td>
                            @if($documentation->featured_image)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Galeri:</strong></td>
                        <td>
                            <span class="badge bg-primary">{{ count($documentation->gallery_images ?? []) }} foto</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.documentations.edit', $documentation) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Dokumentasi
                    </a>

                    @if(Route::has('admin.documentations.destroy'))
                    <form action="{{ route('admin.documentations.destroy', $documentation) }}" method="POST" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Yakin ingin menghapus dokumentasi ini? Tindakan ini tidak dapat dibatalkan.')">
                            <i class="fas fa-trash me-2"></i> Hapus Dokumentasi
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Modal -->
@if($documentation->gallery_images && count($documentation->gallery_images) > 0)
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Galeri Foto - {{ $documentation->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevImage">
                    <i class="fas fa-chevron-left me-2"></i> Sebelumnya
                </button>
                <span id="imageCounter" class="fw-bold"></span>
                <button type="button" class="btn btn-secondary" id="nextImage">
                    Selanjutnya <i class="fas fa-chevron-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .documentation-content {
        line-height: 1.8;
        font-size: 1.1rem;
    }
    .documentation-content p {
        margin-bottom: 1rem;
    }
    .gallery-item:hover img {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
@if($documentation->gallery_images && count($documentation->gallery_images) > 0)
<script>
    $(document).ready(function() {
        const galleryImages = @json($documentation->gallery_images);
        let currentImageIndex = 0;

        $('#galleryModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            currentImageIndex = button.data('image-index');
            $('#modalImage').attr('src', button.data('image-src'));
            updateImageCounter();
        });

        $('#prevImage').click(function() {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            showImage(currentImageIndex);
        });

        $('#nextImage').click(function() {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            showImage(currentImageIndex);
        });

        function showImage(index) {
            $('#modalImage').attr('src', '{{ Storage::url("") }}' + galleryImages[index]);
            updateImageCounter();
        }

        function updateImageCounter() {
            $('#imageCounter').text((currentImageIndex + 1) + ' / ' + galleryImages.length);
        }
    });
</script>
@endif
@endpush
