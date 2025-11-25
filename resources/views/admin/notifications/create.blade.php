@extends('admin.layouts.app')

@section('title', 'Buat Notifikasi Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i> Buat Notifikasi Baru
    </h1>
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.notifications.store') }}">
            @csrf
            <div class="row">
                <!-- Informasi Notifikasi -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Notifikasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Notifikasi <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Pilih Tipe...</option>
                                    @foreach(['general','activity_reminder','invitation_reminder','announcement','system'] as $type)
                                        <option value="{{ $type }}" {{ old('type')==$type?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$type)) }}</option>
                                    @endforeach
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Notifikasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="activity_id" class="form-label">Terkait Kegiatan (Opsional)</label>
                                <select class="form-select @error('activity_id') is-invalid @enderror" name="activity_id">
                                    <option value="">Pilih Kegiatan</option>
                                    @foreach(\App\Models\Activity::published()->get() as $activity)
                                        <option value="{{ $activity->id }}" {{ old('activity_id')==$activity->id?'selected':'' }}>
                                            {{ $activity->title }} ({{ $activity->start_date->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('activity_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Penerima & Pengiriman -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Penerima</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="user_ids" class="form-label">Pilih Penerima <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('user_ids') is-invalid @enderror" 
                                        id="user_ids" name="user_ids[]" multiple required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, old('user_ids',[]))?'selected':'' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @error('user_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Pilih satu atau lebih penerima.</div>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Total Penerima:</strong> <span id="selectedUsersCount">0</span> orang
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Pengaturan Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="channel" class="form-label">Channel <span class="text-danger">*</span></label>
                                <select class="form-select @error('channel') is-invalid @enderror" id="channel" name="channel" required>
                                    @foreach(['web'=>'Web Notification','email'=>'Email','whatsapp'=>'WhatsApp'] as $key=>$label)
                                        <option value="{{ $key }}" {{ old('channel')==$key?'selected':'' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('channel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="scheduled_at" class="form-label">Jadwal Pengiriman</label>
                                <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                       id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                @error('scheduled_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Kosongkan untuk kirim sekarang.</div>
                            </div>
                            <div class="alert alert-warning" id="scheduleWarning" style="display: none;">
                                <i class="fas fa-clock me-2"></i> Notifikasi akan dikirim sesuai jadwal.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Pratinjau Notifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-bold" id="previewTitle">Judul Notifikasi</h6>
                                <p id="previewMessage" class="mb-2 text-muted">Pesan notifikasi akan muncul di sini...</p>
                                <div class="d-flex justify-content-between">
                                    <small id="previewType">General</small>
                                    <small id="previewChannel">Web</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6>Ringkasan Pengiriman:</h6>
                                <ul class="mb-0">
                                    <li><strong>Penerima:</strong> <span id="previewRecipients">0</span> orang</li>
                                    <li><strong>Channel:</strong> <span id="previewChannelFull">Web Notification</span></li>
                                    <li><strong>Waktu:</strong> <span id="previewTime">Sekarang</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Batal</a>
                <div class="btn-group">
                    <button type="submit" name="draft" value="1" class="btn btn-outline-secondary"><i class="fas fa-save me-2"></i>Simpan Draft</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Kirim Notifikasi</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ theme:'bootstrap-5', placeholder:'Pilih penerima', allowClear:true });

    function updateSelectedUsersCount() {
        const count = $('#user_ids').val() ? $('#user_ids').val().length : 0;
        $('#selectedUsersCount').text(count);
        $('#previewRecipients').text(count);
    }

    function updatePreview() {
        $('#previewTitle').text($('#title').val() || 'Judul Notifikasi');
        $('#previewMessage').text($('#message').val() || 'Pesan notifikasi akan muncul di sini...');
        $('#previewType').text($('#type').val() || 'General');
        const channel = $('#channel').val() || 'web';
        $('#previewChannel').text(channel);
        const channels = {web:'Web Notification', email:'Email', whatsapp:'WhatsApp'};
        $('#previewChannelFull').text(channels[channel] || 'Web Notification');

        const scheduled = $('#scheduled_at').val();
        $('#previewTime').text(scheduled ? new Date(scheduled).toLocaleString('id-ID') : 'Sekarang');
    }

    $('#scheduled_at').change(function(){
        $('#scheduleWarning').toggle(!!$(this).val());
        updatePreview();
    });

    $('#title,#message,#type,#channel').on('input change', updatePreview);
    $('#user_ids').on('change', function(){ updateSelectedUsersCount(); updatePreview(); });

    updateSelectedUsersCount();
    updatePreview();

    $('form').submit(function() {
        if (!$('#user_ids').val() || $('#user_ids').val().length===0){
            alert('Pilih minimal satu penerima notifikasi.');
            return false;
        }
        const schedule = $('#scheduled_at').val();
        if (schedule && new Date(schedule) < new Date()){
            alert('Waktu penjadwalan tidak boleh di masa lalu.');
            return false;
        }
        return confirm('Yakin ingin mengirim notifikasi ini?');
    });
});
</script>
@endpush
