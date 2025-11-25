@extends('admin.layouts.app')

@section('title', 'Manajemen Notifikasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bell me-2"></i> Manajemen Notifikasi
    </h1>
    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Buat Notifikasi
    </a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Penerima</th>
                    <th>Judul</th>
                    <th>Channel</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr>
                    <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                    <td>{{ $notification->user->name ?? 'System' }}</td>
                    <td>{{ $notification->title }}</td>
                    <td>{{ ucfirst($notification->channel) }}</td>
                    <td>
                        <span class="badge bg-{{ $notification->status == 'failed' ? 'danger' : ($notification->status == 'sent' ? 'success' : 'warning') }}">
                            {{ ucfirst($notification->status) }}
                        </span>
                    </td>
                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Retry jika status FAILED --}}
                            @if($notification->status === 'failed')
                            <form action="{{ route('admin.notifications.retry', $notification) }}" method="POST" class="d-inline">
                                @csrf
                                <button onclick="return confirm('Retry notifikasi?')" class="btn btn-warning">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus notifikasi?')" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada notifikasi.</p>
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                            Buat Notifikasi Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-between">
            <small class="text-muted">
                Menampilkan {{ $notifications->firstItem() ?? 0 }} - {{ $notifications->lastItem() ?? 0 }}
                dari {{ $notifications->total() }} notifikasi
            </small>
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
