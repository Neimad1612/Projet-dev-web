<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class ExperienceLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'event_type', 'points_earned', 'total_after', 'description', 'loggable_id', 'loggable_type', 'adjusted_by'];
    protected function casts(): array { return ['created_at' => 'datetime', 'points_earned' => 'integer', 'total_after' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function adjustedBy(): BelongsTo { return $this->belongsTo(User::class, 'adjusted_by'); }
    public function loggable(): MorphTo { return $this->morphTo(); }
}