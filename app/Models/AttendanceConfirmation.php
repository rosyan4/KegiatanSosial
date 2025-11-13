<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceConfirmation extends Model
{
    use HasFactory;

    const STATUS_HADIR = 'hadir';
    const STATUS_TIDAK_HADIR = 'tidak_hadir';
    const STATUS_MUNGKIN = 'mungkin';

    protected $fillable = [
        'activity_id',
        'user_id',
        'status',
        'notes',
        'number_of_guests',
        'confirmed_at',
        'reminded_at',
        'reminder_count',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'reminded_at' => 'datetime',
        'number_of_guests' => 'integer',
        'reminder_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($confirmation) {
            if (empty($confirmation->confirmed_at) && !empty($confirmation->status)) {
                $confirmation->confirmed_at = now();
            }
        });

        static::updating(function ($confirmation) {
            if ($confirmation->isDirty('status') && empty($confirmation->confirmed_at)) {
                $confirmation->confirmed_at = now();
            }
        });
    }

    public function scopeHadir($q)
    {
        return $q->where('status', self::STATUS_HADIR);
    }

    public function scopeTidakHadir($q)
    {
        return $q->where('status', self::STATUS_TIDAK_HADIR);
    }

    public function scopeMungkin($q)
    {
        return $q->where('status', self::STATUS_MUNGKIN);
    }

    public function scopeConfirmed($q)
    {
        return $q->whereNotNull('confirmed_at');
    }

    public function scopeUnconfirmed($q)
    {
        return $q->whereNull('confirmed_at');
    }

    public function scopeForActivity($q, $id)
    {
        return $q->where('activity_id', $id);
    }

    public function scopeForUser($q, $id)
    {
        return $q->where('user_id', $id);
    }

    public function scopeWithGuests($q)
    {
        return $q->where('number_of_guests', '>', 0);
    }

    public function scopeNeedsReminder($q)
    {
        return $q->where(function ($query) {
            $query->whereNull('confirmed_at')
                  ->orWhere('status', self::STATUS_MUNGKIN);
        })->whereHas('activity', function ($q) {
            $q->where('start_date', '>', now())
              ->where('start_date', '<=', now()->addDays(2))
              ->where('status', 'published');
        });
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isHadir(): bool
    {
        return $this->status === self::STATUS_HADIR;
    }

    public function isTidakHadir(): bool
    {
        return $this->status === self::STATUS_TIDAK_HADIR;
    }

    public function isMungkin(): bool
    {
        return $this->status === self::STATUS_MUNGKIN;
    }

    public function isConfirmed(): bool
    {
        return !is_null($this->confirmed_at);
    }

    public function isUnconfirmed(): bool
    {
        return is_null($this->confirmed_at);
    }

    public function isOverReminded(): bool
    {
        return $this->reminder_count >= 3;
    }

    public function hasGuests(): bool
    {
        return $this->number_of_guests > 0;
    }

    public function needsReminder(): bool
    {
        $activity = $this->activity;

        return !$this->isTidakHadir()
            && $activity->isPublished()
            && $activity->isUpcoming()
            && $activity->start_date->diffInDays(now()) <= 2
            && ($this->isUnconfirmed() || $this->isMungkin());
    }

    public function confirmHadir($notes = null, $guests = 0): bool
    {
        return $this->updateStatus(self::STATUS_HADIR, $notes, $guests);
    }

    public function confirmTidakHadir($notes = null): bool
    {
        return $this->updateStatus(self::STATUS_TIDAK_HADIR, $notes, 0);
    }

    public function confirmMungkin($notes = null, $guests = 0): bool
    {
        return $this->updateStatus(self::STATUS_MUNGKIN, $notes, $guests);
    }

    public function resetConfirmation(): bool
    {
        return $this->update([
            'status' => self::STATUS_HADIR,
            'notes' => null,
            'number_of_guests' => 0,
            'confirmed_at' => null,
        ]);
    }

    public function markAsReminded(): bool
    {
        return $this->update([
            'reminded_at' => now(),
            'reminder_count' => $this->reminder_count + 1,
        ]);
    }

    protected function updateStatus($status, $notes, $guests): bool
    {
        return $this->update([
            'status' => $status,
            'notes' => $notes,
            'number_of_guests' => $guests,
            'confirmed_at' => now(),
        ]);
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            self::STATUS_HADIR => 'success',
            self::STATUS_TIDAK_HADIR => 'danger',
            self::STATUS_MUNGKIN => 'warning',
            default => 'secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_TIDAK_HADIR => 'Tidak Hadir',
            self::STATUS_MUNGKIN => 'Mungkin Hadir',
            default => 'Belum Dikonfirmasi',
        };
    }

    public function getStatusIcon(): string
    {
        return match ($this->status) {
            self::STATUS_HADIR => 'check-circle',
            self::STATUS_TIDAK_HADIR => 'x-circle',
            self::STATUS_MUNGKIN => 'help-circle',
            default => 'clock',
        };
    }

    public function getTotalAttendees(): int
    {
        return $this->isHadir() ? 1 + $this->number_of_guests : 0;
    }

    public function getTimeSinceConfirmed(): string
    {
        return $this->confirmed_at
            ? $this->confirmed_at->diffForHumans()
            : 'Belum dikonfirmasi';
    }

    public function getTimeSinceReminded(): string
    {
        return $this->reminded_at
            ? $this->reminded_at->diffForHumans()
            : 'Belum diingatkan';
    }

    public static function confirmAttendance($activityId, $userId, $status, $notes = null, $guests = 0): self
    {
        return static::updateOrCreate(
            ['activity_id' => $activityId, 'user_id' => $userId],
            [
                'status' => $status,
                'notes' => $notes,
                'number_of_guests' => $guests,
                'confirmed_at' => now(),
            ]
        );
    }

    public static function hasUserConfirmed($activityId, $userId): bool
    {
        return static::forActivity($activityId)
            ->forUser($userId)
            ->confirmed()
            ->exists();
    }

    public static function getUserConfirmation($activityId, $userId): ?self
    {
        return static::forActivity($activityId)
            ->forUser($userId)
            ->first();
    }

    public static function getAttendanceStats($activityId): array
    {
        $confirmations = static::forActivity($activityId)->get();

        return [
            'total' => $confirmations->count(),
            'hadir' => $confirmations->where('status', self::STATUS_HADIR)->count(),
            'tidak_hadir' => $confirmations->where('status', self::STATUS_TIDAK_HADIR)->count(),
            'mungkin' => $confirmations->where('status', self::STATUS_MUNGKIN)->count(),
            'unconfirmed' => $confirmations->whereNull('confirmed_at')->count(),
            'total_attendees' => $confirmations->where('status', self::STATUS_HADIR)
                                               ->sum(fn($c) => 1 + $c->number_of_guests),
        ];
    }

    public static function getUserAttendanceHistory($userId, $limit = 10): array
    {
        return static::forUser($userId)
            ->with('activity')
            ->whereHas('activity', fn($q) => $q->where('end_date', '<', now()))
            ->orderBy('confirmed_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($c) => [
                'activity' => $c->activity->title,
                'date' => $c->activity->start_date->format('d M Y'),
                'status' => $c->status,
                'status_label' => $c->getStatusLabel(),
                'status_color' => $c->getStatusColor(),
                'confirmed_at' => $c->confirmed_at?->format('d M Y H:i'),
            ])
            ->toArray();
    }
}
