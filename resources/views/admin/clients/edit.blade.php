@extends('layouts.admin')

@section('title', 'Modifier le Client: ' . $client->contact_name)
@section('page_title', 'Modifier le Client: ' . $client->contact_name)
@section('page_subtitle', 'Mettre à jour les coordonnées, projet, ville, téléphone et accès portail')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-custom p-4 bg-white shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Modifier les Informations</h5>
                    <p class="text-muted small mb-0">Mise à jour des coordonnées pour {{ $client->contact_name }} ({{ $client->project_name ?? $client->company_name }}).</p>
                </div>
                <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Retour au Profil
                </a>
            </div>

            <form method="POST" action="{{ route('admin.clients.update', $client) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- 1. Essential Fields: Name, Project Name, City, Phone --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom du Client / Contact <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror" value="{{ old('contact_name', $client->contact_name) }}" required>
                        </div>
                        @error('contact_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nom du Projet</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-folder2-open"></i></span>
                            <input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror" value="{{ old('project_name', $client->project_name) }}">
                        </div>
                        @error('project_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Ville</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $client->city) }}" placeholder="ex. Casablanca">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}" placeholder="+212 6 ...">
                        </div>
                    </div>

                    {{-- Additional Business Details --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Entreprise / Société</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $client->company_name) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Adresse Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}" required>
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Adresse Complète</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $client->address) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Pays</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $client->country ?? 'Maroc') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Logo de l'Entreprise</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($client->logo)
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded border p-1" style="height: 36px; object-fit: contain;">
                                <small class="text-muted">Logo Actuel</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Statut du Compte <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Actif (Accès autorisé)</option>
                            <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactif (Suspendu)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Code d'Accès au Portail Client</label>
                        <input type="text" name="access_code" class="form-control font-monospace fw-bold text-uppercase @error('access_code') is-invalid @enderror" value="{{ old('access_code', $client->access_code) }}">
                        @error('access_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-semibold">Notes Internes Administrateur</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $client->notes) }}</textarea>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-outline-secondary">Annuler</a>
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
@endsection
