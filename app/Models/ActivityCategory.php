<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ActivityCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot function for automatic slug generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /* ============================================================
     | SCOPES
     |============================================================ */

    /** Scope kategori aktif */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Scope urutan kategori */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /* ============================================================
     | ATTRIBUTE CHECKER
     |============================================================ */

    /** Cek aktif */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /* ============================================================
     | RELATIONSHIPS
     |============================================================ */

    /** Satu kategori memiliki banyak kegiatan */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'category_id');
    }

    /* ============================================================
     | COUNT HELPERS
     |============================================================ */

    /** Count aktif activities */
    public function countActiveActivities()
    {
        return $this->activities()->where('is_active', true)->count();
    }

    /** Count semua activities */
    public function countAllActivities()
    {
        return $this->activities()->count();
    }

    /* ============================================================
     | ACCESSORS & MUTATORS
     |============================================================ */

    /** Route binding by slug */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /** Default color fallback */
    public function getColorAttribute($value)
    {
        return $value ?: '#3b82f6';
    }

    /** Set name + auto slug */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
}
