<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Documentation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_id',
        'title',
        'slug',
        'content',
        'summary',
        'featured_image',
        'gallery_images',
        'view_count',
        'created_by',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'view_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($doc) {
            // Generate slug otomatis
            if (empty($doc->slug)) {
                $doc->slug = Str::slug($doc->title) . '-' . Str::random(6);
            }
        });

        static::updating(function ($doc) {
            // Generate slug apabila judul berubah dan slug kosong
            if ($doc->isDirty('title') && empty($doc->slug)) {
                $doc->slug = Str::slug($doc->title) . '-' . Str::random(6);
            }
        });
    }

    /** RELATION */
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** SCOPE */
    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /** GALLERY HANDLER */
    public function hasGallery(): bool
    {
        return !empty($this->gallery_images) &&
            is_array($this->gallery_images) &&
            count($this->gallery_images) > 0;
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

        $gallery = array_filter($this->gallery_images, fn($i) => $i !== $imagePath);

        return $this->update(['gallery_images' => array_values($gallery)]);
    }

    /** EXTRA FUNCTIONS */
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

    /** ROUTE BINDING */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /** MUTATORS */
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
            $this->attributes['summary'] = Str::limit(strip_tags($value), 200);
        }
    }
}
