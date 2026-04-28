@extends('layouts.app')

@section('title', 'Modifier ' . $device->name)

@push('styles')
<style>
    .page-header { background: var(--leon-dark); padding: 1.75rem 0; border-bottom: 2px solid var(--leon-gold); }
    .breadcrumb-trail { display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: rgba(255,255,255,0.4); margin-bottom: 8px; }
    .breadcrumb-trail a { color: rgba(255,255,255,0.55); text-decoration: none; }
    .breadcrumb-trail a:hover { color: var(--leon-gold); }
    .page-title { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.6rem; margin: 0; }
    
    .form-layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start; padding: 2rem 0; }
    @media (max-width: 991px) { .form-layout { grid-template-columns: 1fr; } }

    .form-card { background: #fff; border: 1px solid var(--leon-border); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; }
    .form-card-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--leon-border); display: flex; align-items: center; gap: 10px; }
    .form-card-header h2 { font-size: 0.9rem; font-weight: 600; margin: 0; color: var(--leon-text); }
    .form-card-body { padding: 1.5rem; }

    .field { margin-bottom: 1.25rem; }
    .field-label { display: block; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; color: var(--leon-muted); margin-bottom: 6px; }
    .field-input, .field-select { width: 100%; padding: 10px 14px; border: 1.5px solid var(--leon-border); border-radius: 8px; font-size: 0.9rem; background: var(--leon-surface); outline: none; }
    .field-input:focus { border-color: var(--leon-gold); background: #fff; }
    
    .category-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .category-option { display: none; }
    .category-option + label { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 12px 8px; border: 1.5px solid var(--leon-border); border-radius: 10px; cursor: pointer; font-size: 0.75rem; background: var(--leon-surface); text-align: center; }
    .category-option:checked + label { border-color: var(--leon-gold); background: rgba(201,168,76,0.06); color: var(--leon-text); }

    .btn-submit { width: 100%; padding: 12px; background: var(--leon-dark); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-cancel { display: block; width: 100%; padding: 10px; text-align: center; border: 1.5px solid var(--leon-border); border-radius: 8px; color: var(--leon-muted); text-decoration: none; margin-top: 8px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div class="breadcrumb-trail">
            <a href="{{ route('complex.devices.index') }}">Gestion</a> <span>›</span>
            <span>Modifier l'appareil</span>
        </div>
        <h1 class="page-title">Modifier : {{ $device->name }}</h1>
    </div>
</div>

<div class="container">
    <form method="POST" action="{{ route('complex.devices.update', $device) }}">
        @csrf
        @method('PUT')

        <div class="form-layout">
            <div>
                {{-- Catégorie --}}
                <div class="form-card">
                    <div class="form-card-header"><h2>Catégorie d'appareil</h2></div>
                    <div class="form-card-body">
                        <div class="category-grid">
                            @foreach($categories as $cat)
                                <input type="radio" class="category-option" name="category_id" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" {{ $device->category_id == $cat->id ? 'checked' : '' }}>
                                <label for="cat_{{ $cat->id }}">
                                    <span>{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Infos Générales --}}
                <div class="form-card">
                    <div class="form-card-header"><h2>Informations générales</h2></div>
                    <div class="form-card-body">
                        <div class="field">
                            <label class="field-label">Nom de l'appareil</label>
                            <input type="text" name="name" class="field-input" value="{{ old('name', $device->name) }}" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Numéro de série</label>
                            <input type="text" name="serial_number" class="field-input" value="{{ old('serial_number', $device->serial_number) }}" required>
                        </div>
                    </div>
                </div>

                {{-- Emplacement --}}
                <div class="form-card">
                    <div class="form-card-header"><h2>Emplacement</h2></div>
                    <div class="form-card-body">
                        <div class="field">
                            <label class="field-label">Zone</label>
                            <select name="zone_id" class="field-select">
                                <option value="">— Aucune zone —</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ $device->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <aside>
                <div class="form-card">
                    <div class="form-card-header"><h2>Actions</h2></div>
                    <div class="form-card-body">
                        <div class="field">
                            <label class="field-label">Statut d'activation</label>
                            <select name="is_active" class="field-select">
                                <option value="1" {{ $device->is_active ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ !$device->is_active ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                        <a href="{{ route('simple.devices.show', $device) }}" class="btn-cancel">Annuler</a>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection