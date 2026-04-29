<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FreeTourStep extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'order', 'zone_id', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public static function activeCached(): Collection
    {
        /** @var array<int, array<string, mixed>> $raw */
        $raw = Cache::remember(
            'free_tour.steps.active',
            3600,
            fn() => static::where('is_active', true)
                           ->orderBy('order')
                           ->with('zone')
                           ->get()
                           ->toArray()   // ← La correction est ici : on convertit en tableau
        );

        return collect($raw)->map(
            fn(array $data) => (new static)->forceFill($data)
        );
    }

    public static function clearCache(): void
    {
        Cache::forget('free_tour.steps.active');
    }
}