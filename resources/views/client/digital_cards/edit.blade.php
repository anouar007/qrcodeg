@extends('layouts.client')

@section('title', 'Modifier Carte Digitale — ' . ($digitalCard->title ?: $digitalCard->full_name))
@section('page_title', 'Modifier vos Coordonnées')
@section('page_subtitle', 'Mettez à jour les informations de contact partagées sur votre profil digital sans contact')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('client.digital-cards.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour à mes cartes digitales
        </a>
        <a href="{{ route('digital-cards.public.show', $digitalCard->slug) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
            <i class="bi bi-eye me-1"></i>Aperçu en direct
        </a>
    </div>

    @php
        $blocks = $digitalCard->content_blocks ?? [];
        $contacts = collect($blocks['contact_details'] ?? []);
        $socials = collect($blocks['social_links'] ?? []);
        
        $phoneVal = $contacts->firstWhere('type', 'phone')['value'] ?? '';
        $emailVal = $contacts->firstWhere('type', 'email')['value'] ?? '';
        $webVal = $contacts->firstWhere('type', 'website')['value'] ?? '';
        
        $waVal = str_replace('https://wa.me/', '', $socials->firstWhere('platform', 'whatsapp')['url'] ?? '');
        $liVal = $socials->firstWhere('platform', 'linkedin')['url'] ?? '';
        $instaVal = $socials->firstWhere('platform', 'instagram')['url'] ?? '';
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <form method="POST" action="{{ route('client.digital-cards.update', $digitalCard) }}">
                    @csrf
                    @method('PUT')

                    <h5 class="fw-bold text-dark pb-2 border-bottom mb-3">Identité Professionnelle</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label fw-semibold text-dark small">Titre de la Carte <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $digitalCard->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-semibold text-dark small">Nom &amp; Prénom</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $digitalCard->full_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="job_title" class="form-label fw-semibold text-dark small">Poste / Fonction</label>
                            <input type="text" name="job_title" id="job_title" class="form-control" value="{{ old('job_title', $digitalCard->job_title) }}" placeholder="Ex: Directeur Général, Responsable Commercial">
                        </div>
                        <div class="col-md-6">
                            <label for="company" class="form-label fw-semibold text-dark small">Établissement / Entreprise</label>
                            <input type="text" name="company" id="company" class="form-control" value="{{ old('company', $digitalCard->company) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="bio" class="form-label fw-semibold text-dark small">Description / Courte Présentation</label>
                        <textarea name="bio" id="bio" rows="3" class="form-control" placeholder="Présentation courte de votre activité, spécialité ou services...">{{ old('bio', $digitalCard->bio) }}</textarea>
                    </div>

                    <h5 class="fw-bold text-dark pb-2 border-bottom mb-3">Coordonnées Directes (.vcf Téléchargement)</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold text-dark small"><i class="bi bi-telephone me-1 text-primary"></i>Numéro de Téléphone Direct</label>
                            <input type="text" name="phone" id="phone" class="form-control font-mono" value="{{ old('phone', $phoneVal) }}" placeholder="+212 600 000 000">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold text-dark small"><i class="bi bi-envelope me-1 text-primary"></i>Email de Contact</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $emailVal) }}" placeholder="contact@domaine.com">
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label fw-semibold text-dark small"><i class="bi bi-globe me-1 text-primary"></i>Site Web / Portfolio</label>
                            <input type="url" name="website" id="website" class="form-control font-mono" value="{{ old('website', $webVal) }}" placeholder="https://monsite.com">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold text-dark small">Statut de la Carte</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" {{ old('status', $digitalCard->status) === 'active' ? 'selected' : '' }}>Active (En Ligne)</option>
                                <option value="inactive" {{ old('status', $digitalCard->status) === 'inactive' ? 'selected' : '' }}>Désactivée</option>
                            </select>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark pb-2 border-bottom mb-3">Réseaux Sociaux &amp; Messagerie</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="whatsapp" class="form-label fw-semibold text-dark small"><i class="bi bi-whatsapp text-success me-1"></i>Numéro WhatsApp</label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control font-mono" value="{{ old('whatsapp', $waVal) }}" placeholder="212600000000">
                        </div>
                        <div class="col-md-4">
                            <label for="linkedin" class="form-label fw-semibold text-dark small"><i class="bi bi-linkedin text-info me-1"></i>Lien LinkedIn</label>
                            <input type="url" name="linkedin" id="linkedin" class="form-control font-mono" value="{{ old('linkedin', $liVal) }}" placeholder="https://linkedin.com/in/nom">
                        </div>
                        <div class="col-md-4">
                            <label for="instagram" class="form-label fw-semibold text-dark small"><i class="bi bi-instagram text-danger me-1"></i>Lien Instagram</label>
                            <input type="url" name="instagram" id="instagram" class="form-control font-mono" value="{{ old('instagram', $instaVal) }}" placeholder="https://instagram.com/nom">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('client.digital-cards.index') }}" class="btn btn-sm btn-light border rounded-pill px-4">Annuler</a>
                        <button type="submit" class="btn btn-sm btn-primary-custom rounded-pill px-4 py-2 fw-bold shadow-xs">
                            <i class="bi bi-check-circle-fill me-1"></i>Enregistrer les Modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white sticky-top" style="top: 20px;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3 shadow-sm mx-auto" style="width: 72px; height: 72px; background: linear-gradient(135deg, #5736e9, #7e63f5); font-size: 1.5rem;">
                    {{ substr($digitalCard->full_name ?: $digitalCard->title, 0, 2) }}
                </div>
                <h6 class="fw-bold text-dark mb-0">{{ $digitalCard->full_name ?: $digitalCard->title }}</h6>
                <div class="text-muted small mb-3">{{ $digitalCard->job_title }}</div>

                <div class="p-3 bg-light rounded-3 font-mono text-truncate small mb-3 text-start">
                    <span class="text-muted d-block" style="font-size: 0.65rem;">Lien NFC &amp; Partage :</span>
                    <a href="{{ route('digital-cards.public.show', $digitalCard->slug) }}" target="_blank" class="text-primary text-decoration-none">
                        {{ url('/c/'.$digitalCard->slug) }}
                    </a>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('digital-cards.public.show', $digitalCard->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill py-2 fw-semibold">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Tester la Vue Smartphone
                    </a>
                    <a href="{{ route('client.digital-cards.menu', $digitalCard) }}" class="btn btn-sm btn-primary-custom rounded-pill py-2 fw-semibold">
                        <i class="fa-solid fa-utensils me-1"></i>Gérer le Menu &amp; Catalogue
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

