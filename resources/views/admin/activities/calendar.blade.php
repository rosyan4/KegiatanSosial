@extends('admin.layouts.app')

@section('title', 'Kalender Kegiatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kalender Kegiatan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Kalender Kegiatan</h5>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary" id="prevMonth">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="currentMonth">
                        Bulan Ini
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Detail Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="100">Judul:</th>
                        <td id="eventTitle"></td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td id="eventDate"></td>
                    </tr>
                    <tr>
                        <th>Lokasi:</th>
                        <td id="eventLocation"></td>
                    </tr>
                    <tr>
                        <th>Kategori:</th>
                        <td id="eventCategory"></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td id="eventStatus"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" id="eventDetailLink">
                    <i class="fas fa-eye me-2"></i> Lihat Detail
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    #calendar {
        background: white;
        border-radius: 8px;
        padding: 20px;
    }
    .fc-header-toolbar {
        margin-bottom: 1.5em !important;
    }
    .fc-event {
        cursor: pointer;
        border: none;
        padding: 2px 4px;
        font-size: 0.85em;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const activities = @json($activities);
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: activities.map(activity => ({
                id: activity.id,
                title: activity.title,
                start: activity.start_date,
                end: activity.end_date,
                extendedProps: {
                    location: activity.location,
                    category: activity.category.name,
                    categoryColor: activity.category.color,
                    status: activity.status,
                    status_label: activity.status_label
                },
                backgroundColor: activity.category.color,
                borderColor: activity.category.color,
                textColor: 'white'
            })),
            eventClick: function(info) {
                const event = info.event;
                const extendedProps = event.extendedProps;
                
                // Set modal content
                document.getElementById('eventModalTitle').textContent = event.title;
                document.getElementById('eventTitle').textContent = event.title;
                document.getElementById('eventDate').textContent = 
                    `${event.start.toLocaleDateString('id-ID')} ${event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}`;
                document.getElementById('eventLocation').textContent = extendedProps.location;
                document.getElementById('eventCategory').textContent = extendedProps.category;
                document.getElementById('eventStatus').textContent = extendedProps.status_label;
                
                // Set detail link
                document.getElementById('eventDetailLink').href = `/admin/activities/${event.id}`;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            },
            eventDidMount: function(info) {
                // Add tooltip
                info.el.title = `${info.event.title} - ${info.event.extendedProps.location}`;
            }
        });

        calendar.render();

        // Navigation buttons
        document.getElementById('prevMonth').addEventListener('click', function() {
            calendar.prev();
        });

        document.getElementById('nextMonth').addEventListener('click', function() {
            calendar.next();
        });

        document.getElementById('currentMonth').addEventListener('click', function() {
            calendar.today();
        });
    });
</script>
@endpush