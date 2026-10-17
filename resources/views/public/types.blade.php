@extends('layouts.public')

@section('title', 'QR Code Content Types')

@section('content')
<section class="py-5 bg-white border-bottom">
    <div class="container py-4 text-center">
        <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2 font-mono">EXTENSIVE TYPES</span>
        <h1 class="display-5 fw-bold text-dark">Supported Dynamic QR Types</h1>
        <p class="lead text-muted mx-auto" style="max-width: 680px;">
            Choose from a rich library of specialized mobile interactive modules designed for every business use case.
        </p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-4">
            <!-- 1. Website -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-globe fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Website URL</h5>
                    </div>
                    <p class="text-muted small">Redirect scanners instantly to any website, booking system, or online store with seamless campaign tracking.</p>
                </div>
            </div>

            <!-- 2. Contact / vCard -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-success text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-person-vcard fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Contact & vCard</h5>
                    </div>
                    <p class="text-muted small">Share full contact cards: Name, Phone, Email, Company, Job Title, Address, and single-click .vcf address book downloads.</p>
                </div>
            </div>

            <!-- 3. WiFi -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-wifi fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">WiFi Connect</h5>
                    </div>
                    <p class="text-muted small">Allow guests to connect effortlessly to wireless networks without typing tricky passwords. Supports WPA/WPA2/WEP.</p>
                </div>
            </div>

            <!-- 4. WhatsApp -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">WhatsApp Direct</h5>
                    </div>
                    <p class="text-muted small">Open direct WhatsApp conversation with your sales team or customer service with custom pre-written greeting messages.</p>
                </div>
            </div>

            <!-- 5. Social Media -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-danger text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-share fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Social Media Hub</h5>
                    </div>
                    <p class="text-muted small">Combine Instagram, TikTok, LinkedIn, YouTube, X (Twitter), and Facebook channels on one beautiful unified landing page.</p>
                </div>
            </div>

            <!-- 6. Business -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-indigo text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Business Profile</h5>
                    </div>
                    <p class="text-muted small">Showcase company name, logo, operating hours, physical address, direct phone dialing, and interactive Google Maps link.</p>
                </div>
            </div>

            <!-- 7. Product Showcase -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-purple text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-bag-check fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Product Showcase</h5>
                    </div>
                    <p class="text-muted small">Display product photography, pricing, detailed specifications, warranty manuals, and quick checkout or inquiry links.</p>
                </div>
            </div>

            <!-- 8. PDF & Documents -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-file-earmark-pdf fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">PDF Document</h5>
                    </div>
                    <p class="text-muted small">Upload digital menus, brochures, technical guides, or wine lists for immediate viewing or mobile downloading.</p>
                </div>
            </div>

            <!-- 9. Modular Custom Landing Page -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white border-primary">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-layout-text-window-reverse fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Custom Landing Page</h5>
                    </div>
                    <p class="text-muted small">Assemble mobile pages with modular blocks: headers, paragraphs, buttons, image galleries, video embeds, and contacts.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
