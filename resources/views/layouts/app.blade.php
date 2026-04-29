<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chez Léon') — Plateforme Connectée</title>

    {{-- Cormorant Garamond : display éditorial haut de gamme --}}
    {{-- Plus Jakarta Sans : corps tech/moderne --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    /* ═══════════════════════════════════════════════════════════════
       SYSTÈME DE DESIGN — CHEZ LÉON · GASTRO-TECH LUXE
       ═══════════════════════════════════════════════════════════════ */

    :root {
        /* Palette principale */
        --gold:          #B8922A;
        --gold-light:    #D4A843;
        --gold-pale:     #F5E6C0;
        --gold-glow:     rgba(184, 146, 42, 0.18);

        /* Fond & Surfaces */
        --bg-page:       #F4F1EA;
        --bg-card:       #FFFFFF;
        --bg-card-warm:  #FDFAF4;
        --bg-dark:       #181714;
        --bg-dark-2:     #211F1B;
        --bg-dark-3:     #2C2A25;

        /* Textes */
        --text-primary:  #1C1A16;
        --text-secondary:#5C5647;
        --text-muted:    #8C8374;
        --text-on-dark:  rgba(255,255,255,0.88);
        --text-dim:      rgba(255,255,255,0.45);

        /* Bordures */
        --border-soft:   #E8E2D5;
        --border-warm:   rgba(184,146,42,0.22);
        --border-card:   rgba(0,0,0,0.06);

        /* Ombres */
        --shadow-xs:  0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-sm:  0 2px 8px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md:  0 4px 20px rgba(0,0,0,0.09), 0 2px 6px rgba(0,0,0,0.06);
        --shadow-lg:  0 8px 40px rgba(0,0,0,0.11), 0 4px 12px rgba(0,0,0,0.07);
        --shadow-gold: 0 4px 24px rgba(184,146,42,0.22);

        /* Rayons */
        --r-sm:  8px;
        --r-md:  14px;
        --r-lg:  20px;
        --r-xl:  28px;
        --r-pill:99px;

        /* Transitions */
        --ease-out:  cubic-bezier(0.16, 1, 0.3, 1);
        --ease-back: cubic-bezier(0.34, 1.56, 0.64, 1);
        --t-fast:  0.15s;
        --t-base:  0.25s;

        /* Niveaux XP */
        --xp-beginner:     #5A9E6F;
        --xp-intermediate: #3A7FC1;
        --xp-advanced:     #8B52C4;
        --xp-expert:       #B8922A;

        /* Compatibility avec l'ancien code des vues */
        --leon-gold:    #B8922A;
        --leon-dark:    #181714;
        --leon-surface: #F4F1EA;
        --leon-border:  #E8E2D5;
        --leon-text:    #1C1A16;
        --leon-muted:   #8C8374;

        --navbar-h: 68px;
    }

    /* ═══════════════════════════════════════════════════════════════
       RESET & BASE
       ═══════════════════════════════════════════════════════════════ */

    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }

    body {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        background-color: var(--bg-page);
        color: var(--text-primary);
        font-size: 15px;
        line-height: 1.65;
        -webkit-font-smoothing: antialiased;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.018'/%3E%3C/svg%3E");
    }

    .font-display {
        font-family: 'Cormorant Garamond', Georgia, serif;
    }

    ::selection {
        background: var(--gold-pale);
        color: var(--text-primary);
    }

    /* ═══════════════════════════════════════════════════════════════
       BARRE DE NAVIGATION — GLASSMORPHISM
       ═══════════════════════════════════════════════════════════════ */

    .navbar {
        background: rgba(22, 20, 17, 0.82);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border-bottom: 1px solid rgba(184,146,42,0.20);
        position: sticky;
        top: 0;
        z-index: 1000;
        height: var(--navbar-h);
        transition: background var(--t-base) var(--ease-out),
                    box-shadow var(--t-base) var(--ease-out);
        box-shadow: 0 1px 0 rgba(255,255,255,0.03),
                    0 4px 24px rgba(0,0,0,0.18);
    }

    .navbar.scrolled {
        background: rgba(20, 18, 15, 0.97);
        box-shadow: 0 1px 0 rgba(184,146,42,0.12), 0 8px 32px rgba(0,0,0,0.30);
    }

    .navbar::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 180px;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold-light), transparent);
        opacity: 0.6;
    }

    .navbar-brand {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gold-light) !important;
        letter-spacing: 0.02em;
        text-decoration: none;
        transition: opacity var(--t-fast);
    }
    .navbar-brand:hover { opacity: 0.88; }

    .brand-accent {
        font-style: italic;
        font-weight: 600;
        color: rgba(255,255,255,0.90);
    }

    .nav-link {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: rgba(255,255,255,0.52) !important;
        font-size: 0.8125rem;
        font-weight: 500;
        letter-spacing: 0.025em;
        padding: 0.45rem 0.85rem !important;
        border-radius: var(--r-sm);
        transition: color var(--t-fast), background var(--t-fast);
    }
    .nav-link:hover {
        color: rgba(255,255,255,0.95) !important;
        background: rgba(255,255,255,0.055);
    }
    .nav-link.active {
        color: var(--gold-light) !important;
        background: rgba(184,146,42,0.10);
    }

    /* Dropdowns */
    .dropdown-menu {
        background: var(--bg-dark-2) !important;
        border: 1px solid rgba(184,146,42,0.18) !important;
        border-radius: var(--r-md) !important;
        padding: 6px !important;
        box-shadow: 0 16px 48px rgba(0,0,0,0.40), 0 4px 12px rgba(0,0,0,0.22) !important;
        animation: dropIn 0.18s var(--ease-out) both;
        min-width: 200px;
    }

    @keyframes dropIn {
        from { opacity:0; transform:translateY(-6px) scale(0.98); }
        to   { opacity:1; transform:translateY(0)   scale(1); }
    }

    .dropdown-item {
        color: rgba(255,255,255,0.70) !important;
        font-size: 0.8125rem;
        font-weight: 400;
        padding: 8px 12px !important;
        border-radius: var(--r-sm) !important;
        transition: background var(--t-fast), color var(--t-fast);
    }
    .dropdown-item:hover, .dropdown-item:focus {
        background: rgba(184,146,42,0.12) !important;
        color: var(--gold-light) !important;
    }
    .dropdown-divider {
        border-color: rgba(255,255,255,0.07) !important;
        margin: 4px 0 !important;
    }

    /* Hamburger */
    .navbar-toggler {
        border: none !important;
        background: rgba(255,255,255,0.05) !important;
        padding: 8px 10px !important;
        border-radius: var(--r-sm) !important;
        transition: background var(--t-fast);
    }
    .navbar-toggler:hover  { background: rgba(255,255,255,0.10) !important; }
    .navbar-toggler:focus  { box-shadow: 0 0 0 2px var(--gold-glow) !important; }

    .hamburger-line {
        display: block;
        width: 20px; height: 2px;
        background: var(--gold-light);
        border-radius: 2px;
        margin: 4px 0;
    }

    /* Mobile collapse */
    @media (max-width: 991px) {
        .navbar-collapse {
            background: rgba(24, 21, 17, 0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(184,146,42,0.15);
            border-radius: var(--r-md);
            padding: 12px;
            margin-top: 8px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.35);
        }
    }

    /* Badges XP */
    .badge-level {
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        padding: 3px 9px;
        border-radius: var(--r-pill);
    }
    .badge-level.beginner     { background:rgba(90,158,111,0.14);  color:var(--xp-beginner);     border:1px solid rgba(90,158,111,0.35); }
    .badge-level.intermediate { background:rgba(58,127,193,0.14);  color:var(--xp-intermediate); border:1px solid rgba(58,127,193,0.35); }
    .badge-level.advanced     { background:rgba(139,82,196,0.14);  color:var(--xp-advanced);     border:1px solid rgba(139,82,196,0.35); }
    .badge-level.expert       { background:rgba(184,146,42,0.14);  color:var(--gold-light);       border:1px solid rgba(184,146,42,0.40); }

    /* XP bar */
    .xp-bar-track {
        height: 2px;
        background: rgba(255,255,255,0.08);
        border-radius: 2px;
        overflow: hidden;
    }
    .xp-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
        border-radius: 2px;
        transition: width 0.7s var(--ease-out);
        box-shadow: 0 0 6px rgba(184,146,42,0.50);
    }

    /* Avatar */
    .nav-avatar {
        border-radius: 50%;
        border: 2px solid rgba(184,146,42,0.45);
        object-fit: cover;
        transition: border-color var(--t-fast), box-shadow var(--t-fast);
    }
    .nav-avatar:hover {
        border-color: var(--gold-light);
        box-shadow: 0 0 0 3px var(--gold-glow);
    }

    /* Bouton S'inscrire */
    .btn-nav-register {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: var(--bg-dark) !important;
        font-weight: 700;
        font-size: 0.8125rem;
        padding: 7px 16px;
        border-radius: var(--r-sm);
        border: none;
        text-decoration: none;
        letter-spacing: 0.01em;
        transition: opacity var(--t-fast), transform var(--t-fast), box-shadow var(--t-fast);
        box-shadow: 0 2px 8px rgba(184,146,42,0.28);
        display: inline-block;
    }
    .btn-nav-register:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: var(--shadow-gold);
        color: var(--bg-dark) !important;
    }

    /* ═══════════════════════════════════════════════════════════════
       MESSAGES FLASH
       ═══════════════════════════════════════════════════════════════ */

    .flash-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: var(--r-md);
        font-size: 0.875rem;
        border: 1px solid transparent;
        box-shadow: var(--shadow-sm);
        animation: flashIn 0.3s var(--ease-back) both;
    }
    @keyframes flashIn {
        from { opacity:0; transform:translateY(-10px) scale(0.98); }
        to   { opacity:1; transform:translateY(0)    scale(1); }
    }
    .flash-icon { flex-shrink:0; margin-top:1px; opacity:0.9; }
    .flash-close {
        margin-left:auto; flex-shrink:0;
        background:none; border:none; cursor:pointer;
        opacity:0.40; font-size:1.1rem; line-height:1;
        padding:0; color:inherit;
        transition:opacity var(--t-fast);
    }
    .flash-close:hover { opacity:0.85; }

    .flash-success { background:#F0FBF5; border-color:rgba(39,174,96,0.22); color:#155D30; }
    .flash-error   { background:#FEF2F2; border-color:rgba(220,38,38,0.18); color:#7B1D1D; }
    .flash-info    { background:#FDFAF2; border-color:rgba(184,146,42,0.25); color:#6B4A14; }
    .flash-warning { background:#FFFBEB; border-color:rgba(217,119,6,0.22); color:#78400A; }

    /* ═══════════════════════════════════════════════════════════════
       BOOTSTRAP OVERRIDES — Uplift global sans toucher au HTML
       ═══════════════════════════════════════════════════════════════ */

    /* Cards */
    .card {
        background: var(--bg-card) !important;
        border: 1px solid var(--border-card) !important;
        border-radius: var(--r-lg) !important;
        box-shadow: var(--shadow-sm) !important;
        transition: transform var(--t-base) var(--ease-out),
                    box-shadow var(--t-base) var(--ease-out) !important;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md) !important;
    }
    .card-header {
        background: var(--bg-card-warm) !important;
        border-bottom: 1px solid var(--border-soft) !important;
        padding: 1rem 1.4rem !important;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-secondary);
        letter-spacing: 0.02em;
    }
    .card-body { padding: 1.4rem !important; }
    .card-footer {
        background: var(--bg-card-warm) !important;
        border-top: 1px solid var(--border-soft) !important;
        padding: 0.875rem 1.4rem !important;
    }
    .shadow-sm { box-shadow: var(--shadow-sm) !important; }

    /* Boutons */
    .btn {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.01em;
        border-radius: var(--r-sm) !important;
        padding: 0.55rem 1.2rem !important;
        transition: all var(--t-fast) var(--ease-out) !important;
        border-width: 1.5px !important;
        position: relative;
        overflow: hidden;
    }
    .btn:hover  { transform: translateY(-1px); }
    .btn:active { transform: translateY(0) !important; }
    .btn:focus-visible { box-shadow: 0 0 0 3px var(--gold-glow) !important; outline: none; }

    .btn-primary {
        background: linear-gradient(135deg, #1A1814, #2C2922) !important;
        border-color: transparent !important;
        color: #fff !important;
        box-shadow: var(--shadow-xs) !important;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #2C2922, #3A3630) !important;
        box-shadow: var(--shadow-sm) !important;
        color: #fff !important;
    }
    .btn-secondary {
        background: transparent !important;
        border-color: var(--border-soft) !important;
        color: var(--text-secondary) !important;
    }
    .btn-secondary:hover {
        background: var(--bg-card-warm) !important;
        border-color: var(--gold) !important;
        color: var(--text-primary) !important;
    }
    .btn-success  { background:#1B6B3A !important; border-color:transparent !important; color:#fff !important; }
    .btn-danger   { background:#C0392B !important; border-color:transparent !important; color:#fff !important; }
    .btn-outline-secondary {
        border-color: var(--border-soft) !important;
        color: var(--text-secondary) !important;
        background: transparent !important;
    }
    .btn-outline-secondary:hover {
        background: var(--bg-card-warm) !important;
        border-color: var(--gold) !important;
        color: var(--text-primary) !important;
    }
    .btn-sm { font-size: 0.8125rem !important; padding: 0.375rem 0.875rem !important; }

    /* Tableaux */
    .table {
        font-size: 0.875rem;
        color: var(--text-primary);
        --bs-table-bg: transparent;
    }
    .table thead th {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-soft) !important;
        padding: 0.875rem 1rem !important;
        background: var(--bg-card-warm);
        white-space: nowrap;
    }
    .table tbody td {
        padding: 1rem 1rem !important;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-soft) !important;
        color: var(--text-primary);
    }
    .table tbody tr:last-child td { border-bottom: none !important; }
    .table-hover tbody tr { transition: background var(--t-fast); }
    .table-hover tbody tr:hover td { background: rgba(184,146,42,0.035) !important; }
    .table-responsive { border-radius: var(--r-lg); }

    /* Badges */
    .badge {
        font-size: 0.7rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.04em !important;
        padding: 4px 10px !important;
        border-radius: var(--r-pill) !important;
    }
    .bg-success   { background:rgba(27,107,58,0.12) !important;   color:#1B6B3A !important; }
    .bg-secondary { background:rgba(92,86,71,0.10) !important;    color:var(--text-secondary) !important; }
    .bg-danger    { background:rgba(192,57,43,0.10) !important;   color:#C0392B !important; }
    .bg-warning   { background:rgba(217,119,6,0.10) !important;   color:#B45309 !important; }
    .bg-info      { background:rgba(58,127,193,0.10) !important;  color:#2563EB !important; }
    .bg-primary   { background:rgba(26,24,20,0.10) !important;    color:var(--text-primary) !important; }

    /* Formulaires */
    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.875rem;
        padding: 0.625rem 0.875rem !important;
        border: 1.5px solid var(--border-soft) !important;
        border-radius: var(--r-sm) !important;
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
        transition: border-color var(--t-fast), box-shadow var(--t-fast) !important;
        box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--gold) !important;
        box-shadow: 0 0 0 3px var(--gold-glow) !important;
        background: #fff !important;
    }
    .form-control::placeholder { color: var(--text-muted) !important; }
    .form-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.375rem;
    }
    .form-text { font-size: 0.77rem; color: var(--text-muted); margin-top: 0.3rem; }
    .invalid-feedback { font-size: 0.8rem; color: #C0392B; }
    .form-control.is-invalid { border-color: rgba(192,57,43,0.50) !important; }
    .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(192,57,43,0.12) !important; }
    .input-group-text {
        background: var(--bg-card-warm) !important;
        border-color: var(--border-soft) !important;
        color: var(--text-muted) !important;
        font-size: 0.875rem;
    }

    /* Pagination */
    .pagination { gap: 4px; }
    .page-item .page-link {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-secondary);
        border: 1.5px solid var(--border-soft);
        border-radius: var(--r-sm) !important;
        padding: 6px 12px;
        background: var(--bg-card);
        transition: all var(--t-fast);
    }
    .page-item .page-link:hover {
        background: var(--bg-card-warm);
        border-color: var(--gold);
        color: var(--text-primary);
        box-shadow: none;
    }
    .page-item.active .page-link {
        background: var(--bg-dark) !important;
        border-color: var(--bg-dark) !important;
        color: #fff !important;
        box-shadow: var(--shadow-xs);
    }
    .page-item.disabled .page-link {
        background: transparent;
        border-color: var(--border-soft);
        color: var(--border-soft);
    }

    /* Alertes BS */
    .alert {
        border-radius: var(--r-md) !important;
        border-width: 1px !important;
        font-size: 0.875rem;
        padding: 1rem 1.2rem !important;
    }

    /* Accordéon */
    .accordion-button {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
        background: var(--bg-card-warm) !important;
        border-radius: var(--r-sm) !important;
        box-shadow: none !important;
    }
    .accordion-button:not(.collapsed) { color: var(--gold); }

    /* List groups */
    .list-group-item {
        border-color: var(--border-soft) !important;
        color: var(--text-primary);
        font-size: 0.875rem;
        padding: 0.875rem 1.1rem !important;
    }
    .list-group-item:first-child { border-top-left-radius:    var(--r-md) !important; border-top-right-radius:    var(--r-md) !important; }
    .list-group-item:last-child  { border-bottom-left-radius: var(--r-md) !important; border-bottom-right-radius: var(--r-md) !important; }

    /* Modals */
    .modal-content {
        border-radius: var(--r-xl) !important;
        border: 1px solid var(--border-card) !important;
        box-shadow: 0 24px 80px rgba(0,0,0,0.18) !important;
        overflow: hidden;
    }
    .modal-header { border-bottom: 1px solid var(--border-soft) !important; padding: 1.25rem 1.5rem !important; }
    .modal-footer { border-top: 1px solid var(--border-soft) !important; padding: 1rem 1.5rem !important; }
    .modal-title  { font-weight: 700; font-size: 1rem; }
    .btn-close { opacity: 0.4; }
    .btn-close:hover { opacity: 0.8; }

    /* ═══════════════════════════════════════════════════════════════
       LAYOUT
       ═══════════════════════════════════════════════════════════════ */

    .main-content { min-height: calc(100vh - var(--navbar-h) - 72px); }

    /* ═══════════════════════════════════════════════════════════════
       FOOTER
       ═══════════════════════════════════════════════════════════════ */

    .site-footer {
        background: var(--bg-dark);
        color: var(--text-dim);
        font-size: 0.8125rem;
        padding: 1.75rem 0;
        position: relative;
    }
    .site-footer::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(184,146,42,0.35) 30%, rgba(184,146,42,0.55) 50%, rgba(184,146,42,0.35) 70%, transparent 100%);
    }
    .footer-brand {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: rgba(184,146,42,0.70);
    }
    .footer-link {
        color: var(--text-dim);
        text-decoration: none;
        transition: color var(--t-fast);
        font-size: 0.8125rem;
    }
    .footer-link:hover { color: rgba(255,255,255,0.75); }

    /* ═══════════════════════════════════════════════════════════════
       UTILITAIRES
       ═══════════════════════════════════════════════════════════════ */

    .text-gold       { color: var(--gold) !important; }
    .text-gold-light { color: var(--gold-light) !important; }
    .bg-leon-dark    { background: var(--bg-dark) !important; }

    .divider-gold {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border-warm), transparent);
        border: none;
    }

    .fade-in-up {
        animation: fadeInUp 0.45s var(--ease-out) both;
    }
    @keyframes fadeInUp {
        from { opacity:0; transform:translateY(14px); }
        to   { opacity:1; transform:translateY(0); }
    }

    @keyframes ripple {
        to { transform: scale(2.5); opacity: 0; }
    }

    /* Scrollbar */
    ::-webkit-scrollbar       { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb {
        background: rgba(184,146,42,0.28);
        border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover { background: rgba(184,146,42,0.50); }

    /* Focus accessible */
    :focus-visible {
        outline: 2px solid var(--gold);
        outline-offset: 3px;
    }
    </style>

    @stack('styles')
</head>

<body>

{{-- ═══════════════════════════════════════════════════════════════════
     NAVBAR
     ═══════════════════════════════════════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg" id="mainNavbar">
    <div class="container">

        <a class="navbar-brand me-4" href="{{ route('public.home') }}">
            Chez <span class="brand-accent">Léon</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-label="Ouvrir le menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">

            <ul class="navbar-nav me-auto gap-1">

                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('public.home')) active @endif"
                       href="{{ route('public.home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('public.news.*')) active @endif"
                       href="{{ route('public.news.index') }}">Actualités</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('public.tour.*')) active @endif"
                       href="{{ route('public.tour.index') }}">Visite guidée</a>
                </li>

                @auth
                    @if(auth()->user()->is_approved)

                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('simple.devices.*')) active @endif"
                               href="{{ route('simple.devices.index') }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:4px"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                                Objets connectés
                            </a>
                        </li>

                        @if(auth()->user()->hasAccessToManagement())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle @if(request()->routeIs('complex.*')) active @endif"
                                   href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:4px"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 0-14.14 0M4.93 19.07a10 10 0 0 0 14.14 0"/></svg>
                                    Gestion
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('complex.devices.index') }}">Mes appareils</a></li>
                                    <li><a class="dropdown-item" href="{{ route('complex.devices.create') }}">Ajouter un appareil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('complex.reports.index') }}">Rapports</a></li>
                                    <li><a class="dropdown-item" href="{{ route('complex.zones.index') }}">Zones</a></li>
                                </ul>
                            </li>
                        @endif

                        @if(auth()->user()->hasAccessToAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle @if(request()->routeIs('admin.*')) active @endif"
                                   href="#" data-bs-toggle="dropdown" aria-expanded="false"
                                   style="color:var(--gold-light) !important;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:4px"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    Administration
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.users.pending') }}">
                                            En attente
                                            @php $pending = \App\Models\User::pendingApproval()->count(); @endphp
                                            @if($pending > 0)
                                                <span style="background:var(--gold);color:var(--bg-dark);font-size:0.64rem;font-weight:700;padding:2px 7px;border-radius:99px;line-height:1.4;">{{ $pending }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <a class="dropdown-item" href="{{ route('admin.devices.deletion-requests') }}">
                                        Demandes de suppression
                                    </a>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Catégories</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.zones.index') }}">Zones</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.integrity.index') }}">Intégrité des données</a></li>
                                </ul>
                            </li>
                        @endif

                    @endif
                @endauth

            </ul>

            {{-- Partie droite --}}
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.login') }}">Se connecter</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('public.register') }}" class="btn-nav-register">S'inscrire</a>
                    </li>
                @endguest

                @auth
                    {{-- Widget XP desktop --}}
                    <li class="nav-item d-none d-lg-flex align-items-center" style="min-width:130px;">
                        @php
                            $user       = auth()->user();
                            $thresholds = \App\Models\User::XP_THRESHOLDS;
                            $levels     = array_keys($thresholds);
                            $curIdx     = array_search($user->level, $levels);
                            $floorXp    = $thresholds[$user->level];
                            $nextXp     = $thresholds[$levels[min($curIdx + 1, count($levels)-1)]];
                            $progress   = $curIdx >= count($levels)-1 ? 100
                                : min(100, round(($user->experience_points - $floorXp) / max(1, $nextXp - $floorXp) * 100));
                        @endphp
                        <div style="width:100%">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge-level {{ $user->level }}">{{ ucfirst($user->level) }}</span>
                                <span style="color:var(--text-dim);font-size:0.675rem;font-weight:500;">{{ $user->experience_points }}&thinsp;XP</span>
                            </div>
                            <div class="xp-bar-track">
                                <div class="xp-bar-fill" style="width:{{ $progress }}%"></div>
                            </div>
                        </div>
                    </li>

                    {{-- Avatar --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-2 p-1"
                           href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->pseudo }}"
                                 width="34" height="34"
                                 class="nav-avatar">
                            <span class="d-none d-lg-inline" style="font-size:0.8125rem;color:rgba(255,255,255,0.80);font-weight:500;">
                                {{ auth()->user()->pseudo ?? auth()->user()->name }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div style="padding:10px 14px 8px;border-bottom:1px solid rgba(255,255,255,0.06);margin-bottom:4px;">
                                    <div style="font-size:0.71rem;color:var(--text-dim);margin-bottom:2px;">Connecté en tant que</div>
                                    <div style="font-size:0.8125rem;color:rgba(255,255,255,0.90);font-weight:600;">{{ auth()->user()->name }}</div>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('simple.profile.show') }}">Mon profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('simple.dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('simple.xp.index') }}">Historique XP</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('public.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color:#F87171;">
                                        Se déconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>


{{-- ═══════════════════════════════════════════════════════════════════
     FLASH MESSAGES
     ═══════════════════════════════════════════════════════════════════ --}}
@if(session()->hasAny(['success','error','info','warning']) || $errors->any())
<div class="container" style="padding-top:1rem;" id="flash-container">

    @foreach(['success','error','info','warning'] as $type)
        @if(session($type))
        <div class="flash-alert flash-{{ $type }} mb-2" role="alert">
            @if($type === 'success')
                <svg class="flash-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            @elseif($type === 'error')
                <svg class="flash-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            @elseif($type === 'info')
                <svg class="flash-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            @else
                <svg class="flash-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>
            @endif
            <span>{{ session($type) }}</span>
            <button class="flash-close" onclick="this.closest('.flash-alert').remove()" aria-label="Fermer">×</button>
        </div>
        @endif
    @endforeach

    @if($errors->any())
    <div class="flash-alert flash-error mb-2" role="alert">
        <svg class="flash-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        <div>
            <strong style="font-weight:700;">Erreurs de validation :</strong>
            <ul class="mb-0 mt-1 ps-3" style="font-size:0.85rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="flash-close" onclick="this.closest('.flash-alert').remove()" aria-label="Fermer">×</button>
    </div>
    @endif

</div>
@endif


{{-- ═══════════════════════════════════════════════════════════════════
     CONTENU
     ═══════════════════════════════════════════════════════════════════ --}}
<main class="main-content">
    @yield('content')
</main>


{{-- ═══════════════════════════════════════════════════════════════════
     FOOTER
     ═══════════════════════════════════════════════════════════════════ --}}
<footer class="site-footer">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">

            <div class="d-flex align-items-center gap-3">
                <span class="footer-brand">Chez Léon</span>
                <span style="color:rgba(184,146,42,0.22);font-size:1rem;line-height:1;">|</span>
                <span style="font-size:0.775rem;">Plateforme IoT Restaurant</span>
            </div>

            <span style="font-size:0.775rem;">© {{ date('Y') }} — Tous droits réservés</span>

            <div class="d-flex gap-4">
                <a href="{{ route('public.home') }}" class="footer-link">Accueil</a>
                <a href="{{ route('public.news.index') }}" class="footer-link">Actualités</a>
                <a href="{{ route('public.tour.index') }}" class="footer-link">Visite guidée</a>
            </div>

        </div>
    </div>
</footer>


{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Navbar : effet scroll
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        const tick = () => navbar.classList.toggle('scrolled', window.scrollY > 18);
        window.addEventListener('scroll', tick, { passive: true });
        tick();
    }

    // 2. Auto-dismiss des flash (5 secondes)
    setTimeout(function () {
        document.querySelectorAll('.flash-alert').forEach(function (el) {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);

    // 3. Entrée des cartes au scroll (Intersection Observer)
    if ('IntersectionObserver' in window) {
        const els = document.querySelectorAll('.card, [data-animate]');
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = (i * 0.04) + 's';
                    entry.target.classList.add('fade-in-up');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.07, rootMargin: '0px 0px -24px 0px' });
        els.forEach(el => io.observe(el));
    }

    // 4. Ripple sur les boutons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const r    = btn.getBoundingClientRect();
            const size = Math.max(r.width, r.height);
            const s    = document.createElement('span');
            s.style.cssText = [
                'position:absolute',
                `left:${e.clientX - r.left - size / 2}px`,
                `top:${e.clientY - r.top - size / 2}px`,
                `width:${size}px`, `height:${size}px`,
                'border-radius:50%',
                'background:rgba(255,255,255,0.18)',
                'transform:scale(0)',
                'animation:ripple .5s ease-out forwards',
                'pointer-events:none'
            ].join(';');
            if (getComputedStyle(btn).position === 'static') btn.style.position = 'relative';
            btn.appendChild(s);
            setTimeout(() => s.remove(), 500);
        });
    });

});
</script>

@stack('scripts')
</body>
</html>