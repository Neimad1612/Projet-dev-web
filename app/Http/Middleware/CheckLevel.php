<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLevel
{
    private const LEVEL_ORDER = [User::LEVEL_BEGINNER => 0, User::LEVEL_INTERMEDIATE => 1, User::LEVEL_ADVANCED => 2, User::LEVEL_EXPERT => 3];

    public function handle(Request $request, Closure $next, string $minLevel): mixed {
        $user = Auth::user();
        if (!$user) abort(401);
        if ((self::LEVEL_ORDER[$user->level] ?? 0) < (self::LEVEL_ORDER[$minLevel] ?? 0)) {
            abort(403, "Niveau {$minLevel} requis.");
        }
        return $next($request);
    }
}