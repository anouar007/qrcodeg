@extends('layouts.public')

@section('title', 'Enter Password')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 border-primary border-opacity-25 shadow-lg">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-lock-fill fs-2"></i>
                    </div>
                    <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1 font-mono">CODE: {{ $code }}</span>
                    <h3 class="fw-bold">Enter Your Password</h3>
                    <p class="text-muted small">Please provide the password for this client account to access your QR management dashboard.</p>
                </div>

                <form method="POST" action="{{ route('client.password.login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold small text-muted">Account Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autofocus placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold rounded-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Unlock Dashboard
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <a href="{{ route('client.access') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Enter a different access code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
