@extends('layouts.app')

@section('title', 'Créer une Carte Visite Digitale')

@section('content')
<div class="container-fluid px-0 card-builder-container">

    <!-- Top Command Bar / Action Header -->
    <div class="card bg-white border-0 shadow-sm mb-4 rounded-4 overflow-hidden top-command-bar">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="command-bar-icon-wrap rounded-3 text-white shadow-sm d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-vcard fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h2 class="fw-bold text-dark mb-0 page-headline">Créer une Carte Visite Digitale</h2>
                            <span class="badge rounded-pill px-2.5 py-1 text-white font-mono version-pill">Studio Pro v2.0</span>
                        </div>
                        <p class="text-muted small mb-0 mt-0.5 page-subtitle">
                            Concevez votre carte connectée NFC et QR code avec aperçu en temps réel sur smartphone.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2.5">
                    <a href="{{ route('admin.digital-cards.index') }}" class="btn btn-light fw-medium border px-3 py-2 text-secondary d-flex align-items-center gap-1.5 rounded-3 btn-cancel">
                        <i class="bi bi-arrow-left small"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="submit" form="cardBuilderForm" class="btn text-white fw-semibold d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm btn-publish-card">
                        <i class="bi bi-cloud-arrow-up fs-6"></i>
                        <span>Publier la carte</span>
                    </button>
                </div>
            </div>

            <!-- Mode Selector Switcher -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 mt-3 border-top mode-selector-wrapper">
                <div class="d-flex flex-wrap align-items-center gap-2.5">
                    <span class="small fw-bold text-muted text-uppercase font-mono me-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Mode d'édition :</span>
                    <div class="mode-toggle-group p-1 bg-light rounded-pill border d-inline-flex align-items-center" role="group">
                        <button type="button" class="btn btn-sm btn-white active px-3.5 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-2 mode-tab-btn" id="btnModeClassic" onclick="switchBuilderMode('classic')">
                            <i class="bi bi-ui-checks text-primary"></i>
                            <span>Option 1 : Formulaire Standard</span>
                        </button>
                        <button type="button" class="btn btn-sm text-muted px-3.5 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-2 mode-tab-btn" id="btnModeDragDrop" onclick="switchBuilderMode('dragdrop')">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Option 2 : Glisser-Déposer</span>
                            <span class="badge rounded-pill text-white ms-1" style="background: #6320ee; font-size: 0.65rem; font-weight: 600;">Studio</span>
                        </button>
                    </div>
                </div>

                <div class="text-muted small d-none d-lg-flex align-items-center gap-2 font-mono" style="font-size: 0.78rem;">
                    <span class="mode-pulse-indicator"></span>
                    <span id="modeDescriptionText">Formulaire pas-à-pas avec synchronisation instantanée du simulateur.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Builder Form -->
    <form id="cardBuilderForm" method="POST" action="{{ route('admin.digital-cards.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- Serialized JSON hidden fields for theme and content blocks -->
        <input type="hidden" name="theme_config" id="theme_config_json">
        <input type="hidden" name="content_blocks" id="content_blocks_json">

        <!-- =================================================================== -->
        <!-- OPTION 1: CLASSIC FORM BUILDER PANE -->
        <!-- =================================================================== -->
        <div id="classicBuilderPane">
            <div class="row g-4 align-items-start">
                
                <!-- Left Column: Form Cards & Steps -->
                <div class="col-xl-7 col-lg-7">

                    <!-- Sticky Quick-Jump Navigation Tabs -->
                    <div class="sticky-nav-container mb-3.5">
                        <div class="d-flex align-items-center gap-2 pb-1 overflow-x-auto quick-nav-bar" style="scrollbar-width: none;">
                            <a href="#sec-gabarits" class="quick-nav-pill active">
                                <i class="bi bi-palette text-primary me-1"></i>1. Gabarit
                            </a>
                            <a href="#sec-identite" class="quick-nav-pill">
                                <i class="bi bi-person-badge text-primary me-1"></i>2. Identité
                            </a>
                            <a href="#sec-medias" class="quick-nav-pill">
                                <i class="bi bi-images text-primary me-1"></i>3. Médias
                            </a>
                            <a href="#sec-style" class="quick-nav-pill">
                                <i class="bi bi-sliders text-primary me-1"></i>4. Style
                            </a>
                            <a href="#sec-canaux" class="quick-nav-pill">
                                <i class="bi bi-link-45deg text-primary me-1"></i>5. Canaux
                            </a>
                        </div>
                    </div>

                    <!-- 1. Template Presets Card -->
                    <div class="card bg-white border shadow-xs mb-4 section-card" id="sec-gabarits">
                        <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <span class="section-step-badge">1</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Gabarit & Modèles Visuels</h6>
                                    <small class="text-muted">Choisissez l'univers visuel de départ pour votre carte.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">3 Styles</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Modern Minimal -->
                                <div class="col-md-4">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer active position-relative" data-preset="modern">
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
                                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.92rem;">Modern Minimal</h6>
                                        <p class="text-muted small mb-0" style="font-size: 0.74rem;">Épuré, avatar aux coins arrondis, police contemporaine Outfit.</p>
                                    </div>
                                </div>

                                <!-- Corporate Sleek -->
                                <div class="col-md-4">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer position-relative" data-preset="executive">
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
                                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.92rem;">Corporate Sleek</h6>
                                        <p class="text-muted small mb-0" style="font-size: 0.74rem;">Fond sombre contrasté, finitions sobres et typographie Inter.</p>
                                    </div>
                                </div>

                                <!-- Creative Bold -->
                                <div class="col-md-4">
                                    <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer position-relative" data-preset="creative">
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
                                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.92rem;">Creative Bold</h6>
                                        <p class="text-muted small mb-0" style="font-size: 0.74rem;">Accents magenta vifs, halo moderne, police Space Grotesk.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Profile & Identity Information Card -->
                    <div class="card bg-white border shadow-xs mb-4 section-card" id="sec-identite">
                        <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <span class="section-step-badge">2</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Profile & Identity Details</h6>
                                    <small class="text-muted">Informations personnelles et professionnelles du titulaire.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">Essentiel</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3.5">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Nom Complet <span class="text-danger">*</span></label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="full_name" id="input_full_name" class="form-control" value="Marcus Vance" required placeholder="ex. Younes Zriket">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Intitulé / Nom de la Carte</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-tag"></i></span>
                                        <input type="text" name="title" id="input_title" class="form-control" value="Carte Digitale Direction" placeholder="ex. Carte Digitale Direction">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Poste / Fonction</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-briefcase"></i></span>
                                        <input type="text" name="job_title" id="input_job_title" class="form-control" value="Directeur Général" placeholder="ex. Directeur Général">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Entreprise / Organisation</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-building"></i></span>
                                        <input type="text" name="company" id="input_company" class="form-control" value="YNS Smart Solutions" placeholder="ex. YNS Smart Solutions">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Badge / Mention Vérifiée</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-certificate text-primary"></i></span>
                                        <input type="text" name="badge_text" id="input_badge_text" class="form-control" value="★ Profil Officiel" placeholder="ex. ★ Profil Officiel">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Rattacher à un Client (Optionnel)</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-users"></i></span>
                                        <select name="client_id" class="form-select border-start-0">
                                            <option value="">-- Aucun client spécifique (Direct) --</option>
                                            @foreach($clients as $c)
                                                <option value="{{ $c->id }}">{{ $c->company_name ?: $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <label class="form-label small fw-semibold text-dark mb-0">Biographie / Présentation Courte</label>
                                        <span class="text-muted font-mono" style="font-size: 0.72rem;">Max 180 caractères</span>
                                    </div>
                                    <textarea name="bio" id="input_bio" rows="2" class="form-control custom-textarea" placeholder="Présentez votre activité en une ou deux phrases...">Solutions connectées NFC et cartes de visite interactives de nouvelle génération.</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Media Uploads Card -->
                    <div class="card bg-white border shadow-xs mb-4 section-card" id="sec-medias">
                        <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <span class="section-step-badge">3</span>
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
                                    <label class="form-label small fw-semibold text-dark mb-2">Photo de Profil (Avatar)</label>
                                    <div class="media-upload-box p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                                        <div class="position-relative media-thumbnail-wrap">
                                            <img id="profileThumbnail" src="https://ui-avatars.com/api/?name=Marcus+Vance&background=4f46e5&color=fff&size=120" alt="Avatar" class="rounded-3 border bg-white shadow-2xs media-avatar-thumb">
                                            <div class="media-hover-overlay rounded-3">
                                                <i class="fa-solid fa-camera text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" name="profile_photo" id="profilePhotoInput" class="form-control form-control-sm mb-1 media-file-input" accept="image/*">
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">Format carré 500x500 (max 4 Mo).</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bannière de couverture -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-2">Bannière de Couverture (Header)</label>
                                    <div class="media-upload-box p-3 bg-light rounded-3 border">
                                        <div id="coverThumbnail" class="rounded-2 border mb-2 media-cover-thumb"></div>
                                        <input type="file" name="cover_photo" id="coverPhotoInput" class="form-control form-control-sm media-file-input" accept="image/*">
                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Format paysage 1200x400 (max 6 Mo).</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Styling Controls & Theme Config Card -->
                    <div class="card bg-white border shadow-xs mb-4 section-card" id="sec-style">
                        <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <span class="section-step-badge">4</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Styling Controls & Personnalisation</h6>
                                    <small class="text-muted">Ajustez les palettes, la typographie et l'arrondi des angles.</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono">Palette & Font</span>
                        </div>
                        <div class="card-body p-4">
                            <!-- Quick Designer Palettes -->
                            <div class="mb-3.5 pb-3 border-bottom">
                                <label class="form-label small fw-semibold text-dark mb-2 d-flex align-items-center gap-1.5">
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

                            <div class="row g-3.5">
                                <!-- Couleur d'accent -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Couleur Principale (Boutons & Accents)</label>
                                    <div class="input-group color-input-group">
                                        <input type="color" id="primaryColorPicker" class="form-control form-control-color border-end-0" style="width: 44px; height: 38px; border-radius: 10px 0 0 10px;" value="#4f46e5" title="Choisir la couleur">
                                        <input type="text" name="primary_color" id="primaryColorText" class="form-control font-mono" style="border-radius: 0 10px 10px 0; font-size: 0.88rem;" value="#4f46e5">
                                    </div>
                                </div>

                                <!-- Couleur d'arrière-plan -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Arrière-Plan de la Carte</label>
                                    <div class="input-group color-input-group">
                                        <input type="color" id="bgColorPicker" class="form-control form-control-color border-end-0" style="width: 44px; height: 38px; border-radius: 10px 0 0 10px;" value="#0f172a" title="Choisir le fond">
                                        <input type="text" name="background_color" id="bgColorText" class="form-control font-mono" style="border-radius: 0 10px 10px 0; font-size: 0.88rem;" value="#0f172a">
                                    </div>
                                </div>

                                <!-- Police -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Police / Typographie</label>
                                    <select name="font_family" id="fontFamilySelect" class="form-select custom-select">
                                        <option value="Outfit" selected>Outfit (Moderne & Lisible)</option>
                                        <option value="Inter">Inter (Épuré & Précis)</option>
                                        <option value="Plus Jakarta Sans">Plus Jakarta Sans (Moderne SaaS)</option>
                                        <option value="Space Grotesk">Space Grotesk (Créatif & Audacieux)</option>
                                        <option value="Playfair Display">Playfair Display (Luxe & Sérif)</option>
                                    </select>
                                </div>

                                <!-- Style d'ombre -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Style d'Ombrage</label>
                                    <select name="shadow_style" id="shadowStyleSelect" class="form-select custom-select">
                                        <option value="soft" selected>Ombre Douce (3D Réaliste)</option>
                                        <option value="deep">Ombre Profonde (Exécutive)</option>
                                        <option value="glow">Halo Néon Lumineux</option>
                                        <option value="flat">Minimal Plat (Bordure seule)</option>
                                    </select>
                                </div>

                                <!-- Rayon des coins -->
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold text-dark mb-0">Arrondi des Angles</label>
                                        <span class="badge bg-light text-primary border font-mono small" id="borderRadiusBadge">16px</span>
                                    </div>
                                    <input type="range" name="border_radius" id="borderRadiusSlider" class="form-range mt-2" min="0" max="32" step="2" value="16">
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark mb-1.5">Statut de Publication</label>
                                    <select name="status" class="form-select custom-select">
                                        <option value="active" selected>Active (En ligne & Consultable)</option>
                                        <option value="inactive">Inactive (Brouillon / Suspendue)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Interactive Links & Content Blocks Card (PRO SECTION WITH FONT AWESOME SEARCH) -->
                    <div class="card bg-white border shadow-xs mb-4 section-card" id="sec-canaux">
                        <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="section-step-badge">5</span>
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
                                <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-2xs" onclick="openFaIconPicker(null)" title="Parcourir la bibliothèque d'icônes">
                                    <i class="fa-solid fa-magnifying-glass text-primary"></i>
                                    <span class="small font-mono">Explorer Icônes</span>
                                </button>
                                <button type="button" class="btn btn-sm text-white rounded-pill px-3.5 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-2xs btn-add-channel" id="btnAddChannelBtn" onclick="addDefaultChannel()">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    <span>Ajouter un Canal</span>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-white border rounded-pill px-2.5 py-1.5 text-muted shadow-2xs dropdown-toggle" type="button" id="btnAddChannelDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Modèles de canaux">
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
                            <!-- Dynamic Repeater Container (Generous spacing and clean cards) -->
                            <div id="blocksContainer" class="d-flex flex-column gap-3.5">
                                <!-- Injected dynamically by JS (Channel cards or empty state) -->
                            </div>

                            <!-- 1-Click Quick Add Dock with Brand Colors & Micro-Interactions -->
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
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Sticky Realistic Smartphone Simulator -->
                <div class="col-xl-5 col-lg-5" style="align-self: stretch;">
                    <div class="sticky-preview-wrapper position-sticky">
                        <!-- Simulator Header Status -->
                        <div class="d-flex justify-content-between align-items-center mb-2.5 px-2">
                            <span class="small fw-bold text-muted text-uppercase tracking-wider font-mono d-flex align-items-center gap-1.5" style="font-size: 0.72rem;">
                                <span class="status-dot-pulse"></span>
                                <i class="bi bi-phone text-primary"></i>Live Device Simulator
                            </span>
                            <span class="badge simulator-fixed-badge">
                                <i class="bi bi-pin-angle-fill me-1"></i>Fixé au Défilement
                            </span>
                        </div>

                        <!-- Realistic Smartphone Device Mockup Frame -->
                        <div class="phone-frame-mockup mx-auto shadow-2xl">
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
                                    <button type="button" class="btn preview-save-btn w-100 py-2 mb-3 rounded-pill fw-bold small shadow-sm">
                                        <i class="fa-solid fa-address-card me-1.5"></i> Enregistrer le contact (.vcf)
                                    </button>
                                </div>

                                <!-- Rendered Interactive Links & Blocks -->
                                <div class="preview-links-container px-3 pb-4" id="previewLinksContainer">
                                    <!-- Injected dynamically by JS with Font Awesome support -->
                                </div>

                                <!-- Simulated Branding Footer -->
                                <div class="preview-footer text-center pb-3 small opacity-50 font-mono" style="font-size: 0.65rem;">
                                    CONNECTED SMART CARD &bull; YNS NFC
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
                    <div class="card bg-white border shadow-xs" style="border-radius: 16px; border-color: #e2e8f0; overflow: hidden;">
                        
                        <!-- Tabs Header: Library vs Optimizer -->
                        <div class="card-header bg-white border-bottom p-2.5 d-flex align-items-center justify-content-between" style="border-color: #f1f5f9;">
                            <ul class="nav nav-pills gap-1.5" id="dndSidebarTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active px-3.5 py-1.5 rounded-pill small fw-semibold" id="tab-library-btn" data-bs-toggle="pill" data-bs-target="#tab-library" type="button" role="tab">
                                        <i class="bi bi-collection me-1.5 text-primary"></i> Bibliothèque d'Éléments
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3.5 py-1.5 rounded-pill small fw-semibold" id="tab-optimizer-btn" data-bs-toggle="pill" data-bs-target="#tab-optimizer" type="button" role="tab">
                                        <i class="bi bi-sliders me-1.5 text-warning"></i> Optimiser l'Élément
                                    </button>
                                </li>
                            </ul>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 small font-mono" id="dndBlockCountBadge">0 élément(s)</span>
                                <button type="button" class="btn btn-xs btn-white border rounded-pill px-2.5 py-1 text-dark small fw-medium shadow-2xs" onclick="addNewBlock()" title="Ajouter un bloc vierge">
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
                                    <div class="position-relative mb-2.5">
                                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.82rem;"></i>
                                        <input type="text" id="librarySearchInput" class="form-control form-control-sm" style="border-radius: 10px; border-color: #e2e8f0; padding-left: 2.3rem;" placeholder="Rechercher un widget (WhatsApp, Appel, LinkedIn, vCard...)" oninput="filterWidgetLibrary(this.value)">
                                    </div>

                                    <!-- Quick Category Filter Chips -->
                                    <div class="d-flex align-items-center gap-1.5 pb-2.5 mb-3 border-bottom overflow-x-auto" style="border-color: #f1f5f9 !important;">
                                        <button type="button" class="widget-cat-filter-btn active" onclick="filterWidgetCategory('all', this)">
                                            Tous <span class="opacity-75 font-mono ms-0.5" style="font-size: 0.68rem;">(24)</span>
                                        </button>
                                        <button type="button" class="widget-cat-filter-btn" onclick="filterWidgetCategory('contact', this)">
                                            <i class="fa-solid fa-bolt text-warning me-1"></i>Contact
                                        </button>
                                        <button type="button" class="widget-cat-filter-btn" onclick="filterWidgetCategory('social', this)">
                                            <i class="fa-solid fa-share-nodes text-primary me-1"></i>Réseaux
                                        </button>
                                        <button type="button" class="widget-cat-filter-btn" onclick="filterWidgetCategory('business', this)">
                                            <i class="fa-solid fa-briefcase text-info me-1"></i>Affaires
                                        </button>
                                        <button type="button" class="widget-cat-filter-btn" onclick="filterWidgetCategory('tools', this)">
                                            <i class="fa-solid fa-screwdriver-wrench text-secondary me-1"></i>Outils
                                        </button>
                                    </div>

                                    <div class="d-flex flex-column gap-3.5" style="max-height: 600px; overflow-y: auto; padding-right: 4px;">
                                        
                                        <!-- Category: Actions Rapides de Contact -->
                                        <div class="widget-category-group" data-group="contact">
                                            <div class="small fw-bold text-muted text-uppercase font-mono mb-2 d-flex align-items-center justify-content-between" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                                <span><i class="fa-solid fa-bolt text-warning me-1"></i> Actions Rapides de Contact</span>
                                                <span class="badge bg-light text-muted border rounded-pill font-mono" style="font-size: 0.65rem;">6 widgets</span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="contact" data-type="contact" data-icon="fa-solid fa-phone" data-label="Appel Direct" data-placeholder="+212600112233">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #16a34a;">
                                                                <i class="fa-solid fa-phone"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Téléphone</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Appel direct GSM</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="contact" data-type="contact" data-icon="fa-brands fa-whatsapp" data-label="WhatsApp Chat" data-placeholder="+212600112233">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #25d366;">
                                                                <i class="fa-brands fa-whatsapp"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">WhatsApp</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Chat direct 1-clic</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="contact" data-type="contact" data-icon="fa-solid fa-envelope" data-label="Email Direct" data-placeholder="contact@entreprise.com">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #ef4444;">
                                                                <i class="fa-solid fa-envelope"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Email</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Courrier pro direct</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="contact" data-type="vcard" data-icon="fa-solid fa-address-card" data-label="Enregistrer Contact" data-placeholder="Télécharger vCard">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #6320ee;">
                                                                <i class="fa-solid fa-address-card"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Fiche vCard</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Fichier .vcf complet</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="contact" data-type="contact" data-icon="fa-solid fa-comment-sms" data-label="SMS Direct" data-placeholder="+212600112233">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #0891b2;">
                                                                <i class="fa-solid fa-comment-sms"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">SMS Direct</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Message texte mobile</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="contact" data-type="contact" data-icon="fa-brands fa-telegram" data-label="Telegram" data-placeholder="https://t.me/username">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #0088cc;">
                                                                <i class="fa-brands fa-telegram"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Telegram</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Canal ou chat privé</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category: Réseaux Sociaux Professionnels -->
                                        <div class="widget-category-group" data-group="social">
                                            <div class="small fw-bold text-muted text-uppercase font-mono mb-2 d-flex align-items-center justify-content-between" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                                <span><i class="fa-solid fa-share-nodes text-primary me-1"></i> Réseaux Sociaux & Visibilité</span>
                                                <span class="badge bg-light text-muted border rounded-pill font-mono" style="font-size: 0.65rem;">6 widgets</span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="social" data-type="social" data-icon="fa-brands fa-linkedin" data-label="Profil LinkedIn" data-placeholder="https://linkedin.com/in/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #0a66c2;">
                                                                <i class="fa-brands fa-linkedin"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">LinkedIn</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Réseau professionnel</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="social" data-type="social" data-icon="fa-brands fa-instagram" data-label="Instagram" data-placeholder="https://instagram.com/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);">
                                                                <i class="fa-brands fa-instagram"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Instagram</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Feed & stories photo</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="social" data-type="social" data-icon="fa-brands fa-x-twitter" data-label="Twitter / X" data-placeholder="https://x.com/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #0f172a;">
                                                                <i class="fa-brands fa-x-twitter"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Twitter / X</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Microblogging & veille</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="social" data-type="social" data-icon="fa-brands fa-youtube" data-label="Chaîne YouTube" data-placeholder="https://youtube.com/@...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #ff0000;">
                                                                <i class="fa-brands fa-youtube"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">YouTube</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Vidéos & présentations</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="social" data-type="social" data-icon="fa-brands fa-tiktok" data-label="TikTok" data-placeholder="https://tiktok.com/@...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #000000;">
                                                                <i class="fa-brands fa-tiktok"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">TikTok</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Contenus vidéo courts</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="social" data-type="social" data-icon="fa-brands fa-facebook" data-label="Facebook" data-placeholder="https://facebook.com/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #1877f2;">
                                                                <i class="fa-brands fa-facebook"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Facebook</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Page ou groupe officiel</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category: Liens d'Affaires & Médias -->
                                        <div class="widget-category-group" data-group="business">
                                            <div class="small fw-bold text-muted text-uppercase font-mono mb-2 d-flex align-items-center justify-content-between" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                                <span><i class="fa-solid fa-briefcase text-info me-1"></i> Contenus d'Affaires & Médias</span>
                                                <span class="badge bg-light text-muted border rounded-pill font-mono" style="font-size: 0.65rem;">6 widgets</span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="business" data-type="custom_link" data-icon="fa-solid fa-globe" data-label="Site Web Officiel" data-placeholder="https://monsite.com">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #0284c7;">
                                                                <i class="fa-solid fa-globe"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Site Web</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Portail officiel de l'entreprise</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="business" data-type="contact" data-icon="fa-solid fa-location-dot" data-label="Localisation Google Maps" data-placeholder="Casablanca Marina">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #f59e0b;">
                                                                <i class="fa-solid fa-location-dot"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Google Maps</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Itinéraire GPS & bureaux</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="business" data-type="custom_link" data-icon="fa-solid fa-file-pdf" data-label="Brochure / Catalogue PDF" data-placeholder="https://monsite.com/catalogue.pdf">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #dc2626;">
                                                                <i class="fa-solid fa-file-pdf"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Brochure PDF</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Document commercial</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="business" data-type="custom_link" data-icon="fa-solid fa-qrcode" data-label="Code QR de Partage" data-placeholder="Scanner pour partager">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #334155;">
                                                                <i class="fa-solid fa-qrcode"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">QR Partage</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Partage flash immédiat</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="business" data-type="custom_link" data-icon="fa-solid fa-briefcase" data-label="Portfolio Projets" data-placeholder="https://monsite.com/portfolio">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #059669;">
                                                                <i class="fa-solid fa-briefcase"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Portfolio</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Réalisations & études de cas</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="business" data-type="custom_link" data-icon="fa-solid fa-utensils" data-label="Menu / Grille Tarifaire" data-placeholder="https://monsite.com/menu">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #ea580c;">
                                                                <i class="fa-solid fa-utensils"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Menu / Tarifs</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Carte digitale ou prix</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category: Outils & Prise de Contact Pro -->
                                        <div class="widget-category-group" data-group="tools">
                                            <div class="small fw-bold text-muted text-uppercase font-mono mb-2 d-flex align-items-center justify-content-between" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                                <span><i class="fa-solid fa-screwdriver-wrench text-secondary me-1"></i> Outils & Prise de Contact Pro</span>
                                                <span class="badge bg-light text-muted border rounded-pill font-mono" style="font-size: 0.65rem;">6 widgets</span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="tools" data-type="custom_link" data-icon="fa-solid fa-calendar-check" data-label="Prendre Rendez-vous" data-placeholder="https://calendly.com/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #2563eb;">
                                                                <i class="fa-solid fa-calendar-check"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Prise de RDV</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Calendly / Agenda direct</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="tools" data-type="custom_link" data-icon="fa-solid fa-star" data-label="Avis Clients Google" data-placeholder="https://g.page/r/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #d97706;">
                                                                <i class="fa-solid fa-star"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Avis Google</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Notation & avis clients</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="tools" data-type="custom_link" data-icon="fa-brands fa-spotify" data-label="Écouter Podcast" data-placeholder="https://open.spotify.com/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #1db954;">
                                                                <i class="fa-brands fa-spotify"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Spotify Podcast</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Épisode ou playlist</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="tools" data-type="custom_link" data-icon="fa-brands fa-github" data-label="GitHub / Code" data-placeholder="https://github.com/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #1e293b;">
                                                                <i class="fa-brands fa-github"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">GitHub</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Dépôts & projets dev</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="tools" data-type="custom_link" data-icon="fa-solid fa-credit-card" data-label="Lien de Paiement" data-placeholder="https://payment.link/...">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #7c3aed;">
                                                                <i class="fa-solid fa-credit-card"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Paiement / RIB</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Règlement ou virement</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="draggable-widget-card p-2.5 d-flex align-items-center justify-content-between cursor-grab" draggable="true" data-category="tools" data-type="custom_link" data-icon="fa-solid fa-wifi" data-label="Accès Wi-Fi Direct" data-placeholder="WIFI:S:Guest;T:WPA;P:guest123;;">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <div class="widget-icon-avatar" style="background: #4f46e5;">
                                                                <i class="fa-solid fa-wifi"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="small fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Accès Wi-Fi</div>
                                                                <div class="text-muted text-truncate" style="font-size: 0.67rem;">Connexion instantanée</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <button type="button" class="widget-add-btn" onclick="quickAddWidgetFromCard(this.closest('.draggable-widget-card'))" title="Ajouter au smartphone"><i class="fa-solid fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Tab 2: Element Optimizer Panel -->
                                <div class="tab-pane fade" id="tab-optimizer" role="tabpanel">
                                    <div id="optimizerEmptyState" class="text-center py-5 text-muted">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-3" style="width: 60px; height: 60px;">
                                            <i class="fa-solid fa-arrow-pointer fs-3 text-secondary opacity-50"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Aucun élément sélectionné</h6>
                                        <p class="small text-muted mb-3 max-w-sm mx-auto" style="max-width: 320px;">Cliquez sur un bloc sur l'écran du smartphone ou choisissez ci-dessous pour ajuster son titre, son lien et son icône.</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-2xs" onclick="selectFirstBlockForOptimization()">
                                                <i class="fa-solid fa-crosshairs me-1.5"></i>Sélectionner un bloc
                                            </button>
                                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-3 shadow-2xs" onclick="switchToLibraryTab()">
                                                <i class="fa-solid fa-plus me-1.5"></i>Ajouter un élément
                                            </button>
                                        </div>
                                    </div>

                                    <div id="optimizerFormWrap" style="display: none;">
                                        <!-- Header of active block -->
                                        <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom" style="border-color: #f1f5f9 !important;">
                                            <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary-subtle text-primary flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.15rem;" id="optHeaderIconWrap">
                                                    <i class="fa-solid fa-gear" id="optHeaderIcon"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h6 class="fw-bold mb-0 text-dark text-truncate" id="optHeaderTitle" style="font-size: 0.95rem;">Modifier le bloc</h6>
                                                    <span class="badge bg-light text-muted border font-mono rounded-pill" style="font-size: 0.65rem;" id="optHeaderType">Bloc interactif</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                                <button type="button" class="btn btn-sm btn-white border px-2.5 py-1 rounded-pill small fw-semibold text-dark shadow-2xs" onclick="duplicateActiveOptimizedBlock()" title="Créer une copie de ce bloc">
                                                    <i class="fa-solid fa-copy me-1 text-primary"></i>Dupliquer
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-pill small fw-semibold" onclick="deleteActiveOptimizedBlock()" title="Supprimer ce bloc">
                                                    <i class="fa-solid fa-trash me-1"></i>Supprimer
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <!-- Intitulé -->
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-dark mb-1">Intitulé / Libellé Affiché</label>
                                                <input type="text" id="optInputLabel" class="form-control" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem;" oninput="applyActiveBlockOptimization()">
                                            </div>

                                            <!-- Destination URL / Number with protocol chips -->
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="form-label small fw-semibold text-dark mb-0">Numéro ou URL de Destination</label>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="opt-prefix-chip" onclick="setOptimizerUrlPrefix('https://')">https://</span>
                                                        <span class="opt-prefix-chip" onclick="setOptimizerUrlPrefix('tel:')">tel:</span>
                                                        <span class="opt-prefix-chip" onclick="setOptimizerUrlPrefix('mailto:')">mailto:</span>
                                                        <span class="opt-prefix-chip" onclick="setOptimizerUrlPrefix('https://wa.me/')">wa.me</span>
                                                    </div>
                                                </div>
                                                <input type="text" id="optInputUrl" class="form-control font-mono" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.86rem;" oninput="applyActiveBlockOptimization()" placeholder="ex: +212600112233 ou https://...">
                                            </div>

                                            <!-- Font Awesome Icon Picker & Quick Icons -->
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center mb-1.5">
                                                    <label class="form-label small fw-semibold text-dark mb-0">Icône du Bloc</label>
                                                    <button type="button" class="btn btn-sm btn-link p-0 text-primary small text-decoration-none fw-semibold font-mono" onclick="openFaIconPicker(activeOptimizedBlockId)">
                                                        <i class="fa-solid fa-icons me-1"></i>Parcourir 2,163 icônes Font Awesome
                                                    </button>
                                                </div>

                                                <div class="input-group mb-2">
                                                    <span class="input-group-text bg-light text-primary border-end-0" id="optSelectedIconBox" style="width: 44px; justify-content: center; font-size: 1.05rem;">
                                                        <i class="fa-solid fa-link" id="optPreviewIconTag"></i>
                                                    </span>
                                                    <input type="text" id="optInputIconManual" class="form-control font-mono small" style="border-radius: 0 10px 10px 0; font-size: 0.82rem;" placeholder="ex: fa-brands fa-whatsapp" oninput="applyManualIconOptimization(this.value)">
                                                </div>

                                                <!-- Quick Recommended Icon Chips -->
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                    <span class="small text-muted font-mono" style="font-size: 0.68rem;">Recommandés :</span>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-phone')" title="Téléphone"><i class="fa-solid fa-phone text-success"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-brands fa-whatsapp')" title="WhatsApp"><i class="fa-brands fa-whatsapp text-success"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-envelope')" title="Email"><i class="fa-solid fa-envelope text-danger"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-globe')" title="Site Web"><i class="fa-solid fa-globe text-primary"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-brands fa-linkedin')" title="LinkedIn"><i class="fa-brands fa-linkedin text-info"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-brands fa-instagram')" title="Instagram"><i class="fa-brands fa-instagram text-danger"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-location-dot')" title="Maps"><i class="fa-solid fa-location-dot text-warning"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-calendar-check')" title="Rendez-vous"><i class="fa-solid fa-calendar-check text-primary"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-file-pdf')" title="PDF"><i class="fa-solid fa-file-pdf text-danger"></i></button>
                                                    <button type="button" class="opt-quick-icon-btn" onclick="applyManualIconOptimization('fa-solid fa-star')" title="Avis"><i class="fa-solid fa-star text-warning"></i></button>
                                                </div>
                                            </div>

                                            <!-- Reordering Controls -->
                                            <div class="col-12 d-flex justify-content-between pt-2 border-top" style="border-color: #f1f5f9 !important;">
                                                <button type="button" class="btn btn-sm btn-white border px-3 rounded-pill shadow-2xs" onclick="moveActiveBlock(-1)">
                                                    <i class="fa-solid fa-arrow-up me-1.5 text-muted"></i>Monter d'un rang
                                                </button>
                                                <button type="button" class="btn btn-sm btn-white border px-3 rounded-pill shadow-2xs" onclick="moveActiveBlock(1)">
                                                    <i class="fa-solid fa-arrow-down me-1.5 text-muted"></i>Descendre d'un rang
                                                </button>
                                            </div>

                                            <!-- Mini-Dock of placed blocks for fast switching -->
                                            <div class="col-12 pt-2 border-top" style="border-color: #f1f5f9 !important;">
                                                <div class="small fw-semibold text-muted text-uppercase font-mono mb-2" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                                                    <i class="bi bi-stack me-1"></i> Tous les blocs de la carte :
                                                </div>
                                                <div id="optimizerBlocksDock" class="d-flex flex-wrap gap-1.5">
                                                    <!-- Injected dynamically by JS -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Drag & Drop Smartphone Canvas -->
                <div class="col-xl-6 col-lg-6" style="align-self: stretch;">
                    <div class="sticky-preview-wrapper position-sticky">
                        <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                            <span class="small fw-bold text-muted text-uppercase tracking-wider font-mono d-flex align-items-center gap-1.5" style="font-size: 0.72rem;">
                                <span class="status-dot-pulse"></span>
                                <i class="bi bi-phone text-primary"></i>Écran Smartphone (Zone de Dépôt)
                            </span>
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="badge simulator-fixed-badge">
                                    <i class="bi bi-pin-angle-fill me-1"></i>Fixé au Défilement
                                </span>
                            </div>
                        </div>

                        <!-- Realistic Smartphone Mockup with Canvas Drop Area -->
                        <div class="phone-frame-mockup mx-auto shadow-2xl position-relative">
                            <!-- Stainless Steel Edge Reflection Highlights -->
                            <div class="phone-metal-glare"></div>

                            <!-- Dynamic Island Notch -->
                            <div class="phone-dynamic-island">
                                <div class="island-camera"></div>
                                <div class="island-speaker"></div>
                            </div>

                            <!-- Phone Screen -->
                            <div class="phone-screen-content" id="dndScreenPreview">
                                <!-- Status Bar -->
                                <div class="phone-status-bar d-flex justify-content-between align-items-center px-4 pt-2">
                                    <span class="font-mono small fw-bold" style="font-size: 0.72rem;">09:41</span>
                                    <div class="d-flex align-items-center gap-1.5 small">
                                        <i class="bi bi-reception-4" style="font-size: 0.72rem;"></i>
                                        <i class="bi bi-wifi" style="font-size: 0.72rem;"></i>
                                        <i class="bi bi-battery-full" style="font-size: 0.85rem;"></i>
                                    </div>
                                </div>

                                <!-- Banner -->
                                <div class="preview-cover-banner" id="dndCoverBanner">
                                    <div class="cover-gradient-overlay"></div>
                                </div>

                                <!-- Profile Header -->
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

                                    <!-- "Save Contact" vCard Button Simulator -->
                                    <button type="button" class="btn preview-save-btn w-100 py-2 mb-3 rounded-pill fw-bold small shadow-sm">
                                        <i class="fa-solid fa-address-card me-1.5"></i> Enregistrer le contact (.vcf)
                                    </button>
                                </div>

                                <!-- INTERACTIVE DROP ZONE FOR BLOCKS -->
                                <div class="px-3 pt-1 pb-2 dnd-drop-zone" id="dndDropZone">
                                    <!-- Dynamic canvas drop elements populated by JS -->
                                </div>

                                <!-- Drop Zone Placeholder Indicator -->
                                <div id="dndEmptyPlaceholder" class="mx-3 p-3.5 rounded-3 text-center border border-dashed mb-3" style="border-color: rgba(99, 32, 238, 0.4); background: rgba(99, 32, 238, 0.08); color: rgba(255,255,255,0.75);">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 44px; height: 44px; background: rgba(99, 32, 238, 0.25); color: #a78bfa;">
                                        <i class="fa-solid fa-circle-down fs-4"></i>
                                    </div>
                                    <div class="small fw-bold text-white mb-0.5">Glissez vos widgets ici</div>
                                    <div style="font-size: 0.7rem; opacity: 0.85;">Déposez depuis la bibliothèque à gauche ou cliquez sur le bouton +</div>
                                </div>

                                <!-- Footer -->
                                <div class="preview-footer text-center pb-3 small opacity-50 font-mono" style="font-size: 0.65rem;">
                                    CONNECTED SMART CARD &bull; YNS NFC
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
                <!-- Search bar -->
                <div class="position-relative mb-3">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="faIconSearchInput" class="form-control" style="padding-left: 2.5rem; padding-right: 2.5rem; border-radius: 12px; height: 44px; border-color: #e2e8f0; font-size: 0.9rem;" placeholder="Rechercher parmi les 2,163 icônes (ex: whatsapp, phone, email, pdf, cart, user, heart...)" oninput="filterFaIcons(this.value)">
                    <button type="button" class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y me-2 text-muted" id="faClearSearchBtn" onclick="clearFaSearch()" style="display: none;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Category filter tabs -->
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

                <!-- Icons Grid -->
                <div class="fa-icon-grid" id="faIconGrid">
                    <!-- Populated dynamically by JavaScript -->
                </div>

                <!-- Empty State -->
                <div id="faNoIconsFound" class="text-center py-5 text-muted" style="display: none;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light border mb-2.5" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-magnifying-glass fs-4 text-secondary opacity-50"></i>
                    </div>
                    <p class="mb-1 fw-bold text-dark">Aucune icône trouvée</p>
                    <small class="text-muted">Essayez un autre mot-clé (ex: phone, chat, web, card, file...)</small>
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
@endsection

@push('styles')
<style>
/* -------------------------------------------------------------------------- */
/* Modern Design System, Layout Alignment & Micro-Aesthetics                 */
/* -------------------------------------------------------------------------- */
.card-builder-container {
    max-width: 1440px;
    margin: 0 auto;
}

.cursor-pointer { cursor: pointer; }
.cursor-grab { cursor: grab; }
.cursor-grab:active { cursor: grabbing; }
.shadow-2xs { box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
.shadow-xs { box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03); }
.shadow-2xl { box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35); }

/* Command Bar */
.top-command-bar {
    background: #ffffff;
    border: 1px solid #eef2f6 !important;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05) !important;
}
.command-bar-icon-wrap {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);
    box-shadow: 0 4px 12px rgba(99, 32, 238, 0.25);
}
.page-headline {
    font-size: 1.55rem;
    letter-spacing: -0.02em;
}
.version-pill {
    background: #6320ee;
    font-size: 0.68rem;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(99, 32, 238, 0.2);
}
.btn-publish-card {
    background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);
    font-size: 0.88rem;
    box-shadow: 0 4px 14px rgba(99, 32, 238, 0.25);
    transition: all 0.2s ease;
}
.btn-publish-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(99, 32, 238, 0.35);
}
.btn-cancel {
    background: #ffffff;
    border-color: #e2e8f0;
    font-size: 0.88rem;
    transition: all 0.18s ease;
}
.btn-cancel:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

/* Mode Switcher Pill Styles */
.mode-toggle-group {
    background-color: #f1f5f9;
}
.mode-tab-btn {
    border: none !important;
    color: #64748b;
    transition: all 0.2s ease;
}
.mode-tab-btn.active {
    background: #ffffff !important;
    color: #0f172a !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
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
    background: rgba(248, 250, 252, 0.85);
    backdrop-filter: blur(8px);
    padding: 6px 0;
    border-radius: 12px;
}
.quick-nav-pill {
    display: inline-flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.45rem 1rem;
    border-radius: 9999px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.18s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.quick-nav-pill:hover {
    border-color: #6320ee;
    color: #6320ee;
    background: #fbfbfe;
    transform: translateY(-1px);
}

/* Section Cards & Elevation */
.section-card {
    border-radius: 16px !important;
    border: 1px solid #eef2f6 !important;
    box-shadow: 0 2px 12px -2px rgba(15, 23, 42, 0.04), 0 1px 3px rgba(15, 23, 42, 0.02) !important;
    transition: box-shadow 0.2s ease;
}
.section-card:hover {
    box-shadow: 0 6px 20px -3px rgba(15, 23, 42, 0.06), 0 1px 4px rgba(15, 23, 42, 0.03) !important;
}
.section-step-badge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #ede9fe;
    color: #6320ee;
    font-weight: 700;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(99, 32, 238, 0.15);
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

/* Custom Form Controls */
.custom-input-group .input-group-text {
    border-color: #e2e8f0;
    border-radius: 10px 0 0 10px;
    font-size: 0.88rem;
    color: #64748b;
    background-color: #f8fafc;
}
.custom-input-group .form-control,
.custom-input-group .form-select {
    border-color: #e2e8f0;
    border-radius: 0 10px 10px 0;
    font-size: 0.88rem;
    padding: 0.55rem 0.85rem;
    height: 42px;
}
.custom-textarea {
    border-color: #e2e8f0;
    border-radius: 10px;
    font-size: 0.88rem;
    padding: 0.65rem 0.85rem;
}
.custom-select {
    border-radius: 10px;
    border-color: #e2e8f0;
    font-size: 0.88rem;
    padding: 0.55rem 0.85rem;
    height: 42px;
}
.color-input-group .form-control {
    border-color: #e2e8f0;
}
.form-control:focus, .form-select:focus {
    border-color: #6320ee !important;
    box-shadow: 0 0 0 3px rgba(99, 32, 238, 0.12) !important;
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
.channel-input {
    height: 42px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
    padding: 0.55rem 0.95rem;
    font-size: 0.88rem;
    color: #0f172a;
    transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
}
.channel-input:focus {
    background-color: #ffffff;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
    outline: none;
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

/* ==========================================================================
   VISUAL DRAG & DROP STUDIO (OPTION 2) STYLING
   ========================================================================== */

/* Category Filter Chips */
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

/* Draggable Widget Cards in Library */
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
    background: #ffffff !important;
}
.draggable-widget-card:active {
    cursor: grabbing;
}
.draggable-widget-card.is-dragging {
    opacity: 0.45;
    border-style: dashed !important;
    border-color: #6320ee !important;
    transform: scale(0.98);
}

/* Widget Icon Badges */
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

/* Widget Quick-Add Button */
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

/* Drop Zone Container & Drag Over */
.dnd-drop-zone {
    flex-shrink: 0;
    width: 100%;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.dnd-drop-zone.dnd-drag-over {
    background: rgba(99, 32, 238, 0.12) !important;
    box-shadow: inset 0 0 0 1.5px rgba(99, 32, 238, 0.65), 0 0 24px rgba(99, 32, 238, 0.2) !important;
    border-radius: 16px;
}

/* Insertion Line Indicators (Zero Layout Shift) */
.canvas-block-item.drop-target-before::before {
    content: '';
    position: absolute;
    top: -6px;
    left: 4px;
    right: 4px;
    height: 3px;
    background: linear-gradient(90deg, #6320ee 0%, #a78bfa 100%);
    border-radius: 3px;
    box-shadow: 0 0 10px rgba(99, 32, 238, 0.95);
    z-index: 30;
    pointer-events: none;
    animation: pulseDropLine 1.4s infinite;
}
.canvas-block-item.drop-target-after::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 4px;
    right: 4px;
    height: 3px;
    background: linear-gradient(90deg, #6320ee 0%, #a78bfa 100%);
    border-radius: 3px;
    box-shadow: 0 0 10px rgba(99, 32, 238, 0.95);
    z-index: 30;
    pointer-events: none;
    animation: pulseDropLine 1.4s infinite;
}
@keyframes pulseDropLine {
    0%, 100% { opacity: 0.95; transform: scaleY(1); }
    50% { opacity: 0.6; transform: scaleY(1.4); }
}

/* Canvas Dropped Block Styling - Authentic Mobile Link Card */
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
    transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.18s ease,
                background 0.18s ease,
                box-shadow 0.18s ease;
    position: relative;
    user-select: none;
}
.canvas-block-item:hover {
    border-color: rgba(255, 255, 255, 0.28);
    background: rgba(255, 255, 255, 0.13);
    transform: translateY(-1.5px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
}
.canvas-block-item.active-selected {
    border-color: var(--card-primary, #6320ee) !important;
    box-shadow: 0 0 0 2px var(--card-primary, #6320ee), 0 8px 24px rgba(0, 0, 0, 0.35) !important;
    background: rgba(99, 32, 238, 0.22) !important;
}
.canvas-block-item.is-canvas-dragging {
    opacity: 0.35;
    transform: scale(0.97);
    border-style: dashed !important;
}

/* Drag Handle */
.canvas-block-item .drag-handle-btn {
    cursor: grab;
    opacity: 0.35;
    padding: 2px 3px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.7);
    transition: opacity 0.15s ease, color 0.15s ease;
}
.canvas-block-item:hover .drag-handle-btn,
.canvas-block-item.active-selected .drag-handle-btn {
    opacity: 0.85;
    color: #ffffff;
}

/* Canvas Brand Icon Squircle */
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
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transition: transform 0.15s ease, background 0.15s ease;
}
.canvas-block-item:hover .canvas-icon-box {
    transform: scale(1.05);
    background: rgba(255, 255, 255, 0.18);
}
.canvas-block-item.active-selected .canvas-icon-box {
    background: var(--card-primary, #6320ee);
    color: #ffffff;
}

/* Canvas Block Metadata */
.canvas-block-title {
    font-size: 0.82rem;
    font-weight: 700;
    line-height: 1.25;
    color: #ffffff;
}
.canvas-block-url {
    font-size: 0.66rem;
    opacity: 0.65;
    line-height: 1.2;
    margin-top: 1px;
}

/* Default Chevron Indicator */
.canvas-default-chevron {
    font-size: 0.72rem;
    opacity: 0.35;
    transition: opacity 0.15s ease;
    padding-right: 4px;
}
.canvas-block-item:hover .canvas-default-chevron,
.canvas-block-item.active-selected .canvas-default-chevron {
    display: none;
}

/* Hover Floating Action Bar */
.canvas-hover-actions {
    display: none;
    align-items: center;
    gap: 4px;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 2px 4px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.14);
    animation: fadeInActions 0.15s ease forwards;
}
@keyframes fadeInActions {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
.canvas-block-item:hover .canvas-hover-actions,
.canvas-block-item.active-selected .canvas-hover-actions {
    display: inline-flex;
}

/* Action Buttons */
.canvas-action-btn {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.85);
    border: none;
    font-size: 0.7rem;
    transition: all 0.12s ease;
}
.canvas-action-btn:hover {
    background: rgba(255, 255, 255, 0.28);
    color: #ffffff;
    transform: scale(1.12);
}
.canvas-action-btn.btn-opt:hover {
    background: #6320ee;
    color: #ffffff;
}
.canvas-action-btn.btn-del:hover {
    background: #ef4444;
    color: #ffffff;
}

/* Optimizer Helper Chips */
.opt-prefix-chip {
    font-size: 0.72rem;
    font-family: monospace;
    padding: 2px 8px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.12s ease;
}
.opt-prefix-chip:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.opt-quick-icon-btn {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.15s ease;
}
.opt-quick-icon-btn:hover {
    border-color: #6320ee;
    color: #6320ee;
    background: #f5f3ff;
    transform: scale(1.08);
}

/* Status Indicator Dot Animation */
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

/* Simulator Badge */
.simulator-fixed-badge {
    background-color: #ede9fe;
    color: #6320ee;
    font-weight: 600;
    border-radius: 20px;
    padding: 4px 11px;
    font-size: 0.72rem;
}

/* Realistic Smartphone Device Mockup Frame */
.sticky-preview-wrapper {
    position: -webkit-sticky !important;
    position: sticky !important;
    top: 85px !important;
    z-index: 40;
    will-change: transform;
}
@media (max-height: 840px) {
    .phone-frame-mockup {
        height: 560px;
        width: 310px;
    }
    .sticky-preview-wrapper {
        top: 72px !important;
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
    let initialBlocks = @json($defaultBlocks);

    const screenEl = document.getElementById('phoneScreenPreview');
    const dndScreenEl = document.getElementById('dndScreenPreview');
    const nameInput = document.getElementById('input_full_name');
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
    let activeOptimizedBlockId = null;
    let activePickerTargetBlockId = null;
    let selectedFaIconClass = 'fa-solid fa-link';

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

    // Helper: Formats an icon tag string for both FA and Bootstrap Icons
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
    // 3. Font Awesome Icon Picker Modal Operations (2,163 Icons with Virtual Batching)
    // -------------------------------------------------------------------------
    let currentFaFilterCat = 'all';
    let currentFaSearchTerm = '';
    let currentMatchedIcons = [];
    let faBatchRenderIndex = 0;
    const FA_BATCH_SIZE = 96;

    window.openFaIconPicker = function(blockId) {
        activePickerTargetBlockId = blockId;
        const modalEl = document.getElementById('faIconPickerModal');
        if (!modalEl) return;

        // Determine currently active icon
        if (blockId) {
            const block = currentBlocks.find(b => b.id === blockId);
            if (block && block.icon) {
                selectedFaIconClass = block.icon;
            }
        }
        updateSelectedBadge(selectedFaIconClass);

        // Reset search & render icons
        const searchInput = document.getElementById('faIconSearchInput');
        if (searchInput) {
            searchInput.value = '';
            currentFaSearchTerm = '';
            const clearBtn = document.getElementById('faClearSearchBtn');
            if (clearBtn) clearBtn.style.display = 'none';
        }
        selectFaCategory('all');

        // Show Bootstrap Modal
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

        if (countSpan) countSpan.textContent = currentMatchedIcons.length.toLocaleString();
        if (totalCountBadge) totalCountBadge.textContent = `${faIconCatalog.length.toLocaleString()} icônes Free`;

        if (currentMatchedIcons.length === 0) {
            if (emptyState) emptyState.style.display = 'block';
        } else {
            if (emptyState) emptyState.style.display = 'none';
            renderNextFaBatch();
        }
    }

    function renderNextFaBatch() {
        const grid = document.getElementById('faIconGrid');
        if (!grid || faBatchRenderIndex >= currentMatchedIcons.length) return;

        const nextBatch = currentMatchedIcons.slice(faBatchRenderIndex, faBatchRenderIndex + FA_BATCH_SIZE);
        faBatchRenderIndex += nextBatch.length;

        const fragment = document.createDocumentFragment();
        nextBatch.forEach(item => {
            const card = document.createElement('div');
            const isSelected = selectedFaIconClass === item.class || selectedFaIconClass === item.name;
            card.className = 'fa-icon-card' + (isSelected ? ' is-selected' : '');
            card.innerHTML = `
                <i class="${item.class}"></i>
                <span class="icon-label" title="${item.label}">${item.label}</span>
            `;

            card.addEventListener('click', function () {
                grid.querySelectorAll('.fa-icon-card.is-selected').forEach(c => c.classList.remove('is-selected'));
                card.classList.add('is-selected');
                selectedFaIconClass = item.class;
                updateSelectedBadge(item.class);
            });

            card.addEventListener('dblclick', function () {
                selectedFaIconClass = item.class;
                applyFaIconSelection();
            });

            fragment.appendChild(card);
        });
        grid.appendChild(fragment);
    }

    // Attach Infinite Scroll Listener to FaIconGrid
    const iconGridEl = document.getElementById('faIconGrid');
    if (iconGridEl) {
        iconGridEl.addEventListener('scroll', function() {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight - 160) {
                renderNextFaBatch();
            }
        });
    }

    function updateSelectedBadge(iconClass) {
        const prev = document.getElementById('faSelectedIconPreview');
        const text = document.getElementById('faSelectedIconClass');
        if (prev) prev.className = iconClass;
        if (text) text.textContent = iconClass;
    }

    function applyFaIconSelection() {
        if (activePickerTargetBlockId) {
            const block = currentBlocks.find(b => b.id === activePickerTargetBlockId);
            if (block) {
                block.icon = selectedFaIconClass;
                renderAllBlockViews();
            }
        }

        // Also sync optimizer manual input & header icon if opened
        const optManual = document.getElementById('optInputIconManual');
        const optPreview = document.getElementById('optPreviewIconTag');
        const optHIcon = document.getElementById('optHeaderIcon');
        if (optManual) optManual.value = selectedFaIconClass;
        if (optPreview) optPreview.className = selectedFaIconClass;
        if (optHIcon) optHIcon.className = selectedFaIconClass;

        // Close Modal
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

    const applyBtn = document.getElementById('faApplyIconBtn');
    if (applyBtn) {
        applyBtn.addEventListener('click', applyFaIconSelection);
    }

    // -------------------------------------------------------------------------
    // 4. Mode Switching (Option 1: Classic vs Option 2: Drag & Drop)
    // -------------------------------------------------------------------------
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
                btnClassic.classList.add('active', 'btn-white');
                btnClassic.classList.remove('text-muted');
                const ic = btnClassic.querySelector('i');
                if (ic) ic.classList.add('text-primary');
                btnDnd.classList.remove('active', 'btn-white');
                btnDnd.classList.add('text-muted');
                const icDnd = btnDnd.querySelector('i');
                if (icDnd) icDnd.classList.remove('text-primary');
            }
            if (descText) descText.textContent = "Formulaire pas-à-pas avec synchronisation instantanée du simulateur.";
        } else {
            classicPane.style.display = 'none';
            dragDropPane.style.display = 'block';
            if (btnClassic && btnDnd) {
                btnDnd.classList.add('active', 'btn-white');
                btnDnd.classList.remove('text-muted');
                const icDnd = btnDnd.querySelector('i');
                if (icDnd) icDnd.classList.add('text-primary');
                btnClassic.classList.remove('active', 'btn-white');
                btnClassic.classList.add('text-muted');
                const ic = btnClassic.querySelector('i');
                if (ic) ic.classList.remove('text-primary');
            }
            if (descText) descText.textContent = "Mode interactif : glissez et déposez des widgets directement sur le smartphone.";
        }
        renderAllBlockViews();
    }

    window.switchBuilderMode = doSwitchBuilderMode;

    const btnClassicEl = document.getElementById('btnModeClassic');
    const btnDndEl = document.getElementById('btnModeDragDrop');
    if (btnClassicEl) btnClassicEl.addEventListener('click', () => doSwitchBuilderMode('classic'));
    if (btnDndEl) btnDndEl.addEventListener('click', () => doSwitchBuilderMode('dragdrop'));

    // -------------------------------------------------------------------------
    // 5. Real-Time Style & Text Synchronization
    // -------------------------------------------------------------------------
    function syncLivePreviewStyles() {
        const primary = primaryColorPicker.value;
        const bg = bgColorPicker.value;
        const font = fontSelect.value;
        const radius = radiusSlider.value + 'px';
        const shadowStyle = shadowSelect.value;

        let shadowCss = '0 10px 25px -5px rgba(0, 0, 0, 0.25)';
        if (shadowStyle === 'deep') shadowCss = '0 25px 50px -12px rgba(0, 0, 0, 0.5)';
        if (shadowStyle === 'glow') shadowCss = `0 0 20px ${primary}55`;
        if (shadowStyle === 'flat') shadowCss = 'none';

        // Apply styles to both phone simulators
        [screenEl, dndScreenEl].forEach(screen => {
            if (!screen) return;
            screen.style.setProperty('--card-primary', primary);
            screen.style.setProperty('--card-bg', bg);
            screen.style.setProperty('--card-font', `'${font}', sans-serif`);
            screen.style.setProperty('--card-radius', radius);
            screen.style.setProperty('--card-shadow', shadowCss);
        });

        radiusBadge.textContent = radius;

        // Serialize theme_config into hidden field for backend
        const themeConfig = {
            primary_color: primary,
            background_color: bg,
            font_family: font,
            border_radius: radius,
            shadow_style: shadowStyle,
            element_spacing: '12px'
        };
        themeJsonInput.value = JSON.stringify(themeConfig);
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

        // If no uploaded avatar, update UI-avatars dynamically
        if (!profilePhotoInput.files || profilePhotoInput.files.length === 0) {
            const cleanBg = primaryColorPicker.value.replace('#', '');
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(nameVal)}&background=${cleanBg}&color=fff&size=160`;
            previewAvatar.src = avatarUrl;
            if (dndAvatar) dndAvatar.src = avatarUrl;
        }
    }

    // -------------------------------------------------------------------------
    // 6. Quick Palette Helper
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

    // -------------------------------------------------------------------------
    // 7. Media Upload Instant Client-Side Previews
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
    // 8. Two-Way Color Pickers & Preset Switching
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
        inp.addEventListener('input', syncLivePreviewText);
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
    // 9. Universal Block Rendering (PRO REPEATER + FONT AWESOME INTEGRATION)
    // -------------------------------------------------------------------------
    function renderAllBlockViews() {
        renderClassicRepeater();
        renderClassicPreview();
        renderDndCanvasBlocks();
        renderOptimizerBlocksDock();
        syncSerializedData();
        updateBlockCounter();
    }

    // A. Render Classic Form Mode Repeater (With interactive FA Icon Picker)
    function renderClassicRepeater() {
        blocksContainer.innerHTML = '';

        // Update count badge in Section 5 header
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
                <!-- Top Row: Grip, Order, Icon Trigger, Title & Action Toolbar -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                        <span class="channel-drag-handle" title="Glisser pour réorganiser"><i class="fa-solid fa-grip-vertical"></i></span>
                        <span class="channel-order-badge">0${index + 1}</span>
                        
                        <!-- Interactive Icon Trigger Box -->
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

                <!-- Subtle Clean Divider -->
                <div class="channel-divider"></div>

                <!-- Clean Form Inputs Row with Pro Labels -->
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

    // B. Render Classic Phone Preview Items (With Font Awesome icons)
    function renderClassicPreview() {
        previewLinksContainer.innerHTML = '';
        currentBlocks.forEach(block => {
            if (!block.url) return;
            const a = document.createElement('a');
            a.className = 'preview-link-card';
            a.href = '#';
            a.innerHTML = `
                <div class="preview-link-icon-wrap">
                    ${renderIconElement(block.icon)}
                </div>
                <div class="flex-grow-1 min-w-0 text-start">
                    <div class="fw-semibold small text-truncate">${block.label || 'Lien'}</div>
                    <div class="small opacity-60 text-truncate font-mono" style="font-size: 0.68rem;">${block.url}</div>
                </div>
                <i class="fa-solid fa-chevron-right opacity-40 small"></i>
            `;
            previewLinksContainer.appendChild(a);
        });
    }

    // C. Render Interactive Drag & Drop Canvas Blocks
    function renderDndCanvasBlocks() {
        dndDropZone.innerHTML = '';
        if (currentBlocks.length === 0) {
            dndEmptyPlaceholder.style.display = 'block';
        } else {
            dndEmptyPlaceholder.style.display = 'none';
        }

        currentBlocks.forEach((block, index) => {
            const item = document.createElement('div');
            item.className = 'canvas-block-item' + (activeOptimizedBlockId === block.id ? ' active-selected' : '');
            item.dataset.id = block.id;
            item.draggable = true;

            item.innerHTML = `
                <div class="d-flex align-items-center gap-2.5 flex-grow-1 min-w-0">
                    <span class="drag-handle-btn" title="Glisser pour réorganiser"><i class="fa-solid fa-grip-vertical"></i></span>
                    <div class="canvas-icon-box">
                        ${renderIconElement(block.icon)}
                    </div>
                    <div class="canvas-meta-wrap min-w-0 flex-grow-1 text-start">
                        <div class="canvas-block-title text-truncate">${block.label || 'Sans libellé'}</div>
                        <div class="canvas-block-url text-truncate font-mono">${block.url || 'Non renseigné'}</div>
                    </div>
                </div>
                <div class="canvas-default-chevron">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
                <div class="canvas-hover-actions">
                    <button type="button" class="canvas-action-btn btn-opt" onclick="event.stopPropagation(); selectBlockForOptimization('${block.id}')" title="Optimiser">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="canvas-action-btn" onclick="event.stopPropagation(); duplicateBlockById('${block.id}')" title="Dupliquer">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                    <button type="button" class="canvas-action-btn btn-del" onclick="event.stopPropagation(); deleteBlockById('${block.id}')" title="Supprimer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;

            // Canvas Click to Inspect & Optimize
            item.addEventListener('click', function () {
                selectBlockForOptimization(block.id);
            });

            // Canvas Internal Drag Reorder
            item.addEventListener('dragstart', function (e) {
                e.dataTransfer.setData('text/reorder-id', block.id);
                item.classList.add('is-canvas-dragging');
            });

            item.addEventListener('dragend', function () {
                item.classList.remove('is-canvas-dragging');
                document.querySelectorAll('.canvas-block-item').forEach(el => {
                    el.classList.remove('drop-target-before', 'drop-target-after');
                });
                if (dndDropZone) dndDropZone.classList.remove('dnd-drag-over');
            });

            item.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const rect = item.getBoundingClientRect();
                const mid = rect.top + rect.height / 2;
                if (e.clientY < mid) {
                    item.classList.add('drop-target-before');
                    item.classList.remove('drop-target-after');
                } else {
                    item.classList.add('drop-target-after');
                    item.classList.remove('drop-target-before');
                }
            });

            item.addEventListener('dragleave', function () {
                item.classList.remove('drop-target-before', 'drop-target-after');
            });

            item.addEventListener('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                item.classList.remove('drop-target-before', 'drop-target-after');
                if (dndDropZone) dndDropZone.classList.remove('dnd-drag-over');
                
                // Reorder existing block
                const sourceId = e.dataTransfer.getData('text/reorder-id');
                if (sourceId && sourceId !== block.id) {
                    const fromIndex = currentBlocks.findIndex(b => b.id === sourceId);
                    if (fromIndex !== -1) {
                        const rect = item.getBoundingClientRect();
                        const mid = rect.top + rect.height / 2;
                        let targetIndex = e.clientY < mid ? index : index + 1;
                        if (fromIndex < targetIndex) targetIndex--;
                        const moved = currentBlocks.splice(fromIndex, 1)[0];
                        currentBlocks.splice(targetIndex, 0, moved);
                        renderAllBlockViews();
                        return;
                    }
                }

                // Drop from widget library onto a specific position
                let widget = draggedWidgetData;
                if (!widget) {
                    try {
                        widget = JSON.parse(e.dataTransfer.getData('text/plain'));
                    } catch(err) {}
                }
                if (widget && widget.type) {
                    const rect = item.getBoundingClientRect();
                    const mid = rect.top + rect.height / 2;
                    const insertIndex = e.clientY < mid ? index : index + 1;
                    const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
                    currentBlocks.splice(insertIndex, 0, {
                        id: newBlockId,
                        type: widget.type,
                        icon: widget.icon,
                        label: widget.label,
                        url: widget.url,
                        order: insertIndex + 1
                    });
                    renderAllBlockViews();
                    selectBlockForOptimization(newBlockId);
                }
            });

            dndDropZone.appendChild(item);
        });
    }

    // Mini-Dock of placed blocks inside Optimizer tab
    function renderOptimizerBlocksDock() {
        const dock = document.getElementById('optimizerBlocksDock');
        if (!dock) return;
        dock.innerHTML = '';
        if (currentBlocks.length === 0) {
            dock.innerHTML = '<span class="text-muted small font-mono" style="font-size: 0.75rem;">Aucun bloc placé</span>';
            return;
        }

        currentBlocks.forEach((b, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            const isActive = activeOptimizedBlockId === b.id;
            btn.className = 'btn btn-xs rounded-pill d-inline-flex align-items-center gap-1.5 shadow-2xs ' + 
                (isActive ? 'btn-primary text-white' : 'btn-white border text-dark');
            btn.innerHTML = `${renderIconElement(b.icon)} <span class="text-truncate" style="max-width: 85px; font-size: 0.72rem;">${b.label || 'Bloc #' + (idx + 1)}</span>`;
            btn.onclick = () => selectBlockForOptimization(b.id);
            dock.appendChild(btn);
        });
    }

    function syncSerializedData() {
        contentBlocksJsonInput.value = JSON.stringify(currentBlocks);
        const countBadge = document.getElementById('channelsHeaderCountBadge');
        if (countBadge) {
            countBadge.textContent = `${currentBlocks.length} canal${currentBlocks.length > 1 ? 'ux' : ''}`;
        }
    }

    function updateBlockCounter() {
        const badge = document.getElementById('dndBlockCountBadge');
        if (badge) badge.textContent = `${currentBlocks.length} élément(s)`;
    }

    // -------------------------------------------------------------------------
    // 10. HTML5 Drag & Drop from Smart Library into Canvas
    // -------------------------------------------------------------------------
    let draggedWidgetData = null;

    function initDraggableWidgetCards() {
        document.querySelectorAll('.draggable-widget-card').forEach(card => {
            card.removeEventListener('dragstart', handleWidgetDragStart);
            card.removeEventListener('dragend', handleWidgetDragEnd);
            card.addEventListener('dragstart', handleWidgetDragStart);
            card.addEventListener('dragend', handleWidgetDragEnd);

            card.onclick = function(e) {
                if (e.target.closest('.widget-add-btn')) return; // handled by button
                quickAddWidgetFromCard(this);
            };
        });
    }

    function handleWidgetDragStart(e) {
        draggedWidgetData = {
            type: this.dataset.type,
            icon: this.dataset.icon,
            label: this.dataset.label,
            url: this.dataset.placeholder
        };
        this.classList.add('is-dragging');
        e.dataTransfer.setData('text/plain', JSON.stringify(draggedWidgetData));
    }

    function handleWidgetDragEnd() {
        this.classList.remove('is-dragging');
        draggedWidgetData = null;
        if (dndDropZone) dndDropZone.classList.remove('dnd-drag-over');
    }

    initDraggableWidgetCards();

    [dndDropZone, dndScreenEl].forEach(zone => {
        if (!zone) return;
        zone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dndDropZone.classList.add('dnd-drag-over');
        });

        zone.addEventListener('dragleave', function (e) {
            if (!zone.contains(e.relatedTarget)) {
                dndDropZone.classList.remove('dnd-drag-over');
            }
        });

        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            dndDropZone.classList.remove('dnd-drag-over');
            let widget = draggedWidgetData;
            if (!widget) {
                try {
                    widget = JSON.parse(e.dataTransfer.getData('text/plain'));
                } catch(err) {}
            }

            if (widget && widget.type) {
                const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
                currentBlocks.push({
                    id: newBlockId,
                    type: widget.type,
                    icon: widget.icon,
                    label: widget.label,
                    url: widget.url,
                    order: currentBlocks.length + 1
                });
                renderAllBlockViews();
                selectBlockForOptimization(newBlockId);
            }
        });
    });

    // Tab manual toggles
    const tabLibBtn = document.getElementById('tab-library-btn');
    const tabOptBtn = document.getElementById('tab-optimizer-btn');
    const paneLib = document.getElementById('tab-library');
    const paneOpt = document.getElementById('tab-optimizer');

    if (tabLibBtn && tabOptBtn && paneLib && paneOpt) {
        tabLibBtn.addEventListener('click', function () {
            tabLibBtn.classList.add('active');
            tabOptBtn.classList.remove('active');
            paneLib.classList.add('show', 'active');
            paneOpt.classList.remove('show', 'active');
        });
        tabOptBtn.addEventListener('click', function () {
            tabOptBtn.classList.add('active');
            tabLibBtn.classList.remove('active');
            paneOpt.classList.add('show', 'active');
            paneLib.classList.remove('show', 'active');
        });
    }

    window.switchToLibraryTab = function() {
        if (tabLibBtn && tabOptBtn && paneLib && paneOpt) {
            tabLibBtn.classList.add('active');
            tabOptBtn.classList.remove('active');
            paneLib.classList.add('show', 'active');
            paneOpt.classList.remove('show', 'active');
        }
    };

    // Quick add button in library
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
        renderAllBlockViews();
        selectBlockForOptimization(newBlockId);
    };

    // Smart Widget Library Filtering (Category + Search)
    let currentWidgetCategory = 'all';

    window.filterWidgetCategory = function(cat, btn) {
        currentWidgetCategory = cat || 'all';
        document.querySelectorAll('.widget-cat-filter-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        applyWidgetFilter();
    };

    window.filterWidgetLibrary = function(query) {
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

        // Toggle category group headers if empty
        document.querySelectorAll('.widget-category-group').forEach(group => {
            const cards = group.querySelectorAll('.draggable-widget-card');
            const hasVisible = Array.from(cards).some(c => {
                const col = c.closest('.col-6') || c.closest('.col-12');
                return col && col.style.display !== 'none';
            });
            group.style.display = hasVisible ? 'block' : 'none';
        });
    }

    // -------------------------------------------------------------------------
    // 11. Element Optimizer Panel Logic
    // -------------------------------------------------------------------------
    window.selectFirstBlockForOptimization = function() {
        if (currentBlocks.length > 0) {
            selectBlockForOptimization(currentBlocks[0].id);
        } else {
            window.addNewBlock();
        }
    };

    window.selectBlockForOptimization = function(blockId) {
        activeOptimizedBlockId = blockId;
        const block = currentBlocks.find(b => b.id === blockId);
        if (!block) return;

        // Switch to Optimizer Tab
        if (tabLibBtn && tabOptBtn && paneLib && paneOpt) {
            tabOptBtn.classList.add('active');
            tabLibBtn.classList.remove('active');
            paneOpt.classList.add('show', 'active');
            paneLib.classList.remove('show', 'active');
        }
        if (tabOptBtn && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            try {
                const tab = bootstrap.Tab.getOrCreateInstance(tabOptBtn);
                tab.show();
            } catch(e) {}
        }

        const emptyState = document.getElementById('optimizerEmptyState');
        const formWrap = document.getElementById('optimizerFormWrap');
        if (emptyState) emptyState.style.display = 'none';
        if (formWrap) formWrap.style.display = 'block';

        const hTitle = document.getElementById('optHeaderTitle');
        const hType = document.getElementById('optHeaderType');
        const hIcon = document.getElementById('optHeaderIcon');
        const inpLabel = document.getElementById('optInputLabel');
        const inpUrl = document.getElementById('optInputUrl');
        const optManual = document.getElementById('optInputIconManual');
        const optPrevTag = document.getElementById('optPreviewIconTag');

        if (hTitle) hTitle.textContent = block.label || 'Modifier le bloc';
        if (hType) hType.textContent = `Type : ${block.type || 'bloc'}`;
        if (hIcon) hIcon.className = block.icon;
        if (inpLabel) inpLabel.value = block.label || '';
        if (inpUrl) inpUrl.value = block.url || '';
        if (optManual) optManual.value = block.icon || '';
        if (optPrevTag) optPrevTag.className = block.icon || 'fa-solid fa-link';

        renderDndCanvasBlocks();
        renderOptimizerBlocksDock();
    };

    window.applyActiveBlockOptimization = function() {
        if (!activeOptimizedBlockId) return;
        const block = currentBlocks.find(b => b.id === activeOptimizedBlockId);
        if (!block) return;

        block.label = document.getElementById('optInputLabel').value;
        block.url = document.getElementById('optInputUrl').value;

        document.getElementById('optHeaderTitle').textContent = block.label || 'Modifier le bloc';
        renderAllBlockViews();
    };

    window.applyManualIconOptimization = function(val) {
        if (!activeOptimizedBlockId) return;
        const block = currentBlocks.find(b => b.id === activeOptimizedBlockId);
        if (!block) return;
        block.icon = val;
        const optManual = document.getElementById('optInputIconManual');
        const optPrevTag = document.getElementById('optPreviewIconTag');
        const optHIcon = document.getElementById('optHeaderIcon');
        if (optManual) optManual.value = val;
        if (optPrevTag) optPrevTag.className = val;
        if (optHIcon) optHIcon.className = val;
        renderAllBlockViews();
    };

    window.setOptimizerUrlPrefix = function(prefix) {
        const inp = document.getElementById('optInputUrl');
        if (!inp) return;
        let val = inp.value.trim();
        if (val.startsWith('http://') || val.startsWith('https://') || val.startsWith('tel:') || val.startsWith('mailto:')) {
            val = val.replace(/^(https?:\/\/|tel:|mailto:)/, '');
        }
        inp.value = prefix + val;
        applyActiveBlockOptimization();
        inp.focus();
    };

    window.duplicateActiveOptimizedBlock = function() {
        if (!activeOptimizedBlockId) return;
        duplicateBlockById(activeOptimizedBlockId);
    };

    window.duplicateBlockById = function(blockId) {
        const block = currentBlocks.find(b => b.id === blockId);
        if (!block) return;
        const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
        const cloned = {
            id: newBlockId,
            type: block.type,
            icon: block.icon,
            label: (block.label || 'Canal') + ' (Copie)',
            url: block.url,
            order: currentBlocks.length + 1
        };
        const index = currentBlocks.findIndex(b => b.id === blockId);
        if (index !== -1) {
            currentBlocks.splice(index + 1, 0, cloned);
        } else {
            currentBlocks.push(cloned);
        }
        renderAllBlockViews();
        selectBlockForOptimization(newBlockId);
    };

    window.deleteActiveOptimizedBlock = function() {
        if (!activeOptimizedBlockId) return;
        deleteBlockById(activeOptimizedBlockId);
    };

    window.deleteBlockById = function(blockId) {
        currentBlocks = currentBlocks.filter(b => b.id !== blockId);
        if (activeOptimizedBlockId === blockId) {
            activeOptimizedBlockId = null;
            const emptyState = document.getElementById('optimizerEmptyState');
            const formWrap = document.getElementById('optimizerFormWrap');
            if (emptyState) emptyState.style.display = 'block';
            if (formWrap) formWrap.style.display = 'none';
        }
        renderAllBlockViews();
    };

    window.moveActiveBlock = function(direction) {
        if (!activeOptimizedBlockId) return;
        const index = currentBlocks.findIndex(b => b.id === activeOptimizedBlockId);
        if (index === -1) return;
        const newIndex = index + direction;
        if (newIndex >= 0 && newIndex < currentBlocks.length) {
            const moved = currentBlocks.splice(index, 1)[0];
            currentBlocks.splice(newIndex, 0, moved);
            renderAllBlockViews();
        }
    };

    // Universal Add New Block helper
    window.addNewBlock = function(blockData) {
        if (!blockData) blockData = {};
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

        // Highlight and focus the newly added channel
        setTimeout(() => {
            const row = document.querySelector(`[data-id="${newBlockId}"]`);
            if (row) {
                row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                row.style.outline = '2px solid #6320ee';
                setTimeout(() => { row.style.outline = 'none'; }, 1000);
                const inp = row.querySelector('.block-label-input');
                if (inp) {
                    inp.focus();
                    inp.select();
                }
            }
        }, 60);
    };

    window.addDefaultChannel = function() {
        window.addNewBlock({
            type: 'custom_link',
            icon: 'fa-solid fa-link',
            label: 'Nouveau Canal',
            url: ''
        });
    };

    // Direct click handler on the primary button
    const btnDirectAdd = document.getElementById('btnAddChannelBtn');
    if (btnDirectAdd) {
        btnDirectAdd.addEventListener('click', function(e) {
            e.preventDefault();
            window.addDefaultChannel();
        });
    }

    // Robust native Dropdown Toggle for preset choices
    const addChannelBtn = document.getElementById('btnAddChannelDropdown');
    const addChannelMenu = document.getElementById('addChannelDropdownMenu');
    if (addChannelBtn && addChannelMenu) {
        addChannelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const willShow = !addChannelMenu.classList.contains('show');
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
            if (willShow) {
                addChannelMenu.classList.add('show');
                addChannelBtn.setAttribute('aria-expanded', 'true');
            } else {
                addChannelMenu.classList.remove('show');
                addChannelBtn.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('click', function(e) {
            if (!addChannelBtn.contains(e.target) && !addChannelMenu.contains(e.target)) {
                addChannelMenu.classList.remove('show');
                addChannelBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Add Block Dropdown Trigger & Quick Chips (Option 1)
    document.querySelectorAll('.add-block-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (addChannelMenu) {
                addChannelMenu.classList.remove('show');
                if (addChannelBtn) addChannelBtn.setAttribute('aria-expanded', 'false');
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
    // 12. Initial Mount
    // -------------------------------------------------------------------------
    syncLivePreviewStyles();
    syncLivePreviewText();
    renderAllBlockViews();
});
</script>
@endpush
