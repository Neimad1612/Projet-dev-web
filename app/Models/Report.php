<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'type', 'generated_by', 'zone_id', 'device_id', 'filters', 'data', 'file_path', 'period_start', 'period_end'];
    protected function casts(): array { return ['filters' => 'array', 'data' => 'array', 'period_start' => 'datetime', 'period_end' => 'datetime']; }
    public function generator(): BelongsTo { return $this->belongsTo(User::class, 'generated_by'); }
    public function zone(): BelongsTo { return $this->belongsTo(Zone::class); }
    public function device(): BelongsTo { return $this->belongsTo(Device::class); }
}