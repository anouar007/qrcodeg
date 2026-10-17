@extends('layouts.public')

@section('title', 'Staff & Admin Login')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold">Staff & Admin Login</h3>
                    <p class="text-muted small">Sign in with your administrator or staff credentials</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label fw-semibold small text-muted">Email or Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input id="login" type="text" class="form-control border-start-0 @error('login') is-invalid @enderror" name="login" value="{{ old('login', 'admin@example.com') }}" required autofocus placeholder="admin or admin@example.com">
                        </div>
                        @error('login')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold small text-muted">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required value="password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Keep me logged in</label>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Dashboard
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center">
                    <span class="text-muted small">Are you a client with a printed QR code?</span>
                    <a href="{{ route('client.access') }}" class="fw-semibold text-primary d-block mt-1 small">
                        <i class="bi bi-key-fill me-1"></i> Enter Client Access Code
                    </a>
                </div>

                <!-- Demo helper -->
                <div class="mt-4 p-3 bg-light rounded-3 border small">
                    <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle me-1 text-primary"></i> Seeded Demo Credentials:</div>
                    <div class="text-secondary">Admin: <code>admin@example.com</code> / <code>password</code></div>
                    <div class="text-secondary">Staff: <code>staff@example.com</code> / <code>password</code></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
