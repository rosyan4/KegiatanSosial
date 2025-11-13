@extends('admin.layouts.app')

@section('title', $proposal->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Proposal</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            @if($proposal->isPending() || $proposal->isUnderReview() || $proposal->isPendingRevision())
                <a href="{{ route('admin.proposals.review', $proposal) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i> Review Proposal
                </a>
            @endif
            
            @if($proposal->isPending())
                <form action="{{ route('admin.proposals.markUnderReview', $proposal) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Tandai proposal sedang direview?')">
                        <i class="fas fa-play me-2"></i> Mulai Review
                    </button>
                </form>
            @endif
        </div>
        <a href="{{ route('admin.proposals.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Information -->
    <div class="col-lg-8">
        <!-- Proposal Header -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-lightbulb me-2"></i>{{ $proposal->title }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="140">Status:</th>
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
                                            'pending' => 'Menunggu Review',
                                            'under_review' => 'Sedang Direview',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'pending_revision' => 'Menunggu Revisi'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$proposal->status] }} fs-6">
                                        {{ $statusLabels[$proposal->status] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Diajukan:</th>
                                <td>{{ $proposal->proposed_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Estimasi Peserta:</th>
                                <td>
                                    @if($proposal->estimated_participants)
                                        <span class="badge bg-primary">{{ $proposal->estimated_participants }} orang</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Lokasi Usulan:</th>
                                <td>{{ $proposal->proposed_location ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="140">Pengusul:</th>
                                <td>{{ $proposal->proposer->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $proposal->proposer->email }}</td>
                            </tr>
                            <tr>
                                <th>Reviewer:</th>
                                <td>
                                    @if($proposal->reviewer)
                                        {{ $proposal->reviewer->name }}
                                    @else
                                        <span class="text-muted">Belum ada reviewer</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Review:</th>
                                <td>
                                    @if($proposal->reviewed_at)
                                        {{ $proposal->reviewed_at->format('d F Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <h6>Deskripsi Proposal:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($proposal->description)) !!}
                    </div>
                </div>

                @if($proposal->objectives)
                <div class="mt-3">
                    <h6>Tujuan Kegiatan:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($proposal->objectives)) !!}
                    </div>
                </div>
                @endif

                @if($proposal->expected_outcomes)
                <div class="mt-3">
                    <h6>Hasil yang Diharapkan:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($proposal->expected_outcomes)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Review Information -->
        @if($proposal->isReviewed())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Hasil Review</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Status Akhir:</strong>
                        <span class="badge bg-{{ $proposal->status === 'approved' ? 'success' : 'danger' }} ms-2">
                            {{ $proposal->status === 'approved' ? 'DISETUJUI' : 'DITOLAK' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Reviewer:</strong> {{ $proposal->reviewer->name }}
                    </div>
                </div>

                @if($proposal->admin_notes)
                <div class="mt-3">
                    <strong>Catatan Admin:</strong>
                    <div class="border rounded p-3 bg-light mt-1">
                        {!! nl2br(e($proposal->admin_notes)) !!}
                    </div>
                </div>
                @endif

                @if($proposal->rejection_reason)
                <div class="mt-3">
                    <strong>Alasan Penolakan:</strong>
                    <div class="border rounded p-3 bg-light mt-1">
                        {!! nl2br(e($proposal->rejection_reason)) !!}
                    </div>
                </div>
                @endif

                <div class="mt-3">
                    <strong>Tanggal Review:</strong> 
                    {{ $proposal->reviewed_at->format('d F Y H:i') }}
                </div>
            </div>
        </div>
        @endif

        <!-- Converted Activity -->
        @if($proposal->convertedActivity)
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Kegiatan yang Dihasilkan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-info-circle me-2"></i>
                    Proposal ini telah berhasil dikonversi menjadi kegiatan.
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th width="140">Judul Kegiatan:</th>
                        <td>{{ $proposal->convertedActivity->title }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai:</th>
                        <td>{{ $proposal->convertedActivity->start_date->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi:</th>
                        <td>{{ $proposal->convertedActivity->location }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ $proposal->convertedActivity->status === 'published' ? 'success' : 'secondary' }}">
                                {{ $proposal->convertedActivity->getStatusLabel() }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="text-center mt-3">
                    <a href="{{ route('admin.activities.show', $proposal->convertedActivity) }}" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i> Lihat Kegiatan
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($proposal->isPending() || $proposal->isUnderReview() || $proposal->isPendingRevision())
                        <a href="{{ route('admin.proposals.review', $proposal) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Review Proposal
                        </a>
                    @endif

                    @if($proposal->isPending())
                        <form action="{{ route('admin.proposals.markUnderReview', $proposal) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Tandai proposal sedang direview?')">
                                <i class="fas fa-play me-2"></i> Mulai Review
                            </button>
                        </form>
                    @endif

                    @if($proposal->isApproved() && !$proposal->convertedActivity)
                        <a href="{{ route('admin.proposals.review', $proposal) }}" class="btn btn-success">
                            <i class="fas fa-magic me-2"></i> Konversi ke Kegiatan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Proposal Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $proposal->proposed_date ? 'active' : '' }}">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Diajukan</h6>
                            <small class="text-muted">
                                {{ $proposal->proposed_date->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>

                    @if($proposal->reviewed_at)
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-{{ $proposal->status === 'approved' ? 'success' : 'danger' }}"></div>
                        <div class="timeline-content">
                            <h6>{{ $proposal->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</h6>
                            <small class="text-muted">
                                {{ $proposal->reviewed_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    @endif

                    @if($proposal->convertedActivity)
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>Dikonversi ke Kegiatan</h6>
                            <small class="text-muted">
                                {{ $proposal->convertedActivity->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Proposal Metadata -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>ID Proposal:</strong></td>
                        <td>#{{ $proposal->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $proposal->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diupdate:</strong></td>
                        <td>{{ $proposal->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($proposal->budget)
                    <tr>
                        <td><strong>Anggaran:</strong></td>
                        <td>Rp {{ number_format($proposal->budget, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-content {
        padding-left: 10px;
    }
    .timeline-item:not(.active) .timeline-marker {
        background-color: #dee2e6 !important;
    }
    .timeline-item:not(.active) .timeline-content {
        color: #6c757d;
    }
</style>
@endpush