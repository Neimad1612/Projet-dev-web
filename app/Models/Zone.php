<?php

// ============================================================
// app/Models/Zone.php — CORRIGÉ
// Ajout de allActiveCached() avec la même logique que DeviceCategory
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Zone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'type', 'capacity', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity'  => 'integer',
        ];
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function activeDevices(): HasMany
    {
        return $this->hasMany(Device::class)->where('is_active', true);
    }

    public function tourSteps(): HasMany
    {
        return $this->hasMany(FreeTourStep::class)->orderBy('order');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function scopeActiveWithDeviceCount($query)
    {
        return $query->where('is_active', true)->withCount('activeDevices');
    }

    /**
     * Zones actives depuis le cache — toArray() garantit des scalaires PHP
     * purs, sans dépendance à l'autoload lors de la désérialisation.
     * Retourne une Collection d'objets Zone reconstruits via forceFill().
     */
    public static function allActiveCached(): Collection
    {
        $raw = Cache::remember(
            'zones.active',
            3600,
            fn() => static::where('is_active', true)
                           ->orderBy('name')
                           ->get()
                           ->toArray()
        );

        return collect($raw)->map(
            fn(array $data) => (new static)->forceFill($data)
        );
    }

    public static function clearCache(): void
    {
        Cache::forget('zones.active');
    }
}