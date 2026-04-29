@extends('layouts.app')

@section('title', 'Membres')

@section('content')
<div class="container py-5">
    <h1 style="font-family: 'Playfair Display', serif; color: var(--leon-dark); border-bottom: 2px solid var(--leon-gold); padding-bottom: 10px; margin-bottom: 2rem;">
        Membres
    </h1>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--leon-surface);">
                        <tr>
                            <th>Membre</th>
                            <th>Rôle</th>
                            <th>Niveau</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('simple.users.show', $user) }}'">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover;">
                                        <strong>{{ $user->pseudo ?? $user->name }}</strong>
                                    </div>
                                </td>
                                <td><span class="badge bg-dark">{{ ucfirst($user->role) }}</span></td>
                                <td><span class="badge" style="background: var(--leon-gold);">{{ ucfirst($user->level) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">Aucun membre trouvé.</td>
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