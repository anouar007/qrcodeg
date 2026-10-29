<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign In') — {{ config('app.name', 'QR Studio') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

<div class="guest-wrapper">
    <!-- Ambient Background Glow Elements -->
    <div class="guest-orb-1"></div>
    <div class="guest-orb-2"></div>

    <!-- Top Navigation Bar -->
    <div class="guest-top-bar">
        <a href="{{ route('home') }}" class="guest-back-link">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Home</span>
        </a>
        <div class="guest-system-status d-none d-sm-inline-flex">
            <span class="status-dot-radar"></span>
            <span>Platform Online</span>
        </div>
    </div>

    <div class="guest-card shadow-xl">
        <!-- Brand Header -->
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="d-inline-flex text-decoration-none mb-3" aria-label="Go to homepage">
                <div class="guest-brand-logo">
                    <i class="bi bi-qr-code fs-2"></i>
                </div>
            </a>
            <div class="mb-2">
                <span class="badge bg-light text-primary border rounded-pill px-3 py-1 font-mono small d-inline-flex align-items-center gap-2">
                    <span class="status-dot-radar"></span>
                    <span>SECURE ACCESS GATEWAY</span>
                </span>
            </div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">@yield('heading', 'Staff & Admin Login')</h3>
            <p class="text-muted small mb-0">@yield('subheading', 'Access your dynamic QR and SaaS platform')</p>
        </div>

        <!-- Session Status & Flash Messages -->
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2 px-3 mb-3 border-0 bg-success-subtle text-success-emphasis" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2 px-3 mb-3 border-0 bg-success-subtle text-success-emphasis" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 small py-2 px-3 mb-3 border-0 bg-info-subtle text-info-emphasis" role="alert">
                <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2 px-3 mb-3 border-0 bg-danger-subtle text-danger-emphasis" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Card Content Slot / Section -->
        @yield('content')
        {{ $slot ?? '' }}

        <!-- Card Footer -->
        <div class="mt-4 pt-3 border-top text-center text-muted font-mono" style="font-size: 0.74rem;">
            <i class="bi bi-shield-lock-fill text-success me-1"></i> End-to-End SSL • {{ config('app.name', 'QR Studio') }} &copy; {{ date('Y') }}
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
