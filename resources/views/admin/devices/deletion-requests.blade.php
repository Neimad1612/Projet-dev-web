@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2>Demandes de suppression</h2>

    @forelse($devices as $device)
        <div class="card p-3 mb-3">
            <strong>{{ $device->name }}</strong>

            <p class="text-muted">
                Serial: {{ $device->serial_number }}
            </p>

            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('admin.devices.approve-delete', $device->id) }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Supprimer</button>
                </form>

                <form method="POST" action="{{ route('admin.devices.reject-delete', $device->id) }}">
                    @csrf
                    <button class="btn btn-secondary btn-sm">Refuser</button>
                </form>
            </div>
        </div>
    @empty
        <p>Aucune demande en attente.</p>
    @endforelse

</div>
@endsection