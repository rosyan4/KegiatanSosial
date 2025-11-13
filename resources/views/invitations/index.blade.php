@extends('layouts.app')

@section('title', 'Undangan Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Undangan Kegiatan</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Undangan</h5>
    </div>
    <div class="card-body">
        @if($invitations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Pengundang</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Status</th>
                            <th>Tanggal Undangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invitations as $invitation)
                        <tr>
                            <td>
                                <strong>{{ $invitation->activity->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $invitation->activity->category->name }}</small>
                            </td>
                            <td>{{ $invitation->activity->creator->name }}</td>
                            <td>
                                {{ $invitation->activity->start_date->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ $invitation->activity->start_date->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $invitation->status === 'accepted' ? 'success' : ($invitation->status === 'declined' ? 'danger' : 'warning') }}">
                                    {{ $invitation->status }}
                                </span>
                            </td>
                            <td>{{ $invitation->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('invitations.show', $invitation) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $invitations->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada undangan</h5>
                <p class="text-muted">Semua undangan telah ditangani</p>
            </div>
        @endif
    </div>
</div>
@endsection