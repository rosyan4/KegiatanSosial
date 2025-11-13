@extends('admin.layouts.app')

@section('title', 'Manajemen Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kegiatan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Buat Kegiatan
            </a>
            <a href="{{ route('admin.activities.calendar') }}" class="btn btn-info">
                <i class="fas fa-calendar me-2"></i> Kalender
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">

            <!-- Pencarian -->
            <div class="col-md-3">
                <label for="search" class="form-label">Pencarian</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari judul...">
            </div>

            <!-- Status -->
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Tipe -->
            <div class="col-md-2">
                <label for="type" class="form-label">Tipe</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Semua Tipe</option>
                    <option value="umum" {{ request('type') == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="khusus" {{ request('type') == 'khusus' ? 'selected' : '' }}>Khusus</option>
                </select>
            </div>

            <!-- Kategori -->
            @if(isset($categories) && $categories->count())
                <div class="col-md-2">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Tombol Filter & Reset -->
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                    <i class="fas fa-rotate-right me-1"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>

<!-- Activities Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal Mulai</th>
                        <th>Lokasi</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Pembuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>
                            <strong>{{ $activity->title }}</strong>
                            @if($activity->requires_attendance_confirmation)
                                <i class="fas fa-clipboard-check text-info ms-1" title="Perlu konfirmasi kehadiran"></i>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $activity->category->color }}; color: white;">
                                <i class="{{ $activity->category->icon }} me-1"></i>
                                {{ $activity->category->name }}
                            </span>
                        </td>
                        <td>
                            {{ $activity->start_date->format('d/m/Y H:i') }}
                        </td>
                        <td>{{ $activity->location }}</td>
                        <td>
                            <span class="badge bg-{{ $activity->type === 'umum' ? 'info' : 'warning' }}">
                                {{ $activity->type }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'draft' => 'secondary',
                                    'published' => 'success',
                                    'cancelled' => 'danger',
                                    'completed' => 'primary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$activity->status] ?? 'secondary' }}">
                                {{ $activity->getStatusLabel() }}
                            </span>
                        </td>
                        <td>{{ $activity->creator->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($activity->isDraft())
                                    <form action="{{ route('admin.activities.publish', $activity) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Publish" onclick="return confirm('Yakin ingin mempublish kegiatan ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($activity->isPublished())
                                    <form action="{{ route('admin.activities.cancel', $activity) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" title="Batalkan" onclick="return confirm('Yakin ingin membatalkan kegiatan ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($activity->isPublished() || $activity->isOngoing())
                                    <form action="{{ route('admin.activities.complete', $activity) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" title="Tandai Selesai" onclick="return confirm('Yakin ingin menandai kegiatan sebagai selesai?')">
                                            <i class="fas fa-flag-checkered"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada kegiatan ditemukan.</p>
                            <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Buat Kegiatan Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection