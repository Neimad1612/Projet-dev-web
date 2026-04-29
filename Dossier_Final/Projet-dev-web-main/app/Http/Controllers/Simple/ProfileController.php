<?php

namespace App\Http\Controllers\Simple;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('simple.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('simple.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'pseudo'     => ['required', 'string', 'max:50', 'not_in:modifier,dashboard,experience,objets', 'regex:/^[a-zA-Z0-9_\-]+$/', Rule::unique('users')->ignore($user->id)],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'gender'     => ['nullable', 'string', 'in:M,F,other'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'password'   => ['nullable', 'min:8', 'confirmed'],
            'avatar'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validation de l'image
        ]);

        $user->name = $validated['name'];
        $user->pseudo = $validated['pseudo'];
        $user->email = $validated['email'];
        $user->gender = $validated['gender'] ?? $user->gender;
        $user->birth_date = $validated['birth_date'] ?? $user->birth_date;
        
        // Gestion de la photo de profil
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Enregistrer la nouvelle image
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('simple.profile.show')->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    public function showPublic($pseudo)
    {
        $user = User::where('pseudo', $pseudo)->firstOrFail();
        return view('simple.profile.public', compact('user'));
    }
}