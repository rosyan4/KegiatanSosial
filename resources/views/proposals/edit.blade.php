@extends('layouts.app')

@section('title', 'Edit Usulan Kegiatan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Usulan Kegiatan</h5>
                    <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if($proposal->needsRevision())
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Perlu Revisi</h6>
                            <p class="mb-2"><strong>Catatan Admin:</strong></p>
                            <p class="mb-0">{{ $proposal->admin_notes ?? 'Tidak ada catatan' }}</p>
                        </div>
                    @endif

                    <form action="{{ route('proposals.update', $proposal) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $proposal->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="proposed_date" class="form-label">Tanggal Usulan <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('proposed_date') is-invalid @enderror" 
                                           id="proposed_date" 
                                           name="proposed_date" 
                                           value="{{ old('proposed_date', $proposal->proposed_date->format('Y-m-d')) }}" 
                                           required>
                                    @error('proposed_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimated_participants" class="form-label">Perkiraan Jumlah Peserta <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('estimated_participants') is-invalid @enderror" 
                                           id="estimated_participants" 
                                           name="estimated_participants" 
                                           value="{{ old('estimated_participants', $proposal->estimated_participants) }}" 
                                           min="1" 
                                           required>
                                    @error('estimated_participants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="proposed_location" class="form-label">Lokasi Usulan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('proposed_location') is-invalid @enderror" 
                                   id="proposed_location" 
                                   name="proposed_location" 
                                   value="{{ old('proposed_location', $proposal->proposed_location) }}" 
                                   required>
                            @error('proposed_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required>{{ old('description', $proposal->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="objectives" class="form-label">Tujuan Kegiatan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('objectives') is-invalid @enderror" 
                                      id="objectives" 
                                      name="objectives" 
                                      rows="3" 
                                      required>{{ old('objectives', $proposal->objectives) }}</textarea>
                            @error('objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="benefits" class="form-label">Manfaat Kegiatan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                      id="benefits" 
                                      name="benefits" 
                                      rows="3" 
                                      required>{{ old('benefits', $proposal->benefits) }}</textarea>
                            @error('benefits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimated_budget" class="form-label">Perkiraan Anggaran</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control @error('estimated_budget') is-invalid @enderror" 
                                               id="estimated_budget" 
                                               name="estimated_budget" 
                                               value="{{ old('estimated_budget', $proposal->estimated_budget) }}" 
                                               min="0" 
                                               step="1000">
                                    </div>
                                    @error('estimated_budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kosongkan jika belum ada perkiraan anggaran</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="required_support" class="form-label">Dukungan yang Diperlukan</label>
                                    <select class="form-control @error('required_support') is-invalid @enderror" 
                                            id="required_support" 
                                            name="required_support">
                                        <option value="">Pilih Dukungan...</option>
                                        <option value="dana" {{ old('required_support', $proposal->required_support) == 'dana' ? 'selected' : '' }}>Dana</option>
                                        <option value="fasilitas" {{ old('required_support', $proposal->required_support) == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                                        <option value="personil" {{ old('required_support', $proposal->required_support) == 'personil' ? 'selected' : '' }}>Personil</option>
                                        <option value="dana_fasilitas" {{ old('required_support', $proposal->required_support) == 'dana_fasilitas' ? 'selected' : '' }}>Dana & Fasilitas</option>
                                        <option value="dana_personil" {{ old('required_support', $proposal->required_support) == 'dana_personil' ? 'selected' : '' }}>Dana & Personil</option>
                                        <option value="fasilitas_personil" {{ old('required_support', $proposal->required_support) == 'fasilitas_personil' ? 'selected' : '' }}>Fasilitas & Personil</option>
                                        <option value="semua" {{ old('required_support', $proposal->required_support) == 'semua' ? 'selected' : '' }}>Semua (Dana, Fasilitas, Personil)</option>
                                    </select>
                                    @error('required_support')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format number input for better UX
        const budgetInput = document.getElementById('estimated_budget');
        if (budgetInput) {
            budgetInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Date validation - prevent past dates
        const dateInput = document.getElementById('proposed_date');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi.');
            }
        });
    });
</script>
@endpush