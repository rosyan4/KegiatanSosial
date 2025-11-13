@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kategori</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Kategori
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kategori</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kategori Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $categories->where('is_active', true)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Kegiatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $categories->sum('activities_count') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kegiatan Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $categories->sum('active_activities_count') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-running fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Kategori</th>
                        <th>Warna & Ikon</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Kegiatan</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="color-badge me-2" 
                                     style="background-color: {{ $category->color }}; width: 20px; height: 20px; border-radius: 4px;"
                                     title="{{ $category->color }}"></div>
                                @if($category->icon)
                                    <i class="{{ $category->icon }} text-muted"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($category->description)
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                      title="{{ $category->description }}">
                                    {{ $category->description }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge bg-primary">{{ $category->activities_count }} Total</span>
                                <span class="badge bg-success mt-1">{{ $category->active_activities_count }} Aktif</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $category->sort_order ?? 'Default' }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(Route::has('admin.categories.toggleStatus'))
                                <form action="{{ route('admin.categories.toggleStatus', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }}" 
                                            title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            onclick="return confirm('Yakin ingin {{ $category->is_active ? 'menonaktifkan' : 'mengaktifkan' }} kategori ini?')">
                                        <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                @else
                                <!-- Fallback jika route toggleStatus belum ada -->
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $category->is_active ? 0 : 1 }}">
                                    <button type="submit" class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }}" 
                                            title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            onclick="return confirm('Yakin ingin {{ $category->is_active ? 'menonaktifkan' : 'mengaktifkan' }} kategori ini?')">
                                        <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus" 
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                            {{ $category->activities_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            
                            @if($category->activities_count > 0)
                            <small class="text-danger d-block mt-1">Tidak dapat dihapus karena memiliki kegiatan</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada kategori yang dibuat.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Buat Kategori Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .color-badge {
        border: 2px solid #dee2e6;
        cursor: pointer;
    }
    .color-badge:hover {
        border-color: #007bff;
        transform: scale(1.1);
        transition: all 0.2s;
    }
</style>
@endpush