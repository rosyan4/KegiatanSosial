@extends('admin.layouts.app')

@section('title', 'Manajemen Proposal Kegiatan')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Proposal Kegiatan</h1>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Pencarian</label>
                <input type="text" class="form-control" name="search"
                    value="{{ request('search') }}" placeholder="Cari judul atau deskripsi...">
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Pending','under_review'=>'Under Review','approved'=>'Approved','rejected'=>'Rejected','pending_revision'=>'Pending Revision'] as $val=>$label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Tanggal Diajukan</label>
                <input type="date" class="form-control" name="proposed_date" value="{{ request('proposed_date') }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Jika tidak ada data --}}
@if($proposals->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <h4 class="text-muted mb-1">Belum ada proposal.</h4>
        <p class="text-secondary small mb-0">Proposal hanya dapat diajukan oleh pengguna (mahasiswa / UKM).</p>
    </div>
</div>
@endif

@if(!$proposals->isEmpty())
{{-- Tabel --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Pengusul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Reviewer</th>
                        <th>Peserta</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proposals as $proposal)
                    <tr>
                        <td>{{ $loop->iteration + ($proposals->currentPage()-1) * $proposals->perPage() }}</td>

                        <td><strong>{{ $proposal->title }}</strong></td>

                        <td>
                            {{ $proposal->proposer->name }}<br>
                            <small class="text-muted">{{ $proposal->proposer->email }}</small>
                        </td>

                        <td>{{ optional($proposal->proposed_date)->format('d/m/Y') ?? '-' }}</td>

                        <td>
                            @php
                                $colors = [
                                    'pending' => 'secondary',
                                    'under_review' => 'info',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'pending_revision' => 'warning',
                                ];
                            @endphp
                            <span class="badge bg-{{ $colors[$proposal->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_',' ', $proposal->status)) }}
                            </span>
                        </td>

                        <td>{{ $proposal->reviewer->name ?? '-' }}</td>

                        <td>{{ $proposal->estimated_participants ?? '-' }}</td>

                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.proposals.show', $proposal) }}"
                                    class="btn btn-info"><i class="fas fa-eye"></i></a>

                                <a href="{{ route('admin.proposals.review', $proposal) }}"
                                    class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.proposals.destroy', $proposal) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus proposal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $proposals->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endif

@endsection
