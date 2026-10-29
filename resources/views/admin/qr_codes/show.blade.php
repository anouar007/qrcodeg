@extends('layouts.admin')

@section('title', 'QR Code: ' . $qrCode->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.qr-codes.index') }}">Graphic QR Codes</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $qrCode->name }}</li>
@endsection

@section('content')
<!-- Page Header with Live Status & Quick Actions -->
<div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
            <span class="vp-code-badge" style="font-size: 0.82rem; padding: 0.25rem 0.65rem;">
                <i class="bi bi-qr-code me-1"></i>{{ $qrCode->public_code }}
            </span>
            <span class="badge-status badge-status-{{ $qrCode->status === 'active' ? 'success' : ($qrCode->status === 'suspended' ? 'danger' : 'warning') }}">
                <span class="status-dot"></span>
                <span>{{ strtoupper($qrCode->status) }}</span>
            </span>
            <span class="badge bg-light text-muted border font-mono small d-none d-sm-inline-flex">
                ID #{{ $qrCode->id }}
            </span>
        </div>
        <h2 class="fw-extrabold text-dark mb-1 tracking-tight">{{ $qrCode->name }}</h2>
        <p class="text-muted small mb-0">
            Gestion de la redirection en direct, audit télémétrique des scans et export de supports physiques PDF.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('admin.qr-codes.edit', $qrCode) }}" class="btn btn-light rounded-pill shadow-xs">
            <i class="bi bi-sliders text-primary"></i> Personnaliser Design
        </a>
        <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i> Liste des QR
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: High-Res QR Card & Asset Export Hub -->
    <div class="col-lg-4 col-xl-4">
        <div class="card card-custom p-4 bg-white text-center mb-4 border shadow-sm position-relative overflow-hidden">
            <!-- Subtle Glowing Top Accent -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--primary-gradient);"></div>

            <!-- Header Title -->
            <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                <div>
                    <span class="text-uppercase tracking-wider text-muted font-mono" style="font-size: 0.68rem; font-weight: 700;">Aperçu Vectoriel</span>
                    <h6 class="fw-bold mb-0 text-dark">Support Graphique</h6>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-mono small px-2 py-0.5">
                    SVG HD
                </span>
            </div>

            <!-- Rendered Preview (Template Composite if available, otherwise Vector SVG) -->
            @if($qrCode->design?->template_image_path)
                <div class="mb-3 btn-group w-100 p-1 bg-light rounded-pill border" role="group">
                    <button type="button" class="btn btn-xs rounded-pill fw-bold btn-primary" id="btnShowTemplatePreview" onclick="togglePreviewMode('template');">
                        <i class="bi bi-image me-1"></i> Support / Plaque Imprimée
                    </button>
                    <button type="button" class="btn btn-xs rounded-pill fw-bold btn-light text-muted" id="btnShowVectorPreview" onclick="togglePreviewMode('vector');">
                        <i class="bi bi-qr-code me-1"></i> QR Vectoriel Seul
                    </button>
                </div>

                <div id="templatePreviewContainer" class="p-2 bg-dark rounded-4 border mb-3 shadow-xs text-center overflow-hidden position-relative" style="background: radial-gradient(circle, #334155 0%, #0f172a 100%);">
                    <img src="{{ route('admin.qr-codes.composite-image', $qrCode) }}" alt="Gabarit Composé" class="img-fluid rounded-3 shadow" style="max-height: 380px;">
                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                        <span class="badge bg-dark bg-opacity-75 text-white font-mono small px-2 py-1 border border-secondary">
                            <i class="bi bi-eye me-1"></i>Gabarit Composé HD
                        </span>
                    </div>
                </div>

                <div id="vectorPreviewContainer" class="p-3 bg-light rounded-4 border mb-3 shadow-xs qr-preview-wrapper d-none">
                    <div class="qr-preview-container">
                        {!! $svgPreview !!}
                    </div>
                </div>
            @else
                <div class="p-3 bg-light rounded-4 border mb-3 shadow-xs qr-preview-wrapper" style="background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);">
                    <div class="qr-preview-container">
                        {!! $svgPreview !!}
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <h5 class="fw-bold text-dark mb-1">{{ $qrCode->name }}</h5>
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <span class="badge bg-light text-dark border font-mono small">
                        Format: {{ ucfirst(str_replace('_', ' ', $qrCode->type)) }}
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle font-mono small">
                        {{ $qrCode->status === 'active' ? 'Opérationnel' : ucfirst($qrCode->status) }}
                    </span>
                </div>
            </div>

            <!-- Dynamic URL Box with One-Click Copy -->
            <div class="p-3 rounded-3 text-start border mb-3 bg-light-subtle position-relative">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted text-uppercase font-mono" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.05em;">URL Dynamique Gravée</small>
                    <button type="button" class="btn btn-link text-muted p-0 border-0 copy-btn text-decoration-none small" data-copy="{{ $qrCode->dynamic_url }}" title="Copier l'URL">
                        <i class="bi bi-clipboard fs-6"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <a href="{{ $qrCode->dynamic_url }}" target="_blank" class="fw-bold text-primary font-mono small text-truncate text-decoration-none" title="{{ $qrCode->dynamic_url }}">
                        {{ $qrCode->dynamic_url }}
                    </a>
                    <a href="{{ $qrCode->dynamic_url }}" target="_blank" class="text-secondary small" title="Ouvrir">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>

            <!-- Access Identifiers Box -->
            <div class="row g-2 mb-4 text-start font-mono">
                <div class="col-6">
                    <div class="p-2.5 border rounded-3 bg-light d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 0.65rem; font-weight: 700;">CODE DE SUIVI</span>
                            <button type="button" class="btn btn-link text-muted p-0 copy-btn" data-copy="{{ $qrCode->public_code }}" title="Copier">
                                <i class="bi bi-clipboard" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                        <span class="fw-bold text-dark small mt-1 text-truncate">{{ $qrCode->public_code }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2.5 border rounded-3 bg-light d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 0.65rem; font-weight: 700;">CLÉ PORTAIL</span>
                            <button type="button" class="btn btn-link text-primary p-0 copy-btn" data-copy="{{ $qrCode->access_code }}" title="Copier">
                                <i class="bi bi-clipboard" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                        <span class="fw-bold text-primary small mt-1 text-truncate">{{ $qrCode->access_code }}</span>
                    </div>
                </div>
            </div>

            <!-- Printable PDF Card Templates (3 Styles) -->
            <div class="card border rounded-3 p-3 mb-3 text-start bg-light shadow-xs">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-1.5" style="font-size: 0.86rem;">
                        <i class="bi bi-printer text-danger"></i> Cartes Imprimables PDF
                    </h6>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-mono" style="font-size: 0.65rem;">
                        3 MODÈLES
                    </span>
                </div>
                <p class="text-muted small mb-2" style="font-size: 0.76rem;">Générez et imprimez des cartes professionnelles prêtes à poser :</p>
                
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.qr-codes.card-pdf', [$qrCode, 'template' => 'google_review']) }}" class="btn btn-white btn-sm text-start d-flex align-items-center justify-content-between border rounded-3 py-2 px-2.5 shadow-xs bg-white text-decoration-none">
                        <span class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                            <i class="bi bi-star-fill text-warning"></i> Google Review Card
                        </span>
                        <i class="bi bi-download text-muted"></i>
                    </a>
                    <a href="{{ route('admin.qr-codes.card-pdf', [$qrCode, 'template' => 'social_media']) }}" class="btn btn-white btn-sm text-start d-flex align-items-center justify-content-between border rounded-3 py-2 px-2.5 shadow-xs bg-white text-decoration-none">
                        <span class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                            <i class="bi bi-share-fill text-primary"></i> Social Media Card
                        </span>
                        <i class="bi bi-download text-muted"></i>
                    </a>
                    <a href="{{ route('admin.qr-codes.card-pdf', [$qrCode, 'template' => 'menu']) }}" class="btn btn-white btn-sm text-start d-flex align-items-center justify-content-between border rounded-3 py-2 px-2.5 shadow-xs bg-white text-decoration-none">
                        <span class="d-flex align-items-center gap-2 text-dark small fw-semibold">
                            <i class="bi bi-cup-hot-fill text-warning"></i> Menu Restaurant Card
                        </span>
                        <i class="bi bi-download text-muted"></i>
                    </a>
                </div>
            </div>

            <!-- Composite Template Download (If configured) -->
            @if($qrCode->design?->template_image_path)
                <div class="mb-3">
                    <a href="{{ route('admin.qr-codes.download', [$qrCode, 'template']) }}" class="btn btn-primary btn-sm w-100 rounded-pill py-2.5 shadow-xs d-inline-flex align-items-center justify-content-center gap-2 fw-bold">
                        <i class="bi bi-image fs-6"></i> Télécharger Support Composé (PNG HD)
                    </a>
                </div>
            @endif

            <!-- Standard Vector & Raster Downloads -->
            <div class="row g-2 mb-2">
                <div class="col-6">
                    <a href="{{ route('admin.qr-codes.download', [$qrCode, 'svg']) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-2 shadow-xs d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-filetype-svg"></i> SVG Vectoriel
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.qr-codes.download', [$qrCode, 'png']) }}" class="btn btn-outline-success btn-sm w-100 rounded-pill py-2 shadow-xs d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-filetype-png"></i> PNG HD 600px
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Live Destination Routing & Telemetry Stream -->
    <div class="col-lg-8 col-xl-8">
        <!-- Top Analytics Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card stat-primary h-100 p-3 position-relative overflow-hidden">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                <span class="status-dot-radar" style="width: 6px; height: 6px;"></span>
                                <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.08em;">Total des Scans</span>
                            </div>
                            <h3 class="fw-extrabold text-dark mb-0 fs-2 font-mono tracking-tight">{{ number_format($qrCode->scan_count) }}</h3>
                            <span class="text-muted mt-1 d-block font-sans" style="font-size: 0.74rem;">Télémétrie en direct</span>
                        </div>
                        <div class="stat-icon-wrapper stat-icon-primary shadow-xs">
                            <i class="bi bi-broadcast"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card stat-info h-100 p-3 position-relative overflow-hidden">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="min-w-0 pe-2">
                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                <i class="bi bi-building text-info" style="font-size: 0.75rem;"></i>
                                <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.08em;">Client Assigné</span>
                            </div>
                            @if($qrCode->client)
                                <h5 class="fw-bold text-dark mb-0 text-truncate font-heading mt-1" title="{{ $qrCode->client->company_name }}">
                                    {{ $qrCode->client->company_name }}
                                </h5>
                                <a href="{{ route('admin.clients.show', $qrCode->client) }}" class="text-primary text-decoration-none small d-inline-flex align-items-center gap-1 mt-1 font-sans" style="font-size: 0.74rem;">
                                    <span>Voir le profil</span> <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <h5 class="fw-bold text-muted mb-0 font-heading mt-1">Non assigné</h5>
                                <span class="text-muted mt-1 d-block font-sans" style="font-size: 0.74rem;">Usage interne</span>
                            @endif
                        </div>
                        <div class="stat-icon-wrapper stat-icon-info shadow-xs flex-shrink-0">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card stat-success h-100 p-3 position-relative overflow-hidden">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                <i class="bi bi-layers text-success" style="font-size: 0.75rem;"></i>
                                <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.08em;">Format & Modèle</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-0 font-heading mt-1 text-capitalize">
                                {{ str_replace('_', ' ', $qrCode->type) }}
                            </h5>
                            <span class="badge bg-success-subtle text-success border border-success-subtle font-mono small mt-1">
                                {{ ucwords(str_replace('_', ' ', $qrCode->card_template ?: 'Standard')) }}
                            </span>
                        </div>
                        <div class="stat-icon-wrapper stat-icon-success shadow-xs flex-shrink-0">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In-Dashboard Live Destination Link Management (Instant Redirection) -->
        <div class="card card-custom p-4 bg-white mb-4 border shadow-sm position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-wrapper stat-icon-warning shadow-xs" style="width: 44px; height: 44px; font-size: 1.25rem;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-extrabold mb-0 text-dark">Modifier la Destination en Direct</h5>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle font-mono" style="font-size: 0.68rem;">
                                ZERO REPRINT
                            </span>
                        </div>
                        <p class="text-muted small mb-0 mt-0.5">
                            Changez la cible de redirection instantanément sans jamais avoir à réimprimer vos supports physiques.
                        </p>
                    </div>
                </div>

                <span class="badge bg-light text-muted border font-mono small d-none d-md-inline-block">
                    Routage HTTP 302 Immédiat
                </span>
            </div>

            <form action="{{ route('admin.qr-codes.destination', $qrCode) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark mb-1 d-flex justify-content-between">
                            <span>Lien de Redirection Cible (URL de Destination) <span class="text-danger">*</span></span>
                            <span class="text-muted font-normal" style="font-size: 0.75rem;">Résolution en direct</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-link-45deg fs-5"></i></span>
                            <input type="url" 
                                   name="target_redirect_url" 
                                   id="targetRedirectUrlInput"
                                   class="form-control font-mono border-start-0" 
                                   placeholder="https://votre-nouveau-lien.com" 
                                   value="{{ old('target_redirect_url', $qrCode->target_redirect_url ?: ($qrCode->content?->content_data['url'] ?? '')) }}"
                                   required>
                            <button type="button" class="btn btn-light border" id="testLinkBtn" title="Tester le lien dans un nouvel onglet">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Tester
                            </button>
                        </div>
                        <div class="form-text small mt-1 text-muted d-flex align-items-center gap-1.5 font-sans">
                            <i class="bi bi-shield-check text-success"></i>
                            <span>Toute personne scannant <code>/q/{{ $qrCode->public_code }}</code> sera immédiatement redirigée vers cette adresse.</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-1">Modèle de Carte PDF Associé</label>
                        <select name="card_template" class="form-select">
                            <option value="google_review" {{ $qrCode->card_template === 'google_review' ? 'selected' : '' }}>Google Review Card</option>
                            <option value="social_media" {{ $qrCode->card_template === 'social_media' ? 'selected' : '' }}>Social Media Card</option>
                            <option value="menu" {{ $qrCode->card_template === 'menu' ? 'selected' : '' }}>Menu Card (Restaurant & Bar)</option>
                        </select>
                        <div class="form-text small text-muted">Définit le gabarit graphique des exports PDF.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-1">Statut du QR Code</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $qrCode->status === 'active' ? 'selected' : '' }}>Actif (Redirection opérationnelle)</option>
                            <option value="inactive" {{ $qrCode->status === 'inactive' ? 'selected' : '' }}>Inactif (Message d'indisponibilité)</option>
                            <option value="suspended" {{ $qrCode->status === 'suspended' ? 'selected' : '' }}>Suspendu (Accès bloqué)</option>
                        </select>
                        <div class="form-text small text-muted">Contrôle instantané de la disponibilité du code.</div>
                    </div>

                    <div class="col-12 d-flex justify-content-end pt-2 border-top mt-3">
                        <button type="submit" class="btn btn-primary-custom px-4 py-2.5 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle fs-5"></i>
                            <span>Enregistrer la Destination</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Scans for this QR (Polymorphic Telemetry Log) -->
        <div class="table-custom-wrapper">
            <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-wrapper stat-icon-primary" style="width: 32px; height: 32px; font-size: 1rem;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Journal Récent des Scans</h6>
                        <small class="text-muted" style="font-size: 0.74rem;">Historique télémétrique des dernières interactions</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-muted border font-mono small">
                        25 derniers événements
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Date & Heure</th>
                            <th>Appareil</th>
                            <th>OS & Navigateur</th>
                            <th>Ville / Région</th>
                            <th class="text-end pe-3">IP Hash</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qrCode->scans as $scan)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold text-dark font-mono small">
                                        {{ $scan->scanned_at ? $scan->scanned_at->format('d/m/Y H:i') : $scan->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        {{ ($scan->scanned_at ?: $scan->created_at)->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border text-capitalize font-sans fw-semibold">
                                        <i class="bi bi-{{ $scan->device_type === 'mobile' ? 'phone' : ($scan->device_type === 'tablet' ? 'tablet' : 'laptop') }} me-1 text-primary"></i>
                                        {{ $scan->device_type ?: 'Desktop' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-secondary font-sans">
                                        {{ $scan->operating_system ?: 'OS Inconnu' }} &bull; {{ $scan->browser ?: 'Navigateur' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-dark font-sans d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-geo-alt-fill text-danger small"></i>
                                        {{ $scan->city ? $scan->city . ', ' . $scan->country : 'Metropolitan Area, Global' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <span class="badge bg-light text-muted border font-mono small">
                                        {{ substr($scan->ip_hash, 0, 8) }}...
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="stat-icon-wrapper stat-icon-primary mb-2" style="width: 48px; height: 48px; font-size: 1.5rem;">
                                            <i class="bi bi-broadcast"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Aucun scan enregistré pour ce QR code</h6>
                                        <p class="text-muted small mb-0">Les données télémétriques s'afficheront en temps réel dès la première lecture.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.qr-preview-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.qr-preview-container {
    width: 100%;
    max-width: 290px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
}
.qr-preview-container svg {
    max-width: 100% !important;
    height: auto !important;
    display: block;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}
</style>
@endpush

@push('scripts')
<script>
function togglePreviewMode(mode) {
    const templateContainer = document.getElementById('templatePreviewContainer');
    const vectorContainer = document.getElementById('vectorPreviewContainer');
    const btnTemplate = document.getElementById('btnShowTemplatePreview');
    const btnVector = document.getElementById('btnShowVectorPreview');

    if (!templateContainer || !vectorContainer) return;

    if (mode === 'template') {
        templateContainer.classList.remove('d-none');
        vectorContainer.classList.add('d-none');
        if (btnTemplate) btnTemplate.className = 'btn btn-xs rounded-pill fw-bold btn-primary';
        if (btnVector) btnVector.className = 'btn btn-xs rounded-pill fw-bold btn-light text-muted';
    } else {
        templateContainer.classList.add('d-none');
        vectorContainer.classList.remove('d-none');
        if (btnTemplate) btnTemplate.className = 'btn btn-xs rounded-pill fw-bold btn-light text-muted';
        if (btnVector) btnVector.className = 'btn btn-xs rounded-pill fw-bold btn-primary';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // One-click copy buttons with interactive toast feedback
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const textToCopy = this.getAttribute('data-copy');
            if (textToCopy && navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const icon = this.querySelector('i');
                    if (icon) {
                        const originalClass = icon.className;
                        icon.className = 'bi bi-check-lg text-success';
                        setTimeout(() => {
                            icon.className = originalClass;
                        }, 1600);
                    }
                });
            }
        });
    });

    // Test redirection link button
    const testBtn = document.getElementById('testLinkBtn');
    const input = document.getElementById('targetRedirectUrlInput');
    if (testBtn && input) {
        testBtn.addEventListener('click', function () {
            const url = input.value.trim();
            if (url) {
                window.open(url, '_blank');
            } else {
                input.focus();
            }
        });
    }
});
</script>
@endpush
@endsection
