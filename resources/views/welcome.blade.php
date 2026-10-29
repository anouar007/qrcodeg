@extends('layouts.public')

@section('title', 'VP SaaS — Dynamic QR Codes, Digital Business Cards & NFC Engine')
@section('meta_description', 'All-in-one SaaS platform for dynamic QR codes with VP tracking, customizable digital business cards, and smart NFC tag routing.')

@section('content')
<!-- 1. Next-Gen Futuristic SaaS Hero Section -->
<section class="py-5 position-relative overflow-hidden" style="background: radial-gradient(circle at 10% 10%, rgba(79, 70, 229, 0.08) 0%, transparent 40%), radial-gradient(circle at 90% 90%, rgba(6, 182, 212, 0.09) 0%, transparent 40%), #ffffff;">
    <div class="guest-orb-1 opacity-75"></div>
    <div class="guest-orb-2 opacity-75"></div>

    <div class="container py-lg-5 position-relative z-1">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <!-- High-tech chip indicator -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light text-primary small fw-bold mb-3 border shadow-sm">
                    <span class="status-dot-radar"></span>
                    <span class="font-mono text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.08em;">Quantum Engine v3.4 Active</span>
                </div>

                <h1 class="display-4 fw-extrabold text-dark tracking-tight mb-3">
                    Create, manage and customize your QR codes.
                </h1>

                <p class="lead text-muted mb-4 fs-5 pe-lg-4 lh-base">
                    The enterprise SaaS ecosystem for modern brands. Deploy intelligent <strong>VP-tracked Dynamic QR codes</strong>, publish bespoke <strong>Digital Business Cards</strong>, and configure instant <strong>NFC touch tags</strong> without ever reprinting your collateral.
                </p>

                <!-- Action CTAs -->
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mb-4">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom px-4 py-3 fs-6 rounded-pill d-inline-flex align-items-center gap-2 shadow-lg">
                        <i class="bi bi-rocket-takeoff-fill"></i> Launch Dashboard
                    </a>
                    <a href="{{ route('client.access') }}" class="btn btn-glass px-4 py-3 fs-6 rounded-pill d-inline-flex align-items-center gap-2">
                        <i class="bi bi-key-fill text-primary"></i> Client Access Portal
                    </a>
                </div>

                <!-- Live Metrics Pills -->
                <div class="d-flex align-items-center gap-4 justify-content-center justify-content-lg-start pt-2 text-muted small flex-wrap font-mono">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span>18ms Sub-Routing</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>100% Dynamic VP Keys</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-broadcast text-info"></i>
                        <span>NFC Dual-Layer Routing</span>
                    </div>
                </div>
            </div>

            <!-- Hero Interactive Preview Card -->
            <div class="col-lg-5">
                <div class="preview-hologram-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-dot-radar"></span>
                            <span class="vp-code-badge">VP-8F29A1</span>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill font-mono" style="font-size: 0.72rem;">
                            LIVE ONLINE
                        </span>
                    </div>

                    <div class="p-4 bg-light rounded-4 text-center my-3 border border-secondary border-opacity-10 position-relative overflow-hidden" style="background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);">
                        <div class="p-3 bg-white d-inline-block rounded-3 shadow-md border mb-3 position-relative">
                            <i class="bi bi-qr-code text-primary" style="font-size: 130px; line-height: 1; filter: drop-shadow(0 4px 12px rgba(79, 70, 229, 0.25));"></i>
                        </div>
                        <div class="small text-muted font-mono mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">ACTIVE REDIRECTION TARGET:</div>
                        <div class="p-2 bg-white rounded-3 border text-primary fw-bold font-mono text-truncate small shadow-xs">
                            https://g.page/r/abchotel/review
                        </div>
                    </div>

                    <div class="row g-2 text-center mt-3 pt-3 border-top">
                        <div class="col-4">
                            <span class="text-muted d-block font-mono" style="font-size: 0.7rem;">TOTAL SCANS</span>
                            <span class="fw-extrabold fs-5 text-dark font-mono">1,842</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block font-mono" style="font-size: 0.7rem;">TEMPLATE</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1 font-mono" style="font-size: 0.72rem;">Google Review</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block font-mono" style="font-size: 0.7rem;">NFC LINKED</span>
                            <span class="badge bg-cyan-subtle text-info border border-info-subtle mt-1 font-mono" style="font-size: 0.72rem;">Active NFC</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Core Features Grid -->
<section class="py-5 bg-white border-bottom position-relative" id="features">
    <div class="container py-4">
        <div class="text-center max-w-xl mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2 font-mono" style="letter-spacing: 0.08em;">CORE ARCHITECTURE</span>
            <h2 class="fw-extrabold fs-2 text-dark">One Platform, Infinite Touchpoints</h2>
            <p class="text-muted">Unify physical collateral and digital destinations under a unified enterprise telemetry dashboard.</p>
        </div>

        <div class="row g-4">
            <!-- Feature 1: Dynamic QR Codes with VP Tracking -->
            <div class="col-md-4">
                <div class="feature-card-futuristic">
                    <div class="stat-icon-wrapper stat-icon-primary mb-3 shadow-xs">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Dynamic QR Codes (VP Engine)</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Generate high-resolution vector QR codes with our signature <code>VP-XXXXXX</code> tracking format. Update destination URLs, redirect rules, and campaign types on the fly without ever reprinting your collateral.
                    </p>
                </div>
            </div>

            <!-- Feature 2: Digital Business Cards -->
            <div class="col-md-4">
                <div class="feature-card-futuristic">
                    <div class="stat-icon-wrapper stat-icon-info mb-3 shadow-xs">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Cartes Visite Digitales</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Empower your team with sleek digital business cards featuring live vCard downloads, rich contact blocks, social icons, custom brand themes, and unique vanity URLs (e.g. <code>/c/CD-MARCUS</code>).
                    </p>
                </div>
            </div>

            <!-- Feature 3: Smart NFC Routing -->
            <div class="col-md-4">
                <div class="feature-card-futuristic">
                    <div class="stat-icon-wrapper stat-icon-warning mb-3 shadow-xs">
                        <i class="bi bi-broadcast-pin"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Smart NFC Tag Routing</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Link physical NFC tags and touch cards to dynamic destination URLs, digital cards, or review prompts. Tap telemetry tracks scan counts, device types, and operating systems in real time.
                    </p>
                </div>
            </div>

            <!-- Feature 4: Role-Based Access Control -->
            <div class="col-md-4">
                <div class="feature-card-futuristic">
                    <div class="stat-icon-wrapper stat-icon-success mb-3 shadow-xs">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Enterprise Roles & Permissions</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Granular RBAC architecture. Configure system-level roles (Admin, Staff, QR Specialist, Client Manager) and fine-grained direct user permissions with complete audit trails.
                    </p>
                </div>
            </div>

            <!-- Feature 5: Real-Time Telemetry & Logs -->
            <div class="col-md-4">
                <div class="feature-card-futuristic">
                    <div class="stat-icon-wrapper stat-icon-danger mb-3 shadow-xs">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Polymorphic Scan Analytics</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Track every interaction across QR scans and NFC taps. Record IP addresses, user agents, operating systems, and timestamp distributions with Chart.js analytics.
                    </p>
                </div>
            </div>

            <!-- Feature 6: Batch Printing & Sheet Builder -->
            <div class="col-md-4">
                <div class="feature-card-futuristic">
                    <div class="stat-icon-wrapper stat-icon-primary mb-3 shadow-xs">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Printable Document Engine</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Build automated PDF print sheets for table cards, room keys, and promotional displays with custom margins, high DPI vector output, and batch ZIP export.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Pricing Preview Section -->
<section class="py-5 bg-light border-bottom" id="pricing">
    <div class="container py-4">
        <div class="text-center max-w-xl mx-auto mb-5">
            <span class="badge bg-secondary-subtle text-secondary px-3 py-1 mb-2 font-mono" style="letter-spacing: 0.08em;">TRANSPARENT PLANS</span>
            <h2 class="fw-extrabold fs-2 text-dark">Predictable Cloud Pricing</h2>
            <p class="text-muted">Choose the package that scales with your venues, cards, and dynamic campaigns.</p>
        </div>

        <div class="row g-4 justify-content-center align-items-stretch">
            <!-- Tier 1: Starter -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card-futuristic">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Starter</h5>
                            <span class="badge bg-light text-dark border font-mono">Single Brand</span>
                        </div>
                        <div class="mb-3">
                            <span class="display-6 fw-extrabold text-dark font-mono">$29</span>
                            <span class="text-muted small">/ month</span>
                        </div>
                        <p class="text-muted small mb-4">Essential dynamic QR codes and personal digital business cards.</p>

                        <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Up to 25 Dynamic VP QR Codes</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> 5 Digital Business Cards</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Basic Scan Telemetry</li>
                            <li class="d-flex align-items-center gap-2 text-muted"><i class="bi bi-dash"></i> No NFC Tag Management</li>
                            <li class="d-flex align-items-center gap-2 text-muted"><i class="bi bi-dash"></i> Single Admin User</li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="btn btn-glass w-100 rounded-pill py-2">
                        Get Started <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Tier 2: Professional (Featured) -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card-futuristic pricing-card-featured">
                    <span class="position-absolute top-0 start-50 translate-middle badge bg-primary text-white px-3 py-1 rounded-pill shadow-sm font-mono" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                        RECOMMENDED
                    </span>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-primary">Professional</h5>
                            <span class="badge bg-primary-subtle text-primary font-mono">Hospitality & Retail</span>
                        </div>
                        <div class="mb-3">
                            <span class="display-6 fw-extrabold text-dark font-mono">$79</span>
                            <span class="text-muted small">/ month</span>
                        </div>
                        <p class="text-muted small mb-4">For growing venues, hotels, and agencies needing NFC and advanced tracking.</p>

                        <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Unlimited Dynamic VP Codes</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> 50 Digital Business Cards</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> 50 NFC Tag Links & Routing</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> 30-Day Scan Logs & Device Breakdowns</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Batch Printable PDF Sheets</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Multi-User Roles (Admin + Staff)</li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="btn btn-primary-custom w-100 rounded-pill py-2 shadow-md">
                        Start Professional <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Tier 3: Enterprise -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card-futuristic">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Enterprise</h5>
                            <span class="badge bg-dark text-white font-mono">Full Scale</span>
                        </div>
                        <div class="mb-3">
                            <span class="display-6 fw-extrabold text-dark font-mono">$199</span>
                            <span class="text-muted small">/ month</span>
                        </div>
                        <p class="text-muted small mb-4">Custom white-label branding, dedicated client portal, and multi-tenant isolation.</p>

                        <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Unlimited Everything (QR, Cards, NFC)</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Client Access Portal with Custom Passwords</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Custom Domain & White-Label Branding</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> Full RBAC & Client History Audits</li>
                            <li class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success fw-bold"></i> 24/7 Dedicated Account Manager</li>
                        </ul>
                    </div>

                    <a href="{{ route('contact') }}" class="btn btn-glass w-100 rounded-pill py-2">
                        Contact Enterprise <i class="bi bi-chat-dots ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Futuristic Call-to-Action Banner -->
<section class="py-5 footer-futuristic text-white text-center position-relative overflow-hidden">
    <div class="guest-orb-1 opacity-50"></div>
    <div class="container py-4 position-relative z-1">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-white small fw-bold mb-3 border border-white border-opacity-20 font-mono">
            <span class="status-dot-radar"></span>
            ZERO DOWNTIME REDIRECTION
        </div>
        <h2 class="display-6 fw-extrabold mb-3 text-white">Transform Your Physical Touchpoints Today</h2>
        <p class="lead text-secondary mb-4 max-w-xl mx-auto lh-base">
            Experience the unmatched flexibility of dynamic VP QR codes, digital business cards, and NFC routing backed by enterprise security.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('login') }}" class="btn btn-primary-custom px-4 py-3 rounded-pill fs-6 shadow-lg">
                <i class="bi bi-box-arrow-in-right me-1"></i> Launch Dashboard
            </a>
            <a href="{{ route('client.access') }}" class="btn btn-glass px-4 py-3 rounded-pill fs-6 text-white border-white border-opacity-25" style="background: rgba(255, 255, 255, 0.08);">
                <i class="bi bi-key-fill me-1 text-primary"></i> Client Portal Login
            </a>
        </div>
    </div>
</section>
@endsection
