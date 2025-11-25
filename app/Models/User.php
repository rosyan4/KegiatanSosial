<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'address',
        'rt',
        'rw',
        'profile_photo',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
    ];

    /**
     * Hidden attributes for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Castable attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /****************************************
     *              SCOPES
     ****************************************/

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeWarga($query)
    {
        return $query->where('role', 'warga');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRt($query, $rt)
    {
        return $query->where('rt', $rt);
    }

    public function scopeByRw($query, $rw)
    {
        return $query->where('rw', $rw);
    }

    /****************************************
     *            CHECK FUNCTIONS
     ****************************************/

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function isPhoneVerified(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /****************************************
     *               RELATIONS
     ****************************************/

    public function createdActivities()
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    public function attendanceConfirmations()
    {
        return $this->hasMany(AttendanceConfirmation::class);
    }

    public function activityProposals()
    {
        return $this->hasMany(ActivityProposal::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function documentations()
    {
        return $this->hasMany(Documentation::class, 'created_by');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}
