@extends('layouts.admin')

@section('title', 'Créer un Nouveau Client')
@section('page_title', 'Créer un Nouveau Client')
@section('page_subtitle', 'Enregistrer un client avec son nom, projet, ville, téléphone et générer son accès portail')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-custom p-4 bg-white shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Informations du Client</h5>
                    <p class="text-muted small mb-0">Remplissez les détails obligatoires et secondaires du client.</p>
                </div>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>

            <form method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    {{-- 1. Essential Fields requested by user: Name, Project Name, City, Phone --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom du Client / Contact <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror" value="{{ old('contact_name') }}" required placeholder="ex. Karim Benani">
                        </div>
                        @error('contact_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom du Projet</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-folder2-open"></i></span>
                            <input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror" value="{{ old('project_name') }}" placeholder="ex. Campagne Menu Printemps 2026">
                        </div>
                        @error('project_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Ville</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="ex. Casablanca, Rabat, Marrakech...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="ex. +212 6 00 00 00 00">
                        </div>
                    </div>

                    {{-- Additional Business Details --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Entreprise / Société</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="ex. Alpha Holding SARL">
                        </div>
                        <div class="form-text">Si non renseigné, le nom du projet ou du contact sera utilisé.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Adresse Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="contact@entreprise.com">
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Adresse Complète</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="ex. 45 Bd d'Anfa, Étage 3">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Pays</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', 'Maroc') }}" placeholder="Pays">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Logo de l'Entreprise</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">PNG, JPG, SVG ou WEBP max 2Mo</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif (Accès autorisé)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif (Suspendu)</option>
                        </select>
                    </div>

                    {{-- Portal Credentials --}}
                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold mb-1"><i class="bi bi-key-fill text-primary me-1"></i> Identifiants d'Accès au Portail Client</h6>
                            <p class="text-muted small mb-3">Ce code unique permet au client d'accéder à son espace dédié sur <code>/client/access</code>.</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Code d'Accès Client</label>
                                    <input type="text" name="access_code" class="form-control font-monospace fw-bold fs-6 text-uppercase @error('access_code') is-invalid @enderror" value="{{ old('access_code', $suggestedCode) }}">
                                    <small class="text-muted">Généré automatiquement ou personnalisable.</small>
                                    @error('access_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Mot de passe initial (Optionnel)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Laisser vide pour création par le client au 1er login">
                                    <small class="text-muted">Si vide, le client créera son mot de passe lors de sa première visite.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-semibold">Notes Internes Administrateur</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Notes privées réservées à l'équipe admin...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4">
                            <i class="bi bi-check2-circle"></i>
                            <span>Enregistrer le Client</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
