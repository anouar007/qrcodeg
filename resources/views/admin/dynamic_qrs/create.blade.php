@extends('layouts.app')

@section('title', 'Studio Gabarit & Générateur PDF — Dynamic QR Engine')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dynamic-qrs.index') }}">Dynamic QR code</a></li>
    <li class="breadcrumb-item active" aria-current="page">Studio &amp; Générateur</li>
@endsection

@push('styles')
<style>
/* ==========================================================================
   DYNAMIC QR STUDIO DESIGN SYSTEM (YNS NFC / NEO-SAAS)
   ========================================================================== */
:root {
    --dyn-primary: #6320ee;
    --dyn-primary-hover: #5015d8;
    --dyn-primary-light: #f3f0ff;
    --dyn-primary-border: #ddd6fe;
    --dyn-primary-glow: rgba(99, 32, 238, 0.2);
}

.cursor-pointer { cursor: pointer; }
.cursor-grab { cursor: grab !important; user-select: none; }
.cursor-grab:active, .cursor-grab.is-dragging { cursor: grabbing !important; }
.cursor-grab:focus-visible { outline: 2px solid #6320ee !important; outline-offset: 3px; }

/* Pulse animation on alignment dock click */
.stage-pulse {
    animation: stagePulseAnim 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes stagePulseAnim {
    0% { transform: scale(1); }
    50% { transform: scale(0.995); }
    100% { transform: scale(1); }
}

/* Status Dot Radar */
.status-dot-radar {
    position: relative;
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
}
.status-dot-radar::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    border: 1.5px solid #10b981;
    animation: radarRipple 2s cubic-bezier(0.24, 0, 0.38, 1) infinite;
}
@keyframes radarRipple {
    0% { transform: scale(0.6); opacity: 1; }
    100% { transform: scale(2.2); opacity: 0; }
}

/* Studio Nav Pill Switcher Capsule */
.studio-tabs-nav {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 4px;
    display: inline-flex;
    gap: 4px;
    max-width: 620px;
    width: 100%;
}
.studio-tabs-nav .nav-link {
    border-radius: 9px !important;
    font-weight: 600;
    font-size: 0.88rem;
    color: #64748b;
    padding: 0.6rem 1.25rem;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    border: none !important;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.studio-tabs-nav .nav-link:hover {
    color: #0f172a;
}
.studio-tabs-nav .nav-link.active {
    background: #ffffff !important;
    color: #6320ee !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

/* Studio Card */
.studio-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.05);
    overflow: hidden;
}

/* Form Controls & Inputs */
.studio-input {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    font-size: 0.84rem;
    height: 38px;
    border-radius: 10px;
    transition: all 0.2s ease;
}
.studio-input:focus {
    background-color: #ffffff;
    border-color: #6320ee;
    box-shadow: 0 0 0 3px rgba(99, 32, 238, 0.12);
}

/* Clean Pure White Studio Stage */
.studio-canvas-stage {
    background-color: #ffffff !important;
    background-image: none !important;
    position: relative;
    border-radius: 0 0 16px 16px;
    min-height: 480px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: background-color 0.2s ease;
    border-top: 1px solid #f1f5f9;
}
.studio-canvas-stage.drag-over {
    background-color: #f8f6ff !important;
    background-image: none !important;
}

/* Dropzone Overlay Alert */
.dropzone-overlay {
    position: absolute;
    inset: 12px;
    border: 2px dashed #6320ee;
    background: rgba(243, 240, 255, 0.92);
    border-radius: 14px;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 50;
    pointer-events: none;
    backdrop-filter: blur(4px);
    animation: fadeIn 0.15s ease;
}
.dropzone-overlay.show {
    display: flex;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

/* Viewport Stage */
#canvasViewport,
#canvasViewport * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-user-drag: none;
}
#canvasViewport {
    box-shadow: none !important;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    position: relative;
    background: #ffffff;
    overflow: hidden;
    max-width: 100%;
    max-height: 100%;
    transition: width 0.2s cubic-bezier(0.16, 1, 0.3, 1), height 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Figma-Grade Precision Selection System (Zero Shadow Flat Design) */
.stage-element-wrapper {
    touch-action: none;
    user-select: none;
    -webkit-user-select: none;
    transition: outline 0.15s ease;
}
.stage-element-wrapper:focus-visible {
    outline: none !important;
}

/* Crisp hairline selection bounding box */
.cad-selection-box {
    border: 1.5px solid #6320ee;
    border-radius: 4px;
    box-shadow: none !important;
    transition: border-color 0.15s ease;
}
.cad-selection-box:hover,
.stage-element-wrapper:focus .cad-selection-box,
.stage-element-wrapper.is-dragging .cad-selection-box {
    border-color: #4f46e5;
    box-shadow: none !important;
}

/* Floating Clean Label Tag - FLOATS COMPLETELY OUTSIDE, NEVER TOUCHES MODULES */
.cad-floating-tag {
    position: absolute;
    top: -24px;
    left: 50%;
    transform: translateX(-50%);
    background: #0f172a;
    color: #ffffff;
    font-size: 0.62rem;
    font-weight: 600;
    padding: 1.5px 7px;
    border-radius: 4px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    letter-spacing: 0.4px;
    pointer-events: none;
    white-space: nowrap;
    box-shadow: none !important;
    display: flex;
    align-items: center;
    gap: 4px;
    z-index: 25;
}

/* 4 Corner Figma Anchor Nodes */
.cad-node {
    position: absolute;
    width: 7px;
    height: 7px;
    background: #ffffff;
    border: 1.5px solid #6320ee;
    border-radius: 2px;
    box-shadow: none !important;
    pointer-events: none;
    z-index: 20;
}
.cad-node-tl { top: -4.5px; left: -4.5px; }
.cad-node-tr { top: -4.5px; right: -4.5px; }
.cad-node-bl { bottom: -4.5px; left: -4.5px; }

/* Refined NWSE Corner Resize Handle (Sits on Vertex Outside Modules) */
.cad-resize-node {
    position: absolute;
    bottom: -6.5px;
    right: -6.5px;
    width: 14px;
    height: 14px;
    background: #6320ee;
    border: 2px solid #ffffff;
    border-radius: 3px;
    box-shadow: none !important;
    cursor: nwse-resize !important;
    touch-action: none !important;
    z-index: 30;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.15s ease;
}
.cad-resize-node i {
    font-size: 8px;
    color: #ffffff;
    pointer-events: none;
}
.cad-resize-node:hover,
.cad-resize-node.is-resizing {
    transform: scale(1.35);
    background-color: #4338ca;
    box-shadow: none !important;
}

/* Code unique selection box */
#codeVisualContainer {
    border: 1px dashed rgba(99, 32, 238, 0.55);
    border-radius: 4px;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    box-shadow: none !important;
    transition: all 0.15s ease;
}
#codeVisualContainer:hover,
#draggableCode:focus #codeVisualContainer,
#draggableCode.is-dragging #codeVisualContainer {
    border-color: #6320ee;
    box-shadow: none !important;
}

/* Clean Preview Mode (Toggle to hide all inspector boxes/nodes) */
#canvasViewport.clean-preview-mode .cad-selection-box {
    border-color: transparent !important;
    box-shadow: none !important;
}
#canvasViewport.clean-preview-mode #codeVisualContainer {
    border-color: transparent !important;
    box-shadow: none !important;
}
#canvasViewport.clean-preview-mode .cad-floating-tag,
#canvasViewport.clean-preview-mode .cad-node,
#canvasViewport.clean-preview-mode .cad-resize-node {
    display: none !important;
}

/* Alignment Guide Lines */
#guideLineV, #guideLineH {
    border-color: #6320ee !important;
    box-shadow: none !important;
}

/* Presets & Pill Buttons */
.dimension-preset, .qty-pill {
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 8px;
    padding: 0.35rem 0.65rem;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    transition: all 0.15s ease;
}
.dimension-preset:hover, .qty-pill:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #cbd5e1;
}
.dimension-preset.active, .qty-pill.active {
    background-color: #6320ee !important;
    color: #ffffff !important;
    border-color: #6320ee !important;
    box-shadow: 0 2px 8px rgba(99, 32, 238, 0.35);
}

/* SVG Rendering within Box */
#qrSvgWrapper svg {
    width: 100% !important;
    height: 100% !important;
    display: block;
}

/* Quick URL Chips */
.quick-url-chip {
    font-size: 0.72rem;
    padding: 3px 9px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.15s ease;
    font-weight: 500;
}
.quick-url-chip:hover {
    background: #f3f0ff;
    color: #6320ee;
    border-color: #ddd6fe;
    transform: translateY(-1px);
}

/* Range input styling */
input[type="range"].form-range::-webkit-slider-thumb {
    background-color: #6320ee;
    box-shadow: 0 0 0 3px rgba(99, 32, 238, 0.25);
}
input[type="range"].form-range::-moz-range-thumb {
    background-color: #6320ee;
    box-shadow: 0 0 0 3px rgba(99, 32, 238, 0.25);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 1280px;">
    <!-- Breadcrumb & Top Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="status-dot-radar"></span>
                <span class="text-uppercase tracking-wider font-mono text-muted" style="font-size: 0.72rem; font-weight: 700;">
                    Dynamic Telemetry Core &bull; Print Studio Engine
                </span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white shadow-xs" style="width: 38px; height: 38px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); font-size: 1.15rem;">
                    <i class="bi bi-palette2"></i>
                </span>
                <span>Générateur de QR Codes Dynamiques &amp; Studio</span>
            </h1>
            <p class="text-muted small mb-0">
                Définissez la taille de votre support, positionnez le QR et son code unique par glisser-déposer, puis téléchargez directement votre lot en PDF multi-pages.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dynamic-qrs.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-xs" style="border-radius: 10px; font-weight: 600; padding: 0.55rem 1rem;">
                <i class="bi bi-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
        </div>
    </div>

    <!-- Generator Mode Switcher Tabs -->
    <div class="mb-4">
        <div class="studio-tabs-nav" id="generatorTabs" role="tablist">
            <button class="nav-link active flex-grow-1" id="studio-tab" data-bs-toggle="tab" data-bs-target="#studio-pane" type="button" role="tab" aria-selected="true">
                <i class="bi bi-palette2 text-primary"></i>
                <span>Studio Visuel &amp; Export PDF</span>
            </button>
            <button class="nav-link flex-grow-1" id="standard-tab" data-bs-toggle="tab" data-bs-target="#standard-pane" type="button" role="tab" aria-selected="false">
                <i class="bi bi-qr-code"></i>
                <span>Génération Standard Simple</span>
            </button>
        </div>
    </div>

    <div class="tab-content" id="generatorTabContent">
        <!-- ========================================================================= -->
        <!-- 1. STUDIO GABARIT & AUTO-PDF GENERATION                                   -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade show active" id="studio-pane" role="tabpanel">
            <form method="POST" action="{{ route('admin.dynamic-qrs.generate-pdf') }}" id="batchPdfStudioForm" enctype="multipart/form-data" novalidate>
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4 d-flex align-items-start gap-2.5 p-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="fw-bold mb-1">Erreur de validation lors de la génération :</div>
                            <ul class="mb-0 ps-3 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Hidden inputs for coordinates & style passed to Dompdf -->
                <input type="hidden" name="qr_x" id="inputQrX" value="50">
                <input type="hidden" name="qr_y" id="inputQrY" value="47.5">
                <input type="hidden" name="qr_size" id="inputQrSize" value="35">
                <input type="hidden" name="qr_fg" id="inputQrFg" value="#000000">
                <input type="hidden" name="qr_bg" id="inputQrBg" value="#ffffff">

                <input type="hidden" name="show_code" id="inputShowCode" value="1">
                <input type="hidden" name="code_x" id="inputCodeX" value="50">
                <input type="hidden" name="code_y" id="inputCodeY" value="74">
                <input type="hidden" name="code_font_size" id="inputCodeFontSize" value="15">
                <input type="hidden" name="code_color" id="inputCodeColor" value="#0f172a">
                <input type="hidden" name="code_bg" id="inputCodeBg" value="transparent">
                <input type="hidden" name="code_font_weight" id="inputCodeFontWeight" value="bold">
                <input type="hidden" name="code_font_family" id="inputCodeFontFamily" value="monospace">
                <input type="hidden" name="code_letter_spacing" id="inputCodeLetterSpacing" value="1.5">
                <input type="hidden" name="stage_width" id="inputStageWidth" value="460">
                <input type="hidden" name="stage_height" id="inputStageHeight" value="460">

                <input type="hidden" name="existing_template_image" id="inputExistingTemplateImage" value="images/templates/google_review_plaque.jpg">

                <div class="row g-4">
                    <!-- LEFT COLUMN: Canvas / Interactive Drag & Drop Stage -->
                    <div class="col-xl-7 col-lg-6">
                        <!-- Step 1 & 2: Canvas Size & Upload Bar -->
                        <div class="studio-card mb-3 p-3">
                            <!-- Dimensions Settings Header -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background-color: #f3f0ff; color: #6320ee; font-size: 0.75rem; border-radius: 6px;">1</span>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">Dimensions du support physique</h6>
                                </div>
                                <!-- Preset Pills & Ratio Indicator -->
                                <div class="d-flex flex-wrap align-items-center gap-1.5">
                                    <button type="button" class="dimension-preset active" data-unit="px" data-w="300" data-h="300">
                                        300×300 px
                                    </button>
                                    <button type="button" class="dimension-preset" data-unit="cm" data-w="30" data-h="30">
                                        30×30 cm
                                    </button>
                                    <button type="button" class="dimension-preset" data-unit="cm" data-w="8.5" data-h="5.4">
                                        Carte 8.5×5.4 cm
                                    </button>
                                    <button type="button" class="dimension-preset" data-unit="cm" data-w="10.5" data-h="14.8">
                                        Plaque A6
                                    </button>
                                    <button type="button" class="dimension-preset" data-unit="cm" data-w="21" data-h="29.7">
                                        A4
                                    </button>
                                    <span class="badge bg-light text-secondary border font-mono small py-1 px-2" id="canvasRatioBadge" style="font-size: 0.72rem; border-radius: 6px;">
                                        Ratio 1:1.00
                                    </span>
                                </div>
                            </div>

                            <!-- Custom Inputs: Unit, Width, Height Perfectly Baseline Aligned -->
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-6 col-sm-3">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Unité</label>
                                    <select name="canvas_unit" id="canvasUnitSelect" class="form-select studio-input">
                                        <option value="px" selected>Pixels (px)</option>
                                        <option value="cm">Centimètres (cm)</option>
                                        <option value="mm">Millimètres (mm)</option>
                                    </select>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Largeur (Width)</label>
                                    <div class="input-group">
                                        <input type="number" step="any" min="1" name="canvas_width" id="canvasWidthInput" class="form-control studio-input" value="300" required style="border-radius: 10px 0 0 10px;">
                                        <span class="input-group-text unit-indicator small bg-light text-muted" style="border-radius: 0 10px 10px 0; border-color: #e2e8f0; height: 38px;">px</span>
                                    </div>
                                </div>
                                <div class="col-9 col-sm-4">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Hauteur (Height)</label>
                                    <div class="input-group">
                                        <input type="number" step="any" min="1" name="canvas_height" id="canvasHeightInput" class="form-control studio-input" value="300" required style="border-radius: 10px 0 0 10px;">
                                        <span class="input-group-text unit-indicator small bg-light text-muted" style="border-radius: 0 10px 10px 0; border-color: #e2e8f0; height: 38px;">px</span>
                                    </div>
                                </div>
                                <div class="col-3 col-sm-1">
                                    <label class="form-label small text-muted mb-1 d-none d-sm-block" style="font-size: 0.75rem; visibility: hidden;">Inverser</label>
                                    <button type="button" class="btn btn-light border w-100 d-flex align-items-center justify-content-center" id="btnSwapDimensions" title="Inverser orientation (Paysage / Portrait)" style="height: 38px; border-radius: 10px;">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Design Upload Area -->
                            <div class="border rounded-3 p-2.5 bg-light-subtle d-flex flex-wrap align-items-center justify-content-between gap-2" id="uploadZoneBox" style="border-style: dashed !important; border-color: #cbd5e1 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center bg-white border shadow-xs" style="width: 40px; height: 40px; color: #6320ee; flex-shrink: 0;">
                                        <i class="bi bi-image fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="fw-semibold text-dark small" id="templateNameDisplay">Gabarit : Plaque Avis Google (Prêt à l'emploi)</div>
                                            <span class="badge bg-primary-subtle text-primary font-mono d-none" id="imgDetectedDimBadge" style="font-size: 0.68rem;"></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted" style="font-size: 0.72rem;">PNG, JPG, WebP ou SVG &bull; Glissez-déposez directement sur le canevas</small>
                                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none d-none" id="btnAdoptImgDimensions" style="font-size: 0.72rem; color: #6320ee; font-weight: 600;">
                                                <i class="bi bi-aspect-ratio me-0.5"></i>Adapter taille canevas
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <label class="btn btn-sm text-white mb-0 cursor-pointer d-inline-flex align-items-center gap-1.5 shadow-xs" style="background-color: #6320ee; border-radius: 8px; font-size: 0.82rem; font-weight: 600; padding: 0.4rem 0.85rem;">
                                        <i class="bi bi-upload"></i>
                                        <span>Changer l'image</span>
                                        <input type="file" name="template_image" id="templateFileInput" class="d-none" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" id="btnBlankCanvas" title="Utiliser un canevas blanc uni sans image" style="border-radius: 8px; font-size: 0.82rem;">
                                        <i class="bi bi-square"></i>
                                        <span>Fond Blanc</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetToSample" title="Restaurer exemple Google" style="border-radius: 8px; font-size: 0.82rem;">
                                        Exemple
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Interactive Canvas Stage -->
                        <div class="studio-card mb-4">
                            <div class="card-header bg-white border-0 py-3 px-3.5 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background-color: #f3f0ff; color: #6320ee; font-size: 0.75rem; border-radius: 6px;">2</span>
                                    <span class="fw-bold text-dark small">Atelier Interactif (Glisser-Déposer &amp; Alignement Pro)</span>
                                </div>
                                <!-- Pro Alignment Toolbar -->
                                <div class="d-flex flex-wrap align-items-center gap-1.5">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Alignement QR">
                                        <button type="button" class="btn btn-light border py-1 px-2.5 d-flex align-items-center gap-1" id="btnCenterQrH" title="Centrer QR horizontalement (X: 50%)" style="border-radius: 8px 0 0 8px; font-size: 0.75rem; height: 32px;">
                                            <i class="bi bi-align-center text-primary"></i>
                                            <span>QR H</span>
                                        </button>
                                        <button type="button" class="btn btn-light border py-1 px-2.5 d-flex align-items-center gap-1" id="btnCenterQrV" title="Centrer QR verticalement au milieu (Y: 50%)" style="border-radius: 0; font-size: 0.75rem; height: 32px;">
                                            <i class="bi bi-align-middle text-primary"></i>
                                            <span>QR V</span>
                                        </button>
                                        <button type="button" class="btn btn-light border py-1 px-2.5 d-flex align-items-center gap-1" id="btnCenterQrBoth" title="Centrer QR au milieu total (50% / 50%)" style="border-radius: 0 8px 8px 0; font-size: 0.75rem; height: 32px;">
                                            <i class="bi bi-bullseye text-primary"></i>
                                            <span>Milieu</span>
                                        </button>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Alignement Code">
                                        <button type="button" class="btn btn-light border py-1 px-2.5 d-flex align-items-center gap-1" id="btnCenterCodeH" title="Centrer Code horizontalement (X: 50%)" style="border-radius: 8px 0 0 8px; font-size: 0.75rem; height: 32px;">
                                            <i class="bi bi-text-center text-primary"></i>
                                            <span>Code H</span>
                                        </button>
                                        <button type="button" class="btn btn-light border py-1 px-2.5 d-flex align-items-center gap-1" id="btnCenterCodeV" title="Centrer Code verticalement (Y: 50%)" style="border-radius: 0 8px 8px 0; font-size: 0.75rem; height: 32px;">
                                            <i class="bi bi-align-middle text-primary"></i>
                                            <span>Code V</span>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-sm py-1 px-2.5 fw-semibold d-flex align-items-center gap-1.5 shadow-xs" id="btnAlignEverything" title="Alignement optique parfait selon le cadre de la plaque" style="background: #f3f0ff; color: #6320ee; border: 1px solid #ddd6fe; border-radius: 8px; font-size: 0.75rem; height: 32px;">
                                        <i class="bi bi-stars"></i>
                                        <span>Aligner Tout</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2.5 fw-semibold d-flex align-items-center gap-1.5 shadow-xs" id="btnToggleCleanPreview" title="Masquer / Afficher les repères pour voir le rendu final pur" style="border-radius: 8px; font-size: 0.75rem; height: 32px;">
                                        <i class="bi bi-eye" id="iconCleanPreview"></i>
                                        <span id="lblCleanPreviewText">Aperçu Net</span>
                                    </button>
                                </div>
                            </div>

                            <div class="studio-canvas-stage position-relative" id="canvasDropStage">
                                <!-- Dropzone Drag Overlay -->
                                <div class="dropzone-overlay" id="dropzoneOverlay">
                                    <i class="bi bi-cloud-arrow-up-fill text-primary display-4 mb-2"></i>
                                    <h6 class="fw-bold text-dark mb-1">Déposez votre visuel ici</h6>
                                    <p class="small text-muted mb-0">PNG, JPG, WebP ou SVG</p>
                                </div>

                                <!-- Scalable Stage Wrapper -->
                                <div id="canvasViewport" style="width: 380px; height: 380px;">
                                    <!-- Background Template Image -->
                                    <img id="canvasBgImage" src="{{ asset('images/templates/google_review_plaque.jpg') }}" alt="Design Template" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover; pointer-events: none; z-index: 1;" draggable="false">

                                    <!-- Smart Alignment Guides (Light up on Magnetic Snap) -->
                                    <div id="guideLineV" class="position-absolute top-0 bottom-0" style="left: 50%; width: 1px; border-left: 1.5px dashed #6320ee; display: none; z-index: 8; pointer-events: none;"></div>
                                    <div id="guideLineH" class="position-absolute start-0 end-0" style="top: 50%; height: 1px; border-top: 1.5px dashed #6320ee; display: none; z-index: 8; pointer-events: none;"></div>

                                    <!-- Movable QR Code Box (1:1 Ratio) Aligned to Plaque Golden Frame -->
                                    <div id="draggableQr" class="position-absolute cursor-grab d-flex align-items-center justify-content-center stage-element-wrapper" 
                                         tabindex="0" role="region" aria-label="Zone QR interactive" title="Zone QR (Glisser pour déplacer • Flèches du clavier pour micro-ajustement)"
                                         style="z-index: 10; left: 50%; top: 47.5%; width: 35%; aspect-ratio: 1 / 1; height: auto; transform: translate(-50%, -50%); touch-action: none;">
                                        <div class="qr-content-wrapper position-relative w-100 h-100 d-flex align-items-center justify-content-center p-0 cad-selection-box" 
                                             id="qrVisualContainer" style="background: #ffffff; aspect-ratio: 1 / 1; pointer-events: auto;">
                                            
                                            <!-- Floating Clean Label Tag - FLOATS COMPLETELY OUTSIDE, NEVER OVERLAPS -->
                                            <div class="cad-floating-tag" id="tagQrLabel">
                                                <i class="bi bi-qr-code"></i>
                                                <span>QR Code</span>
                                                <span class="opacity-75 font-mono" id="tagQrSizePct">35%</span>
                                            </div>

                                            <div id="qrSvgWrapper" class="w-100 h-100 d-flex align-items-center justify-content-center" style="pointer-events: none; overflow: hidden; border-radius: 2px;">
                                                @if(!empty($sampleQrSvg))
                                                    {!! $sampleQrSvg !!}
                                                @else
                                                    <img id="qrPreviewImg" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://example.com" alt="QR Code" class="w-100 h-100 object-fit-contain" draggable="false">
                                                @endif
                                            </div>

                                            <!-- CAD 4-Corner Anchors (Figma Style, Sits Outside) -->
                                            <div class="cad-node cad-node-tl"></div>
                                            <div class="cad-node cad-node-tr"></div>
                                            <div class="cad-node cad-node-bl"></div>

                                            <!-- Refined Corner Resize Handle (Sits on Vertex Outside Modules) -->
                                            <div id="qrResizeHandle" class="cad-resize-node" title="Glisser pour redimensionner">
                                                <i class="bi bi-arrows-angle-expand"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Movable Unique Code Box Aligned Below Frame -->
                                    <div id="draggableCode" class="position-absolute cursor-grab stage-element-wrapper" 
                                         tabindex="0" role="region" aria-label="Zone Code Unique interactive" title="Zone Code Unique (Glisser pour déplacer • Flèches du clavier pour micro-ajustement)"
                                         style="z-index: 12; left: 50%; top: 74%; transform: translate(-50%, -50%); touch-action: none; pointer-events: auto;">
                                        <div id="codeVisualContainer" class="px-2.5 py-1 rounded-2 border d-flex align-items-center justify-content-center position-relative" 
                                             style="background: transparent; pointer-events: auto;">
                                            
                                            <!-- Floating Clean Label Tag - Outside, Never Overlaps Content -->
                                            <div class="cad-floating-tag" id="tagCodeLabel" style="top: -22px;">
                                                <i class="bi bi-tag-fill"></i>
                                                <span>Code Unique</span>
                                                <span class="opacity-75 font-mono" id="tagCodeSizePt">15pt</span>
                                            </div>

                                            <span id="codeDisplayText" class="font-mono fw-bold" style="font-size: 15px; color: #0f172a; letter-spacing: 1.5px; pointer-events: none;">
                                                VP-984210
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Coordinates Status Footer -->
                            <div class="card-footer bg-white border-top p-2.5 px-3.5 d-flex flex-wrap align-items-center justify-content-between text-muted small" style="font-size: 0.76rem;">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <i class="bi bi-crosshair me-1 text-primary"></i>
                                        <span>QR: <strong id="lblQrCoords" class="text-dark">X: 50% | Y: 47.5% | Taille: 35%</strong></span>
                                    </div>
                                    <span class="text-secondary opacity-50">&bull;</span>
                                    <div>
                                        <i class="bi bi-type me-1 text-primary"></i>
                                        <span>Code: <strong id="lblCodeCoords" class="text-dark">X: 50% | Y: 74% | Police: 15pt</strong></span>
                                    </div>
                                </div>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    <i class="bi bi-magnet text-primary me-1"></i>Magnétisme &amp; alignement actif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Elements Styling, Quantity, Redirection & Action -->
                    <div class="col-xl-5 col-lg-6">
                        <!-- Step 3: Styling Options for QR and Code -->
                        <div class="studio-card mb-3 p-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background-color: #f3f0ff; color: #6320ee; font-size: 0.75rem; border-radius: 6px;">3</span>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">Personnalisation du QR &amp; du Code</h6>
                                </div>
                                <button class="btn btn-link btn-sm text-decoration-none p-0 text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#fineTuneCoords" aria-expanded="false" style="font-size: 0.74rem;">
                                    <i class="bi bi-sliders me-1"></i>Coordonnées numériques
                                </button>
                            </div>

                            <!-- Collapsible Fine Numerical Tuning -->
                            <div class="collapse mb-3" id="fineTuneCoords">
                                <div class="p-2.5 bg-light rounded-3 border">
                                    <div class="small fw-semibold text-dark mb-1.5" style="font-size: 0.74rem;">Coordonnées exactes (%) :</div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white small" style="font-size: 0.72rem;">QR X%</span>
                                                <input type="number" step="any" min="5" max="95" class="form-control" id="numQrX" value="50">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white small" style="font-size: 0.72rem;">QR Y%</span>
                                                <input type="number" step="any" min="5" max="95" class="form-control" id="numQrY" value="47.5">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white small" style="font-size: 0.72rem;">Code X%</span>
                                                <input type="number" step="any" min="5" max="95" class="form-control" id="numCodeX" value="50">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white small" style="font-size: 0.72rem;">Code Y%</span>
                                                <input type="number" step="any" min="5" max="95" class="form-control" id="numCodeY" value="74">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Size & Colors -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-semibold text-dark mb-0">Taille du QR code</label>
                                    <span class="small font-mono fw-semibold text-primary" id="lblQrSizeVal">35%</span>
                                </div>
                                <input type="range" class="form-range" id="sliderQrSize" min="15" max="80" value="35">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Couleur du QR</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" id="pickerQrFg" class="form-control form-control-color p-0 border-0" value="#000000" style="width: 34px; height: 34px; border-radius: 8px;" title="Couleur des modules QR">
                                        <span class="small font-mono text-dark" id="lblQrFgHex">#000000</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Fond du QR</label>
                                    <select id="selectQrBg" class="form-select studio-input">
                                        <option value="#ffffff" selected>Blanc solide</option>
                                        <option value="transparent">Transparent</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-3 opacity-10">

                            <!-- Code Unique Options -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="chkShowCode" checked>
                                    <label class="form-check-label fw-semibold text-dark small cursor-pointer" for="chkShowCode">
                                        Afficher le Code Unique (VP-)
                                    </label>
                                </div>
                            </div>

                            <div id="codeConfigOptions">
                                <div class="mb-2.5">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">Taille de police du code</label>
                                        <span class="small text-muted font-mono" id="lblCodeSizeVal">15 pt</span>
                                    </div>
                                    <input type="range" class="form-range" id="sliderCodeSize" min="8" max="42" value="15">
                                </div>

                                <div class="mb-2.5">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">Espacement des lettres</label>
                                        <span class="small text-muted font-mono" id="lblCodeLetterSpacingVal">1.5 pt</span>
                                    </div>
                                    <input type="range" class="form-range" id="sliderCodeLetterSpacing" min="0" max="6" step="0.5" value="1.5">
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Couleur du texte</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" id="pickerCodeColor" class="form-control form-control-color p-0 border-0" value="#0f172a" style="width: 32px; height: 32px; border-radius: 8px;">
                                            <span class="small font-mono text-dark" id="lblCodeColorHex">#0f172a</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Fond du texte</label>
                                        <select id="selectCodeBg" class="form-select studio-input">
                                            <option value="transparent" selected>Transparent</option>
                                            <option value="#ffffff">Pastille blanche</option>
                                            <option value="#f8fafc">Pastille grise</option>
                                            <option value="#0f172a">Pastille noire</option>
                                            <option value="#f3f0ff">Pastille violette</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Police</label>
                                        <select id="selectCodeFontFamily" class="form-select studio-input">
                                            <option value="monospace" selected>Monospace (Tech)</option>
                                            <option value="sans-serif">Sans-Serif (Moderne)</option>
                                            <option value="serif">Serif (Élégant)</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Graisse</label>
                                        <select id="selectCodeFontWeight" class="form-select studio-input">
                                            <option value="bold" selected>Gras (Bold 700)</option>
                                            <option value="600">Semi-Gras (600)</option>
                                            <option value="normal">Normal (400)</option>
                                            <option value="800">Extra Gras (800)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Quantity, URL & Instant PDF Download Action -->
                        <div class="studio-card p-3.5">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge" style="background-color: #f3f0ff; color: #6320ee; font-size: 0.75rem; border-radius: 6px;">4</span>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">Paramètres du lot &amp; Destination</h6>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark mb-1">Nombre de QR codes à générer <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-1.5 mb-2">
                                    <button type="button" class="qty-pill" data-qty="1">1</button>
                                    <button type="button" class="qty-pill active" data-qty="5">5</button>
                                    <button type="button" class="qty-pill" data-qty="10">10</button>
                                    <button type="button" class="qty-pill" data-qty="25">25</button>
                                    <button type="button" class="qty-pill" data-qty="50">50</button>
                                    <button type="button" class="qty-pill" data-qty="100">100</button>
                                </div>
                                <input type="number" min="1" max="100" name="quantity" id="quantityInput" class="form-control studio-input" value="5" required>
                                <div class="form-text small" style="font-size: 0.74rem;">Un code unique "VP-XXXXXX" sera assigné à chaque page du PDF.</div>
                            </div>

                            <!-- Label Prefix -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark mb-1">Préfixe du libellé <span class="text-danger">*</span></label>
                                <input type="text" name="label_prefix" class="form-control studio-input" value="Plaque Avis Client" placeholder="ex: Chevalet Table, Plaque Avis Google, Carte VIP" required>
                            </div>

                            <!-- Target Destination URL -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark mb-1">URL de redirection (Destination) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px; border-color: #e2e8f0;"><i class="bi bi-link-45deg"></i></span>
                                    <input type="text" name="target_url" id="targetUrlInputStudio" class="form-control studio-input" value="https://g.page/r/sample-business/review" placeholder="https://..." required style="border-radius: 0 10px 10px 0;">
                                </div>
                                <div class="form-text small text-muted mt-2">
                                    Raccourcis :
                                    <div class="d-inline-flex flex-wrap gap-1 mt-1">
                                        <span class="quick-url-chip" onclick="setStudioUrl('https://')">https://</span>
                                        <span class="quick-url-chip" onclick="setStudioUrl('https://g.page/r/')"><i class="bi bi-google text-danger"></i> Avis Google</span>
                                        <span class="quick-url-chip" onclick="setStudioUrl('https://instagram.com/')"><i class="bi bi-instagram text-primary"></i> Instagram</span>
                                        <span class="quick-url-chip" onclick="setStudioUrl('https://wa.me/')"><i class="bi bi-whatsapp text-success"></i> WhatsApp</span>
                                        <span class="quick-url-chip" onclick="setStudioUrl('https://tiktok.com/@')"><i class="bi bi-tiktok text-dark"></i> TikTok</span>
                                        <span class="quick-url-chip" onclick="setStudioUrl('https://trustpilot.com/review/')"><i class="bi bi-star-fill text-warning"></i> Trustpilot</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Client & Style Selection -->
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.72rem;">Assigner à un client</label>
                                    <select name="client_id" class="form-select studio-input">
                                        <option value="">Général (Aucun)</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.72rem;">Style de code</label>
                                    <select name="code_style" class="form-select studio-input">
                                        <option value="unique" selected>Aléatoire (VP-XXXXXX)</option>
                                        <option value="sequential">Séquentiel (VP-000001)</option>
                                        <option value="numeric">Chiffres (VP-123456)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dynamic Alert Status Placeholder -->
                            <div id="studioAlertArea" class="mb-3"></div>

                            <!-- SUBMIT BUTTON WITH PURPLE GRADIENT -->
                            <button type="submit" id="btnSubmitGeneratePdf" form="batchPdfStudioForm" class="btn text-white w-100 fw-bold py-2.5 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); border-radius: 12px; font-size: 0.96rem;">
                                <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                <span id="btnSubmitText">Générer &amp; Télécharger le PDF (5 pages)</span>
                            </button>
                            <div class="text-center mt-2">
                                <small class="text-muted" style="font-size: 0.72rem;">
                                    <i class="bi bi-shield-check text-success me-1"></i>Enregistre les codes dans le système et lance le téléchargement automatique.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- ========================================================================= -->
        <!-- 2. STANDARD GENERATION PANE (CLASSIC SINGLE DYNAMIC QR)                   -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade" id="standard-pane" role="tabpanel">
            <div class="studio-card p-4 mx-auto" style="max-width: 780px;">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-xs" style="width: 44px; height: 44px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                        <i class="bi bi-qr-code fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0.5">Création de QR Code Dynamique Standard</h5>
                        <p class="text-muted small mb-0">Créez un identifiant permanent avec redirection instantanée et gabarit de support standard.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.dynamic-qrs.store') }}">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Assigner à un client</label>
                            <select name="client_id" class="form-select studio-input">
                                <option value="">Général (Aucun client)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Libellé du QR <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control studio-input" placeholder="ex: Stand VIP, Réception Hôtel" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">URL de destination <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px; border-color: #e2e8f0;"><i class="bi bi-link-45deg"></i></span>
                                <input type="text" name="target_url" id="targetUrlInputStandard" class="form-control studio-input" placeholder="https://..." required style="border-radius: 0 10px 10px 0;">
                            </div>
                            <div class="form-text small text-muted mt-2">
                                Raccourcis :
                                <div class="d-inline-flex flex-wrap gap-1 mt-1">
                                    <span class="quick-url-chip" onclick="setStandardUrl('https://')">https://</span>
                                    <span class="quick-url-chip" onclick="setStandardUrl('https://g.page/r/')"><i class="bi bi-google text-danger"></i> Avis Google</span>
                                    <span class="quick-url-chip" onclick="setStandardUrl('https://instagram.com/')"><i class="bi bi-instagram text-primary"></i> Instagram</span>
                                    <span class="quick-url-chip" onclick="setStandardUrl('https://wa.me/')"><i class="bi bi-whatsapp text-success"></i> WhatsApp</span>
                                    <span class="quick-url-chip" onclick="setStandardUrl('https://tiktok.com/@')"><i class="bi bi-tiktok text-dark"></i> TikTok</span>
                                    <span class="quick-url-chip" onclick="setStandardUrl('https://trustpilot.com/review/')"><i class="bi bi-star-fill text-warning"></i> Trustpilot</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Gabarit d'impression</label>
                            <select name="template_type" class="form-select studio-input" required>
                                <option value="google_review" selected>Avis Google (5 étoiles)</option>
                                <option value="social_media">Réseaux Sociaux</option>
                                <option value="menu">Menu Digital</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Statut initial</label>
                            <select name="status" class="form-select studio-input">
                                <option value="active" selected>Actif (Routage immédiat)</option>
                                <option value="inactive">Inactif (En attente)</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.dynamic-qrs.index') }}" class="btn btn-light border px-4" style="border-radius: 10px;">Annuler</a>
                        <button type="submit" class="btn text-white px-4 fw-semibold shadow-xs" style="background-color: #6320ee; border-radius: 10px; height: 38px;">
                            <i class="bi bi-check-lg me-1"></i> Créer le QR Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Stage Elements
    const stageContainer = document.getElementById('canvasDropStage');
    const viewport = document.getElementById('canvasViewport');
    const bgImage = document.getElementById('canvasBgImage');
    const draggableQr = document.getElementById('draggableQr');
    const qrContainer = document.getElementById('qrVisualContainer');
    const draggableCode = document.getElementById('draggableCode');
    const codeContainer = document.getElementById('codeVisualContainer');
    const codeText = document.getElementById('codeDisplayText');

    // Controls
    const unitSelect = document.getElementById('canvasUnitSelect');
    const widthInput = document.getElementById('canvasWidthInput');
    const heightInput = document.getElementById('canvasHeightInput');
    const unitIndicators = document.querySelectorAll('.unit-indicator');
    const fileInput = document.getElementById('templateFileInput');
    const templateNameDisplay = document.getElementById('templateNameDisplay');
    const btnResetToSample = document.getElementById('btnResetToSample');

    // QR Controls
    const sliderQrSize = document.getElementById('sliderQrSize');
    const lblQrSizeVal = document.getElementById('lblQrSizeVal');
    const pickerQrFg = document.getElementById('pickerQrFg');
    const lblQrFgHex = document.getElementById('lblQrFgHex');
    const selectQrBg = document.getElementById('selectQrBg');
    const qrResizeHandle = document.getElementById('qrResizeHandle');

    // Code Controls
    const chkShowCode = document.getElementById('chkShowCode');
    const codeOptionsWrapper = document.getElementById('codeConfigOptions');
    const sliderCodeSize = document.getElementById('sliderCodeSize');
    const lblCodeSizeVal = document.getElementById('lblCodeSizeVal');
    const pickerCodeColor = document.getElementById('pickerCodeColor');
    const lblCodeColorHex = document.getElementById('lblCodeColorHex');
    const selectCodeBg = document.getElementById('selectCodeBg');

    // Quantity & Button
    const quantityInput = document.getElementById('quantityInput');
    const btnSubmitText = document.getElementById('btnSubmitText');

    // Hidden Inputs
    const inputQrX = document.getElementById('inputQrX');
    const inputQrY = document.getElementById('inputQrY');
    const inputQrSize = document.getElementById('inputQrSize');
    const inputQrFg = document.getElementById('inputQrFg');
    const inputQrBg = document.getElementById('inputQrBg');

    const inputShowCode = document.getElementById('inputShowCode');
    const inputCodeX = document.getElementById('inputCodeX');
    const inputCodeY = document.getElementById('inputCodeY');
    const inputCodeFontSize = document.getElementById('inputCodeFontSize');
    const inputCodeColor = document.getElementById('inputCodeColor');
    const inputCodeBg = document.getElementById('inputCodeBg');

    const lblQrCoords = document.getElementById('lblQrCoords');
    const lblCodeCoords = document.getElementById('lblCodeCoords');

    // Magnetic Snap Guides
    const guideLineV = document.getElementById('guideLineV');
    const guideLineH = document.getElementById('guideLineH');

    // Fine Tuning Inputs
    const numQrX = document.getElementById('numQrX');
    const numQrY = document.getElementById('numQrY');
    const numCodeX = document.getElementById('numCodeX');
    const numCodeY = document.getElementById('numCodeY');

    // Font family & weight
    const selectCodeFontFamily = document.getElementById('selectCodeFontFamily');
    const selectCodeFontWeight = document.getElementById('selectCodeFontWeight');
    const inputCodeFontFamily = document.getElementById('inputCodeFontFamily');
    const inputCodeFontWeight = document.getElementById('inputCodeFontWeight');

    // Alignment Buttons
    const btnCenterQrH = document.getElementById('btnCenterQrH');
    const btnCenterQrV = document.getElementById('btnCenterQrV');
    const btnCenterQrBoth = document.getElementById('btnCenterQrBoth');
    const btnCenterCodeH = document.getElementById('btnCenterCodeH');
    const btnCenterCodeV = document.getElementById('btnCenterCodeV');
    const btnAlignEverything = document.getElementById('btnAlignEverything');

    function syncPresetActive() {
        const curU = unitSelect.value;
        const curW = parseFloat(widthInput.value) || 0;
        const curH = parseFloat(heightInput.value) || 0;
        document.querySelectorAll('.dimension-preset').forEach(b => {
            const match = b.dataset.unit === curU &&
                          Math.abs(parseFloat(b.dataset.w) - curW) < 0.05 &&
                          Math.abs(parseFloat(b.dataset.h) - curH) < 0.05;
            b.classList.toggle('active', match);
        });
    }

    // 1. Dynamic Canvas Responsive Aspect Ratio Calculation
    function updateCanvasAspect() {
        const w = parseFloat(widthInput.value) || 300;
        const h = parseFloat(heightInput.value) || 300;

        // Adapt max dimension to container width to prevent mobile/tablet overflow
        const availWidth = stageContainer ? Math.max(260, stageContainer.clientWidth - 48) : 420;
        const maxStageDim = Math.min(460, availWidth);

        let renderW, renderH;
        if (w >= h) {
            renderW = maxStageDim;
            renderH = Math.max(160, Math.round((h / w) * maxStageDim));
        } else {
            renderH = maxStageDim;
            renderW = Math.max(160, Math.round((w / h) * maxStageDim));
        }

        viewport.style.width = renderW + 'px';
        viewport.style.height = renderH + 'px';

        const stageWInput = document.getElementById('inputStageWidth');
        const stageHInput = document.getElementById('inputStageHeight');
        if (stageWInput) stageWInput.value = renderW;
        if (stageHInput) stageHInput.value = renderH;

        const ratioBadge = document.getElementById('canvasRatioBadge');
        if (ratioBadge) {
            if (Math.abs(w - h) < 0.01) {
                ratioBadge.textContent = 'Carré (1:1)';
            } else if (w > h) {
                ratioBadge.textContent = `Paysage (${(w / h).toFixed(2)}:1)`;
            } else {
                ratioBadge.textContent = `Portrait (1:${(h / w).toFixed(2)})`;
            }
        }
        syncPresetActive();
    }

    widthInput.addEventListener('input', updateCanvasAspect);
    heightInput.addEventListener('input', updateCanvasAspect);
    window.addEventListener('resize', updateCanvasAspect);

    unitSelect.addEventListener('change', function () {
        const u = this.value;
        unitIndicators.forEach(ind => ind.textContent = u);
    });

    document.getElementById('btnSwapDimensions').addEventListener('click', function () {
        const temp = widthInput.value;
        widthInput.value = heightInput.value;
        heightInput.value = temp;
        updateCanvasAspect();
    });

    // Dimension Preset buttons
    document.querySelectorAll('.dimension-preset').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.dimension-preset').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const u = this.dataset.unit;
            const w = this.dataset.w;
            const h = this.dataset.h;

            unitSelect.value = u;
            unitIndicators.forEach(ind => ind.textContent = u);
            widthInput.value = w;
            heightInput.value = h;
            updateCanvasAspect();
        });
    });

    // 2. Upload Design / Template File Preview with Dimension Detection
    function handleImageFile(file) {
        if (!file) return;
        templateNameDisplay.textContent = 'Gabarit : ' + file.name;
        const reader = new FileReader();
        reader.onload = function (event) {
            bgImage.src = event.target.result;

            const tempImg = new Image();
            tempImg.onload = function() {
                const natW = tempImg.naturalWidth;
                const natH = tempImg.naturalHeight;
                const badge = document.getElementById('imgDetectedDimBadge');
                if (badge) {
                    badge.textContent = `${natW} × ${natH} px`;
                    badge.classList.remove('d-none');
                }
                const btnAdopt = document.getElementById('btnAdoptImgDimensions');
                if (btnAdopt) {
                    btnAdopt.classList.remove('d-none');
                    btnAdopt.onclick = function() {
                        unitSelect.value = 'px';
                        unitIndicators.forEach(ind => ind.textContent = 'px');
                        widthInput.value = natW;
                        heightInput.value = natH;
                        document.querySelectorAll('.dimension-preset').forEach(b => b.classList.remove('active'));
                        updateCanvasAspect();
                        btnAdopt.classList.add('d-none');
                    };
                }
            };
            tempImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
        document.getElementById('inputExistingTemplateImage').value = '';
    }

    fileInput.addEventListener('change', function (e) {
        handleImageFile(e.target.files[0]);
    });

    // Drag and Drop File directly onto Canvas Stage
    const dropzoneOverlay = document.getElementById('dropzoneOverlay');
    if (stageContainer) {
        ['dragenter', 'dragover'].forEach(eventName => {
            stageContainer.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                stageContainer.classList.add('drag-over');
                if (dropzoneOverlay) dropzoneOverlay.classList.add('show');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            stageContainer.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                stageContainer.classList.remove('drag-over');
                if (dropzoneOverlay) dropzoneOverlay.classList.remove('show');
            }, false);
        });

        stageContainer.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                const file = files[0];
                if (file.type.match(/^image\//)) {
                    // Update file input if DataTransfer is supported
                    try {
                        fileInput.files = files;
                    } catch(err) {}
                    handleImageFile(file);
                }
            }
        }, false);
    }

    const btnBlankCanvas = document.getElementById('btnBlankCanvas');
    btnBlankCanvas?.addEventListener('click', function () {
        bgImage.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10" fill="white"/></svg>';
        templateNameDisplay.textContent = 'Gabarit : Canevas Blanc Uni';
        document.getElementById('inputExistingTemplateImage').value = 'none';
        fileInput.value = '';
        btnBlankCanvas.classList.add('btn-primary', 'text-white');
        btnBlankCanvas.classList.remove('btn-outline-secondary');
        btnResetToSample?.classList.remove('btn-primary', 'text-white');
        btnResetToSample?.classList.add('btn-outline-secondary');
        const badge = document.getElementById('imgDetectedDimBadge');
        if (badge) badge.classList.add('d-none');
        const btnAdopt = document.getElementById('btnAdoptImgDimensions');
        if (btnAdopt) btnAdopt.classList.add('d-none');
    });

    btnResetToSample.addEventListener('click', function () {
        bgImage.src = "{{ asset('images/templates/google_review_plaque.jpg') }}";
        templateNameDisplay.textContent = 'Gabarit : Plaque Avis Google (Prêt à l\'emploi)';
        document.getElementById('inputExistingTemplateImage').value = 'images/templates/google_review_plaque.jpg';
        fileInput.value = '';
        btnBlankCanvas?.classList.remove('btn-primary', 'text-white');
        btnBlankCanvas?.classList.add('btn-outline-secondary');
        const badge = document.getElementById('imgDetectedDimBadge');
        if (badge) badge.classList.add('d-none');
        const btnAdopt = document.getElementById('btnAdoptImgDimensions');
        if (btnAdopt) btnAdopt.classList.add('d-none');

        // Snap elements perfectly to plaque designated frame
        draggableQr.style.left = '50%';
        draggableQr.style.top = '47.5%';
        draggableQr.style.width = '35%';
        inputQrX.value = '50';
        inputQrY.value = '47.5';
        inputQrSize.value = '35';
        sliderQrSize.value = '35';
        lblQrSizeVal.textContent = '35%';
        const tagQr = document.getElementById('tagQrSizePct');
        if (tagQr) tagQr.textContent = '35%';

        draggableCode.style.left = '50%';
        draggableCode.style.top = '74%';
        inputCodeX.value = '50';
        inputCodeY.value = '74';

        updateCoordLabels();
        triggerStagePulse();
    });

    // 3. Precision Drag & Drop Engine with Magnetic Snap
    function setupDraggable(element, onMoveCallback) {
        let isDragging = false;
        let startX = 0, startY = 0;
        let startLeftPct = 50, startTopPct = 50;

        function onStart(e) {
            // Do not drag if clicking on or inside the resize handle
            if (e.target && e.target.closest && e.target.closest('#qrResizeHandle')) {
                return;
            }

            isDragging = true;
            element.classList.add('is-dragging');

            const point = e.touches ? e.touches[0] : e;
            startX = point.clientX;
            startY = point.clientY;

            startLeftPct = parseFloat(element.style.left) || 50;
            startTopPct = parseFloat(element.style.top) || 50;

            document.body.style.userSelect = 'none';

            window.addEventListener('mousemove', onMove, { passive: false });
            window.addEventListener('mouseup', onEnd);
            window.addEventListener('touchmove', onTouchMove, { passive: false });
            window.addEventListener('touchend', onEnd);

            if (e.cancelable) e.preventDefault();
        }

        function onMove(e) {
            if (!isDragging) return;
            const point = e.touches ? e.touches[0] : e;
            const deltaX = point.clientX - startX;
            const deltaY = point.clientY - startY;

            const rect = viewport.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return;

            const deltaXPct = (deltaX / rect.width) * 100;
            const deltaYPct = (deltaY / rect.height) * 100;

            let newLeftPct = startLeftPct + deltaXPct;
            let newTopPct = startTopPct + deltaYPct;

            // Magnetic snap to center (50%)
            if (Math.abs(newLeftPct - 50) < 1.8) {
                newLeftPct = 50.0;
                if (guideLineV) guideLineV.style.display = 'block';
            } else {
                if (guideLineV) guideLineV.style.display = 'none';
            }

            // Magnetic snap to vertical center (50%) or frame optical center (47.5%)
            if (Math.abs(newTopPct - 50) < 1.8) {
                newTopPct = 50.0;
                if (guideLineH) guideLineH.style.display = 'block';
            } else if (element === draggableQr && Math.abs(newTopPct - 47.5) < 1.4) {
                newTopPct = 47.5;
                if (guideLineH) guideLineH.style.display = 'block';
            } else {
                if (guideLineH) guideLineH.style.display = 'none';
            }

            newLeftPct = Math.max(5, Math.min(95, newLeftPct));
            newTopPct = Math.max(5, Math.min(95, newTopPct));

            element.style.left = newLeftPct.toFixed(1) + '%';
            element.style.top = newTopPct.toFixed(1) + '%';

            onMoveCallback(newLeftPct, newTopPct);
            if (e.cancelable) e.preventDefault();
        }

        function onTouchMove(e) {
            onMove(e);
        }

        function onEnd() {
            if (!isDragging) return;
            isDragging = false;
            element.classList.remove('is-dragging');
            document.body.style.userSelect = '';

            if (guideLineV) guideLineV.style.display = 'none';
            if (guideLineH) guideLineH.style.display = 'none';

            window.removeEventListener('mousemove', onMove);
            window.removeEventListener('mouseup', onEnd);
            window.removeEventListener('touchmove', onTouchMove);
            window.removeEventListener('touchend', onEnd);
        }

        element.addEventListener('mousedown', onStart);
        element.addEventListener('touchstart', onStart, { passive: false });
    }

    // Initialize Draggable QR & Code
    setupDraggable(draggableQr, function (leftPct, topPct) {
        inputQrX.value = leftPct.toFixed(1);
        inputQrY.value = topPct.toFixed(1);
        updateCoordLabels();
    });

    setupDraggable(draggableCode, function (leftPct, topPct) {
        inputCodeX.value = leftPct.toFixed(1);
        inputCodeY.value = topPct.toFixed(1);
        updateCoordLabels();
    });

    // QR Size Slider (Strict Aspect Ratio 1:1)
    sliderQrSize.addEventListener('input', function () {
        const val = this.value;
        lblQrSizeVal.textContent = val + '%';
        const tagQrSize = document.getElementById('tagQrSizePct');
        if (tagQrSize) tagQrSize.textContent = val + '%';
        draggableQr.style.width = val + '%';
        draggableQr.style.aspectRatio = '1 / 1';
        draggableQr.style.height = 'auto';
        inputQrSize.value = val;
        updateCoordLabels();
    });

    // 4. Smooth Direct Corner Resize Handle
    (function setupResizeHandle() {
        let isResizing = false;
        let resizeStartX = 0;
        let resizeStartSize = 38;

        function onResizeStart(e) {
            isResizing = true;
            qrResizeHandle.classList.add('is-resizing');

            const point = e.touches ? e.touches[0] : e;
            resizeStartX = point.clientX;
            resizeStartSize = parseFloat(sliderQrSize.value) || 38;

            document.body.style.userSelect = 'none';

            window.addEventListener('mousemove', onResizeMove, { passive: false });
            window.addEventListener('mouseup', onResizeEnd);
            window.addEventListener('touchmove', onResizeTouchMove, { passive: false });
            window.addEventListener('touchend', onResizeEnd);

            e.stopPropagation();
            if (e.cancelable) e.preventDefault();
        }

        function onResizeMove(e) {
            if (!isResizing) return;
            const point = e.touches ? e.touches[0] : e;
            const deltaX = point.clientX - resizeStartX;
            const rect = viewport.getBoundingClientRect();
            if (rect.width === 0) return;

            const deltaPct = (deltaX / rect.width) * 100;
            const newSize = Math.max(15, Math.min(80, Math.round(resizeStartSize + deltaPct)));

            sliderQrSize.value = newSize;
            sliderQrSize.dispatchEvent(new Event('input'));

            if (e.cancelable) e.preventDefault();
        }

        function onResizeTouchMove(e) {
            onResizeMove(e);
        }

        function onResizeEnd() {
            if (!isResizing) return;
            isResizing = false;
            qrResizeHandle.classList.remove('is-resizing');
            document.body.style.userSelect = '';

            window.removeEventListener('mousemove', onResizeMove);
            window.removeEventListener('mouseup', onResizeEnd);
            window.removeEventListener('touchmove', onResizeTouchMove);
            window.removeEventListener('touchend', onResizeEnd);
        }

        qrResizeHandle.addEventListener('mousedown', onResizeStart);
        qrResizeHandle.addEventListener('touchstart', onResizeStart, { passive: false });
    })();

    // QR Color Controls (Direct SVG & Canvas updates)
    pickerQrFg.addEventListener('input', function () {
        const hex = this.value;
        lblQrFgHex.textContent = hex;
        inputQrFg.value = hex;

        const svg = document.querySelector('#qrSvgWrapper svg');
        if (svg) {
            svg.querySelectorAll('path, rect:not([fill="#ffffff"]):not([fill="white"]):not([fill="#fff"])').forEach(el => {
                const cur = el.getAttribute('fill');
                if (cur && cur !== '#ffffff' && cur !== 'white' && cur !== '#fff') {
                    el.setAttribute('fill', hex);
                }
            });
        }
        const img = document.getElementById('qrPreviewImg');
        if (img) {
            const cleanHex = hex.replace('#', '');
            img.src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&color=${cleanHex}&data=https://example.com`;
        }
    });

    selectQrBg.addEventListener('change', function () {
        const val = this.value;
        inputQrBg.value = val;
        qrContainer.style.backgroundColor = val === 'transparent' ? 'transparent' : '#ffffff';
        const svg = document.querySelector('#qrSvgWrapper svg');
        if (svg) {
            svg.querySelectorAll('rect').forEach(rect => {
                const fill = rect.getAttribute('fill');
                if (fill === '#ffffff' || fill === 'white' || fill === '#fff') {
                    rect.setAttribute('fill-opacity', val === 'transparent' ? '0' : '1');
                }
            });
        }
    });

    // Code Style Listener
    const selectCodeStyle = document.querySelector('select[name="code_style"]');
    if (selectCodeStyle) {
        selectCodeStyle.addEventListener('change', function () {
            if (this.value === 'sequential') {
                codeText.textContent = 'VP-000001';
            } else if (this.value === 'numeric') {
                codeText.textContent = 'VP-519283';
            } else {
                codeText.textContent = 'VP-984210';
            }
        });
    }

    // Unique Code Controls
    chkShowCode.addEventListener('change', function () {
        const checked = this.checked;
        draggableCode.style.display = checked ? 'block' : 'none';
        codeOptionsWrapper.style.display = checked ? 'block' : 'none';
        inputShowCode.value = checked ? '1' : '0';
    });

    sliderCodeSize.addEventListener('input', function () {
        const val = this.value;
        lblCodeSizeVal.textContent = val + ' pt';
        const tagCodeSize = document.getElementById('tagCodeSizePt');
        if (tagCodeSize) tagCodeSize.textContent = val + 'pt';
        codeText.style.fontSize = val + 'px';
        inputCodeFontSize.value = val;
        updateCoordLabels();
    });

    pickerCodeColor.addEventListener('input', function () {
        const hex = this.value;
        lblCodeColorHex.textContent = hex;
        codeText.style.color = hex;
        inputCodeColor.value = hex;
    });

    selectCodeBg.addEventListener('change', function () {
        const val = this.value;
        inputCodeBg.value = val;
        codeContainer.style.backgroundColor = val;
    });

    const sliderCodeLetterSpacing = document.getElementById('sliderCodeLetterSpacing');
    const lblCodeLetterSpacingVal = document.getElementById('lblCodeLetterSpacingVal');
    const inputCodeLetterSpacing = document.getElementById('inputCodeLetterSpacing');

    sliderCodeLetterSpacing?.addEventListener('input', function () {
        const val = this.value;
        lblCodeLetterSpacingVal.textContent = val + ' pt';
        codeText.style.letterSpacing = val + 'px';
        inputCodeLetterSpacing.value = val;
    });

    if (selectCodeFontFamily) {
        selectCodeFontFamily.addEventListener('change', function () {
            const val = this.value;
            inputCodeFontFamily.value = val;
            if (val === 'monospace') {
                codeText.style.fontFamily = 'monospace';
            } else if (val === 'serif') {
                codeText.style.fontFamily = 'Georgia, "Times New Roman", serif';
            } else {
                codeText.style.fontFamily = 'system-ui, -apple-system, sans-serif';
            }
        });
    }

    if (selectCodeFontWeight) {
        selectCodeFontWeight.addEventListener('change', function () {
            const val = this.value;
            inputCodeFontWeight.value = val;
            codeText.style.fontWeight = val;
        });
    }

    // Precise Numerical Inputs (with focus protection to prevent typing jumps)
    if (numQrX) {
        numQrX.addEventListener('input', function() {
            const v = Math.max(5, Math.min(95, parseFloat(this.value) || 50));
            draggableQr.style.left = v + '%';
            inputQrX.value = v;
            updateCoordLabels();
        });
    }
    if (numQrY) {
        numQrY.addEventListener('input', function() {
            const v = Math.max(5, Math.min(95, parseFloat(this.value) || 50));
            draggableQr.style.top = v + '%';
            inputQrY.value = v;
            updateCoordLabels();
        });
    }
    if (numCodeX) {
        numCodeX.addEventListener('input', function() {
            const v = Math.max(5, Math.min(95, parseFloat(this.value) || 50));
            draggableCode.style.left = v + '%';
            inputCodeX.value = v;
            updateCoordLabels();
        });
    }
    if (numCodeY) {
        numCodeY.addEventListener('input', function() {
            const v = Math.max(5, Math.min(95, parseFloat(this.value) || 50));
            draggableCode.style.top = v + '%';
            inputCodeY.value = v;
            updateCoordLabels();
        });
    }

    function triggerStagePulse() {
        if (!viewport) return;
        viewport.classList.remove('stage-pulse');
        void viewport.offsetWidth;
        viewport.classList.add('stage-pulse');
        setTimeout(() => viewport.classList.remove('stage-pulse'), 300);
    }

    // Pro Alignment Toolbar Handlers
    btnCenterQrH?.addEventListener('click', function () {
        draggableQr.style.left = '50%';
        inputQrX.value = '50';
        updateCoordLabels();
        triggerStagePulse();
    });

    btnCenterQrV?.addEventListener('click', function () {
        draggableQr.style.top = '50%';
        inputQrY.value = '50';
        updateCoordLabels();
        triggerStagePulse();
    });

    btnCenterQrBoth?.addEventListener('click', function () {
        draggableQr.style.left = '50%';
        draggableQr.style.top = '50%';
        inputQrX.value = '50';
        inputQrY.value = '50';
        updateCoordLabels();
        triggerStagePulse();
    });

    btnCenterCodeH?.addEventListener('click', function () {
        draggableCode.style.left = '50%';
        inputCodeX.value = '50';
        updateCoordLabels();
        triggerStagePulse();
    });

    btnCenterCodeV?.addEventListener('click', function () {
        draggableCode.style.top = '50%';
        inputCodeY.value = '50';
        updateCoordLabels();
        triggerStagePulse();
    });

    btnAlignEverything?.addEventListener('click', function () {
        draggableQr.style.left = '50%';
        draggableQr.style.top = '47.5%';
        draggableQr.style.width = '35%';
        inputQrX.value = '50';
        inputQrY.value = '47.5';
        inputQrSize.value = '35';
        sliderQrSize.value = '35';
        lblQrSizeVal.textContent = '35%';
        const tagQr = document.getElementById('tagQrSizePct');
        if (tagQr) tagQr.textContent = '35%';

        draggableCode.style.left = '50%';
        draggableCode.style.top = '74%';
        inputCodeX.value = '50';
        inputCodeY.value = '74';

        if (numQrX) numQrX.value = '50.0';
        if (numQrY) numQrY.value = '47.5';
        if (numCodeX) numCodeX.value = '50.0';
        if (numCodeY) numCodeY.value = '74.0';

        updateCoordLabels();
        triggerStagePulse();
    });

    // Clean Preview Mode Toggle (Aperçu Net sans repères)
    const btnToggleCleanPreview = document.getElementById('btnToggleCleanPreview');
    const iconCleanPreview = document.getElementById('iconCleanPreview');
    const lblCleanPreviewText = document.getElementById('lblCleanPreviewText');
    let isCleanPreview = false;

    btnToggleCleanPreview?.addEventListener('click', function () {
        isCleanPreview = !isCleanPreview;
        viewport.classList.toggle('clean-preview-mode', isCleanPreview);
        this.classList.toggle('btn-primary', isCleanPreview);
        this.classList.toggle('text-white', isCleanPreview);
        this.classList.toggle('btn-light', !isCleanPreview);
        if (isCleanPreview) {
            if (iconCleanPreview) iconCleanPreview.className = 'bi bi-eye-slash me-1';
            if (lblCleanPreviewText) lblCleanPreviewText.textContent = 'Repères Masqués';
        } else {
            if (iconCleanPreview) iconCleanPreview.className = 'bi bi-eye me-1';
            if (lblCleanPreviewText) lblCleanPreviewText.textContent = 'Aperçu Net';
        }
        triggerStagePulse();
    });

    // Keyboard Arrow Nudge Engine for Precision CAD Alignment
    [
        { el: draggableQr, inputX: inputQrX, inputY: inputQrY },
        { el: draggableCode, inputX: inputCodeX, inputY: inputCodeY }
    ].forEach(item => {
        if (!item.el) return;
        item.el.addEventListener('keydown', function (e) {
            if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(e.key)) return;
            e.preventDefault();
            const step = e.shiftKey ? 2.0 : 0.5;
            let leftPct = parseFloat(item.el.style.left) || 50;
            let topPct = parseFloat(item.el.style.top) || 50;

            if (e.key === 'ArrowLeft') leftPct = Math.max(5, leftPct - step);
            if (e.key === 'ArrowRight') leftPct = Math.min(95, leftPct + step);
            if (e.key === 'ArrowUp') topPct = Math.max(5, topPct - step);
            if (e.key === 'ArrowDown') topPct = Math.min(95, topPct + step);

            item.el.style.left = leftPct.toFixed(1) + '%';
            item.el.style.top = topPct.toFixed(1) + '%';
            item.inputX.value = leftPct.toFixed(1);
            item.inputY.value = topPct.toFixed(1);
            updateCoordLabels();
            triggerStagePulse();
        });
    });

    function updateCoordLabels() {
        lblQrCoords.textContent = `X: ${inputQrX.value}% | Y: ${inputQrY.value}% | Taille: ${inputQrSize.value}%`;
        lblCodeCoords.textContent = `X: ${inputCodeX.value}% | Y: ${inputCodeY.value}% | Police: ${inputCodeFontSize.value}pt`;

        // Only update numeric inputs if user is not currently typing inside them
        if (numQrX && document.activeElement !== numQrX) numQrX.value = parseFloat(inputQrX.value).toFixed(1);
        if (numQrY && document.activeElement !== numQrY) numQrY.value = parseFloat(inputQrY.value).toFixed(1);
        if (numCodeX && document.activeElement !== numCodeX) numCodeX.value = parseFloat(inputCodeX.value).toFixed(1);
        if (numCodeY && document.activeElement !== numCodeY) numCodeY.value = parseFloat(inputCodeY.value).toFixed(1);
    }

    // Quantity Pills
    document.querySelectorAll('.qty-pill').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.qty-pill').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            quantityInput.value = this.dataset.qty;
            updateBtnText();
        });
    });

    quantityInput.addEventListener('input', function () {
        const val = this.value;
        document.querySelectorAll('.qty-pill').forEach(b => {
            b.classList.toggle('active', b.dataset.qty === val);
        });
        updateBtnText();
    });

    function updateBtnText() {
        const q = quantityInput.value || 1;
        btnSubmitText.textContent = `Générer & Télécharger le PDF (${q} ${q > 1 ? 'pages' : 'page'})`;
    }

    // Quick URL Helper
    window.setStudioUrl = function(prefix) {
        const inp = document.getElementById('targetUrlInputStudio');
        if (inp) {
            inp.value = prefix;
            inp.focus();
        }
    };

    window.setStandardUrl = function(prefix) {
        const inp = document.getElementById('targetUrlInputStandard');
        if (inp) {
            inp.value = prefix;
            inp.focus();
        }
    };

    // Robust Form Submit & Instant PDF Download Engine
    const studioForm = document.getElementById('batchPdfStudioForm');
    const btnSubmit = document.getElementById('btnSubmitGeneratePdf');
    const textSpan = document.getElementById('btnSubmitText');
    const studioAlertArea = document.getElementById('studioAlertArea');

    function showStudioAlert(type, htmlContent) {
        if (!studioAlertArea) return;
        studioAlertArea.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show p-3 rounded-3 border-0 shadow-xs mb-3" role="alert" style="font-size: 0.85rem;">
                ${htmlContent}
                <button type="button" class="btn-close p-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        studioAlertArea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    studioForm?.addEventListener('submit', async function (e) {
        e.preventDefault();

        // 1. Validation Checks
        const wVal = parseFloat(widthInput.value);
        const hVal = parseFloat(heightInput.value);
        const qVal = parseInt(quantityInput.value, 10);
        const prefixInput = studioForm.querySelector('input[name="label_prefix"]');
        const targetUrlInput = studioForm.querySelector('input[name="target_url"]');
        const prefixVal = (prefixInput?.value || '').trim();
        const targetUrlVal = (targetUrlInput?.value || '').trim();

        if (isNaN(wVal) || wVal <= 0 || isNaN(hVal) || hVal <= 0) {
            showStudioAlert('danger', '<i class="bi bi-exclamation-circle me-1"></i> Veuillez indiquer des dimensions valides (largeur et hauteur > 0).');
            widthInput.focus();
            return;
        }

        if (isNaN(qVal) || qVal < 1 || qVal > 100) {
            showStudioAlert('danger', '<i class="bi bi-exclamation-circle me-1"></i> Le nombre de QR codes doit être compris entre 1 et 100.');
            quantityInput.focus();
            return;
        }

        if (!prefixVal) {
            showStudioAlert('danger', '<i class="bi bi-exclamation-circle me-1"></i> Veuillez renseigner le préfixe du libellé pour ce lot.');
            if (prefixInput) prefixInput.focus();
            return;
        }

        if (!targetUrlVal) {
            showStudioAlert('danger', '<i class="bi bi-exclamation-circle me-1"></i> Veuillez renseigner l\'URL de redirection cible.');
            if (targetUrlInput) targetUrlInput.focus();
            return;
        }

        // 2. Loading State
        const originalHtml = textSpan.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.85';
        btnSubmit.style.cursor = 'wait';
        textSpan.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Génération du lot PDF (${qVal} ${qVal > 1 ? 'pages' : 'page'})...`;
        if (studioAlertArea) studioAlertArea.innerHTML = '';

        try {
            const formData = new FormData(studioForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch(studioForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/pdf, application/json, text/plain, */*'
                }
            });

            if (response.ok) {
                const contentType = response.headers.get('content-type') || '';
                if (contentType.includes('application/pdf')) {
                    const blob = await response.blob();

                    // Parse filename
                    let filename = `lot_qrcodes_${qVal}_pages_${Date.now()}.pdf`;
                    const disposition = response.headers.get('content-disposition');
                    if (disposition && disposition.indexOf('filename=') !== -1) {
                        const matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                        if (matches && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '').trim();
                        }
                    }

                    // Direct Download Trigger
                    const blobUrl = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    link.remove();

                    // Success Banner with Manual Redownload Link
                    showStudioAlert('success', `
                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                            <div>
                                <i class="bi bi-check-circle-fill text-success me-1 fs-6"></i>
                                <strong>Lot PDF généré avec succès !</strong> Le téléchargement (${filename}) a démarré automatiquement.
                            </div>
                            <a href="${blobUrl}" download="${filename}" class="btn btn-sm btn-success fw-semibold py-1 px-3 shadow-xs" style="border-radius: 8px;">
                                <i class="bi bi-download me-1"></i> Retélécharger
                            </a>
                        </div>
                    `);

                    textSpan.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> PDF Téléchargé !`;
                    setTimeout(() => {
                        textSpan.innerHTML = originalHtml;
                        btnSubmit.disabled = false;
                        btnSubmit.style.opacity = '1';
                        btnSubmit.style.cursor = '';
                    }, 4000);
                    return;
                }
            }

            // Error responses
            if (response.status === 422) {
                const errData = await response.json();
                let errMsg = 'Erreur de validation : ';
                if (errData.errors) {
                    errMsg += Object.values(errData.errors).flat().join(' | ');
                } else if (errData.message) {
                    errMsg += errData.message;
                }
                showStudioAlert('danger', `<i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> ${errMsg}`);
            } else if (response.status === 419) {
                showStudioAlert('warning', `<i class="bi bi-clock-history me-1"></i> Votre session a expiré. Veuillez rafraîchir la page (F5) et réessayer.`);
            } else {
                showStudioAlert('danger', `<i class="bi bi-x-circle-fill text-danger me-1"></i> Une erreur serveur est survenue (Code ${response.status}).`);
            }
        } catch (fetchErr) {
            console.warn('Fetch error encountered, falling back to standard submission:', fetchErr);
            studioForm.submit();
            return;
        } finally {
            if (btnSubmit.disabled) {
                btnSubmit.disabled = false;
                btnSubmit.style.opacity = '1';
                btnSubmit.style.cursor = '';
                textSpan.innerHTML = originalHtml;
            }
        }
    });

    // Initial setup
    updateCanvasAspect();
    updateCoordLabels();
    updateBtnText();
});
</script>
@endpush
@endsection
