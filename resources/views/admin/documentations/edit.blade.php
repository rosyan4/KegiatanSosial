@extends('admin.layouts.app')

@section('title', 'Edit Dokumentasi - ' . $documentation->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Edit Dokumentasi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.documentations.show', $documentation) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-2"></i> Lihat
        </a>
        <a href="{{ route('admin.documentations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.documentations.update', $documentation) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Kegiatan</label>
                                <p class="form-control-plaintext fw-bold">
                                    {{ $documentation->activity->title ?? 'Tidak ada kegiatan' }}
                                </p>
                                <div class="form-text">
                                    Kegiatan tidak dapat diubah setelah dokumentasi dibuat.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Dokumentasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" 
                                       value="{{ old('title', $documentation->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="summary" class="form-label">Ringkasan</label>
                                <textarea class="form-control @error('summary') is-invalid @enderror" 
                                          id="summary" name="summary" rows="3">{{ old('summary', $documentation->summary) }}</textarea>
                                @error('summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="10" required>{{ old('content', $documentation->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="col-md-4">
                    <!-- Current Featured Image -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Utama</h5>
                        </div>
                        <div class="card-body">
                            @if($documentation->featured_image)
                                <div class="text-center mb-3">
                                    <img 
                                        src="{{ Storage::url($documentation->featured_image) }}" 
                                        class="img-fluid w-100 rounded" 
                                        style="max-height: 200px; object-fit: cover;"
                                        alt="{{ $documentation->title }}"
                                    >
                                    <div class="mt-2">
                                        <a 
                                            href="{{ Storage::url($documentation->featured_image) }}" 
                                            target="_blank" 
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-external-link-alt me-1"></i> Lihat Full
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="featured_image" class="form-label">
                                    {{ $documentation->featured_image ? 'Ganti Gambar Utama' : 'Upload Gambar Utama' }}
                                </label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" name="featured_image" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Image Preview -->
                            <div id="featuredImagePreview" class="mt-3 text-center" style="display: none;">
                                <img id="previewImage" class="img-fluid rounded" 
                                     style="max-height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" 
                                        onclick="removeFeaturedImage()">
                                    <i class="fas fa-times me-1"></i> Batalkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Current Gallery -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Galeri Foto</h5>
                            <span class="badge bg-primary">
                                {{ count($documentation->gallery_images ?? []) }} foto
                            </span>
                        </div>
                        <div class="card-body">
                            @if($documentation->gallery_images && count($documentation->gallery_images) > 0)
                            <div class="row g-2 mb-3">

                                @foreach($documentation->gallery_images as $index => $image)
                                <div class="col-4">
                                    <div class="position-relative">
                                        <img 
                                            src="{{ Storage::url($image) }}" 
                                            class="img-fluid w-100 img-thumbnail" 
                                            style="height: 80px; object-fit: cover;"
                                        >
                                    </div>
                                </div>
                                @endforeach

                            </div>
                            @else
                            <p class="text-muted text-center mb-3">Belum ada foto di galeri</p>
                            @endif

                            <!-- Add More Gallery Images -->
                            <div class="mb-3">
                                <label for="gallery_images" class="form-label">Tambah Foto ke Galeri</label>
                                <input type="file" class="form-control @error('gallery_images') is-invalid @enderror" 
                                       id="gallery_images" name="gallery_images[]" 
                                       multiple accept="image/*">
                                @error('gallery_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('gallery_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Gallery Preview -->
                            <div id="galleryPreview" class="mt-3"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.documentations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i> Update
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Featured image preview
        $('#featured_image').change(function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                    $('#featuredImagePreview').show();
                }
                reader.readAsDataURL(file);
            }
        });

        window.removeFeaturedImage = function() {
            $('#featured_image').val('');
            $('#featuredImagePreview').hide();
        }

        // Gallery images preview
        $('#gallery_images').change(function(e) {
            const files = e.target.files;
            $('#galleryPreview').empty();
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = $(`
                        <div class="position-relative d-inline-block me-2 mb-2">
                            <img src="${e.target.result}" class="img-thumbnail" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                    onclick="removeGalleryPreview(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                    $('#galleryPreview').append(preview);
                }
                
                reader.readAsDataURL(file);
            }
        });

        window.removeGalleryPreview = function(button) {
            $(button).closest('.position-relative').remove();
        }
    });
</script>
@endpush
