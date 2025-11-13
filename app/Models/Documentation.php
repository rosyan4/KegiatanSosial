<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass Assignable Attributes
     */
    protected $fillable = [
        'activity_id',
        'title',
        'slug',
        'content',
        'summary',
        'featured_image',
        'gallery_images',
        'view_count',
        'is_published',
        'published_at',
        'created_by',
    ];

    /**
     * Cast Attributes
     */
    protected $casts = [
        'gallery_images' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Boot function for automatic handling
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($doc) {
            if (empty($doc->slug)) {
                $doc->slug = Str::slug($doc->title) . '-' . Str::random(6);
            }

            if ($doc->is_published && empty($doc->published_at)) {
                $doc->published_at = now();
            }
        });

        static::updating(function ($doc) {
            if ($doc->isDirty('title') && empty($doc->slug)) {
                $doc->slug = Str::slug($doc->title) . '-' . Str::random(6);
            }

            if (
                $doc->isDirty('is_published') &&
                $doc->is_published &&
                empty($doc->published_at)
            ) {
                $doc->published_at = now();
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false)
            ->orWhereNull('published_at')
            ->orWhere('published_at', '>', now());
    }

    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeLatestPublished($query, $limit = 10)
    {
        return $query->published()
            ->latest('published_at')
            ->limit($limit);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->published()
            ->orderBy('view_count', 'desc')
            ->latest('published_at')
            ->limit($limit);
    }

    public function scopeWithGallery($query)
    {
        return $query->whereNotNull('gallery_images')
            ->where('gallery_images', '!=', '[]');
    }

    public function isPublished(): bool
    {
        return $this->is_published &&
            $this->published_at &&
            $this->published_at <= now();
    }

    public function isDraft(): bool
    {
        return !$this->isPublished();
    }

    public function isScheduled(): bool
    {
        return $this->is_published &&
            $this->published_at &&
            $this->published_at > now();
    }

    public function hasFeaturedImage(): bool
    {
        return !empty($this->featured_image);
    }

    public function hasGallery(): bool
    {
        return !empty($this->gallery_images)
            && is_array($this->gallery_images)
            && count($this->gallery_images) > 0;
    }

    public function getGalleryCount(): int
    {
        return $this->hasGallery() ? count($this->gallery_images) : 0;
    }

    public function getGalleryImages(): array
    {
        return $this->hasGallery() ? $this->gallery_images : [];
    }

    public function addToGallery($imagePath): bool
    {
        $gallery = $this->gallery_images ?? [];
        $gallery[] = $imagePath;

        return $this->update(['gallery_images' => $gallery]);
    }

    public function removeFromGallery($imagePath): bool
    {
        if (!$this->hasGallery()) return false;

        $gallery = array_filter($this->gallery_images, fn($img) => $img !== $imagePath);

        return $this->update(['gallery_images' => array_values($gallery)]);
    }

    public function publish(): bool
    {
        return $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish(): bool
    {
        return $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function schedule($publishDate): bool
    {
        return $this->update([
            'is_published' => true,
            'published_at' => $publishDate,
        ]);
    }

    public function incrementViewCount(): bool
    {
        return $this->increment('view_count');
    }

    public function getExcerpt($length = 150): string
    {
        $content = strip_tags($this->content);
        return strlen($content) <= $length
            ? $content
            : Str::limit($content, $length);
    }

    public function getReadingTime(): string
    {
        $minutes = ceil(str_word_count(strip_tags($this->content)) / 200);
        return $minutes <= 1 ? 'Kurang dari 1 menit' : "{$minutes} menit";
    }

    public function getTimeSincePublished(): string
    {
        return $this->published_at
            ? $this->published_at->diffForHumans()
            : 'Belum dipublikasi';
    }

    public function getPublishedDateFormatted(): string
    {
        return $this->published_at
            ? $this->published_at->format('d F Y')
            : '-';
    }

    public function getStatusLabel(): string
    {
        return $this->isScheduled()
            ? 'Terjadwal'
            : ($this->isPublished() ? 'Dipublikasi' : 'Draft');
    }

    public function getStatusColor(): string
    {
        return $this->isScheduled()
            ? 'warning'
            : ($this->isPublished() ? 'success' : 'secondary');
    }

    public function isCreatedBy($userId): bool
    {
        return $this->created_by == $userId;
    }

    public function canBeEditedBy($user): bool
    {
        return $user->isAdmin() || $this->isCreatedBy($user->id);
    }

    public function canBeDeletedBy($user): bool
    {
        return $user->isAdmin() || $this->isCreatedBy($user->id);
    }

    public function duplicate($newTitle = null): self
    {
        $new = $this->replicate();
        $new->title = $newTitle ?: "{$this->title} (Salinan)";
        $new->slug = null;
        $new->is_published = false;
        $new->published_at = null;
        $new->view_count = 0;
        $new->push();
        return $new;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value) . '-' . Str::random(6);
        }
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $value;

        if (empty($this->attributes['summary'])) {
            $this->attributes['summary'] =
                Str::limit(strip_tags($value), 200);
        }
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
