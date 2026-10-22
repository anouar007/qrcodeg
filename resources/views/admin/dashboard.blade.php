@extends('layouts.admin')

@section('title', 'Tableau de Bord Général')
@section('page_title', 'Tableau de Bord Général')
@section('page_subtitle', 'Vue d\'ensemble analytique et sections statistiques dédiées pour chaque partie de la plateforme')

@push('styles')
<style>
.stat-section-card {
    border-radius: 16px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    background: #ffffff;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}
.stat-pill {
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
}
.icon-box-lg {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.section-badge-title {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-weight: 700;
}
</style>
@endpush

@section('content')

{{-- TOP ACTION QUICK BAR --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2 border-bottom">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Bienvenue sur votre espace d'administration</h4>
        <p class="text-muted small mb-0">Statistiques en temps réel actualisées le {{ now()->format('d/m/Y à H:i') }}.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.clients.create') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            <i class="bi bi-building-add me-1"></i> Nouveau Client
        </a>
        <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-outline-purple rounded-pill px-3" style="border-color: #8b5cf6; color: #7c3aed;">
            <i class="bi bi-person-vcard me-1"></i> Nouvelle Carte Visite
        </a>
        <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
            <i class="bi bi-broadcast me-1"></i> Créer Tag NFC
        </a>
        <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-qr-code me-1"></i> Générer QR VP
        </a>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 1. OVERALL STATISTIC SECTION (TRAFIC & INTERACTIONS GLOBALES)             --}}
{{-- ========================================================================= --}}
<div class="mb-5">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-primary text-white p-2 rounded-3"><i class="bi bi-speedometer2"></i></span>
        <h5 class="fw-bold mb-0">1. Vue d'Ensemble & Trafic Global</h5>
    </div>

    <div class="row g-3 mb-4">
        <!-- Interactions Aujourd'hui -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-section-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Interactions Aujourd'hui</span>
                    <div class="icon-box-lg bg-primary-subtle text-primary">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                </div>
                <div class="display-6 fw-bold text-dark">{{ number_format($totalInteractionsToday) }}</div>
                <div class="small text-muted mt-2 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-qr-code text-primary me-1"></i>{{ $qrScansToday }} Scans QR</span>
                    <span><i class="bi bi-broadcast text-info me-1"></i>{{ $nfcTapsToday }} Taps NFC</span>
                </div>
            </div>
        </div>

        <!-- Total Scans QR Historique -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-section-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Total Scans QR</span>
                    <div class="icon-box-lg bg-info-subtle text-info">
                        <i class="bi bi-upc-scan"></i>
                    </div>
                </div>
                <div class="display-6 fw-bold text-dark">{{ number_format($totalQrScans) }}</div>
                <div class="small text-muted mt-2">
                    <span class="text-success fw-bold"><i class="bi bi-graph-up-arrow me-1"></i>{{ $scansThisMonth }}</span> scans enregistrés ce mois
                </div>
            </div>
        </div>

        <!-- Total Vues Cartes Visite -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-section-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Vues Cartes Visite</span>
                    <div class="icon-box-lg bg-purple-subtle text-purple" style="background-color: #ede9fe; color: #6d28d9;">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                </div>
                <div class="display-6 fw-bold text-dark">{{ number_format($totalCardViews) }}</div>
                <div class="small text-muted mt-2">
                    <span>{{ $activeDigitalCards }} cartes actives en ligne</span>
                </div>
            </div>
        </div>

        <!-- Total Taps NFC Détectés -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-section-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Total Taps NFC</span>
                    <div class="icon-box-lg bg-success-subtle text-success">
                        <i class="bi bi-phone-vibrate"></i>
                    </div>
                </div>
                <div class="display-6 fw-bold text-dark">{{ number_format($totalNfcTaps) }}</div>
                <div class="small text-muted mt-2">
                    <span>{{ $activeNfcTags }} puces physiques actives</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 14-Days Interactive Traffic Comparison Chart -->
    <div class="card stat-section-card p-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
            <div>
                <h6 class="fw-bold mb-0">Activité des 14 Derniers Jours : Scans QR vs Détections NFC</h6>
                <small class="text-muted">Évolution quotidienne du trafic physique capté sur le terrain</small>
            </div>
            <div class="d-flex gap-2">
                <span class="stat-pill bg-primary-subtle text-primary border"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Scans QR</span>
                <span class="stat-pill bg-info-subtle text-info border"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Taps NFC</span>
            </div>
        </div>
        <div style="height: 280px; position: relative;">
            <canvas id="trafficTimelineChart"></canvas>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. CLIENTS SECTION (STATISTIQUES & DERNIERS CLIENTS)                      --}}
{{-- ========================================================================= --}}
<div class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-dark text-white p-2 rounded-3"><i class="bi bi-buildings"></i></span>
            <h5 class="fw-bold mb-0">2. Section Clients & Projets</h5>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-outline-secondary">
            Gérer les Clients <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Total Clients Enregistrés</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ $totalClients }}</div>
                <small class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>{{ $activeClients }} Actifs &bull; {{ $inactiveClients }} Inactifs</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Villes Couvertes</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ $distinctCitiesCount }}</div>
                <small class="text-muted">Casablanca, Rabat, Marrakech...</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Inventaire Global Alloué</span>
                <div class="fs-3 fw-bold text-primary mt-1">{{ $totalQrs + $totalDigitalCards + $totalNfcTags }}</div>
                <small class="text-muted">QRs, Cartes de visite et Puces NFC</small>
            </div>
        </div>
    </div>

    <!-- Recent Clients Table -->
    <div class="card stat-section-card overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Derniers Clients & Projets Enregistrés</h6>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-sm btn-primary rounded-pill">
                <i class="bi bi-person-plus-fill me-1"></i> Nouveau Client
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Client & Nom du Projet</th>
                        <th>Ville</th>
                        <th>Téléphone</th>
                        <th class="text-center">Inventaire Connecté</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentClients as $c)
                        <tr>
                            <td class="ps-3 py-2">
                                <div class="fw-bold text-dark">{{ $c->contact_name }}</div>
                                @if($c->project_name)
                                    <div class="text-primary font-monospace" style="font-size: 0.75rem;">
                                        <i class="bi bi-folder2 me-1"></i>{{ $c->project_name }}
                                    </div>
                                @endif
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $c->company_name }}</div>
                            </td>
                            <td>{{ $c->city ?: 'Non spécifiée' }}</td>
                            <td class="font-monospace">{{ $c->phone ?: 'Non renseigné' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary">{{ $c->qr_codes_count }} QR</span>
                                <span class="badge bg-purple-subtle text-purple" style="background-color: #ede9fe; color: #6d28d9;">{{ $c->digital_cards_count }} Cartes</span>
                                <span class="badge bg-info-subtle text-info">{{ $c->nfc_tags_count }} NFC</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $c->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $c->status }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.clients.show', $c) }}" class="btn btn-sm btn-outline-primary" title="Voir l'historique complet">
                                    <i class="bi bi-clock-history me-1"></i> Historique
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Aucun client enregistré pour l'instant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 3. CARTE VISITE DIGITALE SECTION (STATISTIQUES & TOP CARTES)              --}}
{{-- ========================================================================= --}}
<div class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge text-white p-2 rounded-3" style="background-color: #7c3aed;"><i class="bi bi-person-vcard"></i></span>
            <h5 class="fw-bold mb-0">3. Section Cartes Visite Digitales</h5>
        </div>
        <a href="{{ route('admin.digital-cards.index') }}" class="btn btn-sm btn-outline-secondary">
            Gérer les Cartes Visite <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Total Cartes Visite</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ $totalDigitalCards }}</div>
                <small class="text-success"><i class="bi bi-broadcast me-1"></i>{{ $activeDigitalCards }} Cartes en ligne</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Total Vues de Profils</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalCardViews) }}</div>
                <small class="text-muted">Pages publiques consultées</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Templates Utilisés</span>
                <div class="d-flex flex-wrap gap-1 mt-2">
                    @foreach($cardsByTemplate as $tmpl => $cnt)
                        <span class="badge bg-light text-dark border text-capitalize" style="font-size: 0.72rem;">
                            {{ $tmpl }}: <strong>{{ $cnt }}</strong>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top Viewed Digital Cards -->
    <div class="card stat-section-card overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Top Cartes Visite Digitales les Plus Consultées</h6>
            <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-primary rounded-pill">
                <i class="bi bi-plus-lg me-1"></i> Nouvelle Carte Visite
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Titulaire</th>
                        <th>Client Associé</th>
                        <th>Lien Unique (/c/slug)</th>
                        <th>Template</th>
                        <th class="text-center">Total Vues</th>
                        <th class="text-end pe-3">Aperçu & Gestion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topViewedCards as $card)
                        <tr>
                            <td class="ps-3 py-2">
                                <div class="fw-bold text-dark">{{ $card->full_name ?: $card->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $card->job_title }} &bull; {{ $card->company }}</div>
                            </td>
                            <td>{{ $card->client ? $card->client->company_name : 'Sans client' }}</td>
                            <td>
                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="badge bg-dark-subtle text-dark border font-monospace text-decoration-none">
                                    {{ $card->slug }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </td>
                            <td><span class="badge bg-light text-dark border text-capitalize">{{ $card->template }}</span></td>
                            <td class="text-center fw-bold fs-6">{{ number_format($card->views_count) }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="btn btn-sm btn-outline-info me-1" title="Voir la carte publique">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.digital-cards.edit', $card) }}" class="btn btn-sm btn-outline-primary" title="Personnaliser">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Aucune carte visite créée pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 4. NFC TAGS SECTION (STATISTIQUES & DERNIERS TAPS)                        --}}
{{-- ========================================================================= --}}
<div class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info text-white p-2 rounded-3"><i class="bi bi-broadcast"></i></span>
            <h5 class="fw-bold mb-0">4. Section Tags & Cartes NFC Sans-Contact</h5>
        </div>
        <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-sm btn-outline-secondary">
            Gérer les Tags NFC <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Total Tags NFC Déployés</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ $totalNfcTags }}</div>
                <small class="text-success"><i class="bi bi-broadcast-pin me-1"></i>{{ $activeNfcTags }} Tags NFC actifs</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Détections au Tap Détectées</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalNfcTaps) }}</div>
                <small class="text-muted">Interactions physiques sans contact</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Types de Cibles</span>
                <div class="d-flex flex-wrap gap-1 mt-2">
                    @foreach($nfcByTargetType as $type => $cnt)
                        <span class="badge bg-light text-dark border text-capitalize" style="font-size: 0.72rem;">
                            {{ str_replace('_', ' ', $type) }}: <strong>{{ $cnt }}</strong>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Live NFC Taps Log -->
    <div class="card stat-section-card overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Derniers Taps NFC Détectés en Direct</h6>
            <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-sm btn-primary rounded-pill">
                <i class="bi bi-broadcast me-1"></i> Créer un Tag NFC
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tag NFC</th>
                        <th>UID Puce</th>
                        <th>Appareil Détecteur</th>
                        <th>OS & Navigateur</th>
                        <th>Heure du Tap</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentNfcTaps as $tap)
                        <tr>
                            <td class="ps-3 py-2">
                                <div class="fw-bold text-dark">{{ $tap->nfcTag?->name ?? 'Tag inconnu' }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $tap->nfcTag?->client?->company_name ?? 'Usage interne' }}</div>
                            </td>
                            <td><span class="badge bg-dark-subtle text-dark border font-monospace">{{ $tap->nfcTag?->tag_uid }}</span></td>
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">
                                    <i class="bi bi-{{ $tap->device_type === 'mobile' ? 'phone' : 'laptop' }} me-1"></i>
                                    {{ $tap->device_type ?: 'Mobile' }}
                                </span>
                            </td>
                            <td>{{ $tap->operating_system ?: 'OS Inconnu' }} &bull; {{ $tap->browser }}</td>
                            <td>{{ $tap->tapped_at?->diffForHumans() }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.nfc-tags.show', $tap->nfc_tag_id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-graph-up"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Aucun tap NFC enregistré pour le moment. Approchez un smartphone pour tester !</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 5. DYNAMIC QR CODES VP SECTION (STATISTIQUES & TOP CODES)                 --}}
{{-- ========================================================================= --}}
<div class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success text-white p-2 rounded-3"><i class="bi bi-qr-code"></i></span>
            <h5 class="fw-bold mb-0">5. Section Dynamic QR Codes (Suivi VP)</h5>
        </div>
        <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-sm btn-outline-secondary">
            Gérer les QR Codes <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Total Dynamic QR Codes</span>
                <div class="fs-3 fw-bold text-dark mt-1">{{ $totalQrs }}</div>
                <small class="text-success"><i class="bi bi-check-circle me-1"></i>{{ $activeQrs }} Actifs &bull; {{ $inactiveQrs }} Inactifs</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Préfixe de Suivi Unique</span>
                <div class="fs-4 fw-bold text-primary font-monospace mt-1">VP-XXXXXXXX</div>
                <small class="text-muted">Redirection modifiable sans réimpression</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card stat-section-card p-3">
                <span class="text-muted small fw-semibold">Templates de Cartes Imprimables</span>
                <div class="d-flex flex-wrap gap-1 mt-2">
                    <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.72rem;">Google Review</span>
                    <span class="badge bg-danger-subtle text-danger border" style="font-size: 0.72rem;">Social Media</span>
                    <span class="badge bg-warning-subtle text-warning border" style="font-size: 0.72rem;">Menu Restaurant</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Scanned QR Codes -->
    <div class="card stat-section-card overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Top QR Codes Dynamiques les Plus Scannés</h6>
            <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-sm btn-primary rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Générer un QR Code
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nom du QR</th>
                        <th>Code Unique VP</th>
                        <th>Client</th>
                        <th>Destination Actuelle</th>
                        <th class="text-center">Total Scans</th>
                        <th class="text-end pe-3">Actions & PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topScannedQrs as $qr)
                        <tr>
                            <td class="ps-3 py-2">
                                <div class="fw-bold text-dark">{{ $qr->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Format: {{ $qr->type }}</div>
                            </td>
                            <td>
                                <span class="badge bg-dark text-white font-monospace px-2 py-1">{{ $qr->public_code }}</span>
                            </td>
                            <td>{{ $qr->client ? $qr->client->company_name : 'Non assigné' }}</td>
                            <td>
                                @php
                                    $dest = $qr->target_redirect_url ?: ($qr->content?->content_data['url'] ?? null);
                                @endphp
                                @if($dest)
                                    <div class="text-truncate font-monospace" style="max-width: 200px;" title="{{ $dest }}">
                                        {{ $dest }}
                                    </div>
                                @else
                                    <span class="text-muted">Par défaut</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold fs-6">{{ number_format($qr->scan_count) }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.qr-codes.card-pdf', [$qr, 'template' => $qr->card_template ?: 'google_review']) }}" class="btn btn-sm btn-outline-danger me-1" title="Télécharger Carte PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                                <a href="{{ route('admin.qr-codes.show', $qr) }}" class="btn btn-sm btn-outline-primary" title="Modifier la destination">
                                    <i class="bi bi-sliders"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Aucun QR code scanné pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 6. USERS & SECURITY SECTION (STATISTIQUES & AUDIT LOGS)                   --}}
{{-- ========================================================================= --}}
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark p-2 rounded-3"><i class="bi bi-shield-lock"></i></span>
            <h5 class="fw-bold mb-0">6. Section Utilisateurs Internes & Journal d'Audit</h5>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
            Gérer les Utilisateurs <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        <!-- Left: Users metrics & quick list -->
        <div class="col-lg-5">
            <div class="card stat-section-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Équipe & Staff (Non Clients)</span>
                    <span class="badge bg-primary-subtle text-primary">{{ $totalStaff }} Utilisateurs</span>
                </div>
                <div class="d-flex gap-3 my-2">
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $activeStaff }}</div>
                        <small class="text-muted">Actifs</small>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalRoles }}</div>
                        <small class="text-muted">Rôles configurés</small>
                    </div>
                </div>
            </div>

            <div class="card stat-section-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Collaborateurs Récents</h6>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary rounded-pill">
                        <i class="bi bi-person-plus-fill me-1"></i> Nouveau
                    </a>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($recentUsers as $u)
                        <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    {{ substr($u->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold small text-dark">{{ $u->name }}</div>
                                    <div class="text-muted font-monospace" style="font-size: 0.68rem;">{{ $u->email }}</div>
                                </div>
                            </div>
                            <div>
                                @foreach($u->roles as $r)
                                    <span class="badge {{ $r->slug === 'admin' ? 'bg-primary' : 'bg-secondary' }}" style="font-size: 0.65rem;">{{ $r->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Recent Activity Trail -->
        <div class="col-lg-7">
            <div class="card stat-section-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Dernières Activités Système (Journal d'Audit)</h6>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-link text-decoration-none">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light" style="font-size: 0.72rem;">
                            <tr>
                                <th>Action</th>
                                <th>Auteur / Ressource</th>
                                <th>IP</th>
                                <th class="text-end">Heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $log)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark-subtle text-dark font-monospace">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $log->user?->name ?? ($log->client?->company_name ?? 'Système') }}</div>
                                        @if($log->resource_type)
                                            <small class="text-muted">{{ $log->resource_type }} #{{ $log->resource_id }}</small>
                                        @endif
                                    </td>
                                    <td class="text-muted font-monospace">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                                    <td class="text-end text-muted">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-3 text-muted">Aucune activité enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctxTraffic = document.getElementById('trafficTimelineChart');
    if (ctxTraffic) {
        new Chart(ctxTraffic, {
            type: 'line',
            data: {
                labels: @json($chartDates),
                datasets: [
                    {
                        label: 'Scans QR Dynamiques',
                        data: @json($qrScansChartData),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 4,
                    },
                    {
                        label: 'Détections NFC (Taps)',
                        data: @json($nfcTapsChartData),
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#06b6d4',
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>
@endpush
