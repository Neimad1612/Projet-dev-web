<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceControl extends Model
{
    use HasFactory;
    protected $fillable = ['device_id', 'user_id', 'action', 'parameters', 'status', 'response'];
    protected function casts(): array { return ['parameters' => 'array']; }
    public function device(): BelongsTo { return $this->belongsTo(Device::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}