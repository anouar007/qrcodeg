@extends('layouts.admin')

@section('title', 'Modifier l\'Utilisateur')
@section('page_title', 'Modifier l\'Utilisateur & Privilèges')
@section('page_subtitle', 'Mettre à jour le profil, les rôles RBAC et la matrice de permissions de ' . $user->name)

@push('styles')
<style>
    .studio-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }
    .studio-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .studio-step-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        background: #eef2ff;
        color: #4f46e5;
        flex-shrink: 0;
    }
    .role-select-card {
        position: relative;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.15rem;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .role-select-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .role-select-card.is-selected {
        border-color: #4f46e5;
        background: #f5f3ff;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.12);
    }
    .perm-module-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        overflow: hidden;
        transition: all 0.18s ease;
    }
    .perm-module-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px -6px rgba(15, 23, 42, 0.06);
    }
    .perm-item-row {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.55rem 0.75rem 0.55rem 2.6rem;
        background: #f8fafc;
        transition: all 0.15s ease;
    }
    .perm-item-row.is-direct-checked {
        background: #eff6ff;
        border-color: #93c5fd;
    }
    .perm-item-row.is-role-inherited {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .inherited-role-pill {
        display: none;
        font-size: 0.62rem;
        padding: 0.1rem 0.45rem;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
    }
    .perm-item-row.is-role-inherited .inherited-role-pill {
        display: inline-block;
    }
    .sticky-summary-card {
        position: sticky;
        top: 90px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px -8px rgba(15, 23, 42, 0.08);
    }
    .summary-banner {
        background: linear-gradient(135deg, #0f172a 0%, #312e81 100%);
        padding: 1.35rem;
        color: #ffffff;
    }
    .summary-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 800;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
@php
    $roleMap = $rolePermissionsMap ?? $roles->mapWithKeys(fn($r) => [$r->id => $r->permissions->pluck('id')->all()])->all();
    $totalSystemPermissions = collect($permissionGroups)->sum(fn($g) => count($g['permissions']));
    $assignedRoleIds = old('roles', $user->roles->pluck('id')->toArray());
    $directPermissionIds = old('permissions', $user->directPermissions->pluck('id')->toArray());
@endphp

<form method="POST" action="{{ route('admin.users.update', $user) }}" id="userEditForm">
    @csrf
    @method('PUT')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </a>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-dark btn-sm rounded-3">
                <i class="bi bi-eye me-1"></i> Voir le Dossier
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold shadow-sm">
                <i class="bi bi-save2 me-1"></i> Mettre à Jour le Compte
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Left Column -->
        <div class="col-xl-8">
            <!-- Section 1: Identity & Contact -->
            <div class="studio-section-card">
                <div class="studio-section-header">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="studio-step-badge">1</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Modifier le Compte : {{ $user->name }}</h5>
                            <small class="text-muted">Identifiant : <code class="text-dark fw-bold">{{ $user->username }}</code> &bull; Réf #{{ $user->id }}</small>
                        </div>
                    </div>
                    <span class="badge bg-light text-secondary border"> Créé le {{ $user->created_at->format('d/m/Y') }}</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom Complet <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="inputUserName" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required placeholder="ex. Adam Idrissi" oninput="syncCollaboratorPreview()">
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Adresse Email Professionnelle <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="inputUserEmail" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required placeholder="adam@entreprise.com" oninput="syncCollaboratorPreview()">
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Identifiant (Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" id="inputUserUsername" class="form-control font-monospace @error('username') is-invalid @enderror"
                                   value="{{ old('username', $user->username) }}" placeholder="Identifiant unique" data-manual="1" oninput="syncCollaboratorPreview()">
                        </div>
                        @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Numéro de Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" id="inputUserPhone" class="form-control font-monospace"
                                   value="{{ old('phone', $user->phone) }}" placeholder="+212 6 ..." oninput="syncCollaboratorPreview()">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" id="inputUserStatus" class="form-select" required onchange="syncCollaboratorPreview()">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Actif (Connexion autorisée)</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspendu (Bloqué temporairement)</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactif (Désactivé)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Security & Password Update -->
            <div class="studio-section-card">
                <div class="studio-section-header">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="studio-step-badge">2</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Sécurité & Renouvellement du Mot de Passe</h5>
                            <small class="text-muted">Laissez ces champs vides pour conserver le mot de passe actuel du collaborateur</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="generateStrongUserPassword()">
                        <i class="bi bi-magic me-1"></i>Générer un Nouveau MDP
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nouveau Mot de passe (Laisser vide pour conserver)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="inputUserPassword" class="form-control font-monospace @error('password') is-invalid @enderror"
                                   placeholder="8 caractères minimum si modifié" oninput="evaluatePasswordStrength(this.value)">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('inputUserPassword', 'inputUserPasswordConfirm', this)" title="Afficher / Masquer">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="copyPasswordToClipboard()" title="Copier">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Confirmer le Nouveau Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password_confirmation" id="inputUserPasswordConfirm" class="form-control font-monospace"
                                   placeholder="Répéter le mot de passe">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between small mb-1">
                            <span class="text-muted" style="font-size: 0.75rem;">Robustesse du nouveau mot de passe :</span>
                            <span id="passwordStrengthText" class="fw-bold text-muted" style="font-size: 0.75rem;">Mot de passe actuel conservé</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 99px;">
                            <div id="passwordStrengthBar" class="progress-bar bg-secondary" style="width: 0%; transition: width 0.25s ease;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Roles Assignment -->
            <div class="studio-section-card">
                <div class="studio-section-header">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="studio-step-badge">3</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Rôles Assignés</h5>
                            <small class="text-muted">Les rôles accordent automatiquement leurs permissions associées</small>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($roles as $role)
                        @php
                            $isRoleChecked = in_array($role->id, $assignedRoleIds);
                            $rolePermCount = $role->permissions ? $role->permissions->count() : 0;
                        @endphp
                        <div class="col-md-6">
                            <div class="role-select-card h-100 {{ $isRoleChecked ? 'is-selected' : '' }}" id="roleCard_{{ $role->id }}" onclick="toggleRoleCardClick(event, {{ $role->id }})">
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                               id="role_{{ $role->id }}"
                                               data-role-name="{{ $role->name }}"
                                               data-role-slug="{{ $role->slug }}"
                                               {{ $isRoleChecked ? 'checked' : '' }}
                                               onchange="onRoleSelectionChanged()">
                                        <label class="form-check-label fw-bold text-dark cursor-pointer" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                    <span class="badge {{ $role->slug === 'admin' ? 'bg-primary' : 'bg-secondary-subtle text-secondary border' }} rounded-pill" style="font-size: 0.68rem;">
                                        {{ $role->slug === 'admin' ? 'Accès Total' : $rolePermCount . ' perm.' }}
                                    </span>
                                </div>
                                <small class="text-muted d-block mt-1 ps-4">{{ $role->description }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 4: Granular Permissions Matrix -->
            <div class="studio-section-card">
                <div class="studio-section-header flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="studio-step-badge">4</span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Matrice de Permissions Granulaires</h5>
                            <small class="text-muted">Privilèges directs attribués individuellement à ce collaborateur</small>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-1.5">
                        <button type="button" class="btn btn-xs btn-outline-dark rounded-pill px-2.5 py-1 small" onclick="applyPermissionProfilePreset('readonly')">
                            <i class="bi bi-eye me-1"></i>Lecture Seule
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-dark rounded-pill px-2.5 py-1 small" onclick="applyPermissionProfilePreset('operator')">
                            <i class="bi bi-briefcase me-1"></i>Opérateur Studio
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 small" onclick="toggleGlobalPermissions(true)">
                            <i class="bi bi-check-all me-1"></i>Tout Sélectionner
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 small" onclick="toggleGlobalPermissions(false)">
                            <i class="bi bi-x me-1"></i>Tout Désélectionner
                        </button>
                    </div>
                </div>

                <!-- Live Filter inside Permission Matrix -->
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-md-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="permMatrixSearchInput" class="form-control border-start-0"
                                   placeholder="Filtrer une permission par nom ou code technique (ex: export, qr, nfc...)"
                                   oninput="filterPermissionsMatrix(this.value)">
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <span class="small text-muted">
                            Légende :
                            <span class="badge bg-primary-subtle text-primary border ms-1">Directe</span>
                            <span class="badge bg-success-subtle text-success border ms-1">Hérité du Rôle</span>
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($permissionGroups as $moduleKey => $group)
                        <div class="col-lg-6">
                            <div class="perm-module-card h-100 group-card" data-module="{{ $moduleKey }}">
                                <div class="bg-light py-2.5 px-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi {{ $group['icon'] }} text-primary"></i>
                                        <span class="fw-bold small text-dark text-uppercase" style="letter-spacing: 0.03em;">
                                            {{ $group['title'] }}
                                        </span>
                                        <span class="badge bg-white text-dark border rounded-pill font-monospace" style="font-size: 0.68rem;" id="moduleCounter_{{ $moduleKey }}">
                                            0/{{ count($group['permissions']) }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-link btn-sm text-decoration-none p-0" onclick="toggleModuleGroup('{{ $moduleKey }}', true)">
                                            <small class="fw-semibold text-primary">Tous</small>
                                        </button>
                                        <span class="text-muted small">|</span>
                                        <button type="button" class="btn btn-link btn-sm text-decoration-none p-0" onclick="toggleModuleGroup('{{ $moduleKey }}', false)">
                                            <small class="fw-semibold text-secondary">Aucun</small>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <small class="text-muted d-block mb-3" style="font-size: 0.76rem;">
                                        {{ $group['description'] }}
                                    </small>
                                    <div class="d-flex flex-column gap-2">
                                        @forelse($group['permissions'] as $perm)
                                            @php
                                                $isChecked = in_array($perm->id, $directPermissionIds);
                                            @endphp
                                            <div class="form-check form-switch perm-item-row {{ $isChecked ? 'is-direct-checked' : '' }}"
                                                 id="permRow_{{ $perm->id }}"
                                                 data-perm-id="{{ $perm->id }}"
                                                 data-perm-slug="{{ $perm->slug }}"
                                                 data-perm-text="{{ strtolower($perm->name . ' ' . $perm->slug) }}">
                                                <input class="form-check-input perm-checkbox perm-group-{{ $moduleKey }} cursor-pointer"
                                                       type="checkbox"
                                                       role="switch"
                                                       name="permissions[]"
                                                       value="{{ $perm->id }}"
                                                       id="perm_{{ $perm->id }}"
                                                       data-module="{{ $moduleKey }}"
                                                       data-slug="{{ $perm->slug }}"
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       onchange="refreshPermissionsSummaryState()">
                                                <div class="d-flex align-items-center justify-content-between gap-1">
                                                    <label class="form-check-label fw-semibold text-dark small cursor-pointer" for="perm_{{ $perm->id }}">
                                                        {{ $perm->name }}
                                                    </label>
                                                    <span class="inherited-role-pill">Via Rôle</span>
                                                </div>
                                                <div class="text-muted font-monospace" style="font-size: 0.68rem;">{{ $perm->slug }}</div>
                                            </div>
                                        @empty
                                            <span class="text-muted small">Aucune permission pour ce module.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sticky Right Summary Sidebar -->
        <div class="col-xl-4">
            <div class="sticky-summary-card">
                <div class="summary-banner">
                    <div class="d-flex align-items-center gap-3">
                        <div class="summary-avatar" id="previewAvatarInitials">
                            {{ strtoupper(substr($user->name ?: 'U', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <span class="badge bg-success rounded-pill mb-1" id="previewStatusBadge" style="font-size: 0.68rem;">Actif</span>
                            <h6 class="fw-bold text-white mb-0 text-truncate" id="previewFullName">{{ $user->name }}</h6>
                            <small class="text-white-50 font-monospace d-block text-truncate" id="previewUsernameHandle">&#64;{{ $user->username }}</small>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between small mb-1.5">
                            <span class="text-muted">Email :</span>
                            <span class="fw-semibold text-dark text-truncate ms-2" style="max-width: 190px;" id="previewEmailText">{{ $user->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1.5">
                            <span class="text-muted">Téléphone :</span>
                            <span class="fw-semibold text-dark font-monospace" id="previewPhoneText">{{ $user->phone ?: '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Dernier accès :</span>
                            <span class="fw-semibold text-dark">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Jamais' }}</span>
                        </div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-2" style="font-size: 0.7rem;">Rôles Sélectionnés</span>
                        <div id="previewSelectedRolesWrap" class="d-flex flex-wrap gap-1"></div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Privilèges Effectifs</span>
                            <span class="badge bg-primary-subtle text-primary rounded-pill font-monospace" id="previewEffectivePermsBadge">0 / {{ $totalSystemPermissions }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 7px; border-radius: 99px;">
                            <div id="previewEffectivePermsBar" class="progress-bar bg-primary" style="width: 0%;"></div>
                        </div>
                        <div class="d-flex justify-content-between small text-muted" style="font-size: 0.74rem;">
                            <span>Via Rôle(s): <strong class="text-dark" id="previewInheritedCount">0</strong></span>
                            <span>Directes: <strong class="text-dark" id="previewDirectCount">0</strong></span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2.5 fw-semibold rounded-3 shadow-sm">
                            <i class="bi bi-save2 me-1"></i> Mettre à Jour le Compte
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary py-2 rounded-3">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
const rolePermissionsMap = @json($roleMap);
const totalSystemPermissions = {{ (int) $totalSystemPermissions }};

function syncCollaboratorPreview() {
    const nameVal = (document.getElementById('inputUserName')?.value || '').trim();
    const emailVal = (document.getElementById('inputUserEmail')?.value || '').trim();
    const usernameVal = (document.getElementById('inputUserUsername')?.value || '').trim();
    const phoneVal = (document.getElementById('inputUserPhone')?.value || '').trim();
    const statusVal = document.getElementById('inputUserStatus')?.value || 'active';

    const initials = nameVal
        ? nameVal.split(/\s+/).map(p => p.charAt(0)).join('').substring(0, 2).toUpperCase()
        : 'U';

    document.getElementById('previewAvatarInitials').textContent = initials;
    document.getElementById('previewFullName').textContent = nameVal || 'Collaborateur';
    document.getElementById('previewUsernameHandle').textContent = '@' + (usernameVal || 'identifiant');
    document.getElementById('previewEmailText').textContent = emailVal || '—';
    document.getElementById('previewPhoneText').textContent = phoneVal || '—';

    const statusBadge = document.getElementById('previewStatusBadge');
    if (statusBadge) {
        if (statusVal === 'active') {
            statusBadge.className = 'badge bg-success rounded-pill mb-1';
            statusBadge.textContent = 'Actif';
        } else if (statusVal === 'suspended') {
            statusBadge.className = 'badge bg-warning text-dark rounded-pill mb-1';
            statusBadge.textContent = 'Suspendu';
        } else {
            statusBadge.className = 'badge bg-secondary rounded-pill mb-1';
            statusBadge.textContent = 'Inactif';
        }
    }
}

function toggleRoleCardClick(evt, roleId) {
    if (evt.target.tagName === 'INPUT' || evt.target.tagName === 'LABEL') return;
    const cb = document.getElementById('role_' + roleId);
    if (cb) {
        cb.checked = !cb.checked;
        onRoleSelectionChanged();
    }
}

function onRoleSelectionChanged() {
    document.querySelectorAll('.role-checkbox').forEach(cb => {
        const card = document.getElementById('roleCard_' + cb.value);
        if (card) {
            card.classList.toggle('is-selected', cb.checked);
        }
    });
    refreshPermissionsSummaryState();
}

function refreshPermissionsSummaryState() {
    const selectedRoleCheckboxes = Array.from(document.querySelectorAll('.role-checkbox:checked'));
    const inheritedIds = new Set();
    let isSuperAdminRole = false;

    const rolesPreviewWrap = document.getElementById('previewSelectedRolesWrap');
    if (selectedRoleCheckboxes.length > 0) {
        rolesPreviewWrap.innerHTML = selectedRoleCheckboxes.map(cb => {
            const rSlug = cb.dataset.roleSlug;
            if (rSlug === 'admin') isSuperAdminRole = true;
            const rPerms = rolePermissionsMap[cb.value] || [];
            rPerms.forEach(id => inheritedIds.add(Number(id)));
            return `<span class="badge ${rSlug === 'admin' ? 'bg-primary' : 'bg-dark'} rounded-pill px-2.5 py-1"><i class="bi bi-shield-check me-1"></i>${cb.dataset.roleName}</span>`;
        }).join(' ');
    } else {
        rolesPreviewWrap.innerHTML = '<span class="text-muted small">Aucun rôle sélectionné</span>';
    }

    let directCount = 0;
    const effectiveIds = new Set(inheritedIds);

    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        const permId = Number(cb.value);
        const row = document.getElementById('permRow_' + permId);
        const isInherited = isSuperAdminRole || inheritedIds.has(permId);

        if (cb.checked) {
            directCount++;
            effectiveIds.add(permId);
        }
        if (row) {
            row.classList.toggle('is-direct-checked', cb.checked);
            row.classList.toggle('is-role-inherited', isInherited);
        }
    });

    document.querySelectorAll('.group-card').forEach(card => {
        const mod = card.dataset.module;
        const boxes = Array.from(card.querySelectorAll('.perm-checkbox'));
        const activeInMod = boxes.filter(b => b.checked || isSuperAdminRole || inheritedIds.has(Number(b.value))).length;
        const badge = document.getElementById('moduleCounter_' + mod);
        if (badge) {
            badge.textContent = `${activeInMod}/${boxes.length}`;
        }
    });

    const effectiveTotal = isSuperAdminRole ? totalSystemPermissions : effectiveIds.size;
    const pct = totalSystemPermissions > 0 ? Math.round((effectiveTotal / totalSystemPermissions) * 100) : 0;

    document.getElementById('previewInheritedCount').textContent = isSuperAdminRole ? totalSystemPermissions : inheritedIds.size;
    document.getElementById('previewDirectCount').textContent = directCount;
    document.getElementById('previewEffectivePermsBadge').textContent = `${effectiveTotal} / ${totalSystemPermissions}`;
    document.getElementById('previewEffectivePermsBar').style.width = `${pct}%`;
}

function toggleGlobalPermissions(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = checked;
    });
    refreshPermissionsSummaryState();
}

function toggleModuleGroup(moduleKey, checked) {
    document.querySelectorAll('.perm-group-' + moduleKey).forEach(cb => {
        cb.checked = checked;
    });
    refreshPermissionsSummaryState();
}

function applyPermissionProfilePreset(preset) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        const slug = cb.dataset.slug || '';
        if (preset === 'readonly') {
            cb.checked = slug.startsWith('view_');
        } else if (preset === 'operator') {
            cb.checked = slug.startsWith('view_') || slug.startsWith('create_') || slug.startsWith('edit_') || slug.startsWith('export_') || slug.startsWith('redirect_');
        }
    });
    refreshPermissionsSummaryState();
}

function filterPermissionsMatrix(query) {
    const q = (query || '').toLowerCase().trim();
    document.querySelectorAll('.perm-item-row').forEach(row => {
        const text = row.dataset.permText || '';
        row.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
}

function generateStrongUserPassword() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*';
    let pass = '';
    for (let i = 0; i < 14; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    const p1 = document.getElementById('inputUserPassword');
    const p2 = document.getElementById('inputUserPasswordConfirm');
    if (p1 && p2) {
        p1.type = 'text';
        p2.type = 'text';
        p1.value = pass;
        p2.value = pass;
        evaluatePasswordStrength(pass);
    }
}

function togglePasswordVisibility(id1, id2, btn) {
    const p1 = document.getElementById(id1);
    const p2 = document.getElementById(id2);
    if (!p1) return;
    const isPass = p1.type === 'password';
    p1.type = isPass ? 'text' : 'password';
    if (p2) p2.type = isPass ? 'text' : 'password';
    if (btn) {
        btn.innerHTML = isPass ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
    }
}

function copyPasswordToClipboard() {
    const val = document.getElementById('inputUserPassword')?.value;
    if (!val) return;
    navigator.clipboard.writeText(val);
}

function evaluatePasswordStrength(val) {
    const bar = document.getElementById('passwordStrengthBar');
    const txt = document.getElementById('passwordStrengthText');
    if (!bar || !txt) return;
    if (!val) {
        bar.style.width = '0%';
        bar.className = 'progress-bar bg-secondary';
        txt.textContent = 'Mot de passe actuel conservé';
        txt.className = 'fw-bold text-muted';
        return;
    }
    let score = 0;
    if (val.length >= 8) score += 25;
    if (val.length >= 12) score += 25;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 25;
    if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score += 25;

    bar.style.width = score + '%';
    if (score <= 25) {
        bar.className = 'progress-bar bg-danger';
        txt.textContent = 'Faible';
        txt.className = 'fw-bold text-danger';
    } else if (score <= 50) {
        bar.className = 'progress-bar bg-warning';
        txt.textContent = 'Moyen';
        txt.className = 'fw-bold text-warning';
    } else if (score <= 75) {
        bar.className = 'progress-bar bg-info';
        txt.textContent = 'Fort';
        txt.className = 'fw-bold text-info';
    } else {
        bar.className = 'progress-bar bg-success';
        txt.textContent = 'Très Fort (Excellent)';
        txt.className = 'fw-bold text-success';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    syncCollaboratorPreview();
    refreshPermissionsSummaryState();
});
</script>
@endpush
@endsection
