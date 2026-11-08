@extends('layouts.app')

@section('title', 'Studio Menu & Catalogue — ' . ($digitalCard->full_name ?: $digitalCard->name))

@section('content')
<div class="container-fluid px-0">

    <!-- ======================================================================= -->
    <!-- TOP HERO COMMAND DOCK (Aligned with Dynamic QR & Card Studio CAD UI)    -->
    <!-- ======================================================================= -->
    <div class="card border-0 rounded-4 mb-4 studio-hero-card overflow-hidden">
        <div class="card-body p-3.5 p-lg-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="studio-hero-icon d-flex align-items-center justify-content-center rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">
                        <i class="fa-solid fa-utensils fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h2 class="fw-extrabold text-dark mb-0" style="font-size: 1.45rem; letter-spacing: -0.025em;">
                                Studio Menu & Catalogue Interactif
                            </h2>
                            <span class="badge rounded-pill font-mono studio-engine-badge d-inline-flex align-items-center gap-1.5">
                                <span class="status-dot-radar"></span>
                                <span>/c/{{ $digitalCard->slug }}/menu</span>
                            </span>
                        </div>
                        <p class="text-muted small mb-0">
                            Carte rattachée : <strong class="text-dark">{{ $digitalCard->full_name ?: $digitalCard->name }}</strong> &bull; Gérez vos catégories, articles, tarifs, photos et le mode de navigation client (Vertical, Onglets Horizontaux ou Pages Catalogue).
                        </p>
                    </div>
                </div>

                <!-- Right Action Buttons -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.digital-cards.edit', $digitalCard) }}" class="btn btn-studio-outline d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-arrow-left"></i>
                        <span>Retour à la Carte</span>
                    </a>
                    <a href="{{ route('digital-cards.public.menu', $digitalCard->slug) }}" target="_blank" class="btn btn-studio-outline text-primary border-primary-subtle d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>Ouvrir le Menu Public</span>
                    </a>
                    <button type="submit" form="menuStudioForm" class="btn btn-studio-primary text-white fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-check2-circle fs-6"></i>
                        <span>Enregistrer & Publier le Menu</span>
                    </button>
                </div>
            </div>

            <!-- Quick Starter Templates Bar (1-Click Menu Templates) -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3 mt-3 border-top" style="border-color: #f1f5f9 !important;">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="small fw-bold text-muted text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.04em;">
                        <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>Modèles de démarrage rapide :
                    </span>
                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-3 py-1 small fw-semibold" onclick="applyMenuStarterTemplate('restaurant')">
                        <i class="fa-solid fa-utensils text-warning me-1"></i>Menu Restaurant & Bistro
                    </button>
                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-3 py-1 small fw-semibold" onclick="applyMenuStarterTemplate('cafe')">
                        <i class="fa-solid fa-mug-hot text-danger me-1"></i>Coffee Shop & Lounge
                    </button>
                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-3 py-1 small fw-semibold" onclick="applyMenuStarterTemplate('brand')">
                        <i class="fa-solid fa-bag-shopping text-primary me-1"></i>Catalogue Produits / Marque
                    </button>
                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-3 py-1 small fw-semibold" onclick="applyMenuStarterTemplate('services')">
                        <i class="fa-solid fa-briefcase text-success me-1"></i>Grille Tarifaire & Services
                    </button>
                </div>

                <!-- Master Enable Switch -->
                <div class="form-check form-switch m-0 d-flex align-items-center gap-2 bg-light px-3 py-1.5 rounded-pill border">
                    <input class="form-check-input m-0 cursor-pointer" type="checkbox" role="switch" id="masterMenuEnabledSwitch" style="width: 2.4rem; height: 1.25rem;" {{ ($currentMenu['enabled'] ?? true) ? 'checked' : '' }} onchange="toggleMasterMenuEnabled(this.checked)">
                    <label class="form-check-label small fw-bold cursor-pointer {{ ($currentMenu['enabled'] ?? true) ? 'text-success' : 'text-muted' }}" for="masterMenuEnabledSwitch" id="masterMenuStatusBadge">
                        {{ ($currentMenu['enabled'] ?? true) ? 'Menu Actif sur la Carte' : 'Menu Désactivé' }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <form id="menuStudioForm" method="POST" action="{{ route('admin.digital-cards.menu.update', $digitalCard) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="menu_config" id="menu_config_json">

        <div class="row g-4 align-items-start">
            <!-- =================================================================== -->
            <!-- LEFT COLUMN (7 COLS): INTERACTIVE MENU STUDIO WORKSPACE             -->
            <!-- =================================================================== -->
            <div class="col-xl-7 col-lg-7">

                <!-- 1. NAVIGATION MODE & VISUAL DESIGN CARD -->
                <div class="card border-0 rounded-4 studio-card mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="studio-step-badge">1</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Mode de Navigation & Design du Menu</h6>
                                <small class="text-muted">Choisissez comment vos clients parcourent le menu (Vertical, Horizontal ou Pages Catalogue) et personnalisez son style.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Navigation Mode Visual Cards -->
                        <label class="studio-label mb-2">
                            <i class="fa-solid fa-compass text-primary me-1.5"></i>Expérience de Navigation Client
                        </label>
                        <div class="row g-3 mb-4 pb-3 border-bottom" id="navModeCardsContainer" style="border-color: #f1f5f9 !important;">
                            <div class="col-md-4">
                                <div class="nav-mode-card p-3 rounded-4 border cursor-pointer h-100" data-mode="vertical" onclick="setNavigationMode('vertical')">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #eef2ff; color: #4f46e5;">
                                            <i class="fa-solid fa-arrows-up-down"></i>
                                        </span>
                                        <span class="badge bg-light text-muted border font-mono" style="font-size: 0.65rem;">Continu</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">1. Défilement Vertical</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.35;">Toutes les catégories s'affichent de haut en bas avec une barre de filtres rapide.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="nav-mode-card p-3 rounded-4 border cursor-pointer h-100" data-mode="horizontal" onclick="setNavigationMode('horizontal')">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #fff7ed; color: #ea580c;">
                                            <i class="fa-solid fa-arrows-left-right"></i>
                                        </span>
                                        <span class="badge bg-light text-muted border font-mono" style="font-size: 0.65rem;">Onglets</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">2. Onglets Horizontaux</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.35;">Navigation latérale catégorie par catégorie comme une application mobile.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="nav-mode-card p-3 rounded-4 border cursor-pointer h-100" data-mode="catalog" onclick="setNavigationMode('catalog')">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #f0fdf4; color: #16a34a;">
                                            <i class="fa-solid fa-book-open"></i>
                                        </span>
                                        <span class="badge bg-light text-muted border font-mono" style="font-size: 0.65rem;">Livre / Pages</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">3. Pages Type Catalogue</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.35;">Chaque catégorie devient une page numérotée avec boutons Précédent / Suivant.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Titles, CTA Label, Currency & Layout -->
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="studio-label">Titre de la Page Menu / Catalogue</label>
                                <input type="text" id="studioMenuTitle" class="form-control studio-input" value="{{ $currentMenu['title'] ?? 'Notre Menu & Catalogue' }}" oninput="syncStudioSettings()">
                            </div>
                            <div class="col-md-5">
                                <label class="studio-label">Texte du Bouton sur la Carte Visite</label>
                                <input type="text" id="studioMenuBtnLabel" class="form-control studio-input" value="{{ $currentMenu['button_label'] ?? 'Consulter le Menu / Catalogue' }}" oninput="syncStudioSettings()">
                            </div>
                            <div class="col-md-2">
                                <label class="studio-label">Devise</label>
                                <select id="studioMenuCurrency" class="form-select studio-select font-mono fw-bold" onchange="syncStudioSettings()">
                                    @foreach(['MAD', 'EUR', 'USD', 'GBP', 'CAD', 'CHF', 'AED'] as $curr)
                                        <option value="{{ $curr }}" {{ ($currentMenu['currency'] ?? 'MAD') === $curr ? 'selected' : '' }}>{{ $curr }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="studio-label">Sous-titre d'accueil</label>
                                <input type="text" id="studioMenuSubtitle" class="form-control studio-input" value="{{ $currentMenu['subtitle'] ?? '' }}" oninput="syncStudioSettings()">
                            </div>

                            <div class="col-md-3">
                                <label class="studio-label">Disposition des Articles</label>
                                <select id="studioMenuItemLayout" class="form-select studio-select" onchange="syncStudioSettings()">
                                    <option value="grid" selected>Grille de Cartes (2 cartes par ligne - Recommandé)</option>
                                    <option value="list">Liste Détaillée (Restaurant / Services)</option>
                                    <option value="compact">Liste Compacte (Carte Cafétéria / Tarifs)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="studio-label">Thème Visuel du Menu</label>
                                <select id="studioMenuDesignPreset" class="form-select studio-select" onchange="syncStudioSettings()">
                                    <option value="card_theme">Identique à la Carte Visite</option>
                                    <option value="bistro_dark">Bistro Gourmet (Noir & Cuivre)</option>
                                    <option value="clean_light">Catalogue Clair & Minimaliste (Blanc)</option>
                                    <option value="luxury_gold">Prestige & Luxe (Noir & Or)</option>
                                    <option value="custom">Couleurs Personnalisées</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="studioCustomAccentWrap">
                                <label class="studio-label">Couleur d'Accent du Menu</label>
                                <div class="studio-color-capsule">
                                    <input type="color" id="studioAccentColor" class="studio-color-well" value="{{ $currentMenu['accent_color'] ?? '#ea580c' }}" oninput="syncStudioSettings()">
                                    <span class="small font-mono text-muted text-truncate" title="Boutons, prix et onglets actifs">Boutons, prix & onglets</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="studio-label">Options d'Affichage</label>
                                <div class="d-flex align-items-center gap-3 pt-1.5">
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input cursor-pointer" type="checkbox" id="studioShowSearch" onchange="syncStudioSettings()">
                                        <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="studioShowSearch">Recherche</label>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input cursor-pointer" type="checkbox" id="studioShowImages" onchange="syncStudioSettings()">
                                        <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="studioShowImages">Photos</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" id="studioCustomBgWrap" style="display: none;">
                                <label class="studio-label">Couleur de Fond Personnalisée</label>
                                <div class="studio-color-capsule">
                                    <input type="color" id="studioBgColor" class="studio-color-well" value="{{ $currentMenu['bg_color'] ?? '#0f172a' }}" oninput="syncStudioSettings()">
                                    <span class="small font-mono text-muted">Arrière-plan de la page menu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. TWO-PANE CATEGORY SIDEBAR + ACTIVE CATEGORY ITEMS WORKSPACE -->
                <div class="card border-0 rounded-4 studio-card overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="studio-step-badge">2</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Éditeur de Catégories (Pages) & Articles</h6>
                                <small class="text-muted">Sélectionnez une catégorie à gauche pour gérer ses plats, produits ou tarifs à droite.</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-studio-primary text-white rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="addStudioCategory()">
                            <i class="fa-solid fa-folder-plus"></i>
                            <span>Nouvelle Catégorie / Page</span>
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="row g-0" style="min-height: 520px;">
                            <!-- Left Mini Sidebar: Categories / Pages List -->
                            <div class="col-md-4 border-end bg-light bg-opacity-50 p-3 d-flex flex-column justify-content-between" style="border-color: #f1f5f9 !important;">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                                        <span class="small fw-bold text-muted text-uppercase font-mono" style="font-size: 0.68rem;">Catégories / Pages</span>
                                        <span class="badge bg-white text-dark border font-mono small" id="sidebarCatCountBadge">0</span>
                                    </div>
                                    <div id="studioCategorySidebarList" class="d-flex flex-column gap-1.5">
                                        <!-- Populated by JS -->
                                    </div>
                                </div>

                                <div class="pt-3 mt-3 border-top text-center" style="border-color: #e2e8f0 !important;">
                                    <button type="button" class="btn btn-sm btn-white border w-100 rounded-3 fw-semibold text-primary shadow-2xs" onclick="addStudioCategory()">
                                        <i class="fa-solid fa-plus me-1"></i> Ajouter une Catégorie
                                    </button>
                                </div>
                            </div>

                            <!-- Right Active Category Editor & Items List -->
                            <div class="col-md-8 p-4 bg-white">
                                <div id="activeCategoryWorkspace">
                                    <!-- Populated dynamically by JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- =================================================================== -->
            <!-- RIGHT COLUMN (5 COLS): STICKY INTERACTIVE MENU PHONE SIMULATOR      -->
            <!-- =================================================================== -->
            <div class="col-xl-5 col-lg-5" style="align-self: stretch;">
                <div class="sticky-preview-wrapper position-sticky" style="top: 85px; z-index: 40;">
                    <div class="card border-0 rounded-4 studio-card overflow-hidden">
                        <div class="card-header bg-white border-bottom py-2.5 px-3.5 d-flex justify-content-between align-items-center" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="status-dot-radar"></span>
                                <span class="small fw-bold text-dark font-mono text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                                    <i class="fa-solid fa-mobile-screen-button text-primary me-1"></i>Simulateur Menu Interactif
                                </span>
                            </div>
                            <span class="badge bg-primary-subtle text-primary font-mono small" id="simModeBadgeLabel" style="font-size: 0.68rem;">Mode Vertical</span>
                        </div>

                        <div class="card-body p-4 studio-canvas-stage d-flex flex-column align-items-center justify-content-center">
                            <div class="phone-frame-mockup mx-auto shadow-2xl">
                                <div class="phone-dynamic-island">
                                    <div class="island-camera"></div>
                                    <div class="island-speaker"></div>
                                </div>

                                <!-- Dedicated Interactive Menu Phone Screen -->
                                <div class="phone-screen-content" id="studioMenuPhoneScreen">
                                    <!-- Status Bar -->
                                    <div class="phone-status-bar d-flex justify-content-between align-items-center px-4 pt-2">
                                        <span class="font-mono small fw-bold" style="font-size: 0.72rem;">09:41</span>
                                        <div class="d-flex align-items-center gap-1.5 small">
                                            <i class="bi bi-reception-4" style="font-size: 0.72rem;"></i>
                                            <i class="bi bi-wifi" style="font-size: 0.72rem;"></i>
                                            <i class="bi bi-battery-full" style="font-size: 0.85rem;"></i>
                                        </div>
                                    </div>

                                    <!-- Top Navigation Bar inside Phone -->
                                    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="border-color: rgba(148,163,184,0.18) !important;">
                                        <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: rgba(148,163,184,0.16); color: inherit; font-size: 0.66rem;">
                                            <i class="fa-solid fa-arrow-left me-1"></i>Retour Carte
                                        </span>
                                        <div class="d-inline-flex rounded-pill p-0.5" style="background: rgba(148,163,184,0.15); border: 1px solid rgba(148,163,184,0.2);">
                                            <button type="button" class="sim-top-nav-pill" data-mode="vertical" onclick="setNavigationMode('vertical')" title="Défilement Vertical"><i class="fa-solid fa-arrows-up-down"></i></button>
                                            <button type="button" class="sim-top-nav-pill" data-mode="horizontal" onclick="setNavigationMode('horizontal')" title="Onglets Horizontaux"><i class="fa-solid fa-arrows-left-right"></i></button>
                                            <button type="button" class="sim-top-nav-pill" data-mode="catalog" onclick="setNavigationMode('catalog')" title="Pages Catalogue"><i class="fa-solid fa-book-open"></i></button>
                                        </div>
                                    </div>

                                    <!-- Menu Hero inside Phone -->
                                    <div class="p-3 border-bottom" id="simHeroBox" style="border-color: rgba(148,163,184,0.18) !important;">
                                        <div class="small text-uppercase fw-bold opacity-75 font-mono" style="font-size: 0.62rem;">{{ $digitalCard->company ?: ($digitalCard->full_name ?: $digitalCard->name) }}</div>
                                        <h6 class="fw-extrabold mb-0.5" id="simLiveTitle" style="font-size: 0.98rem;">Notre Menu & Catalogue</h6>
                                        <div class="opacity-75" id="simLiveSubtitle" style="font-size: 0.7rem;"></div>

                                        <div id="simSearchBoxWrap" class="mt-2 position-relative">
                                            <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-2.5 opacity-50" style="font-size: 0.7rem;"></i>
                                            <input type="text" id="simSearchInput" class="form-control form-control-sm ps-4 py-1" style="background: rgba(148,163,184,0.12); border-color: rgba(148,163,184,0.24); color: inherit; border-radius: 10px; font-size: 0.72rem;" placeholder="Rechercher dans le menu..." oninput="renderPhoneSimulator()">
                                        </div>
                                    </div>

                                    <!-- Sticky Category Tabs / Page Indicator -->
                                    <div class="px-3 py-2 border-bottom d-flex align-items-center gap-1.5 overflow-x-auto" id="simTabsBar" style="border-color: rgba(148,163,184,0.16) !important; scrollbar-width: none;"></div>

                                    <!-- Menu Items Container -->
                                    <div class="p-3 flex-grow-1" id="simItemsBody"></div>

                                    <!-- Catalog Pager Footer inside Phone -->
                                    <div id="simCatalogFooter" class="px-3 py-2 border-top d-flex align-items-center justify-content-between" style="display: none !important; border-color: rgba(148,163,184,0.2) !important; background: rgba(15,23,42,0.9); color: #fff;">
                                        <button type="button" class="btn btn-xs text-white rounded-pill px-2.5 py-1 font-mono" style="background: rgba(255,255,255,0.14); font-size: 0.65rem;" id="simPrevPageBtn" onclick="stepSimCatalogPage(-1)">
                                            <i class="fa-solid fa-chevron-left me-1"></i>Précédent
                                        </button>
                                        <span class="font-mono fw-bold" style="font-size: 0.68rem;" id="simCatalogPageIndicator">Page 1 / 1</span>
                                        <button type="button" class="btn btn-xs text-white rounded-pill px-2.5 py-1 font-mono" style="background: var(--sim-accent, #ea580c); font-size: 0.65rem;" id="simNextPageBtn" onclick="stepSimCatalogPage(1)">
                                            Suivant<i class="fa-solid fa-chevron-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.cursor-pointer { cursor: pointer; }
.studio-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.05);
}
.studio-hero-icon { width: 48px; height: 48px; flex-shrink: 0; }
.studio-engine-badge {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #e2e8f0;
    padding: 0.3rem 0.75rem;
    font-size: 0.72rem;
}
.status-dot-radar {
    width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}
.btn-studio-primary {
    background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);
    border: none;
    border-radius: 10px;
    padding: 0.55rem 1.25rem;
    font-size: 0.86rem;
}
.btn-studio-outline {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    border-radius: 10px;
    padding: 0.52rem 1rem;
    font-size: 0.84rem;
    font-weight: 600;
}
.btn-studio-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
.studio-card {
    background: #ffffff;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 2px 12px -2px rgba(15, 23, 42, 0.04);
}
.studio-step-badge {
    width: 28px; height: 28px; border-radius: 8px;
    background: #eef2ff; color: #4f46e5;
    display: inline-flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.8rem; font-family: monospace;
}
.studio-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.35rem;
    display: block;
}
.studio-input, .studio-select {
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
}
.studio-input:focus, .studio-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
}
.nav-mode-card {
    background: #ffffff;
    border-color: #e2e8f0 !important;
    transition: all 0.18s ease;
}
.nav-mode-card:hover {
    border-color: #4f46e5 !important;
    transform: translateY(-2px);
}
.nav-mode-card.active {
    border-color: #4f46e5 !important;
    background: #f5f3ff !important;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.22);
}
.cat-sidebar-item {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    border-radius: 11px;
    padding: 0.6rem 0.75rem;
    cursor: pointer;
    transition: all 0.15s ease;
}
.cat-sidebar-item:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.cat-sidebar-item.active {
    border-color: #4f46e5;
    background: #eef2ff;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.12);
}
.studio-canvas-stage {
    background-color: #f8fafc;
    background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
    background-size: 18px 18px;
}
.phone-frame-mockup {
    width: 345px;
    height: 670px;
    background: #0f172a;
    border-radius: 46px;
    border: 10px solid #1e293b;
    box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.4);
    position: relative;
    overflow: hidden;
}
.phone-dynamic-island {
    position: absolute; top: 8px; left: 50%; transform: translateX(-50%);
    width: 90px; height: 20px; background: #000; border-radius: 20px; z-index: 100;
}
.phone-screen-content {
    --sim-accent: #4f46e5;
    --sim-bg: #0f172a;
    width: 100%; height: 100%;
    overflow-y: auto; overflow-x: hidden;
    background: var(--sim-bg);
    color: #ffffff;
    display: flex; flex-direction: column;
    scrollbar-width: none;
}
.phone-screen-content::-webkit-scrollbar { display: none; }
.sim-top-nav-pill {
    border: none; background: transparent; color: inherit; opacity: 0.65;
    border-radius: 99px; padding: 2px 8px; font-size: 0.66rem; cursor: pointer;
}
.sim-top-nav-pill.active {
    background: var(--sim-accent); color: #ffffff; opacity: 1;
}
.sim-cat-pill {
    border: 1px solid rgba(148,163,184,0.25);
    background: rgba(148,163,184,0.1);
    color: inherit;
    border-radius: 999px;
    padding: 0.25rem 0.7rem;
    font-size: 0.68rem;
    font-weight: 700;
    white-space: nowrap;
    cursor: pointer;
}
.sim-cat-pill.active {
    background: var(--sim-accent);
    border-color: var(--sim-accent);
    color: #ffffff;
}

/* Fractional Spacing & Pro Utility System */
.gap-1\.5 { gap: 0.375rem !important; }
.gap-2\.5 { gap: 0.625rem !important; }
.gap-3\.5 { gap: 0.875rem !important; }
.g-2\.5, .gx-2\.5 { --bs-gutter-x: 0.625rem !important; }
.g-2\.5, .gy-2\.5 { --bs-gutter-y: 0.625rem !important; }
.g-3\.5, .gx-3\.5 { --bs-gutter-x: 0.875rem !important; }
.g-3\.5, .gy-3\.5 { --bs-gutter-y: 0.875rem !important; }
.p-0\.5 { padding: 0.125rem !important; }
.p-1\.5 { padding: 0.375rem !important; }
.p-2\.5 { padding: 0.625rem !important; }
.p-3\.5 { padding: 0.875rem !important; }
.px-1\.5 { padding-left: 0.375rem !important; padding-right: 0.375rem !important; }
.px-2\.5 { padding-left: 0.625rem !important; padding-right: 0.625rem !important; }
.px-3\.5 { padding-left: 0.875rem !important; padding-right: 0.875rem !important; }
.py-0\.5 { padding-top: 0.125rem !important; padding-bottom: 0.125rem !important; }
.py-1\.5 { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
.py-2\.5 { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
.m-1\.5 { margin: 0.375rem !important; }
.mb-0\.5 { margin-bottom: 0.125rem !important; }
.mb-1\.5 { margin-bottom: 0.375rem !important; }
.mb-2\.5 { margin-bottom: 0.625rem !important; }
.mb-3\.5 { margin-bottom: 0.875rem !important; }
.mt-0\.5 { margin-top: 0.125rem !important; }
.mt-1\.5 { margin-top: 0.375rem !important; }
.mt-2\.5 { margin-top: 0.625rem !important; }
.mt-3\.5 { margin-top: 0.875rem !important; }
.me-0\.5 { margin-right: 0.125rem !important; }
.me-1\.5 { margin-right: 0.375rem !important; }
.ms-0\.5 { margin-left: 0.125rem !important; }
.ms-1\.5 { margin-left: 0.375rem !important; }
.ms-2\.5 { margin-left: 0.625rem !important; }
.font-mono { font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important; }
.fw-extrabold { font-weight: 800 !important; }
.min-w-0 { min-width: 0 !important; }
.border-dashed { border-style: dashed !important; }
.opacity-40 { opacity: 0.4 !important; }
.opacity-60 { opacity: 0.6 !important; }
.opacity-70 { opacity: 0.7 !important; }
.opacity-80 { opacity: 0.8 !important; }
.opacity-90 { opacity: 0.9 !important; }
.btn-xs {
    padding: 0.22rem 0.55rem;
    font-size: 0.72rem;
    line-height: 1.3;
    border-radius: 6px;
}
.btn-white {
    background-color: #ffffff;
    color: #334155;
}
.btn-white:hover {
    background-color: #f8fafc;
    color: #0f172a;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cardTheme = @json($currentTheme);
    let currentMenu = @json($currentMenu);
    const menuJsonInput = document.getElementById('menu_config_json');

    if (!Array.isArray(currentMenu.categories) || currentMenu.categories.length === 0) {
        currentMenu.categories = [
            {
                id: 'cat_1',
                name: 'Sélection Signature',
                icon: 'fa-solid fa-star',
                description: 'Nos incontournables préparés avec soin.',
                cover_image: 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1000&auto=format&fit=crop&q=80',
                items: [
                    {
                        id: 'itm_1',
                        name: 'Formule Exécutive',
                        description: 'Service complet sur mesure avec accompagnement.',
                        price: '250',
                        badge: 'Populaire',
                        image_url: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&auto=format&fit=crop&q=80',
                        available: true
                    }
                ]
            }
        ];
    }

    let selectedCatIndex = 0;
    let simActiveCatId = 'all';
    let simCatalogPageIdx = 0;

    // Helper: Upload Image via AJAX to /admin/digital-cards/upload-image
    window.uploadMenuFile = function(file, onSuccess, onLoading) {
        if (!file) return;
        if (onLoading) onLoading(true);
        const formData = new FormData();
        formData.append('image', file);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        fetch('{{ route("admin.digital-cards.upload-image") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (onLoading) onLoading(false);
            if (res.success && res.url) {
                onSuccess(res.url);
            } else {
                alert(res.message || 'Erreur lors du téléversement de l\'image.');
            }
        })
        .catch(err => {
            if (onLoading) onLoading(false);
            console.error(err);
            alert('Erreur réseau lors du téléversement.');
        });
    };

    // 1-Click Starter Templates with High Quality Food & Retail Imagery
    const starterTemplates = {
        restaurant: {
            title: 'Carte Gastronomique & Signatures',
            button_label: 'Consulter le Menu du Restaurant',
            subtitle: 'Tous nos plats sont cuisinés minute avec des produits frais du marché.',
            navigation_mode: 'vertical',
            item_layout: 'grid',
            design_preset: 'bistro_dark',
            accent_color: '#ea580c',
            categories: [
                {
                    id: 'cat_entrees', name: 'Entrées & Tapas', icon: 'fa-solid fa-bowl-food', description: 'Pour bien commencer votre repas',
                    cover_image: 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'i_1', name: 'Burrata Crémeuse aux Truffes', description: 'Tomates anciennes, basilic frais & huile de truffe.', price: '110', badge: 'Signature', image_url: 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?w=600&auto=format&fit=crop&q=80', available: true },
                        { id: 'i_2', name: 'Carpaccio de Boeuf Mariné', description: 'Copeaux de parmesan 24 mois, câpres et roquette.', price: '95', badge: 'Frais', image_url: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_plats', name: 'Plats du Chef', icon: 'fa-solid fa-utensils', description: 'Nos spécialités cuites au feu de bois',
                    cover_image: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'i_3', name: 'Filet de Boeuf Rossini', description: 'Jus corsé aux morilles et mousseline truffée.', price: '240', badge: 'Best-seller', image_url: 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&auto=format&fit=crop&q=80', available: true },
                        { id: 'i_4', name: 'Pavé de Saumon Grillé', description: 'Légumes croquants de saison et sauce vierge.', price: '175', badge: 'Healthy', image_url: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_desserts', name: 'Douceurs & Boissons', icon: 'fa-solid fa-ice-cream', description: 'Créations pâtissières & cocktails',
                    cover_image: 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'i_5', name: 'Fondant Chocolat Valrhona', description: 'Coeur coulant praliné & glace vanille Bourbon.', price: '75', badge: 'Gourmand', image_url: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                }
            ]
        },
        cafe: {
            title: 'Carte Coffee Shop & Brunch',
            button_label: 'Voir la Carte Café & Brunch',
            subtitle: 'Torréfaction artisanale, viennoiseries pur beurre et jus pressés à froid.',
            navigation_mode: 'horizontal',
            item_layout: 'grid',
            design_preset: 'clean_light',
            accent_color: '#4f46e5',
            categories: [
                {
                    id: 'cat_coffee', name: 'Cafés & Lattes', icon: 'fa-solid fa-mug-hot', description: 'Arabica 100% de spécialité',
                    cover_image: 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'c_1', name: 'Flat White Pistache', description: 'Double shot espresso & lait velouté pistache.', price: '42', badge: 'Nouveau', image_url: 'https://images.unsplash.com/photo-1577968897966-3d4325b36b61?w=600&auto=format&fit=crop&q=80', available: true },
                        { id: 'c_2', name: 'Iced Caramel Macchiato', description: 'Glace pilée, vanille et caramel beurre salé.', price: '38', badge: 'Populaire', image_url: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_brunch', name: 'Brunch & Salé', icon: 'fa-solid fa-bread-slice', description: 'Servi tous les jours jusqu\'à 16h',
                    cover_image: 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'c_3', name: 'Avocado Toast Saumon', description: 'Pain au levain grillé, oeuf poché bio et graines.', price: '85', badge: 'Brunch', image_url: 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                }
            ]
        },
        brand: {
            title: 'Lookbook & Catalogue Collection',
            button_label: 'Parcourir le Catalogue Produits',
            subtitle: 'Feuilletez nos pages produits et éditions limitées.',
            navigation_mode: 'catalog',
            item_layout: 'grid',
            design_preset: 'luxury_gold',
            accent_color: '#d4af37',
            categories: [
                {
                    id: 'cat_col1', name: 'Collection Prestige', icon: 'fa-solid fa-gem', description: 'Pièces iconiques et séries limitées',
                    cover_image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'b_1', name: 'Coffret Signature Or', description: 'Finitions artisanales et écrin numéroté.', price: '1290', badge: 'Édition Limitée', image_url: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80', available: true },
                        { id: 'b_2', name: 'Essence Royale 100ml', description: 'Notes boisées d\'oud, ambre et bergamote.', price: '890', badge: 'Exclusif', image_url: 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_col2', name: 'Accessoires & Maroquinerie', icon: 'fa-solid fa-bag-shopping', description: 'Idées cadeaux et essentiels',
                    cover_image: 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'b_3', name: 'Pochette Cuir Pleine Fleur', description: 'Tannage végétal et marquage à chaud.', price: '650', badge: 'Nouveau', image_url: 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                }
            ]
        },
        services: {
            title: 'Grille Tarifaire & Prestations',
            button_label: 'Consulter nos Forfaits & Tarifs',
            subtitle: 'Des solutions transparentes adaptées à vos objectifs professionnels.',
            navigation_mode: 'vertical',
            item_layout: 'grid',
            design_preset: 'card_theme',
            accent_color: '#0284c7',
            categories: [
                {
                    id: 'cat_pack', name: 'Forfaits Clés en Main', icon: 'fa-solid fa-briefcase', description: 'Accompagnement complet',
                    cover_image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1000&auto=format&fit=crop&q=80',
                    items: [
                        { id: 's_1', name: 'Pack Digital Starter', description: 'Mise en place complète, configuration NFC & support.', price: '990', badge: 'Essentiel', image_url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&auto=format&fit=crop&q=80', available: true },
                        { id: 's_2', name: 'Pack Croissance Entreprise', description: 'Déploiement multi-équipes, analytique avancée & SLA.', price: '2490', badge: 'Recommandé', image_url: 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=600&auto=format&fit=crop&q=80', available: true }
                    ]
                }
            ]
        }
    };

    window.applyMenuStarterTemplate = function(key) {
        const tpl = starterTemplates[key];
        if (!tpl) return;
        currentMenu.enabled = true;
        currentMenu.title = tpl.title;
        currentMenu.button_label = tpl.button_label;
        currentMenu.subtitle = tpl.subtitle;
        currentMenu.navigation_mode = tpl.navigation_mode;
        currentMenu.item_layout = tpl.item_layout;
        currentMenu.design_preset = tpl.design_preset;
        currentMenu.accent_color = tpl.accent_color || cardTheme.primary_color || '#ea580c';
        currentMenu.categories = JSON.parse(JSON.stringify(tpl.categories));
        selectedCatIndex = 0;
        simCatalogPageIdx = 0;
        populateFormFromState();
        renderAll();
    };

    window.toggleMasterMenuEnabled = function(isEnabled) {
        currentMenu.enabled = Boolean(isEnabled);
        const badge = document.getElementById('masterMenuStatusBadge');
        if (badge) {
            badge.textContent = currentMenu.enabled ? 'Menu Actif sur la Carte' : 'Menu Désactivé';
            badge.className = 'form-check-label small fw-bold cursor-pointer ' + (currentMenu.enabled ? 'text-success' : 'text-muted');
        }
        syncHiddenInput();
    };

    window.setNavigationMode = function(mode) {
        currentMenu.navigation_mode = mode || 'vertical';
        document.querySelectorAll('#navModeCardsContainer .nav-mode-card').forEach(card => {
            card.classList.toggle('active', card.dataset.mode === currentMenu.navigation_mode);
        });
        document.querySelectorAll('.sim-top-nav-pill').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.mode === currentMenu.navigation_mode);
        });

        const modeLabel = document.getElementById('simModeBadgeLabel');
        if (modeLabel) {
            modeLabel.textContent = currentMenu.navigation_mode === 'catalog'
                ? 'Mode Pages Catalogue'
                : (currentMenu.navigation_mode === 'horizontal' ? 'Mode Onglets Horizontaux' : 'Mode Défilement Vertical');
        }

        if (currentMenu.navigation_mode === 'horizontal') {
            simActiveCatId = currentMenu.categories?.[0]?.id || 'all';
        } else if (currentMenu.navigation_mode === 'vertical') {
            simActiveCatId = 'all';
        }
        renderPhoneSimulator();
        syncHiddenInput();
    };

    window.syncStudioSettings = function() {
        currentMenu.title = document.getElementById('studioMenuTitle')?.value || '';
        currentMenu.button_label = document.getElementById('studioMenuBtnLabel')?.value || '';
        currentMenu.currency = document.getElementById('studioMenuCurrency')?.value || 'MAD';
        currentMenu.subtitle = document.getElementById('studioMenuSubtitle')?.value || '';
        currentMenu.item_layout = document.getElementById('studioMenuItemLayout')?.value || 'grid';
        const presetEl = document.getElementById('studioMenuDesignPreset');
        if (presetEl) {
            const oldPreset = currentMenu.design_preset;
            currentMenu.design_preset = presetEl.value;
            if (oldPreset !== currentMenu.design_preset) {
                const accentInp = document.getElementById('studioAccentColor');
                const bgInp = document.getElementById('studioBgColor');
                if (currentMenu.design_preset === 'bistro_dark') {
                    if (accentInp) { accentInp.value = '#ea580c'; currentMenu.accent_color = '#ea580c'; }
                    if (bgInp) { bgInp.value = '#0c0a09'; currentMenu.bg_color = '#0c0a09'; }
                } else if (currentMenu.design_preset === 'luxury_gold') {
                    if (accentInp) { accentInp.value = '#d4af37'; currentMenu.accent_color = '#d4af37'; }
                    if (bgInp) { bgInp.value = '#090d16'; currentMenu.bg_color = '#090d16'; }
                } else if (currentMenu.design_preset === 'clean_light') {
                    if (accentInp) { accentInp.value = '#4f46e5'; currentMenu.accent_color = '#4f46e5'; }
                    if (bgInp) { bgInp.value = '#f8fafc'; currentMenu.bg_color = '#f8fafc'; }
                } else if (currentMenu.design_preset === 'card_theme') {
                    const col = cardTheme.primary_color || '#4f46e5';
                    if (accentInp) { accentInp.value = col; currentMenu.accent_color = col; }
                }
            }
        }
        currentMenu.show_search = Boolean(document.getElementById('studioShowSearch')?.checked);
        currentMenu.show_images = Boolean(document.getElementById('studioShowImages')?.checked);
        currentMenu.accent_color = document.getElementById('studioAccentColor')?.value || '#ea580c';
        currentMenu.bg_color = document.getElementById('studioBgColor')?.value || '#0f172a';

        const isCustom = (currentMenu.design_preset === 'custom');
        const bgWrap = document.getElementById('studioCustomBgWrap');
        if (bgWrap) bgWrap.style.display = isCustom ? 'block' : 'none';

        renderActiveCategoryWorkspace();
        renderPhoneSimulator();
        syncHiddenInput();
    };

    function populateFormFromState() {
        document.getElementById('masterMenuEnabledSwitch').checked = Boolean(currentMenu.enabled);
        document.getElementById('studioMenuTitle').value = currentMenu.title || '';
        document.getElementById('studioMenuBtnLabel').value = currentMenu.button_label || '';
        document.getElementById('studioMenuCurrency').value = currentMenu.currency || 'MAD';
        document.getElementById('studioMenuSubtitle').value = currentMenu.subtitle || '';
        document.getElementById('studioMenuItemLayout').value = currentMenu.item_layout || 'grid';
        document.getElementById('studioMenuDesignPreset').value = currentMenu.design_preset || 'card_theme';
        document.getElementById('studioShowSearch').checked = (currentMenu.show_search !== false);
        document.getElementById('studioShowImages').checked = (currentMenu.show_images !== false);
        document.getElementById('studioAccentColor').value = currentMenu.accent_color || '#ea580c';
        document.getElementById('studioBgColor').value = currentMenu.bg_color || '#0f172a';

        const isCustom = (currentMenu.design_preset === 'custom');
        const bgWrap = document.getElementById('studioCustomBgWrap');
        if (bgWrap) bgWrap.style.display = isCustom ? 'block' : 'none';

        window.setNavigationMode(currentMenu.navigation_mode || 'vertical');
    }

    // Category Management
    window.selectStudioCategory = function(idx) {
        selectedCatIndex = idx;
        if (currentMenu.navigation_mode === 'catalog') {
            simCatalogPageIdx = idx;
        } else if (currentMenu.navigation_mode === 'horizontal') {
            simActiveCatId = currentMenu.categories[idx]?.id || 'all';
        }
        renderAll();
    };

    window.addStudioCategory = function() {
        const newId = 'cat_' + Math.random().toString(36).substring(2, 8);
        currentMenu.categories.push({
            id: newId,
            name: 'Nouvelle Catégorie',
            icon: 'fa-solid fa-utensils',
            description: '',
            cover_image: '',
            items: [
                {
                    id: 'itm_' + Math.random().toString(36).substring(2, 8),
                    name: 'Nouvel Article',
                    description: 'Description détaillée...',
                    price: '75',
                    badge: '',
                    image_url: '',
                    available: true
                }
            ]
        });
        selectedCatIndex = currentMenu.categories.length - 1;
        renderAll();
    };

    window.moveStudioCategory = function(idx, delta, evt) {
        if (evt) evt.stopPropagation();
        const cats = currentMenu.categories;
        const target = idx + delta;
        if (target < 0 || target >= cats.length) return;
        const tmp = cats[idx];
        cats[idx] = cats[target];
        cats[target] = tmp;
        selectedCatIndex = target;
        renderAll();
    };

    window.deleteStudioCategory = function(idx, evt) {
        if (evt) evt.stopPropagation();
        if (currentMenu.categories.length <= 1) {
            alert('Votre menu doit conserver au moins une catégorie.');
            return;
        }
        currentMenu.categories.splice(idx, 1);
        if (selectedCatIndex >= currentMenu.categories.length) {
            selectedCatIndex = currentMenu.categories.length - 1;
        }
        renderAll();
    };

    // Item Management inside Active Category
    window.addStudioItem = function() {
        const cat = currentMenu.categories[selectedCatIndex];
        if (!cat) return;
        if (!Array.isArray(cat.items)) cat.items = [];
        cat.items.push({
            id: 'itm_' + Math.random().toString(36).substring(2, 8),
            name: 'Nouveau Plat / Produit',
            description: '',
            price: '50',
            badge: '',
            image_url: '',
            available: true
        });
        renderAll();
    };

    window.duplicateStudioItem = function(itemIdx) {
        const cat = currentMenu.categories[selectedCatIndex];
        if (!cat || !cat.items[itemIdx]) return;
        const orig = cat.items[itemIdx];
        const copy = JSON.parse(JSON.stringify(orig));
        copy.id = 'itm_' + Math.random().toString(36).substring(2, 8);
        copy.name = (orig.name || 'Article') + ' (Copie)';
        cat.items.splice(itemIdx + 1, 0, copy);
        renderAll();
    };

    window.moveStudioItem = function(itemIdx, delta) {
        const cat = currentMenu.categories[selectedCatIndex];
        if (!cat || !Array.isArray(cat.items)) return;
        const target = itemIdx + delta;
        if (target < 0 || target >= cat.items.length) return;
        const tmp = cat.items[itemIdx];
        cat.items[itemIdx] = cat.items[target];
        cat.items[target] = tmp;
        renderAll();
    };

    window.deleteStudioItem = function(itemIdx) {
        const cat = currentMenu.categories[selectedCatIndex];
        if (!cat || !Array.isArray(cat.items)) return;
        cat.items.splice(itemIdx, 1);
        renderAll();
    };

    function renderCategorySidebar() {
        const listEl = document.getElementById('studioCategorySidebarList');
        const badgeEl = document.getElementById('sidebarCatCountBadge');
        if (!listEl) return;

        const cats = currentMenu.categories || [];
        if (badgeEl) badgeEl.textContent = cats.length;
        listEl.innerHTML = '';

        cats.forEach((cat, idx) => {
            const div = document.createElement('div');
            div.className = 'cat-sidebar-item d-flex align-items-center justify-content-between gap-2 ' + (idx === selectedCatIndex ? 'active' : '');
            div.onclick = () => selectStudioCategory(idx);

            const itemsCount = (cat.items || []).length;
            div.innerHTML = `
                <div class="d-flex align-items-center gap-2 min-w-0">
                    <span class="badge rounded-pill font-mono ${idx === selectedCatIndex ? 'bg-primary text-white' : 'bg-light text-muted border'}" style="font-size: 0.65rem;">P.${idx + 1}</span>
                    <div class="min-w-0">
                        <div class="fw-bold text-dark small text-truncate" style="font-size: 0.82rem;">${cat.name || 'Catégorie'}</div>
                        <div class="text-muted font-mono" style="font-size: 0.66rem;">${itemsCount} article${itemsCount > 1 ? 's' : ''}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <button type="button" class="btn btn-xs btn-light border px-1.5 py-0" ${idx === 0 ? 'disabled' : ''} onclick="moveStudioCategory(${idx}, -1, event)" title="Monter"><i class="bi bi-arrow-up" style="font-size: 0.68rem;"></i></button>
                    <button type="button" class="btn btn-xs btn-light border px-1.5 py-0" ${idx === cats.length - 1 ? 'disabled' : ''} onclick="moveStudioCategory(${idx}, 1, event)" title="Descendre"><i class="bi bi-arrow-down" style="font-size: 0.68rem;"></i></button>
                    <button type="button" class="btn btn-xs btn-outline-danger px-1.5 py-0" onclick="deleteStudioCategory(${idx}, event)" title="Supprimer"><i class="bi bi-trash" style="font-size: 0.68rem;"></i></button>
                </div>
            `;
            listEl.appendChild(div);
        });
    }

    function renderActiveCategoryWorkspace() {
        const container = document.getElementById('activeCategoryWorkspace');
        if (!container) return;

        const cat = currentMenu.categories[selectedCatIndex];
        if (!cat) {
            container.innerHTML = '<div class="text-center py-5 text-muted">Sélectionnez ou créez une catégorie.</div>';
            return;
        }

        const currency = currentMenu.currency || 'MAD';
        const items = cat.items || [];

        let itemsHtml = '';
        items.forEach((item, iIdx) => {
            const isAvail = item.available !== false;
            itemsHtml += `
                <div class="p-3 rounded-3 border bg-white shadow-2xs studio-item-card mb-2" data-idx="${iIdx}">
                    <div class="d-flex align-items-start gap-3">
                        <!-- Product Image Thumbnail Box + Upload -->
                        <div class="flex-shrink-0 text-center">
                            <div class="position-relative rounded-3 border overflow-hidden d-flex align-items-center justify-content-center bg-light mb-1 item-thumb-container" style="width: 72px; height: 72px;">
                                ${item.image_url ? `<img src="${item.image_url}" class="w-100 h-100 item-thumb-img" style="object-fit:cover;" onerror="this.style.display='none';">` : `<i class="fa-solid fa-camera text-muted fs-5 opacity-50"></i>`}
                                <label class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white text-center py-0.5 cursor-pointer m-0" style="font-size: 0.62rem;" title="Téléverser une photo du produit">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <input type="file" class="d-none item-file-upload-input" accept="image/*">
                                </label>
                            </div>
                            <label class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0.5 cursor-pointer" style="font-size: 0.64rem;" title="Téléverser photo">
                                <i class="fa-solid fa-upload"></i> Photo
                                <input type="file" class="d-none item-file-upload-btn" accept="image/*">
                            </label>
                        </div>

                        <!-- Item Details: Name, Price, Badge, Description, URL, Availability -->
                        <div class="flex-grow-1 min-w-0">
                            <div class="row g-2 align-items-center mb-1.5">
                                <div class="col-md-5">
                                    <label class="studio-label mb-0.5" style="font-size: 0.68rem;">Nom du Plat / Produit / Service</label>
                                    <input type="text" class="form-control form-control-sm studio-input item-name-inp fw-bold" value="${(item.name || '').replace(/"/g, '&quot;')}" placeholder="ex: Filet de Boeuf Rossini">
                                </div>
                                <div class="col-md-2">
                                    <label class="studio-label mb-0.5" style="font-size: 0.68rem;">Prix (${currency})</label>
                                    <input type="text" class="form-control form-control-sm studio-input font-mono item-price-inp" value="${(item.price || '').replace(/"/g, '&quot;')}" placeholder="150">
                                </div>
                                <div class="col-md-3">
                                    <label class="studio-label mb-0.5" style="font-size: 0.68rem;">Badge Mise en Avant</label>
                                    <input type="text" class="form-control form-control-sm studio-input item-badge-inp" value="${(item.badge || '').replace(/"/g, '&quot;')}" placeholder="ex: Chef, Populaire, Bio">
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-end gap-1 pt-3">
                                    <button type="button" class="btn btn-sm btn-white border px-1.5 py-0.5" ${iIdx === 0 ? 'disabled' : ''} onclick="moveStudioItem(${iIdx}, -1)" title="Monter"><i class="bi bi-arrow-up"></i></button>
                                    <button type="button" class="btn btn-sm btn-white border px-1.5 py-0.5" ${iIdx === items.length - 1 ? 'disabled' : ''} onclick="moveStudioItem(${iIdx}, 1)" title="Descendre"><i class="bi bi-arrow-down"></i></button>
                                    <button type="button" class="btn btn-sm btn-white border px-1.5 py-0.5 text-primary" onclick="duplicateStudioItem(${iIdx})" title="Dupliquer"><i class="bi bi-copy"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger px-1.5 py-0.5" onclick="deleteStudioItem(${iIdx})" title="Supprimer"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>

                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm studio-input item-desc-inp" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Description, ingrédients ou détails du produit...">
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white px-2"><i class="fa-solid fa-link text-muted" style="font-size: 0.65rem;"></i></span>
                                        <input type="text" class="form-control form-control-sm studio-input font-mono item-img-inp" value="${(item.image_url || '').replace(/"/g, '&quot;')}" placeholder="URL Photo (https://...)">
                                        ${item.image_url ? `<button type="button" class="btn btn-sm btn-outline-secondary px-2 item-clear-img-btn" title="Effacer"><i class="bi bi-x"></i></button>` : ''}
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-end">
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input cursor-pointer item-avail-inp" type="checkbox" id="avail_${iIdx}" ${isAvail ? 'checked' : ''}>
                                        <label class="form-check-label small fw-semibold cursor-pointer ${isAvail ? 'text-success' : 'text-danger'}" for="avail_${iIdx}" style="font-size: 0.72rem;">
                                            ${isAvail ? 'En stock' : 'Épuisé'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = `
            <div class="p-3 rounded-3 bg-light border mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-primary-subtle text-primary font-mono">Page / Catégorie #${selectedCatIndex + 1}</span>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold" onclick="addStudioItem()">
                        <i class="fa-solid fa-plus me-1"></i> Ajouter un Article dans cette Catégorie
                    </button>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-5">
                        <label class="studio-label mb-1">Titre de la Catégorie / Page</label>
                        <input type="text" id="activeCatNameInput" class="form-control form-control-sm studio-input fw-bold" value="${(cat.name || '').replace(/"/g, '&quot;')}">
                    </div>
                    <div class="col-md-3">
                        <label class="studio-label mb-1">Icône Font Awesome</label>
                        <input type="text" id="activeCatIconInput" class="form-control form-control-sm studio-input font-mono" value="${(cat.icon || 'fa-solid fa-utensils').replace(/"/g, '&quot;')}">
                    </div>
                    <div class="col-md-4">
                        <label class="studio-label mb-1">Sous-titre de Catégorie (Optionnel)</label>
                        <input type="text" id="activeCatDescInput" class="form-control form-control-sm studio-input" value="${(cat.description || '').replace(/"/g, '&quot;')}" placeholder="ex: Servi jusqu'à 23h">
                    </div>
                </div>

                <!-- Page / Category Cover Banner Manager -->
                <div class="p-2.5 rounded-3 bg-white border">
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <label class="studio-label mb-0 fw-bold" style="font-size: 0.76rem;">
                            <i class="fa-solid fa-image text-primary me-1"></i>Image de Couverture pour cette Page / Catégorie
                        </label>
                        <span class="text-muted" style="font-size: 0.68rem;">Bannière d'en-tête de page (recommandé 1200x500)</span>
                    </div>
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-3 border overflow-hidden d-flex align-items-center justify-content-center bg-light flex-shrink-0" style="width: 110px; height: 56px;">
                            ${cat.cover_image ? `<img src="${cat.cover_image}" class="w-100 h-100" style="object-fit:cover;" onerror="this.src='';">` : `<i class="fa-regular fa-image text-muted fs-4 opacity-50"></i>`}
                        </div>
                        <div class="flex-grow-1">
                            <div class="input-group input-group-sm mb-1">
                                <span class="input-group-text bg-white px-2"><i class="fa-solid fa-link text-muted" style="font-size: 0.7rem;"></i></span>
                                <input type="text" id="activeCatCoverInput" class="form-control form-control-sm studio-input font-mono" value="${(cat.cover_image || '').replace(/"/g, '&quot;')}" placeholder="URL de la bannière de page...">
                                ${cat.cover_image ? `<button type="button" class="btn btn-sm btn-outline-danger px-2" id="activeCatCoverDelBtn" title="Supprimer la bannière"><i class="bi bi-trash"></i></button>` : ''}
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-0.5 cursor-pointer mb-0">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i>Téléverser une Couverture
                                    <input type="file" id="activeCatCoverFileInput" class="d-none" accept="image/*">
                                </label>
                                <span class="text-muted" style="font-size: 0.68rem;">Format JPEG, PNG, WebP (max 6 Mo)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-2">
                ${itemsHtml || '<div class="text-center py-4 border rounded-3 text-muted small bg-light">Aucun article dans cette catégorie. Cliquez sur "+ Ajouter un Article".</div>'}
            </div>
        `;

        // Bind active category header inputs
        document.getElementById('activeCatNameInput')?.addEventListener('input', e => {
            cat.name = e.target.value;
            renderCategorySidebar();
            renderPhoneSimulator();
            syncHiddenInput();
        });
        document.getElementById('activeCatIconInput')?.addEventListener('input', e => {
            cat.icon = e.target.value;
            renderPhoneSimulator();
            syncHiddenInput();
        });
        document.getElementById('activeCatDescInput')?.addEventListener('input', e => {
            cat.description = e.target.value;
            renderPhoneSimulator();
            syncHiddenInput();
        });

        // Bind Category Cover Image
        document.getElementById('activeCatCoverInput')?.addEventListener('input', e => {
            cat.cover_image = e.target.value.trim();
            renderPhoneSimulator();
            syncHiddenInput();
        });
        document.getElementById('activeCatCoverFileInput')?.addEventListener('change', function() {
            const file = this.files?.[0];
            if (!file) return;
            uploadMenuFile(file, function(url) {
                cat.cover_image = url;
                renderActiveCategoryWorkspace();
                renderPhoneSimulator();
                syncHiddenInput();
            });
        });
        document.getElementById('activeCatCoverDelBtn')?.addEventListener('click', function() {
            cat.cover_image = '';
            renderActiveCategoryWorkspace();
            renderPhoneSimulator();
            syncHiddenInput();
        });

        // Bind item inputs
        container.querySelectorAll('.studio-item-card').forEach(cardEl => {
            const idx = parseInt(cardEl.dataset.idx, 10);
            const itemObj = cat.items[idx];
            if (!itemObj) return;

            cardEl.querySelector('.item-name-inp')?.addEventListener('input', e => { itemObj.name = e.target.value; renderPhoneSimulator(); syncHiddenInput(); });
            cardEl.querySelector('.item-price-inp')?.addEventListener('input', e => { itemObj.price = e.target.value; renderPhoneSimulator(); syncHiddenInput(); });
            cardEl.querySelector('.item-badge-inp')?.addEventListener('input', e => { itemObj.badge = e.target.value; renderPhoneSimulator(); syncHiddenInput(); });
            cardEl.querySelector('.item-desc-inp')?.addEventListener('input', e => { itemObj.description = e.target.value; renderPhoneSimulator(); syncHiddenInput(); });
            cardEl.querySelector('.item-img-inp')?.addEventListener('input', e => { itemObj.image_url = e.target.value; renderPhoneSimulator(); syncHiddenInput(); });
            cardEl.querySelector('.item-clear-img-btn')?.addEventListener('click', () => {
                itemObj.image_url = '';
                renderActiveCategoryWorkspace();
                renderPhoneSimulator();
                syncHiddenInput();
            });
            cardEl.querySelectorAll('.item-file-upload-input, .item-file-upload-btn').forEach(inp => {
                inp.addEventListener('change', function() {
                    const file = this.files?.[0];
                    if (!file) return;
                    uploadMenuFile(file, function(url) {
                        itemObj.image_url = url;
                        renderActiveCategoryWorkspace();
                        renderPhoneSimulator();
                        syncHiddenInput();
                    });
                });
            });
            cardEl.querySelector('.item-avail-inp')?.addEventListener('change', e => {
                itemObj.available = Boolean(e.target.checked);
                renderActiveCategoryWorkspace();
                renderPhoneSimulator();
                syncHiddenInput();
            });
        });
    }

    // Live Phone Simulator
    window.selectSimCategory = function(catId) {
        simActiveCatId = catId;
        renderPhoneSimulator();
    };

    window.stepSimCatalogPage = function(delta) {
        const cats = currentMenu.categories || [];
        if (cats.length === 0) return;
        simCatalogPageIdx = Math.max(0, Math.min(cats.length - 1, simCatalogPageIdx + delta));
        selectedCatIndex = simCatalogPageIdx;
        renderCategorySidebar();
        renderActiveCategoryWorkspace();
        renderPhoneSimulator();
    };

    window.renderPhoneSimulator = function() {
        const screen = document.getElementById('studioMenuPhoneScreen');
        const tabsBar = document.getElementById('simTabsBar');
        const itemsBody = document.getElementById('simItemsBody');
        const catalogFooter = document.getElementById('simCatalogFooter');
        const searchWrap = document.getElementById('simSearchBoxWrap');
        if (!screen || !tabsBar || !itemsBody) return;

        const preset = currentMenu.design_preset || 'card_theme';
        const hasCustomAccent = Boolean(currentMenu.accent_color);
        let accent = currentMenu.accent_color || cardTheme.primary_color || '#ea580c';
        let bg = cardTheme.background_color || '#0f172a';
        let textCol = '#ffffff';
        let cardSurface = 'rgba(255,255,255,0.07)';
        let cardBorder = 'rgba(255,255,255,0.12)';

        if (preset === 'bistro_dark') {
            accent = hasCustomAccent ? currentMenu.accent_color : '#ea580c';
            bg = '#0c0a09';
        } else if (preset === 'clean_light') {
            accent = hasCustomAccent ? currentMenu.accent_color : (cardTheme.primary_color || '#4f46e5');
            bg = '#f8fafc'; textCol = '#0f172a';
            cardSurface = '#ffffff'; cardBorder = '#e2e8f0';
        } else if (preset === 'luxury_gold') {
            accent = hasCustomAccent ? currentMenu.accent_color : '#d4af37';
            bg = '#090d16';
        } else if (preset === 'custom') {
            accent = currentMenu.accent_color || '#ea580c';
            bg = currentMenu.bg_color || '#0f172a';
        }

        screen.style.setProperty('--sim-accent', accent);
        screen.style.setProperty('--sim-bg', bg);
        screen.style.background = bg;
        screen.style.color = textCol;

        document.getElementById('simLiveTitle').textContent = currentMenu.title || 'Notre Menu & Catalogue';
        document.getElementById('simLiveSubtitle').textContent = currentMenu.subtitle || '';
        if (searchWrap) searchWrap.style.display = (currentMenu.show_search !== false) ? 'block' : 'none';

        const cats = currentMenu.categories || [];
        const navMode = currentMenu.navigation_mode || 'vertical';
        const itemLayout = currentMenu.item_layout || 'list';
        const currency = currentMenu.currency || 'MAD';
        const searchQuery = (document.getElementById('simSearchInput')?.value || '').toLowerCase().trim();

        if (navMode === 'horizontal' && simActiveCatId === 'all' && cats.length > 0) {
            simActiveCatId = cats[0].id;
        }

        // Render Tabs or Catalog Header
        if (navMode === 'catalog') {
            const currentCat = cats[simCatalogPageIdx] || cats[0];
            tabsBar.innerHTML = currentCat ? `
                <div class="w-100 d-flex align-items-center justify-content-between py-0.5">
                    <span class="fw-bold small" style="font-size: 0.76rem;"><i class="${currentCat.icon || 'fa-solid fa-book-open'} me-1.5" style="color:${accent}"></i>${currentCat.name}</span>
                    <span class="badge rounded-pill font-mono" style="background:${accent}; color:#fff; font-size: 0.62rem;">Page ${simCatalogPageIdx + 1} / ${cats.length}</span>
                </div>
            ` : '';
            if (catalogFooter) {
                catalogFooter.style.setProperty('display', 'flex', 'important');
                document.getElementById('simCatalogPageIndicator').textContent = `Page ${simCatalogPageIdx + 1} / ${cats.length}`;
                document.getElementById('simPrevPageBtn').disabled = (simCatalogPageIdx <= 0);
                document.getElementById('simNextPageBtn').disabled = (simCatalogPageIdx >= cats.length - 1);
            }
        } else {
            if (catalogFooter) catalogFooter.style.setProperty('display', 'none', 'important');
            let tHtml = '';
            if (navMode === 'vertical') {
                tHtml += `<button type="button" class="sim-cat-pill ${simActiveCatId === 'all' ? 'active' : ''}" style="${simActiveCatId === 'all' ? `background:${accent}; border-color:${accent}; color:#fff; box-shadow: 0 3px 10px ${accent}55;` : ''}" onclick="selectSimCategory('all')">Tout</button>`;
            }
            cats.forEach(c => {
                const isActive = (simActiveCatId === c.id);
                tHtml += `<button type="button" class="sim-cat-pill ${isActive ? 'active' : ''}" style="${isActive ? `background:${accent}; border-color:${accent}; color:#fff; box-shadow: 0 3px 10px ${accent}55;` : ''}" onclick="selectSimCategory('${c.id}')">${c.name || 'Catégorie'}</button>`;
            });
            tabsBar.innerHTML = tHtml;
        }

        // Render Items & Category Covers
        let bodyHtml = '';
        cats.forEach((cat, cIdx) => {
            if (!searchQuery) {
                if (navMode === 'catalog' && cIdx !== simCatalogPageIdx) return;
                if (navMode !== 'catalog' && simActiveCatId !== 'all' && simActiveCatId !== cat.id) return;
            }

            const filteredItems = (cat.items || []).filter(it => {
                if (!searchQuery) return true;
                const hay = `${it.name || ''} ${it.description || ''} ${it.badge || ''}`.toLowerCase();
                return hay.includes(searchQuery);
            });

            if (filteredItems.length === 0 && searchQuery) return;

            bodyHtml += `<div class="mb-3">`;

            // Page Cover in Simulator - The Category Cover Image serves as the Cover of the Page
            if (cat.cover_image) {
                bodyHtml += `
                    <div class="rounded-3 overflow-hidden position-relative mb-2.5 border" style="height: 120px; border-color:${cardBorder} !important; box-shadow: 0 4px 14px rgba(0,0,0,0.25);">
                        <img src="${cat.cover_image}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop&q=80';">
                        <div class="position-absolute top-0 bottom-0 start-0 end-0 p-2.5 d-flex flex-column justify-content-between" style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.15) 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill page-number-pill" style="background:${accent}; color:#fff; font-size: 0.6rem; font-weight:700; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">Page ${cIdx + 1} / ${cats.length}</span>
                                <span class="badge rounded-pill bg-dark bg-opacity-60 text-white" style="font-size: 0.58rem;">${filteredItems.length} article${filteredItems.length > 1 ? 's' : ''}</span>
                            </div>
                            <div>
                                <div class="fw-extrabold text-white" style="font-size: 0.88rem; text-shadow: 0 1px 4px rgba(0,0,0,0.9);"><i class="${cat.icon || 'fa-solid fa-tag'} me-1.5" style="color:${accent};"></i>${cat.name}</div>
                                ${cat.description ? `<div class="text-white text-opacity-85 text-truncate" style="font-size: 0.64rem; text-shadow: 0 1px 3px rgba(0,0,0,0.9);">${cat.description}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                bodyHtml += `
                    <div class="rounded-3 overflow-hidden position-relative mb-2.5 border d-flex flex-column justify-content-between p-2.5" style="height: 85px; background: linear-gradient(135deg, ${accent}33 0%, ${cardSurface} 100%); border-color:${cardBorder} !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill" style="background:${accent}; color:#fff; font-size: 0.58rem; font-weight:700;">Page ${cIdx + 1} / ${cats.length}</span>
                            <span class="badge rounded-pill bg-dark bg-opacity-40 text-white font-mono" style="font-size: 0.56rem;"><i class="fa-solid fa-image me-1 opacity-70"></i>Couverture de Page</span>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 0.82rem;"><i class="${cat.icon || 'fa-solid fa-tag'} me-1.5" style="color:${accent};"></i>${cat.name}</div>
                            ${cat.description ? `<div class="opacity-70 text-truncate" style="font-size: 0.62rem;">${cat.description}</div>` : ''}
                        </div>
                    </div>
                `;
            }

            // Products Grid: 2 cards on each row
            bodyHtml += `<div class="row g-2 mb-3">`;

            filteredItems.forEach(item => {
                const isAvail = item.available !== false;
                const cardHtml = `
                    <div class="col-6">
                        <div class="p-2 rounded-3 h-100 d-flex flex-column border" style="background:${cardSurface}; border-color:${cardBorder} !important; ${!isAvail ? 'opacity:0.5;' : ''}; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            ${currentMenu.show_images !== false ? `
                                <div class="position-relative w-100 rounded-2 overflow-hidden mb-1.5" style="height: 72px; background: rgba(148,163,184,0.15);">
                                    ${item.image_url ? `
                                        <img src="${item.image_url}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-100 h-100 d-flex align-items-center justify-content-center opacity-40\\'><i class=\\'fa-solid fa-camera small\\'></i></div>';">
                                    ` : `
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center opacity-40">
                                            <i class="fa-solid fa-utensils small"></i>
                                        </div>
                                    `}
                                    ${item.badge ? `<span class="badge rounded-pill position-absolute top-0 start-0 m-1" style="background:${accent}; color:#fff; font-size: 0.52rem; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">${item.badge}</span>` : ''}
                                    ${!isAvail ? `<span class="badge bg-danger rounded-pill position-absolute top-0 end-0 m-1" style="font-size: 0.52rem;">Épuisé</span>` : ''}
                                </div>
                            ` : (item.badge || !isAvail ? `
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    ${item.badge ? `<span class="badge rounded-pill" style="background:${accent}; color:#fff; font-size: 0.52rem;">${item.badge}</span>` : ''}
                                    ${!isAvail ? `<span class="badge bg-danger rounded-pill" style="font-size: 0.52rem;">Épuisé</span>` : ''}
                                </div>
                            ` : '')}
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold text-truncate" style="font-size: 0.76rem;" title="${(item.name || '').replace(/"/g, '&quot;')}">${item.name || 'Article'}</div>
                                ${item.description ? `<div class="opacity-70 mt-0.5" style="font-size: 0.62rem; line-height: 1.25; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">${item.description}</div>` : ''}
                            </div>
                            ${item.price ? `
                                <div class="mt-2 pt-1 border-top text-center" style="border-color:${cardBorder} !important;">
                                    <span class="badge rounded-pill font-mono w-100" style="background:${accent}22; color:${accent}; border:1px solid ${accent}66; font-size: 0.64rem; font-weight:700; box-shadow: 0 1px 4px ${accent}22;">${item.price} ${currency}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                bodyHtml += cardHtml;
            });

            bodyHtml += `</div></div>`;
        });

        itemsBody.innerHTML = bodyHtml || '<div class="text-center py-4 small opacity-60">Aucun article trouvé</div>';
    };

    function syncHiddenInput() {
        if (menuJsonInput) {
            menuJsonInput.value = JSON.stringify(currentMenu);
        }
    }

    function renderAll() {
        renderCategorySidebar();
        renderActiveCategoryWorkspace();
        renderPhoneSimulator();
        syncHiddenInput();
    }

    populateFormFromState();
    renderAll();
});
</script>
@endpush

