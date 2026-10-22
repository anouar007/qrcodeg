@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page_title', 'Utilisateurs & Équipe Interne')
@section('page_subtitle', 'Gestion des comptes collaborateurs, attribution des rôles et sélection des permissions granulaires (Non clients)')

@section('content')
<div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm rounded-3">
    <!-- Header with Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Collaborateurs & Administrateurs (Non Clients)</h5>
            <small class="text-muted">Total: {{ $users->total() }} comptes actifs ou assignés</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i>
            <span>Créer un Nouvel Utilisateur</span>
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom, username, email..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">Tous les rôles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tous statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Filtrer</button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
            @endif
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th class="ps-3 py-3">Utilisateur</th>
                    <th>Nom d'Utilisateur</th>
                    <th>Rôles & Permissions</th>
                    <th class="text-center">Statut</th>
                    <th>Dernière Connexion</th>
                    <th>Date Création</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-3 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.05rem;">
                                    {{ substr($user->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="text-dark fw-bold">{{ $user->username }}</code>
                            @if($user->phone)
                                <small class="text-muted d-block font-monospace">{{ $user->phone }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                @forelse($user->roles as $role)
                                    <span class="badge {{ $role->slug === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                        <i class="bi bi-shield-check me-1"></i>{{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-muted small">Aucun rôle</span>
                                @endforelse
                            </div>
                            @if($user->directPermissions->count() > 0)
                                <span class="badge bg-warning-subtle text-dark border" style="font-size: 0.7rem;" title="{{ $user->directPermissions->pluck('name')->join(', ') }}">
                                    <i class="bi bi-key-fill text-warning me-1"></i>+{{ $user->directPermissions->count() }} permission(s) directe(s)
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Actif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Inactif</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Jamais' }}
                        </td>
                        <td class="text-muted small">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Gérer
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}"><i class="bi bi-pencil me-2 text-primary"></i> Modifier / Permissions</a></li>
                                    <li>
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item {{ $user->status === 'active' ? 'text-warning' : 'text-success' }}">
                                                <i class="bi {{ $user->status === 'active' ? 'bi-pause-circle text-warning' : 'bi-play-circle text-success' }} me-2"></i>
                                                {{ $user->status === 'active' ? 'Désactiver le compte' : 'Activer le compte' }}
                                            </button>
                                        </form>
                                    </li>
                                    @if(auth()->id() !== $user->id)
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Attention : supprimer cet utilisateur est irréversible !');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h5 class="fw-bold">Aucun utilisateur trouvé</h5>
                            <p class="small text-muted mb-3">Créez un compte pour un nouveau collaborateur ou administrateur.</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill me-1"></i> Créer un Utilisateur
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-4 pt-3 border-top">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
