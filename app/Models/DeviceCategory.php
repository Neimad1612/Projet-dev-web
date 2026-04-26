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

    // --- CORRECTION DU BUG DE CACHE ---
    public static function allActiveCached(): Collection
    {
        $raw = Cache::remember(
            'device_categories.active',
            3600,
            fn() => static::where('is_active', true)->orderBy('name')->get()->toArray()
        );

        return collect($raw)->map(
            fn(array $data) => (new static)->forceFill($data)
        );
    }

    public static function clearCache(): void
    {
        Cache::forget('device_categories.active');
    }
}