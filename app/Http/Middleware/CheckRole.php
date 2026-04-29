<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed {
        $user = Auth::user();
        if (!$user || !$user->is_approved) abort(403, 'Accès non autorisé ou compte en attente.');
        if (!in_array($user->role, $roles)) abort(403, "Rôle insuffisant.");
        return $next($request);
    }
}