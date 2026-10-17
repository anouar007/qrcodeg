@extends('layouts.public')

@section('title', 'Contact Our Team')

@section('content')
<section class="py-5 bg-white border-bottom text-center">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2 font-mono">SUPPORT & INQUIRIES</span>
        <h1 class="display-5 fw-bold text-dark">Get in Touch</h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
            Have questions about custom hospitality deployments, API integrations, or volume licensing? We are here to help.
        </p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-7">
                <div class="card card-custom p-4 p-md-5 bg-white shadow-sm">
                    <h4 class="fw-bold mb-4">Send Us a Message</h4>

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Your Name</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Jane Doe">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Business Email</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="jane@example.com">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Subject</label>
                                <input type="text" name="subject" class="form-control" required value="{{ old('subject') }}" placeholder="Volume dynamic QR deployment">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Message</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Tell us about your requirements...">{{ old('message') }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary-custom px-4 py-2 w-100 rounded-pill">
                                    <i class="bi bi-send-fill me-1"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-custom p-4 bg-white shadow-sm mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Headquarters</h5>
                    <p class="text-muted small mb-0">
                        QR Studio Platforms Inc.<br>
                        500 Innovation Parkway, Suite 400<br>
                        San Francisco, CA 94105
                    </p>
                </div>

                <div class="card card-custom p-4 bg-white shadow-sm mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-envelope-fill text-primary me-2"></i> Direct Email</h5>
                    <p class="text-muted small mb-1">General: <a href="mailto:contact@qrstudio.io" class="text-primary text-decoration-none">contact@qrstudio.io</a></p>
                    <p class="text-muted small mb-0">Support: <a href="mailto:support@qrstudio.io" class="text-primary text-decoration-none">support@qrstudio.io</a></p>
                </div>

                <div class="card card-custom p-4 bg-primary text-white">
                    <h5 class="fw-bold mb-2">Already have an Access Code?</h5>
                    <p class="small text-white-50 mb-3">Skip inquiries and log directly into your client dashboard to adjust your QR content.</p>
                    <a href="{{ route('client.access') }}" class="btn btn-light btn-sm rounded-pill fw-bold text-primary w-100">
                        Go to Client Access
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
