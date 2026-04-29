@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Playfair Display', serif; color:var(--leon-dark); margin:0;">
            Tous les utilisateurs
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Retour au Tableau de bord</a>
    </div>

    {{-- Filtres et Recherche --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un nom, pseudo, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les rôles</option>
                        <option value="visitor" {{ request('role') == 'visitor' ? 'selected' : '' }}>Visiteur</option>
                        <option value="simple" {{ request('role') == 'simple' ? 'selected' : '' }}>Utilisateur Simple</option>
                        <option value="complex" {{ request('role') == 'complex' ? 'selected' : '' }}>Utilisateur Avancé (Gestion)</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background:var(--leon-dark); color:white;">Filtrer</button>
                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">✕</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau des utilisateurs --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--leon-surface);">
                        <tr>
                            <th>Membre</th>
                            <th>Rôle & Niveau</th>
                            <th>XP Actuel</th>
                            <th>Statut</th>
                            <th class="text-end">Actions Administratives</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                        <strong>{{ $user->name }}</strong>
                                    </a><br>
                                    <small class="text-muted">{{ $user->email }} (@ {{ $user->pseudo }})</small>
                                </td>
                                <td>
                                    <span class="badge bg-dark">{{ ucfirst($user->role) }}</span><br>
                                    <small class="text-muted">Niveau : {{ ucfirst($user->level) }}</small>
                                </td>
                                <td>
                                    <strong style="color:var(--leon-gold); font-size:1.1rem;">{{ $user->experience_points }}</strong> XP
                                </td>
                                <td>
                                    @if($user->is_approved)
                                        <span class="badge bg-success">Approuvé</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    {{-- Formulaire pour ajuster l'XP (Rapide) --}}
                                    <form method="POST" action="{{ route('admin.users.xp', $user) }}" class="d-inline-flex gap-2 align-items-center justify-content-end" onsubmit="return confirm('Confirmer l\'ajustement d\'XP pour cet utilisateur ?');">
                                        @csrf
                                        <input type="number" name="delta" class="form-control form-control-sm" style="width: 80px;" placeholder="+/- XP" required>
                                        <input type="text" name="reason" class="form-control form-control-sm" style="width: 150px;" placeholder="Raison..." required>
                                        <button type="submit" class="btn btn-sm" style="background:var(--leon-gold); color:white; font-weight:bold;">Ajuster XP</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection