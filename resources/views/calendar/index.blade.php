@extends('layouts.app')

@section('title', 'Kalender Kegiatan')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kalender Kegiatan</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Kalender Kegiatan Warga</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select id="categoryFilter" class="form-select form-select-sm w-auto">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var categoryFilter = document.getElementById('categoryFilter');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'id',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {
            url: '{{ route("calendar.events") }}',
            extraParams: function() {
                return {
                    category_id: categoryFilter.value
                };
            }
        },
        eventClick: function(info) {
            if (info.event.extendedProps.slug) {
                window.location.href = '/activities/' + info.event.extendedProps.slug;
            }
        },
        eventDisplay: 'block',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });

    calendar.render();

    // Filter change handler
    categoryFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>
@endpush