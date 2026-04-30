@extends('layouts.app')

@section('title', $device->name)

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('simple.devices.index') }}" style="color:var(--leon-dark); text-decoration:none; font-weight:bold;">
            ← Retour à la liste des objets
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Playfair Display', serif; color:var(--leon-dark); margin:0;">
            {{ $device->name }}
        </h1>
        <span class="badge bg-{{ $device->status === 'online' ? 'success' : 'secondary' }}" style="font-size:0.9rem; padding:8px 12px;">
            {{ ucfirst($device->status) }}
        </span>
    </div>

    <div class="row g-4">
        {{-- Informations générales --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h3 style="font-size: 1.2rem; border-bottom: 1px solid var(--leon-border); padding-bottom: 10px;">
                        Informations
                    </h3>
                    <ul class="list-unstyled mt-3" style="line-height: 2;">
                        <li><strong>Numéro de série :</strong> {{ $device->serial_number }}</li>
                        <li><strong>Catégorie :</strong> {{ $device->category?->name ?? 'Non définie' }}</li>
                        <li><strong>Zone :</strong> {{ $device->zone?->name ?? 'Non définie' }}</li>
                        <li><strong>Modèle :</strong> {{ $device->model ?? '—' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Données en direct --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h3 style="font-size: 1.2rem; border-bottom: 1px solid var(--leon-border); padding-bottom: 10px;">
                        Données en direct
                    </h3>
                    <div class="mt-3">
                        @php
                            $data = $device->current_data;

                            if (is_string($data)) {
                                $data = json_decode($data, true);
                            }

                            if (!is_array($data)) {
                                $data = [];
                            }
                        @endphp

                        @if(count($data) === 0)
                            <p class="text-muted">Aucune donnée reçue pour le moment.</p>
                        @else
                            <ul class="list-unstyled" style="line-height: 2;">
                                @foreach($data as $key => $val)
                                    <li>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong>

                                        @if(is_bool($val))
                                            {{ $val ? 'Oui' : 'Non' }}
                                        @elseif(is_numeric($val))
                                            {{ $val }}
                                        @elseif(is_array($val))
                                            {{ json_encode($val) }}
                                        @else
                                            {{ $val }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(auth()->check() && in_array(auth()->user()->role, ['complex', 'admin']))
        <div class="text-center mt-5">
            <a href="{{ route('complex.devices.edit', $device->id) }}" 
               class="btn btn-primary px-4 py-2">
                Modifier l'objet
            </a>
        </div>
    @endif
    @if(auth()->check() && in_array(auth()->user()->role, ['complex', 'admin']))

        @if(!$device->delete_requested)
            <form method="POST" action="{{ route('complex.devices.request-delete', $device->id) }}">
                @csrf
                <button class="btn btn-danger mt-3">
                    Demander la suppression
                </button>
            </form>
        @else
            <p class="text-warning mt-3">
                Suppression déjà demandée
            </p>
        @endif
    @endif
</div>
@endsection