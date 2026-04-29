@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
{{-- HERO SECTION avec image de fond --}}
<div style="position:relative; min-height: 80vh; display:flex; align-items:center; justify-content:center; overflow:hidden; background-color: var(--bg-dark);">
    
    {{-- Image de fond (Restaurant luxueux / IoT) depuis Unsplash --}}
    <div style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:0;">
        <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1920&auto=format&fit=crop" 
             alt="Intérieur Restaurant Chez Léon" 
             style="width:100%; height:100%; object-fit:cover; opacity:0.4;">
        {{-- Dégradé pour rendre le texte lisible --}}
        <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to bottom, rgba(24,23,20,0.8), rgba(24,23,20,0.3));"></div>
    </div>

    {{-- Contenu du Hero --}}
    <div class="container text-center" style="position:relative; z-index:1; color:white;">
        <span style="color:var(--gold-light); font-weight:600; letter-spacing:0.15em; text-transform:uppercase; font-size:0.85rem; display:block; margin-bottom:1rem;" class="fade-in-up">
            Haute Gastronomie & Intelligence Artificielle
        </span>
        <h1 class="font-display fade-in-up" style="font-size: clamp(3rem, 6vw, 5rem); font-weight: 700; margin-bottom: 1.5rem; line-height: 1.1; animation-delay: 0.1s;">
            L'excellence culinaire,<br>
            <em style="color:var(--gold-pale); font-weight:400;">pilotée par la technologie.</em>
        </h1>
        <p class="lead mx-auto fade-in-up" style="max-width: 600px; color:rgba(255,255,255,0.7); font-size:1.1rem; margin-bottom: 2.5rem; animation-delay: 0.2s;">
            Découvrez Chez Léon, le premier restaurant étoilé où l'Internet des Objets (IoT) garantit une précision absolue, de la conservation des vins à la cuisson de vos plats.
        </p>
        
        <div class="d-flex gap-3 justify-content-center fade-in-up" style="animation-delay: 0.3s;">
            <a href="{{ route('public.tour.index') }}" class="btn" style="background:var(--gold); color:var(--bg-dark); padding:12px 28px; font-size:1rem;">
                Faire la visite guidée
            </a>
            @guest
                <a href="{{ route('public.register') }}" class="btn btn-outline-secondary" style="color:white; border-color:rgba(255,255,255,0.3); padding:12px 28px; font-size:1rem;">
                    S'inscrire
                </a>
            @endguest
        </div>
    </div>
</div>

{{-- SECTION CARACTÉRISTIQUES --}}
<div class="container py-5" style="margin-top: 3rem; margin-bottom: 3rem;">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="p-4">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🌡️</div>
                <h3 class="font-display" style="font-size: 1.5rem; font-weight:600;">Précision Thermique</h3>
                <p class="text-muted">Nos sondes IoT garantissent une cuisson au degré près et une conservation parfaite de nos grands crus.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚡</div>
                <h3 class="font-display" style="font-size: 1.5rem; font-weight:600;">Éco-Responsable</h3>
                <p class="text-muted">Une gestion intelligente de l'énergie et de l'eau en temps réel pour une gastronomie durable.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📱</div>
                <h3 class="font-display" style="font-size: 1.5rem; font-weight:600;">Expérience Connectée</h3>
                <p class="text-muted">Gagnez de l'expérience (XP) en participant à la vie de la plateforme et débloquez de nouvelles fonctionnalités.</p>
            </div>
        </div>
    </div>
</div>
@endsection