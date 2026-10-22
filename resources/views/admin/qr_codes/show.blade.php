@extends('layouts.admin')

@section('title', 'QR Code: ' . $qrCode->name)
@section('page_title', $qrCode->name)
@section('page_subtitle', 'Code de Suivi: ' . $qrCode->public_code . ' • Gestion de la destination en direct et téléchargement des cartes PDF')

@section('content')
<div class="row g-4">
    <!-- Left Column: High-Res QR Card & Action Triggers -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white text-center mb-4 border-0 shadow-sm rounded-3">
            <!-- Rendered Vector SVG -->
            <div class="p-3 bg-light rounded-4 border d-inline-block mb-3 shadow-sm" style="max-width: 100%;">
                {!! $svgPreview !!}
            </div>

            <h4 class="fw-bold mb-1">{{ $qrCode->name }}</h4>
            <div class="mb-3">
                <span class="badge {{ $qrCode->status === 'active' ? 'bg-success' : 'bg-secondary' }} px-3 py-1">
                    {{ strtoupper($qrCode->status) }}
                </span>
            </div>

            <!-- Dynamic URL Box with VP Identifier -->
            <div class="p-3 bg-light rounded-3 text-start border mb-3">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">URL Dynamique Imprimée sur le QR</small>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <a href="{{ $qrCode->dynamic_url }}" target="_blank" class="fw-bold text-primary font-monospace small text-truncate text-decoration-none">
                        {{ $qrCode->dynamic_url }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Identifiers Box -->
            <div class="row g-2 mb-4 text-start">
                <div class="col-6">
                    <div class="p-2 border rounded-3 bg-light">
                        <small class="text-muted d-block" style="font-size: 0.7rem;">CODE DE SUIVI UNIQUE</small>
                        <span class="font-monospace fw-bold text-dark small">{{ $qrCode->public_code }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 border rounded-3 bg-light">
                        <small class="text-muted d-block" style="font-size: 0.7rem;">CLÉ PORTAIL CLIENT</small>
                        <span class="font-monospace fw-bold text-primary small">{{ $qrCode->access_code }}</span>
                    </div>
                </div>
            </div>

            {{-- 3 PDF Printable Card Templates Buttons --}}
            <div class="card border border-danger-subtle bg-danger-subtle bg-opacity-10 rounded-3 p-3 mb-3 text-start">
                <h6 class="fw-bold text-danger mb-2">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Cartes Imprimables PDF (3 Modèles)
                </h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.qr-codes.card-pdf', [$qrCode, 'template' => 'google_review']) }}" class="btn btn-outline-danger btn-sm text-start d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-star-fill text-warning me-2"></i> Google Review Card</span>
                        <i class="bi bi-download"></i>
                    </a>
                    <a href="{{ route('admin.qr-codes.card-pdf', [$qrCode, 'template' => 'social_media']) }}" class="btn btn-outline-danger btn-sm text-start d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-share-fill text-primary me-2"></i> Social Media Card</span>
                        <i class="bi bi-download"></i>
                    </a>
                    <a href="{{ route('admin.qr-codes.card-pdf', [$qrCode, 'template' => 'menu']) }}" class="btn btn-outline-danger btn-sm text-start d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-journal-richtext text-dark me-2"></i> Menu Restaurant Card</span>
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </div>

            <!-- Standard Vector Downloads -->
            <div class="d-flex flex-column gap-2 mb-3">
                <a href="{{ route('admin.qr-codes.download', [$qrCode, 'svg']) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bi bi-filetype-svg me-1"></i> Télécharger SVG Vectoriel
                </a>
                <a href="{{ route('admin.qr-codes.download', [$qrCode, 'png']) }}" class="btn btn-outline-success btn-sm rounded-pill">
                    <i class="bi bi-filetype-png me-1"></i> Télécharger PNG HD (600px)
                </a>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.qr-codes.edit', $qrCode) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">
                    <i class="bi bi-sliders me-1"></i> Personnaliser Design & Couleurs
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Live Destination Management & Analytics -->
    <div class="col-lg-8">
        <!-- Scan Statistics KPI -->
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="card card-custom p-3 bg-white border-0 shadow-sm rounded-3">
                    <span class="text-muted small fw-semibold">Total des Scans</span>
                    <div class="fs-2 fw-bold text-primary mt-1">{{ number_format($qrCode->scan_count) }}</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card card-custom p-3 bg-white border-0 shadow-sm rounded-3">
                    <span class="text-muted small fw-semibold">Client Assigné</span>
                    <div class="fs-6 fw-bold text-dark mt-2 text-truncate">
                        {{ $qrCode->client ? $qrCode->client->company_name : 'Non assigné' }}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card card-custom p-3 bg-white border-0 shadow-sm rounded-3">
                    <span class="text-muted small fw-semibold">Format de Contenu</span>
                    <div class="fs-6 fw-bold text-dark mt-2 text-capitalize">
                        {{ str_replace('_', ' ', $qrCode->type) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- IN-DASHBOARD DESTINATION LINK MANAGEMENT (WITHOUT REPRINTING) --}}
        <div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Modifier la Destination en Direct
                    </h5>
                    <p class="text-muted small mb-0">Changez le lien où atterrira l'utilisateur après le scan, sans jamais réimprimer le QR code physique.</p>
                </div>
            </div>

            <form action="{{ route('admin.qr-codes.destination', $qrCode) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Lien de Redirection Cible (URL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="target_redirect_url" class="form-control font-monospace" placeholder="https://votre-nouveau-lien.com" value="{{ old('target_redirect_url', $qrCode->target_redirect_url ?: ($qrCode->content?->content_data['url'] ?? '')) }}">
                        </div>
                        <div class="form-text">Si configuré, toute personne scannant <code>/q/{{ $qrCode->public_code }}</code> sera immédiatement redirigée vers cette URL.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Modèle de Carte PDF Associé</label>
                        <select name="card_template" class="form-select">
                            <option value="google_review" {{ $qrCode->card_template === 'google_review' ? 'selected' : '' }}>Google Review Card</option>
                            <option value="social_media" {{ $qrCode->card_template === 'social_media' ? 'selected' : '' }}>Social Media Card</option>
                            <option value="menu" {{ $qrCode->card_template === 'menu' ? 'selected' : '' }}>Menu Card (Restaurant)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Statut du QR Code</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $qrCode->status === 'active' ? 'selected' : '' }}>Actif (Redirection normale)</option>
                            <option value="inactive" {{ $qrCode->status === 'inactive' ? 'selected' : '' }}>Inactif (Message d'indisponibilité)</option>
                            <option value="suspended" {{ $qrCode->status === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 rounded-pill">
                            <i class="bi bi-check2-circle"></i>
                            <span>Enregistrer la Destination</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Scans for this QR -->
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-3">
            <h5 class="fw-bold mb-3">Journal Récent des Scans</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light text-uppercase" style="font-size: 0.72rem;">
                        <tr>
                            <th>Date & Heure</th>
                            <th>Appareil</th>
                            <th>OS & Navigateur</th>
                            <th>Ville / Région</th>
                            <th class="text-end">IP Hash</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qrCode->scans()->latest()->take(20)->get() as $scan)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $scan->scanned_at ? $scan->scanned_at->format('d/m/Y H:i') : $scan->created_at->format('d/m/Y H:i') }}</div>
                                    <small class="text-muted">{{ ($scan->scanned_at ?: $scan->created_at)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border text-capitalize">
                                        <i class="bi bi-{{ $scan->device_type === 'mobile' ? 'phone' : ($scan->device_type === 'tablet' ? 'tablet' : 'laptop') }} me-1"></i>
                                        {{ $scan->device_type ?: 'Inconnu' }}
                                    </span>
                                </td>
                                <td>{{ $scan->operating_system ?: 'OS Inconnu' }} &bull; {{ $scan->browser ?: 'Navigateur Inconnu' }}</td>
                                <td>{{ $scan->city ? $scan->city . ', ' . $scan->country : 'Non localisé' }}</td>
                                <td class="text-end font-monospace text-muted">{{ substr($scan->ip_hash, 0, 8) }}...</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Aucun scan enregistré pour ce QR code pour l'instant.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
