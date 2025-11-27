@extends('admin.layouts.app')

@section('title', 'Edit Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Kegiatan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-2"></i> Lihat
        </a>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

{{-- FORM EDIT KEGIATAN --}}
<form action="{{ route('admin.activities.update', $activity) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">

            {{-- Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Judul Kegiatan</label>
                <input type="text" name="title" id="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $activity->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="4">{{ old('description', $activity->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Category --}}
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" id="category_id"
                        class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $activity->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Location --}}
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi Kegiatan</label>
                <input type="text" name="location" id="location"
                       class="form-control @error('location') is-invalid @enderror"
                       value="{{ old('location', $activity->location) }}" required>
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Max Participants --}}
            <div class="mb-3">
                <label for="max_participants" class="form-label">Maksimal Peserta</label>
                <input type="number" name="max_participants" id="max_participants"
                       class="form-control @error('max_participants') is-invalid @enderror"
                       value="{{ old('max_participants', $activity->max_participants) }}">
                @error('max_participants')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="col-md-4">

            {{-- Start Date --}}
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="datetime-local" name="start_date" id="start_date"
                       class="form-control @error('start_date') is-invalid @enderror"
                       value="{{ old('start_date', \Carbon\Carbon::parse($activity->start_date)->format('Y-m-d\TH:i')) }}"
                       required>
                @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- End Date --}}
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="datetime-local" name="end_date" id="end_date"
                       class="form-control @error('end_date') is-invalid @enderror"
                       value="{{ old('end_date', \Carbon\Carbon::parse($activity->end_date)->format('Y-m-d\TH:i')) }}"
                       required>
                @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status Kegiatan</label>
                <select name="status" id="status"
                        class="form-control @error('status') is-invalid @enderror" required>
                    @foreach (['draft','published','cancelled','completed'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $activity->status) == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Type --}}
            <div class="mb-3">
                <label for="type" class="form-label">Jenis Kegiatan</label>
                <select name="type" id="type"
                        class="form-control @error('type') is-invalid @enderror" required>
                    <option value="umum" {{ old('type', $activity->type) == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="khusus" {{ old('type', $activity->type) == 'khusus' ? 'selected' : '' }}>Khusus</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-2"></i> Simpan Perubahan
    </button>
</form>

@endsection
