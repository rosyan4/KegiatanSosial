<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value', 'type', 'group', 'label',
        'description', 'options', 'sort_order',
        'is_public', 'is_encrypted',
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
        'is_encrypted' => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Setting Groups */
    public const GROUP_GENERAL = 'general';
    public const GROUP_ACTIVITY = 'activity';
    public const GROUP_NOTIFICATION = 'notification';
    public const GROUP_EMAIL = 'email';
    public const GROUP_WHATSAPP = 'whatsapp';
    public const GROUP_APPEARANCE = 'appearance';
    public const GROUP_SECURITY = 'security';

    /** Setting Types */
    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_JSON = 'json';
    public const TYPE_ARRAY = 'array';
    public const TYPE_SELECT = 'select';

    /**
     * Boot - Auto encrypt value on saving
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($setting) {
            if ($setting->is_encrypted && !empty($setting->value)) {
                $setting->value = Crypt::encryptString($setting->value);
            }
        });
    }

    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('group')
                     ->orderBy('sort_order')
                     ->orderBy('label');
    }

    public function getDecryptedValueAttribute()
    {
        if ($this->is_encrypted && !empty($this->value)) {
            try {
                return Crypt::decryptString($this->value);
            } catch (\Exception) {
                return null;
            }
        }

        return $this->getCastValue();
    }

    public function getCastValue()
    {
        $value = $this->value;

        if ($this->is_encrypted && !empty($value)) {
            try {
                $value = Crypt::decryptString($value);
            } catch (\Exception) {
                return null;
            }
        }

        return match($this->type) {
            self::TYPE_BOOLEAN => (bool) $value,
            self::TYPE_INTEGER => (int) $value,
            self::TYPE_JSON => json_decode($value, true) ?? [],
            self::TYPE_ARRAY => is_array($value) ? $value : explode(',', $value),
            default => $value,
        };
    }

    public function setValueAttribute($value)
    {
        if ($this->is_encrypted && !empty($value)) {
            $this->attributes['value'] = $value;
            return;
        }

        $this->attributes['value'] = match($this->type) {
            self::TYPE_BOOLEAN => $value ? '1' : '0',
            self::TYPE_INTEGER => (string) $value,
            self::TYPE_JSON => is_array($value) ? json_encode($value) : $value,
            self::TYPE_ARRAY => is_array($value) ? implode(',', $value) : $value,
            default => $value,
        };
    }

    public static function getValue($key, $default = null)
    {
        return static::where('key', $key)->first()?->getCastValue() ?? $default;
    }

    public static function setValue($key, $value): bool
    {
        return (bool) static::where('key', $key)
                    ->first()
                    ?->update(['value' => $value]);
    }

    public static function getAllGrouped(): array
    {
        return static::ordered()
            ->get()
            ->groupBy('group')
            ->map(fn ($settings) =>
                $settings->mapWithKeys(fn ($s) => [$s->key => $s->getCastValue()])
            )
            ->toArray();
    }

    public static function getPublicSettings(): array
    {
        return static::public()
            ->get()
            ->mapWithKeys(fn ($s) => [$s->key => $s->getCastValue()])
            ->toArray();
    }

    public static function updateMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function getGroupLabel(): string
    {
        return match($this->group) {
            self::GROUP_GENERAL => 'Umum',
            self::GROUP_ACTIVITY => 'Kegiatan',
            self::GROUP_NOTIFICATION => 'Notifikasi',
            self::GROUP_EMAIL => 'Email',
            self::GROUP_WHATSAPP => 'WhatsApp',
            self::GROUP_APPEARANCE => 'Tampilan',
            self::GROUP_SECURITY => 'Keamanan',
            default => Str::title(str_replace('_', ' ', $this->group)),
        };
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            self::TYPE_STRING => 'Teks Pendek',
            self::TYPE_TEXT => 'Teks Panjang',
            self::TYPE_BOOLEAN => 'Ya/Tidak',
            self::TYPE_INTEGER => 'Angka',
            self::TYPE_JSON => 'JSON',
            self::TYPE_ARRAY => 'Array',
            self::TYPE_SELECT => 'Pilihan',
            default => Str::title($this->type),
        };
    }

    public function hasOptions(): bool
    {
        return !empty($this->options) && is_array($this->options);
    }

    public function getOptionsArray(): array
    {
        return $this->hasOptions() ? $this->options : [];
    }

    public function getSelectOptions(): array
    {
        if (!$this->hasOptions()) {
            return [];
        }

        return array_map(fn($label, $value) => [
            'value' => $value,
            'label' => $label,
        ], $this->options, array_keys($this->options));
    }

    public function validateValue($value): bool
    {
        return match($this->type) {
            self::TYPE_BOOLEAN => is_bool($value) || in_array($value, [0, 1, '0', '1'], true),
            self::TYPE_INTEGER => is_numeric($value),
            self::TYPE_JSON => is_array($value) || json_validate($value),
            self::TYPE_ARRAY => is_array($value),
            self::TYPE_SELECT => $this->hasOptions() && array_key_exists($value, $this->options),
            default => true,
        };
    }

    public function getValidationRules(): array
    {
        $rules = match($this->type) {
            self::TYPE_BOOLEAN => ['boolean'],
            self::TYPE_INTEGER => ['integer'],
            self::TYPE_JSON => ['json'],
            self::TYPE_SELECT => $this->hasOptions()
                ? ['in:' . implode(',', array_keys($this->options))]
                : [],
            default => [],
        };

        return ($this->is_required ?? false)
            ? array_merge(['required'], $rules)
            : array_merge($rules, ['nullable']);
    }

    public static function initializeDefaults(): void
    {
        $defaultSettings = [ /* ✅ Data kamu tetap utuh */ ];

        foreach ($defaultSettings as $setting) {
            static::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
