@extends('layouts.app')
@section('title', 'Visite Guidée')

@section('content')
<div class="container py-5">
    <h1 style="font-family:'Playfair Display', serif; text-align:center; margin-bottom: 3rem;">Visite Guidée des Installations</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="border-left: 3px solid var(--leon-gold); padding-left: 20px;">
                @forelse($steps as $step)
                    <div class="mb-5" style="position:relative;">
                        <div style="position:absolute; left:-29px; top:0; width:15px; height:15px; background:var(--leon-dark); border:2px solid var(--leon-gold); border-radius:50%;"></div>
                        <h3 style="color:var(--leon-dark); margin-bottom:10px;">{{ $step->title }}</h3>
                        <p class="text-muted">{{ $step->description }}</p>
                        @if($step->zone)
                            <span class="badge bg-secondary">Zone : {{ $step->zone->name }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">La visite guidée est en cours de préparation...</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection