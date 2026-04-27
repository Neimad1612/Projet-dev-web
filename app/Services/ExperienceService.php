<?php

namespace App\Services;

use App\Models\{User, ExperienceLog};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ExperienceService
{
    /**
     * Attribue des points d'XP à un utilisateur pour une action donnée.
     */
    public function award(User $user, string $eventType, int $points, string $description, ?Model $relatedModel = null)
    {
        // On s'assure que l'XP ne soit jamais négatif
        $user->experience_points = max(0, $user->experience_points + $points);
        $user->recalculateLevel();
        $user->save();

        ExperienceLog::create([
            'user_id'       => $user->id,
            'event_type'    => $eventType, // Ex: 'login', 'device_added'
            'points_earned' => $points,
            'total_after'   => $user->experience_points, // CORRECTION : Ajout du total
            'description'   => $description,
            'related_id'    => $relatedModel?->id,
            'related_type'  => $relatedModel ? get_class($relatedModel) : null,
        ]);
    }

    /**
     * Ajustement manuel par un administrateur.
     */
    public function adminAdjust(User $user, int $delta, string $reason, User $admin)
    {
        $user->experience_points = max(0, $user->experience_points + $delta);
        $user->recalculateLevel();
        $user->save();

        ExperienceLog::create([
            'user_id'       => $user->id,
            'event_type'    => 'manual_adjustment', // CORRECTION : le bon mot attendu par la BDD
            'points_earned' => $delta,
            'total_after'   => $user->experience_points, // CORRECTION : Ajout du total
            'description'   => "Ajustement par l'admin ({$admin->name}) : {$reason}",
            'adjusted_by'   => $admin->id, // CORRECTION : Ajout de l'admin responsable
        ]);
    }
}