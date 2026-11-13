<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — {{ config('app.name', 'QR Studio') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 Free (2,163 icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Chart.js -->
    <script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>

    @stack('styles')
</head>
<body>

<div class="dashboard-wrapper" id="dashboardWrapper">
    <!-- Backdrop for Mobile Sidebar -->
    <div class="sidebar-backdrop d-none" id="sidebarBackdrop"></div>

    <!-- Polished Dark-Slate Collapsible Sidebar -->
    <!-- Clean Crisp White Sidebar (YNS NFC Style) -->
    <aside class="sidebar" id="appSidebar">
        <!-- Sidebar Brand Header -->
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2.5 text-decoration-none">
                <div class="sidebar-brand-mark">
                    <i class="bi bi-credit-card-2-front text-white fs-5"></i>
                </div>
                <div class="sidebar-brand-text">
                    <span class="fw-bold fs-5 text-dark font-heading lh-1">YNS NFC</span>
                </div>
            </a>
            <button class="btn btn-sm btn-link text-muted d-lg-none p-1" id="mobileSidebarCloseBtn" aria-label="Close sidebar">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Items with All Sections -->
        <div class="sidebar-nav d-flex flex-column h-100">
            <div class="flex-grow-1">
                <div class="sidebar-heading">Vue d'ensemble</div>

                <!-- 1. Home / Tableau de bord -->
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Tableau de bord">
                    <i class="bi bi-grid"></i>
                    <span>Tableau de bord</span>
                </a>

                <div class="sidebar-heading mt-3">Produits &amp; Tags</div>

                <!-- 2. Carte visite digitale -->
                @haspermission('view_digital_cards')
                <a href="{{ route('admin.digital-cards.index') }}" class="sidebar-link {{ request()->routeIs('admin.digital-cards.*') ? 'active' : '' }}" title="Cartes visite digitales">
                    <i class="bi bi-person-badge"></i>
                    <span>Cartes visite digitales</span>
                </a>
                @endhaspermission

                <!-- Menus Digitaux (Restaurants & Marques) -->
                <a href="{{ route('admin.menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" title="Menus Digitaux (Restaurants, Fast-Food, Cafés)">
                    <i class="bi bi-journal-richtext"></i>
                    <span>Menus digitaux</span>
                </a>

                <!-- 3. NFC -->
                @haspermission('view_nfc_tags')
                <a href="{{ route('admin.nfc-tags.index') }}" class="sidebar-link {{ request()->routeIs('admin.nfc-tags.*') ? 'active' : '' }}" title="Cartes &amp; Puces NFC">
                    <i class="bi bi-credit-card"></i>
                    <span>Cartes &amp; Puces NFC</span>
                </a>

                <a href="{{ route('admin.nfc-encode.index') }}" class="sidebar-link {{ request()->routeIs('admin.nfc-encode.*') ? 'active' : '' }}" title="Encodeur NFC Intégré">
                    <i class="bi bi-broadcast"></i>
                    <span>Encodeur NFC</span>
                </a>
                @endhaspermission

                <!-- 4. Dynamic QR code & QR Suite -->
                @haspermission('view_qr')
                <a href="{{ route('admin.dynamic-qrs.index') }}" class="sidebar-link {{ request()->routeIs('admin.dynamic-qrs.*') ? 'active' : '' }}" title="QR codes dynamiques">
                    <i class="bi bi-lightning-charge"></i>
                    <span>QR codes dynamiques</span>
                </a>

                <a href="{{ route('admin.qr-codes.index') }}" class="sidebar-link {{ request()->routeIs('admin.qr-codes.*') ? 'active' : '' }}" title="QR codes standards">
                    <i class="bi bi-qr-code"></i>
                    <span>QR codes standards</span>
                </a>

                <a href="{{ route('admin.qr-documents.index') }}" class="sidebar-link {{ request()->routeIs('admin.qr-documents.*') ? 'active' : '' }}" title="Planches d'impression">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>Planches d'impression</span>
                </a>

                <a href="{{ route('admin.templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}" title="Modèles &amp; Gabarits">
                    <i class="bi bi-palette"></i>
                    <span>Modèles &amp; Gabarits</span>
                </a>
                @endhaspermission

                <div class="sidebar-heading mt-3">Clients</div>

                <!-- Clients -->
                @haspermission('view_clients')
                <a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" title="Clients &amp; Entreprises">
                    <i class="bi bi-people"></i>
                    <span>Clients</span>
                </a>
                @endhaspermission

                <div class="sidebar-heading mt-3">Fidélisation</div>

                <!-- Loyalty System Hub -->
                <a href="{{ route('admin.loyalty.index') }}" class="sidebar-link {{ request()->routeIs('admin.loyalty.*') ? 'active' : '' }}" title="Cartes &amp; Programme Fidélité">
                    <i class="bi bi-award"></i>
                    <span>Cartes de fidélité</span>
                </a>

                <div class="sidebar-heading mt-3">Télémétrie</div>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics.index') }}" class="sidebar-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" title="Statistiques &amp; Scans">
                    <i class="bi bi-graph-up"></i>
                    <span>Statistiques &amp; Scans</span>
                </a>

                <!-- Activity Logs -->
                <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" title="Journal d'audit">
                    <i class="bi bi-journal-text"></i>
                    <span>Journal d'audit</span>
                </a>

                <div class="sidebar-heading mt-3">Administration</div>

                <!-- Users -->
                @haspermission('view_users')
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" title="Utilisateurs &amp; Équipe">
                    <i class="bi bi-person-gear"></i>
                    <span>Utilisateurs</span>
                </a>
                @endhaspermission

                <!-- Roles & Permissions -->
                @haspermission('manage_roles')
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" title="Rôles &amp; Permissions">
                    <i class="bi bi-shield-lock"></i>
                    <span>Rôles &amp; Permissions</span>
                </a>
                @endhaspermission

                <!-- Settings -->
                @haspermission('manage_settings')
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" title="Paramètres système">
                    <i class="bi bi-gear"></i>
                    <span>Paramètres système</span>
                </a>
                @endhaspermission

                <div class="sidebar-heading mt-3">Raccourcis</div>

                <!-- Public Site -->
                <a href="{{ route('home') }}" target="_blank" class="sidebar-link" title="Voir le site public">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Site public</span>
                </a>
            </div>

            <!-- Bottom Déconnexion Matching Screenshot -->
            <div class="mt-auto pt-3 pb-2 border-top">
                <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
                    @csrf
                    <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start text-danger" title="Déconnexion" style="cursor: pointer;">
                        <i class="bi bi-box-arrow-left text-danger"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="main-content">
        <!-- Sticky Glassmorphic Topbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-2">
                <!-- Mobile Hamburger Toggle -->
                <button class="btn btn-sm btn-light border d-lg-none rounded-3" id="mobileSidebarToggle" aria-label="Toggle Navigation">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <!-- Desktop Sidebar Collapse Toggle -->
                <button class="btn btn-sm btn-light border d-none d-lg-inline-flex align-items-center justify-content-center rounded-3 text-secondary" id="desktopSidebarToggle" style="width: 34px; height: 34px; padding: 0;" title="Réduire / Agrandir le menu">
                    <i class="bi bi-chevron-left" id="sidebarCollapseIcon" style="font-size: 0.85rem;"></i>
                </button>
            </div>

            <!-- Topbar Right: User Email & Violet Initial Avatar Matching Screenshot -->
            <div class="d-flex align-items-center gap-3 ms-auto dropdown">
                <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center gap-2.5 text-secondary border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="text-secondary small fw-medium d-none d-sm-inline" style="font-size: 0.88rem; color: #475569 !important;">
                        {{ auth('web')->user()?->email ?? 'zriktoneyounes@gmail.com' }}
                    </span>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-xs" style="width: 38px; height: 38px; background-color: #6320ee; font-size: 0.95rem;">
                        {{ strtoupper(substr(auth('web')->user()?->email ?? 'Y', 0, 1)) }}
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 py-2" style="min-width: 220px;">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-bold text-dark">{{ auth('web')->user()?->name ?? 'Administrator' }}</div>
                        <div class="text-muted small">{{ auth('web')->user()?->email ?? 'zriktoneyounes@gmail.com' }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear text-secondary"></i> Paramètres
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Main Body -->
        <main class="content-body">
            <!-- Breadcrumbs Bar (Optional slot/yield) -->
            @hasSection('breadcrumbs')
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb-custom">
                        <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            @endif

            <!-- Flash Session Alerts -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-start gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <div class="fw-bold mb-1">Veuillez corriger les erreurs suivantes :</div>
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-info-circle-fill text-info fs-5"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Content Slot / Section -->
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- Sidebar and Interactivity Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('dashboardWrapper');
    const collapseBtn = document.getElementById('sidebarCollapseBtn');
    const desktopToggle = document.getElementById('desktopSidebarToggle');
    const collapseIcon = document.getElementById('sidebarCollapseIcon');
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const mobileClose = document.getElementById('mobileSidebarCloseBtn');
    const backdrop = document.getElementById('sidebarBackdrop');
    const searchInput = document.getElementById('globalSearchInput');

    // Restore desktop collapsed state from localStorage
    if (localStorage.getItem('sidebar_collapsed') === 'true') {
        wrapper.classList.add('sidebar-collapsed');
        if (collapseIcon) {
            collapseIcon.classList.replace('bi-chevron-left', 'bi-chevron-right');
        }
    }

    function toggleDesktopCollapse() {
        wrapper.classList.toggle('sidebar-collapsed');
        const isCollapsed = wrapper.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
        if (collapseIcon) {
            collapseIcon.classList.toggle('bi-chevron-left', !isCollapsed);
            collapseIcon.classList.toggle('bi-chevron-right', isCollapsed);
        }
    }

    if (collapseBtn) collapseBtn.addEventListener('click', toggleDesktopCollapse);
    if (desktopToggle) desktopToggle.addEventListener('click', toggleDesktopCollapse);

    // Mobile slide-in toggling
    function openMobileSidebar() {
        wrapper.classList.add('sidebar-mobile-open');
        backdrop.classList.remove('d-none');
    }

    function closeMobileSidebar() {
        wrapper.classList.remove('sidebar-mobile-open');
        backdrop.classList.add('d-none');
    }

    if (mobileToggle) mobileToggle.addEventListener('click', openMobileSidebar);
    if (mobileClose) mobileClose.addEventListener('click', closeMobileSidebar);
    if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

    // '/' Hotkey for search bar
    document.addEventListener('keydown', function (e) {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            if (searchInput) searchInput.focus();
        }
    });
});
</script>

<!-- Floating Headset Support Button (Matching Screenshot) -->
<a href="javascript:void(0)" class="floating-support-btn shadow-lg d-none" title="Support" onclick="alert('Support client YNS NFC actif : zriktoneyounes@gmail.com');">
    <i class="bi bi-headset fs-5"></i>
</a>

<script src="{{ asset('js/nfc-encoder.js') }}"></script>
@stack('scripts')
</body>
</html>
