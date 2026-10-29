@extends('layouts.app')

@section('title', $dynamicQr->code . ' - Dynamic QR Details & Télémétrie')

@push('styles')
<style>
.vp-code-badge-hero {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'SF Mono', Monaco, Menlo, 'Courier New', monospace;
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    padding: 0.35rem 0.85rem;
    border-radius: 9px;
    background: #f3f0ff;
    color: #6320ee;
    border: 1.5px solid #ddd6fe;
}
.kpi-mini-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.1rem;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.kpi-mini-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.08);
}
.qr-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.06);
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
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $dynamicQr->code }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2.5">
                <span class="vp-code-badge-hero">{{ $dynamicQr->code }}</span>
                <h1 class="h4 fw-bold text-dark mb-0">{{ $dynamicQr->label }}</h1>
                @if($dynamicQr->status === 'active')
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle py-1 px-2.5 small fw-semibold">
                        <span class="d-inline-block rounded-circle bg-success me-1" style="width: 6px; height: 6px;"></span>En Ligne
                    </span>
                @else
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle py-1 px-2.5 small fw-semibold">
                        {{ ucfirst($dynamicQr->status) }}
                    </span>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dynamic-qrs.edit', $dynamicQr) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 shadow-2xs" style="border-radius: 8px;">
                <i class="bi bi-pencil"></i>
                <span>Modifier les paramètres</span>
            </a>
            <a href="{{ route('qr.redirect', ['code' => $dynamicQr->code]) }}" target="_blank" class="btn text-white d-inline-flex align-items-center gap-1.5 shadow-sm" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); border-radius: 8px;">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Tester le scan (302 Live)</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: QR Code & Target Management -->
        <div class="col-12 col-lg-5">
            <!-- QR Display Card -->
            <div class="qr-hero-card p-4 text-center mb-4">
                <!-- SVG Canvas Container -->
                <div class="d-inline-block p-3.5 bg-white border rounded-4 shadow-sm mb-3 position-relative">
                    <div style="width: 250px; height: 250px; margin: 0 auto;" class="d-flex align-items-center justify-content-center">
                        {!! $svgPreview !!}
                    </div>
                </div>

                <!-- Permanent Code & Redirection Info -->
                <div class="mb-3">
                    <div class="small text-muted mb-1 font-mono text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Route de redirection permanente</div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control bg-light font-mono text-dark small" value="{{ $dynamicQr->encoded_url }}" readonly id="encodedUrlInput" style="border-radius: 8px 0 0 8px;">
                        <button class="btn btn-outline-secondary" type="button" id="btnCopyEncodedUrl" style="border-radius: 0 8px 8px 0;" title="Copier l'URL permanente">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Export Downloads -->
                <div class="d-flex flex-wrap justify-content-center gap-2 pt-2 border-top">
                    <!-- PDF Dropdown -->
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Exporter PDF
                        </button>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-3">
                            <li class="dropdown-header small text-uppercase font-mono">Format Carte Standard (85×55mm)</li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $dynamicQr->id, 'template' => 'google_review', 'dimension' => '85x55']) }}">
                                    <i class="bi bi-star-fill text-warning"></i> Carte Avis Google
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $dynamicQr->id, 'template' => 'social_media', 'dimension' => '85x55']) }}">
                                    <i class="bi bi-share-fill text-primary"></i> Carte Réseaux Sociaux
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $dynamicQr->id, 'template' => 'menu', 'dimension' => '85x55']) }}">
                                    <i class="bi bi-cup-hot-fill text-warning"></i> Carte Menu Digital
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="dropdown-header small text-uppercase font-mono">Format Chevalet & Plaque</li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $dynamicQr->id, 'template' => $dynamicQr->template_type, 'dimension' => 'a6']) }}">
                                    <i class="bi bi-printer text-success"></i> Plaque A6 (105×148mm)
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('admin.dynamic-qrs.svg', $dynamicQr) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                        <i class="bi bi-filetype-svg me-1"></i> SVG Vectoriel
                    </a>
                    <a href="{{ route('admin.dynamic-qrs.png', $dynamicQr) }}" class="btn btn-sm btn-outline-info" style="border-radius: 8px;">
                        <i class="bi bi-filetype-png me-1"></i> PNG HD
                    </a>
                </div>
            </div>

            <!-- Instant Target Destination Editor -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-2 d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width: 30px; height: 30px;">
                            <i class="bi bi-lightning-charge-fill fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Destination Active (Cible)</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle font-mono small">En direct</span>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.dynamic-qrs.target', $dynamicQr) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted mb-1">URL de redirection vers laquelle pointe ce QR</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted" style="border-radius: 8px 0 0 8px;"><i class="bi bi-globe"></i></span>
                                <input type="text" name="target_url" class="form-control font-mono small" value="{{ old('target_url', $dynamicQr->target_url) }}" required style="border-radius: 0 8px 8px 0;">
                            </div>
                            <div class="form-text small text-muted mt-1.5">
                                Redirection instantanée sans modification du QR code imprimé.
                            </div>
                        </div>

                        <button type="submit" class="btn text-white w-100 fw-semibold py-2 shadow-sm" style="background-color: #6320ee; border-radius: 8px;">
                            <i class="bi bi-check-lg me-1"></i> Mettre à jour la cible
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Metrics, Client & Scan History Logs -->
        <div class="col-12 col-lg-7">
            <!-- Key Metric Mini-Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-sm-4">
                    <div class="kpi-mini-box shadow-2xs">
                        <div class="text-muted small text-uppercase font-mono fw-semibold mb-1" style="font-size: 0.72rem;">Scans Cumulés</div>
                        <div class="fs-4 fw-extrabold font-mono text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-broadcast text-success"></i>
                            {{ number_format($dynamicQr->scans_count) }}
                        </div>
                        <div class="text-muted small mt-1" style="font-size: 0.72rem;">Redirections uniques</div>
                    </div>
                </div>
                <div class="col-6 col-sm-4">
                    <div class="kpi-mini-box shadow-2xs">
                        <div class="text-muted small text-uppercase font-mono fw-semibold mb-1" style="font-size: 0.72rem;">Gabarit</div>
                        <div class="fs-6 fw-bold text-dark text-capitalize mt-1.5 d-flex align-items-center gap-1.5">
                            <i class="bi bi-palette text-primary"></i>
                            {{ str_replace('_', ' ', $dynamicQr->template_type) }}
                        </div>
                        <div class="text-muted small mt-1" style="font-size: 0.72rem;">Format d'impression</div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="kpi-mini-box shadow-2xs">
                        <div class="text-muted small text-uppercase font-mono fw-semibold mb-1" style="font-size: 0.72rem;">Statut Système</div>
                        <div class="mt-1.5">
                            @if($dynamicQr->status === 'active')
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold px-2.5 py-1">
                                    <span class="d-inline-block rounded-circle bg-success me-1" style="width: 6px; height: 6px;"></span>Actif & Routable
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle fw-semibold px-2.5 py-1">
                                    {{ ucfirst($dynamicQr->status) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1" style="font-size: 0.72rem;">Disponibilité live</div>
                    </div>
                </div>
            </div>

            <!-- Client Info Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 42px; height: 42px;">
                                <i class="bi bi-building fs-5"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.5px;">Client Propriétaire</div>
                                @if($dynamicQr->client)
                                    <h6 class="mb-0 fw-bold text-dark">{{ $dynamicQr->client->name }}</h6>
                                    <div class="text-muted small">Projet : {{ $dynamicQr->client->project_name ?: 'Standard' }} &bull; Ville : {{ $dynamicQr->client->city ?: 'N/A' }}</div>
                                @else
                                    <h6 class="mb-0 fw-semibold text-muted fst-italic">Non assigné (Pool général)</h6>
                                @endif
                            </div>
                        </div>
                        @if($dynamicQr->client)
                            <a href="{{ route('admin.clients.show', $dynamicQr->client) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                Voir la fiche client
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Scan Log Stream -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Historique des Scans & Télémétrie</h6>
                        <small class="text-muted">Événements enregistrés lors des redirections 302</small>
                    </div>
                    <span class="badge bg-light text-secondary border font-mono small">
                        {{ $dynamicQr->scanLogs->count() }} Derniers Événements
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase font-mono" style="font-size: 0.72rem;">
                                <tr>
                                    <th class="ps-4 py-2.5">Adresse IP</th>
                                    <th class="py-2.5">Appareil / Navigateur</th>
                                    <th class="pe-4 py-2.5 text-end">Date & Heure</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y small">
                                @forelse($dynamicQr->scanLogs as $log)
                                    <tr>
                                        <td class="ps-4 font-mono fw-semibold text-primary">
                                            <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $log->ip_address ?: '127.0.0.1' }}
                                        </td>
                                        <td>
                                            @php
                                                $ua = $log->user_agent ?: '';
                                                $deviceIcon = 'bi-globe';
                                                $deviceName = 'Navigateur Web';
                                                if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
                                                    $deviceIcon = 'bi-apple';
                                                    $deviceName = 'Apple iOS';
                                                } elseif (str_contains($ua, 'Android')) {
                                                    $deviceIcon = 'bi-android2';
                                                    $deviceName = 'Android';
                                                } elseif (str_contains($ua, 'Windows')) {
                                                    $deviceIcon = 'bi-windows';
                                                    $deviceName = 'Windows PC';
                                                } elseif (str_contains($ua, 'Macintosh')) {
                                                    $deviceIcon = 'bi-apple';
                                                    $deviceName = 'macOS';
                                                }
                                            @endphp
                                            <div class="d-flex align-items-center gap-1.5">
                                                <i class="bi {{ $deviceIcon }} text-secondary"></i>
                                                <span class="fw-semibold text-dark">{{ $deviceName }}</span>
                                            </div>
                                            <div class="text-truncate text-muted font-mono" style="max-width: 280px; font-size: 0.68rem;" title="{{ $ua }}">
                                                {{ $ua ?: 'User Agent non disponible' }}
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end font-mono text-muted">
                                            <div>{{ $log->scanned_at ? $log->scanned_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
                                            <small class="text-secondary" style="font-size: 0.68rem;">{{ $log->scanned_at ? $log->scanned_at->diffForHumans() : '' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-clock-history fs-3 d-block mb-1 text-muted"></i>
                                            Aucun scan enregistré pour {{ $dynamicQr->code }} pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCopy = document.getElementById('btnCopyEncodedUrl');
    const inputUrl = document.getElementById('encodedUrlInput');
    if (btnCopy && inputUrl) {
        btnCopy.addEventListener('click', function() {
            navigator.clipboard.writeText(inputUrl.value).then(() => {
                const icon = btnCopy.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-check2 text-success';
                    setTimeout(() => { icon.className = 'bi bi-clipboard'; }, 1500);
                }
            });
        });
    }
});
</script>
@endpush
@endsection
