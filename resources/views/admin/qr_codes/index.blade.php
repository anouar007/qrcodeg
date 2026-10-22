@extends('layouts.admin')

@section('title', 'Gestion des QR Codes Dynamiques VP')
@section('page_title', 'QR Codes Dynamiques Studio')
@section('page_subtitle', 'Codes de suivi uniques débutant par VP • Redirection modifiable à tout moment sans réimpression • 3 templates de cartes PDF')

@section('content')
<div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm rounded-3">
    <!-- Top Action Row -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Répertoire des QR Codes Dynamiques</h5>
            <small class="text-muted">Total: {{ $qrCodes->total() }} code(s) actif(s) &bull; Tous trackés avec préfixe unique <strong class="text-primary font-monospace">VP-</strong></small>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-dark rounded-pill" id="batchZipBtn" onclick="submitBatchZip();">
                <i class="bi bi-file-earmark-zip me-1"></i> Télécharger Sélection ZIP
            </button>
            <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary d-flex align-items-center gap-2 rounded-pill">
                <i class="bi bi-plus-circle me-1"></i>
                <span>Générer un ou plusieurs QR</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <form method="GET" action="{{ route('admin.qr-codes.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom, code VP-, client..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="client_id" class="form-select">
                <option value="">Tous les Clients</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->company_name }} ({{ $client->contact_name }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Tous les Statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Filtrer</button>
            @if(request()->hasAny(['search', 'client_id', 'status', 'type']))
                <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
            @endif
        </div>
    </form>

    <!-- Batch ZIP Form (Hidden) -->
    <form id="batchZipForm" action="{{ route('admin.qr-codes.batch-zip') }}" method="POST" style="display: none;">
        @csrf
        <div id="batchZipInputs"></div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onclick="toggleSelectAll(this)">
                    </th>
                    <th>QR Code</th>
                    <th>Code de Suivi (VP)</th>
                    <th>Client / Projet</th>
                    <th>Destination Réelle (Modifiable)</th>
                    <th>Statut</th>
                    <th class="text-center">Scans</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qrCodes as $qr)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input qr-select-item" value="{{ $qr->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{ route('admin.qr-codes.show', $qr) }}" class="p-1 bg-light border rounded-3 d-inline-block text-center" style="width: 44px; height: 44px; overflow: hidden;">
                                    <i class="bi bi-qr-code fs-4 text-primary"></i>
                                </a>
                                <div>
                                    <a href="{{ route('admin.qr-codes.show', $qr) }}" class="fw-bold text-dark text-decoration-none d-block">
                                        {{ $qr->name }}
                                    </a>
                                    <div class="small text-muted">
                                        Template : <span class="badge bg-light text-muted border text-capitalize">{{ str_replace('_', ' ', $qr->card_template ?: 'Google Review') }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-dark text-white font-monospace px-2 py-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                    {{ $qr->public_code }}
                                </span>
                                <a href="{{ $qr->dynamic_url }}" target="_blank" class="btn btn-sm btn-link p-0 text-primary ms-1" title="Tester le scan dynamique">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                            <small class="text-muted font-monospace" style="font-size: 0.68rem;">/q/{{ $qr->public_code }}</small>
                        </td>
                        <td>
                            @if($qr->client)
                                <a href="{{ route('admin.clients.show', $qr->client) }}" class="text-dark fw-semibold text-decoration-none">
                                    {{ $qr->client->company_name }}
                                </a>
                                @if($qr->client->project_name)
                                    <div class="small text-muted">{{ $qr->client->project_name }}</div>
                                @endif
                            @else
                                <span class="badge bg-light text-muted border">Non assigné</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $dest = $qr->target_redirect_url ?: ($qr->content?->content_data['url'] ?? null);
                            @endphp
                            @if($dest)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-truncate font-monospace small text-dark" style="max-width: 220px;" title="{{ $dest }}">
                                        {{ $dest }}
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1" title="Modifier la destination" onclick="openDestinationModal('{{ $qr->id }}', '{{ addslashes($qr->name) }}', '{{ addslashes($qr->target_redirect_url ?: '') }}', '{{ $qr->card_template ?: 'google_review' }}', '{{ $qr->status }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2 small" onclick="openDestinationModal('{{ $qr->id }}', '{{ addslashes($qr->name) }}', '', '{{ $qr->card_template ?: 'google_review' }}', '{{ $qr->status }}')">
                                    <i class="bi bi-plus-lg me-1"></i> Définir Destination
                                </button>
                            @endif
                        </td>
                        <td>
                            @if($qr->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Actif</span>
                            @elseif($qr->status === 'suspended')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Suspendu</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Inactif</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold fs-6">
                            {{ number_format($qr->scan_count) }}
                            @if($qr->last_scanned_at)
                                <small class="text-muted d-block fw-normal" style="font-size: 0.68rem;">{{ $qr->last_scanned_at->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex gap-1">
                                {{-- Quick Destination Update Button --}}
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Modifier le lien cible en direct (sans réimprimer)" onclick="openDestinationModal('{{ $qr->id }}', '{{ addslashes($qr->name) }}', '{{ addslashes($qr->target_redirect_url ?: '') }}', '{{ $qr->card_template ?: 'google_review' }}', '{{ $qr->status }}')">
                                    <i class="bi bi-link-45deg"></i>
                                </button>

                                {{-- PDF Card Download with 3 Templates --}}
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Télécharger Carte PDF (Google Review, Social Media, Menu)" onclick="openPdfModal('{{ $qr->id }}', '{{ addslashes($qr->name) }}', '{{ $qr->card_template ?: 'google_review' }}')">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>

                                <div class="dropdown d-inline">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Plus
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.show', $qr) }}"><i class="bi bi-eye me-2 text-primary"></i> Détails & Analytics</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.edit', $qr) }}"><i class="bi bi-sliders me-2 text-info"></i> Personnaliser Design & Couleurs</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header small text-uppercase">Téléchargement Fichiers</h6></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.download', [$qr, 'svg']) }}"><i class="bi bi-filetype-svg me-2 text-warning"></i> Vectoriel SVG</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.qr-codes.download', [$qr, 'png']) }}"><i class="bi bi-filetype-png me-2 text-success"></i> PNG Haute Définition</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.qr-codes.destroy', $qr) }}" method="POST" onsubmit="return confirm('Supprimer ce QR code ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-qr-code display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h5 class="fw-bold">Aucun QR code dynamique trouvé</h5>
                            <p class="small text-muted mb-3">Générez un ou plusieurs QR codes avec le préfixe VP- et vos cartes PDF prêtes à imprimer.</p>
                            <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Générer des QR Codes
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($qrCodes->hasPages())
        <div class="mt-4 pt-3 border-top">
            {{ $qrCodes->links() }}
        </div>
    @endif
</div>

{{-- MODAL 1: MANAGE DESTINATION LINK WITHOUT TOUCHING PHYSICAL QR --}}
<div class="modal fade" id="destinationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="destinationModalTitle">
                    <i class="bi bi-link-45deg me-2 text-primary"></i> Gérer la Destination en Direct
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="destinationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small mb-3">
                        <i class="bi bi-shield-check me-1"></i>
                        <strong>Avantage Dynamique :</strong> Modifiez le lien cible ci-dessous. Le QR code imprimé reste <strong>100% identique</strong> et redirigera immédiatement vers cette nouvelle adresse !
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Lien de Redirection Cible (URL) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-globe"></i></span>
                            <input type="url" name="target_redirect_url" id="modalTargetUrl" class="form-control font-monospace" placeholder="https://example.com/nouvelle-page" required>
                        </div>
                        <div class="form-text">Entrez l'URL finale avec https:// (Avis Google, Menu PDF, Page Réseaux, Site Web...)</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Modèle de Carte Associé</label>
                            <select name="card_template" id="modalCardTemplate" class="form-select">
                                <option value="google_review">Google Review Card</option>
                                <option value="social_media">Social Media Card</option>
                                <option value="menu">Menu Card (Restaurant)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Statut du QR</label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="active">Actif (Redirige)</option>
                                <option value="inactive">Inactif (Indisponible)</option>
                                <option value="suspended">Suspendu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i>
                        <span>Enregistrer la Redirection</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: DOWNLOAD PDF CARD WITH SELECTION OF 3 TEMPLATES --}}
<div class="modal fade" id="pdfCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Télécharger la Carte Imprimable PDF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-4">
                    Sélectionnez l'un des <strong>3 designs prêts pour l'impression haute définition (Format A6 / Chevalet)</strong> contenant votre QR code vectoriel et votre code de suivi unique :
                </p>

                <div class="row g-3">
                    {{-- Template 1: Google Review Card --}}
                    <div class="col-md-4">
                        <div class="card h-100 border-2 rounded-3 text-center p-3 template-choice-card" onclick="downloadSelectedTemplate('google_review')" style="cursor: pointer; transition: all 0.2s;">
                            <div class="rounded-3 p-3 mb-3 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                                <div class="fs-4 mb-1">★★★★★</div>
                                <div class="fw-bold small text-uppercase tracking-wider">Avis Google</div>
                            </div>
                            <h6 class="fw-bold mb-1">Google Review Card</h6>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                                Idéal pour comptoirs, réceptions et tables. Incite à laisser un avis 5 étoiles.
                            </p>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                <i class="bi bi-download me-1"></i> Choisir & Télécharger
                            </button>
                        </div>
                    </div>

                    {{-- Template 2: Social Media Card --}}
                    <div class="col-md-4">
                        <div class="card h-100 border-2 rounded-3 text-center p-3 template-choice-card" onclick="downloadSelectedTemplate('social_media')" style="cursor: pointer; transition: all 0.2s;">
                            <div class="rounded-3 p-3 mb-3 text-white" style="background: linear-gradient(135deg, #701a75 0%, #ec4899 100%);">
                                <div class="fs-4 mb-1"><i class="bi bi-share-fill"></i></div>
                                <div class="fw-bold small text-uppercase tracking-wider">Réseaux Sociaux</div>
                            </div>
                            <h6 class="fw-bold mb-1">Social Media Card</h6>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                                Design moderne avec icônes Instagram, TikTok, LinkedIn & Facebook.
                            </p>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                <i class="bi bi-download me-1"></i> Choisir & Télécharger
                            </button>
                        </div>
                    </div>

                    {{-- Template 3: Menu Card --}}
                    <div class="col-md-4">
                        <div class="card h-100 border-2 rounded-3 text-center p-3 template-choice-card" onclick="downloadSelectedTemplate('menu')" style="cursor: pointer; transition: all 0.2s;">
                            <div class="rounded-3 p-3 mb-3 text-white" style="background: linear-gradient(135deg, #18181b 0%, #78350f 100%); border-bottom: 2px solid #d97706;">
                                <div class="fs-4 mb-1"><i class="bi bi-journal-richtext"></i></div>
                                <div class="fw-bold small text-uppercase tracking-wider">Menu Restaurant</div>
                            </div>
                            <h6 class="fw-bold mb-1">Menu Card</h6>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                                Parfait pour chevalets de table, cafés, restaurants et bars gastronomiques.
                            </p>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                <i class="bi bi-download me-1"></i> Choisir & Télécharger
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentPdfQrId = null;

function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.qr-select-item');
    checkboxes.forEach(cb => cb.checked = master.checked);
}

function submitBatchZip() {
    const selected = document.querySelectorAll('.qr-select-item:checked');
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un QR code à inclure dans l\'archive ZIP.');
        return;
    }
    const container = document.getElementById('batchZipInputs');
    container.innerHTML = '';
    selected.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'qr_ids[]';
        input.value = cb.value;
        container.appendChild(input);
    });
    document.getElementById('batchZipForm').submit();
}

function openDestinationModal(qrId, qrName, currentUrl, template, status) {
    document.getElementById('destinationForm').action = '/admin/qr-codes/' + qrId + '/destination';
    document.getElementById('destinationModalTitle').innerHTML = '<i class="bi bi-link-45deg me-2 text-primary"></i> Gérer la Destination : ' + qrName;
    document.getElementById('modalTargetUrl').value = currentUrl;
    document.getElementById('modalCardTemplate').value = template;
    document.getElementById('modalStatus').value = status;

    const modal = new bootstrap.Modal(document.getElementById('destinationModal'));
    modal.show();
}

function openPdfModal(qrId, qrName, currentTemplate) {
    currentPdfQrId = qrId;
    const modal = new bootstrap.Modal(document.getElementById('pdfCardModal'));
    modal.show();
}

function downloadSelectedTemplate(template) {
    if (!currentPdfQrId) return;
    window.location.href = '/admin/qr-codes/' + currentPdfQrId + '/card-pdf?template=' + template;
    bootstrap.Modal.getInstance(document.getElementById('pdfCardModal')).hide();
}
</script>
@endpush
@endsection
