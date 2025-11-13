@extends('admin.layouts.app')

@section('title', 'Buat Dokumentasi Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>Buat Dokumentasi Baru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.documentations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.documentations.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="activity_id" class="form-label">Kegiatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('activity_id') is-invalid @enderror" 
                                        id="activity_id" name="activity_id" required>
                                    <option value="">Pilih Kegiatan</option>
                                    @foreach($activities as $activity)
                                        <option value="{{ $activity->id }}" 
                                            {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                            {{ $activity->title }} ({{ $activity->start_date->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('activity_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Hanya kegiatan yang sudah selesai dan belum memiliki dokumentasi yang ditampilkan
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Dokumentasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Contoh: Dokumentasi Kegiatan Bakti Sosial 2024" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="summary" class="form-label">Ringkasan</label>
                                <textarea class="form-control @error('summary') is-invalid @enderror" 
                                          id="summary" name="summary" rows="3" 
                                          placeholder="Ringkasan singkat tentang dokumentasi ini...">{{ old('summary') }}</textarea>
                                @error('summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Ringkasan akan ditampilkan di halaman daftar dokumentasi</div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="10" 
                                          placeholder="Tulis konten dokumentasi lengkap di sini..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media & Settings -->
                <div class="col-md-4">
                    <!-- Featured Image -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Utama</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Upload Gambar Utama</label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" name="featured_image" 
                                       accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Format: JPG, PNG, GIF (Maks: 2MB). Gambar akan ditampilkan sebagai thumbnail.
                                </div>
                            </div>

                            <!-- Image Preview -->
                            <div id="featuredImagePreview" class="mt-3 text-center" style="display: none;">
                                <img id="previewImage" class="img-fluid rounded" 
                                     style="max-height: 200px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" 
                                        onclick="removeFeaturedImage()">
                                    <i class="fas fa-times me-1"></i> Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Galeri Foto</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="gallery_images" class="form-label">Upload Multiple Foto</label>
                                <input type="file" class="form-control @error('gallery_images') is-invalid @enderror" 
                                       id="gallery_images" name="gallery_images[]" 
                                       multiple accept="image/*">
                                @error('gallery_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('gallery_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Pilih multiple foto untuk galeri. Format: JPG, PNG, GIF (Maks: 2MB per file).
                                </div>
                            </div>

                            <!-- Gallery Preview -->
                            <div id="galleryPreview" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Publication Settings -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan Publikasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_published" 
                                       name="is_published" value="1" 
                                       {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    <strong>Publikasikan Sekarang</strong>
                                </label>
                                <div class="form-text">
                                    Jika dicentang, dokumentasi akan langsung dipublikasikan.
                                </div>
                            </div>

                            <div class="mb-3" id="publishSchedule" 
                                 style="{{ old('is_published') ? 'display: none;' : '' }}">
                                <label for="published_at" class="form-label">Jadwal Publikasi</label>
                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                       id="published_at" name="published_at" 
                                       value="{{ old('published_at') }}">
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Atur jadwal publikasi di masa depan (opsional).
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informasi:</strong> Dokumentasi yang dipublikasikan dapat dilihat oleh warga.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.documentations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
                <div class="btn-group">
                    <button type="submit" name="draft" value="1" class="btn btn-outline-secondary">
                        <i class="fas fa-save me-2"></i> Simpan Draft
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> Simpan & Publikasikan
                    </button>
                </div>
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

        // Remove featured image
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
                                    onclick="removeGalleryImage(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                    $('#galleryPreview').append(preview);
                }
                
                reader.readAsDataURL(file);
            }
        });

        // Remove gallery image
        window.removeGalleryImage = function(button) {
            $(button).closest('.position-relative').remove();
            // Note: This only removes the preview, not the actual file input
            // You might want to implement a more complex solution for actual file removal
        }

        // Toggle publish schedule
        $('#is_published').change(function() {
            if (this.checked) {
                $('#publishSchedule').slideUp();
            } else {
                $('#publishSchedule').slideDown();
            }
        });

        // Set default published_at to current time if not set
        if (!$('#published_at').val()) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#published_at').val(now.toISOString().slice(0, 16));
        }
    });
</script>
@endpush