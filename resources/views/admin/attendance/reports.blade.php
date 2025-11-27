@extends('admin.layouts.app')

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>Laporan Kehadiran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Kehadiran
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Filter Laporan
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ request('start_date') }}"
                       max="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ request('end_date') }}"
                       max="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label">Kategori Kegiatan</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\ActivityCategory::active()->get() as $category)
                        <option value="{{ $category->id }}" 
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-grid gap-2 w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Terapkan Filter
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date', 'category_id']))
                    <a href="{{ route('admin.attendance.reports') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activities Reports Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>Data Kehadiran per Kegiatan
        </h5>
        <div class="text-muted small">
            Menampilkan {{ $activities->firstItem() ?? 0 }} - {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} kegiatan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Kegiatan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Konfirmasi</th>
                        <th>Check-in/out</th>
                        <th>Terverifikasi</th>
                        <th>Rate Kehadiran</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    @php
                        $confirmations = $activity->attendanceConfirmations ?? collect();
                        $logs = $activity->attendanceLogs ?? collect();
                        
                        $confirmedCount = $confirmations->count();
                        $presentCount = $confirmations->where('status', 'hadir')->count();
                        $checkedInCount = $logs->whereNotNull('check_in_time')->count();
                        $verifiedCount = $logs->where('is_verified', true)->count();
                        
                        $attendanceRate = $confirmedCount > 0 ? ($presentCount / $confirmedCount) * 100 : 0;
                    @endphp
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}
                        </td>
                        <td>
                            <div>
                                <strong class="d-block">{{ $activity->title }}</strong>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $activity->location }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $activity->category->color ?? '#6c757d' }}; color: white;">
                                <i class="{{ $activity->category->icon ?? 'fas fa-tag' }} me-1"></i>
                                {{ $activity->category->name ?? 'Tidak ada kategori' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-nowrap">
                                <strong>{{ $activity->start_date->format('d/m/Y') }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $activity->start_date->format('H:i') }} - {{ $activity->end_date->format('H:i') }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-success fw-bold">
                                    <i class="fas fa-user-check me-1"></i>{{ $presentCount }} Hadir
                                </span>
                                <span class="text-muted small">
                                    dari {{ $confirmedCount }} konfirmasi
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-primary">
                                    <i class="fas fa-sign-in-alt me-1"></i>{{ $checkedInCount }} Check-in
                                </span>
                                <span class="text-info">
                                    <i class="fas fa-sign-out-alt me-1"></i>{{ $logs->whereNotNull('check_out_time')->count() }} Check-out
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($verifiedCount > 0)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>{{ $verifiedCount }}
                            </span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $attendanceRate >= 70 ? 'success' : ($attendanceRate >= 40 ? 'warning' : 'danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $attendanceRate }}%"
                                             title="{{ number_format($attendanceRate, 1) }}%">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-nowrap">
                                    <small class="fw-bold text-{{ $attendanceRate >= 70 ? 'success' : ($attendanceRate >= 40 ? 'warning' : 'danger') }}">
                                        {{ number_format($attendanceRate, 1) }}%
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                {{ $presentCount }}/{{ max($confirmedCount, 1) }} peserta
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm w-100">
                                @if(Route::has('admin.attendance.showActivity'))
                                <a href="{{ route('admin.attendance.showActivity', $activity) }}" 
                                   class="btn btn-outline-primary" 
                                   title="Lihat Detail Kehadiran">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                @if(Route::has('admin.attendance.exportAttendance'))
                                <a href="{{ route('admin.attendance.exportAttendance', $activity) }}" 
                                   class="btn btn-outline-success" 
                                   title="Export Data Kehadiran">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak Ada Data Laporan</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['start_date', 'end_date', 'category_id']))
                                        Tidak ditemukan data kehadiran untuk filter yang dipilih.
                                    @else
                                        Belum ada data kehadiran yang tercatat.
                                    @endif
                                </p>
                                @if(request()->hasAny(['start_date', 'end_date', 'category_id']))
                                <a href="{{ route('admin.attendance.reports') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-times me-2"></i> Reset Filter
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Menampilkan {{ $activities->firstItem() }} - {{ $activities->lastItem() }} dari {{ $activities->total() }} kegiatan
            </div>
            <div>
                {{ $activities->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Summary Cards -->
@if($activities->count() > 0)
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                <h5>Ringkasan Periode</h5>
                @if(request('start_date') && request('end_date'))
                <p class="mb-1">
                    <strong>Periode:</strong><br>
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - 
                    {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                </p>
                @endif
                <p class="mb-0">
                    <strong>Total Kegiatan:</strong> {{ $activities->total() }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x text-success mb-2"></i>
                <h5>Partisipasi</h5>
                <p class="mb-1">
                    <strong>Rata-rata Kehadiran:</strong><br>
                    {{ number_format($overallStats['average_attendance_rate'] ?? 0, 1) }}%
                </p>
                <p class="mb-0">
                    <strong>Total Partisipasi:</strong> {{ $overallStats['total_participations'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body text-center">
                <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                <h5>Kategori Terpopuler</h5>
                @php
                    $popularCategory = collect($activities->items())->groupBy('category_id')->sortDesc()->first();
                @endphp
                @if($popularCategory)
                <p class="mb-1">
                    <strong>Kategori:</strong><br>
                    {{ $popularCategory->first()->category->name ?? 'Tidak ada' }}
                </p>
                <p class="mb-0">
                    <strong>Jumlah Kegiatan:</strong> {{ $popularCategory->count() }}
                </p>
                @else
                <p class="text-muted">Tidak ada data</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Export Section -->
@if($activities->count() > 0 && Route::has('admin.attendance.exportAll'))
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-download me-2"></i>Ekspor Data Laporan
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                        <h5 class="text-success">Export ke Excel/CSV</h5>
                        <p class="text-muted">Download data laporan dalam format spreadsheet untuk analisis lebih lanjut</p>
                        <form method="POST" action="{{ route('admin.attendance.exportAll') }}">
                            @csrf
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-file-download me-2"></i> Export Data Lengkap
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card h-100 border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-pie fa-3x text-info mb-3"></i>
                        <h5 class="text-info">Analisis Statistik</h5>
                        <p class="text-muted">Lihat analisis mendalam dan visualisasi data kehadiran</p>
                        <button type="button" class="btn btn-info btn-lg" id="showAnalyticsBtn">
                            <i class="fas fa-chart-bar me-2"></i> Tampilkan Analitik
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Data yang diexport akan mencakup semua kegiatan dalam periode yang difilter, 
            termasuk detail konfirmasi kehadiran dan log check-in/out.
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
    }
    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Date range validation
        $('#start_date, #end_date').change(function() {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($('#end_date').val());
            
            if ($('#start_date').val() && $('#end_date').val() && endDate < startDate) {
                alert('Tanggal selesai harus setelah tanggal mulai');
                $('#end_date').val('');
            }
            
            // Set maximum date for start_date to end_date
            if ($('#end_date').val()) {
                $('#start_date').attr('max', $('#end_date').val());
            }
            
            // Set minimum date for end_date to start_date
            if ($('#start_date').val()) {
                $('#end_date').attr('min', $('#start_date').val());
            }
        });

        // Show analytics modal
        $('#showAnalyticsBtn').click(function(e) {
            e.preventDefault();
            
            // Simple analytics summary
            const totalActivities = {{ $activities->total() }};
            const avgAttendance = {{ number_format($overallStats['average_attendance_rate'] ?? 0, 1) }};
            const totalParticipants = {{ $overallStats['total_participations'] ?? 0 }};
            
            const analyticsHtml = `
                <div class="alert alert-info">
                    <h5><i class="fas fa-chart-bar me-2"></i>Ringkasan Analitik</h5>
                    <hr>
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="text-primary">${totalActivities}</h3>
                            <small>Total Kegiatan</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success">${avgAttendance}%</h3>
                            <small>Rata-rata Hadir</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning">${totalParticipants}</h3>
                            <small>Total Partisipasi</small>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Fitur analitik lengkap akan segera tersedia dengan grafik dan visualisasi interaktif.
                    </p>
                </div>
            `;
            
            // Show in modal or alert
            if (typeof bootstrap !== 'undefined') {
                // Create and show modal
                const modalHtml = `
                    <div class="modal fade" id="analyticsModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Analitik Kehadiran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ${analyticsHtml}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if any
                $('#analyticsModal').remove();
                $('body').append(modalHtml);
                
                const modal = new bootstrap.Modal(document.getElementById('analyticsModal'));
                modal.show();
            } else {
                // Fallback to alert
                alert(`Analitik Kehadiran:\n\nTotal Kegiatan: ${totalActivities}\nRata-rata Hadir: ${avgAttendance}%\nTotal Partisipasi: ${totalParticipants}`);
            }
        });

        // Auto-submit form when category changes (optional)
        $('#category_id').change(function() {
            if ($(this).val()) {
                // Optional: auto-submit when category is selected
                // $(this).closest('form').submit();
            }
        });
    });
</script>
@endpush