@extends('layouts.app')

@section('title', 'Créateur de Menu Digital Pro — Studio Restaurant')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}" class="text-decoration-none text-muted">Menus Digitaux</a></li>
    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Nouveau Studio Pro</li>
@endsection

@push('styles')
<style>
    /* Studio Executive Theme */
    .menu-studio-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-top: 3px solid #5736e9;
        box-shadow: 0 8px 24px -6px rgba(15, 23, 42, 0.06);
    }
    .studio-jump-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .studio-jump-pill:hover {
        background: #5736e9;
        color: #ffffff;
        border-color: #5736e9;
    }
    .studio-section-card .form-control,
    .studio-section-card .form-select {
        border-radius: 10px;
        border-color: #cbd5e1;
        font-size: 0.875rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .studio-section-card .form-control:focus,
    .studio-section-card .form-select:focus {
        border-color: #5736e9;
        box-shadow: 0 0 0 3px rgba(87, 54, 233, 0.12);
    }
    .studio-theme-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .studio-theme-pill:hover {
        border-color: #5736e9;
        background: #f5f3ff;
        transform: translateY(-1px);
    }
    .theme-dot-pair {
        display: inline-flex;
        align-items: center;
    }
    .theme-dot-pair span {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        border: 1px solid rgba(15, 23, 42, 0.15);
    }
    .theme-dot-pair span:last-child {
        margin-left: -4px;
    }
    .nav-wireframe {
        height: 46px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.6rem;
    }
    .status-dot-radar {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        display: inline-block;
        position: relative;
    }
    .status-dot-radar::after {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: rgba(16, 185, 129, 0.4);
        animation: radar-pulse 1.8s infinite ease-out;
    }
    @keyframes radar-pulse {
        0% { transform: scale(0.6); opacity: 1; }
        100% { transform: scale(2.2); opacity: 0; }
    }

    /* Quick Palette Swatches */
    .color-swatch-btn {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: transform 0.15s ease, border-color 0.15s ease;
        padding: 0;
    }
    .color-swatch-btn:hover {
        transform: scale(1.15);
    }
    .color-swatch-btn.active {
        border-color: #0f172a;
        transform: scale(1.15);
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }

    /* Navigation Style Cards */
    .nav-style-card {
        transition: all 0.2s ease;
        background: #ffffff;
        cursor: pointer;
        border: 2px solid #e2e8f0;
    }
    .nav-style-card:hover {
        border-color: #5736e9;
        background: #f8fafc;
    }
    .nav-style-card.active {
        border-color: #5736e9 !important;
        background: #f5f3ff !important;
        box-shadow: 0 4px 12px rgba(87, 54, 233, 0.12);
    }

    /* Simulator Pages and Grid Nav styles */
    .sim-tabs-bar {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 3px;
        display: flex;
        gap: 3px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .sim-tabs-bar::-webkit-scrollbar { display: none; }
    .sim-tab-btn {
        flex: 1;
        min-width: 85px;
        padding: 0.38rem 0.6rem;
        border: none;
        background: transparent;
        font-size: 0.68rem;
        font-weight: 700;
        color: #64748b;
        border-radius: 9px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .sim-tab-btn .page-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 17px;
        height: 17px;
        padding: 0 4px;
        border-radius: 999px;
        background: rgba(100, 116, 139, 0.15);
        color: #475569;
        font-size: 0.58rem;
        font-weight: 800;
        transition: all 0.2s ease;
    }
    .sim-tab-btn.active {
        background: #ffffff;
        color: var(--sim-accent);
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    }
    .sim-tab-btn.active .page-pill {
        background: var(--sim-accent);
        color: #ffffff;
    }
    .sim-page-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    .sim-page-dot {
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: #cbd5e1;
        transition: all 0.25s ease;
        cursor: pointer;
    }
    .sim-page-dot.active {
        width: 16px;
        background: var(--sim-accent);
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }
    .sim-page-hero {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.95));
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 12px;
        padding: 0.75rem 0.85rem;
        margin-bottom: 0.75rem;
        animation: simPageFadeIn 0.22s ease-out;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    @keyframes simPageFadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .sim-grid-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.5rem 0.3rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .sim-grid-card:hover, .sim-grid-card.active {
        border-color: var(--sim-accent);
        background: #f5f3ff;
    }
    .sim-floating-drawer-btn {
        position: absolute;
        bottom: 60px;
        right: 12px;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 999px;
        padding: 0.42rem 0.8rem;
        font-size: 0.68rem;
        font-weight: 700;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.4);
        display: flex;
        align-items: center;
        gap: 4px;
        z-index: 60;
        cursor: pointer;
    }
    .sim-drawer-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        z-index: 1080;
        display: none;
        flex-direction: column;
        justify-content: flex-end;
        backdrop-filter: blur(2px);
    }
    .sim-drawer-content {
        background: white;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        padding: 1rem 0.85rem 1.25rem 0.85rem;
        max-height: 70%;
        overflow-y: auto;
    }

    /* Studio Section Cards & Uniform Header Icons */
    .studio-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 2px 10px -2px rgba(15, 23, 42, 0.04);
    }
    .studio-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .currency-chip {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-color: #e2e8f0;
        color: #475569;
    }
    .currency-chip.active {
        background: #5736e9;
        border-color: #5736e9;
        color: #ffffff;
    }
    .sim-device-btn {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        color: #64748b;
    }
    .sim-device-btn.active {
        background: #0f172a !important;
        color: #ffffff !important;
    }

    /* Media Upload Dropzones */
    .media-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.25rem;
        min-height: 156px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    .media-dropzone:hover {
        border-color: #5736e9;
        background: #f5f3ff;
    }
    .media-preview-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #0f172a;
        width: 100%;
    }
    .media-preview-cover {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .media-preview-logo {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
    }

    /* Category & Item Builder Cards */
    .studio-category-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .studio-category-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px -4px rgba(15, 23, 42, 0.06);
    }
    .studio-item-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.9rem 1rem;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .studio-item-row:hover {
        background: #ffffff;
        border-color: #cbd5e1;
    }
    .studio-item-row.is-out-of-stock {
        opacity: 0.65;
        background: #fef2f2;
        border-color: #fecaca;
    }

    /* Dish Image Upload Button & Thumbnail */
    .dish-img-thumb {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        object-fit: cover;
        background: #e2e8f0;
        border: 1px solid #cbd5e1;
        flex-shrink: 0;
        cursor: pointer;
    }
    .dish-upload-trigger {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        border: 1.5px dashed #cbd5e1;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.68rem;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .dish-upload-trigger:hover {
        border-color: #5736e9;
        color: #5736e9;
        background: #f5f3ff;
    }

    /* Proposed Category & Badge Chips */
    .proposed-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .proposed-chip:hover {
        border-color: #5736e9;
        color: #5736e9;
        background: #f5f3ff;
        transform: translateY(-1px);
    }

    /* Sticky Simulator Dock */
    .sticky-preview-wrapper {
        top: 20px;
        z-index: 100;
    }
    .studio-canvas-stage {
        background: radial-gradient(circle at top right, #eef2ff 0%, #f1f5f9 55%, #e2e8f0 100%);
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 1.5rem 1.25rem;
        box-shadow: inset 0 2px 8px rgba(15, 23, 42, 0.03), 0 12px 32px -12px rgba(15, 23, 42, 0.08);
    }

    /* Realistic Smartphone Device Mockup Frame */
    .phone-frame-mockup {
        width: 325px;
        height: 650px;
        background: #0f172a;
        border-radius: 44px;
        border: 9px solid #1e293b;
        box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
        position: relative;
        overflow: hidden;
        transform-origin: top center;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .phone-frame-mockup.device-pixel {
        border-radius: 28px;
        border-width: 8px;
    }
    .phone-frame-mockup.device-frameless {
        border-radius: 16px;
        border-width: 2px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .phone-dynamic-island {
        position: absolute;
        top: 8px;
        left: 50%;
        transform: translateX(-50%);
        width: 86px;
        height: 18px;
        background: #000000;
        border-radius: 20px;
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 8px;
    }
    .phone-dynamic-island .island-camera {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1e293b;
    }
    .phone-dynamic-island .island-speaker {
        width: 34px;
        height: 3px;
        border-radius: 2px;
        background: #1e293b;
    }
    .phone-metal-glare {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border-radius: 35px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        pointer-events: none;
        z-index: 1060;
    }

    /* Screen Content & Live Simulation */
    .phone-screen-content {
        --sim-accent: #5736e9;
        width: 100%;
        height: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff;
        color: #334155;
        display: flex;
        flex-direction: column;
        scrollbar-width: none;
        position: relative;
    }
    .phone-screen-content::-webkit-scrollbar { display: none; }

    /* Phone Internal Simulator Styles */
    .sim-header {
        background: linear-gradient(135deg, var(--sim-accent), #0f172a);
        background-size: cover;
        background-position: center;
        color: white;
        padding: 2.2rem 1rem 1.2rem 1rem;
        position: relative;
        transition: background 0.3s ease;
    }
    .sim-header.has-cover::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.45) 0%, rgba(15, 23, 42, 0.88) 100%);
        z-index: 1;
    }
    .sim-header-content {
        position: relative;
        z-index: 2;
    }
    .sim-logo {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: white;
        color: var(--sim-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        object-fit: cover;
        flex-shrink: 0;
        overflow: hidden;
    }
    .sim-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .sim-cat-pill {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #64748b;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .sim-cat-pill.active {
        background: var(--sim-accent);
        color: white;
    }
    .sim-dish-card {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 0.75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .sim-dish-img {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
    }
    .sim-badge-pill {
        font-size: 0.62rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .sim-wa-floating-btn {
        position: sticky;
        bottom: 12px;
        margin: 0 12px;
        background: #25D366 !important;
        color: #ffffff !important;
        border-radius: 999px;
        padding: 0.6rem 1rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-align: center;
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        z-index: 50;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Studio Header & Action Bar -->
    <div class="card border-0 menu-studio-hero rounded-4 p-3 p-md-4 mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px;" title="Retour">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="status-dot-radar"></span>
                        <span class="badge bg-primary-subtle text-primary text-uppercase px-2.5 py-1 fw-bold rounded-pill" style="font-size: 0.68rem; letter-spacing: 0.05em;">Studio Pro &bull; Écran Réel</span>
                        <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill" style="font-size: 0.68rem;">Synchronisation Instantanée</span>
                    </div>
                    <h1 class="h3 fw-extrabold text-dark mb-0" style="letter-spacing: -0.02em;">Créateur de Menu Digital</h1>
                    <p class="text-muted small mb-0">Téléversez vos photos, personnalisez les couleurs et testez le rendu mobile en direct.</p>
                </div>
            </div>

            <!-- Top Actions -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm rounded-pill dropdown-toggle fw-semibold px-3 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-magic me-1 text-primary"></i>Modèles Proposés
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2" style="min-width: 260px;">
                        <li><h6 class="dropdown-header text-uppercase fw-bold text-muted small">Charger une suggestion</h6></li>
                        <li><a class="dropdown-item rounded-2 py-2" href="javascript:void(0)" onclick="loadPreset('fast_food')">🍔 Fast Food &amp; Smash Burgers</a></li>
                        <li><a class="dropdown-item rounded-2 py-2" href="javascript:void(0)" onclick="loadPreset('restaurant')">🍷 Restaurant &amp; Brasserie</a></li>
                        <li><a class="dropdown-item rounded-2 py-2" href="javascript:void(0)" onclick="loadPreset('cafe')">☕ Café, Brunch &amp; Pâtisserie</a></li>
                        <li><a class="dropdown-item rounded-2 py-2" href="javascript:void(0)" onclick="loadPreset('pizzeria')">🍕 Pizzeria &amp; Trattoria</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-2 py-1.5 text-danger small" href="javascript:void(0)" onclick="clearMenu()"><i class="bi bi-trash me-1"></i>Partir d'une carte vide</a></li>
                    </ul>
                </div>

                <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #5736e9 0%, #4324c7 100%); border: none;" onclick="document.getElementById('menuStudioForm').submit();">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Enregistrer &amp; Publier</span>
                </button>
            </div>
        </div>

        <!-- Quick Section Jump Pills -->
        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top" style="border-color: #f1f5f9 !important;">
            <span class="text-muted small fw-semibold me-1" style="font-size: 0.72rem;">Accès rapide :</span>
            <a href="#sec-identity" class="studio-jump-pill"><i class="bi bi-shop"></i>1. Identité &amp; Couleurs</a>
            <a href="#sec-media" class="studio-jump-pill"><i class="bi bi-image"></i>2. Médias</a>
            <a href="#sec-nav" class="studio-jump-pill"><i class="bi bi-compass"></i>3. Navigation</a>
            <a href="#sec-whatsapp" class="studio-jump-pill"><i class="bi bi-whatsapp"></i>4. WhatsApp &amp; Infos</a>
            <a href="#sec-items" class="studio-jump-pill"><i class="bi bi-card-list"></i>5. Catégories &amp; Plats</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-xs" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Veuillez vérifier les erreurs :</div>
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Studio Workspace (2 Columns) -->
    <div class="row g-4 align-items-start">
        <!-- LEFT COLUMN: Form & Menu Builder -->
        <div class="col-12 col-xl-7">
            <form id="menuStudioForm" method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="categories_json" id="categoriesJsonInput" value="[]">
                <input type="hidden" name="cover_image_url" id="coverImageUrlInput" value="">
                <input type="hidden" name="logo_url" id="logoUrlInput" value="">

                <!-- Section 1: Identité & Marque (Proposé ou Manuel) -->
                <div class="studio-section-card p-4 mb-4" id="sec-identity">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="studio-section-icon bg-primary-subtle text-primary">
                                <i class="bi bi-shop fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">1. Identité de l'Établissement</h5>
                                <p class="text-muted small mb-0">Personnalisez à partir des options suggérées ou librement à la main</p>
                            </div>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1">Obligatoire</span>
                    </div>

                    <div class="row g-3">
                        <!-- Client Owner Selection -->
                        <div class="col-12">
                            <label for="client_id" class="form-label fw-semibold small text-dark d-flex align-items-center gap-2">
                                <span>Entreprise / Client Propriétaire</span>
                                <span class="badge bg-info-subtle text-info">Espace Client Dédié</span>
                            </label>
                            <select name="client_id" id="client_id" class="form-select">
                                <option value="">-- Sans client assigné (Administrateur interne) --</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" {{ (old('client_id', $selectedClientId ?? '') == $c->id) ? 'selected' : '' }}>
                                        {{ $c->company_name }} &bull; {{ $c->contact_name }} ({{ $c->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand Name & Menu Title -->
                        <div class="col-12 col-md-6">
                            <label for="brand_name" class="form-label fw-semibold small text-dark">Nom de l'Établissement <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                                <input type="text" name="brand_name" id="brand_name" class="form-control" value="{{ old('brand_name', 'Smash & Bun') }}" placeholder="Ex: Smash & Bun, Le Bistrot Gourmand" required oninput="syncSimulatorHeader()">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="title" class="form-label fw-semibold small text-dark">Titre de la Carte / Menu <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', 'Carte Street Food & Burgers') }}" placeholder="Ex: Carte des Plats & Boissons" required oninput="syncSimulatorHeader()">
                        </div>

                        <!-- Brand Type (Proposed or Custom Manual) -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label for="brand_type" class="form-label fw-semibold small text-dark mb-0">Type d'Établissement <span class="text-danger">*</span></label>
                                <a href="javascript:void(0)" class="small text-primary text-decoration-none fw-medium" onclick="toggleCustomBrandType()">
                                    <i class="bi bi-pencil-square me-1"></i>Saisie libre
                                </a>
                            </div>

                            <div id="brandTypeSelectWrap">
                                <select name="brand_type_select" id="brand_type_select" class="form-select" onchange="onBrandTypeSelectChange(this.value)">
                                    <option value="fast_food">🍔 Fast Food / Smash Burgers / Street Food</option>
                                    <option value="restaurant">🍽️ Restaurant Traditionnel &amp; Brasserie</option>
                                    <option value="cafe">☕ Café / Brunch / Salon de Thé</option>
                                    <option value="pizzeria">🍕 Pizzeria &amp; Trattoria</option>
                                    <option value="bakery">🥐 Boulangerie &amp; Pâtisserie Fine</option>
                                    <option value="lounge">🍸 Lounge Bar &amp; Cocktails</option>
                                    <option value="sushi">🍣 Sushi &amp; Cuisine Asiatique</option>
                                    <option value="brand">🏷️ Marque &amp; Catalogue Produits</option>
                                    <option value="other">✨ Autre Concept (Personnalisé)</option>
                                </select>
                            </div>

                            <div id="brandTypeCustomWrap" class="d-none mt-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted">Personnalisé</span>
                                    <input type="text" id="brand_type_custom" class="form-control" placeholder="Ex: Food Truck Gourmand, Glacier Artisanal..." oninput="syncSimulatorHeader()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="toggleCustomBrandType()">Retour liste</button>
                                </div>
                            </div>

                            <input type="hidden" name="brand_type" id="brand_type" value="fast_food">
                        </div>

                        <!-- Currency (Aligned baseline with Brand Type, chips underneath) -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label for="currency" class="form-label fw-semibold small text-dark mb-0">Devise <span class="text-danger">*</span></label>
                                <span class="text-muted" style="font-size: 0.7rem;">Choix rapide ou manuel</span>
                            </div>
                            <input type="text" name="currency" id="currency" class="form-control font-mono fw-bold" value="{{ old('currency', 'DH') }}" placeholder="Ex: DH, €, $..." required oninput="renderCategoryBuilder()">
                            <div class="d-flex align-items-center gap-1 flex-wrap mt-1.5">
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip active" onclick="setCurrency('DH')">DH</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip" onclick="setCurrency('MAD')">MAD</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip" onclick="setCurrency('€')">€</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip" onclick="setCurrency('$')">$</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip" onclick="setCurrency('£')">£</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip" onclick="setCurrency('AED')">AED</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill currency-chip" onclick="setCurrency('DT')">DT</button>
                            </div>
                        </div>

                        <!-- Slogan / Subtitle -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold small text-dark">Slogan ou Description d'Accueil</label>
                            <input type="text" name="description" id="description" class="form-control" value="{{ old('description', 'Burgers pur bœuf smashé minute, frites fraîches maison & milkshakes.') }}" placeholder="Ex: Cuisine savoureuse, terrasse ensoleillée & produits frais." oninput="syncSimulatorHeader()">
                        </div>

                        <!-- Palette & Couleurs des Plats & du Menu -->
                        <div class="col-12 mt-2 pt-3 border-top">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-bold small text-dark mb-0">
                                    <i class="bi bi-palette text-primary me-1"></i>Gestion des Couleurs des Plats &amp; de la Carte
                                </label>
                                <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Fond, Texte &amp; Accents</span>
                            </div>

                            <div class="row g-2.5">
                                <!-- 1. Fond des Plats / Cartes -->
                                <div class="col-6 col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                        <i class="bi bi-square-fill me-1" style="color: #64748b;"></i>Fond des Plats
                                    </label>
                                    <div class="d-flex align-items-center gap-1.5 p-1.5 border rounded-3 bg-light">
                                        <input type="color" name="item_bg_color" id="item_bg_color" class="form-control form-control-color border-0 p-0" value="{{ old('item_bg_color', '#ffffff') }}" style="width: 28px; height: 28px; cursor: pointer;" oninput="setItemBgColor(this.value)">
                                        <span class="small font-mono fw-semibold" id="itemBgColorCode" style="font-size: 0.75rem;">{{ old('item_bg_color', '#ffffff') }}</span>
                                    </div>
                                </div>

                                <!-- 2. Texte des Plats -->
                                <div class="col-6 col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                        <i class="bi bi-fonts me-1" style="color: #64748b;"></i>Texte des Plats
                                    </label>
                                    <div class="d-flex align-items-center gap-1.5 p-1.5 border rounded-3 bg-light">
                                        <input type="color" name="item_text_color" id="item_text_color" class="form-control form-control-color border-0 p-0" value="{{ old('item_text_color', '#1e293b') }}" style="width: 28px; height: 28px; cursor: pointer;" oninput="setItemTextColor(this.value)">
                                        <span class="small font-mono fw-semibold" id="itemTextColorCode" style="font-size: 0.75rem;">{{ old('item_text_color', '#1e293b') }}</span>
                                    </div>
                                </div>

                                <!-- 3. Couleur Thématique / Accents -->
                                <div class="col-6 col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                        <i class="bi bi-droplet-fill text-primary me-1"></i>Accent &amp; Prix
                                    </label>
                                    <div class="d-flex align-items-center gap-1.5 p-1.5 border rounded-3 bg-light">
                                        <input type="color" name="theme_color" id="theme_color" class="form-control form-control-color border-0 p-0" value="{{ old('theme_color', '#ea580c') }}" style="width: 28px; height: 28px; cursor: pointer;" oninput="setThemeColor(this.value)">
                                        <span class="small font-mono fw-semibold" id="themeColorCode" style="font-size: 0.75rem;">{{ old('theme_color', '#ea580c') }}</span>
                                    </div>
                                </div>

                                <!-- 4. Fond de la Page Menu -->
                                <div class="col-6 col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                        <i class="bi bi-phone me-1" style="color: #64748b;"></i>Fond de Page
                                    </label>
                                    <div class="d-flex align-items-center gap-1.5 p-1.5 border rounded-3 bg-light">
                                        <input type="color" name="bg_color" id="bg_color" class="form-control form-control-color border-0 p-0" value="{{ old('bg_color', '#f8fafc') }}" style="width: 28px; height: 28px; cursor: pointer;" oninput="setMenuBgColor(this.value)">
                                        <span class="small font-mono fw-semibold" id="bgColorCode" style="font-size: 0.75rem;">{{ old('bg_color', '#f8fafc') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Themes en 1 clic -->
                            <div class="d-flex flex-wrap align-items-center gap-1.5 mt-3">
                                <span class="small text-muted fw-semibold me-1" style="font-size: 0.72rem;">Palettes prêtes :</span>
                                <button type="button" class="studio-theme-pill" onclick="applyColorTheme('#ea580c', '#ffffff', '#1e293b', '#f8fafc')">
                                    <span class="theme-dot-pair"><span style="background:#f8fafc"></span><span style="background:#ea580c"></span></span>
                                    Clair Fast-Food
                                </button>
                                <button type="button" class="studio-theme-pill" onclick="applyColorTheme('#f59e0b', '#1e293b', '#ffffff', '#0f172a')">
                                    <span class="theme-dot-pair"><span style="background:#0f172a"></span><span style="background:#f59e0b"></span></span>
                                    Sombre Lounge
                                </button>
                                <button type="button" class="studio-theme-pill" onclick="applyColorTheme('#5736e9', '#ffffff', '#0f172a', '#f5f3ff')">
                                    <span class="theme-dot-pair"><span style="background:#f5f3ff"></span><span style="background:#5736e9"></span></span>
                                    Violet Studio
                                </button>
                                <button type="button" class="studio-theme-pill" onclick="applyColorTheme('#059669', '#ffffff', '#14532d', '#f0fdf4')">
                                    <span class="theme-dot-pair"><span style="background:#f0fdf4"></span><span style="background:#059669"></span></span>
                                    Vert Fraîcheur
                                </button>
                                <button type="button" class="studio-theme-pill" onclick="applyColorTheme('#dc2626', '#18181b', '#f4f4f5', '#09090b')">
                                    <span class="theme-dot-pair"><span style="background:#09090b"></span><span style="background:#dc2626"></span></span>
                                    Dark Bistro
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Médias, Photo de Couverture & Logo (Upload & URL) -->
                <div class="studio-section-card p-4 mb-4" id="sec-media">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="studio-section-icon bg-info-subtle text-info">
                                <i class="bi bi-image fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">2. Médias : Couverture &amp; Logo</h5>
                                <p class="text-muted small mb-0">Téléversez vos images depuis votre ordinateur ou collez des URLs</p>
                            </div>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1">Visuels</span>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Cover Image Uploader -->
                        <div class="col-12 col-md-7 d-flex flex-column">
                            <label class="form-label fw-semibold small text-dark d-flex align-items-center justify-content-between mb-2">
                                <span><i class="bi bi-card-image text-primary me-1"></i>Photo de Couverture (Bannière)</span>
                                <span class="badge bg-light text-muted border">1200x600</span>
                            </label>

                            <!-- Cover Dropzone -->
                            <div class="media-dropzone mb-2" id="coverDropzone" onclick="document.getElementById('cover_file_input').click()">
                                <input type="file" name="cover" id="cover_file_input" class="d-none" accept="image/*" onchange="handleCoverFileSelect(this)">
                                <div id="coverPlaceholderContent">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-3 d-block mb-1"></i>
                                    <span class="fw-bold text-dark small d-block">Cliquez pour téléverser une photo de couverture</span>
                                    <span class="text-muted small">PNG, JPG, WEBP jusqu'à 6 Mo</span>
                                </div>
                                <div id="coverPreviewWrap" class="d-none media-preview-container position-relative">
                                    <img id="coverPreviewImg" src="" alt="Couverture" class="media-preview-cover">
                                    <div class="position-absolute top-2 end-2 d-flex gap-1" style="z-index: 5;">
                                        <button type="button" class="btn btn-sm btn-dark bg-opacity-75 rounded-pill px-2 py-0.5 small" onclick="event.stopPropagation(); document.getElementById('cover_file_input').click()">Changer</button>
                                        <button type="button" class="btn btn-sm btn-danger bg-opacity-75 rounded-pill px-2 py-0.5 small" onclick="event.stopPropagation(); removeCoverImage()">Retirer</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Proposed Preset Covers (1-Click) -->
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <span class="text-muted small me-1" style="font-size: 0.72rem;">Suggestions :</span>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="applyCoverPreset('https://images.unsplash.com/photo-1550547660-d9450f859349?w=1000&auto=format&fit=crop&q=80')">🍔 Burgers</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="applyCoverPreset('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1000&auto=format&fit=crop&q=80')">🍷 Bistrot</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="applyCoverPreset('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1000&auto=format&fit=crop&q=80')">☕ Café</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="applyCoverPreset('https://images.unsplash.com/photo-1513104890138-7c749659a591?w=1000&auto=format&fit=crop&q=80')">🍕 Pizza</button>
                            </div>

                            <!-- Manual URL Toggle -->
                            <div class="mt-auto pt-2">
                                <a href="javascript:void(0)" class="small text-muted text-decoration-none" onclick="document.getElementById('coverUrlManualWrap').classList.toggle('d-none')">
                                    <i class="bi bi-link-45deg me-0.5"></i>Ou saisir une URL d'image manuelle
                                </a>
                                <div id="coverUrlManualWrap" class="d-none mt-1">
                                    <input type="url" id="manual_cover_url" class="form-control form-control-sm font-mono" placeholder="https://exemple.com/image-couverture.jpg" oninput="applyManualCoverUrl(this.value)">
                                </div>
                            </div>
                        </div>

                        <!-- Logo Uploader -->
                        <div class="col-12 col-md-5 d-flex flex-column">
                            <label class="form-label fw-semibold small text-dark d-flex align-items-center justify-content-between mb-2">
                                <span><i class="bi bi-shield-check text-primary me-1"></i>Logo de la Marque</span>
                                <span class="badge bg-light text-muted border">500x500</span>
                            </label>

                            <!-- Logo Dropzone -->
                            <div class="media-dropzone mb-2" id="logoDropzone" onclick="document.getElementById('logo_file_input').click()">
                                <input type="file" name="logo" id="logo_file_input" class="d-none" accept="image/*" onchange="handleLogoFileSelect(this)">
                                <div id="logoPlaceholderContent">
                                    <i class="bi bi-person-badge text-primary fs-3 d-block mb-1"></i>
                                    <span class="fw-bold text-dark small d-block">Téléverser le logo</span>
                                    <span class="text-muted small">PNG ou SVG transparent</span>
                                </div>
                                <div id="logoPreviewWrap" class="d-none text-center position-relative">
                                    <img id="logoPreviewImg" src="" alt="Logo" class="media-preview-logo shadow-xs">
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill me-1" onclick="event.stopPropagation(); document.getElementById('logo_file_input').click()">Changer</button>
                                        <button type="button" class="btn btn-xs btn-outline-danger rounded-pill" onclick="event.stopPropagation(); removeLogoImage()">Retirer</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual URL Toggle -->
                            <div class="mt-auto pt-2">
                                <a href="javascript:void(0)" class="small text-muted text-decoration-none" onclick="document.getElementById('logoUrlManualWrap').classList.toggle('d-none')">
                                    <i class="bi bi-link-45deg me-0.5"></i>Ou saisir une URL de logo manuelle
                                </a>
                                <div id="logoUrlManualWrap" class="d-none mt-1">
                                    <input type="url" id="manual_logo_url" class="form-control form-control-sm font-mono" placeholder="https://exemple.com/logo.png" oninput="applyManualLogoUrl(this.value)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Mode de Navigation entre les Catégories (Vertical scroll, Horizontal scroll, Pages) -->
                <div class="studio-section-card p-4 mb-4" id="sec-nav">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="studio-section-icon text-primary" style="background: rgba(87, 54, 233, 0.1);">
                                <i class="bi bi-compass fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">3. Mode de Navigation entre les Catégories</h5>
                                <p class="text-muted small mb-0">Choisissez l'ergonomie mobile : Vertical scroll, Horizontal scroll ou Pages</p>
                            </div>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1">3 Options</span>
                    </div>

                    <input type="hidden" name="nav_style" id="nav_style" value="vertical_scroll">

                    <div class="row g-3">
                        <!-- Option 1: Vertical scroll -->
                        <div class="col-12 col-md-4">
                            <div class="nav-style-card p-3 rounded-3 h-100 d-flex flex-column active" id="navCardVertical" onclick="selectNavStyle('vertical_scroll')">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary text-white rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Option 1</span>
                                    <i class="bi bi-check-circle-fill text-primary fs-5" id="navCheckVertical"></i>
                                </div>
                                <div class="nav-wireframe flex-column gap-1">
                                    <div class="w-100 rounded-1" style="height: 8px; background: #cbd5e1;"></div>
                                    <div class="w-100 rounded-1" style="height: 8px; background: #94a3b8;"></div>
                                    <div class="w-75 align-self-start rounded-1" style="height: 8px; background: #cbd5e1;"></div>
                                </div>
                                <div class="fw-bold text-dark mb-1">Vertical scroll</div>
                                <p class="text-muted small mb-3" style="font-size: 0.74rem; line-height: 1.4;">
                                    Défilement vertical continu de toutes les rubriques avec barre d'accès rapide.
                                </p>
                                <div class="bg-light p-1.5 rounded-2 text-center text-muted small mt-auto" style="font-size: 0.68rem;">
                                    Défilement vertical continu
                                </div>
                            </div>
                        </div>

                        <!-- Option 2: Horizontal scroll -->
                        <div class="col-12 col-md-4">
                            <div class="nav-style-card p-3 rounded-3 h-100 d-flex flex-column" id="navCardHorizontal" onclick="selectNavStyle('horizontal_scroll')">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-secondary text-white rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Option 2</span>
                                    <i class="bi bi-circle text-muted fs-5" id="navCheckHorizontal"></i>
                                </div>
                                <div class="nav-wireframe gap-1.5 overflow-hidden">
                                    <div class="rounded-1 flex-shrink-0" style="width: 48%; height: 28px; background: #94a3b8;"></div>
                                    <div class="rounded-1 flex-shrink-0" style="width: 48%; height: 28px; background: #cbd5e1;"></div>
                                </div>
                                <div class="fw-bold text-dark mb-1">Horizontal scroll</div>
                                <p class="text-muted small mb-3" style="font-size: 0.74rem; line-height: 1.4;">
                                    Balayage tactile horizontal (swipe / carrousel) de gauche à droite.
                                </p>
                                <div class="bg-light p-1.5 rounded-2 text-center text-muted small mt-auto" style="font-size: 0.68rem;">
                                    Balayage horizontal swipe
                                </div>
                            </div>
                        </div>

                        <!-- Option 3: Pages -->
                        <div class="col-12 col-md-4">
                            <div class="nav-style-card p-3 rounded-3 h-100 d-flex flex-column" id="navCardPages" onclick="selectNavStyle('pages')">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-secondary text-white rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Option 3</span>
                                    <i class="bi bi-circle text-muted fs-5" id="navCheckPages"></i>
                                </div>
                                <div class="nav-wireframe flex-column justify-content-between">
                                    <div class="w-100 rounded-1" style="height: 18px; background: #94a3b8;"></div>
                                    <div class="d-flex align-items-center justify-content-between w-100 px-1">
                                        <span style="font-size: 0.55rem; color: #64748b;">&lsaquo;</span>
                                        <div class="d-flex gap-1"><span class="rounded-circle" style="width:4px;height:4px;background:#5736e9;"></span><span class="rounded-circle" style="width:4px;height:4px;background:#cbd5e1;"></span><span class="rounded-circle" style="width:4px;height:4px;background:#cbd5e1;"></span></div>
                                        <span style="font-size: 0.55rem; color: #64748b;">&rsaquo;</span>
                                    </div>
                                </div>
                                <div class="fw-bold text-dark mb-1">Pages</div>
                                <p class="text-muted small mb-3" style="font-size: 0.74rem; line-height: 1.4;">
                                    Une catégorie isolée par page avec numérotation et boutons Précédent / Suivant.
                                </p>
                                <div class="bg-light p-1.5 rounded-2 text-center text-muted small mt-auto" style="font-size: 0.68rem;">
                                    Page par catégorie + pagination
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Commande WhatsApp & Services Table -->
                <div class="studio-section-card p-4 mb-4" id="sec-whatsapp">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="studio-section-icon bg-success-subtle text-success">
                                <i class="bi bi-whatsapp fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">4. Commande WhatsApp &amp; Services Table</h5>
                                <p class="text-muted small mb-0">Commande WhatsApp directe, coordonnées et gestion des couleurs des informations</p>
                            </div>
                        </div>
                    </div>

                    <!-- 1. WhatsApp Direct Ordering (Official Green) -->
                    <div class="p-3 rounded-4 mb-3" style="background: rgba(37, 211, 102, 0.06); border: 1px solid rgba(37, 211, 102, 0.25);">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                <i class="bi bi-whatsapp text-success fs-5"></i>Bouton Commande WhatsApp
                            </span>
                            <span class="badge rounded-pill text-white px-2.5 py-1 fw-semibold" style="background: #25D366; font-size: 0.7rem;">
                                <i class="bi bi-shield-check me-1"></i>Bouton Officiel Vert (#25D366) — Protégé
                            </span>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="whatsapp" class="form-label fw-semibold small text-dark">
                                    Numéro WhatsApp Commandes
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-success border-end-0"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="whatsapp" id="whatsapp" class="form-control border-start-0" value="{{ old('whatsapp', '+212 612-345678') }}" placeholder="+212 600-000000" oninput="syncSimulatorHeader(); syncSimulatorWa();">
                                </div>
                                <span class="form-text small">Affiche le bouton flottant officiel vert « Commander via WhatsApp » sur le mobile.</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Gestion des Couleurs des Éléments & Badges (Hors WhatsApp) -->
                    <div class="p-3 rounded-4 mb-3 border bg-light">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2.5">
                            <div>
                                <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                    <i class="bi bi-palette-fill text-primary"></i>Gestion des Couleurs des Informations &amp; Badges (Hors WhatsApp)
                                </span>
                                <p class="text-muted small mb-0" style="font-size: 0.76rem;">Gérez la couleur de fond et du texte des informations (WiFi, Téléphone, Instagram...). Le bouton WhatsApp reste vert officiel.</p>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1" onclick="syncServiceColorsWithItems()">
                                <i class="bi bi-arrow-repeat me-1"></i>Harmoniser avec les plats
                            </button>
                        </div>

                        <div class="row g-2.5">
                            <!-- Fond des Éléments & Badges -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                    <i class="bi bi-square-fill me-1" style="color: #64748b;"></i>Couleur de Fond des Badges / Informations
                                </label>
                                <div class="d-flex align-items-center gap-2 p-1.5 border rounded-3 bg-white">
                                    <input type="color" name="service_bg_color" id="service_bg_color" class="form-control form-control-color border-0 p-0" value="{{ old('service_bg_color', '#ffffff') }}" style="width: 32px; height: 32px; cursor: pointer;" oninput="setServiceBgColor(this.value)">
                                    <span class="small font-mono fw-semibold" id="serviceBgColorCode" style="font-size: 0.78rem;">{{ old('service_bg_color', '#ffffff') }}</span>
                                </div>
                            </div>

                            <!-- Texte des Éléments & Badges -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label small fw-semibold text-dark mb-1" style="font-size: 0.78rem;">
                                    <i class="bi bi-fonts me-1" style="color: #64748b;"></i>Couleur du Texte des Badges / Informations
                                </label>
                                <div class="d-flex align-items-center gap-2 p-1.5 border rounded-3 bg-white">
                                    <input type="color" name="service_text_color" id="service_text_color" class="form-control form-control-color border-0 p-0" value="{{ old('service_text_color', '#1e293b') }}" style="width: 32px; height: 32px; cursor: pointer;" oninput="setServiceTextColor(this.value)">
                                    <span class="small font-mono fw-semibold" id="serviceTextColorCode" style="font-size: 0.78rem;">{{ old('service_text_color', '#1e293b') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Accès & Coordonnées (WiFi, Téléphone, Adresse, Instagram) -->
                    <div class="row g-3">
                        <!-- WiFi Integration -->
                        <div class="col-12 col-md-6">
                            <label for="wifi_ssid" class="form-label fw-semibold small text-dark">
                                <i class="bi bi-wifi text-warning me-1"></i>Nom du Réseau WiFi (SSID)
                            </label>
                            <input type="text" name="wifi_ssid" id="wifi_ssid" class="form-control" value="{{ old('wifi_ssid', 'Smash_FreeWifi') }}" placeholder="Ex: LeComptoir_Guest" oninput="syncSimulatorHeader()">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="wifi_password" class="form-label fw-semibold small text-dark">
                                <i class="bi bi-key text-warning me-1"></i>Mot de Passe WiFi
                            </label>
                            <input type="text" name="wifi_password" id="wifi_password" class="form-control" value="{{ old('wifi_password', 'burgers2026') }}" placeholder="Ex: motdepasse123">
                        </div>

                        <!-- Phone, Instagram, Address -->
                        <div class="col-12 col-md-4">
                            <label for="phone" class="form-label fw-semibold small text-dark">
                                <i class="bi bi-telephone text-primary me-1"></i>Téléphone Fixe / Réservations
                            </label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', '+212 522-112233') }}" placeholder="+212 522-000000" oninput="syncSimulatorHeader()">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="instagram" class="form-label fw-semibold small text-dark">
                                <i class="bi bi-instagram text-danger me-1"></i>Instagram
                            </label>
                            <input type="text" name="instagram" id="instagram" class="form-control" value="{{ old('instagram', '@smashandbun') }}" placeholder="@monrestaurant" oninput="syncSimulatorHeader()">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="address" class="form-label fw-semibold small text-dark">
                                <i class="bi bi-geo-alt text-secondary me-1"></i>Adresse / Emplacement
                            </label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', 'Boulevard d\'Anfa, Casablanca') }}" placeholder="12 Rue de la Plage, Casablanca">
                        </div>
                    </div>
                </div>

                <!-- Section 5: Constructeur de Catégories & Plats (Proposé ou Manuel) -->
                <div class="studio-section-card p-4 mb-4" id="sec-items">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="studio-section-icon bg-warning-subtle text-warning-emphasis">
                                <i class="bi bi-card-list fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">5. Catégories &amp; Plats du Menu</h5>
                                <p class="text-muted small mb-0">Téléversez les photos des plats, utilisez les suggestions ou personnalisez manuellement</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="clearMenu()">
                                <i class="bi bi-trash me-1"></i>Vider tout
                            </button>
                            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 d-flex align-items-center gap-1.5" onclick="addCustomCategory()">
                                <i class="bi bi-plus-circle"></i>
                                <span>Catégorie Manuelle</span>
                            </button>
                        </div>
                    </div>

                    <!-- Quick Proposed Categories Toolbar -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark small">
                                <i class="bi bi-lightning-charge text-warning me-1"></i>Ajouter une catégorie suggérée en 1 clic :
                            </span>
                            <span class="text-muted small" style="font-size: 0.72rem;">Cliquez pour insérer</span>
                        </div>
                        <div class="d-flex flex-wrap gap-1.5">
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🍔 Smash Burgers &amp; Buns', 'Pain brioché toasté, bœuf Angus croustillant &amp; sauces maison')">🍔 Burgers</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🍕 Pizzas au Feu de Bois', 'Pâte fermentée 48h, sauce tomate San Marzano &amp; mozzarella Fior di Latte')">🍕 Pizzas</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🥗 Entrées &amp; Salades Fraîches', 'Légumes de saison, burrata crémeuse et assaisonnements signature')">🥗 Entrées</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🥩 Plats Chauds &amp; Viandes', 'Sélection du boucher, sauces maison et accompagnements')">🥩 Plats</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🍝 Pâtes Fraîches Artisanales', 'Pâtes faites maison chaque matin avec semoule de blé dur')">🍝 Pâtes</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🥪 Sandwiches &amp; Tacos', 'Garnitures généreuses et sauces relevées')">🥪 Sandwiches</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🍟 Accompagnements &amp; Sides', 'Frites fraîches, loaded fries et bouchées croustillantes')">🍟 Sides</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🍰 Desserts &amp; Douceurs', 'Pâtisseries artisanales, fondants et glaces')">🍰 Desserts</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('🥤 Boissons &amp; Sodas', 'Sodas frais, jus pressés minute et mocktails')">🥤 Boissons</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('☕ Cafés &amp; Thés de Spécialité', 'Grains 100% arabica torréfaction artisanale')">☕ Cafés</button>
                            <button type="button" class="proposed-chip" onclick="addSuggestedCategory('👶 Menu Enfant', 'Plat doux, petite boisson et dessert gourmand')">👶 Menu Enfant</button>
                        </div>
                    </div>

                    <!-- Dynamic Category Container -->
                    <div id="categoryBuilderContainer" class="d-flex flex-column gap-3">
                        <!-- Populated by JavaScript -->
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <button type="button" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-semibold" onclick="addCustomCategory()">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter une nouvelle catégorie personnalisée
                        </button>
                    </div>
                </div>

                <!-- Form Submit Bottom Bar -->
                <div class="studio-section-card p-3 d-flex flex-row align-items-center justify-content-between">
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" style="background: linear-gradient(135deg, #5736e9 0%, #4324c7 100%); border: none;">
                        <i class="bi bi-cloud-arrow-up me-1"></i>Enregistrer &amp; Publier le Menu
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT COLUMN: Interactive Smartphone Live Preview Simulator -->
        <div class="col-12 col-xl-5 sticky-preview-wrapper sticky-top">
            <div class="studio-canvas-stage shadow-sm">
                <!-- Simulator Toolbar -->
                <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-dark text-white rounded-pill px-2.5 py-1 small fw-semibold">
                            <i class="bi bi-phone me-1"></i>Simulateur Mobile Réel
                        </span>
                    </div>

                    <!-- Device Switches -->
                    <div class="d-flex align-items-center gap-1 bg-white p-1 rounded-pill border shadow-xs">
                        <button type="button" class="btn btn-xs btn-outline-secondary border-0 rounded-pill active sim-device-btn" onclick="setSimDevice('iphone', this)" title="iPhone 15 Pro">
                            iPhone
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-secondary border-0 rounded-pill sim-device-btn" onclick="setSimDevice('pixel', this)" title="Android Pixel">
                            Pixel
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-secondary border-0 rounded-pill sim-device-btn" onclick="setSimDevice('frameless', this)" title="Sans bordure">
                            Plein
                        </button>
                    </div>

                    <!-- Scale Buttons -->
                    <div class="btn-group btn-group-sm bg-white rounded-pill border shadow-xs">
                        <button type="button" class="btn btn-xs btn-outline-secondary border-0 rounded-start-pill" onclick="setSimScale(0.85, this)">85%</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary border-0 rounded-end-pill active" onclick="setSimScale(1, this)">100%</button>
                    </div>
                </div>

                <!-- Smartphone Device Mockup -->
                <div class="d-flex justify-content-center">
                    <div id="menuPhoneFrame" class="phone-frame-mockup">
                        <!-- Dynamic Island -->
                        <div id="simDynamicIsland" class="phone-dynamic-island">
                            <div class="island-camera"></div>
                            <div class="island-speaker"></div>
                        </div>

                        <!-- Metal Reflection Glare -->
                        <div class="phone-metal-glare"></div>

                        <!-- Phone Inner Screen Content -->
                        <div class="phone-screen-content" id="simScreenContent">
                            <!-- Status Bar -->
                            <div class="d-flex align-items-center justify-content-between px-3 pt-2 text-white" style="position: absolute; top: 0; left: 0; right: 0; z-index: 1040; font-size: 0.7rem; font-weight: 600;">
                                <span id="simClock">12:30</span>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-wifi"></i>
                                    <i class="bi bi-battery-full"></i>
                                </div>
                            </div>

                            <!-- Header Section -->
                            <div class="sim-header" id="simHeaderBg">
                                <div class="sim-header-content">
                                    <div class="d-flex align-items-center gap-2.5 mb-2">
                                        <div class="sim-logo" id="simLogoIcon">
                                            <i class="bi bi-cup-hot"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="badge bg-white text-dark rounded-pill fw-bold text-uppercase px-2 py-0.5" id="simBrandBadge" style="font-size: 0.58rem; letter-spacing: 0.04em;">
                                                Fast Food
                                            </span>
                                            <h4 class="fw-bold mb-0 text-truncate text-white" id="simBrandName" style="font-size: 1.15rem;">
                                                Smash &amp; Bun
                                            </h4>
                                        </div>
                                    </div>

                                    <p class="small text-white-50 mb-2" id="simDescription" style="font-size: 0.75rem; line-height: 1.3;">
                                        Burgers pur bœuf smashé minute, frites fraîches maison &amp; milkshakes.
                                    </p>

                                    <!-- Action Badges (WiFi, Phone, Instagram) with Text as Before -->
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap" id="simActionPills">
                                        <span class="badge fw-semibold px-2 py-1 rounded-pill" id="simWifiBadge" style="background: var(--sim-service-bg, #ffffff); color: var(--sim-service-text, #1e293b); font-size: 0.65rem; border: 1px solid rgba(255,255,255,0.25);">
                                            <i class="bi bi-wifi me-1"></i><span id="simWifiText">Smash_FreeWifi</span>
                                        </span>
                                        <span class="badge fw-semibold px-2 py-1 rounded-pill" id="simPhoneBadge" style="background: var(--sim-service-bg, #ffffff); color: var(--sim-service-text, #1e293b); font-size: 0.65rem; border: 1px solid rgba(255,255,255,0.25);">
                                            <i class="bi bi-telephone me-1"></i><span id="simPhoneText">+212 522-112233</span>
                                        </span>
                                        <span class="badge fw-semibold px-2 py-1 rounded-pill" id="simInstaBadge" style="background: var(--sim-service-bg, #ffffff); color: var(--sim-service-text, #1e293b); font-size: 0.65rem; border: 1px solid rgba(255,255,255,0.25);">
                                            <i class="bi bi-instagram me-1"></i><span id="simInstaText">@smashandbun</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Simulator Search & Price Filter Bar -->
                            <div class="px-2 pt-2 pb-1 bg-white border-bottom" id="simFilterBar">
                                <div class="d-flex align-items-center gap-1.5">
                                    <div class="input-group input-group-sm flex-grow-1" style="background: #f1f5f9; border-radius: 999px; padding: 2px 8px;">
                                        <span class="input-group-text bg-transparent border-0 p-0 me-1 text-muted"><i class="bi bi-search" style="font-size: 0.7rem;"></i></span>
                                        <input type="text" id="simMenuSearch" class="form-control form-control-sm bg-transparent border-0 p-0 shadow-none" style="font-size: 0.7rem;" placeholder="Rechercher..." oninput="syncSimulatorDishes()">
                                    </div>
                                    <div class="input-group input-group-sm" style="width: 92px; background: #f1f5f9; border-radius: 999px; padding: 2px 6px;">
                                        <span class="input-group-text bg-transparent border-0 p-0 me-1 text-muted" style="font-size: 0.65rem;">Max</span>
                                        <input type="number" id="simMaxPrice" min="0" class="form-control form-control-sm bg-transparent border-0 p-0 shadow-none font-mono" style="font-size: 0.7rem;" placeholder="Prix" oninput="syncSimulatorDishes()">
                                    </div>
                                </div>
                            </div>

                            <!-- Option 1: Horizontal Categories Navigation (Pills) -->
                            <div class="bg-white border-bottom px-2 py-2 sticky-top shadow-xs" id="simPillsNavWrap" style="z-index: 30;">
                                <div class="d-flex align-items-center gap-1.5 overflow-x-auto pb-1" id="simNavCategories" style="scrollbar-width: none;">
                                    <!-- Populated by JS -->
                                </div>
                            </div>

                            <!-- Option 2: Segmented Tabs / Pages Navigation -->
                            <div class="bg-white border-bottom p-2 sticky-top d-none shadow-xs" id="simTabsNavWrap" style="z-index: 30;">
                                <div class="sim-tabs-bar" id="simTabsList">
                                    <!-- Populated by JS -->
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 px-1 border-top" style="border-color: rgba(226, 232, 240, 0.7) !important;">
                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.62rem; font-weight: 600;">
                                        <i class="bi bi-book text-primary"></i>
                                        <span id="simPageBreadcrumb">Page 1 sur 1</span>
                                    </div>
                                    <div class="sim-page-dots" id="simPageDotsTop">
                                        <!-- Dots populated by JS -->
                                    </div>
                                </div>
                            </div>

                            <!-- Option 3: Visual Grid Top Cards -->
                            <div class="p-2 d-none" id="simGridNavWrap">
                                <div class="row g-1.5" id="simGridCardsContainer">
                                    <!-- Populated by JS -->
                                </div>
                            </div>

                            <!-- Menu Dishes List Container -->
                            <div class="p-2.5 d-flex flex-column gap-3 flex-grow-1" id="simDishesContainer">
                                <!-- Populated by JS -->
                            </div>

                            <!-- Pages Bottom Navigation Controls inside Simulator -->
                            <div id="simTabArrowsWrap" class="d-none align-items-center justify-content-between p-2 border-top bg-white shadow-sm" style="position: sticky; bottom: 0; z-index: 25;">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" id="simPrevPageBtn" style="font-size: 0.68rem; font-weight: 600;" onclick="simNavigateTab(-1)">
                                    <i class="bi bi-chevron-left"></i><span>Précédent</span>
                                </button>
                                <div class="d-flex flex-column align-items-center gap-0.5">
                                    <div class="sim-page-dots" id="simPageDotsBottom">
                                        <!-- Dots populated by JS -->
                                    </div>
                                    <span class="text-muted fw-bold" style="font-size: 0.58rem;" id="simTabCounter">Page 1 / 1</span>
                                </div>
                                <button type="button" class="btn btn-sm rounded-pill px-2.5 py-1 text-white d-inline-flex align-items-center gap-1 shadow-xs" id="simNextPageBtn" style="background: var(--sim-accent); border: 1px solid var(--sim-accent); font-size: 0.68rem; font-weight: 600;" onclick="simNavigateTab(1)">
                                    <span>Suivant</span><i class="bi bi-chevron-right"></i>
                                </button>
                            </div>

                            <!-- Sticky Floating WhatsApp Button -->
                            <a href="javascript:void(0)" class="sim-wa-floating-btn" id="simFloatingWaBtn">
                                <i class="bi bi-whatsapp"></i>
                                <span>Commander par WhatsApp</span>
                            </a>

                            <!-- Option 3 Floating Category Drawer Trigger inside Phone Frame -->
                            <button type="button" class="sim-floating-drawer-btn d-none" id="simFloatingDrawerBtn" onclick="toggleSimDrawer(true)">
                                <i class="bi bi-list-ul"></i>
                                <span>Catégories</span>
                            </button>

                            <!-- Option 3 Bottom Sheet Drawer inside Phone Frame -->
                            <div class="sim-drawer-overlay" id="simDrawerOverlay" onclick="toggleSimDrawer(false)">
                                <div class="sim-drawer-content" onclick="event.stopPropagation()">
                                    <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-1.5">
                                        <span class="fw-bold text-dark small">Toutes les Catégories</span>
                                        <button type="button" class="btn btn-xs btn-light rounded-circle" onclick="toggleSimDrawer(false)">✕</button>
                                    </div>
                                    <div class="d-flex flex-column gap-1.5" id="simDrawerCategoriesList">
                                        <!-- Populated by JS -->
                                    </div>
                                </div>
                            </div>

                            <!-- Footer inside Simulator -->
                            <div class="text-center py-3 text-muted small mt-auto border-top bg-light" style="font-size: 0.65rem;">
                                <span>Propulsé par <strong>QR-Menu Pro</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3 text-muted small">
                    <i class="bi bi-info-circle me-1"></i>Aperçu interactif : téléversez vos photos et modifiez les textes pour voir le rendu en direct.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// State: Categories & Dishes & Navigation
let menuData = [];
let currentCoverImage = '';
let currentLogoImage = '';
let currentNavStyle = 'vertical_scroll';
let activeCategoryTabIdx = 0;

// Navigation Style Selector (vertical_scroll, horizontal_scroll, pages)
function selectNavStyle(style) {
    if (style === 'pills') style = 'vertical_scroll';
    if (style === 'grid_drawer') style = 'horizontal_scroll';
    if (style === 'tabs') style = 'pages';

    currentNavStyle = style;
    document.getElementById('nav_style').value = style;

    // Update radio cards
    const cards = {
        vertical_scroll: document.getElementById('navCardVertical'),
        horizontal_scroll: document.getElementById('navCardHorizontal'),
        pages: document.getElementById('navCardPages')
    };
    const checks = {
        vertical_scroll: document.getElementById('navCheckVertical'),
        horizontal_scroll: document.getElementById('navCheckHorizontal'),
        pages: document.getElementById('navCheckPages')
    };

    Object.keys(cards).forEach(key => {
        if (cards[key]) {
            if (key === style) {
                cards[key].classList.add('active');
                if (checks[key]) checks[key].className = 'bi bi-check-circle-fill text-primary fs-5';
            } else {
                cards[key].classList.remove('active');
                if (checks[key]) checks[key].className = 'bi bi-circle text-muted fs-5';
            }
        }
    });

    syncSimulatorDishes();
}

function simSelectCategoryTab(idx) {
    activeCategoryTabIdx = idx;
    syncSimulatorDishes();
    const phoneBody = document.querySelector('.menu-phone-body');
    if (phoneBody) phoneBody.scrollTo({ top: 0, behavior: 'smooth' });
}

function simNavigateTab(direction) {
    if (!menuData || menuData.length === 0) return;
    let next = activeCategoryTabIdx + direction;
    if (next < 0) next = 0;
    if (next >= menuData.length) next = 0;
    activeCategoryTabIdx = next;
    syncSimulatorDishes();
    const phoneBody = document.querySelector('.menu-phone-body');
    if (phoneBody) phoneBody.scrollTo({ top: 0, behavior: 'smooth' });
}

function toggleSimDrawer(open) {
    const drawer = document.getElementById('simDrawerOverlay');
    if (drawer) {
        drawer.style.display = open ? 'flex' : 'none';
    }
}

function jumpToSimCategory(idx) {
    toggleSimDrawer(false);
    if (currentNavStyle === 'pages' || currentNavStyle === 'tabs') {
        simSelectCategoryTab(idx);
    } else {
        const target = document.getElementById('sim_cat_' + idx);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }
}

// Proposed Culinary Badges
const proposedBadges = [
    { label: "Sans badge", value: "" },
    { label: "⭐ Chef", value: "Chef" },
    { label: "🔥 Populaire / Best-Seller", value: "Best-Seller" },
    { label: "✨ Nouveau", value: "Nouveau" },
    { label: "🌱 Végétarien", value: "Végétarien" },
    { label: "🌿 Végan", value: "Végan" },
    { label: "🌾 Sans Gluten", value: "Sans Gluten" },
    { label: "🌶️ Épicé", value: "Épicé" },
    { label: "🥩 Halal", value: "Halal" },
    { label: "🏷️ Promo", value: "Promo" },
    { label: "🥇 Signature", value: "Signature" },
    { label: "🥛 Sans Lactose", value: "Sans Lactose" }
];

// Presets Definition
const presets = {
    fast_food: {
        brand_name: "Smash & Bun",
        title: "Carte Street Food & Burgers",
        brand_type: "fast_food",
        currency: "DH",
        theme_color: "#ea580c",
        cover_image: "https://images.unsplash.com/photo-1550547660-d9450f859349?w=1000&auto=format&fit=crop&q=80",
        logo_url: "",
        description: "Burgers pur bœuf smashé minute, frites fraîches maison & milkshakes.",
        whatsapp: "+212 612-345678",
        phone: "+212 522-112233",
        instagram: "@smashandbun",
        wifi_ssid: "Smash_FreeWifi",
        address: "Boulevard d'Anfa, Casablanca",
        categories: [
            {
                name: "🍔 Smash Burgers",
                description: "Pain brioché toasté, bœuf Angus croustillant & sauces maison",
                items: [
                    { name: "Classic Double Cheese", price: 65, original_price: 75, description: "Double steak smashé, double cheddar affiné, pickles, sauce secrète", badge: "Best-Seller", image_url: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Truffle & Crispy Bacon", price: 85, original_price: null, description: "Double smash, sauce truffe noire d'Italie, bacon croustillant, oignons confits", badge: "Chef", image_url: "https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Spicy Jalapeno Crunch", price: 72, original_price: null, description: "Double smash, piments jalapenos marinés, cheddar fondu, sauce spicy mayo", badge: "Épicé", image_url: "https://images.unsplash.com/photo-1550547660-d9450f859349?w=400&auto=format&fit=crop&q=60", is_available: true }
                ]
            },
            {
                name: "🍟 Sides & Frites",
                description: "Accompagnements gourmands et sauces",
                items: [
                    { name: "Loaded Fries Cheddar & Bacon", price: 38, original_price: null, description: "Frites fraîches enrobées de cheddar chaud et éclats de bacon croustillant", badge: "Populaire", image_url: "https://images.unsplash.com/photo-1576107232684-1279f3908594?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Onion Rings Croustillants (x8)", price: 28, original_price: null, description: "Beignets d'oignons dorés avec sauce barbecue fumée", badge: "", image_url: "", is_available: true }
                ]
            },
            {
                name: "🥤 Boissons & Shakes",
                description: "Rafraîchissements et desserts glacés",
                items: [
                    { name: "Milkshake Vanille Bourbon & Spéculoos", price: 35, original_price: null, description: "Glace artisanale, biscuit spéculoos broyé, chantilly maison", badge: "Fait Maison", image_url: "", is_available: true },
                    { name: "Coca-Cola Zéro 33cl", price: 15, original_price: null, description: "Canette fraîche servie avec tranche de citron", badge: "", image_url: "", is_available: true }
                ]
            }
        ]
    },
    restaurant: {
        brand_name: "L'Atelier Bistronomique",
        title: "Menu & Suggestions du Marché",
        brand_type: "restaurant",
        currency: "DH",
        theme_color: "#5736e9",
        cover_image: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1000&auto=format&fit=crop&q=80",
        logo_url: "",
        description: "Cuisine bistronomique moderne, arrivages marins frais et viandes maturées.",
        whatsapp: "+212 661-987654",
        phone: "+212 522-445566",
        instagram: "@latelier_bistro",
        wifi_ssid: "Atelier_Guest",
        address: "Corniche d'Ain Diab, Casablanca",
        categories: [
            {
                name: "🥗 Entrées Gourmandes",
                description: "Pour commencer en douceur",
                items: [
                    { name: "Burrata Crémeuse des Pouilles (200g)", price: 95, original_price: null, description: "Tomates cerises confites, pesto genovese au basilic frais, pignons torréfiés", badge: "Chef", image_url: "https://images.unsplash.com/photo-1592417817098-8f3d6910985c?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Tartare de Saumon Sauvage & Avocat", price: 110, original_price: 125, description: "Saumon label rouge coupé au couteau, émulsion yuzu et graines de sésame noir", badge: "Signature", image_url: "", is_available: true }
                ]
            },
            {
                name: "🥩 Plats Principaux",
                description: "Sélection terre & mer du chef",
                items: [
                    { name: "Filet de Bœuf Maturé Rossini", price: 210, original_price: null, description: "Poêlée de foie gras, écrasé de pommes de terre à l'huile de truffe, jus corsé", badge: "Signature", image_url: "https://images.unsplash.com/photo-1544025162-d76694265947?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Pavé de Loup de Mer Rôti", price: 175, original_price: null, description: "Légumes racines glacés, vierge de tomates à la coriandre fraîche", badge: "Pêche du jour", image_url: "", is_available: true }
                ]
            },
            {
                name: "🍰 Desserts Signatures",
                description: "La touche sucrée de notre chef pâtissier",
                items: [
                    { name: "Fondant Chocolat Valrhona 70%", price: 65, original_price: null, description: "Cœur coulant intense, boule de glace vanille de Madagascar", badge: "Nouveau", image_url: "", is_available: true }
                ]
            }
        ]
    },
    cafe: {
        brand_name: "Café Bloom & Bakery",
        title: "Carte Brunch & Spécialités Café",
        brand_type: "cafe",
        currency: "DH",
        theme_color: "#059669",
        cover_image: "https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1000&auto=format&fit=crop&q=80",
        logo_url: "",
        description: "Cafés de spécialité torréfiés sur place, toasts bio & viennoiseries pur beurre.",
        whatsapp: "+212 660-123456",
        phone: "+212 522-778899",
        instagram: "@bloom_cafe",
        wifi_ssid: "Bloom_Fibre",
        address: "Quartier Gauthier, Casablanca",
        categories: [
            {
                name: "🥑 Brunch & Savory Toasts",
                description: "Servis toute la journée",
                items: [
                    { name: "Avocado Toast & Œufs Pochés Bio", price: 68, original_price: null, description: "Pain au levain toasté, écrasé d'avocat au citron vert, féta émiettée, graines de grenade", badge: "Bio", image_url: "https://images.unsplash.com/photo-1525351484163-7529414344d8?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Pancakes Salés Bacon & Cheddar", price: 74, original_price: null, description: "Pancakes moelleux maison, œufs brouillés, sirop d'érable authentique", badge: "Populaire", image_url: "", is_available: true }
                ]
            },
            {
                name: "☕ Cafés & Lattes",
                description: "Grains 100% arabica pure origine",
                items: [
                    { name: "Flat White Double Shot", price: 28, original_price: null, description: "Espresso équilibré et micromousse de lait onctueuse", badge: "Signature", image_url: "", is_available: true },
                    { name: "Matcha Latte Cérémonial", price: 34, original_price: null, description: "Thé vert bio d'Uji battu au chasen, lait végétal d'avoine au choix", badge: "Bio", image_url: "", is_available: true }
                ]
            }
        ]
    },
    pizzeria: {
        brand_name: "Pizzeria Da Napoli",
        title: "Pizzas Napolitaines au Feu de Bois",
        brand_type: "pizzeria",
        currency: "DH",
        theme_color: "#dc2626",
        cover_image: "https://images.unsplash.com/photo-1513104890138-7c749659a591?w=1000&auto=format&fit=crop&q=80",
        logo_url: "",
        description: "Pâte fermentée 48h, farine Caputo, mozzarella Fior di Latte et cuisson à 450°C.",
        whatsapp: "+212 663-888999",
        phone: "+212 522-334455",
        instagram: "@danapoli_pizza",
        wifi_ssid: "DaNapoli_WiFi",
        address: "Gauthier, Casablanca",
        categories: [
            {
                name: "🍕 Pizzas Rouges (Base Tomate)",
                description: "La tradition napolitaine authentique",
                items: [
                    { name: "Margherita D.O.P.", price: 70, original_price: null, description: "Sauce tomate San Marzano, mozzarella Fior di Latte, basilic frais, filet d'huile d'olive", badge: "Signature", image_url: "https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&auto=format&fit=crop&q=60", is_available: true },
                    { name: "Diavola Piccante", price: 88, original_price: null, description: "Spianata calabraise épicée, mozzarella, poivrons marinés, origan sauvage", badge: "Épicé", image_url: "", is_available: true }
                ]
            },
            {
                name: "🧀 Pizzas Blanches (Base Crème)",
                description: "Pour les amateurs de fromage fondant",
                items: [
                    { name: "Tartufo & Funghi", price: 115, original_price: 130, description: "Crème de truffe blanche, champignons de Paris poêlés, mozzarella, copeaux de parmesan", badge: "Chef", image_url: "", is_available: true }
                ]
            }
        ]
    }
};

// Preset Loader
function loadPreset(key) {
    if (presets[key]) {
        const p = presets[key];
        document.getElementById('brand_name').value = p.brand_name;
        document.getElementById('title').value = p.title;
        setBrandType(p.brand_type);
        setCurrency(p.currency);
        document.getElementById('description').value = p.description;
        document.getElementById('whatsapp').value = p.whatsapp;
        document.getElementById('phone').value = p.phone;
        document.getElementById('instagram').value = p.instagram;
        document.getElementById('wifi_ssid').value = p.wifi_ssid;
        document.getElementById('address').value = p.address;
        applyColorTheme(p.theme_color || '#ea580c', p.item_bg_color || '#ffffff', p.item_text_color || '#1e293b', p.bg_color || '#f8fafc');

        if (p.cover_image) {
            applyCoverPreset(p.cover_image);
        } else {
            removeCoverImage();
        }

        menuData = JSON.parse(JSON.stringify(p.categories));
        renderCategoryBuilder();
    }
}

// Brand Type Management (Proposed or Custom Manual)
function onBrandTypeSelectChange(val) {
    if (val === 'other') {
        toggleCustomBrandType();
    } else {
        document.getElementById('brand_type').value = val;
        syncSimulatorHeader();
    }
}

function toggleCustomBrandType() {
    const selectWrap = document.getElementById('brandTypeSelectWrap');
    const customWrap = document.getElementById('brandTypeCustomWrap');
    const isCustom = !selectWrap.classList.contains('d-none');

    if (isCustom) {
        selectWrap.classList.add('d-none');
        customWrap.classList.remove('d-none');
        document.getElementById('brand_type_custom').focus();
    } else {
        selectWrap.classList.remove('d-none');
        customWrap.classList.add('d-none');
        document.getElementById('brand_type').value = document.getElementById('brand_type_select').value;
    }
    syncSimulatorHeader();
}

function setItemBgColor(hex) {
    document.getElementById('item_bg_color').value = hex;
    document.getElementById('itemBgColorCode').textContent = hex;
    const simScreen = document.getElementById('simScreenContent');
    if (simScreen) {
        simScreen.style.setProperty('--sim-item-bg', hex);
    }
    syncSimulatorDishes();
}

function setItemTextColor(hex) {
    document.getElementById('item_text_color').value = hex;
    document.getElementById('itemTextColorCode').textContent = hex;
    const simScreen = document.getElementById('simScreenContent');
    if (simScreen) {
        simScreen.style.setProperty('--sim-item-text', hex);
    }
    syncSimulatorDishes();
}

function setServiceBgColor(hex) {
    const input = document.getElementById('service_bg_color');
    if (input) input.value = hex;
    const code = document.getElementById('serviceBgColorCode');
    if (code) code.textContent = hex;
    const simScreen = document.getElementById('simScreenContent');
    if (simScreen) {
        simScreen.style.setProperty('--sim-service-bg', hex);
    }
    document.querySelectorAll('#simActionPills .badge').forEach(b => {
        b.style.backgroundColor = hex;
    });
}

function setServiceTextColor(hex) {
    const input = document.getElementById('service_text_color');
    if (input) input.value = hex;
    const code = document.getElementById('serviceTextColorCode');
    if (code) code.textContent = hex;
    const simScreen = document.getElementById('simScreenContent');
    if (simScreen) {
        simScreen.style.setProperty('--sim-service-text', hex);
    }
    document.querySelectorAll('#simActionPills .badge').forEach(b => {
        b.style.color = hex;
    });
}

function syncServiceColorsWithItems() {
    const itemBg = document.getElementById('item_bg_color').value;
    const itemText = document.getElementById('item_text_color').value;
    setServiceBgColor(itemBg);
    setServiceTextColor(itemText);
}

function toggleSimTableServices(enabled) {
    const wrap = document.getElementById('simTableServicesWrap');
    if (wrap) {
        if (enabled) {
            wrap.classList.remove('d-none');
        } else {
            wrap.classList.add('d-none');
        }
    }
}

function syncSimulatorWa() {
    const wa = document.getElementById('whatsapp')?.value.trim();
    const btn = document.getElementById('simFloatingWaBtn');
    if (btn) {
        if (!wa) {
            btn.classList.add('d-none');
        } else {
            btn.classList.remove('d-none');
        }
    }
}

function setMenuBgColor(hex) {
    document.getElementById('bg_color').value = hex;
    document.getElementById('bgColorCode').textContent = hex;
    document.getElementById('simScreenContent').style.setProperty('--sim-menu-bg', hex);
    document.getElementById('simScreenContent').style.backgroundColor = hex;
    const dishesContainer = document.getElementById('simDishesContainer');
    if (dishesContainer) dishesContainer.style.backgroundColor = hex;
}

function applyColorTheme(accent, itemBg, itemText, menuBg) {
    setThemeColor(accent);
    setItemBgColor(itemBg);
    setItemTextColor(itemText);
    setMenuBgColor(menuBg);
    setServiceBgColor(itemBg);
    setServiceTextColor(itemText);
}

function setBrandType(type) {
    const select = document.getElementById('brand_type_select');
    let found = false;
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === type) {
            select.selectedIndex = i;
            found = true;
            break;
        }
    }
    if (!found) {
        document.getElementById('brandTypeSelectWrap').classList.add('d-none');
        document.getElementById('brandTypeCustomWrap').classList.remove('d-none');
        document.getElementById('brand_type_custom').value = type;
        document.getElementById('brand_type').value = type;
    } else {
        document.getElementById('brandTypeSelectWrap').classList.remove('d-none');
        document.getElementById('brandTypeCustomWrap').classList.add('d-none');
        document.getElementById('brand_type').value = type;
    }
    syncSimulatorHeader();
}

// Currency Selector
function setCurrency(curr) {
    document.getElementById('currency').value = curr;
    document.querySelectorAll('.currency-chip').forEach(c => {
        if (c.textContent.trim() === curr || c.textContent.includes(curr)) {
            c.classList.add('active', 'btn-secondary');
            c.classList.remove('btn-outline-secondary');
        } else {
            c.classList.remove('active', 'btn-secondary');
            c.classList.add('btn-outline-secondary');
        }
    });
    renderCategoryBuilder();
}

// Color Theme
function setThemeColor(hex) {
    document.getElementById('theme_color').value = hex;
    document.getElementById('themeColorCode').textContent = hex;
    document.getElementById('simScreenContent').style.setProperty('--sim-accent', hex);

    document.querySelectorAll('.color-swatch-btn').forEach(btn => {
        if (btn.style.backgroundColor === hex || rgbToHex(btn.style.backgroundColor) === hex.toLowerCase()) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    syncSimulatorHeader();
}

function rgbToHex(col) {
    if (col && col.charAt(0) === 'r') {
        col = col.replace('rgb(','').replace(')','').split(',');
        let r = parseInt(col[0], 10).toString(16);
        let g = parseInt(col[1], 10).toString(16);
        let b = parseInt(col[2], 10).toString(16);
        r = r.length === 1 ? '0' + r : r;
        g = g.length === 1 ? '0' + g : g;
        b = b.length === 1 ? '0' + b : b;
        return '#' + r + g + b;
    }
    return col;
}

// Cover Image Handlers (Upload / Preset / Manual URL)
function handleCoverFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Instant local preview
        const reader = new FileReader();
        reader.onload = function(e) {
            setCoverPreview(e.target.result);
        };
        reader.readAsDataURL(file);

        // Upload asynchronously
        uploadCoverAsync(file);
    }
}

function uploadCoverAsync(file) {
    const formData = new FormData();
    formData.append('image', file);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("{{ route('admin.menus.upload-cover-image') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentCoverImage = data.url;
            document.getElementById('coverImageUrlInput').value = data.url;
            syncSimulatorHeader();
        }
    })
    .catch(err => {
        console.warn('Cover async upload error, will fallback to standard form multipart on save.', err);
    });
}

function applyCoverPreset(url) {
    currentCoverImage = url;
    document.getElementById('coverImageUrlInput').value = url;
    setCoverPreview(url);
    syncSimulatorHeader();
}

function applyManualCoverUrl(url) {
    if (url) {
        currentCoverImage = url;
        document.getElementById('coverImageUrlInput').value = url;
        setCoverPreview(url);
    } else {
        removeCoverImage();
    }
}

function setCoverPreview(src) {
    document.getElementById('coverPreviewImg').src = src;
    document.getElementById('coverPreviewWrap').classList.remove('d-none');
    document.getElementById('coverPlaceholderContent').classList.add('d-none');
    currentCoverImage = src;
    syncSimulatorHeader();
}

function removeCoverImage() {
    currentCoverImage = '';
    document.getElementById('coverImageUrlInput').value = '';
    document.getElementById('cover_file_input').value = '';
    document.getElementById('manual_cover_url').value = '';
    document.getElementById('coverPreviewWrap').classList.add('d-none');
    document.getElementById('coverPlaceholderContent').classList.remove('d-none');
    syncSimulatorHeader();
}

// Logo Handlers
function handleLogoFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            setLogoPreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function applyManualLogoUrl(url) {
    if (url) {
        setLogoPreview(url);
    } else {
        removeLogoImage();
    }
}

function setLogoPreview(src) {
    document.getElementById('logoPreviewImg').src = src;
    document.getElementById('logoPreviewWrap').classList.remove('d-none');
    document.getElementById('logoPlaceholderContent').classList.add('d-none');
    document.getElementById('logoUrlInput').value = src;
    currentLogoImage = src;
    syncSimulatorHeader();
}

function removeLogoImage() {
    currentLogoImage = '';
    document.getElementById('logoUrlInput').value = '';
    document.getElementById('logo_file_input').value = '';
    document.getElementById('manual_logo_url').value = '';
    document.getElementById('logoPreviewWrap').classList.add('d-none');
    document.getElementById('logoPlaceholderContent').classList.remove('d-none');
    syncSimulatorHeader();
}

// Header Synchronizer
function syncSimulatorHeader() {
    const brandName = document.getElementById('brand_name').value || 'Mon Établissement';

    let brandTypeText = '';
    if (!document.getElementById('brandTypeCustomWrap').classList.contains('d-none')) {
        brandTypeText = document.getElementById('brand_type_custom').value || 'Établissement';
        document.getElementById('brand_type').value = brandTypeText;
    } else {
        const sel = document.getElementById('brand_type_select');
        brandTypeText = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text.split(' ')[1] || 'Restaurant' : 'Restaurant';
        document.getElementById('brand_type').value = sel.value;
    }

    const desc = document.getElementById('description').value || '';
    const whatsapp = document.getElementById('whatsapp').value.trim();
    const wifiSsid = document.getElementById('wifi_ssid').value.trim();
    const themeColor = document.getElementById('theme_color').value || '#ea580c';

    document.getElementById('simBrandName').textContent = brandName;
    document.getElementById('simBrandBadge').textContent = brandTypeText;
    document.getElementById('simDescription').textContent = desc;

    // Header Background: Cover Photo or Color Gradient
    const headerBg = document.getElementById('simHeaderBg');
    if (currentCoverImage) {
        headerBg.classList.add('has-cover');
        headerBg.style.background = `linear-gradient(180deg, rgba(15, 23, 42, 0.45) 0%, rgba(15, 23, 42, 0.90) 100%), url('${currentCoverImage}') center/cover no-repeat`;
    } else {
        headerBg.classList.remove('has-cover');
        headerBg.style.background = `linear-gradient(135deg, ${themeColor}, #0f172a)`;
    }

    // Logo Icon inside simulator
    const logoSlot = document.getElementById('simLogoIcon');
    if (currentLogoImage) {
        logoSlot.innerHTML = `<img src="${currentLogoImage}" alt="Logo" onerror="this.parentElement.innerHTML='<i class=\\'bi bi-cup-hot\\'></i>'">`;
    } else {
        logoSlot.innerHTML = `<i class="bi bi-cup-hot"></i>`;
    }

    // Action badges with text as before
    const wifiBadge = document.getElementById('simWifiBadge');
    const wifiText = document.getElementById('simWifiText');
    if (wifiBadge) {
        if (wifiSsid) {
            wifiBadge.style.display = 'inline-flex';
            if (wifiText) wifiText.textContent = wifiSsid;
        } else {
            wifiBadge.style.display = 'none';
        }
    }

    const phoneVal = document.getElementById('phone')?.value.trim();
    const phoneBadge = document.getElementById('simPhoneBadge');
    const phoneText = document.getElementById('simPhoneText');
    if (phoneBadge) {
        if (phoneVal) {
            phoneBadge.style.display = 'inline-flex';
            if (phoneText) phoneText.textContent = phoneVal;
        } else {
            phoneBadge.style.display = 'none';
        }
    }

    const instaVal = document.getElementById('instagram')?.value.trim();
    const instaBadge = document.getElementById('simInstaBadge');
    const instaText = document.getElementById('simInstaText');
    if (instaBadge) {
        if (instaVal) {
            instaBadge.style.display = 'inline-flex';
            if (instaText) instaText.textContent = instaVal;
        } else {
            instaBadge.style.display = 'none';
        }
    }

    // WhatsApp floating button (Official WhatsApp Green)
    const waFloat = document.getElementById('simFloatingWaBtn');
    if (whatsapp) {
        waFloat.style.display = 'flex';
        waFloat.href = `https://wa.me/${whatsapp.replace(/[^0-9]/g, '')}`;
    } else {
        waFloat.style.display = 'none';
    }
}

// Category Management
function addCustomCategory() {
    menuData.push({
        id: 'cat_' + Date.now(),
        name: 'Nouvelle Catégorie',
        description: '',
        items: []
    });
    renderCategoryBuilder();
}

function addSuggestedCategory(name, desc) {
    menuData.push({
        id: 'cat_' + Date.now(),
        name: name,
        description: desc,
        items: [
            {
                id: 'dish_' + Date.now(),
                name: 'Article 1',
                price: 45,
                original_price: null,
                description: 'Description des ingrédients et saveurs...',
                badge: 'Nouveau',
                image_url: '',
                is_available: true
            }
        ]
    });
    renderCategoryBuilder();
}

function deleteCategory(catIndex) {
    if (confirm('Supprimer cette catégorie et tous les plats associés ?')) {
        menuData.splice(catIndex, 1);
        renderCategoryBuilder();
    }
}

function updateCategoryField(catIndex, field, value) {
    if (menuData[catIndex]) {
        menuData[catIndex][field] = value;
        persistAndSync();
    }
}

// Item Management
function addItem(catIndex) {
    if (!menuData[catIndex].items) {
        menuData[catIndex].items = [];
    }
    menuData[catIndex].items.push({
        id: 'dish_' + Date.now(),
        name: 'Nouveau Plat',
        price: 50,
        original_price: null,
        description: '',
        badge: '',
        image_url: '',
        is_available: true
    });
    renderCategoryBuilder();
}

function deleteItem(catIndex, itemIndex) {
    if (menuData[catIndex] && menuData[catIndex].items) {
        menuData[catIndex].items.splice(itemIndex, 1);
        renderCategoryBuilder();
    }
}

function updateItemField(catIndex, itemIndex, field, value) {
    if (menuData[catIndex] && menuData[catIndex].items[itemIndex]) {
        if (field === 'price' || field === 'original_price') {
            value = value ? parseFloat(value) : null;
        } else if (field === 'is_available') {
            value = Boolean(value);
        }
        menuData[catIndex].items[itemIndex][field] = value;
        persistAndSync();
    }
}

function toggleItemStock(catIndex, itemIndex, isAvailable) {
    if (menuData[catIndex] && menuData[catIndex].items[itemIndex]) {
        menuData[catIndex].items[itemIndex].is_available = isAvailable;
        renderCategoryBuilder();
    }
}

// Item Dish Image Direct File Upload
function triggerDishUpload(catIndex, itemIndex) {
    const input = document.getElementById(`dish_file_${catIndex}_${itemIndex}`);
    if (input) input.click();
}

function handleDishImageSelect(catIndex, itemIndex, input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // 1. Instant local preview
        const reader = new FileReader();
        reader.onload = function(e) {
            if (menuData[catIndex] && menuData[catIndex].items[itemIndex]) {
                menuData[catIndex].items[itemIndex].image_url = e.target.result;
                persistAndSync();
                renderCategoryBuilder();
            }
        };
        reader.readAsDataURL(file);

        // 2. Upload asynchronously to server storage
        const formData = new FormData();
        formData.append('image', file);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('admin.menus.upload-item-image') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && menuData[catIndex] && menuData[catIndex].items[itemIndex]) {
                menuData[catIndex].items[itemIndex].image_url = data.url;
                persistAndSync();
                renderCategoryBuilder();
            }
        })
        .catch(err => {
            console.warn('Asynchronous dish image upload failed, keeping base64 for submission fallback.', err);
        });
    }
}

function removeDishImage(catIndex, itemIndex) {
    if (menuData[catIndex] && menuData[catIndex].items[itemIndex]) {
        menuData[catIndex].items[itemIndex].image_url = '';
        renderCategoryBuilder();
    }
}

// Persist & Live Update
function persistAndSync() {
    document.getElementById('categoriesJsonInput').value = JSON.stringify(menuData);
    syncSimulatorDishes();
}

// Category & Dishes Builder Renderer
function renderCategoryBuilder() {
    persistAndSync();
    const container = document.getElementById('categoryBuilderContainer');
    container.innerHTML = '';

    const currency = document.getElementById('currency').value || 'DH';

    if (menuData.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5 border rounded-3 bg-light">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <h6 class="fw-bold mt-2">Aucune catégorie pour le moment</h6>
                <p class="text-muted small">Cliquez sur une catégorie suggérée ci-dessus ou sur « ➕ Catégorie Manuelle ».</p>
            </div>
        `;
        return;
    }

    menuData.forEach((cat, catIdx) => {
        const catCard = document.createElement('div');
        catCard.className = 'studio-category-card p-3 p-md-4';

        let itemsHtml = '';
        if (cat.items && cat.items.length > 0) {
            cat.items.forEach((item, itemIdx) => {
                const isOut = item.is_available === false;
                const hasImg = Boolean(item.image_url);

                // Badges options with custom manual option
                let badgeOptionsHtml = proposedBadges.map(b => {
                    const selected = (item.badge === b.value) ? 'selected' : '';
                    return `<option value="${escapeHtml(b.value)}" ${selected}>${escapeHtml(b.label)}</option>`;
                }).join('');

                // If badge is a custom manual string not in proposed list, add it as selected
                const isCustomBadge = item.badge && !proposedBadges.some(b => b.value === item.badge);
                if (isCustomBadge) {
                    badgeOptionsHtml += `<option value="${escapeHtml(item.badge)}" selected>✨ ${escapeHtml(item.badge)} (Personnalisé)</option>`;
                }

                itemsHtml += `
                    <div class="studio-item-row ${isOut ? 'is-out-of-stock' : ''}">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Image Uploader & Preview -->
                            <div class="flex-shrink-0 pt-0.5">
                                <input type="file" id="dish_file_${catIdx}_${itemIdx}" class="d-none" accept="image/*" onchange="handleDishImageSelect(${catIdx}, ${itemIdx}, this)">
                                ${hasImg ? `
                                    <div class="position-relative d-inline-block">
                                        <img src="${item.image_url}" class="dish-img-thumb" alt="${escapeHtml(item.name)}" onclick="triggerDishUpload(${catIdx}, ${itemIdx})" title="Cliquer pour changer l'image">
                                        <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 rounded-circle p-0 shadow-xs" style="width: 18px; height: 18px; font-size: 10px; line-height: 1; transform: translate(25%, -25%);" onclick="removeDishImage(${catIdx}, ${itemIdx})" title="Supprimer la photo">×</button>
                                    </div>
                                ` : `
                                    <div class="dish-upload-trigger" onclick="triggerDishUpload(${catIdx}, ${itemIdx})" title="Téléverser une photo pour ce plat">
                                        <i class="bi bi-camera fs-6 mb-0.5"></i>
                                        <span>Photo</span>
                                    </div>
                                `}
                            </div>

                            <!-- Structured 2-Row Fields Grid -->
                            <div class="flex-grow-1 min-w-0">
                                <!-- Row 1: Name, Price, Promo, Badge, Delete -->
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control form-control-sm fw-semibold text-dark" value="${escapeHtml(item.name || '')}" placeholder="Nom du plat / produit" oninput="updateItemField(${catIdx}, ${itemIdx}, 'name', this.value)">
                                    </div>

                                    <div class="col-6 col-md-2">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.5" class="form-control form-control-sm font-mono fw-bold" value="${item.price ?? ''}" placeholder="Prix" oninput="updateItemField(${catIdx}, ${itemIdx}, 'price', this.value)">
                                            <span class="input-group-text bg-white small px-1.5">${currency}</span>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-2">
                                        <input type="number" step="0.5" class="form-control form-control-sm font-mono text-muted" value="${item.original_price ?? ''}" placeholder="Prix barré" oninput="updateItemField(${catIdx}, ${itemIdx}, 'original_price', this.value)">
                                    </div>

                                    <div class="col-9 col-md-3">
                                        <select class="form-select form-select-sm" onchange="onBadgeSelectChange(${catIdx}, ${itemIdx}, this)">
                                            ${badgeOptionsHtml}
                                            <option value="__custom__">✏️ Autre badge...</option>
                                        </select>
                                    </div>

                                    <div class="col-3 col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-link p-1 text-danger" title="Supprimer le plat" onclick="deleteItem(${catIdx}, ${itemIdx})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Row 2: Description, Manual Image URL, Stock Switch -->
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-6">
                                        <input type="text" class="form-control form-control-sm text-muted" value="${escapeHtml(item.description || '')}" placeholder="Ingrédients ou description (ex: Double steak smashé, cheddar...)" oninput="updateItemField(${catIdx}, ${itemIdx}, 'description', this.value)">
                                    </div>

                                    <div class="col-7 col-md-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted px-1.5"><i class="bi bi-link-45deg"></i></span>
                                            <input type="text" class="form-control form-control-sm font-mono" value="${escapeHtml(hasImg ? item.image_url : '')}" placeholder="URL photo manuelle..." oninput="updateItemField(${catIdx}, ${itemIdx}, 'image_url', this.value)">
                                        </div>
                                    </div>

                                    <div class="col-5 col-md-2 d-flex justify-content-end">
                                        <div class="form-check form-switch mb-0 d-flex align-items-center gap-1.5">
                                            <input class="form-check-input mt-0" type="checkbox" role="switch" id="stock_${catIdx}_${itemIdx}" ${item.is_available !== false ? 'checked' : ''} onchange="toggleItemStock(${catIdx}, ${itemIdx}, this.checked)">
                                            <label class="form-check-label text-muted fw-medium" for="stock_${catIdx}_${itemIdx}" style="font-size: 0.72rem;">${item.is_available !== false ? 'En stock' : 'Épuisé'}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            itemsHtml = `
                <div class="text-center py-3 bg-light rounded-3 text-muted small border border-dashed">
                    Aucun plat dans cette catégorie. Cliquez ci-dessous pour ajouter un premier plat.
                </div>
            `;
        }

        catCard.innerHTML = `
            <!-- Category Header -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <span class="badge bg-secondary-subtle text-secondary font-mono">${catIdx + 1}</span>
                    <input type="text" class="form-control fw-bold fs-6 text-dark border-0 bg-light" value="${escapeHtml(cat.name || '')}" placeholder="Nom de la catégorie (ex: Entrées, Burgers...)" oninput="updateCategoryField(${catIdx}, 'name', this.value)">
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" onclick="addItem(${catIdx})">
                        <i class="bi bi-plus-lg"></i>
                        <span>Ajouter Plat</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer la catégorie" onclick="deleteCategory(${catIdx})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <input type="text" class="form-control form-control-sm text-muted" value="${escapeHtml(cat.description || '')}" placeholder="Description facultative (ex: Pain brioché toasté minute, viandes fraîches...)" oninput="updateCategoryField(${catIdx}, 'description', this.value)">
            </div>

            <!-- Items List -->
            <div class="d-flex flex-column gap-2">
                ${itemsHtml}
            </div>
        `;

        container.appendChild(catCard);
    });
}

function onBadgeSelectChange(catIdx, itemIdx, select) {
    if (select.value === '__custom__') {
        const customBadge = prompt('Entrez votre badge culinaire personnalisé (ex: "Fait Maison", "48h Levain", "Bio Maroc") :');
        if (customBadge && customBadge.trim()) {
            updateItemField(catIdx, itemIdx, 'badge', customBadge.trim());
        } else {
            select.value = '';
            updateItemField(catIdx, itemIdx, 'badge', '');
        }
        renderCategoryBuilder();
    } else {
        updateItemField(catIdx, itemIdx, 'badge', select.value);
    }
}

function escapeHtml(string) {
    if (!string) return '';
    return String(string)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Synchronize Dishes & Categories in Smartphone Simulator according to Nav Style
function syncSimulatorDishes() {
    const pillsWrap = document.getElementById('simPillsNavWrap');
    const tabsWrap = document.getElementById('simTabsNavWrap');
    const gridWrap = document.getElementById('simGridNavWrap');
    const tabArrowsWrap = document.getElementById('simTabArrowsWrap');
    const floatingDrawerBtn = document.getElementById('simFloatingDrawerBtn');
    const navCategories = document.getElementById('simNavCategories');
    const tabsList = document.getElementById('simTabsList');
    const gridCardsContainer = document.getElementById('simGridCardsContainer');
    const drawerList = document.getElementById('simDrawerCategoriesList');
    const dishesContainer = document.getElementById('simDishesContainer');
    const currency = document.getElementById('currency').value || 'DH';

    // Visibility toggles according to active nav style (vertical_scroll, horizontal_scroll, pages)
    if (currentNavStyle === 'vertical_scroll' || currentNavStyle === 'pills') {
        pillsWrap.classList.remove('d-none');
        tabsWrap.classList.add('d-none');
        gridWrap.classList.add('d-none');
        tabArrowsWrap.classList.add('d-none');
        tabArrowsWrap.classList.remove('d-flex');
        floatingDrawerBtn.classList.add('d-none');
        dishesContainer.className = 'p-2.5 d-flex flex-column gap-3 flex-grow-1';
        dishesContainer.style.scrollSnapType = '';
    } else if (currentNavStyle === 'pages' || currentNavStyle === 'tabs') {
        pillsWrap.classList.add('d-none');
        tabsWrap.classList.remove('d-none');
        gridWrap.classList.add('d-none');
        tabArrowsWrap.classList.remove('d-none');
        tabArrowsWrap.classList.add('d-flex');
        floatingDrawerBtn.classList.add('d-none');
        dishesContainer.className = 'p-2.5 d-flex flex-column gap-3 flex-grow-1';
        dishesContainer.style.scrollSnapType = '';
    } else if (currentNavStyle === 'horizontal_scroll' || currentNavStyle === 'grid_drawer') {
        pillsWrap.classList.remove('d-none');
        tabsWrap.classList.add('d-none');
        gridWrap.classList.add('d-none');
        tabArrowsWrap.classList.add('d-none');
        tabArrowsWrap.classList.remove('d-flex');
        floatingDrawerBtn.classList.add('d-none');
        dishesContainer.className = 'p-2 d-flex flex-row overflow-x-auto gap-2.5 flex-grow-1';
        dishesContainer.style.scrollSnapType = 'x mandatory';
        dishesContainer.style.scrollbarWidth = 'none';
    }

    navCategories.innerHTML = '';
    tabsList.innerHTML = '';
    gridCardsContainer.innerHTML = '';
    drawerList.innerHTML = '';
    dishesContainer.innerHTML = '';

    if (menuData.length === 0) {
        dishesContainer.innerHTML = `
            <div class="text-center py-5 text-muted small w-100">
                <i class="bi bi-cup-hot fs-2 mb-2 d-block"></i>
                <span>Aucun article pour le moment.</span>
            </div>
        `;
        return;
    }

    if (activeCategoryTabIdx >= menuData.length) {
        activeCategoryTabIdx = 0;
    }

    // Populate Navigation Controls (Pills for vertical/horizontal, Pages for tabbed)
    menuData.forEach((cat, idx) => {
        // 1. Horizontal Pill (for vertical_scroll & horizontal_scroll)
        const pill = document.createElement('a');
        pill.href = '#sim_cat_' + idx;
        pill.className = `sim-cat-pill ${idx === activeCategoryTabIdx ? 'active' : ''}`;
        pill.textContent = cat.name || 'Catégorie ' + (idx + 1);
        pill.onclick = (e) => {
            e.preventDefault();
            activeCategoryTabIdx = idx;
            document.querySelectorAll('.sim-cat-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            const target = document.getElementById('sim_cat_' + idx);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        };
        navCategories.appendChild(pill);

        // 2. Page Tab Button (for pages mode)
        const tabBtn = document.createElement('button');
        tabBtn.type = 'button';
        tabBtn.className = `sim-tab-btn ${idx === activeCategoryTabIdx ? 'active' : ''}`;
        tabBtn.innerHTML = `<span class="page-pill">${idx + 1}</span><span class="text-truncate">${escapeHtml(cat.name || 'Page ' + (idx + 1))}</span>`;
        tabBtn.onclick = () => simSelectCategoryTab(idx);
        tabsList.appendChild(tabBtn);

        // 3. Grid Card
        const gridCol = document.createElement('div');
        gridCol.className = 'col-6';
        gridCol.innerHTML = `
            <div class="sim-grid-card ${idx === activeCategoryTabIdx ? 'active' : ''}" onclick="jumpToSimCategory(${idx})">
                <div class="fw-bold text-dark text-truncate small" style="font-size: 0.7rem;">${escapeHtml(cat.name || 'Catégorie')}</div>
                <span class="badge bg-white text-muted border" style="font-size: 0.58rem;">${cat.items ? cat.items.length : 0} plats</span>
            </div>
        `;
        gridCardsContainer.appendChild(gridCol);

        // 4. Drawer Category Item
        const drawerItem = document.createElement('div');
        drawerItem.className = 'd-flex align-items-center justify-content-between p-1.5 rounded-2 border bg-light cursor-pointer';
        drawerItem.style.cursor = 'pointer';
        drawerItem.innerHTML = `
            <span class="fw-bold text-dark small" style="font-size: 0.72rem;">${escapeHtml(cat.name || 'Catégorie')}</span>
            <span class="badge bg-white text-muted border" style="font-size: 0.58rem;">${cat.items ? cat.items.length : 0} plats</span>
        `;
        drawerItem.onclick = () => jumpToSimCategory(idx);
        drawerList.appendChild(drawerItem);
    });

    // Render Page Navigation Dots and Breadcrumbs (Pages mode)
    const topDots = document.getElementById('simPageDotsTop');
    const bottomDots = document.getElementById('simPageDotsBottom');
    if (topDots) topDots.innerHTML = '';
    if (bottomDots) bottomDots.innerHTML = '';

    menuData.forEach((_, dIdx) => {
        if (topDots) {
            const dot = document.createElement('span');
            dot.className = `sim-page-dot ${dIdx === activeCategoryTabIdx ? 'active' : ''}`;
            dot.title = `Page ${dIdx + 1}`;
            dot.onclick = () => simSelectCategoryTab(dIdx);
            topDots.appendChild(dot);
        }
        if (bottomDots) {
            const dot = document.createElement('span');
            dot.className = `sim-page-dot ${dIdx === activeCategoryTabIdx ? 'active' : ''}`;
            dot.onclick = () => simSelectCategoryTab(dIdx);
            bottomDots.appendChild(dot);
        }
    });

    const activeCatItemsCount = (menuData[activeCategoryTabIdx]?.items || []).length;
    const pageBreadcrumb = document.getElementById('simPageBreadcrumb');
    if (pageBreadcrumb) {
        pageBreadcrumb.textContent = `Page ${activeCategoryTabIdx + 1} sur ${menuData.length} • ${activeCatItemsCount} article${activeCatItemsCount > 1 ? 's' : ''}`;
    }

    // Update Bottom Tab Counter and Buttons
    const tabCounter = document.getElementById('simTabCounter');
    if (tabCounter) {
        tabCounter.textContent = `Page ${activeCategoryTabIdx + 1} / ${menuData.length}`;
    }

    const prevBtn = document.getElementById('simPrevPageBtn');
    const nextBtn = document.getElementById('simNextPageBtn');
    if (prevBtn) {
        if (activeCategoryTabIdx === 0) {
            prevBtn.style.opacity = '0.4';
            prevBtn.style.pointerEvents = 'none';
        } else {
            prevBtn.style.opacity = '1';
            prevBtn.style.pointerEvents = 'auto';
        }
    }
    if (nextBtn) {
        if (activeCategoryTabIdx === menuData.length - 1) {
            nextBtn.innerHTML = `<span>Recommencer</span><i class="bi bi-arrow-repeat"></i>`;
        } else {
            nextBtn.innerHTML = `<span>Suivant</span><i class="bi bi-chevron-right"></i>`;
        }
    }

    const simSearchQuery = (document.getElementById('simMenuSearch')?.value || '').trim().toLowerCase();
    const simMaxPriceRaw = document.getElementById('simMaxPrice')?.value;
    const simMaxPrice = (simMaxPriceRaw !== '' && simMaxPriceRaw !== undefined) ? parseFloat(simMaxPriceRaw) : null;
    const hasSimFilter = simSearchQuery !== '' || (simMaxPrice !== null && !isNaN(simMaxPrice));

    // Render Dishes List (All stacked if 'vertical_scroll', snap slides if 'horizontal_scroll', or single active page if 'pages')
    menuData.forEach((cat, idx) => {
        if (!hasSimFilter && (currentNavStyle === 'pages' || currentNavStyle === 'tabs') && idx !== activeCategoryTabIdx) {
            return; // Skip non-active categories in pages mode
        }

        const filteredItems = (cat.items || []).filter(dish => {
            const nameMatch = !simSearchQuery || ((dish.name || '') + ' ' + (dish.description || '')).toLowerCase().includes(simSearchQuery);
            const dishPrice = parseFloat(dish.price || '0');
            const priceMatch = (simMaxPrice === null || isNaN(simMaxPrice)) || dishPrice <= simMaxPrice;
            return nameMatch && priceMatch;
        });

        if (hasSimFilter && filteredItems.length === 0) {
            return;
        }

        const section = document.createElement('div');
        section.id = 'sim_cat_' + idx;
        if (currentNavStyle === 'horizontal_scroll' || currentNavStyle === 'grid_drawer') {
            section.className = 'card border rounded-3 p-2 bg-light-subtle shadow-xs flex-shrink-0';
            section.style.width = '88%';
            section.style.scrollSnapAlign = 'center';
            section.style.maxHeight = '420px';
            section.style.overflowY = 'auto';
        } else {
            section.className = 'mb-2';
        }

        if (currentNavStyle === 'pages' || currentNavStyle === 'tabs') {
            const hero = document.createElement('div');
            hero.className = 'sim-page-hero';
            const totalItems = cat.items ? cat.items.length : 0;
            hero.innerHTML = `
                <div class="d-flex align-items-center justify-content-between mb-1.5">
                    <span class="badge rounded-pill fw-bold" style="background: rgba(87, 54, 233, 0.12); color: var(--sim-accent); font-size: 0.6rem; letter-spacing: 0.05em; padding: 0.3em 0.65em;">
                        <i class="bi bi-file-earmark-text me-1"></i>PAGE ${idx + 1} SUR ${menuData.length}
                    </span>
                    <span class="badge bg-white border text-muted" style="font-size: 0.58rem; padding: 0.3em 0.55em;">
                        ${totalItems} article${totalItems > 1 ? 's' : ''}
                    </span>
                </div>
                <h6 class="fw-bolder mb-1" style="font-size: 0.9rem; color: #0f172a; letter-spacing: -0.01em;">
                    ${escapeHtml(cat.name || 'Catégorie')}
                </h6>
                ${cat.description ? `<p class="text-muted mb-0 small" style="font-size: 0.68rem; line-height: 1.35;">${escapeHtml(cat.description)}</p>` : ''}
            `;
            section.appendChild(hero);
        } else {
            const catTitle = document.createElement('div');
            catTitle.className = 'd-flex align-items-center justify-content-between mb-1.5';
            catTitle.innerHTML = `
                <span class="fw-bold text-dark text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.03em;">
                    ${escapeHtml(cat.name || 'Catégorie')}
                </span>
                <span class="badge bg-light text-muted" style="font-size: 0.6rem;">${cat.items ? cat.items.length : 0}</span>
            `;
            section.appendChild(catTitle);

            if (cat.description) {
                const catSub = document.createElement('p');
                catSub.className = 'text-muted small mb-2';
                catSub.style.fontSize = '0.68rem';
                catSub.textContent = cat.description;
                section.appendChild(catSub);
            }
        }

        const itemsWrap = document.createElement('div');
        itemsWrap.className = 'd-flex flex-column gap-2';

        if (filteredItems.length > 0) {
            filteredItems.forEach(dish => {
                const dishCard = document.createElement('div');
                dishCard.className = `sim-dish-card d-flex gap-2 align-items-center ${dish.is_available === false ? 'opacity-50' : ''}`;

                let imgHtml = '';
                if (dish.image_url) {
                    imgHtml = `<img src="${dish.image_url}" class="sim-dish-img" alt="${escapeHtml(dish.name)}" onerror="this.style.display='none'">`;
                }

                let badgeHtml = '';
                if (dish.badge) {
                    badgeHtml = `<span class="sim-badge-pill bg-warning-subtle text-warning-emphasis">${escapeHtml(dish.badge)}</span>`;
                }

                let stockHtml = '';
                if (dish.is_available === false) {
                    stockHtml = `<span class="badge bg-danger-subtle text-danger" style="font-size: 0.58rem;">Épuisé</span>`;
                }

                let promoHtml = '';
                if (dish.original_price) {
                    promoHtml = `<span class="text-muted text-decoration-line-through me-1" style="font-size: 0.7rem;">${parseFloat(dish.original_price).toFixed(2)}</span>`;
                }

                const itemBg = document.getElementById('item_bg_color')?.value || '#ffffff';
                const itemText = document.getElementById('item_text_color')?.value || '#1e293b';

                dishCard.style.backgroundColor = itemBg;
                dishCard.style.color = itemText;

                dishCard.innerHTML = `
                    ${imgHtml}
                    <div class="flex-grow-1 min-w-0" style="min-width: 0; overflow: hidden;">
                        <div class="d-flex align-items-start justify-content-between gap-1 mb-0.5">
                            <span class="fw-bold" style="font-size: 0.8rem; color: ${itemText}; word-break: break-word; overflow-wrap: anywhere; line-height: 1.25;">${escapeHtml(dish.name || 'Plat')}</span>
                            <span class="fw-bold text-primary font-mono flex-shrink-0" style="font-size: 0.82rem; white-space: nowrap;">
                                ${promoHtml}${dish.price ? parseFloat(dish.price).toFixed(2) : '0.00'} <small style="font-size: 0.62rem;">${currency}</small>
                            </span>
                        </div>
                        ${dish.description ? `<p class="small mb-1" style="font-size: 0.68rem; line-height: 1.25; color: ${itemText}; opacity: 0.82; word-break: break-word; overflow-wrap: anywhere; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${escapeHtml(dish.description)}</p>` : ''}
                        <div class="d-flex align-items-center gap-1">
                            ${badgeHtml}
                            ${stockHtml}
                        </div>
                    </div>
                `;
                itemsWrap.appendChild(dishCard);
            });
        } else {
            itemsWrap.innerHTML = `<div class="text-muted text-center py-2" style="font-size: 0.68rem;">Catégorie vide</div>`;
        }

        section.appendChild(itemsWrap);
        dishesContainer.appendChild(section);
    });

    // Safe spacer so floating WhatsApp button never hides the last dishes
    if (document.getElementById('whatsapp')?.value.trim()) {
        const waSpacer = document.createElement('div');
        waSpacer.style.height = '50px';
        waSpacer.style.flexShrink = '0';
        dishesContainer.appendChild(waSpacer);
    }
}

function clearMenu() {
    if (confirm('Voulez-vous réinitialiser tous les plats et catégories à zéro ?')) {
        menuData = [];
        removeCoverImage();
        removeLogoImage();
        renderCategoryBuilder();
    }
}

// Device Switcher Controls
function setSimDevice(device, btn) {
    const frame = document.getElementById('menuPhoneFrame');
    const island = document.getElementById('simDynamicIsland');
    document.querySelectorAll('.sim-device-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    frame.classList.remove('device-pixel', 'device-frameless');

    if (device === 'pixel') {
        frame.classList.add('device-pixel');
        island.style.display = 'none';
    } else if (device === 'frameless') {
        frame.classList.add('device-frameless');
        island.style.display = 'none';
    } else {
        island.style.display = 'flex';
    }
}

function setSimScale(scale, btn) {
    const frame = document.getElementById('menuPhoneFrame');
    frame.style.transform = `scale(${scale})`;
    btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPreset('fast_food');
});
</script>
@endpush
