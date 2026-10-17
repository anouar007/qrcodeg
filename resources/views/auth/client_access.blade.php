@extends('layouts.public')

@section('title', 'Client Portal Access')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card card-custom p-4 p-md-5 border-primary border-opacity-25 shadow-lg">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 64px; height: 64px;">
                        <i class="bi bi-key-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Client QR Access</h3>
                    <p class="text-muted small">Enter the unique access code printed with your dynamic QR code to manage and customize its live content.</p>
                </div>

                <form method="POST" action="{{ route('client.access.verify') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="access_code" class="form-label fw-semibold text-dark small text-uppercase tracking-wider">Unique Client Access Code</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-primary"></i></span>
                            <input id="access_code" type="text"
                                class="form-control border-start-0 font-mono fw-bold fs-5 text-uppercase @error('access_code') is-invalid @enderror"
                                name="access_code"
                                value="{{ old('access_code', 'X7K9-P2M4-81QZ') }}"
                                required autofocus
                                placeholder="XXXX-XXXX-XXXX"
                                style="letter-spacing: 0.1em;">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Found printed directly underneath or next to your QR card.
                        </small>
                        @error('access_code')
                            <div class="text-danger small mt-1 fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-6">
                        <i class="bi bi-arrow-right-circle me-1"></i> Verify & Continue
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    Need help finding your code? Contact your venue administrator or hospitality manager.
                </div>

                <!-- Seeded Access Codes Helper -->
                <div class="mt-4 p-3 bg-light rounded-3 border small">
                    <div class="fw-bold text-dark mb-2"><i class="bi bi-lightning-charge-fill me-1 text-warning"></i> Quick Demo Access Codes:</div>
                    <div class="mb-1">
                        <span class="badge bg-success-subtle text-success me-1">Active Client (ABC Hotel):</span><br>
                        <code class="fw-bold text-primary">X7K9-P2M4-81QZ</code> (Password: <code>secret123</code>)
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-warning-subtle text-warning me-1">First-Time Setup (Lumina Club):</span><br>
                        <code class="fw-bold text-primary">LUMI-FIT7-99XX</code> (Requires first-time password setup)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
