<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'title', 'message', 'data', 'user_id', 'activity_id',
        'related_user_id', 'scheduled_at', 'sent_at', 'read_at',
        'status', 'channel', 'retry_count', 'failure_reason',
    ];

    protected $casts = [
        'data'          => 'array',
        'scheduled_at'  => 'datetime',
        'sent_at'       => 'datetime',
        'read_at'       => 'datetime',
        'retry_count'   => 'integer',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    /** Auto-set scheduled_at */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            if (empty($notification->scheduled_at)) {
                $notification->scheduled_at = now();
            }
        });
    }

    /** === Scopes === */
    public function scopePending($q) { return $q->where('status', 'pending')->where('scheduled_at', '<=', now()); }
    public function scopeSent($q) { return $q->where('status', 'sent'); }
    public function scopeRead($q) { return $q->whereNotNull('read_at'); }
    public function scopeUnread($q) { return $q->whereNull('read_at'); }
    public function scopeFailed($q) { return $q->where('status', 'failed'); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
    public function scopeOfType($q, $type) { return $q->where('type', $type); }
    public function scopeByChannel($q, $channel) { return $q->where('channel', $channel); }
    public function scopeReadyToSend($q) {
        return $q->pending()->where(function ($x) {
            $x->whereNull('sent_at')->orWhere('status', 'failed');
        });
    }
    public function scopeScheduled($q) { return $q->where('scheduled_at', '>', now())->where('status', 'pending'); }
    public function scopeForActivity($q, $id) { return $q->where('activity_id', $id); }

    /** === Status Helpers === */
    public function isPending() { return $this->status === 'pending'; }
    public function isSent() { return $this->status === 'sent'; }
    public function isRead() { return $this->read_at !== null; }
    public function isUnread() { return $this->read_at === null; }
    public function isFailed() { return $this->status === 'failed'; }

    public function canBeRetried() { return $this->isFailed() && $this->retry_count < 3; }

    /** === Mutators === */
    public function markAsSent() { return $this->update(['status' => 'sent', 'sent_at' => now(), 'failure_reason' => null]); }
    public function markAsRead() { return $this->update(['read_at' => now()]); }
    public function markAsUnread() { return $this->update(['read_at' => null]); }
    public function markAsFailed($reason = null) {
        return $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'retry_count' => $this->retry_count + 1
        ]);
    }

    public function retry() {
        if (!$this->isFailed()) return false;
        return $this->update([
            'status' => 'pending',
            'scheduled_at' => now()->addMinutes(5),
            'failure_reason' => null
        ]);
    }

    /** === Factory Notification === */
    public static function createActivityReminder($userId, $activityId, $minutesBefore = 60): self
    {
        $activity = Activity::findOrFail($activityId);
        return self::create([
            'type' => 'activity_reminder',
            'title' => "Pengingat Kegiatan: {$activity->title}",
            'message' => "Kegiatan {$activity->title} akan dimulai dalam {$minutesBefore} menit.",
            'user_id' => $userId,
            'activity_id' => $activityId,
            'channel' => 'web',
            'scheduled_at' => $activity->start_date->subMinutes($minutesBefore),
            'data' => [
                'minutes_before' => $minutesBefore,
                'activity_start' => $activity->start_date,
            ],
        ]);
    }

    public static function createNewActivityNotification($userId, $activityId): self
    {
        $activity = Activity::findOrFail($activityId);
        return self::create([
            'type' => 'new_activity',
            'title' => "Kegiatan Baru: {$activity->title}",
            'message' => "Kegiatan baru '{$activity->title}' telah dibuat.",
            'user_id' => $userId,
            'activity_id' => $activityId,
            'channel' => 'web',
            'data' => [
                'activity_description' => $activity->description
            ],
        ]);
    }

    public static function createInvitationNotification($userId, $activityId, $invitationId): self
    {
        $activity = Activity::findOrFail($activityId);
        return self::create([
            'type' => 'invitation',
            'title' => "Undangan Kegiatan: {$activity->title}",
            'message' => "Anda diundang mengikuti kegiatan '{$activity->title}'.",
            'user_id' => $userId,
            'activity_id' => $activityId,
            'channel' => 'web',
            'data' => ['invitation_id' => $invitationId],
        ]);
    }

    /** === Relationships === */
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function activity() { return $this->belongsTo(Activity::class, 'activity_id'); }
    public function relatedUser() { return $this->belongsTo(User::class, 'related_user_id'); }
}
