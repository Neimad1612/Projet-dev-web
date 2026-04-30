<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

#[Fillable(['name', 'email', 'password', 'pseudo', 'gender', 'birth_date', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_VISITOR = 'visitor'; const ROLE_SIMPLE = 'simple'; const ROLE_COMPLEX = 'complex'; const ROLE_ADMIN = 'admin';
    const LEVEL_BEGINNER = 'beginner'; const LEVEL_INTERMEDIATE = 'intermediate'; const LEVEL_ADVANCED = 'advanced'; const LEVEL_EXPERT = 'expert';
    const XP_THRESHOLDS = [self::LEVEL_BEGINNER => 0, self::LEVEL_INTERMEDIATE => 100, self::LEVEL_ADVANCED => 300, self::LEVEL_EXPERT => 700];
    const XP_LOGIN = 5; const XP_DEVICE_VIEW = 2; const XP_DEVICE_ADDED = 20; const XP_DEVICE_CONTROL = 10; const XP_REPORT = 15;

    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'birth_date' => 'date', 'approved_at' => 'datetime', 'last_login_at' => 'datetime', 'is_approved' => 'boolean', 'experience_points' => 'integer'];
    }

    public function devicesCreated(): HasMany { return $this->hasMany(Device::class, 'created_by'); }
    public function deviceControls(): HasMany { return $this->hasMany(DeviceControl::class); }
    public function experienceLogs(): HasMany { return $this->hasMany(ExperienceLog::class); }
    public function reports(): HasMany { return $this->hasMany(Report::class, 'generated_by'); }
    public function approvedBy(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function approvedUsers(): HasMany { return $this->hasMany(User::class, 'approved_by'); }

    public function scopeApproved($query) { return $query->where('is_approved', true); }
    public function scopeByRole($query, string $role) { return $query->where('role', $role); }
    public function scopePendingApproval($query) { return $query->where('role', '!=', self::ROLE_VISITOR)->where('is_approved', false); }

    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
    public function isComplex(): bool { return in_array($this->role, [self::ROLE_COMPLEX, self::ROLE_ADMIN]); }
    public function hasAccessToManagement(): bool { return $this->isComplex() && in_array($this->level, [self::LEVEL_ADVANCED, self::LEVEL_EXPERT]); }
    public function hasAccessToAdmin(): bool { return $this->isAdmin() && $this->level === self::LEVEL_EXPERT; }

    public function recalculateLevel(): bool {
        $newLevel = self::LEVEL_BEGINNER;

        foreach (array_reverse(self::XP_THRESHOLDS, true) as $level => $threshold) {
            if ($this->experience_points >= $threshold) {
                $newLevel = $level;
                break;
            }
        }

        if ($this->level !== $newLevel) {
            $this->level = $newLevel;

            // Synchronise le rôle avec le niveau
            $this->syncRoleWithLevel();

            $this->save();

            return true;
        }

        return false;
    }

    protected function syncRoleWithLevel(): void
    {
        $this->role = match ($this->level) {
            self::LEVEL_EXPERT => self::ROLE_ADMIN,
            self::LEVEL_ADVANCED => self::ROLE_COMPLEX,
            default => self::ROLE_SIMPLE,
        };
    }

    public function getAgeAttribute(): ?int {
        // Sécurité du cache ajoutée : on vérifie que l'ID existe bien
        if (!$this->birth_date || !$this->id) return null;
        return Cache::remember("user.{$this->id}.age", 3600, fn() => $this->birth_date->age);
    }
    
    public function getAvatarUrlAttribute(): string { return $this->avatar ? asset('storage/' . $this->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($this->pseudo ?? $this->name); }
}
