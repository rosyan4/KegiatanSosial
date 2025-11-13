<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLog extends Model
{
    use HasFactory;

    /** Mass assignable attributes */
    protected $fillable = [
        'activity_id',
        'user_id',
        'recorded_by',
        'status',
        'check_in_time',
        'check_out_time',
        'duration_minutes',
        'notes',
        'check_in_method',
        'check_out_method',
        'check_in_data',
        'check_out_data',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    /** Attribute casting */
    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'verified_at' => 'datetime',
        'duration_minutes' => 'integer',
        'is_verified' => 'boolean',
        'check_in_data' => 'array',
        'check_out_data' => 'array',
    ];

    /** Attendance Status */
    const STATUS_HADIR = 'hadir';
    const STATUS_TERLAMBAT = 'terlambat';
    const STATUS_TIDAK_HADIR = 'tidak_hadir';

    /** Check-in/out Methods */
    const METHOD_MANUAL = 'manual';
    const METHOD_QRCODE = 'qrcode';
    const METHOD_NFC = 'nfc';
    const METHOD_MOBILE = 'mobile';
    const METHOD_WEBSITE = 'website';

    /**
     * Auto calculate duration + status + recorded_by
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($log) {
            if ($log->check_in_time && $log->check_out_time) {
                $log->duration_minutes = $log->check_in_time->diffInMinutes($log->check_out_time);
            }

            if ($log->check_in_time && $log->activity) {
                $activityStart = clone $log->activity->start_date;
                $criticalTime = $activityStart->addMinutes(15);

                $log->status = $log->check_in_time->gt($criticalTime)
                    ? self::STATUS_TERLAMBAT
                    : self::STATUS_HADIR;
            }

            if (empty($log->recorded_by) && Auth::check()) {
                $log->recorded_by = Auth::id();
            }
        });
    }

    public function scopeHadir($q)        { return $q->where('status', self::STATUS_HADIR); }
    public function scopeTerlambat($q)    { return $q->where('status', self::STATUS_TERLAMBAT); }
    public function scopeTidakHadir($q)   { return $q->where('status', self::STATUS_TIDAK_HADIR); }

    public function scopeCheckedIn($q)    { return $q->whereNotNull('check_in_time'); }
    public function scopeCheckedOut($q)   { return $q->whereNotNull('check_out_time'); }
    public function scopeNotCheckedOut($q){ return $q->checkedIn()->whereNull('check_out_time'); }

    public function scopeVerified($q)     { return $q->where('is_verified', true); }
    public function scopeUnverified($q)   { return $q->where('is_verified', false); }

    public function scopeForActivity($q, $id) { return $q->where('activity_id', $id); }
    public function scopeForUser($q, $id)     { return $q->where('user_id', $id); }
    public function scopeOnDate($q, $date)    { return $q->whereDate('check_in_time', $date); }

    public function scopeWithDuration($q, $min = null, $max = null)
    {
        if ($min) $q->where('duration_minutes', '>=', $min);
        if ($max) $q->where('duration_minutes', '<=', $max);
        return $q;
    }

    public function isHadir(): bool        { return $this->status === self::STATUS_HADIR; }
    public function isTerlambat(): bool    { return $this->status === self::STATUS_TERLAMBAT; }
    public function isTidakHadir(): bool   { return $this->status === self::STATUS_TIDAK_HADIR; }

    public function isCheckedIn(): bool    { return !is_null($this->check_in_time); }
    public function isCheckedOut(): bool   { return !is_null($this->check_out_time); }
    public function isActive(): bool       { return $this->isCheckedIn() && !$this->isCheckedOut(); }

    public function isVerified(): bool     { return $this->is_verified; }

    public function calculateLateStatus(): string
    {
        if (!$this->check_in_time || !$this->activity) {
            return self::STATUS_TIDAK_HADIR;
        }

        $deadline = clone $this->activity->start_date;
        $deadline->addMinutes(15);

        return $this->check_in_time->gt($deadline)
            ? self::STATUS_TERLAMBAT
            : self::STATUS_HADIR;
    }

    public function getLateMinutes(): int
    {
        if (!$this->isTerlambat() || !$this->activity) return 0;
        $deadline = clone $this->activity->start_date;
        return max(0, $this->check_in_time->diffInMinutes($deadline->addMinutes(15)));
    }

    /** Check-in */
    public function checkIn($method = self::METHOD_MANUAL, $data = null): bool
    {
        return $this->update([
            'check_in_time' => now(),
            'check_in_method' => $method,
            'check_in_data' => $data,
            'status' => $this->calculateLateStatus(),
        ]);
    }

    /** Check-out */
    public function checkOut($method = self::METHOD_MANUAL, $data = null): bool
    {
        if (!$this->isCheckedIn()) return false;

        return $this->update([
            'check_out_time' => now(),
            'check_out_method' => $method,
            'check_out_data' => $data,
            'duration_minutes' => $this->check_in_time->diffInMinutes(now()),
        ]);
    }

    /* Manual Verification helpers */
    private function updateVerification($status, $notes = null): bool
    {
        return $this->update([
            'status' => $status,
            'check_in_time' => $this->check_in_time ?: now(),
            'notes' => $notes,
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
    }

    public function markHadir($notes = null): bool       { return $this->updateVerification(self::STATUS_HADIR, $notes); }
    public function markTerlambat($notes = null): bool   { return $this->updateVerification(self::STATUS_TERLAMBAT, $notes); }

    public function markTidakHadir($notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_TIDAK_HADIR,
            'check_in_time' => null,
            'check_out_time' => null,
            'duration_minutes' => null,
            'notes' => $notes,
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
    }

    public function verify($adminId): bool
    {
        return $this->update([
            'is_verified' => true,
            'verified_by' => $adminId,
            'verified_at' => now(),
        ]);
    }

    public function unverify(): bool
    {
        return $this->update([
            'is_verified' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_HADIR => 'success',
            self::STATUS_TERLAMBAT => 'warning',
            self::STATUS_TIDAK_HADIR => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_TERLAMBAT => 'Terlambat',
            self::STATUS_TIDAK_HADIR => 'Tidak Hadir',
            default => 'Belum Dicatat'
        };
    }

    public function getMethodLabel($method): string
    {
        return match($method) {
            self::METHOD_MANUAL => 'Manual',
            self::METHOD_QRCODE => 'QR Code',
            self::METHOD_NFC => 'NFC',
            self::METHOD_MOBILE => 'Mobile App',
            self::METHOD_WEBSITE => 'Website',
            default => ucfirst($method),
        };
    }

    /* Format Helpers */
    public function getDurationFormatted(): string
    {
        if (!$this->duration_minutes) return '-';

        $hours = floor($this->duration_minutes / 60);
        $mins = $this->duration_minutes % 60;

        return $hours > 0 ? "{$hours} jam {$mins} menit" : "{$mins} menit";
    }

    public function getCheckInTimeFormatted(): string
    {
        return $this->check_in_time?->format('H:i') ?? '-';
    }

    public function getCheckOutTimeFormatted(): string
    {
        return $this->check_out_time?->format('H:i') ?? '-';
    }

    public function canCheckIn(): bool
    {
        if (!$this->activity || $this->isCheckedIn()) return false;

        $start = (clone $this->activity->start_date)->subHour();
        $end = (clone $this->activity->end_date)->addHour();

        return now()->between($start, $end);
    }

    public function canCheckOut(): bool
    {
        return $this->isCheckedIn() && !$this->isCheckedOut();
    }

    /* Data helpers */
    public function getCheckInLocation(): ?string
    {
        return data_get($this->check_in_data, 'location');
    }

    public function getCheckInDevice(): ?string
    {
        return data_get($this->check_in_data, 'device');
    }

    public static function createForUser($activityId, $userId, $recordedBy = null): self
    {
        return static::create([
            'activity_id' => $activityId,
            'user_id' => $userId,
            'recorded_by' => $recordedBy ?? Auth::id(),
            'status' => self::STATUS_TIDAK_HADIR,
        ]);
    }

    public static function getAttendanceStats($activityId): array
    {
        $query = static::query()->forActivity($activityId);

        return [
            'total' => (clone $query)->count(),
            'hadir' => (clone $query)->where('status', self::STATUS_HADIR)->count(),
            'terlambat' => (clone $query)->where('status', self::STATUS_TERLAMBAT)->count(),
            'tidak_hadir' => (clone $query)->where('status', self::STATUS_TIDAK_HADIR)->count(),
            'checked_in' => (clone $query)->checkedIn()->count(),
            'checked_out' => (clone $query)->checkedOut()->count(),
            'active' => (clone $query)->checkedIn()->whereNull('check_out_time')->count(),
            'verified' => (clone $query)->verified()->count(),
            'average_duration' => (clone $query)->whereNotNull('duration_minutes')->avg('duration_minutes'),
        ];
    }

    public function activity() { return $this->belongsTo(Activity::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
}
