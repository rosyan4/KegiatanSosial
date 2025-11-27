@extends('admin.layouts.app')

@section('title', 'Buat Dokumentasi Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus me-2"></i>Buat Dokumentasi Baru</h1>
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
                <!-- LEFT COLUMN -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                            </h5>
                        </div>

                        <div class="card-body">
                            <!-- Activity -->
                            <div class="mb-3">
                                <label for="activity_id" class="form-label">Kegiatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('activity_id') is-invalid @enderror"
                                        id="activity_id" name="activity_id" required>
                                    <option value="">Pilih Kegiatan</option>
                                    @foreach($activities as $activity)
                                        <option 
                                            value="{{ $activity->id }}"
                                            {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                            {{ $activity->title }} ({{ $activity->start_date->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('activity_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Hanya kegiatan yang selesai & belum memiliki dokumentasi.</div>
                            </div>

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Dokumentasi <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}"
                                       placeholder="Contoh: Dokumentasi Kegiatan Bakti Sosial 2024"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Summary -->
                            <div class="mb-3">
                                <label for="summary" class="form-label">Ringkasan</label>
                                <textarea id="summary" name="summary" rows="3"
                                          class="form-control @error('summary') is-invalid @enderror"
                                          placeholder="Ringkasan singkat tentang dokumentasi ini...">{{ old('summary') }}</textarea>
                                @error('summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Ditampilkan di halaman daftar dokumentasi.</div>
                            </div>

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                                <textarea id="content" name="content" rows="10"
                                          class="form-control @error('content') is-invalid @enderror"
                                          placeholder="Tulis konten dokumentasi lengkap di sini..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-md-4">

                    <!-- Featured Image -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Utama</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Upload Gambar Utama</label>
                                <input type="file" id="featured_image" name="featured_image"
                                       class="form-control @error('featured_image') is-invalid @enderror"
                                       accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: JPG, PNG, GIF (Maks: 2MB).</div>
                            </div>

                            <!-- Preview -->
                            <div id="featuredImagePreview" class="mt-3 text-center" style="display:none;">
                                <img id="previewImage" class="img-fluid rounded"
                                     style="max-height:200px; object-fit:cover;">
                                <button type="button" class="btn btn-sm btn-danger mt-2"
                                        onclick="removeFeaturedImage()">
                                    <i class="fas fa-times me-1"></i> Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.documentations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i> Simpan Dokumentasi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Featured Image Preview
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

    // Gallery Preview
    $('#gallery_images').change(function(e) {
        const files = e.target.files;
        $('#galleryPreview').empty();

        [...files].forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#galleryPreview').append(`
                    <div class="position-relative d-inline-block me-2 mb-2">
                        <img src="${e.target.result}" class="img-thumbnail"
                             style="width:80px; height:80px; object-fit:cover;">
                    </div>
                `);
            }
            reader.readAsDataURL(file);
        });
    });

});
</script>
@endpush
