@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')
@section('page_title', 'Créer un Utilisateur Interne (Staff / Admin)')
@section('page_subtitle', 'Enregistrer un nouveau collaborateur et lui assigner ses rôles et permissions granulaires')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Informations du Compte Utilisateur</h5>
                    <p class="text-muted small mb-0">Cet utilisateur n'est pas un client, il a accès aux interfaces de gestion internes.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required placeholder="ex. Adam">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required placeholder="ex. Idrissi">
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom d'Utilisateur (Login) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="ex. aidrissi">
                        </div>
                        @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Adresse Email Professionnelle <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="adam@studio.com">
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Numéro de Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+212 6 ...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif (Autorisé à se connecter)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif (Bloqué)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 caractères">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Confirmer le Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Répétez le mot de passe">
                    </div>

                    {{-- Roles Selection --}}
                    <div class="col-12 mt-4 pt-3 border-top">
                        <label class="form-label small fw-semibold d-block mb-2">
                            <i class="bi bi-shield-check text-primary me-1"></i> Rôles Généraux
                        </label>
                        <div class="row g-2">
                            @foreach($roles as $role)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light">
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

                    {{-- DIRECT PERMISSIONS CHECKLIST REQUESTED BY USER --}}
                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-key text-warning me-1"></i> Liste des Permissions Granulaires
                                </h6>
                                <p class="text-muted small mb-0">Cochez une ou plusieurs permissions spécifiques pour cet utilisateur.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPermissions(true)">
                                    Tout Sélectionner
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAllPermissions(false)">
                                    Tout Désélectionner
                                </button>
                            </div>
                        </div>

                        <div class="row g-3">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border shadow-none rounded-3">
                                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-uppercase small text-dark tracking-wider">
                                                <i class="bi bi-folder-check text-primary me-1"></i>{{ str_replace('_', ' ', $module) }}
                                            </span>
                                            <span class="badge bg-secondary-subtle text-secondary small">{{ $modulePermissions->count() }}</span>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($modulePermissions as $perm)
                                                    <div class="form-check small py-1 px-3 rounded hover-bg">
                                                        <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                                            {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark" for="perm_{{ $perm->id }}">
                                                            {{ $perm->name }}
                                                        </label>
                                                        <div class="text-muted font-monospace" style="font-size: 0.68rem;">{{ $perm->slug }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

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
function toggleAllPermissions(check) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = check;
    });
}
</script>
@endpush
@endsection
