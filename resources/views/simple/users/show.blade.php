@extends('layouts.app')

@section('title', 'Profil de ' . ($user->pseudo ?? $user->name))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">

                {{-- En-tête --}}
                <div class="card-body text-center py-5 border-bottom">
                    <img
                        src="{{ $user->avatar_url }}"
                        alt="Avatar"
                        class="rounded-circle mb-3"
                        style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#avatarModal"
                    >
                    <h2 style="font-family: 'Playfair Display', serif; color: var(--leon-dark);">
                        {{ $user->pseudo ?? $user->name }}
                    </h2>
                    <small class="text-muted">{{ $user->name }}</small>
                    <div class="mt-2">
                        <span class="badge bg-dark">{{ ucfirst($user->role) }}</span>
                        <span class="badge" style="background: var(--leon-gold);">{{ ucfirst($user->level) }}</span>
                    </div>
                </div>

                {{-- Infos --}}
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted">Points d'expérience</span>
                        <strong style="color: var(--leon-gold);">{{ $user->experience_points ?? 0 }} XP</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted">Membre depuis</span>
                        <strong>{{ $user->created_at->translatedFormat('F Y') }}</strong>
                    </li>
                </ul>

                {{-- Retour --}}
                <div class="card-body text-center">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                        ← Retour à la liste
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>


{{-- Modal photo de profil --}}
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-body text-center p-0">
                <img
                    src="{{ $user->avatar_url }}"
                    alt="Avatar"
                    class="img-fluid rounded"
                    style="max-height: 80vh;"
                >
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection