@extends('layouts.app')

@section('title', 'Gestion des Cartes & Fidélisation')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Fidélisation</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title & Quick Action -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-award text-primary me-2"></i>Cartes &amp; Programme Fidélité
            </h1>
            <p class="text-muted small mb-0">Supervisez les entreprises inscrites au programme, les cartes de fidélité et les quotas attribués.</p>
        </div>

        <div>
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-xs" style="background: #5736e9; border-color: #5736e9;" data-bs-toggle="modal" data-bs-target="#enrollClientModal">
                <i class="bi bi-plus-lg"></i>
                <span>Ajouter une Entreprise au Programme</span>
            </button>
        </div>
    </div>

    <!-- Stat Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Cartes Émises</span>
                    <div class="rounded-3 p-2 text-white" style="background: #5736e9;">
                        <i class="bi bi-credit-card-2-front fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['total_cards']) }}</div>
                <div class="text-muted small">
                    <span class="text-success fw-semibold">{{ number_format($stats['active_cards']) }}</span> actives
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Points en Circulation</span>
                    <div class="rounded-3 p-2 text-white bg-warning">
                        <i class="bi bi-star-fill fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['total_points']) }}</div>
                <div class="text-muted small">Points fidélité actifs cumulés</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Transactions &amp; Scans</span>
                    <div class="rounded-3 p-2 text-white bg-success">
                        <i class="bi bi-arrow-left-right fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['total_transactions']) }}</div>
                <div class="text-muted small">Opérations de caisse et scans</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Entreprises avec Fidélité</span>
                    <div class="rounded-3 p-2 text-white bg-info">
                        <i class="bi bi-buildings fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['total_clients']) }}</div>
                <div class="text-muted small">Quota global : {{ number_format($stats['total_allocated_limit']) }} cartes max</div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <ul class="nav nav-tabs border-bottom mb-4" id="loyaltyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="cards-tab" data-bs-toggle="tab" data-bs-target="#cards-content" type="button" role="tab">
                <i class="bi bi-credit-card me-1.5"></i>Toutes les Cartes ({{ $cards->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="quotas-tab" data-bs-toggle="tab" data-bs-target="#quotas-content" type="button" role="tab">
                <i class="bi bi-pie-chart me-1.5"></i>Quotas par Entreprise ({{ $clients->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs-content" type="button" role="tab">
                <i class="bi bi-clock-history me-1.5"></i>Dernières Transactions
            </button>
        </li>
    </ul>

    <div class="tab-content" id="loyaltyTabsContent">
        <!-- TAB 1: CARDS LIST -->
        <div class="tab-pane fade show active" id="cards-content" role="tabpanel">
            <!-- Search & Filters -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.loyalty.index') }}" class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Code carte, client, titulaire, NFC..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <select name="client_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Toutes les entreprises</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-md-2">
                            <select name="tier" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous les paliers</option>
                                <option value="bronze" {{ request('tier') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                                <option value="silver" {{ request('tier') == 'silver' ? 'selected' : '' }}>Argent</option>
                                <option value="gold" {{ request('tier') == 'gold' ? 'selected' : '' }}>Or</option>
                                <option value="platinum" {{ request('tier') == 'platinum' ? 'selected' : '' }}>Platine</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary flex-grow-1" style="background: #5736e9; border-color: #5736e9;">
                                Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'client_id', 'tier', 'status']))
                                <a href="{{ route('admin.loyalty.index') }}" class="btn btn-sm btn-outline-secondary" title="Réinitialiser">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table of Cards -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Code Carte</th>
                                <th class="py-3">Titulaire</th>
                                <th class="py-3">Entreprise Client</th>
                                <th class="py-3">NFC UID</th>
                                <th class="py-3 text-center">Points</th>
                                <th class="py-3 text-center">Palier</th>
                                <th class="py-3 text-center">Statut</th>
                                <th class="py-3 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cards as $card)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-2 p-1.5 text-white text-center" style="background: #5736e9; width: 32px; height: 32px;">
                                                <i class="bi bi-qr-code fs-6"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.loyalty.show', $card) }}" class="fw-bold text-dark text-decoration-none font-mono">
                                                    {{ $card->card_code }}
                                                </a>
                                                <small class="text-muted d-block" style="font-size: 0.72rem;">Créée {{ $card->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $card->customer_name }}</div>
                                        <small class="text-muted">{{ $card->customer_phone ?: $card->customer_email ?: 'Non renseigné' }}</small>
                                    </td>
                                    <td>
                                        @if($card->client)
                                            <a href="{{ route('admin.clients.show', $card->client) }}" class="text-decoration-none fw-semibold text-dark">
                                                {{ $card->client->company_name }}
                                            </a>
                                            <small class="text-muted d-block font-mono" style="font-size: 0.72rem;">{{ $card->client->access_code }}</small>
                                        @else
                                            <span class="text-muted small">Aucun client</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($card->nfc_uid)
                                            <span class="badge bg-primary-subtle text-primary font-mono fw-normal px-2 py-1">
                                                <i class="bi bi-broadcast me-1"></i>{{ $card->nfc_uid }}
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-warning-subtle text-dark fw-bold px-3 py-1.5 fs-6">
                                            {{ number_format($card->points) }} pts
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $tierColors = [
                                                'bronze' => 'bg-secondary text-white',
                                                'silver' => 'bg-info-subtle text-info',
                                                'gold' => 'bg-warning text-dark',
                                                'platinum' => 'bg-dark text-white',
                                            ];
                                        @endphp
                                        <span class="badge {{ $tierColors[$card->tier] ?? 'bg-secondary text-white' }} text-uppercase fw-semibold px-2 py-1">
                                            {{ $card->tier }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('admin.loyalty.toggle-status', $card) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm border-0 p-0" title="Cliquer pour changer de statut">
                                                @if($card->status === 'active')
                                                    <span class="badge bg-success-subtle text-success px-2 py-1 fw-semibold">
                                                        <i class="bi bi-check-circle me-1"></i>Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1 fw-semibold">
                                                        <i class="bi bi-dash-circle me-1"></i>Inactif
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('admin.loyalty.show', $card) }}" class="btn btn-sm btn-outline-primary" title="Détails & Transactions">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('loyalty.public.show', $card->card_code) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ouvrir le Pass Mobile">
                                                <i class="bi bi-phone"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-credit-card-2-front fs-1 d-block mb-2 text-secondary"></i>
                                        Aucune carte de fidélité trouvée pour ces critères.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($cards->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $cards->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB 2: CLIENT QUOTAS -->
        <div class="tab-pane fade" id="quotas-content" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Quotas &amp; Permissions Fidélité par Entreprise</h5>
                    <small class="text-muted">Définissez le nombre maximum de cartes de fidélité que chaque client est autorisé à émettre.</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Entreprise</th>
                                <th class="py-3">Code Accès</th>
                                <th class="py-3">Statut Fidélité</th>
                                <th class="py-3">Utilisation Quota</th>
                                <th class="py-3 text-center">Cartes Émises / Max</th>
                                <th class="py-3 text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $c)
                                @php
                                    $used = $c->loyalty_cards_count;
                                    $limit = $c->loyalty_card_limit ?? 20;
                                    $pct = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
                                @endphp
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold text-dark">{{ $c->company_name }}</div>
                                        <small class="text-muted">{{ $c->contact_name }} &bull; {{ $c->email }}</small>
                                    </td>
                                    <td>
                                        <span class="font-mono fw-bold text-dark">{{ $c->access_code }}</span>
                                    </td>
                                    <td>
                                        @if($c->loyalty_enabled ?? true)
                                            <span class="badge bg-success-subtle text-success">Activé</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Désactivé</span>
                                        @endif
                                    </td>
                                    <td style="min-width: 180px;">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-primary') }}"
                                                 role="progressbar"
                                                 style="width: {{ $pct }}%; background-color: {{ $pct < 70 ? '#5736e9' : '' }} !important;"
                                                 aria-valuenow="{{ $pct }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted mt-1 d-block">{{ $pct }}% consommé</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold {{ $used >= $limit ? 'text-danger' : 'text-dark' }}">{{ $used }}</span>
                                        <span class="text-muted">/ {{ $limit }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editQuotaModal{{ $c->id }}" title="Modifier le quota">
                                                <i class="bi bi-pencil me-1"></i>Quota
                                            </button>

                                            <form method="POST" action="{{ route('admin.loyalty.remove-client', $c) }}" class="d-inline" onsubmit="return confirm('Retirer {{ addslashes($c->company_name) }} du programme de fidélité ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer du programme fidélité">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Edit Quota Modal -->
                                        <div class="modal fade" id="editQuotaModal{{ $c->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $c->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form method="POST" action="{{ route('admin.loyalty.update-client-limit', $c) }}" class="modal-content text-start">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{ $c->id }}">Quota Fidélité — {{ $c->company_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nombre maximum de cartes autorisées</label>
                                                            <input type="number" name="loyalty_card_limit" class="form-control" value="{{ $limit }}" min="{{ $used }}" max="100000" required>
                                                            <small class="text-muted">Cartes actuellement créées par le client : {{ $used }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Accès au système de fidélité</label>
                                                            <select name="loyalty_enabled" class="form-select">
                                                                <option value="1" {{ ($c->loyalty_enabled ?? true) ? 'selected' : '' }}>Autorisé / Activé</option>
                                                                <option value="0" {{ !($c->loyalty_enabled ?? true) ? 'selected' : '' }}>Bloqué / Désactivé</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-sm btn-primary" style="background: #5736e9; border-color: #5736e9;">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-buildings fs-1 d-block mb-2 text-secondary"></i>
                                        Aucune entreprise n'est actuellement inscrite au programme de fidélité.
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-sm btn-primary" style="background: #5736e9; border-color: #5736e9;" data-bs-toggle="modal" data-bs-target="#enrollClientModal">
                                                <i class="bi bi-plus-lg me-1"></i>Ajouter une Entreprise au Programme
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: AUDIT TRANSACTIONS -->
        <div class="tab-pane fade" id="logs-content" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Dernières Opérations Enregistrées</h5>
                    <small class="text-muted">Historique des ajouts de points, calculs par montant et échanges de récompenses.</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Horodatage</th>
                                <th class="py-3">Carte</th>
                                <th class="py-3">Entreprise</th>
                                <th class="py-3">Type</th>
                                <th class="py-3 text-center">Variation</th>
                                <th class="py-3 text-center">Solde Final</th>
                                <th class="py-3">Motif / Dépense</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                                <tr>
                                    <td class="ps-3 text-muted small">
                                        {{ $tx->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.loyalty.show', $tx->loyaltyCard) }}" class="fw-bold text-dark font-mono text-decoration-none">
                                            {{ $tx->loyaltyCard->card_code }}
                                        </a>
                                        <small class="text-muted d-block">{{ $tx->loyaltyCard->customer_name }}</small>
                                    </td>
                                    <td>
                                        {{ $tx->loyaltyCard->client?->company_name ?? '—' }}
                                    </td>
                                    <td>
                                        @if($tx->type === 'add')
                                            <span class="badge bg-success-subtle text-success">Ajout</span>
                                        @elseif($tx->type === 'redeem')
                                            <span class="badge bg-warning-subtle text-warning">Échange</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Ajustement</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold {{ $tx->points_delta >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $tx->points_delta >= 0 ? '+' : '' }}{{ $tx->points_delta }} pts
                                    </td>
                                    <td class="text-center font-mono">
                                        {{ number_format($tx->balance_after) }} pts
                                    </td>
                                    <td class="small text-muted">
                                        {{ $tx->description ?: ($tx->spend_amount ? 'Dépense : ' . number_format($tx->spend_amount, 2) . ' MAD' : 'Opération caisse') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Aucune transaction enregistrée pour l'instant.
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

<!-- Modal: Inscrire une Entreprise au Programme Fidélité -->
<div class="modal fade" id="enrollClientModal" tabindex="-1" aria-labelledby="enrollClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.loyalty.enroll-client') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="enrollClientModalLabel">
                    <i class="bi bi-award text-primary me-2"></i>Inscrire une Entreprise au Programme
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($availableClients->isEmpty())
                    <div class="alert alert-info border-0 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Toutes vos entreprises clientes sont déjà inscrites au programme de fidélité. Vous pouvez créer un nouveau client depuis la section <strong>Clients</strong>.
                    </div>
                @else
                    <div class="mb-3">
                        <label for="enroll_client_id" class="form-label fw-semibold">Choisir l'entreprise</label>
                        <select name="client_id" id="enroll_client_id" class="form-select" required>
                            <option value="">Sélectionnez un client...</option>
                            @foreach($availableClients as $ac)
                                <option value="{{ $ac->id }}">
                                    {{ $ac->company_name }} ({{ $ac->contact_name }} - {{ $ac->access_code }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Seules les entreprises clientes non encore inscrites sont affichées.</small>
                    </div>

                    <div class="mb-3">
                        <label for="enroll_card_limit" class="form-label fw-semibold">Quota de cartes autorisées</label>
                        <input type="number" name="loyalty_card_limit" id="enroll_card_limit" class="form-control" value="50" min="1" max="100000" required>
                        <small class="text-muted">Nombre maximum de cartes membres que ce client pourra créer.</small>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                @if(!$availableClients->isEmpty())
                    <button type="submit" class="btn btn-sm btn-primary" style="background: #5736e9; border-color: #5736e9;">
                        <i class="bi bi-check-lg me-1"></i>Ajouter au Programme
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

