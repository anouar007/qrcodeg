@extends('layouts.public')

@section('title', 'Simple Transparent Pricing')

@section('content')
<section class="py-5 bg-white border-bottom text-center">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2 font-mono">PLANS & PACKAGES</span>
        <h1 class="display-5 fw-bold text-dark">Flexible Plans for Every Stage</h1>
        <p class="lead text-muted mx-auto" style="max-width: 650px;">
            Start small with individual dynamic codes or power high-volume enterprise hospitality operations with bulk generation.
        </p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-4 justify-content-center">
            <!-- Starter Plan -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom p-4 bg-white h-100">
                    <h5 class="fw-bold mb-1">Starter</h5>
                    <p class="text-muted small">Ideal for individual consultants & small shops.</p>
                    <div class="my-3">
                        <span class="display-5 fw-extrabold text-dark">$19</span>
                        <span class="text-muted">/month</span>
                    </div>
                    <hr>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                        <li><i class="bi bi-check2 text-success me-2"></i> <strong>15</strong> Dynamic QR Codes</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Unlimited scans</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Client Access Code login</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Basic scan analytics</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> High-res PNG & SVG downloads</li>
                    </ul>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary w-100 rounded-pill mt-auto">Get Started</a>
                </div>
            </div>

            <!-- Professional Plan -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom p-4 bg-white h-100 border-primary shadow-lg position-relative">
                    <span class="position-absolute top-0 start-50 translate-middle badge bg-primary px-3 py-2 rounded-pill">MOST POPULAR</span>
                    <h5 class="fw-bold mb-1">Professional</h5>
                    <p class="text-muted small">Best for restaurants, hotels & multi-unit businesses.</p>
                    <div class="my-3">
                        <span class="display-5 fw-extrabold text-primary">$49</span>
                        <span class="text-muted">/month</span>
                    </div>
                    <hr>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                        <li><i class="bi bi-check2 text-success me-2"></i> <strong>100</strong> Dynamic QR Codes</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Real-time geo & device metrics</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> High-density batch document print generator</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Custom logo embedding</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Modular landing page builder</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Priority support</li>
                    </ul>
                    <a href="{{ route('contact') }}" class="btn btn-primary-custom w-100 rounded-pill mt-auto">Upgrade to Pro</a>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom p-4 bg-white h-100">
                    <h5 class="fw-bold mb-1">Enterprise</h5>
                    <p class="text-muted small">For major hospitality chains and large agencies.</p>
                    <div class="my-3">
                        <span class="display-5 fw-extrabold text-dark">$149</span>
                        <span class="text-muted">/month</span>
                    </div>
                    <hr>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-4">
                        <li><i class="bi bi-check2 text-success me-2"></i> <strong>Unlimited</strong> Dynamic QR Codes</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Batch generation up to 1,000 QRs per run</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Multi-client management portal</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Custom staff roles & permissions</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> CSV analytics export & audit logs</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Dedicated account manager</li>
                    </ul>
                    <a href="{{ route('contact') }}" class="btn btn-outline-dark w-100 rounded-pill mt-auto">Contact Enterprise</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
