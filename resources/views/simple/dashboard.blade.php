@extends('layouts.app')

@section('title', 'Mon Tableau de bord')

@section('content')
<div class="container py-5">
    <h1 style="font-family: 'Playfair Display', serif; color:var(--leon-dark);">
        Bonjour, {{ $user->name }} ! 👋
    </h1>
    <p class="text-muted mb-5">Bienvenue sur votre espace personnel Chez Léon.</p>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <h2 style="font-size: 2.5rem; margin-bottom: 0;">{{ $user->experience_points }}</h2>
                <p class="text-muted text-uppercase" style="font-size: 0.8rem; font-weight: bold;">Points d'XP</p>
                <span class="badge bg-dark mt-2">{{ ucfirst($user->level) }}</span>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h3>Vos actions rapides</h3>
                    <div class="d-flex gap-3 mt-3 flex-wrap">
                        <a href="{{ route('simple.devices.index') }}" class="btn" style="background:var(--leon-dark); color:white; padding:10px 20px;">
                            Grille des Objets Connectés
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection