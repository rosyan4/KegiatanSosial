@extends('admin.layouts.app')

@section('title', 'Manajemen Proposal Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Proposal Kegiatan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-cog me-2"></i> Aksi Massal
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item bulk-action" href="#" data-action="mark_review"><i class="fas fa-eye me-2"></i> Tandai Direview</a></li>
                <li><a class="dropdown-item bulk-action" href="#" data-action="approve"><i class="fas fa-check me-2"></i> Setujui</a></li>
                <li><a class="dropdown-item bulk-action" href="#" data-action="reject"><i class="fas fa-times me-2"></i> Tolak</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proposals->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-inbox fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Direview</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['under_review'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-search fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Disetujui</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Ditolak</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Menunggu Revisi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_revision'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-edit fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau deskripsi...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="pending_revision" {{ request('status') == 'pending_revision' ? 'selected' : '' }}>Pending Revision</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="proposed_date" class="form-label">Tanggal Diajukan</label>
                <input type="date" class="form-control" id="proposed_date" name="proposed_date" value="{{ request('proposed_date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Proposals Table -->
<div class="card">
    <div class="card-body">
        @if(Route::has('admin.proposals.bulkAction'))
        <form id="bulkActionForm" method="POST" action="{{ route('admin.proposals.bulkAction') }}">
            @csrf
        @else
        <form id="bulkActionForm" method="POST" action="#">
            @csrf
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Fitur aksi massal belum tersedia. Silakan gunakan aksi individual.
            </div>
        @endif
        
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th width="60">#</th>
                            <th>Judul Proposal</th>
                            <th>Pengusul</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th>Reviewer</th>
                            <th>Estimasi Peserta</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $proposal)
                        <tr>
                            <td>
                                @if(Route::has('admin.proposals.bulkAction'))
                                <input type="checkbox" name="proposals[]" value="{{ $proposal->id }}" class="proposal-checkbox">
                                @else
                                <input type="checkbox" disabled title="Fitur aksi massal tidak tersedia">
                                @endif
                            </td>
                            <td>{{ $loop->iteration + ($proposals->currentPage() - 1) * $proposals->perPage() }}</td>
                            <td>
                                <strong>{{ $proposal->title }}</strong>
                                @if($proposal->convertedActivity)
                                    <br>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Telah dikonversi ke kegiatan
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ $proposal->proposer->name }}</div>
                                        <small class="text-muted">{{ $proposal->proposer->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $proposal->proposed_date->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $proposal->proposed_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'under_review' => 'info',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'pending_revision' => 'secondary'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'under_review' => 'Direview',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'pending_revision' => 'Revisi'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$proposal->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$proposal->status] ?? $proposal->status }}
                                </span>
                            </td>
                            <td>
                                @if($proposal->reviewer)
                                    {{ $proposal->reviewer->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($proposal->estimated_participants)
                                    <span class="badge bg-primary">{{ $proposal->estimated_participants }} orang</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.proposals.show', $proposal) }}" class="btn btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($proposal->isPending())
                                        <a href="{{ route('admin.proposals.review', $proposal) }}" class="btn btn-warning" title="Review Proposal">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        @if(Route::has('admin.proposals.markUnderReview'))
                                        <form action="{{ route('admin.proposals.markUnderReview', $proposal) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" title="Tandai Sedang Direview" 
                                                    onclick="return confirm('Tandai proposal sedang direview?')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                        @endif
                                    @endif

                                    @if($proposal->isUnderReview() || $proposal->isPendingRevision())
                                        <a href="{{ route('admin.proposals.review', $proposal) }}" class="btn btn-{{ $proposal->isUnderReview() ? 'success' : 'warning' }}" 
                                           title="{{ $proposal->isUnderReview() ? 'Lanjutkan Review' : 'Review Revisi' }}">
                                            <i class="fas fa-{{ $proposal->isUnderReview() ? 'edit' : 'redo' }}"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-lightbulb fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada proposal kegiatan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Action Buttons (shown when checkboxes are selected) -->
            @if(Route::has('admin.proposals.bulkAction'))
            <div id="bulkActions" class="mt-3 p-3 bg-light rounded" style="display: none;">
                <div class="d-flex align-items-center">
                    <span class="me-3" id="selectedCount">0 proposal dipilih</span>
                    <button type="submit" name="action" value="mark_review" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i> Tandai Direview
                    </button>
                    <button type="submit" name="action" value="approve" class="btn btn-success me-2">
                        <i class="fas fa-check me-2"></i> Setujui
                    </button>
                    <button type="submit" name="action" value="reject" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i> Tolak
                    </button>
                    <button type="button" id="clearSelection" class="btn btn-secondary ms-3">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                </div>
            </div>
            @endif
        </form>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $proposals->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select all functionality
        $('#selectAll').change(function() {
            $('.proposal-checkbox').prop('checked', this.checked);
            updateBulkActions();
        });

        // Individual checkbox change
        $('.proposal-checkbox').change(function() {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            }
            updateBulkActions();
        });

        // Update bulk actions visibility
        function updateBulkActions() {
            const selectedCount = $('.proposal-checkbox:checked').length;
            if (selectedCount > 0) {
                $('#selectedCount').text(selectedCount + ' proposal dipilih');
                $('#bulkActions').show();
            } else {
                $('#bulkActions').hide();
            }
        }

        // Clear selection
        $('#clearSelection').click(function() {
            $('.proposal-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateBulkActions();
        });

        // Dropdown bulk actions
        $('.bulk-action').click(function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            const selectedCount = $('.proposal-checkbox:checked').length;
            
            if (selectedCount > 0) {
                // Set the action and submit form
                $('button[name="action"]').val(action);
                
                let message = `Yakin ingin ${getActionText(action)} ${selectedCount} proposal?`;
                
                if (action === 'reject') {
                    message += '\n\nProposal yang ditolak tidak dapat dikembalikan.';
                }

                if (confirm(message)) {
                    $('#bulkActionForm').submit();
                }
            } else {
                alert('Pilih minimal satu proposal terlebih dahulu.');
            }
        });

        function getActionText(action) {
            const actions = {
                'mark_review': 'menandai sedang direview',
                'approve': 'menyetujui',
                'reject': 'menolak'
            };
            return actions[action] || 'melakukan aksi pada';
        }

        // Form submission confirmation
        $('#bulkActionForm').submit(function(e) {
            @if(!Route::has('admin.proposals.bulkAction'))
            e.preventDefault();
            alert('Fitur aksi massal belum tersedia. Silakan gunakan aksi individual pada setiap proposal.');
            return false;
            @else
            const selectedCount = $('.proposal-checkbox:checked').length;
            const action = $('button[name="action"]').val();
            
            if (selectedCount === 0) {
                alert('Pilih minimal satu proposal terlebih dahulu.');
                return false;
            }

            let message = `Yakin ingin ${getActionText(action)} ${selectedCount} proposal?`;
            
            if (action === 'reject') {
                message += '\n\nProposal yang ditolak tidak dapat dikembalikan.';
            }

            return confirm(message);
            @endif
        });
    });
</script>
@endpush