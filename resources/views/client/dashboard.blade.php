@extends('layouts.client')

@section('title', 'Tableau de Bord Client')
@section('page_title', 'Espace Client — Vue d\'ensemble')
@section('page_subtitle', 'Consultez et gérez vos produits achetés (Cartes NFC, Menus interactifs & QR Codes)')

@section('content')
<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <!-- Mes Produits Déployés -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-white p-3 rounded-4 border shadow-2xs">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Mes Produits Achetés</span>
                <div class="metric-icon-box bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
            </div>
            <div class="display-6 fw-extrabold text-dark">{{ $totalProducts }}</div>
            <div class="small text-muted mt-2 d-flex justify-content-between align-items-center">
                <span>{{ $digitalCards->count() }} Cartes &bull; {{ $dynamicQrs->count() }} QR &bull; {{ $nfcTags->count() }} NFC &bull; {{ $totalLoyaltyCards }} Fidélité</span>
                <a href="{{ route('client.products.index') }}" class="text-primary text-decoration-none fw-bold">Gérer <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Total Interactions (Scans & Taps) -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-white p-3 rounded-4 border shadow-2xs">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Interactions Clients</span>
                <div class="metric-icon-box bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-broadcast fs-5"></i>
                </div>
            </div>
            <div class="display-6 fw-extrabold text-dark">{{ number_format($totalScans) }}</div>
            <div class="small text-muted mt-2">
                <span class="badge bg-success-subtle text-success">+{{ number_format($scansToday) }} aujourd'hui</span>
                <span class="ms-1">{{ number_format($scansThisMonth) }} ce mois</span>
            </div>
        </div>
    </div>

    <!-- Vues Profils & Menus -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-white p-3 rounded-4 border shadow-2xs">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Vues Profils &amp; Menus</span>
                <div class="metric-icon-box text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #5736e9;">
                    <i class="bi bi-person-badge fs-5"></i>
                </div>
            </div>
            <div class="display-6 fw-extrabold text-dark">{{ number_format($totalCardViews) }}</div>
            <div class="small text-muted mt-2 d-flex justify-content-between">
                <span>{{ $digitalCards->count() }} profil(s) en ligne</span>
                <a href="{{ route('client.digital-cards.index') }}" class="text-primary text-decoration-none fw-bold">Détails <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Dernier Scan / Signal -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-white p-3 rounded-4 border shadow-2xs">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Dernière Activité</span>
                <div class="metric-icon-box bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
            <div class="fs-5 fw-extrabold text-dark mt-1 text-truncate">
                {{ $lastScan ? $lastScan->scanned_at->diffForHumans() : 'En attente de scans' }}
            </div>
            <div class="small text-muted mt-2">
                {{ $lastScan ? ($lastScan->country ?: 'Visiteur mobile direct') : 'Scannez votre QR code physique' }}
            </div>
        </div>
    </div>
</div>

<!-- Quick Products Hub Banner -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border-left: 5px solid #5736e9 !important;">
    <div class="row align-items-center g-3">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill text-white font-mono" style="background: #5736e9;">ESPACE CLIENT SÉCURISÉ</span>
                <span class="text-muted small">&bull; Vos produits sous licence active</span>
            </div>
            <h4 class="fw-extrabold text-dark mb-1">Tous Vos Produits Achetés en Un Seul Endroit</h4>
            <p class="text-muted small mb-0">
                Vous avez un contrôle total : modifiez vos cibles de redirection, vos menus restaurant et vos cartes de visite NFC sans jamais réimprimer vos supports.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('client.products.index') }}" class="btn btn-primary-custom px-4 py-2.5 rounded-pill fw-bold shadow-xs d-inline-flex align-items-center gap-2">
                <i class="bi bi-box-seam fs-6"></i> Accéder au Hub Produits
            </a>
        </div>
    </div>
</div>

<!-- Charts & Quick Access Row -->
<div class="row g-4 mb-4">
    <!-- Scans Timeline -->
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white h-100 rounded-4 border-0 shadow-2xs">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Activité des Scans &amp; Interactions</h5>
                    <small class="text-muted">Volume d'interactions des 14 derniers jours</small>
                </div>
                <span class="badge bg-primary-subtle text-primary font-mono">TEMPS RÉEL</span>
            </div>
            <div style="height: 260px; position: relative;">
                <canvas id="clientTimelineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Card -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white h-100 rounded-4 border-0 shadow-2xs d-flex flex-column justify-content-between">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Raccourcis de Gestion</h5>
                <small class="text-muted mb-3 d-block">Accès direct par famille de produit</small>

                <div class="d-flex flex-column gap-2 mb-3">
                    <a href="{{ route('client.digital-cards.index') }}" class="p-2.5 rounded-3 border bg-light text-decoration-none d-flex align-items-center justify-content-between hover-bg">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: #5736e9;">
                                <i class="bi bi-person-badge small"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Cartes Visite &amp; Menus</span>
                        </div>
                        <span class="badge bg-white text-dark border font-mono">{{ $digitalCards->count() }}</span>
                    </a>

                    <a href="{{ route('client.dynamic-qrs.index') }}" class="p-2.5 rounded-3 border bg-light text-decoration-none d-flex align-items-center justify-content-between hover-bg">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white bg-info" style="width: 28px; height: 28px;">
                                <i class="bi bi-lightning-charge small"></i>
                            </span>
                            <span class="fw-semibold text-dark small">QR Codes Dynamiques</span>
                        </div>
                        <span class="badge bg-white text-dark border font-mono">{{ $dynamicQrs->count() }}</span>
                    </a>

                    <a href="{{ route('client.nfc-tags.index') }}" class="p-2.5 rounded-3 border bg-light text-decoration-none d-flex align-items-center justify-content-between hover-bg">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white bg-success" style="width: 28px; height: 28px;">
                                <i class="bi bi-broadcast small"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Cartes &amp; Puces NFC</span>
                        </div>
                        <span class="badge bg-white text-dark border font-mono">{{ $nfcTags->count() }}</span>
                    </a>

                    <a href="{{ route('client.loyalty.index') }}" class="p-2.5 rounded-3 border bg-light text-decoration-none d-flex align-items-center justify-content-between hover-bg">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: #8b5cf6;">
                                <i class="bi bi-award small"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Cartes Fidélité (VIP)</span>
                        </div>
                        <span class="badge bg-white text-dark border font-mono">{{ $totalLoyaltyCards }} / {{ $loyaltyLimit }}</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('client.products.index') }}" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-semibold small">
                <i class="bi bi-grid me-1"></i> Voir l'Inventaire Complet
            </a>
        </div>
    </div>
</div>

<!-- Assigned Standard QR Codes List Preview (if any) -->
@if(!empty($topQrsWithSvg))
    <div class="card card-custom p-4 bg-white rounded-4 border-0 shadow-2xs mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-dark">Vos QR Codes Standards Imprimés</h5>
            <a href="{{ route('client.qr-codes.index') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold">Voir Tous les QR</a>
        </div>

        <div class="row g-3">
            @foreach($topQrsWithSvg as $entry)
                @php $qr = $entry['qr']; @endphp
                <div class="col-md-6 col-lg-3">
                    <div class="card p-3 border text-center h-100 rounded-3 bg-white shadow-2xs">
                        <div class="p-2 bg-light rounded-2 d-inline-block mx-auto mb-2 border">
                            {!! $entry['svg'] !!}
                        </div>

                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $qr->name }}</h6>
                        <span class="badge bg-light text-dark font-mono mb-2 d-inline-block">{{ $qr->public_code }}</span>

                        <div class="small text-muted mb-3">
                            <span class="fw-bold text-primary">{{ number_format($qr->scan_count) }}</span> scans
                        </div>

                        <div class="d-flex gap-1 justify-content-center mt-auto pt-2 border-top">
                            <a href="{{ route('client.qr-codes.customize', $qr) }}" class="btn btn-primary btn-xs rounded-pill px-3">
                                <i class="bi bi-pencil me-1"></i> Personnaliser
                            </a>
                            <a href="{{ url('/q/' . $qr->public_code) }}" target="_blank" class="btn btn-outline-secondary btn-xs rounded-pill" title="Tester le lien">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('clientTimelineChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($summary['chart_daily']['labels']),
                datasets: [{
                    label: 'Scans',
                    data: @json($summary['chart_daily']['data']),
                    borderColor: '#5736e9',
                    backgroundColor: 'rgba(87, 54, 233, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#5736e9',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
