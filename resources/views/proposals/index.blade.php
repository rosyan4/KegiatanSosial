@extends('layouts.app')

@section('title', 'Usulan Kegiatan Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Usulan Kegiatan Saya</h1>
    <a href="{{ route('proposals.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Usulan Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Usulan</h5>
    </div>
    <div class="card-body">
        @if($proposals->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul Usulan</th>
                            <th>Tanggal Diajukan</th>
                            <th>Tanggal Diusulkan</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Reviewer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposals as $proposal)
                        <tr>
                            <td>
                                <strong>{{ $proposal->title }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($proposal->description, 50) }}</small>
                            </td>
                            <td>{{ $proposal->created_at->format('d M Y') }}</td>
                            <td>{{ $proposal->proposed_date->format('d M Y') }}</td>
                            <td>{{ $proposal->proposed_location }}</td>
                            <td>
                                <span class="badge bg-{{ $proposal->status === 'approved' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : ($proposal->status === 'under_review' ? 'warning' : 'secondary')) }}">
                                    {{ $proposal->status }}
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
                                <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                                @if($proposal->canBeEditedByProposer())
                                    <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-sm btn-outline-warning">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $proposals->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-lightbulb fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada usulan kegiatan</h5>
                <p class="text-muted">Ajukan usulan kegiatan pertama Anda untuk mengembangkan komunitas</p>
                <a href="{{ route('proposals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Ajukan Usulan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection