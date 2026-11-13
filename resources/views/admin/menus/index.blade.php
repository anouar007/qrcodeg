@extends('layouts.app')

@section('title', 'Gestion des Menus Restaurants & Marques')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Menus Digitaux</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Header Title & Action -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-journal-richtext text-primary me-2"></i>Menus Digitaux
            </h1>
            <p class="text-muted small mb-0">Créez et gérez les cartes &amp; menus interactifs pour restaurants, fast-foods, cafés et marques.</p>
        </div>

        <div>
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-xs" style="background: #5736e9; border-color: #5736e9;">
                <i class="bi bi-plus-lg"></i>
                <span>Créer un Nouveau Menu</span>
            </a>
        </div>
    </div>

    <!-- Stat Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Menus Créés</span>
                    <div class="rounded-3 p-2 text-white" style="background: #5736e9;">
                        <i class="bi bi-journal-text fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['total_menus']) }}</div>
                <div class="text-muted small">
                    <span class="text-success fw-semibold">{{ number_format($stats['active_menus']) }}</span> actifs en ligne
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Scans &amp; Vues</span>
                    <div class="rounded-3 p-2 text-white bg-warning">
                        <i class="bi bi-eye fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['total_views']) }}</div>
                <div class="text-muted small">Consultations sur mobile</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Clients avec Menu</span>
                    <div class="rounded-3 p-2 text-white bg-success">
                        <i class="bi bi-shop fs-5"></i>
                    </div>
                </div>
                <div class="h3 fw-bold text-dark mb-1">{{ number_format($stats['clients_with_menus']) }}</div>
                <div class="text-muted small">Établissements enregistrés</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Format de Menu</span>
                    <div class="rounded-3 p-2 text-white bg-info">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-dark mb-1">QR Table &amp; Web</div>
                <div class="text-muted small">Accès instantané sans appli</div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs border-bottom mb-4" id="menusTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="all-menus-tab" data-bs-toggle="tab" data-bs-target="#all-menus-content" type="button" role="tab">
                <i class="bi bi-grid me-1.5"></i>Tous les Menus ({{ $menus->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="clients-menus-tab" data-bs-toggle="tab" data-bs-target="#clients-menus-content" type="button" role="tab">
                <i class="bi bi-people me-1.5"></i>Clients avec Menu ({{ $clientsWithMenus->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="menusTabsContent">
        <!-- TAB 1: ALL MENUS -->
        <div class="tab-pane fade show active" id="all-menus-content" role="tabpanel">
            <!-- Search & Filters -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.menus.index') }}" class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Titre du menu, restaurant, client..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <select name="client_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Toutes les entreprises</option>
                                @foreach($allClients as $c)
                                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-md-2">
                            <select name="brand_type" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous les types</option>
                                <option value="restaurant" {{ request('brand_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                <option value="fast_food" {{ request('brand_type') == 'fast_food' ? 'selected' : '' }}>Fast Food</option>
                                <option value="cafe" {{ request('brand_type') == 'cafe' ? 'selected' : '' }}>Café / Salon</option>
                                <option value="bakery" {{ request('brand_type') == 'bakery' ? 'selected' : '' }}>Boulangerie</option>
                                <option value="lounge" {{ request('brand_type') == 'lounge' ? 'selected' : '' }}>Lounge / Bar</option>
                                <option value="brand" {{ request('brand_type') == 'brand' ? 'selected' : '' }}>Marque</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-1 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100" style="background: #5736e9; border-color: #5736e9;">
                                Filtrer
                            </button>
                            @if(request()->anyFilled(['search', 'client_id', 'brand_type', 'status']))
                                <a href="{{ route('admin.menus.index') }}" class="btn btn-sm btn-outline-secondary" title="Réinitialiser">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table of Menus -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Menu &amp; Établissement</th>
                                <th class="py-3">Type</th>
                                <th class="py-3">Client / Propriétaire</th>
                                <th class="py-3 text-center">Catégories &amp; Articles</th>
                                <th class="py-3 text-center">Vues</th>
                                <th class="py-3 text-center">Statut</th>
                                <th class="py-3 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-3 p-1 text-white text-center d-flex align-items-center justify-content-center"
                                                 style="background: {{ $menu->theme_color ?: '#5736e9' }}; width: 40px; height: 40px; flex-shrink: 0;">
                                                @if($menu->logo_url)
                                                    <img src="{{ $menu->logo_url }}" alt="Logo" class="rounded-2" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="bi bi-journal-richtext fs-5"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.menus.show', $menu) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                    {{ $menu->title }}
                                                </a>
                                                <span class="text-muted small fw-medium">{{ $menu->brand_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $typeBadges = [
                                                'restaurant' => 'bg-primary-subtle text-primary',
                                                'fast_food' => 'bg-danger-subtle text-danger',
                                                'cafe' => 'bg-warning-subtle text-warning',
                                                'bakery' => 'bg-info-subtle text-info',
                                                'lounge' => 'bg-dark-subtle text-dark',
                                                'brand' => 'bg-secondary-subtle text-secondary',
                                            ];
                                        @endphp
                                        <span class="badge {{ $typeBadges[$menu->brand_type] ?? 'bg-light text-secondary' }} text-uppercase fw-semibold px-2 py-1">
                                            {{ str_replace('_', ' ', $menu->brand_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($menu->client)
                                            <a href="{{ route('admin.clients.show', $menu->client) }}" class="text-decoration-none fw-semibold text-dark">
                                                {{ $menu->client->company_name }}
                                            </a>
                                            <small class="text-muted d-block font-mono" style="font-size: 0.72rem;">{{ $menu->client->access_code }}</small>
                                        @else
                                            <span class="text-muted small">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            <i class="bi bi-folder me-1 text-primary"></i>{{ $menu->total_categories_count }} cat.
                                        </span>
                                        <span class="badge bg-light text-dark border px-2 py-1 ms-1">
                                            <i class="bi bi-tag me-1 text-success"></i>{{ $menu->total_items_count }} plats
                                        </span>
                                    </td>
                                    <td class="text-center font-mono fw-semibold text-secondary">
                                        {{ number_format($menu->views_count) }}
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('admin.menus.toggle-status', $menu) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm border-0 p-0" title="Changer le statut">
                                                @if($menu->status === 'active')
                                                    <span class="badge bg-success-subtle text-success px-2 py-1 fw-semibold">
                                                        <i class="bi bi-check-circle me-1"></i>En Ligne
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1 fw-semibold">
                                                        <i class="bi bi-pause-circle me-1"></i>Brouillon
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-success" title="Encoder ce menu sur une puce NFC" onclick="window.nfcEncoder.writeUrl('{{ route('menu.public.show', $menu->slug) }}', 'Menu Digital - {{ addslashes($menu->brand_name) }}')">
                                                <i class="bi bi-broadcast"></i>
                                            </button>
                                            <a href="{{ route('menu.public.show', $menu->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ouvrir le menu en direct">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-sm btn-outline-primary" title="Détails &amp; QR Table">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-outline-secondary" title="Modifier le menu">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="d-inline" onsubmit="return confirm('Supprimer ce menu ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                        Aucun menu trouvé.
                                        <div class="mt-3">
                                            <a href="{{ route('admin.menus.create') }}" class="btn btn-sm btn-primary" style="background: #5736e9; border-color: #5736e9;">
                                                <i class="bi bi-plus-lg me-1"></i>Créer le premier menu
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($menus->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $menus->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB 2: CLIENTS WITH MENUS -->
        <div class="tab-pane fade" id="clients-menus-content" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0">Entreprises &amp; Établissements ayant des Menus</h5>
                        <small class="text-muted">Clients enregistrés disposant d'au moins un menu digital actif.</small>
                    </div>
                    <span class="badge rounded-pill text-white" style="background: #5736e9;">
                        {{ $clientsWithMenus->count() }} clients
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Entreprise</th>
                                <th class="py-3">Contact &amp; Email</th>
                                <th class="py-3">Code Accès</th>
                                <th class="py-3 text-center">Menus Déployés</th>
                                <th class="py-3 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientsWithMenus as $cm)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">{{ $cm->company_name }}</div>
                                        <small class="text-muted">{{ $cm->city ?: 'Maroc' }}</small>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $cm->contact_name }}</div>
                                        <small class="text-muted">{{ $cm->email }}</small>
                                    </td>
                                    <td>
                                        <span class="font-mono fw-bold text-dark">{{ $cm->access_code }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-1 text-white fs-6" style="background: #5736e9;">
                                            {{ $cm->restaurant_menus_count }} menu(s)
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('admin.menus.index', ['client_id' => $cm->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-filter me-1"></i>Voir les menus
                                            </a>
                                            <a href="{{ route('admin.menus.create', ['client_id' => $cm->id]) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-plus-lg me-1"></i>Nouveau Menu
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-shop fs-1 d-block mb-2 text-secondary"></i>
                                        Aucun client n'a de menu digital pour l'instant.
                                        <div class="mt-3">
                                            <a href="{{ route('admin.menus.create') }}" class="btn btn-sm btn-primary" style="background: #5736e9; border-color: #5736e9;">
                                                <i class="bi bi-plus-lg me-1"></i>Créer un Menu pour un Client
                                            </a>
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
</div>
@endsection

