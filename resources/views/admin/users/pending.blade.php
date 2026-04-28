@extends('layouts.app')

@section('title', 'Utilisateurs en attente')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4" style="border-color: var(--leon-gold) !important;">
        <h1 style="font-family:'Playfair Display', serif; color:var(--leon-dark); margin:0;">
            Utilisateurs en attente d'approbation
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Retour au tableau de bord</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--leon-surface);">
                        <tr>
                            <th>Date d'inscription</th>
                            <th>Nom complet</th>
                            <th>Pseudo</th>
                            <th>Email</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td>{{ $user->pseudo }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="background:#2ECC71; color:white; font-weight:bold;">
                                            Approuver
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Aucun utilisateur en attente de validation.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection