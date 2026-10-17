@extends('layouts.public')

@section('title', 'Create Your Password')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 border-success border-opacity-25 shadow-lg">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <span class="badge bg-success mb-2 px-3 py-1">FIRST-TIME ONBOARDING</span>
                    <h3 class="fw-bold">Create Your Password</h3>
                    <p class="text-muted small">Access Code Verified: <strong class="text-dark font-mono">{{ $code }}</strong>. Please set a secure password for future logins.</p>
                </div>

                <form method="POST" action="{{ route('client.password.set') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold small text-muted">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autofocus placeholder="At least 6 characters">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold small text-muted">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input id="password_confirmation" type="password" class="form-control border-start-0" name="password_confirmation" required placeholder="Re-type your password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3">
                        <i class="bi bi-check-lg me-1"></i> Save Password & Enter Portal
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <a href="{{ route('client.access') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Use a different access code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
