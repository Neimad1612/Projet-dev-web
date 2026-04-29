@extends('layouts.app')

@section('title', 'Historique de mon Expérience')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Playfair Display', serif; color:var(--leon-dark); margin:0;">Historique d'Expérience</h1>
        <div style="text-align:right;">
            <span style="font-size:0.8rem; color:var(--leon-muted); text-transform:uppercase;">Total accumulé</span><br>
            <strong style="font-size:1.5rem; color:var(--leon-gold);">{{ auth()->user()->experience_points }} XP</strong>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background: var(--leon-surface);">
                        <tr>
                            <th style="font-size:0.8rem; text-transform:uppercase; color:var(--leon-muted);">Date</th>
                            <th style="font-size:0.8rem; text-transform:uppercase; color:var(--leon-muted);">Action</th>
                            <th style="font-size:0.8rem; text-transform:uppercase; color:var(--leon-muted);">Détails</th>
                            <th class="text-end" style="font-size:0.8rem; text-transform:uppercase; color:var(--leon-muted);">Gains</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td style="font-size:0.9rem;">{{ $log->created_at->format('d/m/Y à H:i') }}</td>
                                <td>
                                    <span class="badge bg-secondary" style="font-size:0.75rem;">{{ strtoupper($log->event_type) }}</span>
                                </td>
                                <td style="font-size:0.9rem;">{{ $log->description }}</td>
                                <td class="text-end">
                                    <strong style="color: {{ $log->points_earned > 0 ? '#2ECC71' : '#E74C3C' }};">
                                        {{ $log->points_earned > 0 ? '+' : '' }}{{ $log->points_earned }} XP
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Vous n'avez pas encore gagné de points d'expérience. Revenez vous connecter demain !
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection