@extends('layouts.app')

@section('title', 'Ajukan Usulan Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Ajukan Usulan Kegiatan Baru</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Usulan Kegiatan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('proposals.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="title" class="form-label">Judul Kegiatan *</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Deskripsi Kegiatan *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="objectives" class="form-label">Tujuan Kegiatan *</label>
                    <textarea class="form-control @error('objectives') is-invalid @enderror" 
                              id="objectives" name="objectives" rows="3" required>{{ old('objectives') }}</textarea>
                    @error('objectives')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="benefits" class="form-label">Manfaat Kegiatan *</label>
                    <textarea class="form-control @error('benefits') is-invalid @enderror" 
                              id="benefits" name="benefits" rows="3" required>{{ old('benefits') }}</textarea>
                    @error('benefits')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="proposed_date" class="form-label">Tanggal Diusulkan *</label>
                    <input type="date" class="form-control @error('proposed_date') is-invalid @enderror" 
                           id="proposed_date" name="proposed_date" value="{{ old('proposed_date') }}" required>
                    @error('proposed_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="proposed_location" class="form-label">Lokasi Diusulkan *</label>
                    <input type="text" class="form-control @error('proposed_location') is-invalid @enderror" 
                           id="proposed_location" name="proposed_location" value="{{ old('proposed_location') }}" required>
                    @error('proposed_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="estimated_participants" class="form-label">Perkiraan Peserta *</label>
                    <input type="number" class="form-control @error('estimated_participants') is-invalid @enderror" 
                           id="estimated_participants" name="estimated_participants" 
                           value="{{ old('estimated_participants') }}" min="1" required>
                    @error('estimated_participants')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="estimated_budget" class="form-label">Perkiraan Anggaran</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('estimated_budget') is-invalid @enderror" 
                               id="estimated_budget" name="estimated_budget" value="{{ old('estimated_budget') }}" min="0">
                    </div>
                    @error('estimated_budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="required_support" class="form-label">Dukungan yang Dibutuhkan</label>
                    <select class="form-select @error('required_support') is-invalid @enderror" 
                            id="required_support" name="required_support">
                        <option value="">Tidak Butuh Dukungan</option>
                        <option value="fasilitas" {{ old('required_support') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="dana" {{ old('required_support') == 'dana' ? 'selected' : '' }}>Dana</option>
                        <option value="personil" {{ old('required_support') == 'personil' ? 'selected' : '' }}>Personil</option>
                        <option value="fasilitas_dana" {{ old('required_support') == 'fasilitas_dana' ? 'selected' : '' }}>Fasilitas & Dana</option>
                    </select>
                    @error('required_support')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Ajukan Usulan
                    </button>
                    <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection