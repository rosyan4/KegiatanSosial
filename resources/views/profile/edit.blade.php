@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Profil</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Profil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($user->phone_verified_at)
                                <div class="form-text text-success">
                                    <i class="fas fa-check-circle me-1"></i>Nomor telepon telah terverifikasi
                                </div>
                            @else
                                <div class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Nomor telepon belum terverifikasi
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile_photo" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                   id="profile_photo" name="profile_photo" accept="image/*">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: JPG, PNG, GIF. Maksimal: 2MB</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male" 
                                           value="male" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female" 
                                           value="female" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Perempuan</label>
                                </div>
                            </div>
                            @error('gender')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                        
                        @if($user->profile_photo)
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="event.preventDefault(); document.getElementById('delete-photo-form').submit();">
                            <i class="fas fa-trash me-1"></i>Hapus Foto
                        </button>
                        @endif
                    </div>
                </form>

                @if($user->profile_photo)
                <form id="delete-photo-form" action="{{ route('profile.delete-photo') }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
                @endif
            </div>
        </div>

        <!-- Account Deletion -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0 text-danger">Hapus Akun</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Setelah menghapus akun, semua data Anda akan dihapus secara permanen. 
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="fas fa-trash me-1"></i>Hapus Akun
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Profile Photo Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Foto Profil</h5>
            </div>
            <div class="card-body text-center">
                @if($user->profile_photo)
                    <img src="{{ Storage::url($user->profile_photo) }}" 
                         alt="Profile Photo" class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-muted"></i>
                    </div>
                @endif
                <p class="text-muted">Foto profil akan ditampilkan di aplikasi</p>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Terdaftar Sejak</strong></td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email Terverifikasi</strong></td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-warning">Belum</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Telepon Terverifikasi</strong></td>
                        <td>
                            @if($user->phone_verified_at)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-warning">Belum</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Semua data Anda akan dihapus secara permanen.
                </p>
                
                <form action="{{ route('profile.destroy') }}" method="POST" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password" name="password" required 
                               placeholder="Masukkan password Anda">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Hapus Akun
                </button>
            </div>
        </div>
    </div>
</div>
@endsection