<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceReading extends Model
{
    public $timestamps = false;
    protected $fillable = ['device_id', 'metric', 'value', 'unit', 'raw_payload', 'recorded_at'];
    protected function casts(): array { return ['value' => 'float', 'raw_payload' => 'array', 'recorded_at' => 'datetime']; }
    public function device(): BelongsTo { return $this->belongsTo(Device::class); }
    public function scopeForMetric($query, string $metric) { return $query->where('metric', $metric); }
    public function scopeInPeriod($query, $start, $end) { return $query->whereBetween('recorded_at', [$start, $end]); }
}