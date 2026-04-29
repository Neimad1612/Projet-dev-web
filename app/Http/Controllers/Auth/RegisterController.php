<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Hash, Auth};
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('simple.dashboard');
        }
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'pseudo' => [
                'required', 'string', 'max:50', 'unique:users', 
                'not_in:modifier,dashboard,experience,objets,gestion,administration', // 🛑 Sécurité : mots réservés
                'regex:/^[a-zA-Z0-9_\-]+$/'
            ],
            'email'      => ['required', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'gender'     => ['nullable', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date', 'before:-16 years'],
        ], [
            'pseudo.regex'           => 'Le pseudo ne peut contenir que des lettres, chiffres, tirets et underscores.',
            'birth_date.before'      => 'Vous devez avoir au moins 16 ans.',
            'pseudo.unique'          => 'Ce pseudo est déjà utilisé.',
            'email.unique'           => 'Cet email est déjà utilisé.',
        ]);

        User::create([
            'name'       => $validated['name'],
            'pseudo'     => $validated['pseudo'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'gender'     => $validated['gender'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'role'       => User::ROLE_SIMPLE,
            'level'      => User::LEVEL_BEGINNER,
            'experience_points' => 0,
            'is_approved' => false,
        ]);

        return redirect()->route('public.login')
                         ->with('info', "Votre compte a été créé ! Un administrateur doit valider votre inscription avant que vous puissiez vous connecter.");
    }
}