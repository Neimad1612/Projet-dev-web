@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')

@section('content')
<div class="container py-4">
    <h1 style="font-family:'Playfair Display', serif; color:var(--leon-dark); border-bottom: 2px solid var(--leon-gold); padding-bottom:10px; margin-bottom: 2rem;">
        Tableau de bord Administrateur
    </h1>

    <div class="row g-4">
        {{-- Carte Utilisateurs --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid var(--leon-gold) !important; cursor: pointer;" onclick="window.location='{{ route('admin.users.index') }}'">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1" style="font-size:0.8rem;">Utilisateurs Inscrits</h6>
                    <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                </div>
            </div>
        </div>

        {{-- Carte En Attente --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #E74C3C !important; cursor: pointer;" onclick="window.location='{{ route('admin.users.pending') }}'">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1" style="font-size:0.8rem;">En attente de validation</h6>
                    <h2 class="mb-0 text-danger">{{ $stats['pending_users'] }}</h2>
                </div>
            </div>
        </div>

        {{-- Carte Appareils --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid var(--leon-dark) !important; cursor: pointer;" onclick="window.location='{{ route('complex.devices.index') }}'">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1" style="font-size:0.8rem;">Appareils Connectés</h6>
                    <h2 class="mb-0">{{ $stats['total_devices'] }}</h2>
                </div>
            </div>
        </div>

        {{-- Carte En Ligne --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #2ECC71 !important; cursor: pointer;" onclick="window.location='{{ route('complex.devices.index') }}?status=online'">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1" style="font-size:0.8rem;">Appareils en Ligne</h6>
                    <h2 class="mb-0 text-success">{{ $stats['online_devices'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection