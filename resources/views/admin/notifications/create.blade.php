@extends('admin.layouts.app')

@section('title', 'Buat Notifikasi Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>Buat Notifikasi Baru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.notifications.store') }}">
            @csrf
            
            <div class="row">
                <!-- Basic Information -->
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
                                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="activity_reminder" {{ old('type') == 'activity_reminder' ? 'selected' : '' }}>Pengingat Kegiatan</option>
                                    <option value="invitation_reminder" {{ old('type') == 'invitation_reminder' ? 'selected' : '' }}>Pengingat Undangan</option>
                                    <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Pengumuman</option>
                                    <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Notifikasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Contoh: Pengingat Kegiatan Bakti Sosial" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="5" 
                                          placeholder="Tulis pesan notifikasi di sini..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Pesan akan ditampilkan kepada penerima notifikasi.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="activity_id" class="form-label">Terkait Kegiatan (Opsional)</label>
                                <select class="form-select @error('activity_id') is-invalid @enderror" 
                                        id="activity_id" name="activity_id">
                                    <option value="">Pilih Kegiatan (Opsional)</option>
                                    @foreach(\App\Models\Activity::published()->get() as $activity)
                                        <option value="{{ $activity->id }}" 
                                            {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                            {{ $activity->title }} ({{ $activity->start_date->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('activity_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recipients & Settings -->
                <div class="col-md-4">
                    <!-- Recipients -->
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
                                        <option value="{{ $user->id }}" 
                                            {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('user_ids.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Pilih satu atau lebih penerima notifikasi.
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Total Penerima:</strong> 
                                <span id="selectedUsersCount">0</span> orang dipilih
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Pengaturan Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="channel" class="form-label">Channel <span class="text-danger">*</span></label>
                                <select class="form-select @error('channel') is-invalid @enderror" 
                                        id="channel" name="channel" required>
                                    <option value="web" {{ old('channel') == 'web' ? 'selected' : '' }}>Web Notification</option>
                                    <option value="email" {{ old('channel') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="whatsapp" {{ old('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                </select>
                                @error('channel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="scheduled_at" class="form-label">Jadwal Pengiriman</label>
                                <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                       id="scheduled_at" name="scheduled_at" 
                                       value="{{ old('scheduled_at') }}">
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Kosongkan untuk mengirim sekarang. Format: YYYY-MM-DD HH:MM
                                </div>
                            </div>

                            <div class="alert alert-warning" id="scheduleWarning" style="display: none;">
                                <i class="fas fa-clock me-2"></i>
                                Notifikasi akan dikirim sesuai jadwal yang ditentukan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Pratinjau Notifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-bold" id="previewTitle">Judul Notifikasi</h6>
                                <p class="mb-2 text-muted" id="previewMessage">Pesan notifikasi akan muncul di sini...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" id="previewType">General</small>
                                    <small class="text-muted" id="previewChannel">Web</small>
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

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
                <div class="btn-group">
                    <button type="submit" name="draft" value="1" class="btn btn-outline-secondary">
                        <i class="fas fa-save me-2"></i> Simpan Draft
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Notifikasi
                    </button>
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
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih penerima...',
            allowClear: true
        });

        // Update selected users count
        function updateSelectedUsersCount() {
            const selectedCount = $('#user_ids').val() ? $('#user_ids').val().length : 0;
            $('#selectedUsersCount').text(selectedCount);
            $('#previewRecipients').text(selectedCount);
        }

        // Update preview in real-time
        function updatePreview() {
            $('#previewTitle').text($('#title').val() || 'Judul Notifikasi');
            $('#previewMessage').text($('#message').val() || 'Pesan notifikasi akan muncul di sini...');
            $('#previewType').text($('#type').val() || 'General');
            $('#previewChannel').text($('#channel').val() || 'Web');
            
            const channelText = {
                'web': 'Web Notification',
                'email': 'Email',
                'whatsapp': 'WhatsApp'
            };
            $('#previewChannelFull').text(channelText[$('#channel').val()] || 'Web Notification');
            
            const scheduledAt = $('#scheduled_at').val();
            if (scheduledAt) {
                const date = new Date(scheduledAt);
                $('#previewTime').text(date.toLocaleString('id-ID'));
            } else {
                $('#previewTime').text('Sekarang');
            }
        }

        // Show/hide schedule warning
        $('#scheduled_at').change(function() {
            if ($(this).val()) {
                $('#scheduleWarning').show();
            } else {
                $('#scheduleWarning').hide();
            }
            updatePreview();
        });

        // Event listeners for real-time preview
        $('#title, #message, #type, #channel').on('input change', updatePreview);
        $('#user_ids').on('change', function() {
            updateSelectedUsersCount();
            updatePreview();
        });

        // Set default scheduled_at to current time if not set
        if (!$('#scheduled_at').val()) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#scheduled_at').val(now.toISOString().slice(0, 16));
        }

        // Initialize counts and preview
        updateSelectedUsersCount();
        updatePreview();

        // Form validation
        $('form').submit(function() {
            const selectedUsers = $('#user_ids').val();
            if (!selectedUsers || selectedUsers.length === 0) {
                alert('Pilih minimal satu penerima notifikasi.');
                return false;
            }
            
            const scheduledAt = $('#scheduled_at').val();
            if (scheduledAt) {
                const scheduleTime = new Date(scheduledAt);
                const now = new Date();
                if (scheduleTime < now) {
                    alert('Waktu penjadwalan tidak boleh di masa lalu.');
                    return false;
                }
            }
            
            return confirm('Yakin ingin mengirim notifikasi ini?');
        });
    });
</script>
@endpush