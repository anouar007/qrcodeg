@extends('layouts.public')

@section('title', 'Platform Features')

@section('content')
<section class="py-5 bg-white border-bottom">
    <div class="container py-4 text-center">
        <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2">POWERFUL SAAS ENGINE</span>
        <h1 class="display-5 fw-bold text-dark">Enterprise Dynamic QR Capabilities</h1>
        <p class="lead text-muted mx-auto" style="max-width: 680px;">
            Everything required to print, distribute, track, and reconfigure physical QR codes at any scale.
        </p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="rounded-3 bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-arrow-repeat fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Dynamic Routing & Re-Targeting</h5>
                    <p class="text-muted small">
                        Never reprint collateral again. Update destination links, files, menus, or WiFi passwords in real-time while the printed code remains identical.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="rounded-3 bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-key-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Client Access Code System</h5>
                    <p class="text-muted small">
                        Each QR code and client receives a unique cryptographically generated access code. Clients can independently manage their own codes without seeing internal admin settings.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="rounded-3 bg-info text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-printer-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold">High-Density Batch Print Engine</h5>
                    <p class="text-muted small">
                        Generate printable PDF sheets configured for standard A4, A5, and Letter formats with custom grid columns (2, 3, 4) and rows (4, 5, 6) ready for professional cutting.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="rounded-3 bg-warning text-dark d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-bar-chart-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Real-time Privacy-Safe Analytics</h5>
                    <p class="text-muted small">
                        Track scans by hour, day, and month. Understand device distribution (iOS, Android, desktop), operating system, and geographic locations without violating user privacy.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="rounded-3 bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-lock-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Granular Role-Based Access Control</h5>
                    <p class="text-muted small">
                        Full dynamic RBAC. Create specialized roles like QR Manager, Client Manager, or Analytics Manager with surgical module permissions.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="rounded-3 bg-dark text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-file-earmark-zip-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold">Multi-Format Vector Exports</h5>
                    <p class="text-muted small">
                        Export crystal clear SVG vectors, high-res PNGs rasterized via PHP GD, single-page PDFs, or bulk ZIP packages in one click.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
