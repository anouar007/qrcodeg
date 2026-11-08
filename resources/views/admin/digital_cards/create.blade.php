@extends('layouts.app')

@section('title', 'Créer une Carte Visite Digitale')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.digital-cards.index') }}" class="text-decoration-none text-muted">Cartes Visite</a></li>
<li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Nouveau Studio</li>
@endsection

@section('content')
<div class="container-fluid px-0 card-builder-container">

    <!-- Executive Header Card (Aligned with Dynamic QR Studio) -->
    <div class="card border-0 mb-4 rounded-4 studio-hero-card">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <i class="bi bi-person-vcard fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="status-dot-radar"></span>
                            <span class="font-mono text-uppercase fw-bold text-indigo" style="font-size: 0.7rem; letter-spacing: 0.08em;">STUDIO CARTE VISITE NFC &bull; CRÉATION INTERACTIVE</span>
                            <span class="badge rounded-pill px-2.5 py-0.5 text-white font-mono version-pill">Studio Pro v2.0</span>
                        </div>
                        <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.02em; font-size: 1.45rem;">Créer une Carte Visite Digitale</h3>
                        <p class="text-muted small mb-0 mt-0.5 page-subtitle">
                            Concevez votre carte connectée NFC et QR code avec simulateur smartphone en temps réel et menu interactif.
                        </p>
                    </div>
                </div>

                <!-- Mode Selector Capsule + Value Actions -->
                <div class="d-flex flex-wrap align-items-center gap-2.5">
                    <div class="nav nav-pills studio-tabs-nav p-1 bg-light rounded-3 border d-inline-flex align-items-center" role="group">
                        <button type="button" class="nav-link active px-3 py-1.5 rounded-2 fw-semibold small d-inline-flex align-items-center gap-1.5 mode-tab-btn" id="btnModeClassic" onclick="switchBuilderMode('classic')">
                            <i class="bi bi-ui-checks text-primary"></i>
                            <span>Option 1 : Formulaire Standard</span>
                        </button>
                        <button type="button" class="nav-link px-3 py-1.5 rounded-2 fw-semibold small d-inline-flex align-items-center gap-1.5 mode-tab-btn" id="btnModeDragDrop" onclick="switchBuilderMode('dragdrop')">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Option 2 : Glisser-Déposer</span>
                            <span class="badge rounded-pill text-white ms-1" style="background: #4f46e5; font-size: 0.63rem; font-weight: 600;">Studio</span>
                        </button>
                    </div>

                    <!-- 1-Click Demo Profiles Dropdown -->
                    <div class="dropdown">
                        <button type="button" class="btn btn-studio-outline fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2 shadow-2xs dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Pré-remplir la carte avec un profil complet">
                            <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                            <span>Profils Démo</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border rounded-3 p-2" style="min-width: 280px; z-index: 1060;">
                            <li><h6 class="dropdown-header small font-mono text-uppercase py-1 text-muted" style="font-size: 0.7rem;">Pré-remplissage en 1-Clic</h6></li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2.5" href="javascript:void(0)" onclick="loadSampleDemoProfile('executive')">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background: #4f46e5;"><i class="fa-solid fa-briefcase"></i></div>
                                    <div class="min-w-0">
                                        <div class="fw-bold small text-dark">Direction & Exécutif</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Dirigeant, canaux pro, thème indigo</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2.5" href="javascript:void(0)" onclick="loadSampleDemoProfile('restaurant')">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background: #ea580c;"><i class="fa-solid fa-utensils"></i></div>
                                    <div class="min-w-0">
                                        <div class="fw-bold small text-dark">Restaurant & Gastronomie</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Chef, menu interactif complet, carte</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2.5" href="javascript:void(0)" onclick="loadSampleDemoProfile('creative')">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background: #ec4899;"><i class="fa-solid fa-palette"></i></div>
                                    <div class="min-w-0">
                                        <div class="fw-bold small text-dark">Créatif & Freelance</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">Designer, portfolio, réseaux visuels</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Direct QR Scan Test Button -->
                    <button type="button" class="btn btn-studio-outline fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2 shadow-2xs" onclick="openQrPreviewModal()" title="Tester l'aperçu directement sur smartphone via QR Code">
                        <i class="fa-solid fa-qrcode text-primary"></i>
                        <span class="d-none d-sm-inline">Tester par QR</span>
                    </button>

                    <a href="{{ route('admin.digital-cards.index') }}" class="btn btn-studio-outline fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2 shadow-2xs btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                    <button type="submit" form="cardBuilderForm" class="btn btn-studio-primary text-white fw-semibold d-inline-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm btn-publish-card">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Publier la carte</span>
                    </button>
                </div>
            </div>

            <!-- Sub-bar status indicator & Completion Progress -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 mt-3 border-top" style="border-color: #f1f5f9 !important;">
                <div class="text-muted small d-flex align-items-center gap-2 font-mono" style="font-size: 0.76rem;">
                    <span class="mode-pulse-indicator"></span>
                    <span id="modeDescriptionText">Formulaire pas-à-pas avec synchronisation instantanée du simulateur.</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Live Form Completion Score -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small font-mono text-muted" style="font-size: 0.72rem;">Complétion :</span>
                        <div class="progress" style="width: 110px; height: 7px; background: #e2e8f0; border-radius: 99px;">
                            <div id="formCompletionProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 75%; border-radius: 99px;"></div>
                        </div>
                        <span id="formCompletionPercentText" class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle font-mono fw-bold px-2 py-0.5" style="font-size: 0.68rem;">75%</span>
                    </div>

                    <div class="d-none d-md-flex align-items-center gap-2">
                        <span class="badge bg-light text-muted border font-mono px-2.5 py-1" style="font-size: 0.7rem;">
                            <i class="bi bi-filetype-vcf text-primary me-1"></i>vCard 3.0 (.vcf)
                        </span>
                        <span class="badge bg-light text-muted border font-mono px-2.5 py-1" style="font-size: 0.7rem;">
                            <i class="bi bi-wifi text-success me-1"></i>Compatible NFC & QR
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Builder Form -->
    <form id="cardBuilderForm" method="POST" action="{{ route('admin.digital-cards.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- Serialized JSON hidden fields for theme, content blocks and interactive menu -->
        <input type="hidden" name="theme_config" id="theme_config_json">
        <input type="hidden" name="content_blocks" id="content_blocks_json">
        <input type="hidden" name="menu_config" id="menu_config_json">

        <!-- =================================================================== -->
        <!-- OPTION 1: CLASSIC FORM BUILDER PANE -->
        <!-- =================================================================== -->
        <div id="classicBuilderPane">
            <div class="row g-4 align-items-start">
                
                <!-- Left Column: Form Cards & Steps -->
                <div class="col-xl-7 col-lg-7">

                    <!-- Sticky Quick-Jump Navigation Tabs -->
                    <div class="sticky-nav-container mb-3.5">
                        <div class="card border-0 rounded-4 studio-card p-1.5 shadow-xs">
                            <div class="nav nav-pills studio-tabs-nav d-flex align-items-center gap-1 overflow-x-auto quick-nav-bar" style="scrollbar-width: none;">
                                <a href="#sec-gabarits" class="nav-link quick-nav-pill active px-3 py-1.5 rounded-3 fw-semibold small text-decoration-none">
                                    <i class="bi bi-palette text-primary me-1"></i>1. Gabarit<span class="nav-step-dot is-done" id="dot-sec-gabarits"></span>
                                </a>
                                <a href="#sec-identite" class="nav-link quick-nav-pill px-3 py-1.5 rounded-3 fw-semibold small text-decoration-none">
                                    <i class="bi bi-person-badge text-primary me-1"></i>2. Identité<span class="nav-step-dot is-done" id="dot-sec-identite"></span>
                                </a>
                                <a href="#sec-medias" class="nav-link quick-nav-pill px-3 py-1.5 rounded-3 fw-semibold small text-decoration-none">
                                    <i class="bi bi-images text-primary me-1"></i>3. Médias<span class="nav-step-dot" id="dot-sec-medias"></span>
                                </a>
                                <a href="#sec-style" class="nav-link quick-nav-pill px-3 py-1.5 rounded-3 fw-semibold small text-decoration-none">
                                    <i class="bi bi-sliders text-primary me-1"></i>4. Style<span class="nav-step-dot is-done" id="dot-sec-style"></span>
                                </a>
                                <a href="#sec-canaux" class="nav-link quick-nav-pill px-3 py-1.5 rounded-3 fw-semibold small text-decoration-none">
                                    <i class="bi bi-link-45deg text-primary me-1"></i>5. Canaux<span class="nav-step-dot is-done" id="dot-sec-canaux"></span>
                                </a>
                                <a href="#sec-menu" class="nav-link quick-nav-pill px-3 py-1.5 rounded-3 fw-semibold small text-decoration-none" onclick="enableAndScrollToMenuSection()">
                                    <i class="fa-solid fa-utensils text-primary me-1"></i>6. Menu / Catalogue<span class="nav-step-dot" id="dot-sec-menu"></span>
                                </a>
                            </div>
                            <div class="quick-nav-progress-track">
                                <div class="quick-nav-progress-bar" id="quickNavProgressBar" style="width: 75%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Template Presets Card -->
                    <div class="card border-0 rounded-4 studio-card mb-4 section-card" id="sec-gabarits">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="studio-step-badge section-step-badge">1</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Gabarit & Modèles Visuels</h6>
                                    <small class="text-muted">Choisissez l'univers visuel de départ pour votre carte.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">4 Styles Signature</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Modern Minimal -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer active position-relative h-100 d-flex flex-column justify-content-between" data-preset="modern">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-2.5">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="preset-color-dot" style="background: #4f46e5;"></span>
                                                    <span class="preset-color-dot" style="background: #0f172a;"></span>
                                                </div>
                                                <div class="form-check m-0 p-0">
                                                    <input class="form-check-input visually-hidden" type="radio" name="template_id" id="preset_modern" value="modern" checked>
                                                    <span class="preset-check-icon"><i class="bi bi-check-circle-fill text-primary fs-6"></i></span>
                                                </div>
                                            </div>
                                            <div class="preset-mini-preview mb-2 modern-preview">
                                                <div class="mini-banner"></div>
                                                <div class="mini-avatar"></div>
                                                <div class="mini-lines">
                                                    <span></span><span></span>
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.88rem;">Modern Minimal</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Épuré, avatar arrondi, typographie contemporaine Outfit.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Corporate Sleek -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer position-relative h-100 d-flex flex-column justify-content-between" data-preset="executive">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-2.5">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="preset-color-dot" style="background: #0ea5e9;"></span>
                                                    <span class="preset-color-dot" style="background: #0b1329;"></span>
                                                </div>
                                                <div class="form-check m-0 p-0">
                                                    <input class="form-check-input visually-hidden" type="radio" name="template_id" id="preset_executive" value="executive">
                                                    <span class="preset-check-icon"><i class="bi bi-check-circle-fill text-primary fs-6"></i></span>
                                                </div>
                                            </div>
                                            <div class="preset-mini-preview mb-2 executive-preview">
                                                <div class="mini-banner"></div>
                                                <div class="mini-avatar"></div>
                                                <div class="mini-lines">
                                                    <span></span><span></span>
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.88rem;">Corporate Sleek</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Fond bleu nuit contrasté, finitions sobres et police Inter.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Creative Bold -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer position-relative h-100 d-flex flex-column justify-content-between" data-preset="creative">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-2.5">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="preset-color-dot" style="background: #ec4899;"></span>
                                                    <span class="preset-color-dot" style="background: #18021c;"></span>
                                                </div>
                                                <div class="form-check m-0 p-0">
                                                    <input class="form-check-input visually-hidden" type="radio" name="template_id" id="preset_creative" value="creative">
                                                    <span class="preset-check-icon"><i class="bi bi-check-circle-fill text-primary fs-6"></i></span>
                                                </div>
                                            </div>
                                            <div class="preset-mini-preview mb-2 creative-preview">
                                                <div class="mini-banner"></div>
                                                <div class="mini-avatar"></div>
                                                <div class="mini-lines">
                                                    <span></span><span></span>
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.88rem;">Creative Bold</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Accents magenta vifs, halo moderne, Space Grotesk.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gourmet & Prestige -->
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer position-relative h-100 d-flex flex-column justify-content-between" data-preset="gourmet">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-2.5">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="preset-color-dot" style="background: #d97706;"></span>
                                                    <span class="preset-color-dot" style="background: #18181b;"></span>
                                                </div>
                                                <div class="form-check m-0 p-0">
                                                    <input class="form-check-input visually-hidden" type="radio" name="template_id" id="preset_gourmet" value="gourmet">
                                                    <span class="preset-check-icon"><i class="bi bi-check-circle-fill text-primary fs-6"></i></span>
                                                </div>
                                            </div>
                                            <div class="preset-mini-preview mb-2 gourmet-preview" style="background: #18181b;">
                                                <div class="mini-banner" style="background: #d97706;"></div>
                                                <div class="mini-avatar" style="background: #3f3f46;"></div>
                                                <div class="mini-lines">
                                                    <span style="background: #d97706;"></span><span style="background: #71717a;"></span>
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.88rem;">Gourmet & Prestige</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Tons ambrés chauds, fond obsidienne, Playfair Display.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Profile & Identity Information Card -->
                    <div class="card border-0 rounded-4 studio-card mb-4 section-card" id="sec-identite">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="studio-step-badge section-step-badge">2</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Profile & Identity Details</h6>
                                    <small class="text-muted">Informations personnelles et professionnelles affichées sur votre carte.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">Essentiel</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3.5">
                                <div class="col-md-6">
                                    <label class="studio-label">Nom Complet <span class="text-danger">*</span></label>
                                    <div class="input-group studio-input-group has-prefix">
                                        <span class="input-group-text studio-input-addon"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="full_name" id="input_full_name" class="form-control studio-input" value="Marcus Vance" required placeholder="ex. Younes Zriket">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="studio-label">Intitulé / Nom de la Carte</label>
                                    <div class="input-group studio-input-group has-prefix">
                                        <span class="input-group-text studio-input-addon"><i class="fa-solid fa-tag"></i></span>
                                        <input type="text" name="title" id="input_title" class="form-control studio-input" value="Carte Digitale Direction" placeholder="ex. Carte Digitale Direction">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="studio-label">Poste / Fonction</label>
                                    <div class="input-group studio-input-group has-prefix">
                                        <span class="input-group-text studio-input-addon"><i class="fa-solid fa-briefcase"></i></span>
                                        <input type="text" name="job_title" id="input_job_title" class="form-control studio-input" value="Directeur Général" placeholder="ex. Directeur Général">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="studio-label">Entreprise / Organisation</label>
                                    <div class="input-group studio-input-group has-prefix">
                                        <span class="input-group-text studio-input-addon"><i class="fa-solid fa-building"></i></span>
                                        <input type="text" name="company" id="input_company" class="form-control studio-input" value="YNS Smart Solutions" placeholder="ex. YNS Smart Solutions">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="studio-label">Badge / Mention Vérifiée</label>
                                    <div class="input-group studio-input-group has-prefix">
                                        <span class="input-group-text studio-input-addon"><i class="fa-solid fa-certificate text-primary"></i></span>
                                        <input type="text" name="badge_text" id="input_badge_text" class="form-control studio-input" value="★ Profil Officiel" placeholder="ex. ★ Profil Officiel">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="studio-label">Rattacher à un Client (Optionnel)</label>
                                    <div class="input-group studio-input-group has-prefix">
                                        <span class="input-group-text studio-input-addon"><i class="fa-solid fa-users"></i></span>
                                        <select name="client_id" class="form-select studio-select">
                                            <option value="">-- Aucun client spécifique (Direct) --</option>
                                            @foreach($clients as $c)
                                                <option value="{{ $c->id }}">{{ $c->company_name ?: $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Dynamic Public URL Slug Live Preview -->
                                <div class="col-12">
                                    <div class="p-2.5 px-3 rounded-3 border d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                                        <div class="d-flex align-items-center gap-2.5 min-w-0">
                                            <span class="rounded-2 d-inline-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 34px; height: 34px; background: #eef2ff;">
                                                <i class="fa-solid fa-link"></i>
                                            </span>
                                            <div class="min-w-0">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="small fw-bold text-dark">URL Publique & QR Code direct :</span>
                                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle font-mono" style="font-size: 0.62rem;">Live Sync</span>
                                                </div>
                                                <span class="text-muted font-mono small text-truncate d-block" id="slugPreviewText" style="font-size: 0.76rem;">
                                                    {{ url('/c/') }}/<strong class="text-primary" id="slugPreviewVal">marcus-vance</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5">
                                            <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark shadow-2xs d-inline-flex align-items-center gap-1.5" onclick="copyCardUrlToClipboard()" title="Copier l'URL">
                                                <i class="fa-regular fa-copy text-primary"></i>
                                                <span class="font-mono small" id="copyUrlBtnText">Copier</span>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-primary shadow-2xs d-inline-flex align-items-center gap-1.5" onclick="openQrPreviewModal()" title="Ouvrir le QR Code">
                                                <i class="fa-solid fa-qrcode"></i>
                                                <span class="font-mono small">QR</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <label class="studio-label mb-0">Biographie / Présentation Courte</label>
                                        <span class="text-muted font-mono" id="bioCharCounter" style="font-size: 0.72rem;">85 / 180 caractères</span>
                                    </div>
                                    <textarea name="bio" id="input_bio" rows="2" class="form-control studio-input-textarea" maxlength="180" placeholder="Présentez votre activité en une ou deux phrases...">Solutions connectées NFC et cartes de visite interactives de nouvelle génération.</textarea>
                                    <div class="d-flex flex-wrap align-items-center gap-1.5 mt-2">
                                        <span class="text-muted small" style="font-size: 0.7rem;"><i class="fa-solid fa-lightbulb text-warning me-1"></i>Modèles de bio :</span>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2 py-0.5 text-muted small" onclick="applyBioSuggestion('Dirigeant d\'entreprise spécialisé dans l\'innovation et la transformation digitale.')">Dirigeant</button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2 py-0.5 text-muted small" onclick="applyBioSuggestion('Restaurant gastronomique & cuisine d\'auteur. Ouvert du mardi au dimanche.')">Restaurant</button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2 py-0.5 text-muted small" onclick="applyBioSuggestion('Consultant expert en stratégie, croissance et transformation des organisations.')">Consultant</button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2 py-0.5 text-muted small" onclick="applyBioSuggestion('Designer UI/UX & direction artistique. Créateur d\'expériences de marque uniques.')">Créatif</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Media Uploads Card -->
                    <div class="card border-0 rounded-4 studio-card mb-4 section-card" id="sec-medias">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="studio-step-badge section-step-badge">3</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Media Uploads & Visuels</h6>
                                    <small class="text-muted">Importez votre avatar de profil et votre bannière de tête.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">JPG, PNG, WEBP</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Photo de profil -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between media-upload-box">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="studio-label mb-0">Photo de Profil (Avatar)</label>
                                                <span class="badge bg-white border text-muted font-mono" style="font-size: 0.65rem;">500x500 px</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 mb-2.5">
                                                <div class="position-relative media-thumbnail-wrap flex-shrink-0 cursor-pointer" onclick="document.getElementById('profilePhotoInput').click()">
                                                    <img id="profileThumbnail" src="https://ui-avatars.com/api/?name=Marcus+Vance&background=4f46e5&color=fff&size=120" alt="Avatar" class="rounded-3 border bg-white shadow-2xs media-avatar-thumb">
                                                    <div class="media-hover-overlay rounded-3">
                                                        <i class="fa-solid fa-camera text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <input type="file" name="profile_photo" id="profilePhotoInput" class="form-control studio-input form-control-sm mb-1 media-file-input" accept="image/*">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <small class="text-muted" style="font-size: 0.72rem;">Format carré (max 4 Mo).</small>
                                                        <button type="button" class="btn btn-link p-0 text-danger text-decoration-none small" style="font-size: 0.72rem;" onclick="resetProfileAvatar()">
                                                            <i class="fa-solid fa-rotate-left me-0.5"></i>Avatar auto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bannière de couverture -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between media-upload-box">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="studio-label mb-0">Bannière de Couverture (Header)</label>
                                                <span class="badge bg-white border text-muted font-mono" style="font-size: 0.65rem;">1200x400 px</span>
                                            </div>
                                            <div id="coverThumbnail" class="rounded-2 border mb-2 media-cover-thumb position-relative overflow-hidden cursor-pointer" onclick="document.getElementById('coverPhotoInput').click()">
                                                <div class="position-absolute top-0 end-0 m-1.5">
                                                    <button type="button" class="btn btn-xs btn-dark bg-opacity-70 text-white rounded-pill px-2 py-0.5" style="font-size: 0.65rem;" onclick="event.stopPropagation(); resetCoverBanner()">
                                                        <i class="fa-solid fa-rotate-left me-1"></i>Dégradé
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="file" name="cover_photo" id="coverPhotoInput" class="form-control studio-input form-control-sm mb-1.5 media-file-input" accept="image/*">
                                            
                                            <!-- Quick Gradient Presets Swatches -->
                                            <div class="d-flex align-items-center gap-1.5 pt-1">
                                                <span class="text-muted" style="font-size: 0.68rem;">Dégradés :</span>
                                                <span class="gradient-swatch-chip" style="background: linear-gradient(135deg, #4f46e5 0%, #1e1b4b 100%);" onclick="applyBannerGradient('linear-gradient(135deg, #4f46e5 0%, #1e1b4b 100%)')" title="Indigo Nuit"></span>
                                                <span class="gradient-swatch-chip" style="background: linear-gradient(135deg, #0ea5e9 0%, #082f49 100%);" onclick="applyBannerGradient('linear-gradient(135deg, #0ea5e9 0%, #082f49 100%)')" title="Ocean Blue"></span>
                                                <span class="gradient-swatch-chip" style="background: linear-gradient(135deg, #ea580c 0%, #7c2d12 100%);" onclick="applyBannerGradient('linear-gradient(135deg, #ea580c 0%, #7c2d12 100%)')" title="Sunset Gold"></span>
                                                <span class="gradient-swatch-chip" style="background: linear-gradient(135deg, #059669 0%, #064e3b 100%);" onclick="applyBannerGradient('linear-gradient(135deg, #059669 0%, #064e3b 100%)')" title="Emerald"></span>
                                                <span class="gradient-swatch-chip" style="background: linear-gradient(135deg, #ec4899 0%, #3b0764 100%);" onclick="applyBannerGradient('linear-gradient(135deg, #ec4899 0%, #3b0764 100%)')" title="Cyber Violet"></span>
                                                <span class="gradient-swatch-chip" style="background: linear-gradient(135deg, #334155 0%, #0f172a 100%);" onclick="applyBannerGradient('linear-gradient(135deg, #334155 0%, #0f172a 100%)')" title="Charcoal"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Styling Controls & Theme Config Card -->
                    <div class="card border-0 rounded-4 studio-card mb-4 section-card" id="sec-style">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="studio-step-badge section-step-badge">4</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Styling Controls & Personnalisation</h6>
                                    <small class="text-muted">Ajustez les palettes, la typographie et l'arrondi des angles.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">Palette & Font</span>
                        </div>
                        <div class="card-body p-4">
                            <!-- Quick Designer Palettes & Dark/Light Switcher -->
                            <div class="mb-3.5 pb-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: #f1f5f9 !important;">
                                <div>
                                    <label class="studio-label d-flex align-items-center gap-1.5 mb-1.5">
                                        <i class="fa-solid fa-wand-magic-sparkles text-primary"></i> Palettes recommandées en 1 clic :
                                    </label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small palette-quick-pick" onclick="applyColorPalette('#4f46e5', '#0f172a', 'Outfit')">
                                            <span class="preset-color-dot me-1" style="background: #4f46e5;"></span> Royal Indigo
                                        </button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small palette-quick-pick" onclick="applyColorPalette('#059669', '#064e3b', 'Plus Jakarta Sans')">
                                            <span class="preset-color-dot me-1" style="background: #059669;"></span> Emerald Pro
                                        </button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small palette-quick-pick" onclick="applyColorPalette('#ec4899', '#18021c', 'Space Grotesk')">
                                            <span class="preset-color-dot me-1" style="background: #ec4899;"></span> Cyber Magenta
                                        </button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small palette-quick-pick" onclick="applyColorPalette('#0284c7', '#082f49', 'Inter')">
                                            <span class="preset-color-dot me-1" style="background: #0284c7;"></span> Ocean Executive
                                        </button>
                                        <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small palette-quick-pick" onclick="applyColorPalette('#d97706', '#1c1917', 'Playfair Display')">
                                            <span class="preset-color-dot me-1" style="background: #d97706;"></span> Luxury Amber
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 mt-2 mt-md-0">
                                    <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small fw-semibold shadow-2xs" onclick="setThemeBrightness('dark')" title="Appliquer un fond sombre contrasté">
                                        <i class="fa-solid fa-moon text-indigo me-1"></i>Mode Sombre
                                    </button>
                                    <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small fw-semibold shadow-2xs" onclick="setThemeBrightness('light')" title="Appliquer un fond clair minimal">
                                        <i class="fa-solid fa-sun text-warning me-1"></i>Mode Clair
                                    </button>
                                </div>
                            </div>

                            <div class="row g-3.5">
                                <!-- Couleur principale -->
                                <div class="col-md-6">
                                    <label class="studio-label">Couleur Principale (Boutons & Accents)</label>
                                    <div class="studio-color-capsule d-flex align-items-center p-1 border rounded-3 bg-white">
                                        <input type="color" id="primaryColorPicker" class="form-control form-control-color border-0 p-0 rounded-2" style="width: 32px; height: 32px; cursor: pointer;" value="#4f46e5" title="Choisir la couleur">
                                        <input type="text" name="primary_color" id="primaryColorText" class="form-control border-0 bg-transparent font-mono small fw-semibold py-0 px-2" style="height: 32px; font-size: 0.86rem;" value="#4f46e5">
                                    </div>
                                </div>

                                <!-- Couleur d'arrière-plan -->
                                <div class="col-md-6">
                                    <label class="studio-label">Arrière-Plan de la Carte</label>
                                    <div class="studio-color-capsule d-flex align-items-center p-1 border rounded-3 bg-white">
                                        <input type="color" id="bgColorPicker" class="form-control form-control-color border-0 p-0 rounded-2" style="width: 32px; height: 32px; cursor: pointer;" value="#0f172a" title="Choisir le fond">
                                        <input type="text" name="background_color" id="bgColorText" class="form-control border-0 bg-transparent font-mono small fw-semibold py-0 px-2" style="height: 32px; font-size: 0.86rem;" value="#0f172a">
                                    </div>
                                </div>

                                <!-- Police -->
                                <div class="col-md-6">
                                    <label class="studio-label">Police / Typographie</label>
                                    <select name="font_family" id="fontFamilySelect" class="form-select studio-select">
                                        <option value="Outfit" selected>Outfit (Moderne & Lisible)</option>
                                        <option value="Inter">Inter (Épuré & Précis)</option>
                                        <option value="Plus Jakarta Sans">Plus Jakarta Sans (Moderne SaaS)</option>
                                        <option value="Space Grotesk">Space Grotesk (Créatif & Audacieux)</option>
                                        <option value="Playfair Display">Playfair Display (Luxe & Sérif)</option>
                                    </select>
                                </div>

                                <!-- Style d'ombre -->
                                <div class="col-md-6">
                                    <label class="studio-label">Style d'Ombrage</label>
                                    <select name="shadow_style" id="shadowStyleSelect" class="form-select studio-select">
                                        <option value="soft" selected>Ombre Douce (3D Réaliste)</option>
                                        <option value="deep">Ombre Profonde (Exécutive)</option>
                                        <option value="glow">Halo Néon Lumineux</option>
                                        <option value="flat">Minimal Plat (Bordure seule)</option>
                                    </select>
                                </div>

                                <!-- Rayon des coins -->
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="studio-label mb-0">Arrondi des Angles</label>
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="badge bg-light text-primary border font-mono small" id="borderRadiusBadge">16px</span>
                                            <button type="button" class="btn btn-link p-0 text-muted font-mono" style="font-size: 0.68rem;" onclick="setCornerRadius(0)">0</button>
                                            <span class="text-muted" style="font-size: 0.65rem;">&bull;</span>
                                            <button type="button" class="btn btn-link p-0 text-muted font-mono" style="font-size: 0.68rem;" onclick="setCornerRadius(14)">14</button>
                                            <span class="text-muted" style="font-size: 0.65rem;">&bull;</span>
                                            <button type="button" class="btn btn-link p-0 text-muted font-mono" style="font-size: 0.68rem;" onclick="setCornerRadius(26)">26</button>
                                        </div>
                                    </div>
                                    <input type="range" name="border_radius" id="borderRadiusSlider" class="form-range studio-range mt-2" min="0" max="32" step="2" value="16">
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6">
                                    <label class="studio-label">Statut de Publication</label>
                                    <select name="status" class="form-select studio-select">
                                        <option value="active" selected>Active (En ligne & Consultable)</option>
                                        <option value="inactive">Inactive (Brouillon / Suspendue)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Interactive Links & Content Blocks Card -->
                    <div class="card border-0 rounded-4 studio-card mb-4 section-card" id="sec-canaux">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="studio-step-badge section-step-badge">5</span>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bold mb-0 text-dark">Dynamic Link Repeater & Canaux</h6>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-mono px-2 py-0.5" style="font-size: 0.68rem;">
                                            <i class="fa-solid fa-icons me-1"></i>Font Awesome Free
                                        </span>
                                        <span class="badge bg-light text-muted border font-mono px-2.5 py-0.5" id="channelsHeaderCountBadge" style="font-size: 0.68rem;">
                                            0 canal
                                        </span>
                                    </div>
                                    <small class="text-muted">Organisez vos canaux d'action et choisissez vos icônes vectorielles parmi la bibliothèque complète Font Awesome.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-studio-outline rounded-3 px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-2xs" onclick="openFaIconPicker(null)" title="Parcourir la bibliothèque d'icônes">
                                    <i class="fa-solid fa-magnifying-glass text-primary"></i>
                                    <span class="small font-mono">Explorer Icônes</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-studio-primary text-white rounded-3 px-3.5 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-2xs btn-add-channel" id="btnAddChannelBtn" onclick="addDefaultChannel()">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    <span>Ajouter un Canal</span>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-studio-outline rounded-3 px-2.5 py-1.5 text-muted shadow-2xs dropdown-toggle" type="button" id="btnAddChannelDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Modèles de canaux">
                                        <i class="bi bi-caret-down-fill" style="font-size: 0.72rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border rounded-3 mt-1 py-1" id="addChannelDropdownMenu" style="min-width: 250px; position: absolute; right: 0; z-index: 1060;">
                                        <li><h6 class="dropdown-header small font-mono text-uppercase py-1 text-muted" style="font-size: 0.7rem;">Canaux prédéfinis Font Awesome</h6></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="fa-solid fa-phone" data-label="Numéro de Téléphone" data-placeholder="+212600112233"><i class="fa-solid fa-phone me-2 text-success"></i> Appel Téléphonique</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="fa-brands fa-whatsapp" data-label="WhatsApp Direct" data-placeholder="+212600112233"><i class="fa-brands fa-whatsapp me-2 text-success"></i> WhatsApp Direct</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="fa-solid fa-envelope" data-label="Adresse Email" data-placeholder="contact@entreprise.com"><i class="fa-solid fa-envelope me-2 text-danger"></i> Email Direct</a></li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="social" data-icon="fa-brands fa-linkedin" data-label="LinkedIn" data-placeholder="https://linkedin.com/in/..."><i class="fa-brands fa-linkedin me-2 text-primary"></i> Profil LinkedIn</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="social" data-icon="fa-brands fa-instagram" data-label="Instagram" data-placeholder="https://instagram.com/..."><i class="fa-brands fa-instagram me-2 text-danger"></i> Instagram</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="social" data-icon="fa-brands fa-x-twitter" data-label="Twitter / X" data-placeholder="https://x.com/..."><i class="fa-brands fa-x-twitter me-2 text-dark"></i> Twitter / X</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="social" data-icon="fa-brands fa-youtube" data-label="YouTube" data-placeholder="https://youtube.com/@..."><i class="fa-brands fa-youtube me-2 text-danger"></i> Chaîne YouTube</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="social" data-icon="fa-brands fa-tiktok" data-label="TikTok" data-placeholder="https://tiktok.com/@..."><i class="fa-brands fa-tiktok me-2 text-dark"></i> Compte TikTok</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="social" data-icon="fa-brands fa-telegram" data-label="Telegram" data-placeholder="https://t.me/..."><i class="fa-brands fa-telegram me-2 text-info"></i> Canal Telegram</a></li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="custom_link" data-icon="fa-solid fa-globe" data-label="Site Web Officiel" data-placeholder="https://monsite.com"><i class="fa-solid fa-globe me-2 text-info"></i> Site Web Officiel</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="fa-solid fa-location-dot" data-label="Localisation Google Maps" data-placeholder="Casablanca Marina"><i class="fa-solid fa-location-dot me-2 text-warning"></i> Adresse Google Maps</a></li>
                                        <li><a class="dropdown-item py-2 add-block-btn" href="javascript:void(0)" data-type="custom_link" data-icon="fa-solid fa-file-pdf" data-label="Catalogue / Brochure PDF" data-placeholder="https://monsite.com/catalogue.pdf"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> Brochure PDF</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Dynamic Repeater Container -->
                            <div id="blocksContainer" class="d-flex flex-column gap-3.5">
                                <!-- Injected dynamically by JS -->
                            </div>

                            <!-- 1-Click Quick Add Dock with Brand Colors -->
                            <div class="quick-add-dock">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill font-mono px-2 py-0.5" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-bolt me-1 text-warning"></i>Accès Rapide
                                        </span>
                                        <span class="small fw-bold text-dark">Ajoutez un canal populaire en 1 clic :</span>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1 fw-semibold small add-block-btn shadow-2xs" data-type="custom_link" data-icon="fa-solid fa-link" data-label="Nouveau Lien" data-placeholder="https://...">
                                        <i class="fa-solid fa-plus me-1"></i>Autre Lien
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="quick-pill add-block-btn" data-type="contact" data-icon="fa-brands fa-whatsapp" data-label="WhatsApp Direct" data-placeholder="+212600112233">
                                        <i class="fa-brands fa-whatsapp text-success fs-6"></i>WhatsApp
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="contact" data-icon="fa-solid fa-phone" data-label="Appel Direct" data-placeholder="+212600112233">
                                        <i class="fa-solid fa-phone text-primary fs-6"></i>Téléphone
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="contact" data-icon="fa-solid fa-envelope" data-label="Email Pro" data-placeholder="contact@entreprise.com">
                                        <i class="fa-solid fa-envelope text-danger fs-6"></i>Email
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="social" data-icon="fa-brands fa-linkedin" data-label="LinkedIn" data-placeholder="https://linkedin.com/in/...">
                                        <i class="fa-brands fa-linkedin" style="color: #0077b5;"></i>LinkedIn
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="social" data-icon="fa-brands fa-instagram" data-label="Instagram" data-placeholder="https://instagram.com/...">
                                        <i class="fa-brands fa-instagram" style="color: #e4405f;"></i>Instagram
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="social" data-icon="fa-brands fa-x-twitter" data-label="Twitter / X" data-placeholder="https://x.com/...">
                                        <i class="fa-brands fa-x-twitter text-dark"></i>Twitter / X
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="custom_link" data-icon="fa-solid fa-globe" data-label="Site Web" data-placeholder="https://monsite.com">
                                        <i class="fa-solid fa-globe text-info"></i>Site Web
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="contact" data-icon="fa-solid fa-location-dot" data-label="Google Maps" data-placeholder="Casablanca Marina">
                                        <i class="fa-solid fa-location-dot text-warning"></i>Google Maps
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="social" data-icon="fa-brands fa-youtube" data-label="YouTube" data-placeholder="https://youtube.com/@...">
                                        <i class="fa-brands fa-youtube text-danger"></i>YouTube
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="social" data-icon="fa-brands fa-tiktok" data-label="TikTok" data-placeholder="https://tiktok.com/@...">
                                        <i class="fa-brands fa-tiktok text-dark"></i>TikTok
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="social" data-icon="fa-brands fa-telegram" data-label="Telegram" data-placeholder="https://t.me/...">
                                        <i class="fa-brands fa-telegram text-info"></i>Telegram
                                    </button>
                                    <button type="button" class="quick-pill add-block-btn" data-type="custom_link" data-icon="fa-solid fa-file-pdf" data-label="Brochure PDF" data-placeholder="https://monsite.com/brochure.pdf">
                                        <i class="fa-solid fa-file-pdf text-danger"></i>Brochure PDF
                                    </button>
                                    <button type="button" class="quick-pill" onclick="enableAndScrollToMenuSection()">
                                        <i class="fa-solid fa-utensils text-warning"></i>Menu / Catalogue Interactif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Interactive Restaurant / Brand Menu Builder Card -->
                    <div class="card border-0 rounded-4 studio-card mb-4 section-card" id="sec-menu">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: #f1f5f9 !important;">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="studio-step-badge section-step-badge">6</span>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bold mb-0 text-dark">Menu Interactif & Catalogue Produits / Services</h6>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill font-mono px-2 py-0.5" style="font-size: 0.68rem;">
                                            <i class="fa-solid fa-utensils me-1"></i>Page Dédiée /menu
                                        </span>
                                    </div>
                                    <small class="text-muted">Créez un menu de restaurant ou un catalogue de marque navigable avec catégories, tarifs et recherche instantanée.</small>
                                </div>
                            </div>

                            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="menuEnabledToggle" {{ !empty($defaultMenu['enabled']) ? 'checked' : '' }} style="width: 2.6rem; height: 1.35rem;" onchange="toggleInteractiveMenu(this.checked)">
                                <label class="form-check-label small fw-bold {{ !empty($defaultMenu['enabled']) ? 'text-success' : 'text-dark' }} cursor-pointer" for="menuEnabledToggle" id="menuEnabledLabel">{{ !empty($defaultMenu['enabled']) ? 'Menu Actif' : 'Activer le Menu' }}</label>
                            </div>
                        </div>

                        <div class="card-body p-4" id="menuBuilderBody" style="{{ !empty($defaultMenu['enabled']) ? 'display: block;' : 'display: none;' }}">
                            <!-- Quick Starter Templates Bar -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2.5 px-3 rounded-3 bg-light border mb-3.5">
                                <span class="small fw-bold text-dark font-mono" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>Modèles de Menu en 1-Clic :
                                </span>
                                <div class="d-flex flex-wrap align-items-center gap-1.5">
                                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-2.5 py-1 small fw-semibold" onclick="applyMenuQuickStarter('restaurant')">
                                        <i class="fa-solid fa-utensils text-warning me-1"></i>Restaurant & Bistro
                                    </button>
                                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-2.5 py-1 small fw-semibold" onclick="applyMenuQuickStarter('cafe')">
                                        <i class="fa-solid fa-mug-hot text-danger me-1"></i>Coffee Shop & Brunch
                                    </button>
                                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-2.5 py-1 small fw-semibold" onclick="applyMenuQuickStarter('brand')">
                                        <i class="fa-solid fa-bag-shopping text-primary me-1"></i>Catalogue Produits
                                    </button>
                                    <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-2.5 py-1 small fw-semibold" onclick="applyMenuQuickStarter('services')">
                                        <i class="fa-solid fa-briefcase text-success me-1"></i>Grille Tarifaire
                                    </button>
                                </div>
                            </div>

                            <!-- A. General Menu Identity & CTA -->
                            <div class="menu-sub-section">
                                <div class="menu-sub-title">
                                    <span class="menu-sub-icon" style="background:#eef2ff; color:#4f46e5;"><i class="fa-solid fa-signature"></i></span>
                                    <div>
                                        <div class="menu-sub-heading">Identité du Menu</div>
                                        <div class="menu-sub-hint">Titre de la page, bouton affiché sur la carte et devise</div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="studio-label">Titre de la Page Menu</label>
                                        <div class="input-group studio-input-group has-prefix">
                                            <span class="input-group-text studio-input-addon"><i class="fa-solid fa-heading"></i></span>
                                            <input type="text" id="menuTitleInput" class="form-control studio-input" value="Notre Menu & Catalogue" placeholder="ex: Carte du Restaurant / Catalogue" oninput="syncMenuSettings()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="studio-label">Libellé du Bouton sur la Carte</label>
                                        <div class="input-group studio-input-group has-prefix">
                                            <span class="input-group-text studio-input-addon"><i class="fa-solid fa-utensils text-primary"></i></span>
                                            <input type="text" id="menuButtonLabelInput" class="form-control studio-input" value="Consulter le Menu / Catalogue" placeholder="ex: Voir notre Menu" oninput="syncMenuSettings()">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="studio-label">Devise</label>
                                        <select id="menuCurrencySelect" class="form-select studio-select font-mono" onchange="syncMenuSettings()">
                                            <option value="MAD" selected>MAD (DH)</option>
                                            <option value="EUR">EUR (€)</option>
                                            <option value="USD">USD ($)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="CAD">CAD ($)</option>
                                            <option value="CHF">CHF</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="studio-label">Sous-titre d'accueil du Menu</label>
                                        <input type="text" id="menuSubtitleInput" class="form-control studio-input" value="Découvrez nos spécialités, offres et tarifs mis à jour en temps réel." placeholder="ex: Tous nos plats sont préparés à la commande..." oninput="syncMenuSettings()">
                                    </div>
                                </div>
                            </div>

                            <!-- B. Mode de Navigation (Vertical / Horizontal / Pages Catalogue) -->
                            <div class="menu-sub-section">
                                <div class="menu-sub-title">
                                    <span class="menu-sub-icon" style="background:#f0fdf4; color:#16a34a;"><i class="fa-solid fa-compass"></i></span>
                                    <div>
                                        <div class="menu-sub-heading">Navigation du Client</div>
                                        <div class="menu-sub-hint">Défilement vertical, onglets horizontaux ou pages type catalogue</div>
                                    </div>
                                </div>
                                <div class="row g-2.5" id="menuNavModeCardsWrap">
                                    <div class="col-md-4">
                                        <div class="menu-nav-option-card active p-3 rounded-3 border cursor-pointer h-100" data-nav-mode="vertical" onclick="selectMenuNavigationMode('vertical', this)">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <span class="rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #eef2ff; color: #4f46e5;">
                                                    <i class="fa-solid fa-arrows-up-down"></i>
                                                </span>
                                                <span class="badge bg-light text-muted border font-mono" style="font-size: 0.62rem;">Classique</span>
                                            </div>
                                            <div class="fw-bold text-dark small mb-0.5">1. Défilement Vertical</div>
                                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.35;">Toutes les catégories s'affichent de haut en bas avec barre de filtres rapide.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="menu-nav-option-card p-3 rounded-3 border cursor-pointer h-100" data-nav-mode="horizontal" onclick="selectMenuNavigationMode('horizontal', this)">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <span class="rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fff7ed; color: #ea580c;">
                                                    <i class="fa-solid fa-arrows-left-right"></i>
                                                </span>
                                                <span class="badge bg-light text-muted border font-mono" style="font-size: 0.62rem;">App Mobile</span>
                                            </div>
                                            <div class="fw-bold text-dark small mb-0.5">2. Onglets Horizontaux</div>
                                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.35;">Navigation horizontale catégorie par catégorie comme une application mobile.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="menu-nav-option-card p-3 rounded-3 border cursor-pointer h-100" data-nav-mode="catalog" onclick="selectMenuNavigationMode('catalog', this)">
                                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                <span class="rounded-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #f0fdf4; color: #16a34a;">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </span>
                                                <span class="badge bg-light text-muted border font-mono" style="font-size: 0.62rem;">Livre / Pages</span>
                                            </div>
                                            <div class="fw-bold text-dark small mb-0.5">3. Pages Type Catalogue</div>
                                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.35;">Chaque catégorie est une page numérotée avec boutons Précédent / Suivant.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- C. Design du Menu & Disposition des Articles -->
                            <div class="menu-sub-section">
                                <div class="menu-sub-title">
                                    <span class="menu-sub-icon" style="background:#fdf2f8; color:#db2777;"><i class="fa-solid fa-palette"></i></span>
                                    <div>
                                        <div class="menu-sub-heading">Design & Apparence</div>
                                        <div class="menu-sub-hint">Disposition des cartes produits, thème et couleur d'accent</div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-4 col-md-6">
                                        <label class="studio-label">Disposition des Articles</label>
                                        <select id="menuItemLayoutSelect" class="form-select studio-select" onchange="syncMenuSettings()">
                                            <option value="grid" selected>Grille de Cartes — 2 par ligne (Recommandé)</option>
                                            <option value="list">Liste Détaillée (Restaurant / Services)</option>
                                            <option value="compact">Liste Compacte (Cafétéria / Tarifs)</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label class="studio-label">Style & Thème Visuel</label>
                                        <select id="menuDesignPresetSelect" class="form-select studio-select" onchange="syncMenuSettings()">
                                            <option value="card_theme" selected>Identique à la Carte Visite</option>
                                            <option value="bistro_dark">Bistro Gourmet (Noir & Cuivre)</option>
                                            <option value="clean_light">Catalogue Clair & Minimaliste</option>
                                            <option value="luxury_gold">Prestige & Luxe (Noir & Or)</option>
                                            <option value="custom">Couleurs Personnalisées</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label class="studio-label">Couleur d'Accent <span class="text-muted fw-normal">(boutons, prix, onglets)</span></label>
                                        <div class="studio-color-capsule d-flex align-items-center gap-2 p-1 border rounded-3 bg-white" id="menuCustomAccentWrap">
                                            <input type="color" id="menuAccentColorPicker" class="studio-color-well" value="#ea580c" oninput="syncMenuSettings()">
                                            <span class="small font-mono text-muted" id="menuAccentHexLabel">#EA580C</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6" id="menuCustomBgWrap" style="display: none;">
                                        <label class="studio-label">Couleur de Fond du Menu</label>
                                        <div class="studio-color-capsule d-flex align-items-center gap-2 p-1 border rounded-3 bg-white">
                                            <input type="color" id="menuBgColorPicker" class="studio-color-well" value="#0f172a" oninput="syncMenuSettings()">
                                            <span class="small font-mono text-muted" id="menuBgHexLabel">#0F172A</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap align-items-center gap-4 menu-toggle-bar">
                                            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                                <input class="form-check-input cursor-pointer m-0" type="checkbox" id="menuShowSearchToggle" checked onchange="syncMenuSettings()">
                                                <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="menuShowSearchToggle"><i class="fa-solid fa-magnifying-glass text-muted me-1"></i>Barre de recherche</label>
                                            </div>
                                            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                                <input class="form-check-input cursor-pointer m-0" type="checkbox" id="menuShowImagesToggle" checked onchange="syncMenuSettings()">
                                                <label class="form-check-label small fw-semibold text-dark cursor-pointer" for="menuShowImagesToggle"><i class="fa-regular fa-image text-muted me-1"></i>Photos des articles</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Categories Header & Add Category Button -->
                            <div class="menu-sub-title menu-sub-title-bar">
                                <span class="menu-sub-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa-solid fa-layer-group"></i></span>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="menu-sub-heading">Catégories & Articles</div>
                                        <span class="badge bg-light text-muted border font-mono small" id="menuCategoriesCountBadge">1 catégorie</span>
                                    </div>
                                    <div class="menu-sub-hint">Chaque catégorie devient une page avec sa couverture et ses cartes produits</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-studio-outline rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="openSimulatorMenuDrawer('classic')">
                                        <i class="fa-solid fa-mobile-screen-button text-primary"></i>
                                        <span>Tester sur le Simulateur</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-studio-primary text-white rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="addMenuCategory()">
                                        <i class="fa-solid fa-folder-plus"></i>
                                        <span>Nouvelle Catégorie</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Menu insights & builder toolbar -->
                            <div class="menu-stats-grid" id="menuStatsGrid">
                                <div class="menu-stat-card"><span class="menu-stat-icon" style="background:#fff7ed;color:#ea580c;"><i class="fa-solid fa-layer-group"></i></span><div><div class="menu-stat-value" id="menuStatCats">0</div><div class="menu-stat-label">Catégories</div></div></div>
                                <div class="menu-stat-card"><span class="menu-stat-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fa-solid fa-box"></i></span><div><div class="menu-stat-value" id="menuStatItems">0</div><div class="menu-stat-label">Articles</div></div></div>
                                <div class="menu-stat-card"><span class="menu-stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-tag"></i></span><div><div class="menu-stat-value" id="menuStatAvg">—</div><div class="menu-stat-label">Prix moyen</div></div></div>
                                <div class="menu-stat-card"><span class="menu-stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="fa-regular fa-image"></i></span><div><div class="menu-stat-value" id="menuStatNoPhoto">0</div><div class="menu-stat-label">Sans photo</div></div></div>
                                <div class="menu-stat-card"><span class="menu-stat-icon" style="background:#fefce8;color:#ca8a04;"><i class="fa-solid fa-ban"></i></span><div><div class="menu-stat-value" id="menuStatOut">0</div><div class="menu-stat-label">Épuisés</div></div></div>
                            </div>

                            <div class="menu-builder-toolbar">
                                <div class="input-group input-group-sm menu-builder-search">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control border-start-0" id="menuBuilderSearchInput" placeholder="Filtrer catégories & plats..." oninput="setMenuBuilderFilter(this.value)">
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <button type="button" class="menu-tool-btn" onclick="setAllMenuCategoriesCollapsed(false)" title="Tout déplier"><i class="fa-solid fa-angles-down text-primary"></i><span>Tout déplier</span></button>
                                    <button type="button" class="menu-tool-btn" onclick="setAllMenuCategoriesCollapsed(true)" title="Tout replier"><i class="fa-solid fa-angles-up text-muted"></i><span>Tout replier</span></button>
                                    <span class="menu-tool-sep"></span>
                                    <button type="button" class="menu-tool-btn" onclick="markAllMenuItemsAvailability(true)" title="Tout marquer disponible"><i class="fa-solid fa-circle-check text-success"></i><span>Tout dispo</span></button>
                                    <button type="button" class="menu-tool-btn" onclick="markAllMenuItemsAvailability(false)" title="Tout marquer épuisé"><i class="fa-solid fa-circle-xmark text-danger"></i><span>Tout épuisé</span></button>
                                    <span class="menu-tool-sep"></span>
                                    <button type="button" class="menu-tool-btn" onclick="exportMenuJson()" title="Exporter en JSON"><i class="fa-solid fa-download text-dark"></i><span>Export</span></button>
                                    <label class="menu-tool-btn m-0 cursor-pointer" title="Importer depuis JSON"><i class="fa-solid fa-upload text-dark"></i><span>Import</span><input type="file" accept="application/json" class="d-none" onchange="importMenuJson(this)"></label>
                                </div>
                            </div>

                            <!-- Dynamic Categories Container -->
                            <div id="menuCategoriesContainer" class="d-flex flex-column gap-3.5"></div>
                        </div>

                        <!-- Disabled Placeholder Banner -->
                        <div class="card-body p-4 text-center" id="menuDisabledBanner" style="{{ !empty($defaultMenu['enabled']) ? 'display: none;' : 'display: block;' }}">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning mb-2.5" style="width: 56px; height: 56px;">
                                <i class="fa-solid fa-utensils fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Ajoutez un Menu de Restaurant ou Catalogue de Marque</h6>
                            <p class="text-muted small mb-3 mx-auto" style="max-width: 460px;">
                                Permettez à vos clients d'ouvrir votre carte/catalogue interactif depuis un bouton dédié sur votre Carte Visite et de naviguer par catégorie.
                            </p>
                            <button type="button" class="btn btn-sm btn-studio-primary text-white rounded-pill px-4 py-1.5 fw-semibold shadow-2xs" onclick="enableAndScrollToMenuSection()">
                                <i class="fa-solid fa-bolt me-1.5"></i>Activer et Configurer le Menu
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Sticky Realistic Smartphone Simulator -->
                <div class="col-xl-5 col-lg-5" style="align-self: stretch;">
                    <div class="sticky-preview-wrapper position-sticky">
                        <div class="card border-0 rounded-4 studio-card overflow-hidden">
                            <!-- Simulator Header Dock (Pro Executive 2-Tier Controls) -->
                            <div class="card-header bg-white border-bottom p-3" style="border-color: #f1f5f9 !important;">
                                <!-- Tier 1: Title & View Switcher (Carte vs Menu) -->
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom" style="border-color: #f8fafc !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="status-dot-radar"></span>
                                        <span class="small fw-bold text-dark font-mono text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                                            Live Device Simulator
                                        </span>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-xs btn-studio-outline active px-2.5 py-1 small fw-semibold sim-view-pill" id="simViewPillCard" onclick="setSimulatorView('card', this)">
                                            <i class="bi bi-person-vcard text-primary me-1"></i>Carte
                                        </button>
                                        <button type="button" class="btn btn-xs btn-studio-outline px-2.5 py-1 small fw-semibold sim-view-pill" id="simViewPillMenu" onclick="setSimulatorView('menu', this)">
                                            <i class="fa-solid fa-utensils text-warning me-1"></i>Menu
                                        </button>
                                    </div>
                                </div>

                                <!-- Tier 2: Device Frame, Zoom Scale, QR Scan & Reset -->
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Device Model">
                                        <button type="button" class="btn btn-xs btn-studio-outline active px-2.5 py-1 sim-device-btn" id="btnDevIphone" onclick="setSimulatorDevice('iphone', this)" title="iPhone 15 Pro (Dynamic Island)">
                                            <i class="fa-brands fa-apple me-1"></i><span style="font-size: 0.68rem;">iPhone</span>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-studio-outline px-2.5 py-1 sim-device-btn" id="btnDevAndroid" onclick="setSimulatorDevice('android', this)" title="Android Pixel (Poinçon)">
                                            <i class="fa-brands fa-android me-1"></i><span style="font-size: 0.68rem;">Pixel</span>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-studio-outline px-2 py-1 sim-device-btn" id="btnDevFrameless" onclick="setSimulatorDevice('frameless', this)" title="Vue Épurée sans bordure">
                                            <i class="fa-solid fa-square-full" style="font-size: 0.62rem;"></i>
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center gap-1.5">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Simulator Zoom">
                                            <button type="button" class="btn btn-xs btn-studio-outline active px-2 py-0.5 font-mono small" style="font-size: 0.68rem;" onclick="setSimulatorScale(1, this)">100%</button>
                                            <button type="button" class="btn btn-xs btn-studio-outline px-2 py-0.5 font-mono small" style="font-size: 0.68rem;" onclick="setSimulatorScale(0.85, this)">85%</button>
                                        </div>

                                        <button type="button" class="btn btn-xs btn-studio-outline px-2 py-1 text-primary shadow-2xs" onclick="openQrPreviewModal()" title="Tester avec un vrai smartphone via QR Code">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </button>

                                        <button type="button" class="btn btn-xs btn-studio-outline px-2 py-1 text-muted" onclick="resetSimulatorPreview()" title="Rafraîchir l'aperçu">
                                            <i class="bi bi-arrow-clockwise" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- CAD Dot-Grid Stage -->
                            <div class="card-body p-4 studio-canvas-stage d-flex flex-column align-items-center justify-content-center">
                                <!-- Realistic Smartphone Device Mockup Frame -->
                                <div class="phone-frame-mockup mx-auto shadow-2xl" id="classicPhoneFrame">
                                    <!-- Stainless Steel Edge Reflection Highlights -->
                                    <div class="phone-metal-glare"></div>

                                    <!-- Dynamic Island Notch -->
                                    <div class="phone-dynamic-island">
                                        <div class="island-camera"></div>
                                        <div class="island-speaker"></div>
                                    </div>

                                    <!-- Phone Screen Content (Target of CSS variables) -->
                                    <div class="phone-screen-content" id="phoneScreenPreview">
                                        <!-- Top Simulated Status Bar -->
                                        <div class="phone-status-bar d-flex justify-content-between align-items-center px-4 pt-2">
                                            <span class="font-mono small fw-bold" style="font-size: 0.72rem;">09:41</span>
                                            <div class="d-flex align-items-center gap-1.5 small">
                                                <i class="bi bi-reception-4" style="font-size: 0.72rem;"></i>
                                                <i class="bi bi-wifi" style="font-size: 0.72rem;"></i>
                                                <i class="bi bi-battery-full" style="font-size: 0.85rem;"></i>
                                            </div>
                                        </div>

                                        <!-- Card Cover Banner Preview -->
                                        <div class="preview-cover-banner" id="previewCoverBanner">
                                            <div class="cover-gradient-overlay"></div>
                                        </div>

                                        <!-- Card Profile Header -->
                                        <div class="preview-profile-wrap text-center px-4">
                                            <!-- Avatar with live preview -->
                                            <div class="preview-avatar-container mb-2">
                                                <img id="previewAvatarImg" src="https://ui-avatars.com/api/?name=Marcus+Vance&background=4f46e5&color=fff&size=160" alt="Avatar" class="preview-avatar-img">
                                            </div>

                                            <!-- Verified Badge Pill -->
                                            <div id="previewBadgeWrap" class="mb-1">
                                                <span class="preview-badge-pill" id="previewBadgeText">★ Profil Officiel</span>
                                            </div>

                                            <!-- Name, Job Title, Company, Bio -->
                                            <h5 class="fw-extrabold mb-1 preview-name-node" id="previewName">Marcus Vance</h5>
                                            <div class="preview-job-node small mb-0 fw-medium opacity-80" id="previewJob">Directeur Général</div>
                                            <div class="preview-company-node small mb-2 fw-bold" id="previewCompany">YNS Smart Solutions</div>

                                            <p class="preview-bio-node small mb-3 opacity-75" id="previewBio">
                                                Solutions connectées NFC et cartes de visite interactives de nouvelle génération.
                                            </p>

                                            <!-- "Save Contact" vCard Button Simulator -->
                                            <button type="button" class="btn preview-save-btn w-100 py-2 mb-2.5 rounded-pill fw-bold small shadow-sm">
                                                <i class="fa-solid fa-address-card me-1.5"></i> Enregistrer le contact (.vcf)
                                            </button>

                                            <!-- Live Restaurant / Brand Menu CTA Button Simulator -->
                                            <div id="previewMenuCtaWrap" style="display: none;" class="mb-3 text-start">
                                                <button type="button" onclick="openSimulatorMenuDrawer('classic')" class="w-100 border-0 text-white p-2.5 d-flex align-items-center justify-content-between shadow-sm" style="background: linear-gradient(135deg, var(--card-primary) 0%, rgba(255,255,255,0.14) 100%); border-radius: var(--card-radius, 14px); cursor: pointer;">
                                                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 36px; height: 36px; background: rgba(255,255,255,0.2);">
                                                            <i class="fa-solid fa-utensils"></i>
                                                        </div>
                                                        <div class="min-w-0 text-start">
                                                            <div class="fw-bold small text-truncate" id="previewMenuBtnLabel" style="font-size: 0.8rem;">Consulter le Menu / Catalogue</div>
                                                            <div class="opacity-80 text-truncate font-mono" id="previewMenuSubtitle" style="font-size: 0.65rem;">Navigation interactive</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge rounded-pill bg-white text-dark fw-bold px-2 py-1" style="font-size: 0.62rem;">Ouvrir <i class="fa-solid fa-arrow-right ms-0.5"></i></span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Rendered Interactive Links & Blocks -->
                                        <div class="preview-links-container px-3 pb-4" id="previewLinksContainer">
                                            <!-- Injected dynamically by JS with Font Awesome support -->
                                        </div>

                                        <!-- Simulated Branding Footer -->
                                        <div class="preview-footer text-center pb-3 small opacity-50 font-mono" style="font-size: 0.65rem;">
                                            CONNECTED SMART CARD &bull; YNS NFC
                                        </div>

                                        <!-- In-Phone Interactive Menu Overlay Drawer (Classic Simulator) -->
                                        <div id="simulatorMenuDrawer_classic" class="simulator-menu-drawer" style="display: none;">
                                            <div class="sim-menu-topbar d-flex align-items-center justify-content-between px-3 py-2.5">
                                                <button type="button" class="btn btn-xs text-white border border-white border-opacity-25 rounded-pill px-2.5 py-1 small fw-semibold" style="background: rgba(255,255,255,0.1); font-size: 0.7rem;" onclick="closeSimulatorMenuDrawer('classic')">
                                                    <i class="fa-solid fa-arrow-left me-1"></i> Retour
                                                </button>
                                                <span class="badge rounded-pill px-2.5 py-1 font-mono" style="background: rgba(255,255,255,0.12); font-size: 0.65rem;" id="simMenuCount_classic">0 articles</span>
                                            </div>
                                            <div class="px-3 pt-2 pb-3">
                                                <div class="p-3 rounded-4 mb-2.5 text-center" id="simMenuHero_classic" style="background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)); border: 1px solid rgba(255,255,255,0.12);">
                                                    <div class="small fw-extrabold mb-0.5" id="simMenuTitle_classic" style="font-size: 0.95rem;">Notre Menu & Catalogue</div>
                                                    <div class="opacity-75" id="simMenuSub_classic" style="font-size: 0.7rem;">Sélectionnez une catégorie</div>
                                                </div>
                                                <div id="simMenuSearchWrap_classic" class="mb-2">
                                                    <div class="input-group input-group-sm rounded-pill overflow-hidden border" style="border-color: rgba(148,163,184,0.25) !important; background: rgba(255,255,255,0.08);">
                                                        <span class="input-group-text border-0 bg-transparent text-current opacity-60 ps-2.5 pe-1"><i class="fa-solid fa-magnifying-glass" style="font-size: 0.68rem;"></i></span>
                                                        <input type="text" class="form-control border-0 bg-transparent text-current shadow-none py-1 px-1.5" style="font-size: 0.72rem; color: inherit;" placeholder="Rechercher un plat ou produit..." oninput="filterSimulatorMenuSearch('classic', this.value)">
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-1.5 overflow-x-auto pb-2 mb-2" id="simMenuTabs_classic" style="scrollbar-width: none;"></div>
                                                <div class="d-flex flex-column gap-2" id="simMenuItems_classic"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Simulator Footer Info & Diagnostics -->
                            <div class="card-footer bg-white border-top py-2 px-3.5 d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: #f1f5f9 !important;">
                                <div class="d-flex align-items-center gap-2 font-mono small text-muted" style="font-size: 0.72rem;">
                                    <span class="status-dot-pulse"></span>
                                    <span id="simSummaryStats">5 canaux connectés &bull; vCard 3.0 Ready</span>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 font-mono" style="font-size: 0.68rem;">
                                    <span class="badge bg-light text-muted border"><i class="fa-solid fa-bolt text-warning me-1"></i>&lt;0.2s</span>
                                    <span class="badge bg-light text-muted border"><i class="fa-solid fa-wifi text-success me-1"></i>NFC ~420B</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- OPTION 2: VISUAL DRAG & DROP STUDIO BUILDER PANE -->
        <!-- =================================================================== -->
        <div id="dragDropBuilderPane" style="display: none;">
            <div class="row g-4 align-items-start">

                <!-- Left Column: Smart Library of Elements & Optimizer Tabs -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card border-0 rounded-4 studio-card overflow-hidden">
                        
                        <!-- Tabs Header: Library vs Optimizer -->
                        <div class="card-header bg-white border-bottom p-2.5 d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: #f1f5f9 !important;">
                            <div class="nav nav-pills studio-tabs-nav p-1 bg-light rounded-3 border d-inline-flex align-items-center" id="dndSidebarTabs" role="tablist">
                                <button class="nav-link active px-3 py-1.5 rounded-2 small fw-semibold" id="tab-library-btn" data-bs-toggle="pill" data-bs-target="#tab-library" type="button" role="tab">
                                    <i class="bi bi-collection me-1.5 text-primary"></i> Bibliothèque d'Éléments
                                </button>
                                <button class="nav-link px-3 py-1.5 rounded-2 small fw-semibold" id="tab-optimizer-btn" data-bs-toggle="pill" data-bs-target="#tab-optimizer" type="button" role="tab">
                                    <i class="bi bi-sliders me-1.5 text-warning"></i> Optimiser l'Élément
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono" id="dndBlockCountBadge">0 élément(s)</span>
                                <button type="button" class="btn btn-xs btn-studio-outline rounded-3 px-2.5 py-1 text-dark small fw-semibold shadow-2xs" onclick="addNewBlock()" title="Ajouter un bloc vierge">
                                    <i class="fa-solid fa-plus me-1 text-primary"></i>Nouveau
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-3.5">
                            <div class="tab-content" id="dndTabContent">
                                
                                <!-- Tab 1: Smart Elements Library -->
                                <div class="tab-pane fade show active" id="tab-library" role="tabpanel">
                                    <div class="d-flex align-items-center justify-content-between mb-2.5">
                                        <div>
                                            <h6 class="fw-bold mb-0.5 text-dark" style="font-size: 0.92rem;">Glissez ou cliquez sur un élément</h6>
                                            <small class="text-muted" style="font-size: 0.76rem;">Déposez directement sur l'écran du smartphone à droite ou cliquez pour l'insérer.</small>
                                        </div>
                                    </div>

                                    <!-- Filter/Search elements input -->
                                    <div class="input-group studio-input-group has-prefix mb-2.5">
                                        <span class="input-group-text studio-input-addon"><i class="bi bi-search"></i></span>
                                        <input type="text" id="librarySearchInput" class="form-control studio-input" placeholder="Rechercher un widget (WhatsApp, Appel, LinkedIn, vCard...)" oninput="filterWidgetLibrary(this.value)">
                                    </div>

                                    <!-- Quick Category Filter Chips -->
                                    <div class="d-flex align-items-center gap-1.5 mb-3 overflow-x-auto pb-1" style="scrollbar-width: none;">
                                        <button type="button" class="widget-cat-filter-btn active" data-category="all" onclick="filterWidgetCategory('all', this)">Tous</button>
                                        <button type="button" class="widget-cat-filter-btn" data-category="contact" onclick="filterWidgetCategory('contact', this)">Contact</button>
                                        <button type="button" class="widget-cat-filter-btn" data-category="social" onclick="filterWidgetCategory('social', this)">Réseaux</button>
                                        <button type="button" class="widget-cat-filter-btn" data-category="business" onclick="filterWidgetCategory('business', this)">Business</button>
                                        <button type="button" class="widget-cat-filter-btn" data-category="media" onclick="filterWidgetCategory('media', this)">Médias</button>
                                    </div>

                                    <!-- Draggable Widgets Grid -->
                                    <div class="row g-2.5" id="libraryWidgetsGrid">
                                        <!-- Contact Direct -->
                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="contact" data-icon="fa-brands fa-whatsapp" data-label="WhatsApp Direct" data-placeholder="+212600112233" data-category="contact">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #25d366;"><i class="fa-brands fa-whatsapp"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">WhatsApp</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Chat direct</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="contact" data-icon="fa-solid fa-phone" data-label="Appel Téléphonique" data-placeholder="+212600112233" data-category="contact">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #0284c7;"><i class="fa-solid fa-phone"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">Téléphone</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Appel direct</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="contact" data-icon="fa-solid fa-envelope" data-label="Adresse Email" data-placeholder="contact@entreprise.com" data-category="contact">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #ef4444;"><i class="fa-solid fa-envelope"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">Email</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Courrier pro</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="social" data-icon="fa-brands fa-linkedin" data-label="LinkedIn Profile" data-placeholder="https://linkedin.com/in/..." data-category="social">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #0077b5;"><i class="fa-brands fa-linkedin"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">LinkedIn</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Réseau B2B</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="social" data-icon="fa-brands fa-instagram" data-label="Instagram" data-placeholder="https://instagram.com/..." data-category="social">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #e4405f;"><i class="fa-brands fa-instagram"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">Instagram</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Photos / Reels</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="custom_link" data-icon="fa-solid fa-globe" data-label="Site Web Officiel" data-placeholder="https://monsite.com" data-category="business">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #4f46e5;"><i class="fa-solid fa-globe"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">Site Web</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Vitrine / SaaS</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="contact" data-icon="fa-solid fa-location-dot" data-label="Google Maps" data-placeholder="Casablanca Marina" data-category="business">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #ea580c;"><i class="fa-solid fa-location-dot"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">Localisation</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Google Maps</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between gap-2" draggable="true" data-type="menu" data-icon="fa-solid fa-utensils" data-label="Menu & Catalogue" data-placeholder="#menu" data-category="business">
                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                    <div class="widget-icon-avatar" style="background: #ea580c;"><i class="fa-solid fa-utensils"></i></div>
                                                    <div class="min-w-0">
                                                        <span class="fw-bold small d-block text-truncate text-dark" style="font-size: 0.8rem;">Menu / Carte</span>
                                                        <span class="text-muted small d-block text-truncate font-mono" style="font-size: 0.68rem;">Restaurant</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 2: Element Optimizer -->
                                <div class="tab-pane fade" id="tab-optimizer" role="tabpanel">
                                    <div id="optimizerEmptyState" class="text-center py-5 text-muted">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-2.5" style="width: 50px; height: 50px;">
                                            <i class="bi bi-cursor fs-4 text-secondary"></i>
                                        </div>
                                        <p class="mb-1 fw-bold text-dark">Aucun élément sélectionné</p>
                                        <small class="text-muted">Cliquez sur un widget déposé sur le simulateur à droite pour l'ajuster.</small>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-sm btn-studio-outline rounded-pill px-3" onclick="switchToLibraryTab()">
                                                <i class="bi bi-arrow-left me-1"></i>Retour à la bibliothèque
                                            </button>
                                        </div>
                                    </div>

                                    <div id="optimizerFormWrap" style="display: none;">
                                        <div class="d-flex align-items-center justify-content-between pb-2.5 mb-3 border-bottom">
                                            <div class="d-flex align-items-center gap-2 min-w-0">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" id="optHeaderIcon" style="width: 32px; height: 32px; background: #4f46e5;">
                                                    <i class="fa-solid fa-link small"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h6 class="fw-bold mb-0 text-dark text-truncate" id="optHeaderTitle" style="font-size: 0.9rem;">Édition de l'élément</h6>
                                                    <span class="badge bg-light text-muted border font-mono small" id="optHeaderType">type</span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-xs btn-outline-danger rounded-pill px-2.5 py-1" onclick="deleteCurrentOptimizedBlock()">
                                                <i class="fa-solid fa-trash me-1"></i>Supprimer
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="studio-label">Libellé du Bouton</label>
                                                <input type="text" id="optBlockLabelInput" class="form-control studio-input" placeholder="ex: Mon WhatsApp Pro">
                                            </div>
                                            <div class="col-12">
                                                <label class="studio-label">Lien / Numéro / URL</label>
                                                <input type="text" id="optBlockUrlInput" class="form-control studio-input font-mono" placeholder="ex: +212600112233 ou https://...">
                                            </div>
                                            <div class="col-12">
                                                <label class="studio-label">Icône Vectorielle (Font Awesome)</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="channel-icon-trigger-box" onclick="openFaIconPickerForActiveOptimized()" title="Changer l'icône">
                                                        <i class="fa-solid fa-link" id="optActiveIconPreview"></i>
                                                        <span class="edit-badge-dot"><i class="fa-solid fa-pen"></i></span>
                                                    </div>
                                                    <span class="channel-icon-tag" id="optActiveIconClass">fa-solid fa-link</span>
                                                    <button type="button" class="btn btn-sm btn-studio-outline rounded-pill px-3 py-1 ms-auto" onclick="openFaIconPickerForActiveOptimized()">
                                                        <i class="fa-solid fa-magnifying-glass me-1 text-primary"></i>Explorer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Smartphone Stage (Option 2 Drag & Drop) -->
                <div class="col-xl-6 col-lg-6">
                    <div class="sticky-preview-wrapper position-sticky">
                        <div class="card border-0 rounded-4 studio-card overflow-hidden">
                            <div class="card-header bg-white border-bottom py-2.5 px-3 d-flex justify-content-between align-items-center" style="border-color: #f1f5f9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot-radar"></span>
                                    <span class="small fw-bold text-dark font-mono text-uppercase" style="font-size: 0.72rem;">Studio Stage Interactif</span>
                                </div>
                                <span class="badge bg-light text-muted border font-mono small">Glisser & Déposer Actif</span>
                            </div>

                            <div class="card-body p-4 studio-canvas-stage d-flex flex-column align-items-center justify-content-center">
                                <div class="phone-frame-mockup mx-auto shadow-2xl" id="dndPhoneFrame">
                                    <div class="phone-metal-glare"></div>
                                    <div class="phone-dynamic-island">
                                        <div class="island-camera"></div>
                                        <div class="island-speaker"></div>
                                    </div>

                                    <div class="phone-screen-content" id="dndScreenPreview">
                                        <div class="phone-status-bar d-flex justify-content-between align-items-center px-4 pt-2">
                                            <span class="font-mono small fw-bold" style="font-size: 0.72rem;">09:41</span>
                                            <div class="d-flex align-items-center gap-1.5 small">
                                                <i class="bi bi-reception-4" style="font-size: 0.72rem;"></i>
                                                <i class="bi bi-wifi" style="font-size: 0.72rem;"></i>
                                                <i class="bi bi-battery-full" style="font-size: 0.85rem;"></i>
                                            </div>
                                        </div>

                                        <div class="preview-cover-banner" id="dndCoverBanner">
                                            <div class="cover-gradient-overlay"></div>
                                        </div>

                                        <div class="preview-profile-wrap text-center px-4">
                                            <div class="preview-avatar-container mb-2">
                                                <img id="dndAvatarImg" src="https://ui-avatars.com/api/?name=Marcus+Vance&background=4f46e5&color=fff&size=160" alt="Avatar" class="preview-avatar-img">
                                            </div>

                                            <h5 class="fw-extrabold mb-1 preview-name-node" id="dndName">Marcus Vance</h5>
                                            <div class="preview-job-node small mb-0 fw-medium opacity-80" id="dndJob">Directeur Général</div>
                                            <div class="preview-company-node small mb-2 fw-bold" id="dndCompany">YNS Smart Solutions</div>

                                            <p class="preview-bio-node small mb-3 opacity-75" id="dndBio">
                                                Solutions connectées NFC et cartes de visite interactives de nouvelle génération.
                                            </p>

                                            <button type="button" class="btn preview-save-btn w-100 py-2 mb-2.5 rounded-pill fw-bold small shadow-sm">
                                                <i class="fa-solid fa-address-card me-1.5"></i> Enregistrer le contact (.vcf)
                                            </button>
                                        </div>

                                        <!-- Droppable Zone on Smartphone Screen -->
                                        <div class="px-3 pb-4">
                                            <div id="dndDropZone" class="dnd-drop-zone"></div>
                                            <div id="dndEmptyPlaceholder" class="text-center py-4 rounded-3 border border-dashed border-white border-opacity-25" style="background: rgba(255,255,255,0.04);">
                                                <i class="fa-solid fa-arrow-down opacity-50 mb-1 d-block"></i>
                                                <span class="small font-mono opacity-70" style="font-size: 0.72rem;">Déposez vos widgets ici</span>
                                            </div>
                                        </div>

                                        <div class="preview-footer text-center pb-3 small opacity-50 font-mono" style="font-size: 0.65rem;">
                                            CONNECTED SMART CARD &bull; YNS NFC
                                        </div>

                                        <!-- In-Phone Menu Drawer (DnD Mode) -->
                                        <div id="simulatorMenuDrawer_dnd" class="simulator-menu-drawer" style="display: none;">
                                            <div class="sim-menu-topbar d-flex align-items-center justify-content-between px-3 py-2.5">
                                                <button type="button" class="btn btn-xs text-white border border-white border-opacity-25 rounded-pill px-2.5 py-1 small fw-semibold" style="background: rgba(255,255,255,0.1); font-size: 0.7rem;" onclick="closeSimulatorMenuDrawer('dnd')">
                                                    <i class="fa-solid fa-arrow-left me-1"></i> Retour
                                                </button>
                                                <span class="badge rounded-pill px-2.5 py-1 font-mono" style="background: rgba(255,255,255,0.12); font-size: 0.65rem;" id="simMenuCount_dnd">0 articles</span>
                                            </div>
                                            <div class="px-3 pt-2 pb-3">
                                                <div class="p-3 rounded-4 mb-2.5 text-center" id="simMenuHero_dnd" style="background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)); border: 1px solid rgba(255,255,255,0.12);">
                                                    <div class="small fw-extrabold mb-0.5" id="simMenuTitle_dnd" style="font-size: 0.95rem;">Notre Menu & Catalogue</div>
                                                    <div class="opacity-75" id="simMenuSub_dnd" style="font-size: 0.7rem;">Sélectionnez une catégorie</div>
                                                </div>
                                                <div id="simMenuSearchWrap_dnd" class="mb-2">
                                                    <div class="input-group input-group-sm rounded-pill overflow-hidden border" style="border-color: rgba(148,163,184,0.25) !important; background: rgba(255,255,255,0.08);">
                                                        <span class="input-group-text border-0 bg-transparent text-current opacity-60 ps-2.5 pe-1"><i class="fa-solid fa-magnifying-glass" style="font-size: 0.68rem;"></i></span>
                                                        <input type="text" class="form-control border-0 bg-transparent text-current shadow-none py-1 px-1.5" style="font-size: 0.72rem; color: inherit;" placeholder="Rechercher un plat ou produit..." oninput="filterSimulatorMenuSearch('dnd', this.value)">
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-1.5 overflow-x-auto pb-2 mb-2" id="simMenuTabs_dnd" style="scrollbar-width: none;"></div>
                                                <div class="d-flex flex-column gap-2" id="simMenuItems_dnd"></div>
                                            </div>
                                        </div>
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

<!-- ======================================================================= -->
<!-- FONT AWESOME FREE ICON PICKER MODAL WITH FULL SEARCH & CATEGORIES       -->
<!-- ======================================================================= -->
<div class="modal fade" id="faIconPickerModal" tabindex="-1" aria-labelledby="faIconPickerModalLabel" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light bg-opacity-75">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                        <i class="fa-solid fa-icons fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title fw-bold text-dark fs-6 mb-0" id="faIconPickerModalLabel">Bibliothèque d'Icônes Font Awesome</h5>
                            <span class="badge rounded-pill bg-primary font-mono text-white px-2 py-0.5" id="faTotalCountBadge" style="font-size: 0.68rem;">2,163 icônes Free</span>
                        </div>
                        <small class="text-muted" id="faIconPickerSubtitle">Recherchez et appliquez n'importe quelle icône gratuite parmi les 2,163 icônes Font Awesome Free.</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body p-4">
                <div class="position-relative mb-3">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="faIconSearchInput" class="form-control studio-input" style="padding-left: 2.5rem; padding-right: 2.5rem; height: 42px;" placeholder="Rechercher parmi les 2,163 icônes (whatsapp, phone, email, pdf, cart, heart...)" oninput="filterFaIcons(this.value)">
                    <button type="button" class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y me-2 text-muted" id="faClearSearchBtn" onclick="clearFaSearch()" style="display: none;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-1.5 mb-3.5 overflow-x-auto pb-1" id="faCategoryFilterPills" style="scrollbar-width: none;">
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill active" data-cat="all" onclick="selectFaCategory('all')">
                        <i class="fa-solid fa-border-all me-1"></i>Toutes (<span id="faActiveCount">2,163</span>)
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill" data-cat="brands" onclick="selectFaCategory('brands')">
                        <i class="fa-brands fa-whatsapp me-1 text-success"></i>Réseaux & Marques
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill" data-cat="contact" onclick="selectFaCategory('contact')">
                        <i class="fa-solid fa-phone me-1 text-primary"></i>Contact & Appel
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill" data-cat="business" onclick="selectFaCategory('business')">
                        <i class="fa-solid fa-briefcase me-1 text-warning"></i>Commerce & Pro
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill" data-cat="media" onclick="selectFaCategory('media')">
                        <i class="fa-solid fa-globe me-1 text-info"></i>Médias & Web
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill" data-cat="lifestyle" onclick="selectFaCategory('lifestyle')">
                        <i class="fa-solid fa-star me-1 text-danger"></i>Style & Symboles
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill px-3 py-1 fw-semibold fa-cat-pill" data-cat="general" onclick="selectFaCategory('general')">
                        <i class="fa-solid fa-shapes me-1 text-secondary"></i>Général
                    </button>
                </div>

                <div class="fa-icon-grid" id="faIconGrid"></div>

                <div id="faNoIconsFound" class="text-center py-5 text-muted" style="display: none;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-2.5" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-magnifying-glass fs-4 text-secondary opacity-50"></i>
                    </div>
                    <p class="mb-1 fw-bold text-dark">Aucune icône trouvée</p>
                    <small class="text-muted">Essayez un autre mot-clé (phone, chat, web, card, file...)</small>
                </div>
            </div>

            <div class="modal-footer border-top py-2.5 px-4 bg-light d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted font-mono" style="font-size: 0.76rem;">Sélection :</span>
                    <span class="badge bg-white text-dark border font-mono small py-1.5 px-2.5 d-inline-flex align-items-center gap-2 shadow-2xs" id="faCurrentSelectionBadge">
                        <i class="fa-solid fa-link text-primary fs-6" id="faSelectedIconPreview"></i>
                        <span id="faSelectedIconClass" style="font-size: 0.78rem;">fa-solid fa-link</span>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-light border px-3 rounded-pill" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-sm text-white px-4 rounded-pill fw-semibold shadow-sm" id="faApplyIconBtn" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                        <i class="fa-solid fa-check me-1"></i>Appliquer l'icône
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- LIVE SMARTPHONE QR CODE SCAN TESTER MODAL                               -->
<!-- ======================================================================= -->
<div class="modal fade" id="liveQrPreviewModal" tabindex="-1" aria-labelledby="liveQrPreviewModalLabel" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light bg-opacity-75 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 38px; height: 38px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <i class="fa-solid fa-qrcode fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="liveQrPreviewModalLabel">Tester la Carte par QR Code</h6>
                        <small class="text-muted" style="font-size: 0.74rem;">Scannez directement avec votre smartphone réel</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm mb-3 position-relative" style="border-color: #e2e8f0 !important;">
                    <img id="modalQrCodeImg" src="" alt="QR Code Test" class="img-fluid rounded-3" style="width: 220px; height: 220px; object-fit: contain;">
                    <div class="position-absolute top-50 start-50 translate-middle rounded-circle bg-white shadow-sm p-1.5 border" style="width: 44px; height: 44px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white w-100 h-100" style="background: #4f46e5;">
                            <i class="fa-solid fa-bolt small"></i>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="fw-bold text-dark mb-0.5" id="modalCardHolderName">Marcus Vance</div>
                    <div class="text-muted small" id="modalCardJobCompany">Directeur Général &bull; YNS Smart Solutions</div>
                </div>
                <div class="p-2.5 px-3 rounded-3 bg-light border d-flex align-items-center justify-content-between gap-2 text-start mb-3">
                    <div class="min-w-0">
                        <span class="small text-muted font-mono d-block" style="font-size: 0.68rem;">Lien direct :</span>
                        <span class="small font-mono fw-bold text-primary text-truncate d-block" id="modalTargetUrlText" style="font-size: 0.78rem;"></span>
                    </div>
                    <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark shadow-2xs" onclick="copyCardUrlToClipboard()">
                        <i class="fa-regular fa-copy me-1"></i><span id="modalCopyBtnText">Copier</span>
                    </button>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <a id="modalDownloadQrLink" href="#" target="_blank" download="qrcode-carte.png" class="btn btn-sm btn-studio-primary text-white rounded-pill px-4 py-2 fw-semibold text-decoration-none">
                        <i class="fa-solid fa-download me-1.5"></i>Télécharger QR
                    </a>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* -------------------------------------------------------------------------- */
/* Modern Design System, Layout Alignment & Micro-Aesthetics                 */
/* Aligned with Dynamic QR Studio Design System                              */
/* -------------------------------------------------------------------------- */
.card-builder-container {
    max-width: 1440px;
    margin: 0 auto;
}

/* Fractional Spacing & Pro Utility System (Tailwind-grade precision for BS5) */
.gap-1\.5 { gap: 0.375rem !important; }
.gap-2\.5 { gap: 0.625rem !important; }
.gap-3\.5 { gap: 1.125rem !important; }
.g-2\.5, .gx-2\.5 { --bs-gutter-x: 0.625rem; }
.g-2\.5, .gy-2\.5 { --bs-gutter-y: 0.625rem; }
.g-3\.5, .gx-3\.5 { --bs-gutter-x: 1.125rem; }
.g-3\.5, .gy-3\.5 { --bs-gutter-y: 1.125rem; }

.p-0\.5 { padding: 0.125rem !important; }
.p-1\.5 { padding: 0.375rem !important; }
.p-2\.5 { padding: 0.625rem !important; }
.p-3\.5 { padding: 1.125rem !important; }
.px-1\.5 { padding-left: 0.375rem !important; padding-right: 0.375rem !important; }
.px-2\.5 { padding-left: 0.625rem !important; padding-right: 0.625rem !important; }
.px-3\.5 { padding-left: 1.125rem !important; padding-right: 1.125rem !important; }
.py-0\.5 { padding-top: 0.125rem !important; padding-bottom: 0.125rem !important; }
.py-1\.5 { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
.py-2\.5 { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
.pt-1\.5 { padding-top: 0.375rem !important; }
.pb-2\.5 { padding-bottom: 0.625rem !important; }
.ps-2\.5 { padding-left: 0.625rem !important; }

.m-1\.5 { margin: 0.375rem !important; }
.mb-0\.5 { margin-bottom: 0.125rem !important; }
.mb-1\.5 { margin-bottom: 0.375rem !important; }
.mb-2\.5 { margin-bottom: 0.625rem !important; }
.mb-3\.5 { margin-bottom: 1.125rem !important; }
.mt-0\.5 { margin-top: 0.125rem !important; }
.mt-1\.5 { margin-top: 0.375rem !important; }
.mt-2\.5 { margin-top: 0.625rem !important; }
.mt-3\.5 { margin-top: 1.125rem !important; }
.me-0\.5 { margin-right: 0.125rem !important; }
.me-1\.5 { margin-right: 0.375rem !important; }
.ms-0\.5 { margin-left: 0.125rem !important; }
.ms-1\.5 { margin-left: 0.375rem !important; }
.ms-2\.5 { margin-left: 0.625rem !important; }

.font-mono { font-family: 'JetBrains Mono', var(--bs-font-monospace), monospace !important; }
.fw-extrabold { font-weight: 800 !important; }
.min-w-0 { min-width: 0 !important; }
.border-dashed { border-style: dashed !important; }
.opacity-40 { opacity: 0.4 !important; }
.opacity-60 { opacity: 0.6 !important; }
.opacity-70 { opacity: 0.7 !important; }
.opacity-80 { opacity: 0.8 !important; }
.opacity-90 { opacity: 0.9 !important; }
.text-opacity-85 { opacity: 0.85 !important; }

.btn-xs {
    padding: 0.28rem 0.65rem;
    font-size: 0.74rem;
    line-height: 1.35;
    border-radius: 0.45rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-white {
    background-color: #ffffff !important;
    color: #1e293b !important;
    border: 1px solid #e2e8f0 !important;
    transition: all 0.15s ease;
}
.btn-white:hover:not(:disabled) {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}

.section-card {
    scroll-margin-top: 135px;
}

.cursor-pointer { cursor: pointer; }
.cursor-grab { cursor: grab; }
.cursor-grab:active { cursor: grabbing; }
.shadow-2xs { box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
.shadow-xs { box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03); }
.shadow-2xl { box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35); }
.text-indigo { color: #4f46e5 !important; }

/* Executive Hero Card & Studio Card Surface */
.studio-hero-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04) !important;
}
.studio-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.03) !important;
}

/* Radar Pulse Dot */
.status-dot-radar {
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
    display: inline-block;
    position: relative;
}
.status-dot-radar::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    border: 2px solid #10b981;
    animation: radarPulse 2s infinite ease-out;
}
@keyframes radarPulse {
    0% { transform: scale(0.8); opacity: 0.8; }
    100% { transform: scale(2.2); opacity: 0; }
}

/* Studio Buttons */
.btn-studio-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
    border: none !important;
    color: #ffffff !important;
    transition: all 0.2s ease;
}
.btn-studio-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3) !important;
    color: #ffffff !important;
}
.btn-studio-outline {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #334155 !important;
    transition: all 0.15s ease;
}
.btn-studio-outline:hover {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}

/* Studio Segmented Pill Tabs */
.studio-tabs-nav {
    background: #f8fafc !important;
}
.studio-tabs-nav .nav-link {
    color: #64748b !important;
    border: 1px solid transparent !important;
    background: transparent !important;
    transition: all 0.18s ease;
    padding: 0.4rem 0.9rem !important;
}
.studio-tabs-nav .nav-link:hover {
    color: #0f172a !important;
    background: rgba(255, 255, 255, 0.6) !important;
}
.studio-tabs-nav .nav-link.active {
    background: #ffffff !important;
    color: #4f46e5 !important;
    border-color: #e2e8f0 !important;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05) !important;
}

/* Studio Step Badge */
.studio-step-badge,
.section-step-badge {
    width: 26px;
    height: 26px;
    border-radius: 8px;
    background: #eef2ff;
    color: #4f46e5;
    font-weight: 700;
    font-size: 0.78rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: var(--bs-font-monospace);
    flex-shrink: 0;
}

/* Studio Form Controls (Strict 42px Unified Height Alignment) */
.studio-label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #475569;
    margin-bottom: 0.45rem;
}
.studio-input,
.studio-select,
.channel-input {
    height: 42px !important;
    border-radius: 10px !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 0.88rem !important;
    color: #0f172a !important;
    background-color: #ffffff !important;
    padding: 0.5rem 0.85rem !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.studio-input:focus,
.studio-select:focus,
.channel-input:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    outline: none;
}
.studio-input-textarea {
    border-radius: 10px !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 0.88rem !important;
    padding: 0.65rem 0.85rem !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.studio-input-textarea:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    outline: none;
}
.studio-input-group.has-prefix .studio-input-addon {
    height: 42px !important;
    border-radius: 10px 0 0 10px !important;
    border: 1px solid #cbd5e1 !important;
    border-right: 0 !important;
    background: #f8fafc !important;
    color: #64748b !important;
    font-size: 0.88rem !important;
    padding: 0 0.85rem !important;
}
.studio-input-group.has-prefix .studio-input,
.studio-input-group.has-prefix .studio-select {
    border-radius: 0 10px 10px 0 !important;
}
.studio-color-capsule {
    height: 42px !important;
    border-color: #cbd5e1 !important;
    border-radius: 10px !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.studio-color-capsule:focus-within {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
.studio-range::-webkit-slider-thumb {
    background: #4f46e5;
}

/* CAD Dot-Grid Simulator Stage */
.studio-canvas-stage {
    background-color: #f8fafc;
    background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
    background-size: 16px 16px;
}

/* Command Bar Legacy Compat */
.version-pill {
    background: #4f46e5;
    font-size: 0.65rem;
    font-weight: 600;
}
.mode-pulse-indicator {
    width: 7px;
    height: 7px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
}

/* Quick Navigation Sticky Pill Bar */
.sticky-nav-container {
    position: sticky;
    top: 72px;
    z-index: 30;
    padding: 4px 0;
}
.quick-nav-pill {
    color: #64748b !important;
    font-size: 0.8rem;
    border-radius: 10px !important;
    transition: all 0.18s ease;
    white-space: nowrap;
}
.quick-nav-pill:hover {
    color: #0f172a !important;
    background: #f1f5f9 !important;
}
.quick-nav-pill.active {
    color: #4f46e5 !important;
    background: #ffffff !important;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06) !important;
    font-weight: 700 !important;
}
.nav-step-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #cbd5e1;
    display: inline-block;
    margin-left: 5px;
    transition: all 0.2s ease;
}
.nav-step-dot.is-done {
    background: #10b981;
    box-shadow: 0 0 6px rgba(16, 185, 129, 0.7);
}
.quick-nav-progress-track {
    height: 3px;
    background: #f1f5f9;
    border-radius: 99px;
    overflow: hidden;
    margin-top: 5px;
}
.quick-nav-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5 0%, #10b981 100%);
    transition: width 0.3s ease;
}

/* Template Presets Mini Mockups */
.template-preset-card {
    border-color: #e2e8f0 !important;
    position: relative;
    transition: all 0.2s ease;
}
.template-preset-card:hover {
    border-color: #6320ee !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 32, 238, 0.08);
}
.template-preset-card.active {
    border-color: #6320ee !important;
    background-color: rgba(99, 32, 238, 0.03) !important;
    box-shadow: 0 0 0 2px rgba(99, 32, 238, 0.18), 0 6px 16px rgba(99, 32, 238, 0.08);
}
.template-preset-card .preset-check-icon {
    display: none;
}
.template-preset-card.active .preset-check-icon {
    display: inline-block;
}
.preset-color-dot {
    width: 13px;
    height: 13px;
    border-radius: 50%;
    display: inline-block;
    border: 1px solid rgba(255,255,255,0.5);
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.preset-mini-preview {
    height: 48px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    overflow: hidden;
}
.preset-mini-preview .mini-banner {
    width: 14px;
    height: 100%;
    border-radius: 4px;
    background: #4f46e5;
}
.preset-mini-preview.executive-preview .mini-banner { background: #0ea5e9; }
.preset-mini-preview.creative-preview .mini-banner { background: #ec4899; }
.preset-mini-preview .mini-avatar {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #cbd5e1;
}
.preset-mini-preview .mini-lines {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}
.preset-mini-preview .mini-lines span:nth-child(1) {
    height: 4px;
    width: 75%;
    background: #94a3b8;
    border-radius: 2px;
}
.preset-mini-preview .mini-lines span:nth-child(2) {
    height: 4px;
    width: 45%;
    background: #cbd5e1;
    border-radius: 2px;
}

/* Media Upload Box */
.media-upload-box {
    border-color: #e2e8f0 !important;
    transition: border-color 0.18s ease;
}
.media-upload-box:hover {
    border-color: #6320ee !important;
}
.media-thumbnail-wrap {
    width: 58px;
    height: 58px;
}
.media-avatar-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.media-hover-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.18s ease;
}
.media-thumbnail-wrap:hover .media-hover-overlay {
    opacity: 1;
}
.media-cover-thumb {
    height: 58px;
    background: linear-gradient(135deg, #4f46e5 0%, #1e1b4b 100%);
    background-size: cover;
    background-position: center;
}
.gradient-swatch-chip {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    display: inline-block;
    cursor: pointer;
    border: 1px solid rgba(255,255,255,0.7);
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.gradient-swatch-chip:hover {
    transform: scale(1.22);
    box-shadow: 0 3px 8px rgba(0,0,0,0.25);
}

/* Palette Quick Picks */
.palette-quick-pick {
    transition: all 0.15s ease;
}
.palette-quick-pick:hover {
    border-color: #6320ee !important;
    color: #6320ee !important;
    transform: translateY(-1px);
}

/* Channel Card Rows (PRO REPEATER ITEM STYLES) */
.channel-card-row {
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    background: #ffffff;
    padding: 1.25rem 1.35rem !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02) !important;
    transition: border-color 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
}
.channel-card-row:hover {
    border-color: #cbd5e1 !important;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.02) !important;
    transform: translateY(-1px);
}
.channel-card-row.is-dragging {
    opacity: 0.35;
    border: 2px dashed #4f46e5 !important;
}
.channel-order-badge {
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-family: var(--bs-font-monospace);
    font-size: 0.72rem;
    font-weight: 700;
}
.channel-icon-trigger-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #4f46e5;
    cursor: pointer;
    position: relative;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    flex-shrink: 0;
}
.channel-icon-trigger-box:hover {
    background: #eef2ff;
    border-color: #6366f1;
    transform: scale(1.06);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}
.channel-icon-trigger-box .edit-badge-dot {
    position: absolute;
    bottom: -3px;
    right: -3px;
    width: 16px;
    height: 16px;
    background: #4f46e5;
    color: #ffffff;
    border-radius: 50%;
    font-size: 0.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.channel-icon-tag {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 2px 8px;
    color: #64748b;
    font-family: var(--bs-font-monospace);
    font-size: 0.72rem;
    font-weight: 500;
    transition: all 0.15s ease;
}
.channel-icon-tag:hover {
    background: #eef2ff;
    border-color: #c7d2fe;
    color: #4f46e5;
}
.channel-drag-handle {
    cursor: grab;
    color: #94a3b8;
    padding: 2px 4px;
    transition: color 0.15s ease;
}
.channel-drag-handle:hover {
    color: #334155;
}
.channel-action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    transition: all 0.15s ease;
    font-size: 0.8rem;
    cursor: pointer;
}
.channel-action-btn:hover:not(:disabled) {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}
.channel-action-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.channel-action-btn.delete-btn:hover {
    background: #fff1f2;
    color: #e11d48;
    border-color: #fecdd3;
}
.channel-divider {
    height: 1px;
    background-color: #f1f5f9;
    margin: 14px 0 16px 0;
}
.channel-field-label {
    font-size: 0.76rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
}
.btn-add-channel {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.28);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-add-channel:hover {
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.38);
}
.quick-add-dock {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    padding: 18px 20px;
    margin-top: 24px;
}
.quick-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 6px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none;
    cursor: pointer;
}
.quick-pill:hover {
    border-color: #4f46e5 !important;
    color: #4f46e5 !important;
    background: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.12);
}
.empty-channels-card {
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    background: #f8fafc;
    padding: 42px 24px;
    text-align: center;
}
.empty-channels-icon-wrap {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 14px;
}

/* Font Awesome Icon Picker Modal Styling */
.fa-cat-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
    white-space: nowrap;
    transition: all 0.15s ease;
}
.fa-cat-pill:hover {
    border-color: #4f46e5;
    color: #4f46e5;
}
.fa-cat-pill.active {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.28);
}
.fa-icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(88px, 1fr));
    gap: 8px;
    max-height: 420px;
    overflow-y: auto;
    padding: 4px;
}
.fa-icon-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 10px 4px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.16s ease;
    text-align: center;
    height: 80px;
}
.fa-icon-card i {
    font-size: 1.35rem;
    margin-bottom: 6px;
    color: #334155;
    transition: transform 0.16s ease, color 0.16s ease;
}
.fa-icon-card .icon-label {
    font-size: 0.68rem;
    color: #64748b;
    max-width: 90%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-family: var(--bs-font-monospace);
}
.fa-icon-card:hover {
    border-color: #4f46e5;
    background: #f5f3ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.12);
}
.fa-icon-card:hover i {
    color: #4f46e5;
    transform: scale(1.15);
}
.fa-icon-card.is-selected {
    border-color: #4f46e5 !important;
    background: #eef2ff !important;
    box-shadow: 0 0 0 2px #4f46e5 !important;
}
.fa-icon-card.is-selected i {
    color: #4f46e5 !important;
}
.fa-icon-card.is-selected .icon-label {
    color: #4f46e5 !important;
    font-weight: 700;
}

/* Option 2: Visual Drag & Drop Studio */
.widget-cat-filter-btn {
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 11px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.widget-cat-filter-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}
.widget-cat-filter-btn.active {
    background: #6320ee !important;
    border-color: #6320ee !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(99, 32, 238, 0.25);
}
.draggable-widget-card {
    transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease;
    user-select: none;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff;
    border-radius: 12px;
    cursor: grab;
    position: relative;
}
.draggable-widget-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -4px rgba(99, 32, 238, 0.14), 0 2px 6px -1px rgba(0, 0, 0, 0.04);
    border-color: #6320ee !important;
}
.draggable-widget-card:active {
    cursor: grabbing;
}
.widget-icon-avatar {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 0.92rem;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
}
.widget-add-btn {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    font-size: 0.72rem;
    transition: all 0.15s ease;
    padding: 0;
}
.draggable-widget-card:hover .widget-add-btn,
.widget-add-btn:hover {
    background: #6320ee !important;
    border-color: #6320ee !important;
    color: #ffffff !important;
    transform: scale(1.1);
}
.dnd-drop-zone {
    width: 100%;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.canvas-block-item {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: var(--card-radius, 14px);
    padding: 0.68rem 0.85rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.65rem;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.18s ease;
    position: relative;
    user-select: none;
}
.canvas-block-item:hover {
    border-color: rgba(255, 255, 255, 0.28);
    background: rgba(255, 255, 255, 0.13);
    transform: translateY(-1.5px);
}
.canvas-block-item.active-selected {
    border-color: var(--card-primary, #6320ee) !important;
    box-shadow: 0 0 0 2px var(--card-primary, #6320ee), 0 8px 24px rgba(0, 0, 0, 0.35) !important;
    background: rgba(99, 32, 238, 0.22) !important;
}
.canvas-icon-box {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: var(--card-primary, #6320ee);
    flex-shrink: 0;
}
.status-dot-pulse {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
    animation: pulseDot 2s infinite;
}
@keyframes pulseDot {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
    70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Realistic Smartphone Device Mockup Frame */
.sticky-preview-wrapper {
    position: -webkit-sticky !important;
    position: sticky !important;
    top: 75px !important;
    z-index: 40;
    will-change: transform;
}
@media (max-height: 840px) {
    .phone-frame-mockup {
        height: 560px;
        width: 310px;
    }
    .sticky-preview-wrapper {
        top: 65px !important;
    }
}
.phone-frame-mockup {
    width: 335px;
    height: 645px;
    background: #0f172a;
    border-radius: 46px;
    border: 9px solid #1e293b;
    box-shadow: 0 25px 65px -12px rgba(15, 23, 42, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.12);
    overflow: hidden;
    position: relative;
    transition: all 0.25s ease;
}

/* Device frame variations */
.phone-frame-mockup.is-android {
    border-radius: 36px;
    border-width: 9px;
}
.phone-frame-mockup.is-android .phone-dynamic-island {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    top: 10px;
    padding: 0;
}
.phone-frame-mockup.is-android .island-speaker,
.phone-frame-mockup.is-android .island-camera {
    display: none;
}
.phone-frame-mockup.is-frameless {
    border: 3px solid #cbd5e1;
    border-radius: 28px;
    box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.2);
}
.phone-frame-mockup.is-frameless .phone-dynamic-island,
.phone-frame-mockup.is-frameless .phone-metal-glare {
    display: none;
}

/* Stainless Steel / Titanium Outer Glare */
.phone-metal-glare {
    position: absolute;
    top: 0;
    left: -60%;
    width: 220%;
    height: 100%;
    background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.06) 48%, rgba(255,255,255,0.12) 50%, rgba(255,255,255,0.04) 52%, transparent 60%);
    pointer-events: none;
    z-index: 95;
}

.phone-dynamic-island {
    position: absolute;
    top: 7px;
    left: 50%;
    transform: translateX(-50%);
    width: 86px;
    height: 20px;
    background: #000000;
    border-radius: 20px;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
    pointer-events: none;
}
.island-camera {
    width: 7px;
    height: 7px;
    background: #1e293b;
    border-radius: 50%;
}
.island-speaker {
    width: 28px;
    height: 3px;
    background: #1e293b;
    border-radius: 2px;
    margin-right: 6px;
}

.phone-screen-content {
    --card-primary: #4f46e5;
    --card-bg: #0f172a;
    --card-font: 'Outfit', sans-serif;
    --card-radius: 16px;
    --card-spacing: 12px;
    --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);

    width: 100%;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    background-color: var(--card-bg);
    color: #ffffff;
    font-family: var(--card-font);
    display: flex;
    flex-direction: column;
    scrollbar-width: none;
    scroll-behavior: smooth;
}
.phone-screen-content::-webkit-scrollbar { display: none; }
.phone-screen-content > * {
    flex-shrink: 0;
}

.phone-status-bar {
    position: sticky;
    top: 0;
    z-index: 80;
    padding: 9px 18px 5px 18px;
    background: rgba(15, 23, 42, 0.88);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: rgba(255, 255, 255, 0.88);
    flex-shrink: 0;
}

.preview-cover-banner {
    width: 100%;
    height: 130px;
    background: linear-gradient(135deg, var(--card-primary) 0%, #1e1b4b 100%);
    background-size: cover;
    background-position: center;
    position: relative;
    flex-shrink: 0;
}
.cover-gradient-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 30%, rgba(0,0,0,0.45) 100%);
}

.preview-profile-wrap {
    margin-top: -46px;
    position: relative;
    z-index: 5;
    flex-shrink: 0;
}

.preview-avatar-img {
    width: 82px;
    height: 82px;
    border-radius: var(--card-radius);
    object-fit: cover;
    border: 3.5px solid var(--card-bg);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.4);
    background: var(--card-bg);
}

.preview-badge-pill {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.12);
    color: var(--card-primary);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.preview-company-node {
    color: var(--card-primary);
}

.preview-save-btn {
    background-color: var(--card-primary);
    border-color: var(--card-primary);
    color: #ffffff !important;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.preview-save-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
}

.preview-link-card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: var(--card-radius);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: var(--card-shadow);
    padding: 0.68rem 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #ffffff;
    text-decoration: none;
    margin-bottom: var(--card-spacing);
    transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
}
.preview-link-card:hover {
    transform: translateY(-1px);
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.preview-link-icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    color: var(--card-primary);
    flex-shrink: 0;
}

.preview-footer {
    flex-shrink: 0;
    width: 100%;
    margin-top: 24px;
    padding-bottom: 24px;
    text-align: center;
    position: relative;
    z-index: 2;
    clear: both;
}

/* Interactive Menu Builder & Simulator Overlay Styles */
.menu-nav-option-card {
    background: #ffffff;
    border-color: #e2e8f0 !important;
    transition: all 0.18s ease;
}
.menu-nav-option-card:hover {
    border-color: #4f46e5 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px -4px rgba(79, 70, 229, 0.1);
}
.menu-nav-option-card.active {
    border-color: #4f46e5 !important;
    background: #f5f3ff !important;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.25);
}
.menu-category-box {
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    overflow: hidden;
    transition: border-color 0.18s ease, box-shadow 0.18s ease;
}
.menu-category-box:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px -4px rgba(15, 23, 42, 0.06);
}
.menu-category-header {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}
.menu-page-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.28rem 0.7rem;
    border-radius: 999px;
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}
.menu-cover-panel {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.9rem 1rem;
    background: #fbfcfe;
    border-bottom: 1px solid #eef2f7;
}
.menu-cover-preview {
    width: 160px;
    height: 72px;
    flex-shrink: 0;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    background: repeating-linear-gradient(45deg, #f8fafc, #f8fafc 8px, #f1f5f9 8px, #f1f5f9 16px);
    display: flex;
    align-items: center;
    justify-content: center;
}
.menu-cover-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    color: #94a3b8;
    font-size: 0.64rem;
}
.menu-cover-empty i { font-size: 1.25rem; }
.menu-items-list {
    padding: 0.9rem 1rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}
.menu-items-empty {
    text-align: center;
    padding: 1.25rem;
    border: 1.5px dashed #cbd5e1;
    border-radius: 12px;
    color: #94a3b8;
    font-size: 0.8rem;
}
.menu-items-empty i { display: block; font-size: 1.4rem; margin-bottom: 0.35rem; }
.menu-item-row {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.85rem;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.menu-item-row:hover {
    border-color: #c7d2fe;
    box-shadow: 0 6px 16px -6px rgba(79, 70, 229, 0.15);
}
.menu-item-row.is-unavailable { background: #fafafa; }
.menu-item-row.is-unavailable .menu-item-fields { opacity: 0.6; }
.menu-item-thumb-col {
    width: 84px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.4rem;
}
.menu-item-thumb {
    width: 84px;
    height: 84px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.menu-upload-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    padding: 0.28rem 0.75rem;
    border-radius: 999px;
    border: 1px solid #c7d2fe;
    background: #eef2ff;
    color: #4338ca;
    font-size: 0.68rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s ease, transform 0.15s ease;
}
.menu-upload-btn:hover { background: #e0e7ff; transform: translateY(-1px); }
.menu-item-fields { flex: 1 1 auto; min-width: 0; }
.menu-field-label {
    display: block;
    margin-bottom: 0.2rem;
    font-size: 0.66rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: #64748b;
}
.menu-item-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.4rem;
    flex-shrink: 0;
}
@media (max-width: 767.98px) {
    .menu-item-row { flex-wrap: wrap; }
    .menu-item-fields { flex-basis: 100%; order: 3; }
    .menu-item-actions { flex-direction: row; margin-left: auto; }
    .menu-cover-panel { flex-direction: column; }
    .menu-cover-preview { width: 100%; height: 110px; }
}

/* Menu insights & toolbar */
.menu-stats-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.menu-stat-card {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.7rem 0.85rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.menu-stat-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.menu-stat-value { font-weight: 800; font-size: 1rem; color: #0f172a; line-height: 1.1; }
.menu-stat-label { font-size: 0.68rem; color: #64748b; font-weight: 600; }
.menu-builder-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.6rem 0.75rem;
    margin-bottom: 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}
.menu-builder-search { max-width: 320px; flex: 1 1 220px; }
.menu-tool-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.32rem 0.7rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    color: #334155;
    font-size: 0.72rem;
    font-weight: 600;
    cursor: pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
}
.menu-tool-btn:hover { border-color: #c7d2fe; background: #f5f3ff; }
.menu-tool-sep { width: 1px; height: 20px; background: #e2e8f0; }
.menu-category-box.is-collapsed .menu-cover-panel,
.menu-category-box.is-collapsed .menu-items-list,
.menu-category-box.is-collapsed .menu-category-footer { display: none !important; }
.menu-category-box.is-collapsed .menu-category-header { border-bottom: 0; }
@media (max-width: 991.98px) {
    .menu-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .menu-tool-btn span { display: none; }
}

/* Light-Theme Simulator Auto-Contrast Rules */
.phone-screen-content.is-light-theme {
    color: #0f172a;
}
.phone-screen-content.is-light-theme .phone-status-bar {
    background: rgba(255, 255, 255, 0.9);
    color: #0f172a;
    border-bottom-color: #e2e8f0;
}
.phone-screen-content.is-light-theme .preview-badge-pill {
    background: rgba(79, 70, 229, 0.08);
    border-color: rgba(79, 70, 229, 0.22);
}
.phone-screen-content.is-light-theme .preview-link-card,
.phone-screen-content.is-light-theme .canvas-block-item {
    background: #ffffff;
    border-color: #e2e8f0;
    color: #0f172a;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}
.phone-screen-content.is-light-theme .preview-link-card:hover,
.phone-screen-content.is-light-theme .canvas-block-item:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.phone-screen-content.is-light-theme .preview-link-icon-wrap,
.phone-screen-content.is-light-theme .canvas-icon-box {
    background: rgba(79, 70, 229, 0.1);
}

.dnd-drop-zone.drag-over-active {
    background: rgba(99, 32, 238, 0.12);
    border-radius: 16px;
    outline: 2px dashed #6320ee;
    outline-offset: 4px;
}

.simulator-menu-drawer.is-light-menu {
    color: #0f172a;
}
.simulator-menu-drawer.is-light-menu .sim-menu-topbar {
    background: rgba(255, 255, 255, 0.92);
    border-bottom-color: #e2e8f0;
}
.simulator-menu-drawer.is-light-menu .sim-menu-tab-btn {
    background: #ffffff;
    border-color: #cbd5e1;
    color: #334155;
}

/* Menu builder sub-sections */
.menu-sub-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #eef2f7;
}
.menu-sub-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.menu-sub-title-bar {
    flex-wrap: wrap;
    margin-bottom: 1rem;
}
.menu-sub-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.menu-sub-heading {
    font-weight: 800;
    font-size: 0.9rem;
    color: #0f172a;
    line-height: 1.2;
}
.menu-sub-hint {
    font-size: 0.74rem;
    color: #64748b;
}
.menu-toggle-bar {
    padding: 0.7rem 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}
#menuCustomAccentWrap .studio-color-well,
#menuCustomBgWrap .studio-color-well {
    width: 32px;
    height: 28px;
    padding: 0;
    border: 0;
    border-radius: 8px;
    background: transparent;
    cursor: pointer;
}
.simulator-menu-drawer {
    position: absolute;
    inset: 0;
    top: 28px;
    z-index: 90;
    background: var(--card-bg, #0f172a);
    color: #ffffff;
    overflow-y: auto;
    scrollbar-width: none;
    animation: slideUpMenuDrawer 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}
.simulator-menu-drawer::-webkit-scrollbar {
    display: none;
}
@keyframes slideUpMenuDrawer {
    from { opacity: 0; transform: translateY(18px); }
    to { opacity: 1; transform: translateY(0); }
}
.sim-menu-topbar {
    position: sticky;
    top: 0;
    z-index: 10;
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.sim-menu-tab-btn {
    border: 1px solid rgba(255, 255, 255, 0.16);
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.75);
    border-radius: 999px;
    padding: 0.28rem 0.7rem;
    font-size: 0.68rem;
    font-weight: 700;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.15s ease;
}
.sim-menu-tab-btn.active {
    background: var(--card-primary, #4f46e5);
    border-color: var(--card-primary, #4f46e5);
    color: #ffffff;
}
.sim-menu-item-card {
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: var(--card-radius, 12px);
    padding: 0.65rem 0.75rem;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/fa-free-icons.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------------------
    // 1. Initial State & Elements
    // -------------------------------------------------------------------------
    const presets = @json($presets);
    presets.gourmet = presets.gourmet || {
        name: 'Gourmet & Prestige',
        primary_color: '#d97706',
        background_color: '#18181b',
        font_family: 'Playfair Display',
        border_radius: '14px',
        shadow_style: 'deep',
        element_spacing: '14px'
    };

    let initialBlocks = @json($defaultBlocks);

    const screenEl = document.getElementById('phoneScreenPreview');
    const dndScreenEl = document.getElementById('dndScreenPreview');
    const nameInput = document.getElementById('input_full_name');
    const titleInput = document.getElementById('input_title');
    const jobInput = document.getElementById('input_job_title');
    const companyInput = document.getElementById('input_company');
    const bioInput = document.getElementById('input_bio');
    const badgeInput = document.getElementById('input_badge_text');

    const primaryColorPicker = document.getElementById('primaryColorPicker');
    const primaryColorText = document.getElementById('primaryColorText');
    const bgColorPicker = document.getElementById('bgColorPicker');
    const bgColorText = document.getElementById('bgColorText');
    const fontSelect = document.getElementById('fontFamilySelect');
    const shadowSelect = document.getElementById('shadowStyleSelect');
    const radiusSlider = document.getElementById('borderRadiusSlider');
    const radiusBadge = document.getElementById('borderRadiusBadge');

    const previewCover = document.getElementById('previewCoverBanner');
    const previewAvatar = document.getElementById('previewAvatarImg');
    const dndCover = document.getElementById('dndCoverBanner');
    const dndAvatar = document.getElementById('dndAvatarImg');
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const coverPhotoInput = document.getElementById('coverPhotoInput');
    const profileThumbnail = document.getElementById('profileThumbnail');
    const coverThumbnail = document.getElementById('coverThumbnail');

    const blocksContainer = document.getElementById('blocksContainer');
    const previewLinksContainer = document.getElementById('previewLinksContainer');
    const dndDropZone = document.getElementById('dndDropZone');
    const dndEmptyPlaceholder = document.getElementById('dndEmptyPlaceholder');
    const themeJsonInput = document.getElementById('theme_config_json');
    const contentBlocksJsonInput = document.getElementById('content_blocks_json');

    let currentBlocks = JSON.parse(JSON.stringify(initialBlocks));
    let currentMenu = @json($defaultMenu);
    let activeOptimizedBlockId = null;
    let activePickerTargetBlockId = null;
    let activePickerTargetCategoryId = null;
    let selectedFaIconClass = 'fa-solid fa-link';

    function isLightHexColor(hex) {
        const clean = String(hex || '#0f172a').replace('#', '').trim();
        if (clean.length !== 6) return false;
        const r = parseInt(clean.substring(0, 2), 16) / 255;
        const g = parseInt(clean.substring(2, 4), 16) / 255;
        const b = parseInt(clean.substring(4, 6), 16) / 255;
        const luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b;
        return luminance > 0.62;
    }

    // -------------------------------------------------------------------------
    // 2. Comprehensive Font Awesome Free 6 (2,163 Icons) Catalog
    // -------------------------------------------------------------------------
    const rawFaCatalog = window.FONT_AWESOME_FREE_ICONS || [];
    const faIconCatalog = rawFaCatalog.map(item => ({
        class: item.c || item.class,
        name: String(item.n || item.name || ''),
        label: item.l || item.label || String(item.n || ''),
        cat: item.cat || 'general',
        tags: item.t || item.tags || ''
    }));

    function renderIconElement(iconClass) {
        if (!iconClass) return '<i class="fa-solid fa-link"></i>';
        const ic = iconClass.trim();
        if (ic.includes('fa-') || ic.startsWith('fas ') || ic.startsWith('fab ') || ic.startsWith('far ')) {
            return `<i class="${ic}"></i>`;
        }
        if (ic.startsWith('bi-') || ic.startsWith('bi ')) {
            const clean = ic.startsWith('bi ') ? ic : 'bi ' + ic;
            return `<i class="${clean}"></i>`;
        }
        return `<i class="fa-solid fa-${ic}"></i>`;
    }

    // -------------------------------------------------------------------------
    // 3. Font Awesome Icon Picker Modal Operations
    // -------------------------------------------------------------------------
    let currentFaFilterCat = 'all';
    let currentFaSearchTerm = '';
    let currentMatchedIcons = [];
    let faBatchRenderIndex = 0;
    const FA_BATCH_SIZE = 96;

    window.openFaIconPicker = function(blockId, categoryId) {
        activePickerTargetBlockId = blockId || null;
        activePickerTargetCategoryId = categoryId || null;
        const modalEl = document.getElementById('faIconPickerModal');
        if (!modalEl) return;

        if (blockId) {
            const block = currentBlocks.find(b => b.id === blockId);
            if (block && block.icon) {
                selectedFaIconClass = block.icon;
            }
        } else if (categoryId) {
            const cat = (currentMenu.categories || []).find(c => c.id === categoryId);
            if (cat && cat.icon) {
                selectedFaIconClass = cat.icon;
            }
        }
        updateSelectedBadge(selectedFaIconClass);

        const searchInput = document.getElementById('faIconSearchInput');
        if (searchInput) {
            searchInput.value = '';
            currentFaSearchTerm = '';
            const clearBtn = document.getElementById('faClearSearchBtn');
            if (clearBtn) clearBtn.style.display = 'none';
        }
        selectFaCategory('all');

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
        }
    };

    window.selectFaCategory = function(cat) {
        currentFaFilterCat = cat;
        document.querySelectorAll('.fa-cat-pill').forEach(btn => {
            if (btn.dataset.cat === cat) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        renderFaIconGrid();
    };

    window.filterFaIcons = function(query) {
        currentFaSearchTerm = (query || '').toLowerCase().trim();
        const clearBtn = document.getElementById('faClearSearchBtn');
        if (clearBtn) {
            clearBtn.style.display = currentFaSearchTerm.length > 0 ? 'block' : 'none';
        }
        renderFaIconGrid();
    };

    window.clearFaSearch = function() {
        const searchInput = document.getElementById('faIconSearchInput');
        if (searchInput) searchInput.value = '';
        currentFaSearchTerm = '';
        const clearBtn = document.getElementById('faClearSearchBtn');
        if (clearBtn) clearBtn.style.display = 'none';
        renderFaIconGrid();
        if (searchInput) searchInput.focus();
    };

    function renderFaIconGrid() {
        const grid = document.getElementById('faIconGrid');
        const emptyState = document.getElementById('faNoIconsFound');
        const countSpan = document.getElementById('faActiveCount');
        const totalCountBadge = document.getElementById('faTotalCountBadge');
        if (!grid) return;

        grid.innerHTML = '';
        faBatchRenderIndex = 0;

        currentMatchedIcons = faIconCatalog.filter(item => {
            const matchCat = currentFaFilterCat === 'all' || item.cat === currentFaFilterCat;
            if (!matchCat) return false;
            if (!currentFaSearchTerm) return true;
            return item.tags.includes(currentFaSearchTerm) ||
                   item.name.toLowerCase().includes(currentFaSearchTerm) ||
                   item.label.toLowerCase().includes(currentFaSearchTerm) ||
                   item.class.toLowerCase().includes(currentFaSearchTerm);
        });

        if (countSpan) countSpan.textContent = currentMatchedIcons.length;
        if (totalCountBadge) totalCountBadge.textContent = `${currentMatchedIcons.length} icônes`;

        if (currentMatchedIcons.length === 0) {
            if (emptyState) emptyState.style.display = 'block';
            return;
        } else {
            if (emptyState) emptyState.style.display = 'none';
        }

        renderNextFaBatch();
    }

    function renderNextFaBatch() {
        const grid = document.getElementById('faIconGrid');
        if (!grid) return;

        const start = faBatchRenderIndex;
        const end = Math.min(start + FA_BATCH_SIZE, currentMatchedIcons.length);

        const frag = document.createDocumentFragment();
        for (let i = start; i < end; i++) {
            const item = currentMatchedIcons[i];
            const card = document.createElement('div');
            card.className = 'fa-icon-card' + (item.class === selectedFaIconClass ? ' is-selected' : '');
            card.dataset.iconClass = item.class;
            card.dataset.iconLabel = item.label;

            card.innerHTML = `
                <i class="${item.class}"></i>
                <span class="icon-label" title="${item.label}">${item.label}</span>
            `;

            card.addEventListener('click', function() {
                grid.querySelectorAll('.fa-icon-card').forEach(c => c.classList.remove('is-selected'));
                this.classList.add('is-selected');
                selectedFaIconClass = item.class;
                updateSelectedBadge(item.class);
            });

            card.addEventListener('dblclick', function() {
                selectedFaIconClass = item.class;
                applySelectedFaIcon();
            });

            frag.appendChild(card);
        }

        grid.appendChild(frag);
        faBatchRenderIndex = end;
    }

    const iconGridEl = document.getElementById('faIconGrid');
    if (iconGridEl) {
        iconGridEl.addEventListener('scroll', function() {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight - 100) {
                if (faBatchRenderIndex < currentMatchedIcons.length) {
                    renderNextFaBatch();
                }
            }
        });
    }

    function updateSelectedBadge(iconClass) {
        const previewEl = document.getElementById('faSelectedIconPreview');
        const classEl = document.getElementById('faSelectedIconClass');
        if (previewEl) {
            previewEl.className = iconClass + ' text-primary fs-6';
        }
        if (classEl) {
            classEl.textContent = iconClass;
        }
    }

    const applyIconBtn = document.getElementById('faApplyIconBtn');
    if (applyIconBtn) {
        applyIconBtn.addEventListener('click', applySelectedFaIcon);
    }

    function applySelectedFaIcon() {
        if (!selectedFaIconClass) return;

        if (activePickerTargetCategoryId) {
            const cat = (currentMenu.categories || []).find(c => c.id === activePickerTargetCategoryId);
            if (cat) {
                cat.icon = selectedFaIconClass;
                renderMenuCategories();
                syncMenuSerializedData();
            }
        } else if (activePickerTargetBlockId) {
            const block = currentBlocks.find(b => b.id === activePickerTargetBlockId);
            if (block) {
                block.icon = selectedFaIconClass;
                renderAllBlockViews();
            }
        } else if (activeOptimizedBlockId) {
            const block = currentBlocks.find(b => b.id === activeOptimizedBlockId);
            if (block) {
                block.icon = selectedFaIconClass;
                renderAllBlockViews();
                selectBlockForOptimization(block.id);
            }
        }

        const modalEl = document.getElementById('faIconPickerModal');
        if (modalEl) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            } else {
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
            }
        }
    }

    // -------------------------------------------------------------------------
    // 4. Mode Switching & Simulator Controls
    // -------------------------------------------------------------------------
    window.setSimulatorScale = function(scale, btn) {
        ['classicPhoneFrame', 'dndPhoneFrame'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.transform = scale === 1 ? 'none' : `scale(${scale})`;
                el.style.transformOrigin = 'top center';
                el.style.transition = 'transform 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
            }
        });
        if (btn) {
            btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    };

    window.setSimulatorDevice = function(device, btn) {
        const frames = [document.getElementById('classicPhoneFrame'), document.getElementById('dndPhoneFrame')];
        frames.forEach(frame => {
            if (!frame) return;
            frame.classList.remove('is-android', 'is-frameless');
            if (device === 'android') frame.classList.add('is-android');
            if (device === 'frameless') frame.classList.add('is-frameless');
        });
        document.querySelectorAll('.sim-device-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
    };

    window.setSimulatorView = function(view, btn) {
        document.querySelectorAll('.sim-view-pill').forEach(b => b.classList.remove('active'));
        if (btn) {
            btn.classList.add('active');
        } else {
            const targetPill = document.getElementById(view === 'menu' ? 'simViewPillMenu' : 'simViewPillCard');
            if (targetPill) targetPill.classList.add('active');
        }
        if (view === 'menu') {
            openSimulatorMenuDrawer('classic');
        } else {
            closeSimulatorMenuDrawer('classic');
        }
    };

    window.resetSimulatorPreview = function() {
        window.setSimulatorScale(1);
        syncLivePreviewStyles();
        syncLivePreviewText();
        renderAllBlockViews();
    };

    function doSwitchBuilderMode(mode) {
        const classicPane = document.getElementById('classicBuilderPane');
        const dragDropPane = document.getElementById('dragDropBuilderPane');
        const btnClassic = document.getElementById('btnModeClassic');
        const btnDnd = document.getElementById('btnModeDragDrop');
        const descText = document.getElementById('modeDescriptionText');

        if (!classicPane || !dragDropPane) return;

        if (mode === 'classic') {
            classicPane.style.display = 'block';
            dragDropPane.style.display = 'none';
            if (btnClassic && btnDnd) {
                btnClassic.classList.add('active');
                btnDnd.classList.remove('active');
            }
            if (descText) descText.textContent = "Formulaire pas-à-pas avec synchronisation instantanée du simulateur.";
        } else {
            classicPane.style.display = 'none';
            dragDropPane.style.display = 'block';
            if (btnClassic && btnDnd) {
                btnDnd.classList.add('active');
                btnClassic.classList.remove('active');
            }
            if (descText) descText.textContent = "Mode interactif : glissez et déposez des widgets directement sur le smartphone.";
        }
        renderAllBlockViews();
    }
    window.switchBuilderMode = doSwitchBuilderMode;

    // -------------------------------------------------------------------------
    // 5. Live Style & Text Synchronization
    // -------------------------------------------------------------------------
    function syncLivePreviewStyles() {
        const primary = primaryColorPicker.value;
        const bg = bgColorPicker.value;
        const font = fontSelect.value;
        const radius = radiusSlider.value + 'px';
        const shadowStyle = shadowSelect.value;
        const lightCard = isLightHexColor(bg);

        let shadowCss = '0 10px 25px -5px rgba(0, 0, 0, 0.25)';
        if (shadowStyle === 'deep') shadowCss = '0 25px 50px -12px rgba(0, 0, 0, 0.5)';
        if (shadowStyle === 'glow') shadowCss = `0 0 20px ${primary}55`;
        if (shadowStyle === 'flat') shadowCss = 'none';

        [screenEl, dndScreenEl].forEach(screen => {
            if (!screen) return;
            screen.style.setProperty('--card-primary', primary);
            screen.style.setProperty('--card-bg', bg);
            screen.style.setProperty('--card-font', `'${font}', sans-serif`);
            screen.style.setProperty('--card-radius', radius);
            screen.style.setProperty('--card-shadow', shadowCss);
            screen.classList.toggle('is-light-theme', lightCard);
        });

        radiusBadge.textContent = radius;

        const themeConfig = {
            primary_color: primary,
            background_color: bg,
            font_family: font,
            border_radius: radius,
            shadow_style: shadowStyle,
            element_spacing: '12px'
        };
        themeJsonInput.value = JSON.stringify(themeConfig);
        if (typeof renderSimulatorMenuDrawerContent === 'function') {
            renderSimulatorMenuDrawerContent('classic');
            renderSimulatorMenuDrawerContent('dnd');
        }
        updateFormCompletionMeter();
    }

    function syncLivePreviewText() {
        const nameVal = nameInput.value.trim() || 'Marcus Vance';
        const jobVal = jobInput.value.trim() || '';
        const compVal = companyInput.value.trim() || '';
        const bioVal = bioInput.value.trim() || '';

        document.getElementById('previewName').textContent = nameVal;
        document.getElementById('previewJob').textContent = jobVal;
        document.getElementById('previewCompany').textContent = compVal;
        document.getElementById('previewBio').textContent = bioVal;

        if (document.getElementById('dndName')) {
            document.getElementById('dndName').textContent = nameVal;
            document.getElementById('dndJob').textContent = jobVal;
            document.getElementById('dndCompany').textContent = compVal;
            document.getElementById('dndBio').textContent = bioVal;
        }

        const badgeVal = badgeInput.value.trim();
        const badgeWrap = document.getElementById('previewBadgeWrap');
        if (badgeVal) {
            badgeWrap.style.display = 'block';
            document.getElementById('previewBadgeText').textContent = badgeVal;
        } else {
            badgeWrap.style.display = 'none';
        }

        // Slug live sync preview
        const slugVal = nameVal.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'carte';
        const slugNode = document.getElementById('slugPreviewVal');
        if (slugNode) slugNode.textContent = slugVal;

        // Character counter for bio
        const charCount = document.getElementById('bioCharCounter');
        if (charCount) {
            charCount.textContent = `${bioVal.length} / 180 caractères`;
        }

        // Avatar fallback if no uploaded image
        if (!profilePhotoInput.files || profilePhotoInput.files.length === 0) {
            const cleanBg = primaryColorPicker.value.replace('#', '');
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(nameVal)}&background=${cleanBg}&color=fff&size=160`;
            previewAvatar.src = avatarUrl;
            if (dndAvatar) dndAvatar.src = avatarUrl;
        }

        updateFormCompletionMeter();
    }

    // -------------------------------------------------------------------------
    // 6. Value-Add Utilities: Demo Profiles, Palettes, Gradients, Bio Chips
    // -------------------------------------------------------------------------
    window.applyColorPalette = function (primary, bg, font) {
        primaryColorPicker.value = primary;
        primaryColorText.value = primary;
        bgColorPicker.value = bg;
        bgColorText.value = bg;
        if (font) fontSelect.value = font;
        syncLivePreviewStyles();
        syncLivePreviewText();
    };

    window.setThemeBrightness = function(mode) {
        if (mode === 'dark') {
            bgColorPicker.value = '#0f172a';
            bgColorText.value = '#0f172a';
        } else {
            bgColorPicker.value = '#ffffff';
            bgColorText.value = '#ffffff';
        }
        syncLivePreviewStyles();
    };

    window.setCornerRadius = function(val) {
        radiusSlider.value = val;
        syncLivePreviewStyles();
    };

    window.applyBioSuggestion = function(text) {
        bioInput.value = text;
        syncLivePreviewText();
    };

    window.applyBannerGradient = function(gradient) {
        coverPhotoInput.value = '';
        previewCover.style.backgroundImage = gradient;
        coverThumbnail.style.backgroundImage = gradient;
        if (dndCover) dndCover.style.backgroundImage = gradient;
    };

    window.resetCoverBanner = function() {
        coverPhotoInput.value = '';
        const prim = primaryColorPicker.value;
        const grad = `linear-gradient(135deg, ${prim} 0%, #1e1b4b 100%)`;
        previewCover.style.backgroundImage = grad;
        coverThumbnail.style.backgroundImage = grad;
        if (dndCover) dndCover.style.backgroundImage = grad;
    };

    window.resetProfileAvatar = function() {
        profilePhotoInput.value = '';
        const nameVal = nameInput.value.trim() || 'User';
        const cleanBg = primaryColorPicker.value.replace('#', '');
        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(nameVal)}&background=${cleanBg}&color=fff&size=160`;
        previewAvatar.src = avatarUrl;
        profileThumbnail.src = avatarUrl;
        if (dndAvatar) dndAvatar.src = avatarUrl;
    };

    window.copyCardUrlToClipboard = function() {
        const slug = document.getElementById('slugPreviewVal')?.textContent || 'carte';
        const url = `${window.location.origin}/c/${slug}`;
        navigator.clipboard.writeText(url).then(() => {
            const btnText = document.getElementById('copyUrlBtnText');
            if (btnText) {
                const prev = btnText.textContent;
                btnText.textContent = 'Copié !';
                setTimeout(() => { btnText.textContent = prev; }, 1800);
            }
        });
    };

    window.openQrPreviewModal = function() {
        const slug = document.getElementById('slugPreviewVal')?.textContent || 'carte';
        const url = `${window.location.origin}/c/${slug}`;
        const modalEl = document.getElementById('liveQrPreviewModal');
        const qrImg = document.getElementById('modalQrCodeImg');
        const targetUrl = document.getElementById('modalTargetUrlText');
        const holderName = document.getElementById('modalCardHolderName');
        const jobComp = document.getElementById('modalCardJobCompany');

        if (targetUrl) targetUrl.textContent = url;
        if (holderName) holderName.textContent = nameInput.value || 'Carte Visite';
        if (jobComp) jobComp.textContent = `${jobInput.value || ''} • ${companyInput.value || ''}`;
        if (qrImg) qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=8&data=${encodeURIComponent(url)}`;

        const downloadLink = document.getElementById('modalDownloadQrLink');
        if (downloadLink && qrImg) {
            downloadLink.href = qrImg.src;
        }

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }
    };

    // 1-Click Complete Demo Profile Loader
    window.loadSampleDemoProfile = function(type) {
        if (type === 'executive') {
            nameInput.value = 'Marcus Vance';
            titleInput.value = 'Carte Visite Direction';
            jobInput.value = 'Directeur Général';
            companyInput.value = 'YNS Smart Solutions';
            badgeInput.value = '★ Profil Officiel';
            bioInput.value = 'Solutions connectées NFC et cartes de visite interactives de nouvelle génération.';
            applyColorPalette('#4f46e5', '#0f172a', 'Outfit');
            currentBlocks = [
                { id: 'blk_1', type: 'contact', icon: 'fa-solid fa-phone', label: 'Appel Direct', url: '+212600112233', order: 1 },
                { id: 'blk_2', type: 'contact', icon: 'fa-brands fa-whatsapp', label: 'WhatsApp Exécutif', url: '+212600112233', order: 2 },
                { id: 'blk_3', type: 'contact', icon: 'fa-solid fa-envelope', label: 'Email Direction', url: 'direction@ynssolutions.ma', order: 3 },
                { id: 'blk_4', type: 'custom_link', icon: 'fa-solid fa-globe', label: 'Site Web Officiel', url: 'https://ynssolutions.ma', order: 4 },
                { id: 'blk_5', type: 'social', icon: 'fa-brands fa-linkedin', label: 'LinkedIn Pro', url: 'https://linkedin.com/in/marcus-vance', order: 5 }
            ];
            toggleInteractiveMenu(false);
        } else if (type === 'restaurant') {
            nameInput.value = 'Chef Antoine Laurent';
            titleInput.value = 'Carte & Menu du Restaurant';
            jobInput.value = 'Chef Exécutif & Fondateur';
            companyInput.value = 'Le Bistro Parisien';
            badgeInput.value = '★ Maître Restaurateur';
            bioInput.value = 'Gastronomie française contemporaine, produits de saison & vins d\'exception.';
            applyColorPalette('#ea580c', '#18181b', 'Playfair Display');
            currentBlocks = [
                { id: 'blk_1', type: 'contact', icon: 'fa-solid fa-phone', label: 'Réservation Table', url: '+212522001122', order: 1 },
                { id: 'blk_2', type: 'contact', icon: 'fa-brands fa-whatsapp', label: 'WhatsApp Bistro', url: '+212600112233', order: 2 },
                { id: 'blk_3', type: 'contact', icon: 'fa-solid fa-location-dot', label: 'Localisation Google Maps', url: 'Marina Casablanca', order: 3 },
                { id: 'blk_4', type: 'social', icon: 'fa-brands fa-instagram', label: 'Instagram @lebistro', url: 'https://instagram.com/lebistro', order: 4 },
                { id: 'blk_menu', type: 'menu', icon: 'fa-solid fa-utensils', label: 'Consulter notre Menu', url: '#menu', order: 5 }
            ];
            toggleInteractiveMenu(true);
            applyMenuQuickStarter('restaurant');
        } else if (type === 'creative') {
            nameInput.value = 'Elena Rostova';
            titleInput.value = 'Portfolio & Studio Créatif';
            jobInput.value = 'Directrice Artistique & UI/UX';
            companyInput.value = 'Studio Aurora';
            badgeInput.value = '★ Designer Vérifiée';
            bioInput.value = 'Direction artistique, interfaces haut de gamme et expériences de marque mémorables.';
            applyColorPalette('#ec4899', '#18021c', 'Space Grotesk');
            currentBlocks = [
                { id: 'blk_1', type: 'contact', icon: 'fa-brands fa-whatsapp', label: 'WhatsApp Studio', url: '+212600998877', order: 1 },
                { id: 'blk_2', type: 'contact', icon: 'fa-solid fa-envelope', label: 'Email Collaborations', url: 'hello@studioaurora.design', order: 2 },
                { id: 'blk_3', type: 'custom_link', icon: 'fa-solid fa-globe', label: 'Portfolio en Ligne', url: 'https://studioaurora.design', order: 3 },
                { id: 'blk_4', type: 'social', icon: 'fa-brands fa-instagram', label: 'Instagram Portfolio', url: 'https://instagram.com/studioaurora', order: 4 },
                { id: 'blk_5', type: 'social', icon: 'fa-brands fa-youtube', label: 'Chaîne Design & Tutoriels', url: 'https://youtube.com/@studioaurora', order: 5 }
            ];
            toggleInteractiveMenu(false);
        }

        renderAllBlockViews();
        syncLivePreviewStyles();
        syncLivePreviewText();
    };

    // Live Form Completion Score Calculator
    function updateFormCompletionMeter() {
        let score = 0;
        const hasName = Boolean(nameInput && nameInput.value.trim());
        const hasTitle = Boolean(jobInput && jobInput.value.trim());
        const hasBio = Boolean(bioInput && bioInput.value.trim());
        const hasChannels = currentBlocks.length >= 2;
        const hasRichChannels = currentBlocks.length >= 4;
        const hasMenu = currentMenu.enabled;

        if (hasName) score += 25;
        if (hasTitle) score += 15;
        if (hasBio) score += 15;
        if (hasChannels) score += 25;
        if (hasRichChannels) score += 10;
        if (hasMenu) score += 10;

        score = Math.min(score, 100);

        const bar = document.getElementById('formCompletionProgressBar');
        const text = document.getElementById('formCompletionPercentText');
        const navBar = document.getElementById('quickNavProgressBar');
        if (bar) bar.style.width = `${score}%`;
        if (text) text.textContent = `${score}%`;
        if (navBar) navBar.style.width = `${score}%`;

        // Update nav dots
        document.getElementById('dot-sec-gabarits')?.classList.add('is-done');
        if (hasName) document.getElementById('dot-sec-identite')?.classList.add('is-done');
        document.getElementById('dot-sec-medias')?.classList.add('is-done');
        document.getElementById('dot-sec-style')?.classList.add('is-done');
        if (hasChannels) document.getElementById('dot-sec-canaux')?.classList.add('is-done');
        if (hasMenu) document.getElementById('dot-sec-menu')?.classList.add('is-done');
    }

    // -------------------------------------------------------------------------
    // 7. Media Upload Client-Side Previews
    // -------------------------------------------------------------------------
    profilePhotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (evt) {
                previewAvatar.src = evt.target.result;
                if (dndAvatar) dndAvatar.src = evt.target.result;
                profileThumbnail.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    coverPhotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (evt) {
                previewCover.style.backgroundImage = `url('${evt.target.result}')`;
                if (dndCover) dndCover.style.backgroundImage = `url('${evt.target.result}')`;
                coverThumbnail.style.backgroundImage = `url('${evt.target.result}')`;
            };
            reader.readAsDataURL(file);
        }
    });

    // -------------------------------------------------------------------------
    // 8. Event Listeners for Live Preview Text & Style
    // -------------------------------------------------------------------------
    primaryColorPicker.addEventListener('input', function () {
        primaryColorText.value = primaryColorPicker.value;
        syncLivePreviewStyles();
        syncLivePreviewText();
    });
    primaryColorText.addEventListener('input', function () {
        if (/^#[0-9A-Fa-f]{6}$/.test(primaryColorText.value)) {
            primaryColorPicker.value = primaryColorText.value;
            syncLivePreviewStyles();
        }
    });

    bgColorPicker.addEventListener('input', function () {
        bgColorText.value = bgColorPicker.value;
        syncLivePreviewStyles();
    });
    bgColorText.addEventListener('input', function () {
        if (/^#[0-9A-Fa-f]{6}$/.test(bgColorText.value)) {
            bgColorPicker.value = bgColorText.value;
            syncLivePreviewStyles();
        }
    });

    fontSelect.addEventListener('change', syncLivePreviewStyles);
    shadowSelect.addEventListener('change', syncLivePreviewStyles);
    radiusSlider.addEventListener('input', syncLivePreviewStyles);

    [nameInput, jobInput, companyInput, bioInput, badgeInput].forEach(inp => {
        if (inp) inp.addEventListener('input', syncLivePreviewText);
    });

    document.querySelectorAll('.template-preset-card').forEach(card => {
        card.addEventListener('click', function () {
            const presetKey = this.dataset.preset;
            const config = presets[presetKey];
            if (!config) return;

            document.querySelectorAll('.template-preset-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            primaryColorPicker.value = config.primary_color;
            primaryColorText.value = config.primary_color;
            bgColorPicker.value = config.background_color;
            bgColorText.value = config.background_color;
            fontSelect.value = config.font_family;
            radiusSlider.value = parseInt(config.border_radius, 10);
            shadowSelect.value = config.shadow_style;

            syncLivePreviewStyles();
            syncLivePreviewText();
        });
    });

    // -------------------------------------------------------------------------
    // 9. Universal Block Rendering & Repeater
    // -------------------------------------------------------------------------
    function renderAllBlockViews() {
        renderClassicRepeater();
        renderClassicPreview();
        renderDndCanvasBlocks();
        syncSerializedData();
        updateFormCompletionMeter();
    }

    function renderClassicRepeater() {
        blocksContainer.innerHTML = '';
        const countBadge = document.getElementById('channelsHeaderCountBadge');
        if (countBadge) {
            countBadge.textContent = `${currentBlocks.length} canal${currentBlocks.length > 1 ? 'ux' : ''}`;
        }

        if (currentBlocks.length === 0) {
            const emptyCard = document.createElement('div');
            emptyCard.className = 'empty-channels-card';
            emptyCard.innerHTML = `
                <div class="empty-channels-icon-wrap">
                    <i class="fa-solid fa-share-nodes"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Aucun canal configuré</h6>
                <p class="text-muted small mb-3.5 mx-auto" style="max-width: 420px; font-size: 0.84rem;">
                    Ajoutez vos canaux de communication (WhatsApp, Téléphone, Profils sociaux, Liens) pour connecter vos visiteurs et alimenter votre simulateur.
                </p>
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-add-channel text-white px-3.5 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-1.5" onclick="addDefaultChannel()">
                        <i class="fa-solid fa-circle-plus"></i> Ajouter mon premier canal
                    </button>
                    <button type="button" class="btn btn-sm btn-white border px-3.5 py-2 rounded-pill fw-semibold text-dark shadow-2xs d-inline-flex align-items-center gap-1.5" onclick="openFaIconPicker(null)">
                        <i class="fa-solid fa-icons text-primary"></i> Explorer les icônes
                    </button>
                </div>
            `;
            blocksContainer.appendChild(emptyCard);
            return;
        }

        currentBlocks.forEach((block, index) => {
            block.order = index + 1;
            const row = document.createElement('div');
            row.className = 'channel-card-row';
            row.dataset.id = block.id;

            const iconHtml = renderIconElement(block.icon);

            row.innerHTML = `
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <span class="channel-drag-handle" title="Glisser pour réorganiser"><i class="fa-solid fa-grip-vertical"></i></span>
                        <span class="channel-order-badge">0${index + 1}</span>

                        <div class="channel-icon-trigger-box" onclick="openFaIconPicker('${block.id}')" title="Cliquer pour changer d'icône (Font Awesome)">
                            ${iconHtml}
                            <span class="edit-badge-dot"><i class="fa-solid fa-pen"></i></span>
                        </div>

                        <div class="min-w-0">
                            <span class="small fw-bold text-dark block-title-text d-block lh-sm text-truncate" style="font-size: 0.92rem;">${block.label || 'Nouveau Canal'}</span>
                            <span class="channel-icon-tag mt-1" onclick="openFaIconPicker('${block.id}')" style="cursor: pointer;" title="Changer l'icône Font Awesome">
                                <i class="fa-solid fa-icons text-primary me-1"></i>${block.icon || 'fa-solid fa-link'}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1.5 ms-auto">
                        <button type="button" class="btn btn-sm btn-white border px-2.5 py-1 rounded-pill choose-fa-icon-btn d-inline-flex align-items-center gap-1.5 shadow-2xs" onclick="openFaIconPicker('${block.id}')" title="Changer l'icône Font Awesome">
                            <i class="fa-solid fa-magnifying-glass text-primary"></i>
                            <span class="small font-mono fw-semibold d-none d-sm-inline">Icône</span>
                        </button>
                        <div class="btn-group shadow-2xs" role="group">
                            <button type="button" class="channel-action-btn move-up-btn" ${index === 0 ? 'disabled' : ''} title="Monter d'un rang">
                                <i class="fa-solid fa-arrow-up"></i>
                            </button>
                            <button type="button" class="channel-action-btn move-down-btn" ${index === currentBlocks.length - 1 ? 'disabled' : ''} title="Descendre d'un rang">
                                <i class="fa-solid fa-arrow-down"></i>
                            </button>
                        </div>
                        <button type="button" class="channel-action-btn delete-btn remove-block-btn shadow-2xs" title="Supprimer ce canal">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <div class="channel-divider"></div>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="channel-field-label">
                            <i class="fa-solid fa-pen-nib text-primary opacity-75 me-1.5"></i>Libellé Affiché
                        </label>
                        <input type="text" class="form-control channel-input block-label-input" value="${block.label || ''}" placeholder="ex: Mon WhatsApp Pro">
                    </div>
                    <div class="col-md-7">
                        <label class="channel-field-label">
                            <i class="fa-solid fa-link text-primary opacity-75 me-1.5"></i>Lien Cible ou Coordonnées
                        </label>
                        <input type="text" class="form-control channel-input font-mono block-url-input" value="${block.url || ''}" placeholder="ex: +212600112233 ou https://...">
                    </div>
                </div>
            `;

            const labelInp = row.querySelector('.block-label-input');
            const urlInp = row.querySelector('.block-url-input');
            const titleNode = row.querySelector('.block-title-text');

            labelInp.addEventListener('input', function (e) {
                block.label = e.target.value;
                if (titleNode) titleNode.textContent = e.target.value || 'Canal';
                renderClassicPreview();
                renderDndCanvasBlocks();
                syncSerializedData();
            });

            urlInp.addEventListener('input', function (e) {
                block.url = e.target.value;
                renderClassicPreview();
                renderDndCanvasBlocks();
                syncSerializedData();
            });

            row.querySelector('.move-up-btn').addEventListener('click', function (e) {
                e.preventDefault();
                if (index > 0) {
                    const temp = currentBlocks[index];
                    currentBlocks[index] = currentBlocks[index - 1];
                    currentBlocks[index - 1] = temp;
                    renderAllBlockViews();
                }
            });

            row.querySelector('.move-down-btn').addEventListener('click', function (e) {
                e.preventDefault();
                if (index < currentBlocks.length - 1) {
                    const temp = currentBlocks[index];
                    currentBlocks[index] = currentBlocks[index + 1];
                    currentBlocks[index + 1] = temp;
                    renderAllBlockViews();
                }
            });

            row.querySelector('.remove-block-btn').addEventListener('click', function (e) {
                e.preventDefault();
                currentBlocks = currentBlocks.filter(b => b.id !== block.id);
                renderAllBlockViews();
            });

            blocksContainer.appendChild(row);
        });
    }

    function renderClassicPreview() {
        previewLinksContainer.innerHTML = '';
        currentBlocks.forEach(block => {
            if (!block.url) return;
            const a = document.createElement('a');
            a.className = 'preview-link-card';
            a.href = '#';
            const isMenuLink = (block.type === 'menu' || block.url === '#menu' || String(block.url).endsWith('/menu'));
            a.addEventListener('click', function(e) {
                e.preventDefault();
                if (isMenuLink) {
                    openSimulatorMenuDrawer('classic');
                }
            });
            a.innerHTML = `
                <div class="preview-link-icon-wrap">
                    ${renderIconElement(block.icon)}
                </div>
                <div class="flex-grow-1 min-w-0 text-start">
                    <div class="fw-semibold small text-truncate">${block.label || 'Lien'}</div>
                    <div class="small opacity-60 text-truncate font-mono" style="font-size: 0.68rem;">${isMenuLink ? 'Ouvrir le Menu Interactif' : block.url}</div>
                </div>
                <i class="fa-solid ${isMenuLink ? 'fa-utensils' : 'fa-chevron-right'} opacity-40 small"></i>
            `;
            previewLinksContainer.appendChild(a);
        });

        // Diagnostics in footer
        const statsNode = document.getElementById('simSummaryStats');
        if (statsNode) {
            statsNode.textContent = `${currentBlocks.length} canaux • vCard 3.0 Ready • NFC`;
        }
    }

    let draggedBlockIndex = null;

    function renderDndCanvasBlocks() {
        dndDropZone.innerHTML = '';
        if (currentBlocks.length === 0) {
            dndEmptyPlaceholder.style.display = 'block';
        } else {
            dndEmptyPlaceholder.style.display = 'none';
        }

        const dndBadge = document.getElementById('dndBlockCountBadge');
        if (dndBadge) {
            dndBadge.textContent = `${currentBlocks.length} élément${currentBlocks.length > 1 ? 's' : ''}`;
        }

        currentBlocks.forEach((block, index) => {
            const item = document.createElement('div');
            item.className = 'canvas-block-item' + (activeOptimizedBlockId === block.id ? ' active-selected' : '');
            item.dataset.id = block.id;
            item.dataset.index = index;
            item.draggable = true;

            item.innerHTML = `
                <div class="d-flex align-items-center gap-2.5 flex-grow-1 min-w-0">
                    <span class="opacity-40 small me-0.5" style="cursor: grab;"><i class="fa-solid fa-grip-vertical"></i></span>
                    <div class="canvas-icon-box">
                        ${renderIconElement(block.icon)}
                    </div>
                    <div class="min-w-0 text-start">
                        <div class="canvas-block-title text-truncate">${block.label || 'Widget'}</div>
                        <div class="canvas-block-url text-truncate font-mono">${block.url || 'Aucun lien'}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-xs btn-link text-white opacity-70 p-0" onclick="event.stopPropagation(); selectBlockForOptimization('${block.id}')"><i class="fa-solid fa-pen"></i></button>
                    <button type="button" class="btn btn-xs btn-link text-danger opacity-70 p-0" onclick="event.stopPropagation(); deleteBlockById('${block.id}')"><i class="fa-solid fa-trash"></i></button>
                </div>
            `;
            item.addEventListener('click', () => selectBlockForOptimization(block.id));

            item.addEventListener('dragstart', function(e) {
                draggedBlockIndex = index;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', 'reorder:' + index);
                this.style.opacity = '0.5';
            });
            item.addEventListener('dragend', function() {
                this.style.opacity = '1';
                draggedBlockIndex = null;
            });
            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });
            item.addEventListener('drop', function(e) {
                e.preventDefault();
                const payload = e.dataTransfer.getData('text/plain') || '';
                if (payload.startsWith('reorder:') && draggedBlockIndex !== null && draggedBlockIndex !== index) {
                    e.stopPropagation();
                    const moved = currentBlocks.splice(draggedBlockIndex, 1)[0];
                    currentBlocks.splice(index, 0, moved);
                    draggedBlockIndex = null;
                    renderAllBlockViews();
                }
            });

            dndDropZone.appendChild(item);
        });
    }

    // HTML5 Drag & Drop from Widget Library to Canvas Drop Zone
    document.querySelectorAll('.draggable-widget-card').forEach(card => {
        card.addEventListener('dragstart', function(e) {
            e.dataTransfer.effectAllowed = 'copy';
            e.dataTransfer.setData('text/plain', JSON.stringify({
                source: 'widget_library',
                type: this.dataset.type,
                icon: this.dataset.icon,
                label: this.dataset.label,
                placeholder: this.dataset.placeholder
            }));
        });
    });

    if (dndDropZone) {
        dndDropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        dndDropZone.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        dndDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            const raw = e.dataTransfer.getData('text/plain') || '';
            if (!raw || raw.startsWith('reorder:')) return;
            try {
                const data = JSON.parse(raw);
                if (data && data.source === 'widget_library') {
                    const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
                    currentBlocks.push({
                        id: newBlockId,
                        type: data.type || 'contact',
                        icon: data.icon || 'fa-solid fa-link',
                        label: data.label || 'Nouveau Canal',
                        url: data.placeholder || '',
                        order: currentBlocks.length + 1
                    });
                    if (data.type === 'menu') {
                        window.toggleInteractiveMenu(true);
                    }
                    renderAllBlockViews();
                    selectBlockForOptimization(newBlockId);
                }
            } catch (err) {
                // Ignore non-JSON drops
            }
        });
    }

    function syncSerializedData() {
        contentBlocksJsonInput.value = JSON.stringify(currentBlocks);
    }

    window.addDefaultChannel = function() {
        const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
        currentBlocks.push({
            id: newBlockId,
            type: 'custom_link',
            icon: 'fa-solid fa-link',
            label: 'Nouveau Canal',
            url: '',
            order: currentBlocks.length + 1
        });
        renderAllBlockViews();
    };

    window.addNewBlock = function(blockData) {
        blockData = blockData || {};
        const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
        currentBlocks.push({
            id: newBlockId,
            type: blockData.type || 'contact',
            icon: blockData.icon || 'fa-solid fa-link',
            label: blockData.label || 'Nouveau Canal',
            url: blockData.url || '',
            order: currentBlocks.length + 1
        });
        renderAllBlockViews();
        selectBlockForOptimization(newBlockId);
    };

    window.deleteBlockById = function(blockId) {
        currentBlocks = currentBlocks.filter(b => b.id !== blockId);
        renderAllBlockViews();
    };

    window.selectBlockForOptimization = function(blockId) {
        activeOptimizedBlockId = blockId;
        const block = currentBlocks.find(b => b.id === blockId);
        if (!block) return;

        const tabOptBtn = document.getElementById('tab-optimizer-btn');
        const tabLibBtn = document.getElementById('tab-library-btn');
        const paneLib = document.getElementById('tab-library');
        const paneOpt = document.getElementById('tab-optimizer');

        if (tabOptBtn && tabLibBtn && paneLib && paneOpt) {
            tabOptBtn.classList.add('active');
            tabLibBtn.classList.remove('active');
            paneOpt.classList.add('show', 'active');
            paneLib.classList.remove('show', 'active');
        }

        const emptyState = document.getElementById('optimizerEmptyState');
        const formWrap = document.getElementById('optimizerFormWrap');
        if (emptyState) emptyState.style.display = 'none';
        if (formWrap) formWrap.style.display = 'block';

        const labelInp = document.getElementById('optBlockLabelInput');
        const urlInp = document.getElementById('optBlockUrlInput');
        const hTitle = document.getElementById('optHeaderTitle');
        const hType = document.getElementById('optHeaderType');
        const iconPrev = document.getElementById('optActiveIconPreview');
        const iconClass = document.getElementById('optActiveIconClass');

        if (labelInp) labelInp.value = block.label || '';
        if (urlInp) urlInp.value = block.url || '';
        if (hTitle) hTitle.textContent = block.label || 'Élément';
        if (hType) hType.textContent = block.type || 'contact';
        if (iconPrev) iconPrev.className = block.icon || 'fa-solid fa-link';
        if (iconClass) iconClass.textContent = block.icon || 'fa-solid fa-link';

        labelInp.oninput = function() {
            block.label = this.value;
            if (hTitle) hTitle.textContent = this.value || 'Élément';
            renderAllBlockViews();
        };
        urlInp.oninput = function() {
            block.url = this.value;
            renderAllBlockViews();
        };
    };

    window.deleteCurrentOptimizedBlock = function() {
        if (activeOptimizedBlockId) {
            deleteBlockById(activeOptimizedBlockId);
            activeOptimizedBlockId = null;
            switchToLibraryTab();
        }
    };

    window.switchToLibraryTab = function() {
        const tabLibBtn = document.getElementById('tab-library-btn');
        const tabOptBtn = document.getElementById('tab-optimizer-btn');
        const paneLib = document.getElementById('tab-library');
        const paneOpt = document.getElementById('tab-optimizer');
        if (tabLibBtn && tabOptBtn && paneLib && paneOpt) {
            tabLibBtn.classList.add('active');
            tabOptBtn.classList.remove('active');
            paneLib.classList.add('show', 'active');
            paneOpt.classList.remove('show', 'active');
        }
    };

    window.openFaIconPickerForActiveOptimized = function() {
        if (activeOptimizedBlockId) {
            openFaIconPicker(activeOptimizedBlockId);
        }
    };

    window.quickAddWidgetFromCard = function(cardEl) {
        if (!cardEl) return;
        const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
        currentBlocks.push({
            id: newBlockId,
            type: cardEl.dataset.type,
            icon: cardEl.dataset.icon,
            label: cardEl.dataset.label,
            url: cardEl.dataset.placeholder,
            order: currentBlocks.length + 1
        });
        if (cardEl.dataset.type === 'menu') {
            window.toggleInteractiveMenu(true);
        }
        renderAllBlockViews();
        selectBlockForOptimization(newBlockId);
    };

    let currentWidgetCategory = 'all';
    window.filterWidgetCategory = function(cat, btn) {
        currentWidgetCategory = cat || 'all';
        document.querySelectorAll('.widget-cat-filter-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        applyWidgetFilter();
    };

    window.filterWidgetLibrary = function() {
        applyWidgetFilter();
    };

    function applyWidgetFilter() {
        const searchVal = (document.getElementById('librarySearchInput')?.value || '').toLowerCase().trim();
        document.querySelectorAll('.draggable-widget-card').forEach(card => {
            const cat = card.dataset.category || '';
            const label = (card.dataset.label || '').toLowerCase();
            const text = card.textContent.toLowerCase();
            const matchesCategory = (currentWidgetCategory === 'all' || cat === currentWidgetCategory);
            const matchesSearch = (!searchVal || label.includes(searchVal) || text.includes(searchVal));

            const col = card.closest('.col-6') || card.closest('.col-12');
            if (col) {
                col.style.display = (matchesCategory && matchesSearch) ? 'block' : 'none';
            }
        });
    }

    // Direct Add Button handler
    const btnDirectAdd = document.getElementById('btnAddChannelBtn');
    if (btnDirectAdd) {
        btnDirectAdd.addEventListener('click', function(e) {
            e.preventDefault();
            window.addDefaultChannel();
        });
    }

    // Robust native Dropdown Toggle
    const addChannelBtn = document.getElementById('btnAddChannelDropdown');
    const addChannelMenu = document.getElementById('addChannelDropdownMenu');
    if (addChannelBtn && addChannelMenu) {
        addChannelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            addChannelMenu.classList.toggle('show');
            addChannelBtn.setAttribute('aria-expanded', addChannelMenu.classList.contains('show'));
        });
        document.addEventListener('click', function(e) {
            if (!addChannelBtn.contains(e.target) && !addChannelMenu.contains(e.target)) {
                addChannelMenu.classList.remove('show');
                addChannelBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('.add-block-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (addChannelMenu) addChannelMenu.classList.remove('show');
            if (this.dataset.type === 'menu') {
                window.toggleInteractiveMenu(true);
            }
            addNewBlock({
                type: this.dataset.type,
                icon: this.dataset.icon,
                label: this.dataset.label,
                url: this.dataset.placeholder
            });
        });
    });

    // -------------------------------------------------------------------------
    // 10. Interactive Restaurant / Brand Menu Builder & Simulator Navigation
    // -------------------------------------------------------------------------
    const menuConfigJsonInput = document.getElementById('menu_config_json');
    const menuCategoriesContainer = document.getElementById('menuCategoriesContainer');
    let activeSimMenuCat_classic = 'all';
    let activeSimMenuCat_dnd = 'all';
    let simCatalogPage_classic = 0;
    let simCatalogPage_dnd = 0;
    let simMenuSearchTerm_classic = '';
    let simMenuSearchTerm_dnd = '';

    window.setSimMenuSearchTerm = function(mode, val) {
        if (mode === 'classic') simMenuSearchTerm_classic = (val || '').toLowerCase().trim();
        if (mode === 'dnd') simMenuSearchTerm_dnd = (val || '').toLowerCase().trim();
        renderSimulatorMenuDrawerContent(mode);
    };

    window.uploadMenuImageAjax = function(file, onDone, onLoading) {
        if (!file) return;
        if (onLoading) onLoading(true);
        const formData = new FormData();
        formData.append('image', file);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

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
                onDone(res.url);
            } else {
                alert(res.message || 'Erreur lors du téléversement de l\'image.');
            }
        })
        .catch(err => {
            if (onLoading) onLoading(false);
            console.error(err);
            alert('Erreur réseau lors du téléversement de l\'image.');
        });
    };

    window.toggleInteractiveMenu = function(isEnabled) {
        currentMenu.enabled = Boolean(isEnabled);
        const toggleEl = document.getElementById('menuEnabledToggle');
        if (toggleEl) toggleEl.checked = currentMenu.enabled;
        const bodyWrap = document.getElementById('menuBuilderBody');
        const disabledPlaceholder = document.getElementById('menuDisabledBanner');
        const labelBadge = document.getElementById('menuEnabledLabel');

        if (bodyWrap) bodyWrap.style.display = currentMenu.enabled ? 'block' : 'none';
        if (disabledPlaceholder) disabledPlaceholder.style.display = currentMenu.enabled ? 'none' : 'block';
        if (labelBadge) {
            labelBadge.textContent = currentMenu.enabled ? 'Menu Actif' : 'Activer le Menu';
            labelBadge.className = 'form-check-label small fw-bold cursor-pointer ' + (currentMenu.enabled ? 'text-success' : 'text-dark');
        }
        syncMenuSerializedData();
        updateFormCompletionMeter();
    };

    window.enableAndScrollToMenuSection = function() {
        window.toggleInteractiveMenu(true);
        const secMenu = document.getElementById('sec-menu');
        if (secMenu) {
            secMenu.scrollIntoView({ behavior: 'smooth', block: 'start' });
            secMenu.style.outline = '2px solid #ea580c';
            setTimeout(() => { secMenu.style.outline = 'none'; }, 1500);
        }
    };

    const starterTemplates = {
        restaurant: {
            title: 'Carte & Menu du Restaurant',
            button_label: 'Découvrir la Carte Restaurant',
            subtitle: 'Entrées fraîches, viandes sélectionnées et desserts signature maison.',
            currency: 'MAD',
            navigation_mode: 'vertical',
            item_layout: 'grid',
            design_preset: 'card_theme',
            accent_color: '#ea580c',
            bg_color: '#0f172a',
            show_search: true,
            show_images: true,
            categories: [
                {
                    id: 'cat_starters',
                    name: 'Entrées & Salades',
                    icon: 'fa-solid fa-bowl-food',
                    description: 'Préparations fraîches et spécialités de saison.',
                    cover_image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'itm_1', name: 'Burrata Crémeuse aux Tomates Confites', description: 'Basilic frais, huile d\'olive extra vierge et réduction balsamique.', price: '95', badge: 'Signature', image_url: 'https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5?w=500&auto=format&fit=crop&q=80', available: true },
                        { id: 'itm_2', name: 'Carpaccio de Saumon Gravlax', description: 'Agrumes marinés, baies roses et toasts de campagne chauds.', price: '110', badge: 'Frais', image_url: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_mains',
                    name: 'Plats Chauds & Grillades',
                    icon: 'fa-solid fa-utensils',
                    description: 'Viandes nobles, poissons du jour et garnitures gourmandes.',
                    cover_image: 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'itm_3', name: 'Filet de Bœuf Rossini', description: 'Foie gras poêlé, jus truffé et mousseline de pommes de terre.', price: '240', badge: 'Chef', image_url: 'https://images.unsplash.com/photo-1558030006-450675393462?w=500&auto=format&fit=crop&q=80', available: true },
                        { id: 'itm_4', name: 'Pavé de Loup Bar Rôti', description: 'Légumes croquants étuvés, émulsion citronnelle et herbes.', price: '190', badge: 'Pêche du jour', image_url: 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_desserts',
                    name: 'Desserts & Gourmandises',
                    icon: 'fa-solid fa-cake-candles',
                    description: 'Douceurs sucrées préparées chaque matin par notre chef pâtissier.',
                    cover_image: 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=800&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'itm_5', name: 'Fondant Cœur Coulant Chocolat Valrhona', description: 'Glace vanille Bourbon artisanale et coulis de framboises.', price: '65', badge: 'Best-seller', image_url: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=500&auto=format&fit=crop&q=80', available: true }
                    ]
                }
            ]
        },
        cafe: {
            title: 'Coffee Shop, Brunch & Boissons',
            button_label: 'Voir notre Menu Coffee & Brunch',
            subtitle: 'Cafés de spécialité, jus pressés à froid, toasts et douceurs.',
            currency: 'MAD',
            navigation_mode: 'horizontal',
            item_layout: 'grid',
            design_preset: 'bistro_dark',
            accent_color: '#ea580c',
            bg_color: '#18181b',
            show_search: true,
            show_images: true,
            categories: [
                {
                    id: 'cat_coffee',
                    name: 'Cafés & Spécialités',
                    icon: 'fa-solid fa-mug-hot',
                    description: 'Grains torréfiés sur mesure, 100% Arabica sélectionné.',
                    cover_image: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'itm_c1', name: 'Flat White Signature', description: 'Double espresso et micromousse de lait veloutée.', price: '38', badge: 'Populaire', image_url: 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&auto=format&fit=crop&q=80', available: true },
                        { id: 'itm_c2', name: 'Cold Brew Infusé 18h', description: 'Café glacé doux, notes florales et chocolatées.', price: '42', badge: 'Glacé', image_url: 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500&auto=format&fit=crop&q=80', available: true }
                    ]
                },
                {
                    id: 'cat_brunch',
                    name: 'Brunch & Toasts',
                    icon: 'fa-solid fa-egg',
                    description: 'Assiettes généreuses servies toute la journée.',
                    cover_image: 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'itm_b1', name: 'Avocado Toast & Œuf Poché', description: 'Pain au levain, guacamole maison, graines de grenade.', price: '75', badge: 'Bio', image_url: 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=500&auto=format&fit=crop&q=80', available: true }
                    ]
                }
            ]
        },
        brand: {
            title: 'Catalogue Produits & Nouveautés',
            button_label: 'Feuilleter le Catalogue Produits',
            subtitle: 'Collections officielles, tarifs conseillés et fiches techniques complètes.',
            currency: 'MAD',
            navigation_mode: 'catalog',
            item_layout: 'grid',
            design_preset: 'clean_light',
            accent_color: '#4f46e5',
            bg_color: '#ffffff',
            show_search: true,
            show_images: true,
            categories: [
                {
                    id: 'cat_p1',
                    name: 'Gamme Connectée & NFC',
                    icon: 'fa-solid fa-wifi',
                    description: 'Cartes professionnelles intelligentes en métal et bois.',
                    cover_image: 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
                    items: [
                        { id: 'itm_p1', name: 'Smart Card Métal Mat Noir', description: 'Finition acier brossé mat, puce NFC NTAG216 haute puissance.', price: '350', badge: 'Luxe', image_url: '', available: true },
                        { id: 'itm_p2', name: 'Smart Card Bois Bambou Éco', description: 'Gravure laser personnalisée, matériau 100% écoresponsable.', price: '260', badge: 'Éco', image_url: '', available: true }
                    ]
                }
            ]
        },
        services: {
            title: 'Grille Tarifaire & Prestations',
            button_label: 'Consulter nos Forfaits & Tarifs',
            subtitle: 'Transparence totale, packs modulables et accompagnement sur-mesure.',
            currency: 'MAD',
            navigation_mode: 'vertical',
            item_layout: 'list',
            design_preset: 'luxury_gold',
            accent_color: '#d97706',
            bg_color: '#18181b',
            show_search: false,
            show_images: false,
            categories: [
                {
                    id: 'cat_s1',
                    name: 'Forfaits Conseil & Stratégie',
                    icon: 'fa-solid fa-briefcase',
                    description: 'Audit approfondi, feuille de route et pilotage de projet.',
                    cover_image: '',
                    items: [
                        { id: 'itm_s1', name: 'Diagnostic Stratégique 360°', description: 'Analyse complète de l\'existant et rapport d\'opportunités.', price: '4500', badge: 'Initial', image_url: '', available: true },
                        { id: 'itm_s2', name: 'Accompagnement Mensuel VIP', description: 'Séance hebdomadaire, support dédié et revues régulières.', price: '9000', badge: 'Suivi', image_url: '', available: true }
                    ]
                }
            ]
        }
    };

    window.applyMenuQuickStarter = function(key) {
        const tpl = starterTemplates[key];
        if (!tpl) return;
        currentMenu.title = tpl.title;
        currentMenu.button_label = tpl.button_label;
        currentMenu.subtitle = tpl.subtitle;
        currentMenu.currency = tpl.currency;
        currentMenu.navigation_mode = tpl.navigation_mode;
        currentMenu.item_layout = tpl.item_layout;
        currentMenu.design_preset = tpl.design_preset;
        currentMenu.accent_color = tpl.accent_color;
        currentMenu.bg_color = tpl.bg_color;
        currentMenu.show_search = tpl.show_search;
        currentMenu.show_images = tpl.show_images;
        currentMenu.categories = JSON.parse(JSON.stringify(tpl.categories));

        const titleEl = document.getElementById('menuTitleInput');
        const btnLabelEl = document.getElementById('menuButtonLabelInput');
        const subEl = document.getElementById('menuSubtitleInput');
        const currEl = document.getElementById('menuCurrencySelect');
        const layoutEl = document.getElementById('menuItemLayoutSelect');
        const presetEl = document.getElementById('menuDesignPresetSelect');
        const accentColEl = document.getElementById('menuAccentColorPicker');
        const accentHexEl = document.getElementById('menuAccentHexLabel');
        const bgColEl = document.getElementById('menuBgColorPicker');
        const searchToggle = document.getElementById('menuShowSearchToggle');
        const imgToggle = document.getElementById('menuShowImagesToggle');

        if (titleEl) titleEl.value = currentMenu.title;
        if (btnLabelEl) btnLabelEl.value = currentMenu.button_label;
        if (subEl) subEl.value = currentMenu.subtitle;
        if (currEl) currEl.value = currentMenu.currency;
        if (layoutEl) layoutEl.value = currentMenu.item_layout;
        if (presetEl) presetEl.value = currentMenu.design_preset;
        if (accentColEl) accentColEl.value = currentMenu.accent_color;
        if (accentHexEl) accentHexEl.textContent = (currentMenu.accent_color || '').toUpperCase();
        if (bgColEl) bgColEl.value = currentMenu.bg_color;
        if (searchToggle) searchToggle.checked = currentMenu.show_search;
        if (imgToggle) imgToggle.checked = currentMenu.show_images;

        window.selectMenuNavigationMode(currentMenu.navigation_mode);
        window.toggleInteractiveMenu(true);
        renderMenuCategories();
        window.syncMenuSettings();
    };

    window.selectMenuNavigationMode = function(mode, cardEl) {
        currentMenu.navigation_mode = mode;
        document.querySelectorAll('#menuNavModeCardsWrap .menu-nav-option-card').forEach(c => {
            if (c.dataset.navMode === mode) {
                c.classList.add('active');
            } else {
                c.classList.remove('active');
            }
        });
        syncMenuSerializedData();
    };

    window.syncMenuSettings = function() {
        const titleEl = document.getElementById('menuTitleInput');
        const btnLabelEl = document.getElementById('menuButtonLabelInput');
        const subEl = document.getElementById('menuSubtitleInput');
        const currEl = document.getElementById('menuCurrencySelect');
        const layoutEl = document.getElementById('menuItemLayoutSelect');
        const presetEl = document.getElementById('menuDesignPresetSelect');
        const accentColEl = document.getElementById('menuAccentColorPicker');
        const accentHexEl = document.getElementById('menuAccentHexLabel');
        const bgColEl = document.getElementById('menuBgColorPicker');
        const bgHexEl = document.getElementById('menuBgHexLabel');
        const customBgWrap = document.getElementById('menuCustomBgWrap');
        const searchToggle = document.getElementById('menuShowSearchToggle');
        const imgToggle = document.getElementById('menuShowImagesToggle');

        if (titleEl) currentMenu.title = titleEl.value;
        if (btnLabelEl) currentMenu.button_label = btnLabelEl.value;
        if (subEl) currentMenu.subtitle = subEl.value;
        if (currEl) currentMenu.currency = currEl.value;
        if (layoutEl) currentMenu.item_layout = layoutEl.value;
        if (presetEl) {
            currentMenu.design_preset = presetEl.value;
            if (customBgWrap) {
                customBgWrap.style.display = (presetEl.value === 'custom') ? 'block' : 'none';
            }
        }
        if (accentColEl) {
            currentMenu.accent_color = accentColEl.value;
            if (accentHexEl) accentHexEl.textContent = (accentColEl.value || '').toUpperCase();
        }
        if (bgColEl) {
            currentMenu.bg_color = bgColEl.value;
            if (bgHexEl) bgHexEl.textContent = (bgColEl.value || '').toUpperCase();
        }
        if (searchToggle) currentMenu.show_search = searchToggle.checked;
        if (imgToggle) currentMenu.show_images = imgToggle.checked;

        syncMenuSerializedData();
    };

    window.addMenuCategory = function() {
        if (!currentMenu.categories) currentMenu.categories = [];
        const newCatId = newMenuId('cat');
        currentMenu.categories.push({
            id: newCatId,
            name: 'Nouvelle Catégorie',
            icon: 'fa-solid fa-tag',
            description: '',
            cover_image: '',
            items: [
                {
                    id: newMenuId('itm'),
                    name: 'Nouvel Article',
                    description: '',
                    price: '50',
                    badge: '',
                    image_url: '',
                    available: true
                }
            ]
        });
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.moveMenuCategory = function(catIndex, delta) {
        const cats = currentMenu.categories;
        const target = catIndex + delta;
        if (target < 0 || target >= cats.length) return;
        const temp = cats[catIndex];
        cats[catIndex] = cats[target];
        cats[target] = temp;
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.removeMenuCategory = function(catId) {
        if (!confirm('Supprimer cette catégorie et tous ses articles ?')) return;
        currentMenu.categories = (currentMenu.categories || []).filter(c => c.id !== catId);
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.addMenuItem = function(catId) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat) return;
        if (!cat.items) cat.items = [];
        cat.items.push({
            id: newMenuId('itm'),
            name: 'Nouvel Article',
            description: '',
            price: '50',
            badge: '',
            image_url: '',
            available: true
        });
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.moveMenuItem = function(catId, itemIndex, delta) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat || !cat.items) return;
        const target = itemIndex + delta;
        if (target < 0 || target >= cat.items.length) return;
        const temp = cat.items[itemIndex];
        cat.items[itemIndex] = cat.items[target];
        cat.items[target] = temp;
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.removeMenuItem = function(catId, itemId) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat || !cat.items) return;
        cat.items = cat.items.filter(i => i.id !== itemId);
        renderMenuCategories();
        syncMenuSerializedData();
    };

    function newMenuId(prefix) {
        return prefix + '_' + Math.random().toString(36).substring(2, 9);
    }

    let menuFilterTerm = '';
    window.setMenuBuilderFilter = function(value) {
        menuFilterTerm = (value || '').toLowerCase().trim();
        renderMenuCategories();
    };

    window.toggleMenuCategoryCollapse = function(catId) {
        const box = document.querySelector(`.menu-category-box[data-cat-id="${catId}"]`);
        if (!box) return;
        box.classList.toggle('is-collapsed');
        const icon = box.querySelector('.collapse-toggle-btn i');
        if (icon) {
            icon.className = box.classList.contains('is-collapsed') ? 'fa-solid fa-chevron-down' : 'fa-solid fa-chevron-up';
        }
    };

    window.setAllMenuCategoriesCollapsed = function(collapse) {
        document.querySelectorAll('.menu-category-box').forEach(box => {
            if (collapse) {
                box.classList.add('is-collapsed');
            } else {
                box.classList.remove('is-collapsed');
            }
            const icon = box.querySelector('.collapse-toggle-btn i');
            if (icon) {
                icon.className = collapse ? 'fa-solid fa-chevron-down' : 'fa-solid fa-chevron-up';
            }
        });
    };

    window.duplicateMenuCategory = function(catId) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat) return;
        const clone = JSON.parse(JSON.stringify(cat));
        clone.id = newMenuId('cat');
        clone.name = (clone.name || 'Catégorie') + ' (Copie)';
        (clone.items || []).forEach(item => { item.id = newMenuId('itm'); });
        const idx = currentMenu.categories.findIndex(c => c.id === catId);
        currentMenu.categories.splice(idx + 1, 0, clone);
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.duplicateMenuItem = function(catId, itemId) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat || !cat.items) return;
        const item = cat.items.find(i => i.id === itemId);
        if (!item) return;
        const clone = JSON.parse(JSON.stringify(item));
        clone.id = newMenuId('itm');
        clone.name = (clone.name || 'Article') + ' (Copie)';
        const idx = cat.items.findIndex(i => i.id === itemId);
        cat.items.splice(idx + 1, 0, clone);
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.markAllMenuItemsAvailability = function(isAvailable) {
        (currentMenu.categories || []).forEach(cat => {
            (cat.items || []).forEach(item => {
                item.available = Boolean(isAvailable);
            });
        });
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.exportMenuJson = function() {
        const dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(currentMenu, null, 2));
        const dl = document.createElement('a');
        dl.setAttribute('href', dataStr);
        dl.setAttribute('download', 'menu-config-' + (new Date().toISOString().slice(0,10)) + '.json');
        document.body.appendChild(dl);
        dl.click();
        dl.remove();
    };

    window.importMenuJson = function(inputEl) {
        const file = inputEl.files && inputEl.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(evt) {
            try {
                const parsed = JSON.parse(evt.target.result);
                if (parsed && typeof parsed === 'object') {
                    currentMenu = Object.assign({}, currentMenu, parsed);
                    currentMenu.categories = parsed.categories || currentMenu.categories || [];
                    const titleEl = document.getElementById('menuTitleInput');
                    const btnLabelEl = document.getElementById('menuButtonLabelInput');
                    const subEl = document.getElementById('menuSubtitleInput');
                    const currEl = document.getElementById('menuCurrencySelect');
                    const layoutEl = document.getElementById('menuItemLayoutSelect');
                    const presetEl = document.getElementById('menuDesignPresetSelect');
                    const accentColEl = document.getElementById('menuAccentColorPicker');
                    const bgColEl = document.getElementById('menuBgColorPicker');

                    if (titleEl) titleEl.value = currentMenu.title || '';
                    if (btnLabelEl) btnLabelEl.value = currentMenu.button_label || '';
                    if (subEl) subEl.value = currentMenu.subtitle || '';
                    if (currEl && currentMenu.currency) currEl.value = currentMenu.currency;
                    if (layoutEl && currentMenu.item_layout) layoutEl.value = currentMenu.item_layout;
                    if (presetEl && currentMenu.design_preset) presetEl.value = currentMenu.design_preset;
                    if (accentColEl && currentMenu.accent_color) accentColEl.value = currentMenu.accent_color;
                    if (bgColEl && currentMenu.bg_color) bgColEl.value = currentMenu.bg_color;

                    window.selectMenuNavigationMode(currentMenu.navigation_mode || 'vertical');
                    window.toggleInteractiveMenu(true);
                    renderMenuCategories();
                    window.syncMenuSettings();
                    alert('Menu importé avec succès !');
                }
            } catch (err) {
                console.error(err);
                alert('Fichier JSON invalide pour le menu.');
            }
        };
        reader.readAsText(file);
    };

    function updateMenuStats() {
        const cats = currentMenu.categories || [];
        let itemsCount = 0;
        let priceSum = 0;
        let pricedItems = 0;
        let noPhotoCount = 0;
        let outOfStock = 0;

        cats.forEach(c => {
            (c.items || []).forEach(it => {
                itemsCount++;
                const p = parseFloat(it.price);
                if (!isNaN(p) && p > 0) { priceSum += p; pricedItems++; }
                if (!it.image_url) noPhotoCount++;
                if (it.available === false) outOfStock++;
            });
        });

        const avgPrice = pricedItems > 0 ? (priceSum / pricedItems).toFixed(1) + ' ' + (currentMenu.currency || 'MAD') : '—';
        const elCats = document.getElementById('menuStatCats');
        const elItems = document.getElementById('menuStatItems');
        const elAvg = document.getElementById('menuStatAvg');
        const elNoPhoto = document.getElementById('menuStatNoPhoto');
        const elOut = document.getElementById('menuStatOut');

        if (elCats) elCats.textContent = cats.length;
        if (elItems) elItems.textContent = itemsCount;
        if (elAvg) elAvg.textContent = avgPrice;
        if (elNoPhoto) elNoPhoto.textContent = noPhotoCount;
        if (elOut) elOut.textContent = outOfStock;
    }

    function renderMenuCategories() {
        if (!menuCategoriesContainer) return;
        menuCategoriesContainer.innerHTML = '';
        const cats = currentMenu.categories || [];

        const badgeCount = document.getElementById('menuCategoriesCountBadge');
        if (badgeCount) {
            badgeCount.textContent = `${cats.length} catégorie${cats.length > 1 ? 's' : ''}`;
        }
        updateMenuStats();

        if (cats.length === 0) {
            menuCategoriesContainer.innerHTML = `
                <div class="menu-items-empty">
                    <i class="fa-solid fa-layer-group"></i>
                    <div>Aucune catégorie pour le moment.</div>
                    <small class="text-muted d-block mt-1">Créez votre première catégorie pour organiser vos articles et plats.</small>
                    <button type="button" class="btn btn-sm btn-studio-primary text-white rounded-pill px-3 py-1.5 mt-2.5 fw-semibold" onclick="addMenuCategory()">
                        <i class="fa-solid fa-plus me-1"></i>Créer une Catégorie
                    </button>
                </div>
            `;
            return;
        }

        cats.forEach((cat, catIndex) => {
            const matchesCat = !menuFilterTerm ||
                (cat.name || '').toLowerCase().includes(menuFilterTerm) ||
                (cat.description || '').toLowerCase().includes(menuFilterTerm);

            const filteredItems = (cat.items || []).filter(item => {
                if (!menuFilterTerm) return true;
                return matchesCat ||
                    (item.name || '').toLowerCase().includes(menuFilterTerm) ||
                    (item.description || '').toLowerCase().includes(menuFilterTerm) ||
                    (item.badge || '').toLowerCase().includes(menuFilterTerm);
            });

            if (menuFilterTerm && !matchesCat && filteredItems.length === 0) {
                return;
            }

            const catBox = document.createElement('div');
            catBox.className = 'menu-category-box';
            catBox.dataset.catId = cat.id;

            catBox.innerHTML = `
                <div class="menu-category-header">
                    <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                        <span class="menu-page-pill">Page ${catIndex + 1}</span>
                        <div class="input-group input-group-sm" style="max-width: 280px;">
                            <button type="button" class="input-group-text bg-white cursor-pointer" onclick="openFaIconPicker(null, '${cat.id}')" title="Changer l'icône de cette catégorie">
                                <i class="${cat.icon || 'fa-solid fa-tag'} text-primary cat-icon-preview"></i>
                            </button>
                            <input type="text" class="form-control fw-bold cat-name-input" value="${(cat.name || '').replace(/"/g, '&quot;')}" placeholder="Nom de la catégorie">
                        </div>
                        <input type="text" class="form-control form-control-sm text-muted d-none d-md-block cat-desc-input" style="max-width: 300px;" value="${(cat.description || '').replace(/"/g, '&quot;')}" placeholder="Sous-titre / description (optionnel)">
                    </div>

                    <div class="d-flex align-items-center gap-1.5 ms-auto">
                        <span class="badge bg-white text-muted border font-mono small px-2 py-1">${(cat.items || []).length} article${(cat.items || []).length > 1 ? 's' : ''}</span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-white border move-cat-up-btn" ${catIndex === 0 ? 'disabled' : ''} title="Monter d'une page"><i class="fa-solid fa-arrow-up"></i></button>
                            <button type="button" class="btn btn-white border move-cat-down-btn" ${catIndex === cats.length - 1 ? 'disabled' : ''} title="Descendre d'une page"><i class="fa-solid fa-arrow-down"></i></button>
                        </div>
                        <button type="button" class="btn btn-sm btn-white border duplicate-cat-btn" title="Dupliquer la catégorie"><i class="fa-regular fa-copy"></i></button>
                        <button type="button" class="btn btn-sm btn-white border text-danger delete-cat-btn" title="Supprimer la catégorie"><i class="fa-solid fa-trash-can"></i></button>
                        <button type="button" class="btn btn-sm btn-white border collapse-toggle-btn" title="Plier / Déplier"><i class="fa-solid fa-chevron-up"></i></button>
                    </div>
                </div>

                <!-- Page Cover Image Panel -->
                <div class="menu-cover-panel">
                    <div class="menu-cover-preview" id="catCoverPreview_${cat.id}">
                        ${cat.cover_image ? `
                            <img src="${cat.cover_image}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'menu-cover-empty\\'><i class=\\'fa-regular fa-image\\'></i><span>Erreur image</span></div>';">
                        ` : `
                            <div class="menu-cover-empty">
                                <i class="fa-regular fa-image"></i>
                                <span>Image de Couverture</span>
                                <span style="font-size: 0.58rem; opacity: 0.7;">Page ${catIndex + 1}</span>
                            </div>
                        `}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="menu-field-label m-0"><i class="fa-solid fa-panorama text-warning me-1"></i>Image de Couverture pour cette Page / Catégorie</label>
                            ${cat.cover_image ? `
                                <button type="button" class="btn btn-link p-0 text-danger small text-decoration-none remove-cover-btn" style="font-size: 0.68rem;">
                                    <i class="fa-solid fa-trash me-0.5"></i>Supprimer la couverture
                                </button>
                            ` : ''}
                        </div>
                        <p class="text-muted small mb-1.5" style="font-size: 0.72rem;">
                            Cette image s'affiche en grand en tête de page comme couverture de catalogue pour la catégorie <strong>${cat.name || 'Page ' + (catIndex + 1)}</strong>.
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <label class="menu-upload-btn m-0">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Téléverser la Couverture</span>
                                <input type="file" accept="image/*" class="d-none menu-cat-cover-file">
                            </label>
                            <input type="text" class="form-control form-control-sm font-mono cat-cover-url-input" style="max-width: 240px; font-size: 0.72rem;" value="${(cat.cover_image || '').replace(/"/g, '&quot;')}" placeholder="ou coller une URL https://...">
                            <span class="small font-mono text-muted upload-status-text" style="font-size: 0.68rem;"></span>
                        </div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="menu-items-list" id="catItemsList_${cat.id}"></div>

                <div class="menu-category-footer p-2.5 px-3 bg-light border-top d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-xs btn-studio-primary text-white rounded-pill px-3 py-1 fw-semibold add-item-btn">
                        <i class="fa-solid fa-plus me-1"></i>Ajouter un Article à ${cat.name || 'cette catégorie'}
                    </button>
                    <span class="small font-mono text-muted" style="font-size: 0.68rem;">Disposition : ${currentMenu.item_layout === 'list' ? '1 carte détaillée par ligne' : (currentMenu.item_layout === 'compact' ? 'Liste compacte' : '2 cartes par ligne')}</span>
                </div>
            `;

            // Event bindings for Category Header
            const nameInp = catBox.querySelector('.cat-name-input');
            const descInp = catBox.querySelector('.cat-desc-input');
            nameInp.addEventListener('input', function() {
                cat.name = this.value;
                syncMenuSerializedData();
            });
            if (descInp) {
                descInp.addEventListener('input', function() {
                    cat.description = this.value;
                    syncMenuSerializedData();
                });
            }

            catBox.querySelector('.move-cat-up-btn').addEventListener('click', () => moveMenuCategory(catIndex, -1));
            catBox.querySelector('.move-cat-down-btn').addEventListener('click', () => moveMenuCategory(catIndex, 1));
            catBox.querySelector('.duplicate-cat-btn').addEventListener('click', () => duplicateMenuCategory(cat.id));
            catBox.querySelector('.delete-cat-btn').addEventListener('click', () => removeMenuCategory(cat.id));
            catBox.querySelector('.collapse-toggle-btn').addEventListener('click', () => toggleMenuCategoryCollapse(cat.id));
            catBox.querySelector('.add-item-btn').addEventListener('click', () => addMenuItem(cat.id));

            // Cover file upload & direct URL input
            const coverFileInput = catBox.querySelector('.menu-cat-cover-file');
            const coverUrlInput = catBox.querySelector('.cat-cover-url-input');
            const statusSpan = catBox.querySelector('.upload-status-text');
            const removeCoverBtn = catBox.querySelector('.remove-cover-btn');

            if (coverFileInput) {
                coverFileInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (!file) return;
                    uploadMenuImageAjax(file, (url) => {
                        cat.cover_image = url;
                        renderMenuCategories();
                        syncMenuSerializedData();
                    }, (loading) => {
                        if (statusSpan) statusSpan.textContent = loading ? 'Téléversement en cours...' : '';
                    });
                });
            }

            if (coverUrlInput) {
                coverUrlInput.addEventListener('change', function() {
                    cat.cover_image = this.value.trim();
                    renderMenuCategories();
                    syncMenuSerializedData();
                });
            }

            if (removeCoverBtn) {
                removeCoverBtn.addEventListener('click', function() {
                    cat.cover_image = '';
                    renderMenuCategories();
                    syncMenuSerializedData();
                });
            }

            // Render Items inside Category
            const itemsContainer = catBox.querySelector(`#catItemsList_${cat.id}`);
            const items = cat.items || [];

            if (filteredItems.length === 0) {
                itemsContainer.innerHTML = `
                    <div class="menu-items-empty py-3">
                        <i class="fa-solid fa-utensils"></i>
                        <div>${menuFilterTerm ? 'Aucun article ne correspond au filtre.' : 'Aucun article dans cette catégorie.'}</div>
                        <button type="button" class="btn btn-xs btn-studio-outline rounded-pill px-3 py-1 mt-1.5 fw-semibold" onclick="addMenuItem('${cat.id}')">
                            <i class="fa-solid fa-plus me-1"></i>Ajouter un Article
                        </button>
                    </div>
                `;
            } else {
                filteredItems.forEach((item) => {
                    const itemIdx = items.indexOf(item);
                    const itemRow = document.createElement('div');
                    itemRow.className = 'menu-item-row' + (item.available === false ? ' is-unavailable' : '');
                    itemRow.dataset.itemId = item.id;

                    itemRow.innerHTML = `
                        <!-- Thumbnail Column with Direct Upload Button -->
                        <div class="menu-item-thumb-col">
                            <div class="menu-item-thumb" id="itemThumb_${item.id}">
                                ${item.image_url ? `
                                    <img src="${item.image_url}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fa-solid fa-utensils\\'></i>';">
                                ` : `
                                    <i class="fa-solid fa-utensils"></i>
                                `}
                            </div>
                            <label class="menu-upload-btn">
                                <i class="fa-solid fa-camera"></i>
                                <span>${item.image_url ? 'Changer' : 'Photo'}</span>
                                <input type="file" accept="image/*" class="d-none menu-item-file-upload">
                            </label>
                            ${item.image_url ? `
                                <button type="button" class="btn btn-link p-0 text-danger text-decoration-none remove-item-img-btn" style="font-size: 0.65rem;">
                                    Retirer
                                </button>
                            ` : ''}
                        </div>

                        <!-- Editable Fields Column -->
                        <div class="menu-item-fields">
                            <div class="row g-2 mb-1.5">
                                <div class="col-md-7">
                                    <label class="menu-field-label">Nom du Produit / Plat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm fw-bold item-name-input" value="${(item.name || '').replace(/"/g, '&quot;')}" placeholder="ex: Filet de Bœuf Rossini">
                                </div>
                                <div class="col-md-5">
                                    <label class="menu-field-label">Prix (${currentMenu.currency || 'MAD'})</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control font-mono fw-bold item-price-input" value="${item.price !== undefined ? item.price : ''}" placeholder="ex: 120">
                                        <span class="input-group-text bg-white font-mono text-muted" style="font-size: 0.72rem;">${currentMenu.currency || 'MAD'}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="menu-field-label">Description / Ingrédients / Caractéristiques</label>
                                    <textarea rows="2" class="form-control form-control-sm item-desc-input" placeholder="Détaillez la préparation, les ingrédients, allergènes ou caractéristiques...">${item.description || ''}</textarea>
                                </div>
                                <div class="col-md-7">
                                    <label class="menu-field-label">Badge Promotionnel (ex: Signature, Chef, Éco)</label>
                                    <input type="text" class="form-control form-control-sm item-badge-input" value="${(item.badge || '').replace(/"/g, '&quot;')}" placeholder="ex: Signature / Populaire">
                                </div>
                                <div class="col-md-5 d-flex align-items-end">
                                    <div class="form-check form-switch m-0 pb-1 d-flex align-items-center gap-2">
                                        <input class="form-check-input cursor-pointer item-avail-switch" type="checkbox" ${item.available !== false ? 'checked' : ''}>
                                        <label class="form-check-label small fw-semibold cursor-pointer">${item.available !== false ? 'Disponible' : '<span class="text-danger">Épuisé</span>'}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Column -->
                        <div class="menu-item-actions">
                            <div class="btn-group-vertical btn-group-sm shadow-2xs">
                                <button type="button" class="btn btn-white border move-item-up-btn" ${itemIdx === 0 ? 'disabled' : ''} title="Monter"><i class="fa-solid fa-arrow-up"></i></button>
                                <button type="button" class="btn btn-white border move-item-down-btn" ${itemIdx === items.length - 1 ? 'disabled' : ''} title="Descendre"><i class="fa-solid fa-arrow-down"></i></button>
                            </div>
                            <button type="button" class="btn btn-sm btn-white border duplicate-item-btn shadow-2xs" title="Dupliquer"><i class="fa-regular fa-copy"></i></button>
                            <button type="button" class="btn btn-sm btn-white border text-danger delete-item-btn shadow-2xs" title="Supprimer"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    `;

                    // Bind item inputs
                    const inName = itemRow.querySelector('.item-name-input');
                    const inPrice = itemRow.querySelector('.item-price-input');
                    const inDesc = itemRow.querySelector('.item-desc-input');
                    const inBadge = itemRow.querySelector('.item-badge-input');
                    const swAvail = itemRow.querySelector('.item-avail-switch');

                    inName.addEventListener('input', function() { item.name = this.value; syncMenuSerializedData(); });
                    inPrice.addEventListener('input', function() { item.price = this.value; syncMenuSerializedData(); updateMenuStats(); });
                    inDesc.addEventListener('input', function() { item.description = this.value; syncMenuSerializedData(); });
                    inBadge.addEventListener('input', function() { item.badge = this.value; syncMenuSerializedData(); });
                    swAvail.addEventListener('change', function() {
                        item.available = this.checked;
                        itemRow.classList.toggle('is-unavailable', !this.checked);
                        const label = itemRow.querySelector('.form-check-label');
                        if (label) label.innerHTML = this.checked ? 'Disponible' : '<span class="text-danger">Épuisé</span>';
                        syncMenuSerializedData();
                        updateMenuStats();
                    });

                    // Item actions
                    itemRow.querySelector('.move-item-up-btn').addEventListener('click', () => moveMenuItem(cat.id, itemIdx, -1));
                    itemRow.querySelector('.move-item-down-btn').addEventListener('click', () => moveMenuItem(cat.id, itemIdx, 1));
                    itemRow.querySelector('.duplicate-item-btn').addEventListener('click', () => duplicateMenuItem(cat.id, item.id));
                    itemRow.querySelector('.delete-item-btn').addEventListener('click', () => removeMenuItem(cat.id, item.id));

                    // Image upload
                    const itemFileInput = itemRow.querySelector('.menu-item-file-upload');
                    const removeImgBtn = itemRow.querySelector('.remove-item-img-btn');

                    if (itemFileInput) {
                        itemFileInput.addEventListener('change', function() {
                            const file = this.files && this.files[0];
                            if (!file) return;
                            uploadMenuImageAjax(file, (url) => {
                                item.image_url = url;
                                renderMenuCategories();
                                syncMenuSerializedData();
                            });
                        });
                    }

                    if (removeImgBtn) {
                        removeImgBtn.addEventListener('click', function() {
                            item.image_url = '';
                            renderMenuCategories();
                            syncMenuSerializedData();
                        });
                    }

                    itemsContainer.appendChild(itemRow);
                });
            }

            menuCategoriesContainer.appendChild(catBox);
        });
    }

    function syncMenuSerializedData() {
        if (!menuConfigJsonInput) return;
        menuConfigJsonInput.value = JSON.stringify(currentMenu);

        // Update CTA Button on Phone Mockup
        const ctaWrap = document.getElementById('previewMenuCtaWrap');
        const ctaBtnLabel = document.getElementById('previewMenuBtnLabel');
        const ctaSub = document.getElementById('previewMenuSubtitle');

        if (ctaWrap) {
            ctaWrap.style.display = currentMenu.enabled ? 'block' : 'none';
        }
        if (ctaBtnLabel) {
            ctaBtnLabel.textContent = currentMenu.button_label || 'Consulter le Menu / Catalogue';
        }
        if (ctaSub) {
            ctaSub.textContent = currentMenu.title || 'Notre Menu & Catalogue';
        }

        renderSimulatorMenuDrawerContent('classic');
        renderSimulatorMenuDrawerContent('dnd');
    }

    // In-Phone Drawer View Handlers
    window.openSimulatorMenuDrawer = function(mode) {
        mode = mode || 'classic';
        const drawer = document.getElementById(`simulatorMenuDrawer_${mode}`);
        if (drawer) {
            drawer.style.display = 'block';
            renderSimulatorMenuDrawerContent(mode);
        }
        const pillCard = document.getElementById('simViewPillCard');
        const pillMenu = document.getElementById('simViewPillMenu');
        if (pillCard && pillMenu) {
            pillCard.classList.remove('active');
            pillMenu.classList.add('active');
        }
    };

    window.closeSimulatorMenuDrawer = function(mode) {
        mode = mode || 'classic';
        const drawer = document.getElementById(`simulatorMenuDrawer_${mode}`);
        if (drawer) drawer.style.display = 'none';
        const pillCard = document.getElementById('simViewPillCard');
        const pillMenu = document.getElementById('simViewPillMenu');
        if (pillCard && pillMenu) {
            pillMenu.classList.remove('active');
            pillCard.classList.add('active');
        }
    };

    window.filterSimulatorMenuCategory = function(mode, catId) {
        if (mode === 'classic') activeSimMenuCat_classic = catId;
        if (mode === 'dnd') activeSimMenuCat_dnd = catId;
        renderSimulatorMenuDrawerContent(mode);
    };

    window.stepSimulatorCatalogPage = function(mode, delta) {
        const categories = currentMenu.categories || [];
        if (categories.length === 0) return;
        let page = (mode === 'classic') ? simCatalogPage_classic : simCatalogPage_dnd;
        page += delta;
        if (page < 0) page = 0;
        if (page >= categories.length) page = categories.length - 1;
        if (mode === 'classic') simCatalogPage_classic = page;
        if (mode === 'dnd') simCatalogPage_dnd = page;
        renderSimulatorMenuDrawerContent(mode);
    };

    function renderSimulatorMenuDrawerContent(mode) {
        mode = mode || 'classic';
        const drawerEl = document.getElementById(`simulatorMenuDrawer_${mode}`);
        const tabsEl = document.getElementById(`simMenuTabs_${mode}`);
        const itemsEl = document.getElementById(`simMenuItems_${mode}`);
        const countEl = document.getElementById(`simMenuCount_${mode}`);
        const titleEl = document.getElementById(`simMenuTitle_${mode}`);
        const subEl = document.getElementById(`simMenuSub_${mode}`);
        const searchWrapEl = document.getElementById(`simMenuSearchWrap_${mode}`);
        if (!tabsEl || !itemsEl) return;

        const categories = currentMenu.categories || [];
        let totalItems = 0;
        categories.forEach(c => { totalItems += (c.items || []).length; });

        if (countEl) countEl.textContent = `${totalItems} article${totalItems > 1 ? 's' : ''}`;
        if (titleEl) titleEl.textContent = currentMenu.title || 'Notre Menu & Catalogue';
        if (subEl) subEl.textContent = currentMenu.subtitle || 'Sélectionnez une catégorie';

        const navMode = currentMenu.navigation_mode || 'vertical';
        const itemLayout = currentMenu.item_layout || 'grid';
        const designPreset = currentMenu.design_preset || 'card_theme';
        const currency = currentMenu.currency || 'MAD';
        const accentCol = currentMenu.accent_color || '#ea580c';

        // Apply Menu Theme Preset to Drawer
        let menuBgHex = '#0f172a';
        if (designPreset === 'card_theme') {
            menuBgHex = document.getElementById('bgColorPicker')?.value || '#0f172a';
        } else if (designPreset === 'bistro_dark') {
            menuBgHex = '#111827';
        } else if (designPreset === 'clean_light') {
            menuBgHex = '#f8fafc';
        } else if (designPreset === 'luxury_gold') {
            menuBgHex = '#18181b';
        } else if (designPreset === 'custom') {
            menuBgHex = currentMenu.bg_color || '#0f172a';
        }
        const isLightMenu = isLightHexColor(menuBgHex);
        if (drawerEl) {
            drawerEl.style.background = menuBgHex;
            drawerEl.classList.toggle('is-light-menu', isLightMenu);
        }

        // Toggle in-phone search bar
        if (searchWrapEl) {
            searchWrapEl.style.display = (currentMenu.show_search !== false) ? 'block' : 'none';
        }

        const activeCat = (mode === 'classic') ? activeSimMenuCat_classic : activeSimMenuCat_dnd;
        const catalogPageIdx = (mode === 'classic') ? simCatalogPage_classic : simCatalogPage_dnd;
        const searchTerm = (mode === 'classic') ? simMenuSearchTerm_classic : simMenuSearchTerm_dnd;

        // Render Tabs
        if (navMode === 'catalog') {
            const currentCat = categories[catalogPageIdx];
            tabsEl.innerHTML = currentCat ? `
                <div class="w-100 d-flex align-items-center justify-content-between px-2 py-1 rounded-3" style="background: ${isLightMenu ? 'rgba(15,23,42,0.06)' : 'rgba(255,255,255,0.08)'}; border: 1px solid ${isLightMenu ? 'rgba(15,23,42,0.12)' : 'rgba(255,255,255,0.14)'};">
                    <span class="small fw-bold" style="font-size: 0.74rem;"><i class="${currentCat.icon || 'fa-solid fa-book-open'} me-1" style="color: ${accentCol};"></i>${currentCat.name}</span>
                    <span class="badge rounded-pill font-mono" style="background: ${accentCol}; color: #fff; font-size: 0.62rem;">Page ${catalogPageIdx + 1} / ${categories.length}</span>
                </div>
            ` : '';
        } else {
            let tabsHtml = '';
            if (navMode === 'vertical') {
                tabsHtml += `<button type="button" class="sim-menu-tab-btn ${activeCat === 'all' ? 'active' : ''}" style="${activeCat === 'all' ? `background: ${accentCol} !important; border-color: ${accentCol} !important; color: #ffffff !important; box-shadow: 0 3px 10px ${accentCol}55;` : ''}" onclick="filterSimulatorMenuCategory('${mode}', 'all')">Tout</button>`;
            }
            categories.forEach(cat => {
                const isActive = (activeCat === cat.id);
                tabsHtml += `<button type="button" class="sim-menu-tab-btn ${isActive ? 'active' : ''}" style="${isActive ? `background: ${accentCol} !important; border-color: ${accentCol} !important; color: #ffffff !important; box-shadow: 0 3px 10px ${accentCol}55;` : ''}" onclick="filterSimulatorMenuCategory('${mode}', '${cat.id}')">${cat.name || 'Catégorie'}</button>`;
            });
            tabsEl.innerHTML = tabsHtml;
        }

        // Render Items and Category Cover according to navigation_mode & item_layout
        let itemsHtml = '';

        categories.forEach((cat, idx) => {
            if (navMode === 'catalog' && idx !== catalogPageIdx) return;
            if (navMode !== 'catalog' && activeCat !== 'all' && activeCat !== cat.id) return;

            const visibleItems = (cat.items || []).filter(item => {
                if (!searchTerm || currentMenu.show_search === false) return true;
                return (item.name || '').toLowerCase().includes(searchTerm) ||
                    (item.description || '').toLowerCase().includes(searchTerm) ||
                    (item.badge || '').toLowerCase().includes(searchTerm);
            });

            if (searchTerm && currentMenu.show_search !== false && visibleItems.length === 0) {
                return;
            }

            // Page Cover Image on Preview Simulator
            if (cat.cover_image) {
                itemsHtml += `
                    <div class="rounded-3 overflow-hidden position-relative mb-2.5 border" style="height: 110px; border-color: rgba(255,255,255,0.18) !important; box-shadow: 0 4px 14px rgba(0,0,0,0.25);">
                        <img src="${cat.cover_image}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop&q=80';">
                        <div class="position-absolute top-0 bottom-0 start-0 end-0 p-2 d-flex flex-column justify-content-between" style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.38) 60%, rgba(0,0,0,0.12) 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill page-number-pill" style="background: ${accentCol}; color:#fff; font-size: 0.58rem; font-weight:700; box-shadow: 0 2px 6px ${accentCol}66;">Page ${idx + 1} / ${categories.length}</span>
                                <span class="badge rounded-pill bg-dark bg-opacity-60 text-white" style="font-size: 0.56rem;">${visibleItems.length} article${visibleItems.length > 1 ? 's' : ''}</span>
                            </div>
                            <div>
                                <div class="fw-extrabold text-white" style="font-size: 0.86rem; text-shadow: 0 1px 4px rgba(0,0,0,0.9);"><i class="${cat.icon || 'fa-solid fa-tag'} me-1.5" style="color: ${accentCol};"></i>${cat.name || 'Catégorie'}</div>
                                ${cat.description ? `<div class="text-white text-opacity-85 text-truncate" style="font-size: 0.64rem; text-shadow: 0 1px 3px rgba(0,0,0,0.9);">${cat.description}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                itemsHtml += `
                    <div class="rounded-3 overflow-hidden position-relative mb-2.5 border d-flex flex-column justify-content-between p-2" style="height: 72px; background: linear-gradient(135deg, ${accentCol}30 0%, ${isLightMenu ? 'rgba(15,23,42,0.04)' : 'rgba(255,255,255,0.06)'} 100%); border-color: ${isLightMenu ? 'rgba(15,23,42,0.12)' : 'rgba(255,255,255,0.12)'} !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill" style="background: ${accentCol}; color:#fff; font-size: 0.56rem; font-weight:700; box-shadow: 0 2px 6px ${accentCol}66;">Page ${idx + 1} / ${categories.length}</span>
                            <span class="badge rounded-pill bg-dark bg-opacity-40 text-white font-mono" style="font-size: 0.54rem;"><i class="fa-solid fa-image me-1 opacity-70"></i>Couverture de Page</span>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 0.8rem;"><i class="${cat.icon || 'fa-solid fa-tag'} me-1.5" style="color: ${accentCol};"></i>${cat.name || 'Catégorie'}</div>
                            ${cat.description ? `<div class="opacity-70 text-truncate" style="font-size: 0.62rem;">${cat.description}</div>` : ''}
                        </div>
                    </div>
                `;
            }

            const cardBgStyle = isLightMenu
                ? 'background:#ffffff; color:#0f172a; border-color:#e2e8f0 !important;'
                : 'background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.12) !important;';

            if (itemLayout === 'list') {
                // Detailed 1-column list cards
                itemsHtml += '<div class="d-flex flex-column gap-2 mb-2.5">';
                visibleItems.forEach(item => {
                    const isAvail = item.available !== false;
                    itemsHtml += `
                        <div class="sim-menu-item-card text-start d-flex align-items-center gap-2 p-2 rounded-3 border" style="${cardBgStyle} ${!isAvail ? 'opacity:0.5;' : ''} box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            ${currentMenu.show_images !== false ? `
                                <div class="position-relative rounded-2 overflow-hidden flex-shrink-0" style="width: 56px; height: 56px; background: rgba(148,163,184,0.15);">
                                    ${item.image_url ? `
                                        <img src="${item.image_url}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-100 h-100 d-flex align-items-center justify-content-center opacity-40\\'><i class=\\'fa-solid fa-utensils small\\'></i></div>';">
                                    ` : `
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center opacity-40"><i class="fa-solid fa-utensils small"></i></div>
                                    `}
                                </div>
                            ` : ''}
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <div class="fw-bold text-truncate" style="font-size: 0.76rem;">${item.name || 'Article'}</div>
                                    ${item.price !== '' && item.price !== null ? `<span class="badge rounded-pill font-mono flex-shrink-0" style="background: ${accentCol}22; color: ${accentCol}; font-size: 0.64rem; border: 1px solid ${accentCol}66;">${item.price} ${currency}</span>` : ''}
                                </div>
                                ${item.description ? `<div class="opacity-70 mt-0.5 text-truncate" style="font-size: 0.62rem;">${item.description}</div>` : ''}
                                ${(item.badge || !isAvail) ? `
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        ${item.badge ? `<span class="badge rounded-pill" style="background: ${accentCol}; color:#fff; font-size: 0.5rem;">${item.badge}</span>` : ''}
                                        ${!isAvail ? `<span class="badge bg-danger rounded-pill" style="font-size: 0.5rem;">Épuisé</span>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });
                itemsHtml += '</div>';
            } else if (itemLayout === 'compact') {
                // Compact menu rows
                itemsHtml += '<div class="d-flex flex-column gap-1.5 mb-2.5">';
                visibleItems.forEach(item => {
                    const isAvail = item.available !== false;
                    itemsHtml += `
                        <div class="sim-menu-item-card text-start d-flex align-items-center justify-content-between gap-2 px-2.5 py-1.5 rounded-3 border" style="${cardBgStyle} ${!isAvail ? 'opacity:0.5;' : ''}">
                            <div class="min-w-0 flex-grow-1">
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="fw-bold text-truncate" style="font-size: 0.74rem;">${item.name || 'Article'}</span>
                                    ${item.badge ? `<span class="badge rounded-pill" style="background: ${accentCol}; color:#fff; font-size: 0.48rem;">${item.badge}</span>` : ''}
                                    ${!isAvail ? `<span class="badge bg-danger rounded-pill" style="font-size: 0.48rem;">Épuisé</span>` : ''}
                                </div>
                                ${item.description ? `<div class="opacity-60 text-truncate" style="font-size: 0.58rem;">${item.description}</div>` : ''}
                            </div>
                            ${item.price !== '' && item.price !== null ? `<span class="badge rounded-pill font-mono flex-shrink-0" style="background: ${accentCol}22; color: ${accentCol}; font-size: 0.64rem; border: 1px solid ${accentCol}66;">${item.price} ${currency}</span>` : ''}
                        </div>
                    `;
                });
                itemsHtml += '</div>';
            } else {
                // Default Grid: 2 cards on each row
                itemsHtml += '<div class="row g-2 mb-2.5">';

                visibleItems.forEach(item => {
                    const isAvail = item.available !== false;
                    const cardInner = `
                        <div class="col-6">
                            <div class="sim-menu-item-card text-start h-100 d-flex flex-column p-2 rounded-3 border" style="${cardBgStyle} ${!isAvail ? 'opacity:0.5;' : ''} box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                ${currentMenu.show_images !== false ? `
                                    <div class="position-relative w-100 rounded-2 overflow-hidden mb-1.5" style="height: 68px; background: rgba(148,163,184,0.15);">
                                        ${item.image_url ? `
                                            <img src="${item.image_url}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-100 h-100 d-flex align-items-center justify-content-center opacity-40\\'><i class=\\'fa-solid fa-camera small\\'></i></div>';">
                                        ` : `
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center opacity-40">
                                                <i class="fa-solid fa-utensils small"></i>
                                            </div>
                                        `}
                                        ${item.badge ? `<span class="badge rounded-pill position-absolute top-0 start-0 m-1" style="background: ${accentCol}; color:#fff; font-size: 0.52rem; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">${item.badge}</span>` : ''}
                                        ${!isAvail ? `<span class="badge bg-danger rounded-pill position-absolute top-0 end-0 m-1" style="font-size: 0.52rem;">Épuisé</span>` : ''}
                                    </div>
                                ` : (item.badge || !isAvail ? `
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        ${item.badge ? `<span class="badge rounded-pill" style="background: ${accentCol}; color:#fff; font-size: 0.52rem;">${item.badge}</span>` : ''}
                                        ${!isAvail ? `<span class="badge bg-danger rounded-pill" style="font-size: 0.52rem;">Épuisé</span>` : ''}
                                    </div>
                                ` : '')}
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold text-truncate" style="font-size: 0.76rem;" title="${(item.name || '').replace(/"/g, '&quot;')}">${item.name || 'Article'}</div>
                                    ${item.description ? `<div class="opacity-70 mt-0.5" style="font-size: 0.62rem; line-height: 1.25; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">${item.description}</div>` : ''}
                                </div>
                                ${item.price !== '' && item.price !== null ? `
                                    <div class="mt-2 pt-1 border-top text-center" style="border-color: ${isLightMenu ? 'rgba(15,23,42,0.08)' : 'rgba(255,255,255,0.1)'} !important;">
                                        <span class="badge rounded-pill font-mono w-100" style="background: ${accentCol}22; color: ${accentCol}; font-size: 0.65rem; font-weight: 700; border: 1px solid ${accentCol}66; box-shadow: 0 1px 4px ${accentCol}20;">${item.price} ${currency}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    itemsHtml += cardInner;
                });

                itemsHtml += '</div>';
            }
        });

        // Catalog Pager
        if (navMode === 'catalog' && categories.length > 0) {
            itemsHtml += `
                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top" style="border-color: ${isLightMenu ? 'rgba(15,23,42,0.12)' : 'rgba(255,255,255,0.12)'} !important;">
                    <button type="button" class="btn btn-xs rounded-pill px-2.5 py-1 font-mono" style="background: ${isLightMenu ? 'rgba(15,23,42,0.08)' : 'rgba(255,255,255,0.12)'}; color: ${isLightMenu ? '#0f172a' : '#ffffff'}; font-size: 0.65rem;" ${catalogPageIdx <= 0 ? 'disabled' : ''} onclick="stepSimulatorCatalogPage('${mode}', -1)">
                        <i class="fa-solid fa-chevron-left me-1"></i>Précédent
                    </button>
                    <span class="font-mono small opacity-75" style="font-size: 0.65rem;">Page ${catalogPageIdx + 1}/${categories.length}</span>
                    <button type="button" class="btn btn-xs text-white rounded-pill px-2.5 py-1 font-mono" style="background: ${accentCol}; font-size: 0.65rem; box-shadow: 0 2px 8px ${accentCol}55;" ${catalogPageIdx >= categories.length - 1 ? 'disabled' : ''} onclick="stepSimulatorCatalogPage('${mode}', 1)">
                        Suivant<i class="fa-solid fa-chevron-right ms-1"></i>
                    </button>
                </div>
            `;
        }

        itemsEl.innerHTML = itemsHtml || '<div class="text-center py-3 small opacity-60">Aucun article dans cette catégorie</div>';
    }

    // Smooth Scrollspy for Quick Navigation Pills
    const navPills = document.querySelectorAll('.quick-nav-pill');
    const sections = Array.from(navPills).map(pill => {
        const id = pill.getAttribute('href');
        return document.querySelector(id);
    }).filter(Boolean);

    window.addEventListener('scroll', function() {
        const scrollPos = window.scrollY + 120;
        let currentSecId = null;
        sections.forEach(sec => {
            if (sec.offsetTop <= scrollPos) {
                currentSecId = sec.getAttribute('id');
            }
        });
        if (currentSecId) {
            navPills.forEach(pill => {
                if (pill.getAttribute('href') === '#' + currentSecId) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });
        }
    });

    // -------------------------------------------------------------------------
    // 11. Initial Mount
    // -------------------------------------------------------------------------
    syncLivePreviewStyles();
    syncLivePreviewText();
    renderAllBlockViews();
    renderMenuCategories();
    toggleInteractiveMenu(currentMenu && currentMenu.enabled !== false);
    syncMenuSerializedData();
    updateFormCompletionMeter();

    if (window.location.hash === '#sec-menu') {
        enableAndScrollToMenuSection();
        if (typeof setSimulatorView === 'function') {
            setSimulatorView('menu', document.getElementById('simViewPillMenu'));
        }
    }
});
</script>
@endpush