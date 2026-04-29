<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Facades\Cache;

class Device extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'serial_number', 'model', 'manufacturer', 'category_id', 'zone_id', 'status', 'current_data', 'last_seen_at', 'ip_address', 'firmware_version', 'installation_date', 'warranty_until', 'created_by'];
    protected function casts(): array { return ['current_data' => 'array', 'last_seen_at' => 'datetime', 'installation_date' => 'date', 'warranty_until' => 'date']; }

    public function category(): BelongsTo { return $this->belongsTo(DeviceCategory::class, 'category_id'); }
    public function zone(): BelongsTo { return $this->belongsTo(Zone::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function readings(): HasMany { return $this->hasMany(DeviceReading::class); }
    public function latestReadings(): HasMany { return $this->hasMany(DeviceReading::class)->orderByDesc('recorded_at')->limit(50); }
    public function controls(): HasMany { return $this->hasMany(DeviceControl::class); }
    public function reports(): HasMany { return $this->hasMany(Report::class); }

    public function scopeOnline($query) { return $query->where('status', 'online'); }
    public function scopeByCategory($query, int|string $categoryId) { return $query->where('category_id', $categoryId); }
    public function scopeByZone($query, int|string $zoneId) { return $query->where('zone_id', $zoneId); }
    public function scopeSearch($query, string $term) {
        return $query->where(fn($q) => $q->where('name', 'like', "%{$term}%")->orWhere('serial_number', 'like', "%{$term}%")->orWhere('model', 'like', "%{$term}%"));
    }

    public function isOnline(): bool { return $this->status === 'online'; }
    public function getIsActiveAttribute(): bool{ return $this->status === 'online'; }
    public function getCachedCurrentData(): ?array { return Cache::remember("device.{$this->id}.current_data", 30, fn() => $this->current_data); }
    public function invalidateDataCache(): void { Cache::forget("device.{$this->id}.current_data"); }
}