<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QR Studio') — Dynamic QR Code Management Platform</title>
    <meta name="description" content="@yield('meta_description', 'Enterprise-grade dynamic QR code platform with real-time scan analytics, client portal, customizable landing pages, and printable document batches.')">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 Free (2,163 icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Clean Mobile-First Single-Page Production Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-2.5 navbar-public-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2.5" href="{{ route('home') }}">
                <div class="sidebar-brand-mark shadow-sm" style="width: 38px; height: 38px; border-radius: 11px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                    <i class="bi bi-qr-code-scan text-white fs-5"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-extrabold fs-5 text-dark brand-font lh-1">YNS<span class="text-primary">NFC</span> <span class="fw-normal text-muted fs-6">&amp; QR</span></span>
                    <span class="text-uppercase text-muted font-mono" style="font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em;">Smart Digital Studio</span>
                </div>
            </a>

            <button class="navbar-toggler border rounded-3 p-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-4 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto my-3 my-lg-0 fw-semibold gap-1">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill text-secondary hover-primary" href="{{ request()->routeIs('home') ? '#solutions' : route('home') . '#solutions' }}">Solutions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill text-secondary hover-primary" href="{{ request()->routeIs('home') ? '#interactive-demo' : route('home') . '#interactive-demo' }}">Aperçu Live</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill text-secondary hover-primary" href="{{ request()->routeIs('home') ? '#how-it-works' : route('home') . '#how-it-works' }}">Fonctionnement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill text-secondary hover-primary" href="{{ request()->routeIs('home') ? '#use-cases' : route('home') . '#use-cases' }}">Secteurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill text-secondary hover-primary" href="{{ request()->routeIs('home') ? '#faq' : route('home') . '#faq' }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-pill text-secondary hover-primary" href="{{ request()->routeIs('home') ? '#contact' : route('home') . '#contact' }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 pb-2 pb-lg-0">
                    <a href="{{ route('client.access') }}" class="btn btn-outline-primary px-3.5 py-2 rounded-pill fw-semibold border-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-key-fill text-primary"></i>
                        <span>Espace Client</span>
                    </a>
                    @if(auth('web')->check())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom px-4 py-2 rounded-pill d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    @elseif(auth('client')->check())
                        <a href="{{ route('client.dashboard') }}" class="btn btn-primary-custom px-4 py-2 rounded-pill d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-grid-fill"></i>
                            <span>Mon Portail</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary-custom px-4 py-2 rounded-pill d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Connexion</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Alerts -->
    @if(session('success') || session('error') || session('warning') || session('info'))
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 bg-success-subtle text-success-emphasis d-flex align-items-center gap-2 p-3" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 bg-danger-subtle text-danger-emphasis d-flex align-items-center gap-2 p-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm border-0 bg-warning-subtle text-warning-emphasis d-flex align-items-center gap-2 p-3" role="alert">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-4 shadow-sm border-0 bg-info-subtle text-info-emphasis d-flex align-items-center gap-2 p-3" role="alert">
                    <i class="bi bi-bell-fill fs-5"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Clean Dark Production Footer -->
    <footer class="footer-futuristic text-white pt-5 pb-4 mt-auto position-relative overflow-hidden">
        <div class="container position-relative z-1">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2.5 mb-3">
                        <div class="sidebar-brand-mark shadow-sm" style="width: 36px; height: 36px; border-radius: 9px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                            <i class="bi bi-qr-code-scan text-white fs-6"></i>
                        </div>
                        <span class="fw-bold fs-4 text-white brand-font">YNS<span style="color: #a855f7;">NFC</span> &amp; QR</span>
                    </div>
                    <p class="text-secondary small pe-lg-4 lh-base">
                        Plateforme tout-en-un de gestion de Cartes de Visite Digitales, Menus &amp; Catalogues Interactifs, Tags NFC et QR Codes Dynamiques. Modifiez vos contenus en temps réel sans jamais réimprimer vos supports.
                    </p>
                </div>

                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3 font-mono" style="font-size: 0.76rem; letter-spacing: 0.08em;">Navigation</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="#solutions" class="text-secondary text-decoration-none hover-white">Solutions</a></li>
                        <li><a href="#interactive-demo" class="text-secondary text-decoration-none hover-white">Aperçu Interactif</a></li>
                        <li><a href="#how-it-works" class="text-secondary text-decoration-none hover-white">Fonctionnement</a></li>
                        <li><a href="#use-cases" class="text-secondary text-decoration-none hover-white">Secteurs</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3 font-mono" style="font-size: 0.76rem; letter-spacing: 0.08em;">Accès Rapide</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="{{ route('client.access') }}" class="text-secondary text-decoration-none hover-white">Portail Client (Clé)</a></li>
                        <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none hover-white">Administration</a></li>
                        <li><a href="#faq" class="text-secondary text-decoration-none hover-white">Questions Fréquentes</a></li>
                        <li><a href="#contact" class="text-secondary text-decoration-none hover-white">Nous Contacter</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="p-4 rounded-4 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="status-dot-radar"></span>
                            <h6 class="fw-bold text-white text-uppercase tracking-wider mb-0 font-mono" style="font-size: 0.78rem;">Accès Instantané Client</h6>
                        </div>
                        <p class="text-secondary small mb-3">
                            Vous possédez une carte NFC ou un chevalet QR ? Entrez votre code d'accès unique pour modifier votre lien de destination en direct.
                        </p>
                        <a href="{{ route('client.access') }}" class="btn btn-primary-custom w-100 rounded-pill py-2 text-white d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-key-fill"></i> Entrer mon Code d'Accès
                        </a>
                    </div>
                </div>
            </div>

            <hr class="border-secondary border-opacity-25 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-secondary small">
                <p class="mb-2 mb-md-0 font-mono" style="font-size: 0.78rem;">&copy; {{ date('Y') }} YNS NFC &amp; QR Studio. Tous droits réservés.</p>
                <div class="d-flex gap-3 font-mono" style="font-size: 0.78rem;">
                    <a href="#solutions" class="text-secondary text-decoration-none hover-white">Cartes NFC</a>
                    <span class="text-secondary opacity-50">•</span>
                    <a href="#solutions" class="text-secondary text-decoration-none hover-white">Menus Digitaux</a>
                    <span class="text-secondary opacity-50">•</span>
                    <a href="#contact" class="text-secondary text-decoration-none hover-white">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const navCollapse = document.getElementById('publicNav');
        if (!navCollapse) return;
        navCollapse.querySelectorAll('a.nav-link[href*="#"]').forEach(function (link) {
            link.addEventListener('click', function () {
                if (navCollapse.classList.contains('show') && window.bootstrap) {
                    bootstrap.Collapse.getOrCreateInstance(navCollapse).hide();
                }
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
