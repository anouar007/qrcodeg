<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Portal') — QR Studio Client</title>

    <!-- PWA Web App Manifest & Mobile Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#5736e9">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="POS Terminal">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Chart.js -->
    <script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Client Sidebar -->
    <aside class="sidebar" id="clientSidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <a href="{{ route('client.dashboard') }}" class="d-flex align-items-center gap-2.5 text-decoration-none">
                <div class="sidebar-brand-mark" style="background: #5736e9;">
                    <i class="bi bi-qr-code text-white fs-5"></i>
                </div>
                <div class="sidebar-brand-text">
                    <span class="fw-bold fs-5 text-dark font-heading lh-1">QR Studio</span>
                    <span class="badge rounded-pill text-white mt-1 d-inline-block" style="background: #5736e9; font-size: 0.62rem; padding: 2px 7px;">ESPACE CLIENT</span>
                </div>
            </a>
            <button class="btn btn-sm btn-link text-muted d-lg-none p-1" id="sidebarCloseBtn" aria-label="Close sidebar">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            <div class="sidebar-heading">Navigation</div>
            <a href="{{ route('client.dashboard') }}" class="sidebar-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" title="Tableau de bord">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de Bord</span>
            </a>
            <a href="{{ route('client.products.index') }}" class="sidebar-link {{ request()->routeIs('client.products.*') ? 'active' : '' }}" title="Mes Produits">
                <i class="bi bi-box-seam"></i>
                <span>Mes Produits</span>
                <span class="badge rounded-pill text-white ms-auto" style="background: #5736e9; font-size: 0.65rem;">
                    {{ (auth('client')->user()->digitalCards()->count() + auth('client')->user()->dynamicQrCodes()->count() + auth('client')->user()->nfcTags()->count() + auth('client')->user()->qrCodes()->count() + auth('client')->user()->loyaltyCards()->count() + auth('client')->user()->restaurantMenus()->count()) }}
                </span>
            </a>

            <div class="sidebar-heading mt-3">Produits Assignés</div>
            <a href="{{ route('client.menus.index') }}" class="sidebar-link {{ request()->routeIs('client.menus.*') ? 'active' : '' }}" title="Mes Menus Digitaux">
                <i class="bi bi-journal-richtext"></i>
                <span>Menus Restaurants</span>
                <span class="badge rounded-pill bg-light text-secondary ms-auto small" style="font-size: 0.65rem;">
                    {{ auth('client')->user()->restaurantMenus()->count() }}
                </span>
            </a>
            <a href="{{ route('client.digital-cards.index') }}" class="sidebar-link {{ request()->routeIs('client.digital-cards.*') ? 'active' : '' }}" title="Cartes Visite & Menus">
                <i class="bi bi-person-badge"></i>
                <span>Cartes Visite &amp; Menus</span>
                <span class="badge rounded-pill bg-light text-secondary ms-auto small" style="font-size: 0.65rem;">
                    {{ auth('client')->user()->digitalCards()->count() }}
                </span>
            </a>
            <a href="{{ route('client.dynamic-qrs.index') }}" class="sidebar-link {{ request()->routeIs('client.dynamic-qrs.*') ? 'active' : '' }}" title="QR Dynamiques">
                <i class="bi bi-lightning-charge"></i>
                <span>QR Dynamiques</span>
                <span class="badge rounded-pill bg-light text-secondary ms-auto small" style="font-size: 0.65rem;">
                    {{ auth('client')->user()->dynamicQrCodes()->count() }}
                </span>
            </a>
            <a href="{{ route('client.nfc-tags.index') }}" class="sidebar-link {{ request()->routeIs('client.nfc-tags.*') ? 'active' : '' }}" title="Cartes & Tags NFC">
                <i class="bi bi-broadcast"></i>
                <span>Cartes &amp; Tags NFC</span>
                <span class="badge rounded-pill bg-light text-secondary ms-auto small" style="font-size: 0.65rem;">
                    {{ auth('client')->user()->nfcTags()->count() }}
                </span>
            </a>
            <a href="{{ route('client.qr-codes.index') }}" class="sidebar-link {{ request()->routeIs('client.qr-codes.*') ? 'active' : '' }}" title="QR Codes Standards">
                <i class="bi bi-qr-code"></i>
                <span>QR Codes Standards</span>
                <span class="badge rounded-pill bg-light text-secondary ms-auto small" style="font-size: 0.65rem;">
                    {{ auth('client')->user()->qrCodes()->count() }}
                </span>
            </a>

            <div class="sidebar-heading mt-3">Fidélisation Client</div>
            <a href="{{ route('client.loyalty.terminal') }}" class="sidebar-link {{ request()->routeIs('client.loyalty.terminal') ? 'active' : '' }}" title="Terminal POS Scanner">
                <i class="bi bi-upc-scan text-warning"></i>
                <span class="fw-semibold">Terminal POS Scanner</span>
                <span class="badge rounded-pill text-white ms-auto" style="background: #5736e9; font-size: 0.65rem;">
                    LIVE
                </span>
            </a>
            <a href="{{ route('client.loyalty.index') }}" class="sidebar-link {{ request()->routeIs('client.loyalty.index', 'client.loyalty.show', 'client.loyalty.create') ? 'active' : '' }}" title="Cartes Membres">
                <i class="bi bi-award"></i>
                <span>Cartes Membres</span>
                <span class="badge rounded-pill text-white ms-auto" style="background: #10b981; font-size: 0.65rem;">
                    {{ auth('client')->user()->loyaltyCards()->count() }} / {{ auth('client')->user()->loyalty_card_limit ?? 20 }}
                </span>
            </a>
            <a href="{{ route('client.loyalty.scan') }}" class="sidebar-link {{ request()->routeIs('client.loyalty.scan') ? 'active' : '' }}" title="Scanner Rapide">
                <i class="bi bi-qr-code-scan"></i>
                <span>Scanner Rapide</span>
            </a>
            <a href="{{ route('client.loyalty.settings') }}" class="sidebar-link {{ request()->routeIs('client.loyalty.settings') ? 'active' : '' }}" title="Règles & Barème Points">
                <i class="bi bi-sliders"></i>
                <span>Règles &amp; Barème Points</span>
            </a>

            <div class="sidebar-heading mt-3">Télémétrie</div>
            <a href="{{ route('client.analytics') }}" class="sidebar-link {{ request()->routeIs('client.analytics') ? 'active' : '' }}" title="Statistiques & Scans">
                <i class="bi bi-graph-up"></i>
                <span>Statistiques &amp; Scans</span>
            </a>

            <div class="sidebar-heading mt-3">Raccourcis</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link" title="Voir le site public">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Site public</span>
            </a>

            <div class="sidebar-heading mt-3">Identifiant Produit</div>
            <div class="px-3 py-2.5 mx-2 rounded-3 border" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <small class="text-muted fw-bold" style="font-size: 0.68rem; letter-spacing: 0.05em;">CLÉ D'ACCÈS ACTIVE</small>
                    <i class="bi bi-shield-check text-success" style="font-size: 0.85rem;"></i>
                </div>
                <span class="text-dark font-mono fw-bold small">{{ auth('client')->user()->access_code }}</span>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 36px; height: 36px; background: #5736e9; font-size: 0.95rem;">
                        {{ strtoupper(substr(auth('client')->user()->company_name ?? 'C', 0, 1)) }}
                    </div>
                    <div style="line-height: 1.2;" class="overflow-hidden">
                        <span class="text-dark small fw-semibold d-block text-truncate">
                            {{ auth('client')->user()->company_name ?? 'Client Account' }}
                        </span>
                        <span class="text-muted" style="font-size: 0.72rem;">
                            {{ auth('client')->user()->contact_name }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('client.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1.5" title="Déconnexion">
                        <i class="bi bi-box-arrow-right fs-6"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Sticky Header -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary btn-sm d-lg-none" id="sidebarToggleBtn">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold">@yield('page_title', 'Client Dashboard')</h5>
                    <small class="text-muted">@yield('page_subtitle', 'Manage your dynamic QR content and real-time scans')</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('client.products.index') }}" class="btn btn-primary-custom btn-sm rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs">
                    <i class="bi bi-box-seam"></i>
                    <span>Mes Produits</span>
                </a>
            </div>
        </header>

        <!-- Alerts -->
        @if(session('success') || session('error') || session('warning') || session('info'))
            <div class="px-4 pt-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-0" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-0" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm mb-0" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm mb-0" role="alert">
                        <i class="bi bi-bell-fill me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Body Content -->
        <main class="content-body">
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/nfc-encoder.js') }}"></script>

<script>
    // Register PWA Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration error:', err));
        });
    }

    // Responsive sidebar toggle script
    const sidebar = document.getElementById('clientSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            if (sidebar.style.display === 'flex') {
                sidebar.style.display = 'none';
            } else {
                sidebar.style.display = 'flex';
                sidebar.style.position = 'fixed';
                sidebar.style.height = '100vh';
            }
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            sidebar.style.display = 'none';
        });
    }
</script>
@stack('scripts')
</body>
</html>
