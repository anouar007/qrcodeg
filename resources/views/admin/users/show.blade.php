@extends('layouts.admin')

@section('title', 'Dossier Collaborateur : ' . $user->name)
@section('page_title', 'Dossier Collaborateur & Audit de Sécurité')
@section('page_subtitle', 'Vue détaillée des privilèges effectifs, ressources créées et historique d\'activité de ' . $user->name)

@push('styles')
<style>
    .dossier-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border-radius: 18px;
        padding: 1.75rem;
        color: #ffffff;
        box-shadow: 0 12px 28px -8px rgba(15, 23, 42, 0.25);
    }
    .dossier-avatar {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.14);
        border: 2px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        font-weight: 800;
        color: #ffffff;
        flex-shrink: 0;
    }
    .footprint-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.15rem 1.25rem;
    }
    .perm-audit-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 0.45rem;
        font-size: 0.82rem;
    }
    .perm-audit-row.is-granted {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .perm-audit-row.is-denied {
        background: #f8fafc;
        opacity: 0.65;
    }
</style>
@endpush

@section('content')
@php
    $isCurrentUser = (auth('web')->id() === $user->id);
    $isUserAdmin = $user->isAdmin();
    $rolePermSet = collect($rolePermissionIds ?? []);
    $directPermSet = collect($directPermissionIds ?? []);
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
        <i class="bi bi-arrow-left me-1"></i> Retour au répertoire
    </a>

    <div class="d-flex flex-wrap align-items-center gap-2">
        @can('update', $user)
            <button type="button" class="btn btn-outline-dark btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#dossierPasswordModal">
                <i class="bi bi-key me-1"></i> Réinitialiser le Mot de Passe
            </button>
            @if(!$isCurrentUser)
                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-3 {{ $user->status === 'active' ? 'btn-outline-warning text-dark' : 'btn-outline-success' }}">
                        <i class="bi {{ $user->status === 'active' ? 'bi-pause-circle' : 'bi-play-circle' }} me-1"></i>
                        {{ $user->status === 'active' ? 'Suspendre' : 'Activer' }}
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm rounded-3 px-3">
                <i class="bi bi-pencil-square me-1"></i> Modifier & Permissions
            </a>
        @endcan
    </div>
</div>

<!-- 1. Hero Profile Banner -->
<div class="dossier-hero-card mb-4">
    <div class="row align-items-center g-3">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-3">
                <div class="dossier-avatar">
                    {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h4 class="fw-bold text-white mb-0">{{ $user->name }}</h4>
                        @if($user->status === 'active')
                            <span class="badge bg-success rounded-pill px-2.5 py-1">Actif</span>
                        @elseif($user->status === 'suspended')
                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">Suspendu</span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-2.5 py-1">Inactif</span>
                        @endif
                        @if($isUserAdmin)
                            <span class="badge bg-primary rounded-pill px-2.5 py-1"><i class="bi bi-shield-lock-fill me-1"></i>SuperAdmin</span>
                        @endif
                    </div>
                    <div class="text-white-50 small d-flex flex-wrap align-items-center gap-3">
                        <span><i class="bi bi-at me-1"></i><code class="text-white">{{ $user->username }}</code></span>
                        <span><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                        @if($user->phone)
                            <span><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 text-lg-end">
            <div class="d-inline-flex flex-wrap gap-2 justify-content-lg-end">
                <div class="px-3 py-2 rounded-3" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);">
                    <small class="text-white-50 d-block" style="font-size: 0.68rem;">Dernière connexion</small>
                    <span class="fw-bold small">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Jamais connecté' }}</span>
                </div>
                <div class="px-3 py-2 rounded-3" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);">
                    <small class="text-white-50 d-block" style="font-size: 0.68rem;">Date d'enregistrement</small>
                    <span class="fw-bold small">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Resource Footprint Counters -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="footprint-card">
            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.68rem;">Cartes Digitales</span>
            <h4 class="fw-bold text-dark mb-0 mt-1">{{ $user->digital_cards_count ?? 0 }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="footprint-card">
            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.68rem;">QR Codes Créés</span>
            <h4 class="fw-bold text-dark mb-0 mt-1">{{ $user->qr_codes_count ?? 0 }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="footprint-card">
            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.68rem;">Planches & Documents</span>
            <h4 class="fw-bold text-dark mb-0 mt-1">{{ $user->qr_documents_count ?? 0 }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="footprint-card">
            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.68rem;">Actions Journalisées</span>
            <h4 class="fw-bold text-primary mb-0 mt-1">{{ $user->activity_logs_count ?? 0 }}</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- 3. Effective Permissions Breakdown -->
    <div class="col-xl-7">
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Matrice des Privilèges Effectifs</h5>
                    <small class="text-muted">Droits cumulés via les rôles RBAC et les permissions directes</small>
                </div>
                <div class="d-flex gap-1">
                    @forelse($user->roles as $r)
                        <span class="badge bg-primary rounded-pill px-2.5 py-1">{{ $r->name }}</span>
                    @empty
                        <span class="badge bg-light text-muted border">Aucun rôle</span>
                    @endforelse
                </div>
            </div>

            <div class="row g-3">
                @foreach($permissionGroups as $moduleKey => $group)
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                            <div class="d-flex align-items-center gap-2 mb-2 pb-1 border-bottom">
                                <i class="bi {{ $group['icon'] }} text-primary"></i>
                                <span class="fw-bold small text-uppercase text-dark">{{ $group['title'] }}</span>
                            </div>

                            @foreach($group['permissions'] as $perm)
                                @php
                                    $viaRole = $isUserAdmin || $rolePermSet->contains($perm->id);
                                    $viaDirect = $directPermSet->contains($perm->id);
                                    $isGranted = $viaRole || $viaDirect;
                                @endphp
                                <div class="perm-audit-row {{ $isGranted ? 'is-granted' : 'is-denied' }}">
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-dark text-truncate">{{ $perm->name }}</div>
                                        <code class="text-muted" style="font-size: 0.66rem;">{{ $perm->slug }}</code>
                                    </div>
                                    <div class="ms-2 flex-shrink-0">
                                        @if($viaDirect)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.64rem;">Directe</span>
                                        @elseif($viaRole)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.64rem;">Via Rôle</span>
                                        @else
                                            <span class="badge bg-light text-muted border" style="font-size: 0.64rem;">Non</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 4. Recent Security & Activity Logs -->
    <div class="col-xl-5">
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Journal d'Audit & Activité</h5>
                    <small class="text-muted">20 derniers événements liés à ce collaborateur</small>
                </div>
                <i class="bi bi-clock-history text-primary fs-5"></i>
            </div>

            <div class="d-flex flex-column gap-2">
                @forelse($recentLogs as $log)
                    <div class="p-2.5 rounded-3 border bg-light-subtle d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="badge bg-dark font-monospace mb-1" style="font-size: 0.68rem;">{{ $log->action }}</span>
                            <div class="small text-muted">
                                Ressource : <strong>{{ $log->resource_type }}</strong>
                                @if($log->resource_id) #{{ $log->resource_id }} @endif
                            </div>
                        </div>
                        <small class="text-muted font-monospace flex-shrink-0" style="font-size: 0.7rem;">
                            {{ $log->created_at?->diffForHumans() }}
                        </small>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-text display-6 d-block mb-2 opacity-50"></i>
                        <span class="small">Aucune activité enregistrée pour le moment.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
@can('update', $user)
<div class="modal fade" id="dossierPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                @csrf
                <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                    <h6 class="modal-title fw-bold mb-0"><i class="bi bi-key-fill text-warning me-2"></i>Réinitialiser le Mot de Passe</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nouveau Mot de Passe <span class="text-danger">*</span></label>
                        <input type="text" name="password" class="form-control font-monospace" required minlength="8" value="{{ Str::random(12) }}!9">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Confirmer le Mot de Passe <span class="text-danger">*</span></label>
                        <input type="text" name="password_confirmation" class="form-control font-monospace" required minlength="8" placeholder="Répéter le mot de passe">
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2.5 px-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3.5 fw-semibold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

