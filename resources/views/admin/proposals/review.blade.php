@extends('admin.layouts.app')

@section('title', 'Review Proposal: ' . $proposal->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Review Proposal</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.proposals.show', $proposal) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-2"></i> Lihat Detail
        </a>
        <a href="{{ route('admin.proposals.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Proposal Details -->
    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Proposal</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="120">Judul:</th>
                        <td>{{ $proposal->title }}</td>
                    </tr>
                    <tr>
                        <th>Pengusul:</th>
                        <td>{{ $proposal->proposer->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td>{{ $proposal->proposed_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Estimasi Peserta:</th>
                        <td>
                            @if($proposal->estimated_participants)
                                {{ $proposal->estimated_participants }} orang
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Lokasi Usulan:</th>
                        <td>{{ $proposal->proposed_location ?? '-' }}</td>
                    </tr>
                </table>

                <h6 class="mt-4">Deskripsi:</h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($proposal->description)) !!}
                </div>

                @if($proposal->objectives)
                <h6 class="mt-3">Tujuan:</h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($proposal->objectives)) !!}
                </div>
                @endif

                @if($proposal->expected_outcomes)
                <h6 class="mt-3">Hasil yang Diharapkan:</h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($proposal->expected_outcomes)) !!}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <div class="col-lg-7">
        <!-- Approval Form -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Setujui Proposal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.proposals.approve', $proposal) }}" method="POST" id="approveForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Catatan Admin (Opsional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Berikan catatan atau saran untuk pengusul...">{{ old('admin_notes') }}</textarea>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="convert_to_activity" name="convert_to_activity" value="1" checked>
                        <label class="form-check-label" for="convert_to_activity">
                            <strong>Konversi langsung menjadi kegiatan</strong>
                        </label>
                        <div class="form-text">Jika dicentang, proposal akan otomatis dikonversi menjadi kegiatan yang dapat diikuti warga.</div>
                    </div>

                    <!-- Activity Conversion Fields -->
                    <div id="activityFields">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Kategori Kegiatan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="{{ old('location', $proposal->proposed_location) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Proposal yang disetujui akan diberi status <strong>Approved</strong> dan dapat dikonversi menjadi kegiatan.
                    </div>

                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin menyetujui proposal ini?')">
                        <i class="fas fa-check me-2"></i> Setujui Proposal
                    </button>
                </form>
            </div>
        </div>

        <!-- Rejection Form -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-times-circle me-2"></i>Tolak Proposal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.proposals.reject', $proposal) }}" method="POST" id="rejectForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                  placeholder="Jelaskan alasan penolakan proposal ini..." required>{{ old('rejection_reason') }}</textarea>
                        <div class="form-text">Alasan penolakan akan dikirimkan kepada pengusul.</div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Proposal yang ditolak <strong>tidak dapat dikembalikan</strong>. Pastikan alasan penolakan sudah jelas.
                    </div>

                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menolak proposal ini?')">
                        <i class="fas fa-times me-2"></i> Tolak Proposal
                    </button>
                </form>
            </div>
        </div>

        <!-- Request Revision Form -->
        @if($proposal->isUnderReview() || $proposal->isPending())
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Minta Revisi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.proposals.request-revision', $proposal->id) }}" method="POST" id="revisionForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Jelaskan bagian mana yang perlu direvisi..." required>{{ old('admin_notes') }}</textarea>
                        <div class="form-text">Pengusul akan diminta untuk merevisi proposal berdasarkan catatan ini.</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Proposal akan berstatus <strong>Pending Revision</strong> dan pengusul dapat mengajukan revisi.
                    </div>

                    <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Yakin ingin meminta revisi proposal ini?')">
                        <i class="fas fa-redo me-2"></i> Minta Revisi
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle activity conversion fields
        $('#convert_to_activity').change(function() {
            if (this.checked) {
                $('#activityFields').slideDown();
                // Make activity fields required
                $('#activityFields select, #activityFields input').prop('required', true);
            } else {
                $('#activityFields').slideUp();
                // Remove required from activity fields
                $('#activityFields select, #activityFields input').prop('required', false);
            }
        });

        // Initialize activity fields state
        $('#convert_to_activity').trigger('change');

        // Date validation
        $('#start_date, #end_date').change(function() {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($('#end_date').val());
            
            if (endDate <= startDate) {
                alert('Tanggal selesai harus setelah tanggal mulai');
                $('#end_date').val('');
            }
        });

        // Set default dates (tomorrow and day after tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(10, 0, 0, 0);

        const dayAfterTomorrow = new Date(tomorrow);
        dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 1);
        dayAfterTomorrow.setHours(12, 0, 0, 0);

        // Format for datetime-local input
        function formatDateForInput(date) {
            return date.toISOString().slice(0, 16);
        }

        // Set default values if not already set
        if (!$('#start_date').val()) {
            $('#start_date').val(formatDateForInput(tomorrow));
        }
        if (!$('#end_date').val()) {
            $('#end_date').val(formatDateForInput(dayAfterTomorrow));
        }

        // Form validation
        $('form').submit(function() {
            const formId = $(this).attr('id');
            
            if (formId === 'rejectForm' || formId === 'revisionForm') {
                const textarea = $(this).find('textarea');
                if (textarea.val().trim().length < 10) {
                    alert('Harap berikan penjelasan yang lebih detail (minimal 10 karakter).');
                    textarea.focus();
                    return false;
                }
            }
            
            return true;
        });
    });
</script>
@endpush