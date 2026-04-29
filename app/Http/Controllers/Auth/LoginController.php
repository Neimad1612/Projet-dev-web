<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('simple.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = strtolower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'Identifiants incorrects.',
            ]);
        }

        $user = Auth::user();
        if (!$user->is_approved && $user->role !== 'visitor') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte est en attente de validation par un administrateur.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return match ($user->role) {
            'admin'   => redirect()->intended(route('admin.dashboard')),
            'complex' => redirect()->intended(route('complex.devices.index')),
            'simple'  => redirect()->intended(route('simple.dashboard')),
            default   => redirect()->intended(route('public.home')),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.login')
                         ->with('success', 'Vous avez été déconnecté.');
    }
}