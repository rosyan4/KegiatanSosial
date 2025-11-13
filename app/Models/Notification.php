<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /** Notification Types */
    const TYPE_ACTIVITY_REMINDER      = 'activity_reminder';
    const TYPE_NEW_ACTIVITY           = 'new_activity';
    const TYPE_ACTIVITY_UPDATE        = 'activity_update';
    const TYPE_ACTIVITY_CANCELLED     = 'activity_cancelled';
    const TYPE_INVITATION             = 'invitation';
    const TYPE_INVITATION_REMINDER    = 'invitation_reminder';
    const TYPE_ATTENDANCE_CONFIRMATION = 'attendance_confirmation';
    const TYPE_PROPOSAL_STATUS_UPDATE = 'proposal_status_update';
    const TYPE_NEW_DOCUMENTATION      = 'new_documentation';
    const TYPE_SYSTEM_ANNOUNCEMENT    = 'system_announcement';

    /** Notification Channels */
    const CHANNEL_WEB      = 'web';
    const CHANNEL_EMAIL    = 'email';
    const CHANNEL_WHATSAPP = 'whatsapp';

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

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                     ->where('scheduled_at', '<=', now());
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeReadyToSend($query)
    {
        return $query->pending()
                     ->where(function ($q) {
                         $q->whereNull('sent_at')
                           ->orWhere('status', 'failed');
                     });
    }

    public function scopeScheduled($query)
    {
        return $query->where('scheduled_at', '>', now())
                     ->where('status', 'pending');
    }

    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isReadyToSend(): bool
    {
        return $this->isPending()
            && $this->scheduled_at <= now()
            && is_null($this->sent_at);
    }

    public function isScheduled(): bool
    {
        return $this->isPending() && $this->scheduled_at > now();
    }

    public function canBeRetried(): bool
    {
        return $this->isFailed() && $this->retry_count < 3;
    }

    public function markAsSent(): bool
    {
        return $this->update([
            'status'         => 'sent',
            'sent_at'        => now(),
            'failure_reason' => null,
        ]);
    }

    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    public function markAsFailed($reason = null): bool
    {
        return $this->update([
            'status'         => 'failed',
            'failure_reason' => $reason,
            'retry_count'    => $this->retry_count + 1,
        ]);
    }

    public function retry(): bool
    {
        if (!$this->isFailed()) {
            return false;
        }

        return $this->update([
            'status'        => 'pending',
            'scheduled_at'  => now()->addMinutes(5),
            'failure_reason' => null,
        ]);
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'sent'    => 'info',
            'read'    => 'success',
            'failed'  => 'danger',
            default   => 'secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'sent'    => 'Terkirim',
            'read'    => 'Dibaca',
            'failed'  => 'Gagal',
            default   => $this->status,
        };
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_ACTIVITY_REMINDER      => 'Pengingat Kegiatan',
            self::TYPE_NEW_ACTIVITY           => 'Kegiatan Baru',
            self::TYPE_ACTIVITY_UPDATE        => 'Update Kegiatan',
            self::TYPE_ACTIVITY_CANCELLED     => 'Kegiatan Dibatalkan',
            self::TYPE_INVITATION             => 'Undangan',
            self::TYPE_INVITATION_REMINDER    => 'Pengingat Undangan',
            self::TYPE_ATTENDANCE_CONFIRMATION => 'Konfirmasi Kehadiran',
            self::TYPE_PROPOSAL_STATUS_UPDATE => 'Update Status Usulan',
            self::TYPE_NEW_DOCUMENTATION      => 'Dokumentasi Baru',
            self::TYPE_SYSTEM_ANNOUNCEMENT    => 'Pengumuman Sistem',
            default                           => $this->type,
        };
    }

    public function getChannelLabel(): string
    {
        return match ($this->channel) {
            self::CHANNEL_WEB      => 'Web',
            self::CHANNEL_EMAIL    => 'Email',
            self::CHANNEL_WHATSAPP => 'WhatsApp',
            default => $this->channel,
        };
    }

    public function getTimeSinceSent(): string
    {
        return $this->sent_at
            ? $this->sent_at->diffForHumans()
            : 'Belum dikirim';
    }

    public function getTimeSinceRead(): string
    {
        return $this->read_at
            ? $this->read_at->diffForHumans()
            : 'Belum dibaca';
    }

    public function getTimeUntilScheduled(): string
    {
        return $this->scheduled_at <= now()
            ? 'Siap dikirim'
            : $this->scheduled_at->diffForHumans();
    }

    public function getData($key, $default = null)
    {
        return data_get($this->data, $key, $default);
    }

    public function setData($key, $value): bool
    {
        $data = $this->data ?? [];
        data_set($data, $key, $value);

        return $this->update(['data' => $data]);
    }

    public static function createActivityReminder($userId, $activityId, $minutesBefore = 60): self
    {
        $activity = Activity::findOrFail($activityId);

        return self::create([
            'type'         => self::TYPE_ACTIVITY_REMINDER,
            'title'        => "Pengingat Kegiatan: {$activity->title}",
            'message'      => "Kegiatan {$activity->title} akan dimulai dalam {$minutesBefore} menit di {$activity->location}.",
            'user_id'      => $userId,
            'activity_id'  => $activityId,
            'channel'      => self::CHANNEL_WEB,
            'scheduled_at' => $activity->start_date->subMinutes($minutesBefore),
            'data' => [
                'minutes_before' => $minutesBefore,
                'activity_title' => $activity->title,
                'activity_location' => $activity->location,
                'activity_start' => $activity->start_date->toISOString(),
            ],
        ]);
    }

    public static function createNewActivityNotification($userId, $activityId): self
    {
        $activity = Activity::findOrFail($activityId);

        return self::create([
            'type'        => self::TYPE_NEW_ACTIVITY,
            'title'       => "Kegiatan Baru: {$activity->title}",
            'message'     => "Kegiatan baru '{$activity->title}' telah dibuat. Jangan lupa untuk konfirmasi kehadiran!",
            'user_id'     => $userId,
            'activity_id' => $activityId,
            'channel'     => self::CHANNEL_WEB,
            'data' => [
                'activity_title' => $activity->title,
                'activity_description' => $activity->description,
                'activity_start' => $activity->start_date->toISOString(),
                'activity_location' => $activity->location,
            ],
        ]);
    }

    public static function createInvitationNotification($userId, $activityId, $invitationId): self
    {
        $activity = Activity::findOrFail($activityId);

        return self::create([
            'type'        => self::TYPE_INVITATION,
            'title'       => "Undangan Kegiatan: {$activity->title}",
            'message'     => "Anda diundang mengikuti kegiatan '{$activity->title}'. Silakan konfirmasi kehadiran.",
            'user_id'     => $userId,
            'activity_id' => $activityId,
            'channel'     => self::CHANNEL_WEB,
            'data' => [
                'invitation_id' => $invitationId,
                'activity_title' => $activity->title,
                'activity_start' => $activity->start_date->toISOString(),
                'activity_location' => $activity->location,
            ],
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function getIcon(): string
    {
        return match ($this->type) {
            self::TYPE_ACTIVITY_REMINDER => 'clock',
            self::TYPE_NEW_ACTIVITY => 'calendar-plus',
            self::TYPE_ACTIVITY_UPDATE => 'calendar-edit',
            self::TYPE_ACTIVITY_CANCELLED => 'calendar-x',
            self::TYPE_INVITATION => 'mail',
            self::TYPE_INVITATION_REMINDER => 'mail-warning',
            self::TYPE_ATTENDANCE_CONFIRMATION => 'check-circle',
            self::TYPE_PROPOSAL_STATUS_UPDATE => 'file-text',
            self::TYPE_NEW_DOCUMENTATION => 'image',
            self::TYPE_SYSTEM_ANNOUNCEMENT => 'megaphone',
            default => 'bell',
        };
    }

    public function getActionUrl(): ?string
    {
        return match ($this->type) {
            self::TYPE_ACTIVITY_REMINDER,
            self::TYPE_NEW_ACTIVITY,
            self::TYPE_ACTIVITY_UPDATE,
            self::TYPE_ACTIVITY_CANCELLED =>
                route('activities.show', $this->activity_id),

            self::TYPE_INVITATION,
            self::TYPE_INVITATION_REMINDER =>
                route('invitations.show', $this->getData('invitation_id')),

            self::TYPE_ATTENDANCE_CONFIRMATION =>
                route('activities.attendance', $this->activity_id),

            self::TYPE_PROPOSAL_STATUS_UPDATE =>
                route('proposals.show', $this->getData('proposal_id')),

            self::TYPE_NEW_DOCUMENTATION =>
                route('documentations.show', $this->getData('documentation_id')),

            default => null,
        };
    }

    public function getActionText(): ?string
    {
        return match ($this->type) {
            self::TYPE_ACTIVITY_REMINDER,
            self::TYPE_NEW_ACTIVITY,
            self::TYPE_ACTIVITY_UPDATE,
            self::TYPE_ACTIVITY_CANCELLED => 'Lihat Kegiatan',

            self::TYPE_INVITATION,
            self::TYPE_INVITATION_REMINDER => 'Lihat Undangan',

            self::TYPE_ATTENDANCE_CONFIRMATION => 'Konfirmasi Kehadiran',
            self::TYPE_PROPOSAL_STATUS_UPDATE => 'Lihat Usulan',
            self::TYPE_NEW_DOCUMENTATION => 'Lihat Dokumentasi',

            default => 'Lihat Detail',
        };
    }
}
