@extends('layouts.admin')

@section('title', 'Client: ' . $client->contact_name)
@section('page_title', $client->contact_name)
@section('page_subtitle', ($client->project_name ? 'Projet: ' . $client->project_name . ' • ' : '') . 'Historique complet, inventaire connecté et identifiants')

@section('content')
<div class="row g-4">
    <!-- Left Column: Client Profile & Credentials -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm rounded-3">
            <div class="text-center mb-4 pb-3 border-bottom">
                @if($client->logo)
                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded-3 border p-2 mb-3 bg-white" style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-inline-flex align-items-center justify-content-center mb-3 fs-2" style="width: 80px; height: 80px;">
                        {{ substr($client->contact_name ?: $client->company_name, 0, 1) }}
                    </div>
                @endif
                <h4 class="fw-bold mb-1">{{ $client->contact_name }}</h4>
                @if($client->project_name)
                    <div class="badge bg-primary-subtle text-primary border mb-2 px-3 py-1">
                        <i class="bi bi-folder2-open me-1"></i> Projet: {{ $client->project_name }}
                    </div>
                @endif
                <div>
                    <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $client->status === 'active' ? 'Compte Actif' : 'Compte Inactif' }}
                    </span>
                </div>
            </div>

            <!-- Access Credentials Box -->
            <div class="p-3 bg-light rounded-3 border mb-4">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Code d'Accès au Portail Client</small>
                <div class="font-monospace fw-bold fs-5 text-primary my-1">{{ $client->access_code ?? 'Non défini' }}</div>
                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <span>Mot de passe :</span>
                    @if($client->is_password_set)
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-shield-check"></i> Défini & Sécurisé</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i> En attente du 1er login</span>
                    @endif
                </div>

                <div class="mt-3 pt-2 border-top">
                    <form action="{{ route('admin.clients.reset-access', $client) }}" method="POST" onsubmit="return confirm('Régénérer un nouveau code d\'accès ? Le client devra réinitialiser son mot de passe.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100 rounded-pill">
                            <i class="bi bi-arrow-repeat me-1"></i> Réinitialiser le Code d'Accès
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact & Details Requested by user -->
            <div class="d-flex flex-column gap-2 small mb-4">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Nom du Client :</span>
                    <strong class="text-dark">{{ $client->contact_name }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Nom du Projet :</span>
                    <strong class="text-primary">{{ $client->project_name ?: 'Non spécifié' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Ville :</span>
                    <strong class="text-dark">{{ $client->city ?: 'Non spécifiée' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Téléphone :</span>
                    <strong class="text-dark font-monospace">{{ $client->phone ?: 'Non spécifié' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Entreprise :</span>
                    <strong class="text-dark">{{ $client->company_name ?: 'N/A' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Email :</span>
                    <strong class="text-dark">{{ $client->email }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Adresse :</span>
                    <strong class="text-dark">{{ $client->address ? $client->address.', ' : '' }}{{ $client->country ?: 'Maroc' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Client Depuis :</span>
                    <strong class="text-dark">{{ $client->created_at->format('d/m/Y') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Dernier Login Portail :</span>
                    <strong class="text-dark">{{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Jamais' }}</strong>
                </div>
            </div>

            @if($client->notes)
                <div class="mb-4 p-2 bg-light rounded small">
                    <div class="fw-semibold text-muted mb-1">Notes Internes :</div>
                    <p class="mb-0 text-dark">{{ $client->notes }}</p>
                </div>
            @endif

            <div class="d-flex gap-2">
                <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-pencil me-1"></i> Modifier le Client
                </a>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary btn-sm">
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Tabs (Historic Timeline, QR Codes, Digital Cards, NFC Tags) -->
    <div class="col-lg-8">
        <!-- Quick Performance Counters -->
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                            <i class="bi bi-qr-code fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold">Scans QR</span>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($totalScans) }}</div>
                            <small class="text-muted">{{ $client->qrCodes->count() }} code(s)</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2 bg-purple-subtle text-purple" style="background-color: #ede9fe; color: #6d28d9;">
                            <i class="bi bi-person-vcard fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold">Vues Cartes</span>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($totalCardViews) }}</div>
                            <small class="text-muted">{{ $client->digitalCards->count() }} carte(s)</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2 bg-info-subtle text-info">
                            <i class="bi bi-phone-vibrate fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold">Taps NFC</span>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($totalNfcTaps) }}</div>
                            <small class="text-muted">{{ $client->nfcTags->count() }} tag(s)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-header bg-white border-bottom-0 pt-3 px-3">
                <ul class="nav nav-pills card-header-pills" id="clientTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold small" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane" type="button" role="tab">
                            <i class="bi bi-clock-history me-1"></i> Historique Complet ({{ $historicEvents->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small" id="qrs-tab" data-bs-toggle="tab" data-bs-target="#qrs-pane" type="button" role="tab">
                            <i class="bi bi-qr-code me-1"></i> QR Codes ({{ $client->qrCodes->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small" id="cards-tab" data-bs-toggle="tab" data-bs-target="#cards-pane" type="button" role="tab">
                            <i class="bi bi-person-vcard me-1"></i> Cartes Visite ({{ $client->digitalCards->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small" id="nfc-tab" data-bs-toggle="tab" data-bs-target="#nfc-pane" type="button" role="tab">
                            <i class="bi bi-broadcast me-1"></i> Tags NFC ({{ $client->nfcTags->count() }})
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-3 pt-2">
                <div class="tab-content" id="clientTabsContent">

                    {{-- TAB 1: ALL HISTORIC ABOUT CLIENT --}}
                    <div class="tab-pane fade show active" id="history-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
                            <h6 class="fw-bold mb-0">Journal d'Audit & Historique Chronologique</h6>
                            <span class="badge bg-light text-muted border">Derniers 50 événements</span>
                        </div>

                        <div class="timeline position-relative ps-4 border-start border-2 ms-2 my-3">
                            @forelse($historicEvents as $event)
                                <div class="position-relative mb-4">
                                    <div class="position-absolute rounded-circle bg-primary" style="width: 12px; height: 12px; left: -25px; top: 4px;"></div>
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start gap-1">
                                        <div>
                                            <span class="badge bg-dark-subtle text-dark border font-monospace me-1" style="font-size: 0.72rem;">
                                                {{ $event->action }}
                                            </span>
                                            <span class="small fw-semibold text-dark">
                                                {{ $event->user ? $event->user->name : 'Système' }}
                                            </span>
                                            @if($event->details)
                                                <div class="small text-muted mt-1 bg-light p-2 rounded">
                                                    @if(is_array($event->details))
                                                        @foreach($event->details as $k => $v)
                                                            <div><strong>{{ $k }}:</strong> {{ is_array($v) ? json_encode($v) : $v }}</div>
                                                        @endforeach
                                                    @else
                                                        {{ $event->details }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-sm-end text-muted small flex-shrink-0" style="font-size: 0.75rem;">
                                            <div>{{ $event->created_at->format('d/m/Y H:i') }}</div>
                                            <div>{{ $event->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">
                                    Aucun événement consigné dans l'historique pour l'instant.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB 2: QR CODES --}}
                    <div class="tab-pane fade" id="qrs-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
                            <h6 class="fw-bold mb-0">QR Codes Dynamiques Assignés</h6>
                            <a href="{{ route('admin.qr-codes.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-plus-lg me-1"></i> Nouveau QR pour ce client
                            </a>
                        </div>

                        {{-- Assign QR Code Form --}}
                        @if($availableQrs->count() > 0)
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <form action="{{ route('admin.clients.assign-qr', $client) }}" method="POST" class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                                    @csrf
                                    <div class="flex-grow-1">
                                        <select name="qr_code_id" class="form-select form-select-sm" required>
                                            <option value="">-- Allouer un QR non-assigné existant --</option>
                                            @foreach($availableQrs as $uqr)
                                                <option value="{{ $uqr->id }}">
                                                    {{ $uqr->name }} ({{ $uqr->public_code }} - Type: {{ $uqr->type }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-sm rounded-pill text-nowrap px-3">
                                        <i class="bi bi-plus-lg me-1"></i> Allouer
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom du QR</th>
                                        <th>Code VP</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Scans</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->qrCodes as $qr)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.qr-codes.show', $qr) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $qr->name }}
                                                </a>
                                            </td>
                                            <td><span class="badge bg-dark-subtle text-dark border font-monospace">{{ $qr->public_code }}</span></td>
                                            <td><span class="badge bg-light text-dark border">{{ $qr->type }}</span></td>
                                            <td>
                                                <span class="badge {{ $qr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $qr->status }}
                                                </span>
                                            </td>
                                            <td class="fw-bold">{{ number_format($qr->scan_count) }}</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.qr-codes.show', $qr) }}" class="btn btn-outline-secondary" title="Détails"><i class="bi bi-eye"></i></a>
                                                    <a href="{{ route('admin.qr-codes.edit', $qr) }}" class="btn btn-outline-secondary" title="Modifier"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{ url('/q/' . $qr->public_code) }}" target="_blank" class="btn btn-outline-primary" title="Tester le Scan"><i class="bi bi-box-arrow-up-right"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Aucun QR Code assigné à ce client.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 3: DIGITAL BUSINESS CARDS --}}
                    <div class="tab-pane fade" id="cards-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
                            <h6 class="fw-bold mb-0">Cartes Visite Digitales Connectées</h6>
                            <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-plus-lg me-1"></i> Créer une Carte Visite
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titulaire</th>
                                        <th>Slug Unique</th>
                                        <th>Template</th>
                                        <th>Vues</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->digitalCards as $card)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $card->full_name ?: $card->name }}</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">{{ $card->job_title }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="badge bg-light text-dark border font-monospace text-decoration-none">
                                                    {{ $card->slug }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                                </a>
                                            </td>
                                            <td><span class="badge bg-info-subtle text-info text-capitalize">{{ $card->template }}</span></td>
                                            <td class="fw-bold">{{ $card->views_count }}</td>
                                            <td>
                                                <span class="badge {{ $card->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $card->status }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.digital-cards.edit', $card) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Aucune carte de visite digitale créée pour ce client.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 4: NFC TAGS --}}
                    <div class="tab-pane fade" id="nfc-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
                            <h6 class="fw-bold mb-0">Tags & Cartes NFC Physiques</h6>
                            <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-plus-lg me-1"></i> Créer un Tag NFC
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom du Tag</th>
                                        <th>UID</th>
                                        <th>Cible</th>
                                        <th>Taps</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->nfcTags as $tag)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $tag->name }}</div>
                                            </td>
                                            <td><span class="badge bg-dark-subtle text-dark border font-monospace">{{ $tag->tag_uid }}</span></td>
                                            <td>
                                                <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $tag->target_type) }}</span>
                                            </td>
                                            <td class="fw-bold">{{ $tag->tap_count }}</td>
                                            <td>
                                                <span class="badge {{ $tag->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $tag->status }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.nfc-tags.show', $tag) }}" class="btn btn-outline-secondary"><i class="bi bi-graph-up"></i></a>
                                                    <a href="{{ route('admin.nfc-tags.edit', $tag) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{ $tag->public_url }}" target="_blank" class="btn btn-outline-info"><i class="bi bi-play-circle"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Aucun tag NFC programmé pour ce client.
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
</div>
@endsection
