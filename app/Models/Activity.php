<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'type',
        'start_date',
        'end_date',
        'location',
        'max_participants',
        'is_active',
        'requires_attendance_confirmation',
        'status',
        'created_by',
        'proposal_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
        'requires_attendance_confirmation' => 'boolean',
        'max_participants' => 'integer',
    ];

    /**
     * Booting model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title) . '-' . Str::random(6);
            }
        });

        static::updating(function ($activity) {
            if ($activity->isDirty('title') && empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title) . '-' . Str::random(6);
            }
        });
    }

    /* ============================================================
     |  Query Scopes
     ============================================================ */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUmum($query)
    {
        return $query->where('type', 'umum');
    }

    public function scopeKhusus($query)
    {
        return $query->where('type', 'khusus');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now())
                     ->orWhere('status', 'completed');
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    public function scopeWithInvitations($query)
    {
        return $query->where('type', 'khusus')
                     ->whereHas('invitations');
    }

    public function scopeFromProposal($query)
    {
        return $query->whereNotNull('proposal_id');
    }

    /* ============================================================
     |  Status Checker Helpers
     ============================================================ */

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->end_date < now();
    }

    public function isOngoing(): bool
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function isUpcoming(): bool
    {
        return $this->start_date > now();
    }

    public function isPast(): bool
    {
        return $this->end_date < now();
    }

    public function isUmum(): bool
    {
        return $this->type === 'umum';
    }

    public function isKhusus(): bool
    {
        return $this->type === 'khusus';
    }

    public function getConfirmedAttendeesCount(): int
    {
        return $this->attendanceConfirmations()->where('status', 'hadir')->count();
    }

    public function getDeclinedAttendeesCount(): int
    {
        return $this->attendanceConfirmations()->where('status', 'tidak_hadir')->count();
    }

    public function getAttendancePercentage(): float
    {
        $total = $this->attendanceConfirmations()->count();
        return $total ? round(($this->getConfirmedAttendeesCount() / $total) * 100, 2) : 0;
    }

    public function getInvitedUsers()
    {
        return $this->invitations()->with('user')->get()->pluck('user');
    }

    public function getAcceptedInvitationsCount(): int
    {
        return $this->invitations()->accepted()->count();
    }

    public function getDeclinedInvitationsCount(): int
    {
        return $this->invitations()->declined()->count();
    }

    public function getPendingInvitationsCount(): int
    {
        return $this->invitations()->pending()->count();
    }

    public function inviteUser($userId, $customMessage = null): Invitation
    {
        if (!$this->isKhusus()) {
            throw new \Exception('Hanya kegiatan khusus yang bisa mengundang user tertentu');
        }

        return Invitation::create([
            'activity_id'     => $this->id,
            'user_id'         => $userId,
            'custom_message'  => $customMessage,
            'sent_at'         => now(),
        ]);
    }

    public function inviteUsers(array $userIds, $customMessage = null): array
    {
        return array_map(fn($id) => 
            !$this->isUserInvited($id) ? $this->inviteUser($id, $customMessage) : null,
        $userIds);
    }

    public function publish(): bool
    {
        return $this->update(['status' => 'published']);
    }

    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }

    public function complete(): bool
    {
        return $this->update(['status' => 'completed']);
    }

    /* ============================================================
     |  Formatting Helpers
     ============================================================ */

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'draft'     => 'secondary',
            'published' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            default     => 'secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'published' => 'Dipublikasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default     => $this->status,
        };
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'umum'   => 'Umum',
            'khusus' => 'Khusus',
            default  => $this->type,
        };
    }

    public function getDurationInMinutes(): int
    {
        return $this->start_date->diffInMinutes($this->end_date);
    }

    public function getDurationFormatted(): string
    {
        $duration = $this->getDurationInMinutes();
        $hours    = floor($duration / 60);
        $minutes  = $duration % 60;

        return $hours > 0
            ? "{$hours} jam {$minutes} menit"
            : "{$minutes} menit";
    }

    public function getTimeUntilStart(): string
    {
        return $this->isPast() ? 'Sudah selesai' : $this->start_date->diffForHumans();
    }

    public function requiresConfirmation(): bool
    {
        return $this->requires_attendance_confirmation && $this->isPublished();
    }

    public function hasDocumentation(): bool
    {
        return !is_null($this->documentation);
    }

    public function hasPublishedDocumentation(): bool
    {
        return $this->documentation && $this->documentation->isPublished();
    }

    public function getTotalAttendeesCount(): int
    {
        return $this->attendanceConfirmations()
            ->hadir()
            ->get()
            ->sum(fn($c) => 1 + $c->number_of_guests);
    }

    public function getConfirmedAttendees()
    {
        return $this->attendanceConfirmations()
            ->with('user')
            ->hadir()
            ->get()
            ->pluck('user');
    }

    public function hasUserConfirmedAttendance($userId): bool
    {
        return $this->attendanceConfirmations()
            ->where('user_id', $userId)
            ->whereNotNull('confirmed_at')
            ->exists();
    }

    public function getRealTimeAttendanceStats(): array
    {
        return AttendanceLog::getAttendanceStats($this->id);
    }

    public function getActiveAttendees()
    {
        return $this->attendanceLogs()
            ->with('user')
            ->notCheckedOut()
            ->get()
            ->pluck('user');
    }

    public function initializeAttendanceLogs(): void
    {
        $users = $this->isKhusus()
            ? $this->invitations()->accepted()->with('user')->get()->pluck('user')->filter()
            : $this->attendanceConfirmations()->hadir()->with('user')->get()->pluck('user')->filter();

        foreach ($users as $user) {
            AttendanceLog::createForUser($this->id, $user->id, Auth::id());
        }
    }

    /* ============================================================
     |  Eloquent Relationships
     ============================================================ */

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function proposal()
    {
        return $this->belongsTo(ActivityProposal::class, 'proposal_id');
    }

    public function attendanceConfirmations()
    {
        return $this->hasMany(AttendanceConfirmation::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function documentation()
    {
        return $this->hasOne(Documentation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value) . '-' . Str::random(6);
        }
    }

    public function isFromProposal(): bool
    {
        return !is_null($this->proposal_id);
    }

    // Tambahkan method ini ke model Activity
    public function isUserInvited($userId): bool
    {
        return $this->invitations()->where('user_id', $userId)->exists();
    }

    public function getUserConfirmation($userId): ?AttendanceConfirmation
    {
        return $this->attendanceConfirmations()->where('user_id', $userId)->first();
    }

    public function getAvailableSlots(): int
    {
        if (is_null($this->max_participants)) {
            return PHP_INT_MAX;
        }
        $confirmed = $this->attendanceConfirmations()->where('status', 'hadir')->count();
        return max(0, $this->max_participants - $confirmed);
    }

    public function hasAvailableSlots(): bool
    {
        return $this->getAvailableSlots() > 0;
    }

    public function getAttendanceStats(): array
    {
        $confirmations = $this->attendanceConfirmations;
        return [
            'total' => $confirmations->count(),
            'hadir' => $confirmations->where('status', 'hadir')->count(),
            'tidak_hadir' => $confirmations->where('status', 'tidak_hadir')->count(),
            'mungkin' => $confirmations->where('status', 'mungkin')->count(),
            'total_attendees' => $confirmations->where('status', 'hadir')
                ->sum(fn($c) => 1 + $c->number_of_guests),
        ];
    }

    public function canUserJoin($userId): bool
    {
        if ($this->type === 'umum') return true;
        
        $invitation = $this->invitations()->where('user_id', $userId)->first();
        return $invitation && $invitation->status === 'accepted';
    }
}