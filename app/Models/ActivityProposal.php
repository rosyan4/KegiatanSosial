<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityProposal extends Model
{
    use HasFactory, SoftDeletes;

    /*----------------------------------------
    | Fillable & Casts
    ----------------------------------------*/
    protected $fillable = [
        'title',
        'description',
        'objectives',
        'benefits',
        'proposed_date',
        'proposed_location',
        'estimated_participants',
        'estimated_budget',
        'required_support',
        'status',
        'admin_notes',
        'rejection_reason',
        'proposed_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'proposed_date' => 'datetime',
        'estimated_budget' => 'decimal:2',
        'estimated_participants' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    /*----------------------------------------
    | Query Scopes
    ----------------------------------------*/
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopeUnderReview($q)
    {
        return $q->where('status', 'under_review');
    }

    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    public function scopeRejected($q)
    {
        return $q->where('status', 'rejected');
    }

    public function scopeNeedRevision($q)
    {
        return $q->where('status', 'need_revision');
    }

    public function scopeByUser($q, $userId)
    {
        return $q->where('proposed_by', $userId);
    }

    public function scopeUnreviewed($q)
    {
        return $q->whereIn('status', ['pending', 'need_revision']);
    }

    public function scopeReviewed($q)
    {
        return $q->whereIn('status', ['approved', 'rejected', 'under_review']);
    }

    public function scopeUpcoming($q)
    {
        return $q->where('proposed_date', '>=', now());
    }

    public function scopePast($q)
    {
        return $q->where('proposed_date', '<', now());
    }

    /*----------------------------------------
    | Status Helpers
    ----------------------------------------*/
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function needsRevision(): bool
    {
        return $this->status === 'need_revision';
    }

    public function isReviewed(): bool
    {
        return !is_null($this->reviewed_at);
    }

    public function canBeEditedByProposer(): bool
    {
        return in_array($this->status, ['pending', 'need_revision']);
    }

    public function canBeReviewed(): bool
    {
        return in_array($this->status, ['pending', 'need_revision']);
    }

    public function isProposedDatePast(): bool
    {
        return $this->proposed_date < now();
    }

    public function isProposedDateSoon(): bool
    {
        return $this->proposed_date >= now() &&
               $this->proposed_date <= now()->addDays(7);
    }

    /*----------------------------------------
    | UI Helpers
    ----------------------------------------*/
    public function getFormattedBudget(): string
    {
        return is_null($this->estimated_budget)
            ? 'Belum ditentukan'
            : 'Rp ' . number_format($this->estimated_budget, 0, ',', '.');
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending'       => 'warning',
            'under_review'  => 'info',
            'approved'      => 'success',
            'rejected'      => 'danger',
            'need_revision' => 'secondary',
            default         => 'secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending'       => 'Menunggu Review',
            'under_review'  => 'Sedang Direview',
            'approved'      => 'Disetujui',
            'rejected'      => 'Ditolak',
            'need_revision' => 'Perlu Revisi',
            default         => $this->status,
        };
    }

    public function getDaysUntilProposedDate(): int
    {
        return now()->diffInDays($this->proposed_date, false);
    }

    public function getProposedDateFormatted(): string
    {
        return $this->proposed_date->format('d F Y');
    }

    public function getTimeSinceProposed(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getTimeSinceReviewed(): string
    {
        return $this->reviewed_at
            ? $this->reviewed_at->diffForHumans()
            : 'Belum direview';
    }

    /*----------------------------------------
    | Actions
    ----------------------------------------*/
    public function markAsUnderReview($adminId): bool
    {
        return $this->update([
            'status'      => 'under_review',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);
    }

    public function approve($adminId, $notes = null): bool
    {
        return $this->update([
            'status'          => 'approved',
            'admin_notes'     => $notes,
            'rejection_reason'=> null,
            'reviewed_by'     => $adminId,
            'reviewed_at'     => now(),
        ]);
    }

    public function reject($adminId, $reason = null): bool
    {
        return $this->update([
            'status'          => 'rejected',
            'rejection_reason'=> $reason,
            'admin_notes'     => null,
            'reviewed_by'     => $adminId,
            'reviewed_at'     => now(),
        ]);
    }

    public function requestRevision($adminId, $notes = null): bool
    {
        return $this->update([
            'status'          => 'need_revision',
            'admin_notes'     => $notes,
            'rejection_reason'=> null,
            'reviewed_by'     => $adminId,
            'reviewed_at'     => now(),
        ]);
    }

    /*----------------------------------------
    | Relationships
    ----------------------------------------*/
    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function convertedActivity()
    {
        return $this->hasOne(Activity::class, 'proposal_id');
    }

    public function isConvertedToActivity(): bool
    {
        return !is_null($this->convertedActivity);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'proposal_id');
    }

    public function isPendingRevision(): bool
    {
        return $this->status === 'need_revision';
    }

}

