@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="font-family: 'Playfair Display', serif; color:var(--leon-dark); margin:0;">Mon Profil</h1>
                <span class="badge" style="background:var(--leon-gold); font-size:1rem; padding:8px 15px;">
                    Niveau : {{ ucfirst($user->level) }}
                </span>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted font-weight-bold text-uppercase" style="font-size:0.8rem;">Nom complet</div>
                        <div class="col-sm-8 font-weight-bold">{{ $user->name }}</div>
                    </div>
                    <hr style="border-color:var(--leon-border);">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted font-weight-bold text-uppercase" style="font-size:0.8rem;">Pseudo</div>
                        <div class="col-sm-8">{{ $user->pseudo }}</div>
                    </div>
                    <hr style="border-color:var(--leon-border);">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted font-weight-bold text-uppercase" style="font-size:0.8rem;">Email</div>
                        <div class="col-sm-8">{{ $user->email }}</div>
                    </div>
                    <hr style="border-color:var(--leon-border);">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted font-weight-bold text-uppercase" style="font-size:0.8rem;">Rôle</div>
                        <div class="col-sm-8">{{ ucfirst($user->role) }}</div>
                    </div>
                    <hr style="border-color:var(--leon-border);">
                    <div class="row">
                        <div class="col-sm-4 text-muted font-weight-bold text-uppercase" style="font-size:0.8rem;">Total XP</div>
                        <div class="col-sm-8"><strong style="color:var(--leon-gold);">{{ $user->experience_points }} XP</strong></div>
                    </div>
                </div>
            </div>

            {{-- Le Formulaire de modification rapide (Caché dans un "collapse" Bootstrap si tu utilises Bootstrap, ou affiché directement) --}}
            <div class="card shadow-sm border-0 mt-4" style="border-top: 4px solid var(--leon-dark) !important;">
                <div class="card-body p-4">
                    <h3 style="font-size:1.2rem; margin-bottom:1rem;">Modifier mes informations</h3>
                    <form method="POST" action="{{ route('simple.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Ajout du champ Avatar --}}
                        <div class="mb-4 d-flex align-items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <label class="form-label" style="font-size:0.8rem; font-weight:bold; color:var(--leon-muted);">Photo de profil (Optionnel)</label>
                                <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.8rem; font-weight:bold; color:var(--leon-muted);">Nom</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.8rem; font-weight:bold; color:var(--leon-muted);">Pseudo</label>
                                <input type="text" name="pseudo" class="form-control" value="{{ old('pseudo', $user->pseudo) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:0.8rem; font-weight:bold; color:var(--leon-muted);">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="alert alert-light border mt-4 mb-3">
                            <small class="text-muted d-block mb-2">Laissez les champs de mot de passe vides si vous ne souhaitez pas le changer.</small>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn" style="background:var(--leon-dark); color:white; padding:10px 20px;">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection