{{-- ============================================================
     resources/views/simple/devices/index.blade.php — CORRIGÉ
     La vue est nettoyée de tous les blindages is_object().
     $categories et $zones sont TOUJOURS des Collections d'objets
     Eloquent (garantis par allActiveCached()).
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Objets Connectés')

@push('styles')
<style>
    .page-header {
        background: var(--leon-dark);
        padding: 2rem 0 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(90deg,
            transparent, transparent 60px,
            rgba(201,168,76,0.03) 60px, rgba(201,168,76,0.03) 61px);
        pointer-events: none;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: clamp(1.5rem, 4vw, 2rem);
        margin: 0;
    }
    .page-stats { display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: 0.75rem; }
    .stat-chip  { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: rgba(255,255,255,0.55); }
    .stat-chip strong { color: var(--leon-gold); }

    .status-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .status-dot.online      { background: #2ECC71; box-shadow: 0 0 6px rgba(46,204,113,0.6); animation: pulse-dot 2s infinite; }
    .status-dot.offline     { background: #95A5A6; }
    .status-dot.maintenance { background: #F39C12; }
    .status-dot.error       { background: #E74C3C; }
    @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

    /* Filtres */
    .filters-panel {
        background: #fff;
        border: 1px solid var(--leon-border);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-top: -1rem;
        position: relative;
        z-index: 2;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    .filters-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: var(--leon-muted); margin-bottom: 8px; display: block; letter-spacing: 0.05em; }
    .filter-select, .filter-input {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid var(--leon-border);
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        background: var(--leon-surface);
        color: var(--leon-text);
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-select:focus, .filter-input:focus { border-color: var(--leon-gold); background: #fff; }
    .btn-filter { padding: 8px 20px; background: var(--leon-dark); color: #fff; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s; }
    .btn-filter:hover { background: #2C2C28; }
    .active-filters { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px; }
    .filter-tag { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px 3px 8px; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.3); border-radius: 20px; font-size: 0.75rem; color: #7A5C1A; font-weight: 500; }
    .filter-tag button { background: none; border: none; cursor: pointer; color: inherit; padding: 0; font-size: 0.9rem; line-height: 1; }

    /* Grille */
    .devices-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; padding: 1.5rem 0; }
    @media (max-width: 576px) { .devices-grid { grid-template-columns: 1fr; } }

    /* Cartes */
    .device-card {
        background: #fff;
        border: 1px solid var(--leon-border);
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        animation: cardFadeIn 0.3s ease both;
    }
    .device-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,0.1); border-color: rgba(201,168,76,0.4); }
    @keyframes cardFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .device-card-header { padding: 1rem 1rem 0.75rem; display: flex; align-items: flex-start; gap: 0.75rem; border-bottom: 1px solid var(--leon-border); }
    .device-icon { width: 42px; height: 42px; border-radius: 10px; background: var(--leon-surface); border: 1px solid var(--leon-border); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
    .device-name { font-weight: 600; font-size: 0.9rem; color: var(--leon-text); margin: 0; }
    .device-meta { font-size: 0.75rem; color: var(--leon-muted); margin-top: 2px; margin-bottom: 0; }
    .device-status-badge { margin-left: auto; flex-shrink: 0; display: flex; align-items: center; gap: 4px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; padding: 3px 8px; border-radius: 20px; }
    .device-status-badge.online      { background: rgba(46,204,113,0.1);  color: #1A7A40; border: 1px solid rgba(46,204,113,0.25); }
    .device-status-badge.offline     { background: rgba(149,165,166,0.1); color: #5A6B6C; border: 1px solid rgba(149,165,166,0.25); }
    .device-status-badge.maintenance { background: rgba(243,156,18,0.1);  color: #7A5010; border: 1px solid rgba(243,156,18,0.25); }
    .device-status-badge.error       { background: rgba(231,76,60,0.1);   color: #7B1E1E; border: 1px solid rgba(231,76,60,0.25); }

    .device-card-body { padding: 0.75rem 1rem; flex: 1; }
    .device-data-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #F5F2EC; font-size: 0.8rem; }
    .device-data-row:last-child { border-bottom: none; }
    .device-data-key { color: var(--leon-muted); }
    .device-data-val { font-weight: 600; color: var(--leon-text); }

    .device-card-footer { padding: 0.6rem 1rem; background: var(--leon-surface); border-top: 1px solid var(--leon-border); display: flex; align-items: center; justify-content: space-between; }
    .zone-tag  { font-size: 0.72rem; color: var(--leon-muted); }
    .last-seen { font-size: 0.7rem;  color: var(--leon-muted); }

    /* Empty state */
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--leon-muted); }
    .empty-state-icon { width: 56px; height: 56px; margin: 0 auto 1rem; background: var(--leon-surface); border: 1px solid var(--leon-border); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
</style>
@endpush

@section('content')

{{-- ── En-tête ── --}}
<div class="page-header">
    <div class="container">
        <div style="position:relative;z-index:1;">
            <h1 class="page-title">Objets Connectés</h1>
            <div class="page-stats">
                <span class="stat-chip">
                    <strong>{{ $devices->total() }}</strong>&nbsp;appareil{{ $devices->total() > 1 ? 's' : '' }}
                </span>
                <span class="stat-chip">
                    <span class="status-dot online"></span>
                    <strong>{{ collect($devices->items())->where('status', 'online')->count() }}</strong>&nbsp;en ligne
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container py-3">

    {{-- ════════════════════════════════════
         FILTRES
         $categories et $zones sont des Collections d'objets Eloquent.
         Accès direct via ->id et ->name, sans blindage is_object().
         ════════════════════════════════════ --}}
    <div class="filters-panel">
        <form method="GET" action="{{ route('simple.devices.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">

                {{-- Filtre 1 : Recherche --}}
                <div class="col-12 col-md-4">
                    <label class="filters-label" for="search">Recherche</label>
                    <input type="text" id="search" name="search" class="filter-input"
                           value="{{ request('search') }}" placeholder="Nom, modèle, numéro de série…">
                </div>

                {{-- Filtre 2 : Catégorie ── CDC obligatoire --}}
                <div class="col-6 col-md-3">
                    <label class="filters-label" for="category">Catégorie</label>
                    <select name="category" id="category" class="filter-select">
                        <option value="">Toutes</option>
                        {{-- $categories = Collection d'objets DeviceCategory --}}
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                    {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtre 3 : Zone ── CDC obligatoire --}}
                <div class="col-6 col-md-3">
                    <label class="filters-label" for="zone">Zone</label>
                    <select name="zone" id="zone" class="filter-select">
                        <option value="">Toutes</option>
                        {{-- $zones = Collection d'objets Zone --}}
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}"
                                    {{ request('zone') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtre 4 : Statut --}}
                <div class="col-6 col-md-2 d-none d-md-block">
                    <label class="filters-label" for="status">Statut</label>
                    <select name="status" id="status" class="filter-select">
                        <option value="">Tous</option>
                        <option value="online"      {{ request('status') === 'online'      ? 'selected' : '' }}>En ligne</option>
                        <option value="offline"     {{ request('status') === 'offline'     ? 'selected' : '' }}>Hors ligne</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="error"       {{ request('status') === 'error'       ? 'selected' : '' }}>Erreur</option>
                    </select>
                </div>

                {{-- Actions --}}
                <div class="col-6 col-md-auto d-flex gap-2 align-items-end">
                    <button type="submit" class="btn-filter">Filtrer</button>
                    @if(request()->hasAny(['search','category','zone','status']))
                        <a href="{{ route('simple.devices.index') }}"
                           class="btn-filter" style="background:transparent;color:var(--leon-muted);border:1.5px solid var(--leon-border);">
                            ✕ Effacer
                        </a>
                    @endif
                </div>

                {{-- Tags des filtres actifs --}}
                @php
                    $activeFilters = [];
                    if (request('search'))
                        $activeFilters['search'] = 'Recherche : "' . request('search') . '"';
                    if (request('category')) {
                        $fc = $categories->firstWhere('id', request('category'));
                        $activeFilters['category'] = 'Cat. : ' . ($fc?->name ?? request('category'));
                    }
                    if (request('zone')) {
                        $fz = $zones->firstWhere('id', request('zone'));
                        $activeFilters['zone'] = 'Zone : ' . ($fz?->name ?? request('zone'));
                    }
                    if (request('status'))
                        $activeFilters['status'] = 'Statut : ' . request('status');
                @endphp
                @if($activeFilters)
                    <div class="col-12">
                        <div class="active-filters">
                            @foreach($activeFilters as $key => $label)
                                <span class="filter-tag">
                                    {{ $label }}
                                    <button type="button" onclick="clearFilter('{{ $key }}')">×</button>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </form>
    </div>

    {{-- ════════════════════════════════════
         GRILLE DES APPAREILS
         $device->category et $device->zone sont des objets Eloquent
         chargés via with(['category','zone']) — accès direct.
         ════════════════════════════════════ --}}

    @if($devices->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">⚙️</div>
            <h3 style="font-size:1rem;font-weight:600;margin-bottom:6px;">Aucun appareil trouvé</h3>
            <p style="font-size:0.875rem;">Ajustez vos filtres ou
                <a href="{{ route('simple.devices.index') }}" style="color:var(--leon-gold);">réinitialisez la recherche</a>.
            </p>
        </div>
    @else
        <div class="devices-grid" id="devicesContainer">
            @foreach($devices as $index => $device)
                <a href="{{ route('simple.devices.show', $device->id) }}"
                   class="device-card"
                   style="animation-delay: {{ $index * 0.04 }}s">

                    <div class="device-card-header">
                        <div class="device-icon">
                            @switch($device->category?->slug)
                                @case('four')          🔥 @break
                                @case('refrigerateur') ❄️ @break
                                @case('thermostat')    🌡️ @break
                                @case('borne-commande') 📱 @break
                                @case('cave-a-vin')    🍷 @break
                                @case('plonge')        💧 @break
                                @default               ⚙️
                            @endswitch
                        </div>

                        <div style="flex:1;min-width:0;">
                            <p class="device-name text-truncate">{{ $device->name }}</p>
                            <p class="device-meta">{{ $device->category?->name ?? '—' }}</p>
                        </div>

                        <span class="device-status-badge {{ $device->status }}">
                            <span class="status-dot {{ $device->status }}"></span>
                            @switch($device->status)
                                @case('online')      En ligne    @break
                                @case('offline')     Hors ligne  @break
                                @case('maintenance') Maintenance @break
                                @case('error')       Erreur      @break
                                @default {{ $device->status }}
                            @endswitch
                        </span>
                    </div>

                    <div class="device-card-body">
                        @php
                            $data = $device->current_data;

                            if (is_string($data)) {
                                $data = json_decode($data, true);
                            }

                            if (!is_array($data)) {
                                $data = [];
                            }
                        @endphp

                        @forelse($data as $key => $val)
                            <div class="device-data-row">
                                <span class="device-data-key">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                </span>

                                <span class="device-data-val">
                                    @if(is_bool($val))
                                        {{ $val ? 'Oui' : 'Non' }}
                                    @elseif(is_numeric($val))
                                        {{ number_format((float) $val, 1) }}
                                    @else
                                        {{ $val }}
                                    @endif
                                </span>
                            </div>
                        @empty
                            <p style="font-size:0.78rem;color:var(--leon-muted);text-align:center;margin:0.5rem 0;">
                                Aucune donnée disponible
                            </p>
                        @endforelse
                    </div>

                    <div class="device-card-footer">
                        <span class="zone-tag">📍 {{ $device->zone?->name ?? 'Zone non définie' }}</span>
                        <span class="last-seen">
                            {{ $device->last_seen_at?->diffForHumans() ?? 'Jamais vu' }}
                        </span>
                    </div>

                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($devices->hasPages())
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pb-4">
                <p style="font-size:0.8rem;color:var(--leon-muted);margin:0;">
                    Affichage de <strong>{{ $devices->firstItem() }}</strong>
                    à <strong>{{ $devices->lastItem() }}</strong>
                    sur <strong>{{ $devices->total() }}</strong>
                </p>
                {{ $devices->withQueryString()->links() }}
            </div>
        @endif
    @endif

</div>
@endsection

@push('scripts')
<script>
function clearFilter(name) {
    const url = new URL(window.location.href);
    url.searchParams.delete(name);
    window.location.href = url.toString();
}
document.querySelectorAll('#filterForm select').forEach(function (el) {
    el.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endpush