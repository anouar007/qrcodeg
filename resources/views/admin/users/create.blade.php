@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')
@section('page_title', 'Créer un Utilisateur Interne')
@section('page_subtitle', 'Enregistrer un collaborateur et lui assigner ses rôles et la matrice de permissions granulaires')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-11">
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Informations du Compte Collaborateur</h5>
                    <p class="text-muted small mb-0">Gestion interne du personnel, non applicable aux comptes clients externes.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <!-- Basic Profile Information -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom Complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="ex. Adam Idrissi">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Adresse Email Professionnelle <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="adam@entreprise.com">
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Identifiant (Optionnel)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Auto-généré si vide">
                        </div>
                        @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Numéro de Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+212 6 ...">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif (Connexion autorisée)</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspendu (Bloqué temporairement)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif (Désactivé)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 caractères">
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Confirmer le Mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Répéter le mot de passe">
                        </div>
                    </div>

                    <!-- Roles Assignment -->
                    <div class="col-12 mt-4 pt-3 border-top">
                        <label class="form-label small fw-semibold d-block mb-2">
                            <i class="bi bi-shield-check text-primary me-1"></i> Attribution des Rôles
                        </label>
                        <div class="row g-2">
                            @foreach($roles as $role)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light h-100">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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
                                <p class="text-muted small mb-0">Assignez des permissions directes réparties en 5 modules clés.</p>
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
                                                            {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
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
                            <i class="bi bi-check2-circle"></i>
                            <span>Créer le Compte Utilisateur</span>
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
