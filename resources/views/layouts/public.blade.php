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

    <!-- Futuristic Public Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 navbar-public-glass">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="sidebar-brand-mark shadow-sm" style="width: 38px; height: 38px; border-radius: 10px;">
                    <i class="bi bi-qr-code text-white fs-5"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-4 text-dark brand-font lh-1">QR<span class="text-primary">Studio</span></span>
                    <span class="text-uppercase tracking-wider text-muted font-mono" style="font-size: 0.62rem; font-weight: 700; letter-spacing: 0.12em;">Next-Gen SaaS</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium gap-1">
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill {{ request()->routeIs('home') ? 'active text-primary fw-bold bg-primary-subtle' : 'text-secondary hover-primary' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill {{ request()->routeIs('features') ? 'active text-primary fw-bold bg-primary-subtle' : 'text-secondary hover-primary' }}" href="{{ route('features') }}">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill {{ request()->routeIs('types') ? 'active text-primary fw-bold bg-primary-subtle' : 'text-secondary hover-primary' }}" href="{{ route('types') }}">QR Types</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill {{ request()->routeIs('pricing') ? 'active text-primary fw-bold bg-primary-subtle' : 'text-secondary hover-primary' }}" href="{{ route('pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill {{ request()->routeIs('about') ? 'active text-primary fw-bold bg-primary-subtle' : 'text-secondary hover-primary' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill {{ request()->routeIs('contact') ? 'active text-primary fw-bold bg-primary-subtle' : 'text-secondary hover-primary' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Live SLA status pill -->
                    <div class="d-none d-xl-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border me-2 small">
                        <span class="status-dot-radar"></span>
                        <span class="text-muted font-mono" style="font-size: 0.72rem; font-weight: 600;">99.99% Core</span>
                    </div>

                    <a href="{{ route('client.access') }}" class="btn btn-outline-primary px-3 rounded-pill fw-semibold border-2 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-key-fill text-primary"></i> Client Portal
                    </a>
                    @if(auth('web')->check())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom px-4 rounded-pill d-inline-flex align-items-center gap-2">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @elseif(auth('client')->check())
                        <a href="{{ route('client.dashboard') }}" class="btn btn-primary-custom px-4 rounded-pill d-inline-flex align-items-center gap-2">
                            <i class="bi bi-grid-fill"></i> My Portal
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary-custom px-4 rounded-pill d-inline-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i> Staff Sign In
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

    <!-- Futuristic Cosmic Dark Footer -->
    <footer class="footer-futuristic text-white pt-5 pb-4 mt-auto position-relative overflow-hidden">
        <div class="container position-relative z-1">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="sidebar-brand-mark shadow-sm" style="width: 36px; height: 36px; border-radius: 9px;">
                            <i class="bi bi-qr-code text-white fs-6"></i>
                        </div>
                        <span class="fw-bold fs-4 text-white brand-font">QR<span style="color: var(--cyan-accent);">Studio</span></span>
                    </div>
                    <p class="text-secondary small pe-lg-4 lh-base">
                        The ultimate dynamic QR code management platform for enterprises, agencies, and hospitality. Update destination content anywhere, anytime, without ever reprinting physical assets.
                    </p>
                    <div class="d-flex gap-2 text-secondary fs-6">
                        <a href="#" class="btn btn-sm btn-dark border border-secondary border-opacity-25 rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-sm btn-dark border border-secondary border-opacity-25 rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="btn btn-sm btn-dark border border-secondary border-opacity-25 rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi bi-github"></i></a>
                        <a href="#" class="btn btn-sm btn-dark border border-secondary border-opacity-25 rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi bi-shield-check text-success"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3 font-mono" style="font-size: 0.8rem; letter-spacing: 0.08em;">Product</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="{{ route('features') }}" class="text-secondary text-decoration-none hover-white">Dynamic QRs (VP)</a></li>
                        <li><a href="{{ route('types') }}" class="text-secondary text-decoration-none hover-white">Smart NFC Tags</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-secondary text-decoration-none hover-white">Digital Cards</a></li>
                        <li><a href="{{ route('client.access') }}" class="text-secondary text-decoration-none hover-white">Client Portal</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3 font-mono" style="font-size: 0.8rem; letter-spacing: 0.08em;">Solutions</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="#" class="text-secondary text-decoration-none hover-white">Hotels & Resorts</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none hover-white">Restaurants & Bars</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none hover-white">Retail & FMCG</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none hover-white">Corporate Events</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="p-4 rounded-4 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="status-dot-radar"></span>
                            <h6 class="fw-bold text-white text-uppercase tracking-wider mb-0 font-mono" style="font-size: 0.8rem;">Client Instant Access</h6>
                        </div>
                        <p class="text-secondary small mb-3">
                            Have a printed QR card or NFC token? Enter your unique access key to modify your live redirection destination.
                        </p>
                        <a href="{{ route('client.access') }}" class="btn btn-primary-custom w-100 rounded-pill py-2 text-white d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-key-fill"></i> Enter Access Key
                        </a>
                    </div>
                </div>
            </div>

            <hr class="border-secondary border-opacity-25 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-secondary small">
                <p class="mb-2 mb-md-0 font-mono" style="font-size: 0.78rem;">&copy; {{ date('Y') }} QR Studio. Quantum Dynamic QR & NFC Engine.</p>
                <div class="d-flex gap-3 font-mono" style="font-size: 0.78rem;">
                    <a href="#" class="text-secondary text-decoration-none hover-white">Security & Privacy</a>
                    <span class="text-secondary opacity-50">•</span>
                    <a href="#" class="text-secondary text-decoration-none hover-white">Terms of Service</a>
                    <span class="text-secondary opacity-50">•</span>
                    <a href="{{ route('contact') }}" class="text-secondary text-decoration-none hover-white">Telemetry SLA</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
