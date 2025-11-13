@extends('layouts.app')

@section('title', $proposal->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $proposal->title }}</h1>
    <div>
        <span class="badge bg-{{ $proposal->status === 'approved' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : ($proposal->status === 'under_review' ? 'warning' : 'secondary')) }} me-2">
            Status: {{ $proposal->status }}
        </span>
        @if($proposal->canBeEditedByProposer())
            <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-sm btn-outline-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Proposal Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Usulan</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-primary">Deskripsi Kegiatan</h6>
                    <p>{{ $proposal->description }}</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Tujuan Kegiatan</h6>
                        <p>{{ $proposal->objectives }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Manfaat Kegiatan</h6>
                        <p>{{ $proposal->benefits }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Pelaksanaan</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Tanggal Diusulkan</strong></td>
                                <td>{{ $proposal->proposed_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi</strong></td>
                                <td>{{ $proposal->proposed_location }}</td>
                            </tr>
                            <tr>
                                <td><strong>Perkiraan Peserta</strong></td>
                                <td>{{ $proposal->estimated_participants }} orang</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Pendukung</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Perkiraan Anggaran</strong></td>
                                <td>
                                    @if($proposal->estimated_budget)
                                        Rp {{ number_format($proposal->estimated_budget, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dukungan Dibutuhkan</strong></td>
                                <td>
                                    @if($proposal->required_support)
                                        <span class="badge bg-info">{{ $proposal->required_support }}</span>
                                    @else
                                        <span class="text-muted">Tidak butuh dukungan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Diajukan</strong></td>
                                <td>{{ $proposal->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Information -->
        @if($proposal->status !== 'draft')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Review</h5>
            </div>
            <div class="card-body">
                @if($proposal->reviewer)
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Reviewer:</strong>
                            <p>{{ $proposal->reviewer->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Review:</strong>
                            <p>{{ $proposal->reviewed_at ? $proposal->reviewed_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                    
                    @if($proposal->review_notes)
                    <div class="mt-3">
                        <strong>Catatan Review:</strong>
                        <div class="alert alert-info mt-2">
                            {{ $proposal->review_notes }}
                        </div>
                    </div>
                    @endif
                @else
                    <p class="text-muted">Usulan sedang menunggu proses review.</p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Status Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Status Usulan</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-{{ $proposal->status === 'approved' ? 'check-circle text-success' : ($proposal->status === 'rejected' ? 'times-circle text-danger' : 'clock text-warning') }} fa-3x"></i>
                    </div>
                    <h5 class="text-{{ $proposal->status === 'approved' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($proposal->status) }}
                    </h5>
                    <p class="text-muted">
                        @if($proposal->status === 'draft')
                            Usulan masih dalam draft
                        @elseif($proposal->status === 'under_review')
                            Usulan sedang dalam proses review
                        @elseif($proposal->status === 'approved')
                            Usulan telah disetujui
                        @elseif($proposal->status === 'rejected')
                            Usulan telah ditolak
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Converted Activity -->
        @if($proposal->convertedActivity)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Kegiatan Terkonversi</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Usulan ini telah dikonversi menjadi kegiatan.
                </div>
                <a href="{{ route('activities.show', $proposal->convertedActivity) }}" 
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Kegiatan
                </a>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                    
                    @if($proposal->canBeEditedByProposer())
                        <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Edit Usulan
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection