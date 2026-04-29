<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DeviceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    // --- CORRECTION DU BUG DE CACHE ---
    public static function allActiveCached(): Collection
{
    return static::where('is_active', true)
        ->orderBy('name')
        ->get();
}

    public static function clearCache(): void
    {
        Cache::forget('device_categories.active');
    }
}