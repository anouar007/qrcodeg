@extends('layouts.admin')

@section('title', 'Gestion des Clients')
@section('page_title', 'Gestion des Clients')
@section('page_subtitle', 'Consultez la liste des clients, leurs projets, coordonnées, codes d\'accès et inventaire connecté')

@section('content')
<div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm rounded-3">
    <!-- Header with Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Clients & Entreprises Partenaires</h5>
            <small class="text-muted">Total: {{ $clients->total() }} clients enregistrés</small>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i>
            <span>Créer un Nouveau Client</span>
        </a>
    </div>

    <!-- Search & Status filter -->
    <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom, projet, ville, téléphone, email ou code..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Filtrer</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
            @endif
        </div>
    </form>

    <!-- Clients Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th class="ps-3 py-3">Client & Projet</th>
                    <th>Ville & Contact</th>
                    <th>Code d'Accès</th>
                    <th class="text-center">Inventaire</th>
                    <th class="text-center">Statut</th>
                    <th>Dernière Connexion</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="ps-3 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($client->logo)
                                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded border p-1 bg-white" style="width: 44px; height: 44px; object-fit: contain;">
                                @else
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                        {{ substr($client->contact_name ?: $client->company_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.clients.show', $client) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $client->contact_name }}
                                    </a>
                                    @if($client->project_name)
                                        <div class="small text-primary fw-semibold">
                                            <i class="bi bi-folder2-open me-1"></i>{{ $client->project_name }}
                                        </div>
                                    @endif
                                    <div class="small text-muted">{{ $client->company_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @if($client->city)
                                    <span class="small fw-semibold text-dark"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $client->city }}</span>
                                @endif
                                @if($client->phone)
                                    <span class="small text-muted font-monospace"><i class="bi bi-telephone text-success me-1"></i>{{ $client->phone }}</span>
                                @endif
                                <span class="small text-muted">{{ $client->email }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-dark-subtle text-dark border font-monospace px-2 py-1" style="font-size: 0.8rem;">
                                {{ $client->access_code ?? 'Non défini' }}
                            </span>
                            @if($client->is_password_set)
                                <small class="text-success d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-check"></i> Mot de passe configuré</small>
                            @else
                                <small class="text-warning d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-hourglass-split"></i> En attente 1er login</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <span class="badge bg-primary-subtle text-primary border" title="QR Codes assignés">
                                    <i class="bi bi-qr-code me-1"></i>{{ $client->qr_codes_count }} QR(s)
                                </span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-purple-subtle text-purple border" style="background-color: #ede9fe; color: #6d28d9; font-size: 0.7rem;" title="Cartes Visite">
                                        <i class="bi bi-person-vcard me-1"></i>{{ $client->digital_cards_count }}
                                    </span>
                                    <span class="badge bg-info-subtle text-info border" style="font-size: 0.7rem;" title="Tags NFC">
                                        <i class="bi bi-broadcast me-1"></i>{{ $client->nfc_tags_count }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($client->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Actif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Inactif</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Jamais' }}
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Gérer
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><a class="dropdown-item" href="{{ route('admin.clients.show', $client) }}"><i class="bi bi-clock-history me-2 text-primary"></i> Historique & Détails</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.clients.edit', $client) }}"><i class="bi bi-pencil me-2 text-info"></i> Modifier le Client</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.clients.reset-access', $client) }}" method="POST" onsubmit="return confirm('Régénérer un nouveau code d\'accès ? Le client devra réinitialiser son mot de passe.');">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-key me-2 text-warning"></i> Réinitialiser Code d'Accès
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item {{ $client->status === 'active' ? 'text-danger' : 'text-success' }}">
                                                <i class="bi {{ $client->status === 'active' ? 'bi-pause-circle text-danger' : 'bi-play-circle text-success' }} me-2"></i>
                                                {{ $client->status === 'active' ? 'Désactiver le Compte' : 'Activer le Compte' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Attention : Êtes-vous sûr de vouloir supprimer définitivement ce client ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Supprimer le Client
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h5 class="fw-bold">Aucun client trouvé</h5>
                            <p class="small text-muted mb-3">Enregistrez un premier client avec son nom, projet, ville et téléphone.</p>
                            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill me-1"></i> Créer un Nouveau Client
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <div class="mt-4 pt-3 border-top">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection
