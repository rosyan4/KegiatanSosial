<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'activity_id',
        'user_id',
        'status',
        'sent_at',
        'responded_at',
        'custom_message',
        'decline_reason',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Boot untuk set sent_at otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->sent_at)) {
                $invitation->sent_at = now();
            }
        });
    }

    /* ===========================
       Query Scopes
    =========================== */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    public function scopeUnresponded($query)
    {
        return $query->where('status', 'pending')
                     ->whereNotNull('sent_at');
    }

    public function scopeResponded($query)
    {
        return $query->whereIn('status', ['accepted', 'declined']);
    }

    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeValid($query)
    {
        return $query->whereHas('activity', function ($q) {
            $q->where('end_date', '>', now())
              ->where('status', 'published');
        });
    }

    /* ===========================
       Status Checkers
    =========================== */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    public function isSent(): bool
    {
        return !is_null($this->sent_at);
    }

    public function isResponded(): bool
    {
        return !is_null($this->responded_at);
    }

    public function canBeResponded(): bool
    {
        return $this->isPending()
            && $this->activity->start_date > now()
            && $this->activity->isPublished();
    }

    public function isExpired(): bool
    {
        return $this->activity->start_date <= now()
            || $this->activity->isCancelled()
            || $this->activity->isCompleted();
    }

    /* ===========================
       Status Actions
    =========================== */

    public function accept(): bool
    {
        if (!$this->canBeResponded()) {
            return false;
        }

        return $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
            'decline_reason' => null,
        ]);
    }

    public function decline($reason = null): bool
    {
        if (!$this->canBeResponded()) {
            return false;
        }

        return $this->update([
            'status' => 'declined',
            'responded_at' => now(),
            'decline_reason' => $reason,
        ]);
    }

    public function resetResponse(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        return $this->update([
            'status' => 'pending',
            'responded_at' => null,
            'decline_reason' => null,
        ]);
    }

    /* ===========================
       View Helpers
    =========================== */

    public function getTimeSinceSent(): string
    {
        return $this->sent_at
            ? $this->sent_at->diffForHumans()
            : 'Belum dikirim';
    }

    public function getTimeSinceResponded(): string
    {
        return $this->responded_at
            ? $this->responded_at->diffForHumans()
            : 'Belum direspon';
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'accepted' => 'success',
            'declined' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'accepted' => 'Diterima',
            'declined' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getDaysUntilActivity(): int
    {
        return now()->diffInDays($this->activity->start_date, false);
    }

    public function needsReminder(): bool
    {
        return $this->isPending()
            && $this->getDaysUntilActivity() <= 2
            && $this->getDaysUntilActivity() > 0;
    }

    /* ===========================
       Relationships
    =========================== */

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendanceConfirmation()
    {
        return $this->hasOne(AttendanceConfirmation::class, 'activity_id', 'activity_id')
                    ->where('user_id', $this->user_id);
    }

    /* ===========================
       Attendance Confirmation
    =========================== */

    public function createAttendanceConfirmation(): ?AttendanceConfirmation
    {
        if (!$this->isAccepted()) {
            return null;
        }

        return AttendanceConfirmation::create([
            'activity_id' => $this->activity_id,
            'user_id' => $this->user_id,
            'status' => 'hadir',
            'confirmed_at' => now(),
        ]);
    }
}
