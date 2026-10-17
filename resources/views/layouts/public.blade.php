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
    <!-- Custom Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Public Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-qr-code fs-5"></i>
                </div>
                <span class="fw-bold fs-4 text-dark brand-font">QR Studio</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('features') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('features') }}">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('types') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('types') }}">QR Types</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pricing') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('client.access') }}" class="btn btn-outline-primary px-3 rounded-pill fw-semibold">
                        <i class="bi bi-key-fill me-1"></i> Client Access
                    </a>
                    @if(auth('web')->check())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom px-4 rounded-pill">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @elseif(auth('client')->check())
                        <a href="{{ route('client.dashboard') }}" class="btn btn-primary-custom px-4 rounded-pill">
                            <i class="bi bi-grid-fill me-1"></i> My Portal
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark-custom px-3 rounded-pill">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Staff Login
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
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-bell-fill me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-auto border-top border-secondary">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                            <i class="bi bi-qr-code fs-6"></i>
                        </div>
                        <span class="fw-bold fs-4 text-white brand-font">QR Studio</span>
                    </div>
                    <p class="text-secondary small pe-lg-4">
                        The ultimate dynamic QR code management platform for enterprises, agencies, and hospitality. Update destination content anywhere, anytime, without ever reprinting your codes.
                    </p>
                    <div class="d-flex gap-3 text-secondary fs-5">
                        <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-github"></i></a>
                        <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3">Product</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="{{ route('features') }}" class="text-secondary text-decoration-none">Dynamic QRs</a></li>
                        <li><a href="{{ route('types') }}" class="text-secondary text-decoration-none">All QR Types</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-secondary text-decoration-none">Pricing Plans</a></li>
                        <li><a href="{{ route('client.access') }}" class="text-secondary text-decoration-none">Client Portal</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3">Solutions</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="#" class="text-secondary text-decoration-none">Hotels & Resorts</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Restaurants & Bars</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Retail & Products</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Events & Venues</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold text-white text-uppercase tracking-wider mb-3">Client Quick Access</h6>
                    <p class="text-secondary small">
                        Have a printed QR card with an access code? Enter it directly to customize your landing page.
                    </p>
                    <a href="{{ route('client.access') }}" class="btn btn-primary-custom w-100 rounded-3">
                        <i class="bi bi-key-fill me-1"></i> Access Your QR Portal
                    </a>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-secondary small">
                <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} QR Studio. All rights reserved. Dynamic QR Architecture.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary text-decoration-none">Privacy Policy</a>
                    <a href="#" class="text-secondary text-decoration-none">Terms of Service</a>
                    <a href="{{ route('contact') }}" class="text-secondary text-decoration-none">Support</a>
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
