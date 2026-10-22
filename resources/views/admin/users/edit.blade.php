@extends('layouts.admin')

@section('title', 'Modifier l\'Utilisateur: ' . $user->name)
@section('page_title', 'Modifier l\'Utilisateur: ' . $user->name)
@section('page_subtitle', 'Mettre à jour les informations, rôles et permissions d\'accès')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4 bg-white border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Modifier le Profil Utilisateur</h5>
                    <p class="text-muted small mb-0">Gestion des droits et accès pour {{ $user->name }} ({{ $user->username }}).</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom d'Utilisateur <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                        </div>
                        @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Professionnel <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Actif (Autorisé)</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactif (Bloqué)</option>
                        </select>
                    </div>

                    <div class="col-12 mt-3 pt-3 border-top">
                        <h6 class="fw-bold mb-1">Réinitialiser le Mot de passe (Optionnel)</h6>
                        <small class="text-muted d-block mb-3">Laissez vide si vous ne souhaitez pas modifier le mot de passe actuel.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nouveau Mot de passe</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Confirmer le Nouveau Mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                    </div>

                    {{-- Roles Checklist --}}
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
                                    <div class="p-3 border rounded-3 bg-light">
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

                    {{-- DIRECT PERMISSIONS CHECKLIST REQUESTED BY USER --}}
                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-key text-warning me-1"></i> Permissions Granulaires de l'Utilisateur
                                </h6>
                                <p class="text-muted small mb-0">Cochez une ou plusieurs permissions que cet utilisateur aura le droit d'exécuter.</p>
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

                        @php
                            $userDirectPermIds = old('permissions', $user->directPermissions->pluck('id')->toArray());
                        @endphp

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
                                                            {{ in_array($perm->id, $userDirectPermIds) ? 'checked' : '' }}>
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
                            <span>Enregistrer les Modifications</span>
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
