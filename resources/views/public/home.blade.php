@extends('layouts.public')

@section('title', 'Create, Manage & Customize Dynamic QR Codes')

@section('content')
<!-- Hero Section -->
<section class="py-5 hero-gradient border-bottom">
    <div class="container py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <div class="feature-pill">
                    <i class="bi bi-stars"></i> Next-Gen Dynamic QR Architecture
                </div>
                <h1 class="display-4 fw-extrabold text-dark tracking-tight mb-3">
                    Create, manage and customize your QR codes.
                </h1>
                <p class="lead text-muted mb-4 fs-5 pe-lg-4">
                    Deploy intelligent dynamic QR codes for your business, hotel, restaurant, or marketing campaigns. Change destinations and customize content on the fly without ever reprinting your materials.
                </p>

                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mb-4">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom px-4 py-3 fs-6 rounded-pill d-inline-flex align-items-center gap-2">
                        <i class="bi bi-qr-code-scan"></i> Create QR Code
                    </a>
                    <a href="{{ route('client.access') }}" class="btn btn-outline-dark px-4 py-3 fs-6 rounded-pill d-inline-flex align-items-center gap-2">
                        <i class="bi bi-key-fill text-primary"></i> Client Access
                    </a>
                </div>

                <div class="d-flex align-items-center gap-4 justify-content-center justify-content-lg-start pt-2 text-muted small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> 100% Dynamic URLs
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Real-time Analytics
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Batch Print Engine
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <!-- Visual Interactive Mockup Card -->
                <div class="card card-custom p-4 shadow-xl border-primary border-opacity-25 bg-white position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary-subtle text-primary fw-bold font-mono">PUBLIC: QR-X7K9P2M4</span>
                        <span class="badge bg-success-subtle text-success">● ACTIVE</span>
                    </div>

                    <div class="p-3 bg-light rounded-4 text-center my-2 border">
                        <div class="p-3 bg-white d-inline-block rounded-3 shadow-sm border mb-2">
                            <i class="bi bi-qr-code text-primary" style="font-size: 130px; line-height: 1;"></i>
                        </div>
                        <div class="small text-muted font-mono">Dynamic Destination:</div>
                        <div class="text-primary fw-bold font-mono text-truncate small">{{ url('/q/QR-X7K9P2M4') }}</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top small">
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.72rem;">TOTAL SCANS</span>
                            <span class="fw-bold fs-5 text-dark">1,248</span>
                        </div>
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.72rem;">CONTENT TYPE</span>
                            <span class="badge bg-secondary">Business Card & vCard</span>
                        </div>
                        <a href="{{ url('/q/QR-X7K9P2M4') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                            Test Scan <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- The Dynamic Concept Workflow Section -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="text-center max-w-xl mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2 font-mono">HOW IT WORKS</span>
            <h2 class="fw-bold fs-2 text-dark">Never Reprint a QR Code Again</h2>
            <p class="text-muted">Dynamic QR routing separates physical codes from your digital content.</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card card-custom p-4 h-100">
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-printer fs-4"></i>
                    </div>
                    <h5 class="fw-bold">1. Print Dynamic QR Once</h5>
                    <p class="text-muted small mb-0">
                        Generate unique identifiers such as <code>/q/QR-X7K9P2M4</code> and print them on business cards, menus, flyers, or room keys.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-4 h-100 border-primary">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-sliders fs-4"></i>
                    </div>
                    <h5 class="fw-bold">2. Customize Content Anytime</h5>
                    <p class="text-muted small mb-0">
                        Clients login using their private access code (e.g. <code>X7K9-P2M4-81QZ</code>) to change URLs, upload files, or swap menus instantaneously.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-4 h-100">
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-phone fs-4"></i>
                    </div>
                    <h5 class="fw-bold">3. Scanners See Updated Live Info</h5>
                    <p class="text-muted small mb-0">
                        Whenever someone scans the physical QR code, our cloud engine logs analytics and immediately serves the fresh content.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Supported QR Content Types Grid -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-between align-items-end mb-4">
            <div class="col-md-8">
                <span class="badge bg-secondary px-3 py-1 mb-2">VERSATILE CAPABILITIES</span>
                <h2 class="fw-bold fs-2 text-dark">Supported QR Content Types</h2>
                <p class="text-muted">Turn any physical code into an interactive mobile application.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('types') }}" class="btn btn-outline-primary rounded-pill">
                    View All Types <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-globe fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Website URL</h6>
                            <small class="text-muted">Smart instant redirects</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-person-vcard fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Digital vCard</h6>
                            <small class="text-muted">Downloadable phone contact</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-wifi fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">WiFi Auto-Connect</h6>
                            <small class="text-muted">Zero-friction network login</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">WhatsApp Direct</h6>
                            <small class="text-muted">Pre-filled chat trigger</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-share fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Social Media Hub</h6>
                            <small class="text-muted">Instagram, TikTok, YouTube links</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-indigo-subtle text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-layout-text-window fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Custom Landing Page</h6>
                            <small class="text-muted">Modular mobile blocks</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container py-4">
        <h2 class="display-6 fw-bold mb-3">Ready to upgrade your physical communications?</h2>
        <p class="lead text-secondary mb-4">Start managing high-impact dynamic QR codes with real-time intelligence today.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('client.access') }}" class="btn btn-primary-custom px-4 py-3 rounded-pill">
                <i class="bi bi-key-fill me-1"></i> Enter Client Access Code
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light px-4 py-3 rounded-pill">
                <i class="bi bi-box-arrow-in-right me-1"></i> Administrator Login
            </a>
        </div>
    </div>
</section>
@endsection
