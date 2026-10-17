@extends('layouts.public')

@section('title', 'About QR Studio')

@section('content')
<section class="py-5 bg-white border-bottom">
    <div class="container py-4 text-center">
        <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2 font-mono">OUR MISSION</span>
        <h1 class="display-5 fw-bold text-dark">Bridging Physical Assets & Dynamic Digital Content</h1>
        <p class="lead text-muted mx-auto" style="max-width: 720px;">
            We built QR Studio to solve the fundamental pain of static QR codes: broken links, outdated menus, and expensive re-printing cycles.
        </p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3">Engineered for Reliability & Privacy</h3>
                <p class="text-muted">
                    In high-traffic venues like resorts, convention centers, and restaurants, QR codes must scan instantaneously on any phone camera. Our dynamic routing layer identifies incoming requests, records privacy-respecting metrics (IP hashed via SHA-256), and serves mobile-optimized landing experiences with zero latency.
                </p>
                <div class="row g-3 pt-2">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border">
                            <h4 class="fw-bold text-primary mb-1">99.99%</h4>
                            <small class="text-muted">Uptime & Redundancy</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border">
                            <h4 class="fw-bold text-primary mb-1">100%</h4>
                            <small class="text-muted">Dynamic Separation</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-custom p-4 bg-white shadow-sm">
                    <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-2"></i> Core Architectural Tenets</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 small text-muted mb-0">
                        <li class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <div><strong>Cryptographic Identifiers:</strong> Non-sequential, unguessable public and access codes protect client boundaries.</div>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <div><strong>Client Sovereignty:</strong> Clients only ever see and customize the QR codes explicitly assigned to their company.</div>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <div><strong>Print-First Precision:</strong> Grid geometry for A4, A5, and Letter printable documents guarantees clear physical scanning.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
