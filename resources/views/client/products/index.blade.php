@extends('layouts.client')

@section('title', 'Mes Produits Achetés')
@section('page_title', 'Gestion de Mes Produits')
@section('page_subtitle', 'Consultez et configurez vos cartes digitales, puces NFC et QR codes en temps réel')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <!-- Client Header Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="card-body p-4 text-white">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-extrabold shadow-sm flex-shrink-0" style="width: 54px; height: 54px; background: linear-gradient(135deg, #5736e9 0%, #7e63f5 100%); font-size: 1.35rem;">
                            {{ substr($client->company_name ?: $client->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="fw-extrabold mb-0 text-white">{{ $client->company_name ?: $client->name }}</h4>
                                <span class="badge bg-success-subtle text-success font-mono rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">● Compte Actif</span>
                            </div>
                            <div class="text-white-50 small mt-1">
                                <span><i class="bi bi-person me-1"></i>{{ $client->contact_name ?: $client->name }}</span>
                                @if($client->email)
                                    <span class="ms-3"><i class="bi bi-envelope me-1"></i>{{ $client->email }}</span>
                                @endif
                                @if($client->project_name)
                                    <span class="ms-3"><i class="bi bi-folder2 me-1"></i>Projet : {{ $client->project_name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block text-start p-2.5 px-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                        <small class="text-white-50 d-block font-mono text-uppercase" style="font-size: 0.65rem;">Clé d'Accès Produit</small>
                        <span class="font-mono text-white fw-bold fs-6">{{ $client->access_code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 KPI Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-2xs rounded-3 p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Produits Déployés</span>
                        <h3 class="fw-extrabold mb-0 mt-1 text-dark">{{ $totalProducts }}</h3>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px; background: #faf8ff;">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">
                    <span class="text-success fw-bold">{{ $activeProducts }}</span> actifs en ligne
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-2xs rounded-3 p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Cartes &amp; Menus</span>
                        <h3 class="fw-extrabold mb-0 mt-1" style="color: #5736e9;">{{ $digitalCards->count() }}</h3>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ece8fc; color: #5736e9;">
                        <i class="bi bi-person-badge fs-5"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">
                    <span class="fw-bold text-dark">{{ number_format($totalCardViews) }}</span> vues de profils
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-2xs rounded-3 p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">QR Dynamiques (VP)</span>
                        <h3 class="fw-extrabold mb-0 mt-1 text-dark">{{ $dynamicQrs->count() }}</h3>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-info" style="width: 40px; height: 40px; background: #ecfeff;">
                        <i class="bi bi-lightning-charge fs-5"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">
                    <span class="fw-bold text-dark">{{ number_format($dynamicQrs->sum('scans_count')) }}</span> scans dynamiques
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-2xs rounded-3 p-3 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Cartes &amp; Puces NFC</span>
                        <h3 class="fw-extrabold mb-0 mt-1 text-dark">{{ $nfcTags->count() }}</h3>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success" style="width: 40px; height: 40px; background: #f0fdf4;">
                        <i class="bi bi-broadcast fs-5"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">
                    <span class="fw-bold text-dark">{{ number_format($nfcTags->sum('scans_count') + $nfcTags->sum('tap_count')) }}</span> contacts sans contact
                </div>
            </div>
        </div>
    </div>

    <!-- Product Category Tabs -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom p-3">
            <ul class="nav nav-pills gap-1 flex-wrap" id="productsFilterTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold small active" id="tab-all-btn" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab">
                        Tous les Produits ({{ $totalProducts }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold small" id="tab-cards-btn" data-bs-toggle="pill" data-bs-target="#tab-cards" type="button" role="tab">
                        <i class="bi bi-person-badge me-1"></i>Cartes &amp; Menus ({{ $digitalCards->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold small" id="tab-dynamic-btn" data-bs-toggle="pill" data-bs-target="#tab-dynamic" type="button" role="tab">
                        <i class="bi bi-lightning-charge me-1"></i>QR Dynamiques ({{ $dynamicQrs->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold small" id="tab-nfc-btn" data-bs-toggle="pill" data-bs-target="#tab-nfc" type="button" role="tab">
                        <i class="bi bi-broadcast me-1"></i>Puces &amp; Tags NFC ({{ $nfcTags->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold small" id="tab-qr-btn" data-bs-toggle="pill" data-bs-target="#tab-qr" type="button" role="tab">
                        <i class="bi bi-qr-code me-1"></i>QR Standards ({{ $qrCodes->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold small" id="tab-loyalty-btn" data-bs-toggle="pill" data-bs-target="#tab-loyalty" type="button" role="tab">
                        <i class="bi bi-award me-1"></i>Cartes Fidélité ({{ $loyaltyCards->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-3 p-md-4">
            <div class="tab-content" id="productsFilterTabsContent">

                <!-- 1. ALL PRODUCTS TAB -->
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    @if($totalProducts === 0)
                        <div class="text-center py-5">
                            <i class="bi bi-box2 fs-1 text-muted d-block mb-3"></i>
                            <h5 class="fw-bold text-dark">Aucun produit assigné pour le moment</h5>
                            <p class="text-muted small">Vos cartes NFC, QR codes dynamiques ou menus digitaux apparaîtront ici dès leur activation par notre équipe.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            <!-- Digital Cards -->
                            @foreach($digitalCards as $card)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card h-100 border rounded-3 p-3 shadow-2xs hover-shadow position-relative" style="border-left: 4px solid #5736e9 !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge rounded-pill text-white font-mono" style="background: #5736e9; font-size: 0.68rem;">
                                                <i class="bi bi-person-badge me-1"></i>Carte Visite &bull; Menu
                                            </span>
                                            <span class="badge {{ $card->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                                {{ ucfirst($card->status) }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $card->title ?: $card->full_name }}</h6>
                                        <p class="text-muted small mb-3 text-truncate">{{ $card->job_title ?: 'Profil connecté sans contact' }} &bull; {{ $card->company ?: $client->name }}</p>
                                        
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto small">
                                            <span class="text-muted"><i class="bi bi-eye me-1"></i>{{ number_format($card->views_count) }} vues</span>
                                            <div class="d-flex gap-1.5">
                                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="btn btn-xs btn-outline-secondary rounded-pill" title="Aperçu public">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                                <a href="{{ route('client.digital-cards.edit', $card) }}" class="btn btn-xs btn-primary-custom rounded-pill">
                                                    <i class="bi bi-pencil-square me-1"></i>Modifier
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Dynamic QR Codes -->
                            @foreach($dynamicQrs as $dqr)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card h-100 border rounded-3 p-3 shadow-2xs hover-shadow position-relative" style="border-left: 4px solid #0891b2 !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge rounded-pill bg-info text-white font-mono" style="font-size: 0.68rem;">
                                                <i class="bi bi-lightning-charge me-1"></i>QR Dynamique (VP)
                                            </span>
                                            <span class="badge {{ $dqr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                                {{ ucfirst($dqr->status) }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <code class="fw-bold font-mono text-dark fs-6">{{ $dqr->code }}</code>
                                            <span class="text-muted small">&bull;</span>
                                            <span class="fw-semibold text-dark text-truncate small">{{ $dqr->label }}</span>
                                        </div>
                                        <div class="p-2 rounded-2 bg-light font-mono text-truncate small mb-3" style="font-size: 0.72rem;">
                                            <span class="text-muted">Cible :</span> <span class="text-primary">{{ $dqr->target_url }}</span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto small">
                                            <span class="text-muted"><i class="bi bi-qr-code-scan me-1"></i>{{ number_format($dqr->scans_count) }} scans</span>
                                            <div class="d-flex gap-1.5">
                                                <a href="{{ route('qr.redirect', ['code' => $dqr->code]) }}" target="_blank" class="btn btn-xs btn-outline-secondary rounded-pill" title="Tester redirection">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                                <a href="{{ route('client.dynamic-qrs.edit', $dqr) }}" class="btn btn-xs btn-primary-custom rounded-pill">
                                                    <i class="bi bi-sliders me-1"></i>Changer Cible
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- NFC Tags -->
                            @foreach($nfcTags as $nfc)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card h-100 border rounded-3 p-3 shadow-2xs hover-shadow position-relative" style="border-left: 4px solid #16a34a !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge rounded-pill bg-success text-white font-mono" style="font-size: 0.68rem;">
                                                <i class="bi bi-broadcast me-1"></i>Puce NFC &bull; {{ $nfc->tag_uid ?: 'NFC' }}
                                            </span>
                                            <span class="badge {{ $nfc->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                                {{ ucfirst($nfc->status) }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $nfc->label ?: $nfc->name }}</h6>
                                        <div class="p-2 rounded-2 bg-light font-mono text-truncate small mb-3" style="font-size: 0.72rem;">
                                            <span class="text-muted">Redirection :</span> <span class="text-success">{{ $nfc->destination_url ?: $nfc->target_url }}</span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto small">
                                            <span class="text-muted"><i class="bi bi-hand-index-thumb me-1"></i>{{ number_format($nfc->scans_count ?: $nfc->tap_count) }} touches</span>
                                            <a href="{{ route('client.nfc-tags.edit', $nfc) }}" class="btn btn-xs btn-primary-custom rounded-pill">
                                                <i class="bi bi-arrow-repeat me-1"></i>Changer Lien
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Standard QRs -->
                            @foreach($qrCodes as $qr)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card h-100 border rounded-3 p-3 shadow-2xs hover-shadow position-relative" style="border-left: 4px solid #475569 !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge rounded-pill bg-dark text-white font-mono" style="font-size: 0.68rem;">
                                                <i class="bi bi-qr-code me-1"></i>QR Standard &bull; {{ strtoupper($qr->type) }}
                                            </span>
                                            <span class="badge {{ $qr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                                {{ ucfirst($qr->status) }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $qr->name }}</h6>
                                        <div class="text-muted small mb-3 font-mono" style="font-size: 0.72rem;">Code : {{ $qr->public_code }}</div>
                                        
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto small">
                                            <span class="text-muted"><i class="bi bi-qr-code-scan me-1"></i>{{ number_format($qr->scan_count) }} scans</span>
                                            <div class="d-flex gap-1.5">
                                                <a href="{{ route('client.qr-codes.download', ['qrCode' => $qr, 'format' => 'png']) }}" class="btn btn-xs btn-outline-secondary rounded-pill" title="Télécharger PNG">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <a href="{{ route('client.qr-codes.customize', $qr) }}" class="btn btn-xs btn-primary-custom rounded-pill">
                                                    <i class="bi bi-sliders me-1"></i>Personnaliser
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 2. DIGITAL CARDS TAB -->
                <div class="tab-pane fade" id="tab-cards" role="tabpanel">
                    @if($digitalCards->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-person-badge fs-2 d-block mb-2"></i>
                            <p class="mb-0">Vous ne possédez aucune Carte Visite ou Menu Digital pour l'instant.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($digitalCards as $card)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border rounded-3 p-3 shadow-2xs h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge rounded-pill text-white" style="background: #5736e9;">{{ ucfirst($card->template ?: 'Modern') }}</span>
                                            <span class="badge {{ $card->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill">{{ ucfirst($card->status) }}</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">{{ $card->title ?: $card->full_name }}</h5>
                                        <div class="text-muted small mb-3">{{ $card->job_title }} &bull; {{ $card->company }}</div>
                                        <div class="d-flex gap-2 mt-auto pt-2 border-top">
                                            <a href="{{ route('client.digital-cards.edit', $card) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-fill">
                                                <i class="bi bi-pencil-square me-1"></i>Coordonnées
                                            </a>
                                            <a href="{{ route('client.digital-cards.menu', $card) }}" class="btn btn-sm btn-primary-custom rounded-pill flex-fill">
                                                <i class="fa-solid fa-utensils me-1"></i>Menu / Catalogue
                                            </a>
                                            <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 3. DYNAMIC QRS TAB -->
                <div class="tab-pane fade" id="tab-dynamic" role="tabpanel">
                    @if($dynamicQrs->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-lightning-charge fs-2 d-block mb-2"></i>
                            <p class="mb-0">Aucun QR code dynamique assigné pour l'instant.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light font-mono small">
                                    <tr>
                                        <th>CODE</th>
                                        <th>LIBELLÉ</th>
                                        <th>DESTINATION ACTIVE</th>
                                        <th>SCANS</th>
                                        <th>STATUT</th>
                                        <th class="text-end">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dynamicQrs as $dqr)
                                        <tr>
                                            <td><code class="fw-bold text-primary">{{ $dqr->code }}</code></td>
                                            <td class="fw-semibold text-dark">{{ $dqr->label }}</td>
                                            <td class="font-mono small text-truncate" style="max-width: 250px;">{{ $dqr->target_url }}</td>
                                            <td><span class="badge bg-light text-dark font-mono">{{ number_format($dqr->scans_count) }}</span></td>
                                            <td>
                                                <span class="badge {{ $dqr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono">
                                                    {{ ucfirst($dqr->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('client.dynamic-qrs.edit', $dqr) }}" class="btn btn-xs btn-primary-custom rounded-pill me-1">
                                                    <i class="bi bi-pencil me-1"></i>Modifier Cible
                                                </a>
                                                <a href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dqr, 'format' => 'png']) }}" class="btn btn-xs btn-outline-secondary rounded-pill" title="Télécharger PNG">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- 4. NFC TAGS TAB -->
                <div class="tab-pane fade" id="tab-nfc" role="tabpanel">
                    @if($nfcTags->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-broadcast fs-2 d-block mb-2"></i>
                            <p class="mb-0">Aucune puce ou carte NFC active.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($nfcTags as $nfc)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border rounded-3 p-3 shadow-2xs h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-success-subtle text-success font-mono">{{ $nfc->tag_uid ?: 'NFC-CHIP' }}</span>
                                            <span class="badge bg-light text-dark font-mono"><i class="bi bi-hand-index-thumb me-1"></i>{{ number_format($nfc->scans_count ?: $nfc->tap_count) }} touches</span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $nfc->label ?: $nfc->name }}</h6>
                                        <div class="p-2 rounded-2 bg-light font-mono small text-truncate mb-3" style="font-size: 0.72rem;">
                                            {{ $nfc->destination_url ?: $nfc->target_url }}
                                        </div>
                                        <div class="mt-auto pt-2 border-top text-end">
                                            <a href="{{ route('client.nfc-tags.edit', $nfc) }}" class="btn btn-sm btn-primary-custom rounded-pill">
                                                <i class="bi bi-arrow-repeat me-1"></i>Changer Destination
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 5. STANDARD QRS TAB -->
                <div class="tab-pane fade" id="tab-qr" role="tabpanel">
                    @if($qrCodes->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-qr-code fs-2 d-block mb-2"></i>
                            <p class="mb-0">Aucun QR code standard assigné.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($qrCodes as $qr)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border rounded-3 p-3 shadow-2xs h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-secondary-subtle text-secondary font-mono">{{ $qr->public_code }}</span>
                                            <span class="badge bg-light text-dark font-mono">{{ number_format($qr->scan_count) }} scans</span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $qr->name }}</h6>
                                        <p class="text-muted small mb-3">Type : {{ ucfirst($qr->type) }}</p>
                                        <div class="d-flex gap-2 mt-auto pt-2 border-top">
                                            <a href="{{ route('client.qr-codes.customize', $qr) }}" class="btn btn-sm btn-primary-custom rounded-pill flex-fill">
                                                <i class="bi bi-sliders me-1"></i>Personnaliser
                                            </a>
                                            <a href="{{ route('client.qr-codes.download', ['qrCode' => $qr, 'format' => 'png']) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 6. LOYALTY CARDS TAB -->
                <div class="tab-pane fade" id="tab-loyalty" role="tabpanel">
                    @if($loyaltyCards->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-award fs-2 d-block mb-2 text-purple"></i>
                            <p class="mb-2">Aucune carte de fidélité active.</p>
                            @if(auth('client')->user()->canGenerateLoyaltyCard())
                                <a href="{{ route('client.loyalty.create') }}" class="btn btn-sm text-white rounded-pill px-3" style="background: #5736e9;">
                                    <i class="bi bi-plus-lg me-1"></i>Émettre une Carte Fidélité
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($loyaltyCards as $card)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border rounded-3 p-3 shadow-2xs h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-purple-subtle text-purple font-mono">{{ $card->card_code }}</span>
                                            <span class="badge bg-light text-dark font-mono">{{ $card->stamps_count }} / {{ $card->max_stamps }} tampons</span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $card->customer_name }}</h6>
                                        <p class="text-muted small mb-2">Récompense : {{ $card->reward_text }}</p>
                                        <div class="progress rounded-pill mb-3" style="height: 6px;">
                                            <div class="progress-bar {{ $card->isRewardAvailable() ? 'bg-success' : '' }}" style="width: {{ $card->progressPercentage() }}%; {{ !$card->isRewardAvailable() ? 'background-color: #5736e9;' : '' }}"></div>
                                        </div>
                                        <div class="d-flex gap-2 mt-auto pt-2 border-top">
                                            <a href="{{ route('client.loyalty.show', $card) }}" class="btn btn-sm btn-primary-custom rounded-pill flex-fill">
                                                <i class="bi bi-award me-1"></i>Gérer &amp; Tamponner
                                            </a>
                                            <a href="{{ route('loyalty.public.show', ['card_code' => $card->card_code]) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

