@extends('layouts.admin')

@section('title', 'Modifier l\'Utilisateur')
@section('page_title', 'Modifier l\'Utilisateur & Privilèges')
@section('page_subtitle', 'Mettre à jour le profil, les rôles et la matrice de permissions de ' . $user->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-11">
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Modifier le Compte : {{ $user->name }}</h5>
                    <p class="text-muted small mb-0">Identifiant : <code class="text-dark fw-bold">{{ $user->username }}</code> &bull; Réf #{{ $user->id }}</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <!-- Basic Profile Information -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom Complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required placeholder="ex. Adam Idrissi">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Adresse Email Professionnelle <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="adam@entreprise.com">
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Identifiant (Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" placeholder="Identifiant unique">
                        </div>
                        @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Numéro de Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+212 6 ...">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Actif (Connexion autorisée)</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspendu (Bloqué temporairement)</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactif (Désactivé)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nouveau Mot de passe (Laisser vide pour conserver)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="8 caractères minimum si modifié">
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Confirmer le Nouveau Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Répéter le mot de passe">
                        </div>
                    </div>

                    <!-- Roles Assignment -->
                    <div class="col-12 mt-4 pt-3 border-top">
                        <label class="form-label small fw-semibold d-block mb-2">
                            <i class="bi bi-shield-check text-primary me-1"></i> Rôles Assignés
                        </label>
                        <div class="row g-2">
                            @php
                                $assignedRoleIds = old('roles', $user->roles->pluck('id')->toArray());
                            @endphp
                            @foreach($roles as $role)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light h-100">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                {{ in_array($role->id, $assignedRoleIds) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                            <small class="text-muted d-block">{{ $role->description }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Granular Permissions Matrix -->
                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-sliders text-warning me-1"></i> Matrice de Permissions Granulaires
                                </h6>
                                <p class="text-muted small mb-0">Privilèges directs attribués individuellement à ce collaborateur.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleGlobalPermissions(true)">
                                    <i class="bi bi-check-all me-1"></i> Tout Sélectionner
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleGlobalPermissions(false)">
                                    <i class="bi bi-x me-1"></i> Tout Désélectionner
                                </button>
                            </div>
                        </div>

                        @php
                            $directPermissionIds = old('permissions', $user->directPermissions->pluck('id')->toArray());
                        @endphp

                        <div class="row g-3">
                            @foreach($permissionGroups as $moduleKey => $group)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card h-100 border shadow-none rounded-3 group-card" data-module="{{ $moduleKey }}">
                                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi {{ $group['icon'] }} text-primary"></i>
                                                <span class="fw-bold small text-dark text-uppercase tracking-wider">
                                                    {{ $group['title'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted" title="Cocher le module" onclick="toggleModuleGroup('{{ $moduleKey }}', true)">
                                                    <small class="fw-semibold text-primary">Tous</small>
                                                </button>
                                                <span class="text-muted small">|</span>
                                                <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted" title="Décocher le module" onclick="toggleModuleGroup('{{ $moduleKey }}', false)">
                                                    <small class="fw-semibold text-secondary">Aucun</small>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <small class="text-muted d-block mb-3" style="font-size: 0.78rem;">
                                                {{ $group['description'] }}
                                            </small>
                                            <div class="d-flex flex-column gap-2">
                                                @forelse($group['permissions'] as $perm)
                                                    <div class="form-check form-switch p-2 ps-5 rounded border bg-light-subtle">
                                                        <input class="form-check-input perm-checkbox perm-group-{{ $moduleKey }}"
                                                            type="checkbox"
                                                            role="switch"
                                                            name="permissions[]"
                                                            value="{{ $perm->id }}"
                                                            id="perm_{{ $perm->id }}"
                                                            {{ in_array($perm->id, $directPermissionIds) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark small" for="perm_{{ $perm->id }}">
                                                            {{ $perm->name }}
                                                        </label>
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

                    <!-- Submission -->
                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4">
                            <i class="bi bi-save2"></i>
                            <span>Mettre à Jour le Compte</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleGlobalPermissions(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = checked;
    });
}

function toggleModuleGroup(moduleKey, checked) {
    document.querySelectorAll('.perm-group-' + moduleKey).forEach(cb => {
        cb.checked = checked;
    });
}
</script>
@endpush
@endsection
