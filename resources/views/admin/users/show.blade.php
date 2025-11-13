@extends('admin.layouts.app')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary rounded-circle text-white d-inline-flex align-items-center justify-content-center mb-3" 
                    style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="mb-3">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }} me-1">
                        {{ $user->role }}
                    </span>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                @if($user->phone)
                <p><i class="fas fa-phone me-2"></i>{{ $user->phone }}</p>
                @endif

                @if($user->rt && $user->rw)
                <p><i class="fas fa-map-marker-alt me-2"></i>RT {{ $user->rt }}/RW {{ $user->rw }}</p>
                @endif

                @if($user->address)
                <p class="text-muted"><small>{{ $user->address }}</small></p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} btn-sm" 
                        onclick="toggleStatus({{ $user->id }})">
                        <i class="fas fa-{{ $user->is_active ? 'times' : 'check' }}"></i> 
                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Statistik Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h4 class="text-primary">{{ $attendanceStats['total'] }}</h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h4 class="text-success">{{ $attendanceStats['hadir'] }}</h4>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h4 class="text-danger">{{ $attendanceStats['tidak_hadir'] }}</h4>
                            <small class="text-muted">Tidak Hadir</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Activities Created -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Kegiatan yang Dibuat</h5>
                <span class="badge bg-primary">{{ $user->createdActivities->count() }} kegiatan</span>
            </div>
            <div class="card-body">
                @if($user->createdActivities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->createdActivities as $activity)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.activities.show', $activity) }}" class="text-decoration-none">
                                        {{ $activity->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $activity->category->color }}; color: white;">
                                        {{ $activity->category->name }}
                                    </span>
                                </td>
                                <td>{{ $activity->start_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $activity->status == 'published' ? 'success' : ($activity->status == 'draft' ? 'secondary' : 'warning') }}">
                                        {{ $activity->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">Belum membuat kegiatan</p>
                @endif
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Riwayat Konfirmasi Kehadiran</h5>
                <span class="badge bg-secondary">{{ $user->attendanceConfirmations->count() }} konfirmasi</span>
            </div>
            <div class="card-body">
                @if($user->attendanceConfirmations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Status</th>
                                <th>Tanggal Konfirmasi</th>
                                <th>Tamu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->attendanceConfirmations as $confirmation)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.activities.show', $confirmation->activity) }}" class="text-decoration-none">
                                        {{ $confirmation->activity->title }}
                                    </a>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'hadir' => 'success',
                                            'tidak_hadir' => 'danger',
                                            'mungkin' => 'warning'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$confirmation->status] ?? 'secondary' }}">
                                        {{ $confirmation->status }}
                                    </span>
                                </td>
                                <td>{{ $confirmation->confirmed_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    @if($confirmation->number_of_guests > 0)
                                    <span class="badge bg-info">{{ $confirmation->number_of_guests }} tamu</span>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">Belum ada konfirmasi kehadiran</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(userId) {
    if (confirm('Apakah Anda yakin ingin mengubah status pengguna?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}/toggle-status`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush