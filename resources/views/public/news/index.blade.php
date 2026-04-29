@extends('layouts.app')
@section('title', 'Actualités & Menus')

@section('content')
<div class="container py-5">
    <h1 style="font-family:'Playfair Display', serif; color:var(--leon-dark); border-bottom: 2px solid var(--leon-gold); padding-bottom:10px;">Actualités & Menus</h1>
    
    {{-- Moteur de recherche (Exigence CDC) --}}
    <form method="GET" action="{{ route('public.news.index') }}" class="my-4 p-3" style="background:#fff; border-radius:8px; border:1px solid var(--leon-border);">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un plat, un événement..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="">Toutes les catégories</option>
                    <option value="menu" {{ request('category') == 'menu' ? 'selected' : '' }}>Menu de la semaine</option>
                    <option value="announcement" {{ request('category') == 'announcement' ? 'selected' : '' }}>Annonces</option>
                    <option value="event" {{ request('category') == 'event' ? 'selected' : '' }}>Événements</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn w-100" style="background:var(--leon-dark); color:#fff;">Filtrer</button>
            </div>
        </div>
    </form>

    <div class="row">
        @forelse($news as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100" style="border-color:var(--leon-border);">
                    <div class="card-body">
                        <span class="badge" style="background:var(--leon-gold); color:#000;">{{ ucfirst($item->category) }}</span>
                        <h5 class="card-title mt-2">{{ $item->title }}</h5>
                        <p class="card-text text-muted" style="font-size:0.9rem;">{{ $item->excerpt }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">Aucune actualité trouvée.</div>
        @endforelse
    </div>
</div>
@endsection