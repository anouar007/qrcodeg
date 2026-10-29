@extends('layouts.app')

@section('title', 'Modifier ' . $dynamicQr->code)

@push('styles')
<style>
.vp-code-badge-hero {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'SF Mono', Monaco, Menlo, 'Courier New', monospace;
    font-size: 1.1rem;
    font-weight: 800;
    padding: 0.35rem 0.8rem;
    border-radius: 9px;
    background: #f3f0ff;
    color: #6320ee;
    border: 1.5px solid #ddd6fe;
}
.quick-url-chip {
    font-size: 0.72rem;
    padding: 2px 8px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.12s ease;
}
.quick-url-chip:hover {
    background: #e2e8f0;
    color: #0f172a;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dynamic-qrs.index') }}" class="text-decoration-none text-muted">QR Dynamiques</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dynamic-qrs.show', $dynamicQr) }}" class="text-decoration-none text-muted">{{ $dynamicQr->code }}</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Modifier</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2.5">
                <span class="vp-code-badge-hero">{{ $dynamicQr->code }}</span>
                <h1 class="h4 fw-bold text-dark mb-0">Configuration du QR Code</h1>
            </div>
            <p class="text-muted small mb-0 mt-1">
                L'identifiant <strong class="text-dark font-mono">{{ $dynamicQr->code }}</strong> est permanent. La destination peut être mise à jour à tout moment sans réimpression.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dynamic-qrs.show', $dynamicQr) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
                <i class="bi bi-arrow-left"></i>
                <span>Retour aux détails</span>
            </a>
            <a href="{{ route('qr.redirect', ['code' => $dynamicQr->code]) }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-1.5" style="border-radius: 8px;">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Tester la redirection</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: Live QR Code Card Preview -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4">
                <div class="d-inline-block p-3 bg-white border rounded-4 shadow-sm mb-3">
                    <div style="width: 200px; height: 200px; margin: 0 auto;" class="d-flex align-items-center justify-content-center">
                        {!! $svgPreview !!}
                    </div>
                </div>

                <div class="fw-bold text-dark mb-1">{{ $dynamicQr->label }}</div>
                <div class="small text-muted font-mono mb-3">/qr/{{ $dynamicQr->code }}</div>

                <div class="p-2.5 bg-light rounded-3 text-start small border mb-3">
                    <div class="text-muted fw-semibold font-mono" style="font-size: 0.72rem;">Destination actuelle :</div>
                    <div class="font-mono text-truncate text-primary" style="font-size: 0.76rem;">{{ $dynamicQr->target_url }}</div>
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('admin.dynamic-qrs.svg', $dynamicQr) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                        <i class="bi bi-filetype-svg me-1"></i> SVG
                    </a>
                    <a href="{{ route('admin.dynamic-qrs.png', $dynamicQr) }}" class="btn btn-sm btn-outline-info" style="border-radius: 8px;">
                        <i class="bi bi-filetype-png me-1"></i> PNG
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Configuration Form -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold text-dark fs-6 mb-0">Paramètres et Destination</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.dynamic-qrs.update', $dynamicQr) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <!-- Identifier (Readonly) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold small text-muted mb-1">Identifiant Permanent</label>
                                <input type="text" class="form-control bg-light font-mono fw-bold text-primary" value="{{ $dynamicQr->code }}" readonly style="border-radius: 8px;">
                                <div class="form-text small" style="font-size: 0.72rem;">Gravé et encodé dans le QR physique.</div>
                            </div>

                            <!-- Client Assignment -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold small text-muted mb-1">Client Assigné</label>
                                <select name="client_id" class="form-select" style="border-radius: 8px;">
                                    <option value="">Général (Aucun client)</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $dynamicQr->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} {{ $client->project_name ? "({$client->project_name})" : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Label -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold small text-dark mb-1">Libellé du QR <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" 
                                       value="{{ old('label', $dynamicQr->label) }}" required style="border-radius: 8px;">
                                @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Template Type -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold small text-dark mb-1">Gabarit d'impression <span class="text-danger">*</span></label>
                                <select name="template_type" class="form-select" required style="border-radius: 8px;">
                                    <option value="google_review" {{ old('template_type', $dynamicQr->template_type) === 'google_review' ? 'selected' : '' }}>Carte Avis Google (5 étoiles)</option>
                                    <option value="social_media" {{ old('template_type', $dynamicQr->template_type) === 'social_media' ? 'selected' : '' }}>Carte Réseaux Sociaux</option>
                                    <option value="menu" {{ old('template_type', $dynamicQr->template_type) === 'menu' ? 'selected' : '' }}>Carte Menu Digital</option>
                                    <option value="custom" {{ old('template_type', $dynamicQr->template_type) === 'custom' ? 'selected' : '' }}>Gabarit Sur-Mesure</option>
                                </select>
                            </div>

                            <!-- Target URL -->
                            <div class="col-12">
                                <label class="form-label fw-bold small text-dark mb-1">URL de destination active <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted" style="border-radius: 8px 0 0 8px;"><i class="bi bi-globe"></i></span>
                                    <input type="text" name="target_url" id="targetUrlInput" class="form-control @error('target_url') is-invalid @enderror" 
                                           value="{{ old('target_url', $dynamicQr->target_url) }}" required style="border-radius: 0 8px 8px 0;">
                                </div>
                                <div class="d-flex align-items-center gap-1.5 mt-1.5">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Raccourcis :</span>
                                    <span class="quick-url-chip" onclick="applyEditPrefix('https://')">https://</span>
                                    <span class="quick-url-chip" onclick="applyEditPrefix('https://g.page/r/')">Avis Google</span>
                                    <span class="quick-url-chip" onclick="applyEditPrefix('https://instagram.com/')">Instagram</span>
                                    <span class="quick-url-chip" onclick="applyEditPrefix('https://wa.me/')">WhatsApp</span>
                                </div>
                                @error('target_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold small text-dark mb-1">Statut opérationnel</label>
                                <select name="status" class="form-select" style="border-radius: 8px;">
                                    <option value="active" {{ old('status', $dynamicQr->status) === 'active' ? 'selected' : '' }}>Actif (En ligne & Routable)</option>
                                    <option value="inactive" {{ old('status', $dynamicQr->status) === 'inactive' ? 'selected' : '' }}>Inactif (Suspendu)</option>
                                    <option value="archived" {{ old('status', $dynamicQr->status) === 'archived' ? 'selected' : '' }}>Archivé</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <a href="{{ route('admin.dynamic-qrs.show', $dynamicQr) }}" class="btn btn-light border" style="border-radius: 8px;">Annuler</a>
                            <button type="submit" class="btn text-white px-4 fw-semibold shadow-sm" style="background-color: #6320ee; border-radius: 8px;">
                                <i class="bi bi-check-lg me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function applyEditPrefix(prefix) {
    const input = document.getElementById('targetUrlInput');
    if (input) {
        input.value = prefix;
        input.focus();
    }
}
</script>
@endpush
@endsection
