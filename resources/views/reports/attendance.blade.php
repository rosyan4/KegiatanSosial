@extends('layouts.app')

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Kehadiran</h1>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reports.attendance') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.attendance') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Monthly Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Statistik Kehadiran Bulan {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</h5>
            </div>
            <div class="card-body">
                @if(count($monthlyAttendances) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Total Konfirmasi</th>
                                    <th>Hadir</th>
                                    <th>Tidak Hadir</th>
                                    <th>Mungkin</th>
                                    <th>Persentase Hadir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyAttendances as $monthKey => $data)
                                <tr>
                                    <td>{{ DateTime::createFromFormat('!Y-m', $monthKey)->format('F Y') }}</td>
                                    <td>{{ $data['total'] }}</td>
                                    <td class="text-success">{{ $data['hadir'] }}</td>
                                    <td class="text-danger">{{ $data['tidak_hadir'] }}</td>
                                    <td class="text-warning">{{ $data['mungkin'] }}</td>
                                    <td>
                                        @if($data['total'] > 0)
                                            {{ number_format(($data['hadir'] / $data['total']) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada data kehadiran untuk periode ini.</p>
                @endif
            </div>
        </div>

        <!-- Attended Activities -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Kegiatan yang Dihadiri</h5>
            </div>
            <div class="card-body">
                @if($attendedActivities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendedActivities as $activity)
                                <tr>
                                    <td>
                                        <strong>{{ $activity->title }}</strong>
                                    </td>
                                    <td>{{ $activity->start_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $activity->category->name }}</span>
                                    </td>
                                    <td>{{ $activity->location }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $attendedActivities->links() }}
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada kegiatan yang dihadiri.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Yearly Overview -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Overview Tahun {{ $year }}</h5>
            </div>
            <div class="card-body">
                @if(count($yearlyStats) > 0)
                    @foreach($yearlyStats as $monthKey => $data)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ DateTime::createFromFormat('!m', $monthKey)->format('F') }}</span>
                            <span>{{ $data['hadir'] }}/{{ $data['total'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $data['total'] > 0 ? ($data['hadir'] / $data['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Tidak ada data untuk tahun {{ $year }}.</p>
                @endif
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Laporan Lainnya</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.participation') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-pie me-1"></i>Statistik Partisipasi
                    </a>
                    <a href="{{ route('attendances.my') }}" class="btn btn-outline-info">
                        <i class="fas fa-history me-1"></i>Riwayat Kehadiran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection