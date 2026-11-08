@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page_title', 'Gestion des Utilisateurs & Contrôle d\'Accès')
@section('page_subtitle', 'Comptes internes collaborateurs, rôles RBAC, audit de sécurité et privilèges granulaires')

@push('styles')
<style>
    .user-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.2rem 1.35rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }
    .user-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -6px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .user-kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .status-tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.42rem 0.9rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }
    .status-tab-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .status-tab-pill.active {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }
    .status-tab-pill .pill-count {
        font-size: 0.7rem;
        padding: 0.1rem 0.45rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.08);
        color: inherit;
    }
    .status-tab-pill.active .pill-count {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }
    .user-avatar-box {
        position: relative;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
        letter-spacing: -0.02em;
    }
    .user-avatar-box.is-admin {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }
    .user-avatar-box.is-staff {
        background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }
    .user-status-dot {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #ffffff;
    }
    .user-status-dot.dot-active { background: #10b981; }
    .user-status-dot.dot-suspended { background: #f59e0b; }
    .user-status-dot.dot-inactive { background: #94a3b8; }

    .bulk-action-bar {
        display: none;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        animation: fadeInSlide 0.2s ease;
    }
    .bulk-action-bar.is-visible {
        display: flex;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .user-table-row {
        transition: background-color 0.15s ease;
    }
    .user-table-row:hover {
        background-color: #f8fafc !important;
    }
    .perm-inspect-pill {
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .perm-inspect-pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.08);
    }
    .action-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .action-icon-btn:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('content')
@php
    $kpiStats = $stats ?? [
        'total' => $users->total(),
        'active' => $users->where('status', 'active')->count(),
        'suspended' => $users->where('status', 'suspended')->count(),
        'inactive' => $users->where('status', 'inactive')->count(),
        'admins' => 0,
        'total_permissions' => 22,
    ];
    $activePercent = $kpiStats['total'] > 0 ? round(($kpiStats['active'] / $kpiStats['total']) * 100) : 0;
@endphp

<!-- 1. Executive KPI Strip -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="user-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Collaborateurs</span>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $kpiStats['total'] }}</h3>
                </div>
                <div class="user-kpi-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                <span>Taux d'activation</span>
                <span class="fw-bold text-primary">{{ $activePercent }}%</span>
            </div>
            <div class="progress mt-1" style="height: 5px; border-radius: 99px;">
                <div class="progress-bar bg-primary" style="width: {{ $activePercent }}%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="user-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Comptes Actifs</span>
                    <h3 class="fw-bold text-success mb-0 mt-1">{{ $kpiStats['active'] }}</h3>
                </div>
                <div class="user-kpi-icon bg-success-subtle text-success">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">
                <i class="bi bi-shield-check text-success me-1"></i>Accès plateforme autorisé
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="user-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Administrateurs & Rôles</span>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $kpiStats['admins'] }} <span class="fs-6 fw-normal text-muted">/ {{ $roles->count() }} rôles</span></h3>
                </div>
                <div class="user-kpi-icon" style="background: #ede9fe; color: #6d28d9;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">
                <i class="bi bi-key text-primary me-1"></i>{{ $kpiStats['total_permissions'] }} permissions système actives
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="user-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Suspendus / Inactifs</span>
                    <h3 class="fw-bold {{ ($kpiStats['suspended'] + $kpiStats['inactive']) > 0 ? 'text-warning' : 'text-dark' }} mb-0 mt-1">
                        {{ $kpiStats['suspended'] + $kpiStats['inactive'] }}
                    </h3>
                </div>
                <div class="user-kpi-icon bg-warning-subtle text-warning">
                    <i class="bi bi-person-fill-lock"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">
                <span>{{ $kpiStats['suspended'] }} suspendu(s) &bull; {{ $kpiStats['inactive'] }} inactif(s)</span>
            </div>
        </div>
    </div>
</div>

<!-- 2. Main Directory Card -->
<div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm rounded-4">
    <!-- Top Header & Primary Actions -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-dark mb-0">Répertoire des Collaborateurs Internes</h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                    {{ $users->total() }} compte(s)
                </span>
            </div>
            <small class="text-muted d-block mt-1">Gérez les identifiants d'équipe, les rôles RBAC et les permissions granulaires par module.</small>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1.5 rounded-3 px-3 py-2">
                <i class="bi bi-download"></i>
                <span>Export CSV</span>
            </a>

            @if(auth('web')->user()?->isAdmin() || auth('web')->user()?->hasPermission('manage_roles'))
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5 rounded-3 px-3 py-2">
                <i class="bi bi-shield-shaded"></i>
                <span>Rôles & RBAC</span>
            </a>
            @endif

            @can('create', App\Models\User::class)
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 rounded-3 px-3.5 py-2 shadow-sm fw-semibold">
                <i class="bi bi-person-plus-fill"></i>
                <span>Nouvel Utilisateur</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- Quick Status Tabs -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex flex-wrap align-items-center gap-1.5">
            <a href="{{ route('admin.users.index', array_filter(array_merge(request()->except('page'), ['status' => null]))) }}"
               class="status-tab-pill {{ !request('status') ? 'active' : '' }}">
                <span>Tous</span>
                <span class="pill-count">{{ $kpiStats['total'] }}</span>
            </a>
            <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['status' => 'active'])) }}"
               class="status-tab-pill {{ request('status') === 'active' ? 'active' : '' }}">
                <i class="bi bi-check-circle-fill {{ request('status') === 'active' ? 'text-white' : 'text-success' }}"></i>
                <span>Actifs</span>
                <span class="pill-count">{{ $kpiStats['active'] }}</span>
            </a>
            <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['status' => 'suspended'])) }}"
               class="status-tab-pill {{ request('status') === 'suspended' ? 'active' : '' }}">
                <i class="bi bi-pause-circle-fill {{ request('status') === 'suspended' ? 'text-white' : 'text-warning' }}"></i>
                <span>Suspendus</span>
                <span class="pill-count">{{ $kpiStats['suspended'] }}</span>
            </a>
            <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['status' => 'inactive'])) }}"
               class="status-tab-pill {{ request('status') === 'inactive' ? 'active' : '' }}">
                <i class="bi bi-slash-circle {{ request('status') === 'inactive' ? 'text-white' : 'text-secondary' }}"></i>
                <span>Inactifs</span>
                <span class="pill-count">{{ $kpiStats['inactive'] }}</span>
            </a>
        </div>

        @if(request()->hasAny(['search', 'role', 'status', 'sort']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0">
                <i class="bi bi-x-circle me-1"></i>Effacer tous les filtres
            </a>
        @endif
    </div>

    <!-- Search & Multi-Criterion Filter Form -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-lg-4 col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom, @username, email, tél..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-lg-2 col-md-3 col-6">
            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Tous les rôles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 col-md-3 col-6">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Tous statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>

        <div class="col-lg-2 col-md-6 col-6">
            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Trier: Plus récents</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Trier: Ordre A-Z</option>
                <option value="last_login" {{ request('sort') === 'last_login' ? 'selected' : '' }}>Trier: Dernière connexion</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Trier: Plus anciens</option>
            </select>
        </div>

        <div class="col-lg-2 col-md-6 col-6 d-flex gap-1.5">
            <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold">
                <i class="bi bi-funnel me-1"></i>Filtrer
            </button>
            @if(request()->hasAny(['search', 'role', 'status', 'sort']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm" title="Réinitialiser">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- Bulk Action Bar (Shown when rows are checked) -->
    <form id="bulkUsersForm" method="POST" action="{{ route('admin.users.bulk-action') }}">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput" value="">
        <input type="hidden" name="role_id" id="bulkRoleIdInput" value="">
        <div id="bulkSelectedIdsContainer"></div>

        <div id="bulkActionBar" class="bulk-action-bar align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill px-2.5 py-1" id="bulkSelectedCountBadge">0</span>
                <span class="small fw-semibold">collaborateur(s) sélectionné(s)</span>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="submitBulkUserAction('activate')">
                    <i class="bi bi-check-circle me-1"></i>Activer
                </button>
                <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-3" onclick="submitBulkUserAction('suspend')">
                    <i class="bi bi-pause-circle me-1"></i>Suspendre
                </button>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-plus me-1"></i>Assigner Rôle
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        @foreach($roles as $r)
                            <li>
                                <button type="button" class="dropdown-item small" onclick="submitBulkUserAction('assign_role', {{ $r->id }})">
                                    <i class="bi bi-shield-check me-2 text-primary"></i>{{ $r->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3" onclick="submitBulkUserAction('delete')">
                    <i class="bi bi-trash me-1"></i>Supprimer
                </button>
                <button type="button" class="btn btn-sm btn-link text-white-50 text-decoration-none" onclick="clearAllUserSelection()">
                    Annuler
                </button>
            </div>
        </div>
    </form>

    <!-- Users Directory Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small" style="font-size: 0.73rem; letter-spacing: 0.04em;">
                <tr>
                    <th class="ps-3 py-3" style="width: 40px;">
                        <input type="checkbox" class="form-check-input cursor-pointer" id="selectAllUsersCheckbox" onchange="toggleSelectAllUsers(this.checked)">
                    </th>
                    <th class="py-3">Collaborateur</th>
                    <th class="py-3">Identifiant & Contact</th>
                    <th class="py-3">Rôles & Privilèges Effectifs</th>
                    <th class="py-3 text-center">Activité</th>
                    <th class="py-3 text-center">Statut</th>
                    <th class="py-3">Dernière Connexion</th>
                    <th class="text-end pe-3 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $isCurrentUser = (auth('web')->id() === $user->id);
                        $isUserAdmin = $user->isAdmin();
                        $rolePerms = $user->roles->flatMap->permissions->pluck('name', 'slug');
                        $directPerms = $user->directPermissions->pluck('name', 'slug');
                        $effectivePerms = $rolePerms->merge($directPerms);
                        $dotClass = $user->status === 'active' ? 'dot-active' : ($user->status === 'suspended' ? 'dot-suspended' : 'dot-inactive');
                        $inspectPayload = [
                            'id' => $user->id,
                            'name' => $user->name ?: ($user->first_name . ' ' . $user->last_name),
                            'email' => $user->email,
                            'username' => $user->username,
                            'is_admin' => $isUserAdmin,
                            'edit_url' => route('admin.users.edit', $user),
                            'show_url' => route('admin.users.show', $user),
                            'roles' => $user->roles->map(fn($r) => ['name' => $r->name, 'slug' => $r->slug])->values()->all(),
                            'direct_permissions' => $user->directPermissions->map(fn($p) => ['name' => $p->name, 'slug' => $p->slug, 'module' => $p->module])->values()->all(),
                            'role_permissions_count' => $rolePerms->count(),
                            'effective_count' => $isUserAdmin ? ($kpiStats['total_permissions'] ?? 22) : $effectivePerms->count(),
                        ];
                    @endphp
                    <tr class="user-table-row">
                        <td class="ps-3">
                            <input type="checkbox" class="form-check-input user-row-checkbox cursor-pointer"
                                   value="{{ $user->id }}"
                                   onchange="updateBulkSelectionBar()">
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar-box {{ $isUserAdmin ? 'is-admin' : 'is-staff' }}">
                                    {{ strtoupper(substr($user->name ?: $user->first_name ?: 'U', 0, 2)) }}
                                    <span class="user-status-dot {{ $dotClass }}" title="Statut: {{ ucfirst($user->status) }}"></span>
                                </div>
                                <div class="min-w-0">
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.users.show', $user) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $user->name ?: ($user->first_name . ' ' . $user->last_name) }}
                                        </a>
                                        @if($isCurrentUser)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill" style="font-size: 0.65rem;">Vous</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5 text-muted small">
                                        <span class="text-truncate" style="max-width: 210px;">{{ $user->email }}</span>
                                        <button type="button" class="btn btn-link p-0 text-muted border-0" style="font-size: 0.72rem;"
                                                onclick="copyToClipboardText('{{ $user->email }}', this)" title="Copier l'email">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <code class="text-dark fw-bold bg-light px-2 py-0.5 rounded border" style="font-size: 0.78rem;">&#64;{{ $user->username }}</code>
                            </div>
                            @if($user->phone)
                                <small class="text-muted d-block font-monospace mt-1" style="font-size: 0.74rem;">
                                    <i class="bi bi-telephone me-1"></i>{{ $user->phone }}
                                </small>
                            @else
                                <small class="text-muted opacity-50 d-block mt-1" style="font-size: 0.72rem;">Aucun téléphone</small>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-wrap align-items-center gap-1 mb-1">
                                @forelse($user->roles as $role)
                                    <span class="badge {{ $role->slug === 'admin' ? 'bg-primary' : 'bg-dark' }} rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-shield-check me-1"></i>{{ $role->name }}
                                    </span>
                                @empty
                                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.7rem;">Aucun rôle</span>
                                @endforelse
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                <button type="button"
                                        class="badge bg-light text-dark border perm-inspect-pill px-2 py-1 text-start"
                                        style="font-size: 0.69rem;"
                                        onclick='openUserAccessInspectModal(@json($inspectPayload))'
                                        title="Cliquer pour inspecter tous les accès de ce collaborateur">
                                    <i class="bi bi-sliders text-primary me-1"></i>
                                    @if($isUserAdmin)
                                        Accès Total SuperAdmin
                                    @else
                                        {{ $effectivePerms->count() }} permission(s) effective(s)
                                    @endif
                                </button>

                                @if($user->directPermissions->count() > 0)
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle"
                                          style="font-size: 0.68rem;"
                                          title="{{ $user->directPermissions->pluck('name')->join(', ') }}">
                                        <i class="bi bi-key-fill text-warning me-1"></i>+{{ $user->directPermissions->count() }} directe(s)
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1 bg-light border rounded-pill px-2.5 py-1 small" style="font-size: 0.72rem;">
                                <span title="Cartes Digitales créées"><i class="bi bi-person-badge text-primary me-0.5"></i>{{ $user->digital_cards_count ?? 0 }}</span>
                                <span class="text-muted">&bull;</span>
                                <span title="QR Codes créés"><i class="bi bi-qr-code text-dark me-0.5"></i>{{ $user->qr_codes_count ?? 0 }}</span>
                                <span class="text-muted">&bull;</span>
                                <span title="Actions journalisées"><i class="bi bi-activity text-success me-0.5"></i>{{ $user->activity_logs_count ?? 0 }}</span>
                            </div>
                        </td>

                        <td class="text-center">
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i>Actif
                                </span>
                            @elseif($user->status === 'suspended')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1">
                                    <i class="bi bi-pause-circle-fill me-1"></i>Suspendu
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2.5 py-1">
                                    <i class="bi bi-slash-circle me-1"></i>Inactif
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="small text-dark fw-medium">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Jamais connecté' }}
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">
                                Créé le {{ $user->created_at->format('d/m/Y') }}
                            </small>
                        </td>

                        <td class="text-end pe-3">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="action-icon-btn" title="Dossier & Audit de sécurité">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @can('update', $user)
                                <a href="{{ route('admin.users.edit', $user) }}" class="action-icon-btn text-primary" title="Modifier le profil & la matrice de permissions">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @endcan

                                <div class="dropdown">
                                    <button class="action-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Plus d'actions">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li>
                                            <a class="dropdown-item small py-2" href="{{ route('admin.users.show', $user) }}">
                                                <i class="bi bi-shield-check me-2 text-info"></i>Dossier & Audit d'Accès
                                            </a>
                                        </li>
                                        @can('update', $user)
                                        <li>
                                            <a class="dropdown-item small py-2" href="{{ route('admin.users.edit', $user) }}">
                                                <i class="bi bi-pencil me-2 text-primary"></i>Modifier / Permissions
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item small py-2"
                                                    onclick="openQuickPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')">
                                                <i class="bi bi-key me-2 text-dark"></i>Réinitialiser le mot de passe
                                            </button>
                                        </li>
                                        @if(!$isCurrentUser)
                                        <li>
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item small py-2 {{ $user->status === 'active' ? 'text-warning' : 'text-success' }}">
                                                    <i class="bi {{ $user->status === 'active' ? 'bi-pause-circle text-warning' : 'bi-play-circle text-success' }} me-2"></i>
                                                    {{ $user->status === 'active' ? 'Suspendre le compte' : 'Activer le compte' }}
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @endcan

                                        @can('delete', $user)
                                            @if(!$isCurrentUser)
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Attention : supprimer cet utilisateur est irréversible !');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item small py-2 text-danger">
                                                            <i class="bi bi-trash me-2"></i>Supprimer le compte
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h5 class="fw-bold">Aucun utilisateur trouvé</h5>
                            <p class="small text-muted mb-3">Aucun collaborateur ne correspond aux critères de recherche ou de filtre sélectionnés.</p>
                            @can('create', App\Models\User::class)
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3.5 py-2">
                                <i class="bi bi-person-plus-fill me-1"></i> Nouvel Utilisateur
                            </a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
            <div class="small text-muted">
                Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} collaborateurs
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal 1: Access & Permissions Inspector -->
<div class="modal fade" id="userAccessInspectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="inspectModalUserName">Inspecteur d'Accès Collaborateur</h6>
                    <small class="text-white-50 font-monospace" id="inspectModalUserMeta"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-2" style="font-size: 0.7rem;">Rôles RBAC Assignés</span>
                            <div id="inspectModalRolesList" class="d-flex flex-wrap gap-1.5"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Couverture de Privilèges</span>
                            <div class="fw-bold text-dark fs-5" id="inspectModalSummaryCount"></div>
                            <small class="text-muted" id="inspectModalSummarySub"></small>
                        </div>
                    </div>
                </div>

                <div>
                    <h6 class="fw-bold small text-uppercase text-muted mb-2" style="font-size: 0.72rem;">Permissions Directes Additionnelles</h6>
                    <div id="inspectModalDirectPerms" class="d-flex flex-wrap gap-1.5"></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between">
                <a href="#" id="inspectModalDossierLink" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                    <i class="bi bi-file-earmark-person me-1"></i>Voir le Dossier Complet
                </a>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Fermer</button>
                    <a href="#" id="inspectModalEditLink" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="bi bi-sliders me-1"></i>Modifier la Matrice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Quick Password Reset -->
<div class="modal fade" id="quickPasswordResetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form method="POST" id="quickPasswordResetForm" action="">
                @csrf
                <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                    <div>
                        <h6 class="modal-title fw-bold mb-0"><i class="bi bi-key-fill text-warning me-2"></i>Réinitialiser le Mot de Passe</h6>
                        <small class="text-white-50" id="resetModalUserSubtitle"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold mb-0">Nouveau Mot de Passe <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold" onclick="generateQuickModalPassword()">
                                <i class="bi bi-magic me-1"></i>Générer un MDP fort
                            </button>
                        </div>
                        <div class="input-group">
                            <input type="text" name="password" id="quickResetPasswordInput" class="form-control font-monospace" required minlength="8" placeholder="Minimum 8 caractères">
                            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboardText(document.getElementById('quickResetPasswordInput').value, this)" title="Copier">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">Confirmer le Mot de Passe <span class="text-danger">*</span></label>
                        <input type="text" name="password_confirmation" id="quickResetPasswordConfirm" class="form-control font-monospace" required minlength="8" placeholder="Confirmer le mot de passe">
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle me-1"></i>Cette action sera enregistrée dans le journal d'audit de sécurité.
                    </small>
                </div>
                <div class="modal-footer bg-light border-top py-2.5 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3.5 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i>Appliquer le Nouveau Mot de Passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboardText(text, btnEl) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        if (btnEl) {
            const orig = btnEl.innerHTML;
            btnEl.innerHTML = '<i class="bi bi-check2 text-success"></i>';
            setTimeout(() => { btnEl.innerHTML = orig; }, 1400);
        }
    });
}

function toggleSelectAllUsers(checked) {
    document.querySelectorAll('.user-row-checkbox').forEach(cb => {
        cb.checked = checked;
    });
    updateBulkSelectionBar();
}

function clearAllUserSelection() {
    const master = document.getElementById('selectAllUsersCheckbox');
    if (master) master.checked = false;
    toggleSelectAllUsers(false);
}

function updateBulkSelectionBar() {
    const checkedBoxes = Array.from(document.querySelectorAll('.user-row-checkbox:checked'));
    const bar = document.getElementById('bulkActionBar');
    const countBadge = document.getElementById('bulkSelectedCountBadge');
    if (!bar || !countBadge) return;

    countBadge.textContent = checkedBoxes.length;
    if (checkedBoxes.length > 0) {
        bar.classList.add('is-visible');
    } else {
        bar.classList.remove('is-visible');
    }
}

function submitBulkUserAction(action, roleId = null) {
    const checkedBoxes = Array.from(document.querySelectorAll('.user-row-checkbox:checked'));
    if (checkedBoxes.length === 0) return;

    if (action === 'delete' && !confirm(`Confirmer la suppression de ${checkedBoxes.length} compte(s) utilisateur ?`)) {
        return;
    }

    document.getElementById('bulkActionInput').value = action;
    document.getElementById('bulkRoleIdInput').value = roleId || '';

    const container = document.getElementById('bulkSelectedIdsContainer');
    container.innerHTML = '';
    checkedBoxes.forEach(cb => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'user_ids[]';
        inp.value = cb.value;
        container.appendChild(inp);
    });

    document.getElementById('bulkUsersForm').submit();
}

function openUserAccessInspectModal(payload) {
    document.getElementById('inspectModalUserName').textContent = payload.name || 'Collaborateur';
    document.getElementById('inspectModalUserMeta').textContent = `@${payload.username} • ${payload.email}`;
    document.getElementById('inspectModalEditLink').href = payload.edit_url || '#';
    document.getElementById('inspectModalDossierLink').href = payload.show_url || '#';

    const rolesWrap = document.getElementById('inspectModalRolesList');
    if (payload.roles && payload.roles.length > 0) {
        rolesWrap.innerHTML = payload.roles.map(r =>
            `<span class="badge ${r.slug === 'admin' ? 'bg-primary' : 'bg-dark'} rounded-pill px-2.5 py-1"><i class="bi bi-shield-check me-1"></i>${r.name}</span>`
        ).join(' ');
    } else {
        rolesWrap.innerHTML = '<span class="text-muted small">Aucun rôle assigné</span>';
    }

    const summaryCount = document.getElementById('inspectModalSummaryCount');
    const summarySub = document.getElementById('inspectModalSummarySub');
    if (payload.is_admin) {
        summaryCount.textContent = 'SuperAdmin (100%)';
        summarySub.textContent = 'Accès complet à tous les modules et paramètres système.';
    } else {
        summaryCount.textContent = `${payload.effective_count} permission(s) active(s)`;
        summarySub.textContent = `${payload.role_permissions_count} via rôle(s) + ${(payload.direct_permissions || []).length} directe(s)`;
    }

    const directWrap = document.getElementById('inspectModalDirectPerms');
    if (payload.direct_permissions && payload.direct_permissions.length > 0) {
        directWrap.innerHTML = payload.direct_permissions.map(p =>
            `<span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1"><i class="bi bi-key-fill text-warning me-1"></i>${p.name} <code class="ms-1 text-muted">${p.slug}</code></span>`
        ).join(' ');
    } else {
        directWrap.innerHTML = '<span class="text-muted small">Aucune permission directe additionnelle (droits hérités des rôles uniquement).</span>';
    }

    const modalEl = document.getElementById('userAccessInspectModal');
    if (modalEl && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
}

function openQuickPasswordModal(userId, userName, userEmail) {
    const form = document.getElementById('quickPasswordResetForm');
    form.action = `/admin/users/${userId}/reset-password`;
    document.getElementById('resetModalUserSubtitle').textContent = `${userName} (${userEmail})`;
    document.getElementById('quickResetPasswordInput').value = '';
    document.getElementById('quickResetPasswordConfirm').value = '';
    generateQuickModalPassword();

    const modalEl = document.getElementById('quickPasswordResetModal');
    if (modalEl && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
}

function generateQuickModalPassword() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*';
    let pass = '';
    for (let i = 0; i < 14; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    const p1 = document.getElementById('quickResetPasswordInput');
    const p2 = document.getElementById('quickResetPasswordConfirm');
    if (p1) p1.value = pass;
    if (p2) p2.value = pass;
}
</script>
@endpush
@endsection
