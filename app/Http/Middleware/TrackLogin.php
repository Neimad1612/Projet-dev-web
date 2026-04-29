<?php
namespace App\Http\Middleware;

use App\Models\User;
use App\Services\ExperienceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class TrackLogin
{
    public function __construct(private ExperienceService $xpService) {}

    public function handle(Request $request, Closure $next): mixed {
        $response = $next($request);
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = "xp.login.{$user->id}." . now()->toDateString();
            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, now()->endOfDay());
                $user->updateQuietly(['last_login_at' => now()]);
                $this->xpService->award($user, 'login', User::XP_LOGIN, 'Connexion quotidienne');
            }
        }
        return $response;
    }
}