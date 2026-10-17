<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — QR Studio Admin</title>

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
    <!-- Admin Sidebar -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-qr-code fs-5"></i>
                </div>
                <div>
                    <span class="fw-bold fs-5 text-white brand-font d-block leading-tight">QR Studio</span>
                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">ADMIN PANEL</span>
                </div>
            </a>
            <button class="btn btn-sm btn-link text-white-50 d-lg-none" id="sidebarCloseBtn">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            <div class="sidebar-heading">Core</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-heading mt-3">QR Management</div>
            <a href="{{ route('admin.qr-codes.index') }}" class="sidebar-link {{ request()->routeIs('admin.qr-codes.index') || request()->routeIs('admin.qr-codes.show') || request()->routeIs('admin.qr-codes.edit') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>All QR Codes</span>
            </a>
            <a href="{{ route('admin.qr-codes.create') }}" class="sidebar-link {{ request()->routeIs('admin.qr-codes.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Generate QR Codes</span>
            </a>
            <a href="{{ route('admin.qr-documents.index') }}" class="sidebar-link {{ request()->routeIs('admin.qr-documents.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>QR Documents</span>
            </a>
            <a href="{{ route('admin.templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                <i class="bi bi-palette"></i>
                <span>Templates</span>
            </a>

            <div class="sidebar-heading mt-3">Accounts & Access</div>
            <a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <i class="bi bi-buildings"></i>
                <span>Clients</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Users & Staff</span>
            </a>
            <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i>
                <span>Roles & Permissions</span>
            </a>

            <div class="sidebar-heading mt-3">Intelligence & System</div>
            <a href="{{ route('admin.analytics.index') }}" class="sidebar-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Analytics</span>
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Activity Logs</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                        {{ substr(auth('web')->user()->first_name ?? 'A', 0, 1) }}
                    </div>
                    <div style="line-height: 1.2;">
                        <span class="text-white small fw-semibold d-block text-truncate" style="max-width: 130px;">
                            {{ auth('web')->user()->name ?? 'Administrator' }}
                        </span>
                        <span class="text-secondary" style="font-size: 0.72rem;">
                            {{ auth('web')->user()->roles->first()->name ?? 'Staff' }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
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
                <div class="d-none d-sm-block">
                    <h5 class="mb-0 fw-bold">@yield('page_title', 'Dashboard')</h5>
                    <small class="text-muted">@yield('page_subtitle', 'System overview and dynamic QR status')</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-up-right"></i> Live Site
                </a>
                <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary-custom btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1">
                    <i class="bi bi-plus-lg"></i> New QR Code
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
    const sidebar = document.getElementById('adminSidebar');
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
