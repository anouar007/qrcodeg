@extends('layouts.app')

@section('title', 'Générateur de QR Codes & Gabarits')
@section('page_title', 'Générer des QR Codes Dynamiques')
@section('page_subtitle', 'Créez vos QR codes avec gabarit visuel sur-mesure, placement par glisser-déposer et personnalisation du code unique.')

@section('content')
<div class="container-fluid px-0">
    <!-- Dedicated Dynamic Studio Callout Banner -->
    <div class="alert border-0 shadow-xs rounded-3 p-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: linear-gradient(135deg, #f5f3ff 0%, #eef2ff 100%); border-left: 4px solid #6320ee !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.25rem; color: #6320ee;">
                <i class="bi bi-file-earmark-pdf-fill"></i>
            </div>
            <div>
                <div class="fw-bold text-dark" style="font-size: 0.95rem;">Générateur Studio Dynamique avec Export PDF Direct (Multi-Pages)</div>
                <small class="text-muted">Définissez vos dimensions exactes (px, cm, mm), importez votre visuel, positionnez le QR et code unique et téléchargez directement votre PDF multi-pages prêt à l'emploi.</small>
            </div>
        </div>
        <a href="{{ route('admin.dynamic-qrs.create') }}" class="btn text-white fw-bold shadow-xs px-3 py-2 d-inline-flex align-items-center gap-2" style="background-color: #6320ee; border-radius: 8px; font-size: 0.85rem;">
            <i class="bi bi-palette2"></i>
            <span>Ouvrir le Studio Dynamique PDF</span>
        </a>
    </div>

<form method="POST" action="{{ route('admin.qr-codes.store') }}" id="qrGenerateForm" enctype="multipart/form-data">
    @csrf

    <!-- Hidden Fields for Template Visual Coordinates and Config -->
    <input type="hidden" name="template_mode" id="templateModeInput" value="1">
    <input type="hidden" name="template_preset" id="templatePresetInput" value="google_review_plaque">
    <input type="hidden" name="custom_template_config" id="customTemplateConfigInput" value="">

    <!-- Top Mode Selector Banner -->
    <div class="card card-custom p-3 bg-white mb-4 border shadow-xs">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary font-mono text-uppercase px-2 py-1 mb-1">
                    <i class="bi bi-magic me-1"></i>Studio de Création Visuelle
                </span>
                <h5 class="fw-bold mb-0 text-dark">Mode de Conception du QR Code</h5>
            </div>

            <!-- Mode Switch Tabs -->
            <div class="btn-group p-1 bg-light rounded-pill border" role="group" id="modeSelectorGroup">
                <button type="button" class="btn btn-sm rounded-pill fw-bold px-3 btn-primary shadow-xs" id="btnModeStudio" onclick="switchMode('studio');">
                    <i class="bi bi-palette2 me-1"></i> Studio Gabarit (Drag & Drop)
                </button>
                <button type="button" class="btn btn-sm rounded-pill fw-bold px-3 btn-light text-muted" id="btnModeStandard" onclick="switchMode('standard');">
                    <i class="bi bi-qr-code me-1"></i> QR Vectoriel Standard
                </button>
            </div>
        </div>
    </div>

    <!-- 1. STUDIO GABARIT VISUEL (Drag & Drop Template Studio) -->
    <div id="sectionStudioMode">
        <div class="row g-4">
            <!-- Left: Interactive Visual Canvas & Template Chooser -->
            <div class="col-xl-8 col-lg-7">
                <!-- Template Chooser Card -->
                <div class="card card-custom p-4 bg-white mb-4 border shadow-xs">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-image text-primary me-2"></i>Image de Support / Gabarit
                            </h6>
                            <small class="text-muted">Choisissez un modèle prêt à l'emploi ou importez votre propre visuel (plaque plexi, chevalet, affiche).</small>
                        </div>
                        <span class="badge bg-light text-dark border font-mono small" id="templateStatusBadge">
                            Modèle Actif : Plaque Avis Google
                        </span>
                    </div>

                    <div class="row g-3 align-items-center">
                        <!-- Preset Button 1: Sample Google Plaque -->
                        <div class="col-sm-6">
                            <div class="template-preset-card p-3 rounded-3 border active-preset cursor-pointer" id="presetPlaqueGoogle" onclick="selectTemplatePreset('google_review_plaque');">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-2 overflow-hidden border bg-light shadow-xs flex-shrink-0" style="width: 54px; height: 54px;">
                                        <img src="{{ asset('images/templates/google_review_plaque.jpg') }}" alt="Plaque Avis Google" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold text-dark small">Plaque Avis Google</span>
                                            <span class="badge bg-success-subtle text-success font-mono" style="font-size: 0.65rem;">Fourni</span>
                                        </div>
                                        <p class="text-muted mb-0 small text-truncate" style="font-size: 0.78rem;">Plaque comptoir NFC & QR avec avis Google</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Image Upload Dropzone -->
                        <div class="col-sm-6">
                            <div class="template-upload-zone p-3 rounded-3 border border-dashed text-center cursor-pointer position-relative bg-light-subtle" id="customUploadZone">
                                <input type="file" name="template_image" id="templateImageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-4"></i>
                                    <div class="text-start">
                                        <div class="fw-bold text-dark small" id="uploadLabelText">Téléverser votre propre image</div>
                                        <small class="text-muted" style="font-size: 0.74rem;">PNG, JPG, WebP jusqu'à 10 Mo</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Visual Canvas Card -->
                <div class="card card-custom p-4 bg-white border shadow-xs">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-arrows-move text-primary me-2"></i>Atelier Interactif (Glisser-Déposer & Redimensionnement)
                            </h6>
                            <small class="text-muted">Déplacez directement le QR code et son code unique à la souris ou au doigt sur l'image.</small>
                        </div>

                        <!-- Canvas Quick Helper Actions -->
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="centerQrOnCanvas();" title="Centrer le QR Code">
                                <i class="bi bi-align-center"></i> Centrer QR
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="snapCodeUnderQr();" title="Aligner le code sous le QR">
                                <i class="bi bi-magnet"></i> Caler Code
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill" onclick="resetToSampleCoordinates();" title="Réinitialiser positions par défaut du modèle">
                                <i class="bi bi-arrow-counterclockwise"></i> Positions Plaque
                            </button>
                        </div>
                    </div>

                    <!-- Canvas Wrapper with Checkered Backdrop -->
                    <div class="canvas-viewport-wrapper p-2 p-md-3 rounded-4 bg-dark-subtle position-relative overflow-hidden text-center" style="min-height: 480px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle, #334155 0%, #0f172a 100%);">
                        <!-- Canvas Inner Frame -->
                        <div id="studioCanvas" class="studio-canvas position-relative user-select-none shadow-lg rounded-3 overflow-hidden" style="max-width: 600px; width: 100%; aspect-ratio: 1/1; background-color: #ffffff;">
                            <!-- Template Background Image -->
                            <img id="canvasBackgroundImg" src="{{ asset('images/templates/google_review_plaque.jpg') }}" alt="Template Background" class="w-100 h-100 object-fit-contain d-block pointer-events-none">

                            <!-- Draggable QR Code Layer -->
                            <div id="draggableQr" class="draggable-element draggable-qr position-absolute cursor-grab" style="left: 63.5%; top: 65.5%; width: 23%; aspect-ratio: 1/1;" title="Glissez pour déplacer le QR Code">
                                <!-- Top Label ("Qr Code") -->
                                <div id="canvasTopLabel" class="canvas-top-label text-center position-absolute w-100" style="bottom: 104%; left: 0; font-family: system-ui, sans-serif; font-size: 11px; font-weight: 500; color: #475569; pointer-events: none; line-height: 1;">
                                    Qr Code
                                </div>

                                <!-- Corner Brackets Overlay (Sample Plaque Style) -->
                                <div id="canvasBrackets" class="corner-brackets-frame position-absolute w-100 h-100 pointer-events-none" style="top: 0; left: 0;">
                                    <span class="corner-bracket top-left"></span>
                                    <span class="corner-bracket top-right"></span>
                                    <span class="corner-bracket bottom-left"></span>
                                    <span class="corner-bracket bottom-right"></span>
                                </div>

                                <!-- Actual QR Pattern Preview -->
                                <div class="qr-pattern-box w-100 h-100 p-1 d-flex align-items-center justify-content-center bg-white rounded-1 shadow-xs border">
                                    <div id="canvasQrSvgTarget" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <!-- Realistic Scalable SVG QR Icon -->
                                        <svg viewBox="0 0 100 100" class="w-100 h-100" fill="#000000">
                                            <!-- Standard QR finder patterns -->
                                            <rect x="5" y="5" width="28" height="28" rx="2" fill="#000000"/>
                                            <rect x="9" y="9" width="20" height="20" fill="#ffffff"/>
                                            <rect x="13" y="13" width="12" height="12" fill="#000000"/>
                                            
                                            <rect x="67" y="5" width="28" height="28" rx="2" fill="#000000"/>
                                            <rect x="71" y="9" width="20" height="20" fill="#ffffff"/>
                                            <rect x="75" y="13" width="12" height="12" fill="#000000"/>

                                            <rect x="5" y="67" width="28" height="28" rx="2" fill="#000000"/>
                                            <rect x="9" y="71" width="20" height="20" fill="#ffffff"/>
                                            <rect x="13" y="75" width="12" height="12" fill="#000000"/>

                                            <!-- Data modules -->
                                            <rect x="38" y="8" width="5" height="5"/>
                                            <rect x="48" y="8" width="5" height="5"/>
                                            <rect x="58" y="8" width="5" height="5"/>
                                            <rect x="38" y="18" width="5" height="5"/>
                                            <rect x="48" y="18" width="15" height="5"/>
                                            <rect x="38" y="28" width="10" height="5"/>
                                            <rect x="53" y="28" width="5" height="5"/>
                                            <rect x="8" y="38" width="5" height="5"/>
                                            <rect x="18" y="38" width="15" height="5"/>
                                            <rect x="38" y="38" width="15" height="5"/>
                                            <rect x="58" y="38" width="5" height="5"/>
                                            <rect x="68" y="38" width="10" height="5"/>
                                            <rect x="83" y="38" width="10" height="5"/>
                                            <rect x="8" y="48" width="10" height="5"/>
                                            <rect x="23" y="48" width="5" height="5"/>
                                            <rect x="38" y="48" width="5" height="15"/>
                                            <rect x="48" y="48" width="10" height="5"/>
                                            <rect x="68" y="48" width="5" height="5"/>
                                            <rect x="78" y="48" width="15" height="5"/>
                                            <rect x="8" y="58" width="5" height="5"/>
                                            <rect x="18" y="58" width="10" height="5"/>
                                            <rect x="48" y="58" width="15" height="5"/>
                                            <rect x="78" y="58" width="5" height="5"/>
                                            <rect x="88" y="58" width="5" height="5"/>
                                            <rect x="38" y="68" width="10" height="5"/>
                                            <rect x="53" y="68" width="5" height="10"/>
                                            <rect x="68" y="68" width="15" height="5"/>
                                            <rect x="38" y="78" width="5" height="15"/>
                                            <rect x="48" y="78" width="10" height="5"/>
                                            <rect x="68" y="78" width="5" height="5"/>
                                            <rect x="78" y="78" width="15" height="5"/>
                                            <rect x="48" y="88" width="15" height="5"/>
                                            <rect x="68" y="88" width="10" height="5"/>
                                            <rect x="83" y="88" width="10" height="5"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Resize Handle at bottom right -->
                                <div id="qrResizeHandle" class="resize-handle position-absolute bottom-0 end-0 cursor-se-resize" title="Glissez pour redimensionner">
                                    <i class="bi bi-arrow-down-right-square-fill text-primary fs-6"></i>
                                </div>

                                <!-- Floating Coordinate Indicator -->
                                <div class="element-badge badge bg-dark text-white font-mono position-absolute top-0 start-50 translate-middle-x" style="font-size: 0.62rem; padding: 2px 6px; transform: translateY(-110%); pointer-events: none;">
                                    QR (<span id="indicatorQrX">63%</span>, <span id="indicatorQrY">65%</span>)
                                </div>
                            </div>

                            <!-- Draggable Unique Code Layer -->
                            <div id="draggableCode" class="draggable-element draggable-code position-absolute cursor-grab text-center user-select-none" style="left: 75.0%; top: 86.8%; transform: translateX(-50%); white-space: nowrap;" title="Glissez pour déplacer le Code Unique">
                                <div id="canvasCodeBadge" class="canvas-code-badge font-mono" style="font-size: 13px; font-weight: 700; color: #1e293b; letter-spacing: 0.05em; padding: 2px 4px; border-radius: 4px;">
                                    <span id="canvasCodePrefix">VPM </span><span id="canvasCodeSample">013</span>
                                </div>
                                
                                <!-- Floating Coordinate Indicator for Code -->
                                <div class="element-badge badge bg-secondary text-white font-mono position-absolute top-100 start-50 translate-middle-x" style="font-size: 0.6rem; padding: 1px 4px; transform: translateY(4px); pointer-events: none;">
                                    Code (<span id="indicatorCodeX">75%</span>, <span id="indicatorCodeY">87%</span>)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Coordinate Readout Status Bar -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 p-2 bg-light rounded-3 font-mono small border">
                        <div class="d-flex align-items-center gap-3">
                            <span><i class="bi bi-crosshair text-primary me-1"></i>QR: <strong>X:<span id="statusQrX">63.5</span>% Y:<span id="statusQrY">65.5</span>%</strong> (Taille: <strong><span id="statusQrSize">23.0</span>%</strong>)</span>
                            <span class="text-muted">|</span>
                            <span><i class="bi bi-tag text-success me-1"></i>Code: <strong>X:<span id="statusCodeX">75.0</span>% Y:<span id="statusCodeY">86.8</span>%</strong></span>
                        </div>
                        <span class="text-muted" style="font-size: 0.72rem;">
                            <i class="bi bi-info-circle me-1"></i>Les coordonnées sont enregistrées automatiquement en pourcentage haute précision.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Studio Styling & Operational Controls Panel -->
            <div class="col-xl-4 col-lg-5">
                <!-- Accordion of Precision Controls -->
                <div class="card card-custom p-4 bg-white mb-4 border shadow-xs sticky-top" style="top: 85px; z-index: 10;">
                    <div class="text-center mb-3">
                        <span class="badge bg-primary-subtle text-primary font-mono mb-1">PARAMÈTRES DU GABARIT</span>
                        <h6 class="fw-bold mb-0 text-dark">Personnalisation du Support</h6>
                    </div>

                    <!-- Tabbed Controls Navigation -->
                    <ul class="nav nav-pills nav-fill mb-3 bg-light p-1 rounded-pill border" id="studioTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill small py-1 fw-bold" id="tab-qr-tab" data-bs-toggle="pill" data-bs-target="#tab-qr" type="button" role="tab">
                                <i class="bi bi-qr-code"></i> QR Code
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill small py-1 fw-bold" id="tab-code-tab" data-bs-toggle="pill" data-bs-target="#tab-code" type="button" role="tab">
                                <i class="bi bi-tag"></i> Code Unique
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill small py-1 fw-bold" id="tab-batch-tab" data-bs-toggle="pill" data-bs-target="#tab-batch" type="button" role="tab">
                                <i class="bi bi-gear"></i> Lot & Cible
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="studioTabContent">
                        <!-- TAB 1: QR CODE POSITION & CORNER BRACKETS -->
                        <div class="tab-pane fade show active" id="tab-qr" role="tabpanel">
                            <!-- QR Size Slider -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-semibold mb-0">Taille du QR Code</label>
                                    <span class="badge bg-light text-dark font-mono border" id="qrSizeValDisplay">23 %</span>
                                </div>
                                <input type="range" class="form-range" id="sliderQrSize" min="10" max="60" step="0.5" value="23" oninput="updateQrSizeFromSlider(this.value);">
                            </div>

                            <!-- QR Coordinates X & Y Sliders -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold mb-0">Position X</label>
                                        <span class="font-mono text-muted small" id="qrXValDisplay">63.5%</span>
                                    </div>
                                    <input type="range" class="form-range" id="sliderQrX" min="0" max="90" step="0.5" value="63.5" oninput="updateQrXFromSlider(this.value);">
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold mb-0">Position Y</label>
                                        <span class="font-mono text-muted small" id="qrYValDisplay">65.5%</span>
                                    </div>
                                    <input type="range" class="form-range" id="sliderQrY" min="0" max="90" step="0.5" value="65.5" oninput="updateQrYFromSlider(this.value);">
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            <!-- Corner Brackets Toggle (Sample Image Style) -->
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="toggleBrackets" checked onchange="toggleCornerBrackets(this.checked);">
                                <label class="form-check-label small fw-semibold" for="toggleBrackets">
                                    Coins Dorés Encadrants (Brackets)
                                </label>
                            </div>

                            <div class="row g-2 mb-3" id="bracketControlsRow">
                                <div class="col-7">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Couleur des Coins</label>
                                    <div class="input-group input-group-sm">
                                        <input type="color" id="bracketColorPicker" class="form-control form-control-color" value="#d9a74a" onchange="updateBracketColor(this.value);">
                                        <input type="text" class="form-control font-mono" id="bracketColorText" value="#d9a74a" oninput="updateBracketColor(this.value);">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Palette Rapide</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm p-0 rounded-circle border shadow-xs" style="width: 26px; height: 26px; background-color: #d9a74a;" onclick="updateBracketColor('#d9a74a');" title="Or Antique (Plaque)"></button>
                                        <button type="button" class="btn btn-sm p-0 rounded-circle border shadow-xs" style="width: 26px; height: 26px; background-color: #0f172a;" onclick="updateBracketColor('#0f172a');" title="Noir Intense"></button>
                                        <button type="button" class="btn btn-sm p-0 rounded-circle border shadow-xs" style="width: 26px; height: 26px; background-color: #2563eb;" onclick="updateBracketColor('#2563eb');" title="Bleu Royal"></button>
                                        <button type="button" class="btn btn-sm p-0 rounded-circle border shadow-xs" style="width: 26px; height: 26px; background-color: #ffffff;" onclick="updateBracketColor('#ffffff');" title="Blanc"></button>
                                    </div>
                                </div>
                            </div>

                            <!-- Top Label Toggle ("Qr Code") -->
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="toggleTopLabel" checked onchange="toggleTopLabelSetting(this.checked);">
                                <label class="form-check-label small fw-semibold" for="toggleTopLabel">
                                    Libellé Supérieur ("Qr Code")
                                </label>
                            </div>

                            <div class="row g-2" id="topLabelControlsRow">
                                <div class="col-7">
                                    <input type="text" class="form-control form-control-sm" id="topLabelTextInput" value="Qr Code" placeholder="ex: Qr Code" oninput="updateTopLabelText(this.value);">
                                </div>
                                <div class="col-5">
                                    <div class="input-group input-group-sm">
                                        <input type="color" id="topLabelColorPicker" class="form-control form-control-color" value="#475569" onchange="updateTopLabelColor(this.value);">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: UNIQUE CODE STYLING & POSITION -->
                        <div class="tab-pane fade" id="tab-code" role="tabpanel">
                            <!-- Code Prefix -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Préfixe du Code Unique</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light font-mono">Préfixe</span>
                                    <input type="text" class="form-control font-mono fw-bold" id="codePrefixInput" value="VPM " placeholder="ex: VPM ou VP-" oninput="updateCodePrefix(this.value);">
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">Chaque QR aura ce préfixe suivi de son code séquentiel unique (ex: <strong>VPM 013</strong>).</small>
                            </div>

                            <!-- Font Size Slider -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-semibold mb-0">Taille de Police</label>
                                    <span class="badge bg-light text-dark font-mono border" id="codeSizeValDisplay">13 px</span>
                                </div>
                                <input type="range" class="form-range" id="sliderCodeSize" min="9" max="32" step="1" value="13" oninput="updateCodeFontSize(this.value);">
                            </div>

                            <!-- Code Text Color -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Couleur du Code</label>
                                <div class="input-group input-group-sm">
                                    <input type="color" id="codeColorPicker" class="form-control form-control-color" value="#1e293b" onchange="updateCodeColor(this.value);">
                                    <input type="text" class="form-control font-mono" id="codeColorText" value="#1e293b" oninput="updateCodeColor(this.value);">
                                </div>
                            </div>

                            <!-- Font Family & Weight -->
                            <div class="row g-2 mb-3">
                                <div class="col-7">
                                    <label class="form-label small fw-semibold mb-1">Style de Police</label>
                                    <select class="form-select form-select-sm" id="codeFontFamilySelect" onchange="updateCodeFontFamily(this.value);">
                                        <option value="monospace" selected>Monospace Technique</option>
                                        <option value="sans-serif">Sans-Serif Moderne</option>
                                        <option value="serif">Serif Élégant</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label class="form-label small fw-semibold mb-1">Graisse</label>
                                    <select class="form-select form-select-sm" id="codeFontWeightSelect" onchange="updateCodeFontWeight(this.value);">
                                        <option value="700" selected>Gras (Bold)</option>
                                        <option value="600">Semi-Gras</option>
                                        <option value="500">Normal</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Position Sliders (Two-way sync with direct dragging) -->
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Code X: <span id="codeXValDisplay">75.0%</span></label>
                                    <input type="range" class="form-range" id="sliderCodeX" min="5" max="95" step="0.5" value="75.0" oninput="updateCodeXFromSlider(this.value);">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Code Y: <span id="codeYValDisplay">86.8%</span></label>
                                    <input type="range" class="form-range" id="sliderCodeY" min="5" max="95" step="0.5" value="86.8" oninput="updateCodeYFromSlider(this.value);">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: BATCH GENERATION & TARGET DESTINATION -->
                        <div class="tab-pane fade" id="tab-batch" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Quantité à Générer <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bi bi-stack"></i></span>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control fw-bold" min="1" max="1000" value="{{ old('quantity', 1) }}" required>
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">1 pour un QR unique, ou jusqu'à 1 000 en série.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Nom / Préfixe <span class="text-danger">*</span></label>
                                <input type="text" name="name_prefix" id="namePrefixInput" class="form-control form-control-sm" value="{{ old('name_prefix', 'Plaque Avis Google') }}" required placeholder="ex: Plaque Google Review ou Table">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Attribuer au Client</label>
                                <select name="client_id" class="form-select form-select-sm">
                                    <option value="">-- Sans Client (Interne) --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ (old('client_id') == $client->id || request('client_id') == $client->id) ? 'selected' : '' }}>
                                            {{ $client->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Type de Contenu <span class="text-danger">*</span></label>
                                <select name="type" id="typeSelector" class="form-select form-select-sm" required onchange="updateContentTypeFields();">
                                    <option value="website" {{ old('type', 'website') === 'website' ? 'selected' : '' }}>Lien Web / Fiche Google</option>
                                    <option value="contact" {{ old('type') === 'contact' ? 'selected' : '' }}>Contact Numérique (vCard)</option>
                                    <option value="whatsapp" {{ old('type') === 'whatsapp' ? 'selected' : '' }}>Message WhatsApp Direct</option>
                                    <option value="wifi" {{ old('type') === 'wifi' ? 'selected' : '' }}>Connexion WiFi Directe</option>
                                    <option value="business" {{ old('type') === 'business' ? 'selected' : '' }}>Profil d'Entreprise</option>
                                    <option value="social" {{ old('type') === 'social' ? 'selected' : '' }}>Hub Réseaux Sociaux</option>
                                    <option value="custom_landing" {{ old('type') === 'custom_landing' ? 'selected' : '' }}>Page d'Atterrissage Sur-Mesure</option>
                                </select>
                            </div>

                            <div class="mb-3" id="urlDestinationGroup">
                                <label class="form-label small fw-semibold mb-1">URL de Destination Active</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                                    <input type="url" name="url" class="form-control" placeholder="https://g.page/r/your-google-review-link" value="{{ old('url', 'https://g.page/r/review-sample') }}">
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">Modifiable à volonté à tout moment sans réimpression.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Big Vibrant Submit Action Button -->
                    <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-3 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-magic fs-5"></i>
                        <span>Générer avec Gabarit</span>
                    </button>
                    <div class="text-center mt-2">
                        <small class="text-muted" style="font-size: 0.72rem;">
                            <i class="bi bi-shield-check text-success me-1"></i>Chaque support sera généré avec son QR et code séquentiel imprimés.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STANDARD VECTOR QR MODE (Clean direct vector generation) -->
    <div id="sectionStandardMode" class="d-none">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Standard Batch and Content Options -->
                <div class="card card-custom p-4 bg-white mb-4 border shadow-xs">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Paramètres Opérationnels Standard</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Quantité à Générer <span class="text-danger">*</span></label>
                            <input type="number" name="std_quantity" id="stdQuantityInput" class="form-control fw-bold" min="1" max="1000" value="1" onchange="document.getElementById('quantityInput').value = this.value;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nom / Préfixe <span class="text-danger">*</span></label>
                            <input type="text" name="std_name_prefix" class="form-control" value="QR Code Standard" oninput="document.getElementById('namePrefixInput').value = this.value;">
                        </div>
                    </div>
                </div>

                <!-- Standard Design Options -->
                <div class="card card-custom p-4 bg-white border shadow-xs">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Style Vectoriel Simple</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Couleur Foreground</label>
                            <input type="color" name="foreground_color" id="fgColorInput" class="form-control form-control-color" value="#1e3a8a">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Couleur Background</label>
                            <input type="color" name="background_color" id="bgColorInput" class="form-control form-control-color" value="#ffffff">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Style Points</label>
                            <select name="dot_style" id="dotStyleInput" class="form-select">
                                <option value="square">Carré Standard</option>
                                <option value="dots">Points Circulaires</option>
                                <option value="rounded">Coins Arrondis</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-custom p-4 bg-white border shadow-xs text-center">
                    <h6 class="fw-bold mb-3">Génération Directe</h6>
                    <p class="text-muted small">Génère les QR codes au format SVG et PNG transparent standard.</p>
                    <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold">
                        <i class="bi bi-qr-code me-1"></i> Générer en Mode Standard
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@push('styles')
<style>
/* Studio Gabarit Styles & Aesthetics */
.cursor-grab {
    cursor: grab !important;
}
.cursor-grab:active,
.cursor-grab.is-dragging {
    cursor: grabbing !important;
}
.cursor-pointer {
    cursor: pointer;
}
.cursor-se-resize {
    cursor: se-resize !important;
}
.studio-canvas,
.studio-canvas * {
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    -webkit-user-drag: none;
}

.template-preset-card {
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    background-color: #f8fafc;
}
.template-preset-card:hover {
    border-color: var(--primary-color) !important;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
}
.template-preset-card.active-preset {
    border-color: var(--primary-color) !important;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(37, 99, 235, 0.02) 100%);
    box-shadow: 0 0 0 1px var(--primary-color);
}

.template-upload-zone {
    transition: all 0.2s ease;
}
.template-upload-zone:hover {
    border-color: var(--primary-color) !important;
    background-color: #ffffff !important;
}

/* Studio Canvas & Overlays */
.studio-canvas {
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4);
}

/* Draggable QR element */
.draggable-qr {
    transition: box-shadow 0.15s ease;
    touch-action: none;
    z-index: 5;
}
.draggable-qr:hover, .draggable-qr.is-dragging {
    box-shadow: 0 0 0 2px var(--primary-color), 0 8px 24px rgba(37, 99, 235, 0.35);
}

/* Draggable Unique Code element */
.draggable-code {
    transition: box-shadow 0.15s ease;
    touch-action: none;
    z-index: 6;
}
.draggable-code:hover, .draggable-code.is-dragging {
    box-shadow: 0 0 0 2px #10b981, 0 4px 12px rgba(16, 185, 129, 0.35);
    background-color: rgba(255, 255, 255, 0.95);
}

/* Golden Corner Brackets (Sample Plaque Style) */
.corner-brackets-frame .corner-bracket {
    position: absolute;
    width: 20%;
    height: 20%;
    border-color: #d9a74a;
    border-style: solid;
    pointer-events: none;
    transition: border-color 0.15s ease;
}
.corner-bracket.top-left {
    top: -8%;
    left: -8%;
    border-width: 2.5px 0 0 2.5px;
    border-top-left-radius: 4px;
}
.corner-bracket.top-right {
    top: -8%;
    right: -8%;
    border-width: 2.5px 2.5px 0 0;
    border-top-right-radius: 4px;
}
.corner-bracket.bottom-left {
    bottom: -8%;
    left: -8%;
    border-width: 0 0 2.5px 2.5px;
    border-bottom-left-radius: 4px;
}
.corner-bracket.bottom-right {
    bottom: -8%;
    right: -8%;
    border-width: 0 2.5px 2.5px 0;
    border-bottom-right-radius: 4px;
}

.resize-handle {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translate(25%, 25%);
    background: #ffffff;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    z-index: 10;
}

.element-badge {
    opacity: 0;
    transition: opacity 0.15s ease;
}
.draggable-element:hover .element-badge, .draggable-element.is-dragging .element-badge {
    opacity: 1;
}

.pointer-events-none {
    pointer-events: none;
}
</style>
@endpush

@push('scripts')
<script>
// State Configuration Store
const studioState = {
    // QR position & size (Percentages)
    qrX: 63.5,
    qrY: 65.5,
    qrSize: 23.0,
    showBrackets: true,
    bracketColor: '#d9a74a',
    showTopLabel: true,
    topLabelText: 'Qr Code',
    topLabelColor: '#475569',
    topLabelSize: 11,

    // Unique Code styling & position (Percentages)
    codeX: 75.0,
    codeY: 86.8,
    codePrefix: 'VPM ',
    codeSample: '013',
    codeSize: 13,
    codeColor: '#1e293b',
    codeFont: 'monospace',
    codeWeight: '700',

    // Template Mode
    mode: 'studio', // 'studio' or 'standard'
    preset: 'google_review_plaque',
    customImageUrl: null
};

// DOM References
const studioCanvas = document.getElementById('studioCanvas');
const draggableQr = document.getElementById('draggableQr');
const draggableCode = document.getElementById('draggableCode');
const qrResizeHandle = document.getElementById('qrResizeHandle');

// Sliders and Displays
const sliderQrSize = document.getElementById('sliderQrSize');
const sliderQrX = document.getElementById('sliderQrX');
const sliderQrY = document.getElementById('sliderQrY');
const sliderCodeX = document.getElementById('sliderCodeX');
const sliderCodeY = document.getElementById('sliderCodeY');
const sliderCodeSize = document.getElementById('sliderCodeSize');

// Initialize Studio on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    applyStudioStateToDom();
    setupCanvasDragAndDrop();
    setupImageUploadListener();
    serializeConfigForSubmission();
});

// Mode Switching (Studio vs Standard Vector)
function switchMode(mode) {
    studioState.mode = mode;
    document.getElementById('templateModeInput').value = mode === 'studio' ? '1' : '0';

    if (mode === 'studio') {
        document.getElementById('sectionStudioMode').classList.remove('d-none');
        document.getElementById('sectionStandardMode').classList.add('d-none');
        document.getElementById('btnModeStudio').className = 'btn btn-sm rounded-pill fw-bold px-3 btn-primary shadow-xs';
        document.getElementById('btnModeStandard').className = 'btn btn-sm rounded-pill fw-bold px-3 btn-light text-muted';
    } else {
        document.getElementById('sectionStudioMode').classList.add('d-none');
        document.getElementById('sectionStandardMode').classList.remove('d-none');
        document.getElementById('btnModeStudio').className = 'btn btn-sm rounded-pill fw-bold px-3 btn-light text-muted';
        document.getElementById('btnModeStandard').className = 'btn btn-sm rounded-pill fw-bold px-3 btn-primary shadow-xs';
    }
}

// Preset Selection
function selectTemplatePreset(presetName) {
    studioState.preset = presetName;
    document.getElementById('templatePresetInput').value = presetName;

    // Reset upload text if preset picked
    document.getElementById('presetPlaqueGoogle').classList.add('active-preset');
    document.getElementById('uploadLabelText').textContent = 'Téléverser votre propre image';

    if (presetName === 'google_review_plaque') {
        document.getElementById('canvasBackgroundImg').src = "{{ asset('images/templates/google_review_plaque.jpg') }}";
        document.getElementById('templateStatusBadge').textContent = 'Modèle Actif : Plaque Avis Google';
        resetToSampleCoordinates();
    }
}

// Custom Image Upload Handler
function setupImageUploadListener() {
    const fileInput = document.getElementById('templateImageInput');
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                studioState.customImageUrl = event.target.result;
                document.getElementById('canvasBackgroundImg').src = event.target.result;
                document.getElementById('templatePresetInput').value = 'custom';
                document.getElementById('presetPlaqueGoogle').classList.remove('active-preset');
                document.getElementById('uploadLabelText').textContent = file.name.substring(0, 24) + '...';
                document.getElementById('templateStatusBadge').textContent = 'Modèle Actif : Image Personnalisée';
            };
            reader.readAsDataURL(file);
        }
    });
}

// Apply State to DOM Elements
function applyStudioStateToDom() {
    // 1. QR Element
    draggableQr.style.left = studioState.qrX + '%';
    draggableQr.style.top = studioState.qrY + '%';
    draggableQr.style.width = studioState.qrSize + '%';

    // 2. Sliders & Displays for QR
    sliderQrSize.value = studioState.qrSize;
    sliderQrX.value = studioState.qrX;
    sliderQrY.value = studioState.qrY;

    document.getElementById('qrSizeValDisplay').textContent = studioState.qrSize.toFixed(1) + ' %';
    document.getElementById('qrXValDisplay').textContent = studioState.qrX.toFixed(1) + '%';
    document.getElementById('qrYValDisplay').textContent = studioState.qrY.toFixed(1) + '%';
    document.getElementById('statusQrX').textContent = studioState.qrX.toFixed(1);
    document.getElementById('statusQrY').textContent = studioState.qrY.toFixed(1);
    document.getElementById('statusQrSize').textContent = studioState.qrSize.toFixed(1);
    document.getElementById('indicatorQrX').textContent = studioState.qrX.toFixed(0) + '%';
    document.getElementById('indicatorQrY').textContent = studioState.qrY.toFixed(0) + '%';

    // 3. Brackets & Top Label
    const bracketsEl = document.getElementById('canvasBrackets');
    bracketsEl.style.display = studioState.showBrackets ? 'block' : 'none';
    bracketsEl.querySelectorAll('.corner-bracket').forEach(cb => {
        cb.style.borderColor = studioState.bracketColor;
    });

    const topLabelEl = document.getElementById('canvasTopLabel');
    topLabelEl.style.display = studioState.showTopLabel ? 'block' : 'none';
    topLabelEl.textContent = studioState.topLabelText;
    topLabelEl.style.color = studioState.topLabelColor;

    // 4. Unique Code Element
    draggableCode.style.left = studioState.codeX + '%';
    draggableCode.style.top = studioState.codeY + '%';

    const codeBadge = document.getElementById('canvasCodeBadge');
    codeBadge.style.fontSize = studioState.codeSize + 'px';
    codeBadge.style.color = studioState.codeColor;
    codeBadge.style.fontFamily = studioState.codeFont;
    codeBadge.style.fontWeight = studioState.codeWeight;

    document.getElementById('canvasCodePrefix').textContent = studioState.codePrefix;
    document.getElementById('canvasCodeSample').textContent = studioState.codeSample;

    // 5. Code Sliders & Displays
    sliderCodeX.value = studioState.codeX;
    sliderCodeY.value = studioState.codeY;
    sliderCodeSize.value = studioState.codeSize;

    document.getElementById('codeSizeValDisplay').textContent = studioState.codeSize + ' px';
    document.getElementById('codeXValDisplay').textContent = studioState.codeX.toFixed(1) + '%';
    document.getElementById('codeYValDisplay').textContent = studioState.codeY.toFixed(1) + '%';
    document.getElementById('statusCodeX').textContent = studioState.codeX.toFixed(1);
    document.getElementById('statusCodeY').textContent = studioState.codeY.toFixed(1);
    document.getElementById('indicatorCodeX').textContent = studioState.codeX.toFixed(0) + '%';
    document.getElementById('indicatorCodeY').textContent = studioState.codeY.toFixed(0) + '%';

    serializeConfigForSubmission();
}

// Serialize config object into hidden form input for backend processing
function serializeConfigForSubmission() {
    const configPayload = {
        qr_x: studioState.qrX,
        qr_y: studioState.qrY,
        qr_size_percent: studioState.qrSize,
        show_brackets: studioState.showBrackets,
        bracket_color: studioState.bracketColor,
        show_top_label: studioState.showTopLabel,
        top_label_text: studioState.topLabelText,
        top_label_color: studioState.topLabelColor,
        top_label_size: studioState.topLabelSize,
        code_x: studioState.codeX,
        code_y: studioState.codeY,
        code_size: studioState.codeSize,
        code_color: studioState.codeColor,
        code_prefix: studioState.codePrefix,
        code_font: studioState.codeFont,
        code_bold: studioState.codeWeight === '700'
    };
    document.getElementById('customTemplateConfigInput').value = JSON.stringify(configPayload);
}

// -------------------------------------------------------------
// Interactive Direct Drag & Drop Engine (Touch + Mouse Enabled)
// -------------------------------------------------------------
function setupCanvasDragAndDrop() {
    let activeDragElement = null;
    let isResizingQr = false;
    let startX = 0;
    let startY = 0;
    let initialLeft = 0;
    let initialTop = 0;
    let initialWidth = 0;

    // 1. Mouse & Touch Down Handlers
    draggableQr.addEventListener('mousedown', (e) => {
        if (e.target.closest('#qrResizeHandle')) return;
        startDrag(e, draggableQr);
    });
    draggableQr.addEventListener('touchstart', (e) => {
        if (e.target.closest('#qrResizeHandle')) return;
        startDrag(e.touches[0], draggableQr);
    }, { passive: true });

    draggableCode.addEventListener('mousedown', (e) => startDrag(e, draggableCode));
    draggableCode.addEventListener('touchstart', (e) => startDrag(e.touches[0], draggableCode), { passive: true });

    qrResizeHandle.addEventListener('mousedown', (e) => {
        e.stopPropagation();
        startResize(e);
    });
    qrResizeHandle.addEventListener('touchstart', (e) => {
        e.stopPropagation();
        startResize(e.touches[0]);
    }, { passive: true });

    function startDrag(e, element) {
        if (e.preventDefault && e.cancelable !== false) e.preventDefault();
        activeDragElement = element;
        element.classList.add('is-dragging');
        startX = e.clientX;
        startY = e.clientY;

        const canvasRect = studioCanvas.getBoundingClientRect();
        if (element === draggableQr) {
            initialLeft = (studioState.qrX / 100) * canvasRect.width;
            initialTop = (studioState.qrY / 100) * canvasRect.height;
        } else {
            initialLeft = (studioState.codeX / 100) * canvasRect.width;
            initialTop = (studioState.codeY / 100) * canvasRect.height;
        }

        document.addEventListener('mousemove', onMove, { passive: false });
        document.addEventListener('mouseup', onEnd);
        document.addEventListener('touchmove', onTouchMove, { passive: false });
        document.addEventListener('touchend', onEnd);
    }

    function startResize(e) {
        if (e.preventDefault && e.cancelable !== false) e.preventDefault();
        isResizingQr = true;
        startX = e.clientX;
        const canvasRect = studioCanvas.getBoundingClientRect();
        initialWidth = (studioState.qrSize / 100) * canvasRect.width;

        document.addEventListener('mousemove', onMove, { passive: false });
        document.addEventListener('mouseup', onEnd);
        document.addEventListener('touchmove', onTouchMove, { passive: false });
        document.addEventListener('touchend', onEnd);
    }

    function onTouchMove(e) {
        if (activeDragElement || isResizingQr) {
            e.preventDefault();
            onMove(e.touches[0]);
        }
    }

    function onMove(e) {
        const canvasRect = studioCanvas.getBoundingClientRect();
        if (canvasRect.width === 0) return;

        const deltaX = e.clientX - startX;
        const deltaY = e.clientY - startY;

        if (isResizingQr) {
            let newWidthPx = initialWidth + deltaX;
            let newWidthPercent = (newWidthPx / canvasRect.width) * 100;
            newWidthPercent = Math.max(10, Math.min(60, newWidthPercent));
            studioState.qrSize = newWidthPercent;
            applyStudioStateToDom();
            return;
        }

        if (!activeDragElement) return;

        let newLeftPx = initialLeft + deltaX;
        let newTopPx = initialTop + deltaY;

        let percentX = (newLeftPx / canvasRect.width) * 100;
        let percentY = (newTopPx / canvasRect.height) * 100;

        if (activeDragElement === draggableQr) {
            percentX = Math.max(0, Math.min(100 - studioState.qrSize, percentX));
            percentY = Math.max(0, Math.min(100 - studioState.qrSize, percentY));
            studioState.qrX = percentX;
            studioState.qrY = percentY;
        } else if (activeDragElement === draggableCode) {
            percentX = Math.max(5, Math.min(95, percentX));
            percentY = Math.max(5, Math.min(95, percentY));
            studioState.codeX = percentX;
            studioState.codeY = percentY;
        }

        applyStudioStateToDom();
    }

    function onEnd() {
        if (activeDragElement) {
            activeDragElement.classList.remove('is-dragging');
            activeDragElement = null;
        }
        isResizingQr = false;
        document.removeEventListener('mousemove', onMove);
        document.removeEventListener('mouseup', onEnd);
        document.removeEventListener('touchmove', onTouchMove);
        document.removeEventListener('touchend', onEnd);
    }
}

// Slider & Control Action Callbacks
function updateQrSizeFromSlider(val) {
    studioState.qrSize = parseFloat(val);
    applyStudioStateToDom();
}
function updateQrXFromSlider(val) {
    studioState.qrX = parseFloat(val);
    applyStudioStateToDom();
}
function updateQrYFromSlider(val) {
    studioState.qrY = parseFloat(val);
    applyStudioStateToDom();
}
function updateCodeXFromSlider(val) {
    studioState.codeX = parseFloat(val);
    applyStudioStateToDom();
}
function updateCodeYFromSlider(val) {
    studioState.codeY = parseFloat(val);
    applyStudioStateToDom();
}
function updateCodeFontSize(val) {
    studioState.codeSize = parseInt(val);
    applyStudioStateToDom();
}
function updateCodeColor(val) {
    studioState.codeColor = val;
    document.getElementById('codeColorPicker').value = val;
    document.getElementById('codeColorText').value = val;
    applyStudioStateToDom();
}
function updateCodePrefix(val) {
    studioState.codePrefix = val;
    applyStudioStateToDom();
}
function updateCodeFontFamily(val) {
    studioState.codeFont = val;
    applyStudioStateToDom();
}
function updateCodeFontWeight(val) {
    studioState.codeWeight = val;
    applyStudioStateToDom();
}
function toggleCornerBrackets(checked) {
    studioState.showBrackets = checked;
    applyStudioStateToDom();
}
function updateBracketColor(color) {
    studioState.bracketColor = color;
    document.getElementById('bracketColorPicker').value = color;
    document.getElementById('bracketColorText').value = color;
    applyStudioStateToDom();
}
function toggleTopLabelSetting(checked) {
    studioState.showTopLabel = checked;
    applyStudioStateToDom();
}
function updateTopLabelText(text) {
    studioState.topLabelText = text;
    applyStudioStateToDom();
}
function updateTopLabelColor(color) {
    studioState.topLabelColor = color;
    applyStudioStateToDom();
}

// Quick Canvas Alignments
function centerQrOnCanvas() {
    studioState.qrX = (100 - studioState.qrSize) / 2;
    studioState.qrY = (100 - studioState.qrSize) / 2;
    applyStudioStateToDom();
}
function snapCodeUnderQr() {
    studioState.codeX = studioState.qrX + (studioState.qrSize / 2);
    studioState.codeY = studioState.qrY + studioState.qrSize + 7.5;
    applyStudioStateToDom();
}
function resetToSampleCoordinates() {
    studioState.qrX = 63.5;
    studioState.qrY = 65.5;
    studioState.qrSize = 23.0;
    studioState.codeX = 75.0;
    studioState.codeY = 86.8;
    studioState.codePrefix = 'VPM ';
    studioState.bracketColor = '#d9a74a';
    studioState.showBrackets = true;
    studioState.showTopLabel = true;
    studioState.topLabelText = 'Qr Code';
    applyStudioStateToDom();
}

function updateContentTypeFields() {
    const type = document.getElementById('typeSelector').value;
    const destGroup = document.getElementById('urlDestinationGroup');
    if (destGroup) {
        destGroup.style.display = (type === 'website' || type === 'custom_landing') ? 'block' : 'none';
    }
}
</script>
@endpush
