@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Pengguna</h3>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-body border-bottom">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="warga" {{ request('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" id="is_active" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="rt" class="form-label">RT</label>
                <input type="text" name="rt" id="rt" class="form-control" value="{{ request('rt') }}" placeholder="RT">
            </div>
            <div class="col-md-2">
                <label for="rw" class="form-label">RW</label>
                <input type="text" name="rw" id="rw" class="form-control" value="{{ request('rw') }}" placeholder="RW">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-refresh"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>RT/RW</th>
                        <th>Kegiatan Dibuat</th>
                        <th>Konfirmasi Hadir</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            @if($user->rt && $user->rw)
                                {{ $user->rt }}/{{ $user->rw }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $user->created_activities_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $user->attendance_confirmations_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }}" 
                                    onclick="toggleStatus({{ $user->id }})" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'times' : 'check' }}"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-2x mb-3"></i>
                                <p>Tidak ada data pengguna</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <select id="bulk-action" class="form-select me-2" style="width: auto;">
                        <option value="">Pilih Aksi</option>
                        <option value="activate">Aktifkan</option>
                        <option value="deactivate">Nonaktifkan</option>
                        <option value="delete">Hapus</option>
                    </select>
                    <button type="button" id="apply-bulk-action" class="btn btn-outline-primary">
                        Terapkan
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select all checkbox
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk action
document.getElementById('apply-bulk-action').addEventListener('click', function() {
    const action = document.getElementById('bulk-action').value;
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (!action) {
        alert('Pilih aksi terlebih dahulu');
        return;
    }

    if (selectedUsers.length === 0) {
        alert('Pilih minimal satu pengguna');
        return;
    }

    if (action === 'delete' && !confirm('Apakah Anda yakin ingin menghapus pengguna yang dipilih?')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.users.bulk-action") }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);

    selectedUsers.forEach(userId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'users[]';
        input.value = userId;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
});

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

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush