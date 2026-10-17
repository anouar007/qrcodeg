<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Portal') — QR Studio Client</title>

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
            <a href="{{ route('client.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-qr-code fs-5"></i>
                </div>
                <div>
                    <span class="fw-bold fs-5 text-white brand-font d-block leading-tight">QR Studio</span>
                    <span class="badge bg-primary-subtle text-white" style="font-size: 0.65rem;">CLIENT PORTAL</span>
                </div>
            </a>
            <button class="btn btn-sm btn-link text-white-50 d-lg-none" id="sidebarCloseBtn">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            <div class="sidebar-heading">Portal</div>
            <a href="{{ route('client.dashboard') }}" class="sidebar-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('client.qr-codes.index') }}" class="sidebar-link {{ request()->routeIs('client.qr-codes.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code"></i>
                <span>My QR Codes</span>
            </a>
            <a href="{{ route('client.analytics') }}" class="sidebar-link {{ request()->routeIs('client.analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i>
                <span>Analytics</span>
            </a>

            <div class="sidebar-heading mt-3">Access Credential</div>
            <div class="px-3 py-2 mx-2 bg-dark rounded-3 border border-secondary">
                <small class="text-secondary d-block" style="font-size: 0.72rem;">ACTIVE ACCESS CODE</small>
                <span class="text-white font-mono fw-bold small">{{ auth('client')->user()->access_code }}</span>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                        {{ substr(auth('client')->user()->company_name ?? 'C', 0, 1) }}
                    </div>
                    <div style="line-height: 1.2;">
                        <span class="text-white small fw-semibold d-block text-truncate" style="max-width: 130px;">
                            {{ auth('client')->user()->company_name ?? 'Client Account' }}
                        </span>
                        <span class="text-secondary" style="font-size: 0.72rem;">
                            {{ auth('client')->user()->contact_name }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('client.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
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

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('client.qr-codes.index') }}" class="btn btn-primary-custom btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1">
                    <i class="bi bi-sliders"></i> Customize QR Content
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

<script>
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
