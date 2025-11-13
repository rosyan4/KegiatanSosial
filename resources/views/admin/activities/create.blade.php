@extends('admin.layouts.app')

@section('title', 'Buat Kegiatan Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buat Kegiatan Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

@include('admin.activities.form')
@endsection