@extends('admin.layouts.app')

@section('title', isset($activity) ? 'Edit Kegiatan' : 'Buat Kegiatan Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ isset($activity) ? 'Edit Kegiatan' : 'Buat Kegiatan Baru' }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ isset($activity) ? route('admin.activities.update', $activity) : route('admin.activities.store') }}">
            @csrf
            @if(isset($activity))
                @method('PUT')
            @endif
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                               value="{{ old('title', $activity->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', isset($activity) ? $activity->category_id : '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                                  rows="4" required>{{ old('description', $activity->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Schedule & Location -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary"><i class="fas fa-clock me-2"></i>Jadwal & Lokasi</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                    class="form-control @error('start_date') is-invalid @enderror" 
                                    id="start_date" name="start_date"
                                    value="{{ old('start_date', isset($activity) && $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('Y-m-d\TH:i') : '') }}"
                                    required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                    class="form-control @error('end_date') is-invalid @enderror" 
                                    id="end_date" name="end_date"
                                    value="{{ old('end_date', isset($activity) && $activity->end_date ? \Carbon\Carbon::parse($activity->end_date)->format('Y-m-d\TH:i') : '') }}"
                                    required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" 
                               value="{{ old('location', $activity->location ?? '') }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_participants" class="form-label">Maksimal Peserta</label>
                        <input type="number" class="form-control @error('max_participants') is-invalid @enderror" 
                               id="max_participants" name="max_participants" min="1"
                               value="{{ old('max_participants', $activity->max_participants ?? '') }}">
                        @error('max_participants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <!-- Settings -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary"><i class="fas fa-cog me-2"></i>Pengaturan</h5>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Kegiatan <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="umum" {{ old('type', isset($activity) ? $activity->type : '') == 'umum' ? 'selected' : '' }}>Umum (Terbuka untuk semua)</option>
                            <option value="khusus" {{ old('type', isset($activity) ? $activity->type : '') == 'khusus' ? 'selected' : '' }}>Khusus (Undangan tertentu)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="draft" {{ old('status', isset($activity) ? $activity->status : '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', isset($activity) ? $activity->status : '') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="cancelled" {{ old('status', isset($activity) ? $activity->status : '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="completed" {{ old('status', isset($activity) ? $activity->status : '') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="requires_attendance_confirmation" 
                               name="requires_attendance_confirmation" value="1"
                               {{ old('requires_attendance_confirmation', isset($activity) ? $activity->requires_attendance_confirmation : false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requires_attendance_confirmation">
                            Perlu konfirmasi kehadiran
                        </label>
                    </div>
                </div>

                <!-- Invited Users -->
                <div class="col-md-6">
                    <div id="invited-users-section" style="{{ old('type', isset($activity) ? $activity->type : 'umum') == 'khusus' ? '' : 'display: none;' }}">
                        <h5 class="mb-3 text-primary"><i class="fas fa-users me-2"></i>Undangan Khusus</h5>

                        <div class="mb-3">
                            <label for="invited_users" class="form-label">Pilih Peserta</label>
                            <select class="form-select select2 @error('invited_users') is-invalid @enderror" 
                                    id="invited_users" name="invited_users[]" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ in_array($user->id, old('invited_users', isset($activity) ? $activity->invitations->pluck('user_id')->toArray() : [])) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('invited_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih peserta yang diundang untuk kegiatan khusus</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> {{ isset($activity) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih peserta...'
        });

        // Show/hide invited users based on type
        $('#type').change(function() {
            if ($(this).val() === 'khusus') {
                $('#invited-users-section').slideDown();
            } else {
                $('#invited-users-section').slideUp();
            }
        });

        // Trigger change on page load
        $('#type').trigger('change');

        // Date validation (END DATE boleh sama dengan START DATE)
        $('#start_date, #end_date').change(function() {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($('#end_date').val());
            
            if (endDate < startDate) {
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                $('#end_date').val('');
            }
        });
    });
</script>
@endpush
