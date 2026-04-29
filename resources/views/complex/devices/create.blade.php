{{-- ============================================================
     resources/views/complex/devices/create.blade.php
     Module Gestion — Formulaire de création d'un appareil connecté
     Accès : role complex|admin + level advanced+
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Ajouter un appareil')

@push('styles')
<style>
    /* ── Page header ── */
    .page-header {
        background: var(--leon-dark);
        padding: 1.75rem 0;
        border-bottom: 2px solid var(--leon-gold);
    }
    .breadcrumb-trail {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.4);
        margin-bottom: 8px;
    }
    .breadcrumb-trail a { color: rgba(255,255,255,0.55); text-decoration: none; }
    .breadcrumb-trail a:hover { color: var(--leon-gold); }
    .breadcrumb-trail span { color: var(--leon-gold); }
    .page-title {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.6rem;
        margin: 0;
    }

    /* ── Layout deux colonnes ── */
    .form-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
        align-items: start;
        padding: 2rem 0;
    }
    @media (max-width: 991px) {
        .form-layout { grid-template-columns: 1fr; }
    }

    /* ── Cartes de section ── */
    .form-card {
        background: #fff;
        border: 1px solid var(--leon-border);
        border-radius: 12px;
        overflow: hidden;
    }
    .form-card + .form-card { margin-top: 1rem; }

    .form-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--leon-border);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: var(--leon-surface);
        border: 1px solid var(--leon-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .form-card-header h2 {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        color: var(--leon-text);
    }
    .form-card-header p {
        font-size: 0.75rem;
        color: var(--leon-muted);
        margin: 2px 0 0;
    }
    .form-card-body { padding: 1.5rem; }

    /* ── Champs ── */
    .field { margin-bottom: 1.25rem; }
    .field:last-child { margin-bottom: 0; }

    .field-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--leon-muted);
        margin-bottom: 6px;
    }
    .field-label .required {
        color: #E74C3C;
        font-size: 0.7rem;
    }

    .field-input,
    .field-select,
    .field-textarea {
        display: block;
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--leon-border);
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        background: var(--leon-surface);
        color: var(--leon-text);
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .field-textarea { resize: vertical; min-height: 80px; }

    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        border-color: var(--leon-gold);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
        background: #fff;
    }

    .field-input.is-invalid,
    .field-select.is-invalid { border-color: #E74C3C; }

    .field-error {
        font-size: 0.78rem;
        color: #E74C3C;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .field-hint {
        font-size: 0.75rem;
        color: var(--leon-muted);
        margin-top: 5px;
    }

    /* ── Sélecteur de catégorie visuel ── */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    @media (max-width: 576px) {
        .category-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .category-option { display: none; }
    .category-option + label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 12px 8px;
        border: 1.5px solid var(--leon-border);
        border-radius: 10px;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--leon-muted);
        background: var(--leon-surface);
        text-align: center;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
        line-height: 1.2;
    }
    .category-option + label span.cat-icon { font-size: 1.4rem; }
    .category-option:checked + label {
        border-color: var(--leon-gold);
        color: var(--leon-text);
        background: rgba(201,168,76,0.06);
    }
    .category-option + label:hover {
        border-color: rgba(201,168,76,0.5);
        color: var(--leon-text);
    }

    /* ── Sidebar ── */
    .sidebar-card {
        background: #fff;
        border: 1px solid var(--leon-border);
        border-radius: 12px;
        overflow: hidden;
        position: sticky;
        top: 80px;
    }
    .sidebar-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--leon-border);
        background: var(--leon-surface);
    }
    .sidebar-card-header h3 {
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0;
        color: var(--leon-text);
    }
    .sidebar-card-body { padding: 1.25rem; }

    /* ── Bouton submit ── */
    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: var(--leon-dark);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        position: relative;
        overflow: hidden;
    }
    .btn-submit::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: var(--leon-gold);
        transform: scaleX(0);
        transition: transform 0.2s;
    }
    .btn-submit:hover { background: #2C2C28; transform: translateY(-1px); }
    .btn-submit:hover::after { transform: scaleX(1); }
    .btn-submit:active { transform: none; }

    .btn-cancel {
        display: block;
        width: 100%;
        padding: 10px;
        text-align: center;
        border: 1.5px solid var(--leon-border);
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--leon-muted);
        text-decoration: none;
        margin-top: 8px;
        transition: border-color 0.2s, color 0.2s;
    }
    .btn-cancel:hover { border-color: #E74C3C; color: #E74C3C; }

    /* ── XP badge ── */
    .xp-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #7A5C1A;
    }

    /* ── Info checklist ── */
    .check-list { list-style: none; padding: 0; margin: 0; }
    .check-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.8rem;
        color: var(--leon-muted);
        padding: 6px 0;
        border-bottom: 1px solid var(--leon-border);
    }
    .check-list li:last-child { border-bottom: none; }
    .check-list li .icon { flex-shrink: 0; font-size: 0.85rem; }

    /* ── Status preview ── */
    .status-preview {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: rgba(149,165,166,0.08);
        border: 1px solid rgba(149,165,166,0.25);
        border-radius: 8px;
        font-size: 0.825rem;
        color: #5A6B6C;
    }
    .status-dot-preview {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #95A5A6;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- ── En-tête ── --}}
<div class="page-header">
    <div class="container">
        <div class="breadcrumb-trail">
            <a href="{{ route('complex.devices.index') }}">Gestion</a>
            <span>›</span>
            <a href="{{ route('complex.devices.index') }}">Objets connectés</a>
            <span>›</span>
            <span style="color:rgba(255,255,255,0.7)">Nouvel appareil</span>
        </div>
        <h1 class="page-title">Ajouter un appareil</h1>
    </div>
</div>

<div class="container">
    <form method="POST"
          action="{{ route('complex.devices.store') }}"
          id="createDeviceForm"
          novalidate>
        @csrf

        <div class="form-layout">

            {{-- ════════════════════════════════════
                 COLONNE PRINCIPALE
                 ════════════════════════════════════ --}}
            <div>

                {{-- ── SECTION 1 : Catégorie ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-header-icon">📂</div>
                        <div>
                            <h2>Catégorie d'appareil</h2>
                            <p>Sélectionnez le type d'objet connecté</p>
                        </div>
                    </div>
                    <div class="form-card-body">

                        <div class="category-grid">
                            @php
                                $catIcons = [
                                    'four'          => '🔥',
                                    'refrigerateur' => '❄️',
                                    'thermostat'    => '🌡️',
                                    'borne-commande'=> '📱',
                                    'cave-a-vin'    => '🍷',
                                    'plonge'        => '💧',
                                ];
                            @endphp

                            @foreach($categories as $cat)
                                <div>
                                    <input type="radio"
                                           class="category-option @error('category_id') is-invalid @enderror"
                                           name="category_id"
                                           id="cat_{{ $cat->id }}"
                                           value="{{ $cat->id }}"
                                           {{ old('category_id') == $cat->id ? 'checked' : '' }}>
                                    <label for="cat_{{ $cat->id }}">
                                        <span class="cat-icon">
                                            {{ $catIcons[$cat->slug] ?? '⚙️' }}
                                        </span>
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('category_id')
                            <p class="field-error mt-2">
                                <span>⚠</span> {{ $message }}
                            </p>
                        @enderror

                    </div>
                </div>

                {{-- ── SECTION 2 : Informations générales ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-header-icon">📋</div>
                        <div>
                            <h2>Informations générales</h2>
                            <p>Identification de l'appareil sur la plateforme</p>
                        </div>
                    </div>
                    <div class="form-card-body">

                        <div class="row g-3">
                            <div class="col-12 col-md-7">
                                <div class="field">
                                    <label class="field-label" for="name">
                                        Nom de l'appareil <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           class="field-input @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           placeholder="Ex : Four n°3 — Cuisine principale"
                                           required>
                                    @error('name')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="field">
                                    <label class="field-label" for="serial_number">
                                        Numéro de série <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           id="serial_number"
                                           name="serial_number"
                                           class="field-input @error('serial_number') is-invalid @enderror"
                                           value="{{ old('serial_number') }}"
                                           placeholder="Ex : CL-0042-AB12"
                                           required>
                                    @error('serial_number')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                    <p class="field-hint">Doit être unique sur la plateforme</p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="field">
                                    <label class="field-label" for="manufacturer">Fabricant</label>
                                    <input type="text"
                                           id="manufacturer"
                                           name="manufacturer"
                                           class="field-input @error('manufacturer') is-invalid @enderror"
                                           value="{{ old('manufacturer') }}"
                                           placeholder="Ex : Rational, Liebherr…">
                                    @error('manufacturer')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="field">
                                    <label class="field-label" for="model">Modèle</label>
                                    <input type="text"
                                           id="model"
                                           name="model"
                                           class="field-input @error('model') is-invalid @enderror"
                                           value="{{ old('model') }}"
                                           placeholder="Ex : SelfCookingCenter XS">
                                    @error('model')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── SECTION 3 : Emplacement ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-header-icon">📍</div>
                        <div>
                            <h2>Emplacement</h2>
                            <p>Associez l'appareil à une zone du restaurant</p>
                        </div>
                    </div>
                    <div class="form-card-body">

                        <div class="field">
                            <label class="field-label" for="zone_id">Zone d'installation</label>
                            <select name="zone_id"
                                    id="zone_id"
                                    class="field-select @error('zone_id') is-invalid @enderror">
                                <option value="">— Sélectionner une zone —</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}"
                                            {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                        @if($zone->type) ({{ $zone->type }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('zone_id')
                                <p class="field-error"><span>⚠</span> {{ $message }}</p>
                            @enderror
                            <p class="field-hint">Laissez vide si la zone n'est pas encore déterminée</p>
                        </div>

                    </div>
                </div>

                {{-- ── SECTION 4 : Informations techniques ── --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-header-icon">🔧</div>
                        <div>
                            <h2>Informations techniques</h2>
                            <p>Réseau, firmware, dates de service</p>
                        </div>
                    </div>
                    <div class="form-card-body">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="field">
                                    <label class="field-label" for="ip_address">Adresse IP</label>
                                    <input type="text"
                                           id="ip_address"
                                           name="ip_address"
                                           class="field-input @error('ip_address') is-invalid @enderror"
                                           value="{{ old('ip_address') }}"
                                           placeholder="192.168.1.42">
                                    @error('ip_address')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="field">
                                    <label class="field-label" for="firmware_version">Version firmware</label>
                                    <input type="text"
                                           id="firmware_version"
                                           name="firmware_version"
                                           class="field-input @error('firmware_version') is-invalid @enderror"
                                           value="{{ old('firmware_version') }}"
                                           placeholder="Ex : 2.4.1">
                                    @error('firmware_version')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="field">
                                    <label class="field-label" for="installation_date">
                                        Date d'installation
                                    </label>
                                    <input type="date"
                                           id="installation_date"
                                           name="installation_date"
                                           class="field-input @error('installation_date') is-invalid @enderror"
                                           value="{{ old('installation_date', date('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('installation_date')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="field">
                                    <label class="field-label" for="warranty_until">
                                        Fin de garantie
                                    </label>
                                    <input type="date"
                                           id="warranty_until"
                                           name="warranty_until"
                                           class="field-input @error('warranty_until') is-invalid @enderror"
                                           value="{{ old('warranty_until') }}">
                                    @error('warranty_until')
                                        <p class="field-error"><span>⚠</span> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- /.colonne principale --}}

            {{-- ════════════════════════════════════
                 SIDEBAR
                 ════════════════════════════════════ --}}
            <aside>
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h3>Résumé de création</h3>
                    </div>
                    <div class="sidebar-card-body">

                        {{-- Statut initial (informatif) --}}
                        <p style="font-size:0.75rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:var(--leon-muted);margin-bottom:8px;">
                            Statut initial
                        </p>
                        <div class="status-preview">
                            <span class="status-dot-preview"></span>
                            <div>
                                <strong style="font-size:0.825rem;">Hors ligne</strong>
                                <p style="font-size:0.72rem;margin:0;color:var(--leon-muted);">
                                    L'appareil passe en ligne automatiquement dès sa première connexion IoT
                                </p>
                            </div>
                        </div>

                        <hr style="border-color:var(--leon-border);margin:1rem 0;">

                        {{-- Gains XP --}}
                        <p style="font-size:0.75rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:var(--leon-muted);margin-bottom:8px;">
                            Récompense
                        </p>
                        <div class="xp-badge">
                            ⭐ +{{ \App\Models\User::XP_DEVICE_ADDED }} XP
                            <span style="font-weight:400;opacity:0.7;">pour cet ajout</span>
                        </div>
                        <p style="font-size:0.72rem;color:var(--leon-muted);margin-top:8px;margin-bottom:0;">
                            Votre total actuel : <strong>{{ auth()->user()->experience_points }} XP</strong>
                            (niveau <em>{{ auth()->user()->level }}</em>)
                        </p>

                        <hr style="border-color:var(--leon-border);margin:1rem 0;">

                        {{-- Checklist --}}
                        <p style="font-size:0.75rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:var(--leon-muted);margin-bottom:8px;">
                            Rappels
                        </p>
                        <ul class="check-list">
                            <li>
                                <span class="icon">📌</span>
                                Le numéro de série doit être unique et correspondre à l'étiquette physique
                            </li>
                            <li>
                                <span class="icon">🌐</span>
                                L'adresse IP doit être dans le réseau local du restaurant
                            </li>
                            <li>
                                <span class="icon">📅</span>
                                La date de garantie déclenche des alertes avant expiration
                            </li>
                            <li>
                                <span class="icon">🗂️</span>
                                Vous pourrez modifier tous ces champs après création
                            </li>
                        </ul>

                        <hr style="border-color:var(--leon-border);margin:1rem 0;">

                        {{-- Actions --}}
                        <button type="submit" class="btn-submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Créer l'appareil
                        </button>
                        <a href="{{ route('complex.devices.index') }}" class="btn-cancel">
                            Annuler
                        </a>

                    </div>
                </div>
            </aside>

        </div>{{-- /.form-layout --}}
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Synchroniser la date de fin de garantie avec la date d'installation
    const installDate   = document.getElementById('installation_date');
    const warrantyDate  = document.getElementById('warranty_until');

    installDate?.addEventListener('change', function () {
        if (warrantyDate && !warrantyDate.value) {
            // Par défaut, suggère 2 ans après l'installation
            const d = new Date(this.value);
            d.setFullYear(d.getFullYear() + 2);
            warrantyDate.min   = this.value;
            warrantyDate.value = d.toISOString().split('T')[0];
        } else if (warrantyDate) {
            warrantyDate.min = this.value;
        }
    });

    // Validation côté client : catégorie obligatoire avant soumission
    document.getElementById('createDeviceForm')?.addEventListener('submit', function (e) {
        const catSelected = document.querySelector('input[name="category_id"]:checked');
        if (!catSelected) {
            e.preventDefault();
            const grid = document.querySelector('.category-grid');
            grid.style.outline = '2px solid #E74C3C';
            grid.style.outlineOffset = '4px';
            grid.style.borderRadius  = '8px';
            grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Afficher un message d'erreur temporaire
            let msg = document.getElementById('cat-error-msg');
            if (!msg) {
                msg = document.createElement('p');
                msg.id = 'cat-error-msg';
                msg.style.cssText = 'color:#E74C3C;font-size:0.78rem;margin-top:8px;display:flex;align-items:center;gap:4px;';
                msg.innerHTML = '<span>⚠</span> Veuillez sélectionner une catégorie.';
                grid.parentElement.appendChild(msg);
            }
        }
    });

    // Retirer le highlight d'erreur dès qu'une catégorie est choisie
    document.querySelectorAll('input[name="category_id"]').forEach(function (input) {
        input.addEventListener('change', function () {
            const grid = document.querySelector('.category-grid');
            grid.style.outline = '';
            const msg = document.getElementById('cat-error-msg');
            if (msg) msg.remove();
        });
    });
</script>
@endpush