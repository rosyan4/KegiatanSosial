@extends('layouts.app')

@section('title', 'Statistik Partisipasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Statistik Partisipasi</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Participation Overview -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan Partisipasi</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h3>{{ $participationStats['total_activities'] }}</h3>
                                <p class="mb-0">Total Kegiatan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h3>{{ $participationStats['umum_count'] }}</h3>
                                <p class="mb-0">Kegiatan Umum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h3>{{ $participationStats['khusus_count'] }}</h3>
                                <p class="mb-0">Kegiatan Khusus</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h3>{{ $participationStats['attended_count'] }}</h3>
                                <p class="mb-0">Dihadiri</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6>Rasio Kehadiran</h6>
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $participationStats['total_activities'] > 0 ? ($participationStats['attended_count'] / $participationStats['total_activities']) * 100 : 0 }}%">
                                {{ $participationStats['total_activities'] > 0 ? number_format(($participationStats['attended_count'] / $participationStats['total_activities']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $participationStats['attended_count'] }} dari {{ $participationStats['total_activities'] }} kegiatan
                        </small>
                    </div>
                    <div class="col-md-6">
                        <h6>Rasio Partisipasi</h6>
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ $participationStats['total_activities'] > 0 ? ($participationStats['participated_count'] / $participationStats['total_activities']) * 100 : 0 }}%">
                                {{ $participationStats['total_activities'] > 0 ? number_format(($participationStats['participated_count'] / $participationStats['total_activities']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $participationStats['participated_count'] }} dari {{ $participationStats['total_activities'] }} kegiatan
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Top Categories -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Kategori Favorit</h5>
            </div>
            <div class="card-body">
                @if($topCategories->count() > 0)
                    @foreach($topCategories as $category)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>
                                <span class="badge" style="background-color: {{ $category->color }}">{{ $category->name }}</span>
                            </span>
                            <span>{{ $category->participation_count }} kali</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" style="background-color: {{ $category->color }}; width: {{ ($category->participation_count / $topCategories->max('participation_count')) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada data partisipasi.</p>
                @endif
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Laporan Lainnya</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.attendance') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar me-1"></i>Laporan Kehadiran
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