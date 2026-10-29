@extends('layouts.guest')

@section('title', 'Staff & Admin Login')
@section('heading', 'Staff & Admin Login')
@section('subheading', 'Sign in to access your dashboard and SaaS modules')

@section('content')
<!-- Interactive Fast-Fill Credentials Switcher -->
<div class="quick-fill-container">
    <div class="quick-fill-label">
        <i class="bi bi-lightning-charge-fill text-warning"></i>
        <span>Instant Demo Credentials</span>
    </div>
    <div class="quick-fill-chips">
        <button
            type="button"
            class="quick-fill-btn active"
            id="quickFillAdminBtn"
            data-login="admin@example.com"
            data-password="password"
            title="Click to fill Master Admin credentials"
        >
            <span class="chip-role text-primary">
                <i class="bi bi-shield-check"></i> Admin
            </span>
            <span class="chip-email">admin@example.com</span>
        </button>

        <button
            type="button"
            class="quick-fill-btn"
            id="quickFillStaffBtn"
            data-login="staff@example.com"
            data-password="password"
            title="Click to fill Operator Staff credentials"
        >
            <span class="chip-role text-dark">
                <i class="bi bi-person-badge"></i> Staff
            </span>
            <span class="chip-email">staff@example.com</span>
        </button>
    </div>
</div>

<form method="POST" action="{{ route('login.post') }}" id="loginForm" novalidate>
    @csrf

    <!-- Email or Username Input -->
    <div class="guest-field">
        <label for="login" class="guest-field-label">
            <span>Email or Username <span class="text-danger">*</span></span>
        </label>
        <div class="guest-input-group">
            <i class="bi bi-person-circle guest-input-icon"></i>
            <input
                type="text"
                name="login"
                id="login"
                value="{{ old('login', 'admin@example.com') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="admin@example.com or username"
                class="guest-input-control {{ $errors->has('login') ? 'is-invalid' : '' }}"
            >
        </div>
        @if($errors->has('login'))
            <div class="invalid-feedback d-block small mt-1 fw-semibold text-danger">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first('login') }}
            </div>
        @endif
    </div>

    <!-- Password Input with Reveal Toggle -->
    <div class="guest-field">
        <div class="guest-field-label">
            <span>Password <span class="text-danger">*</span></span>
            <span class="badge bg-light text-muted border font-mono px-2 py-0" style="font-size: 0.7rem;">Default: password</span>
        </div>
        <div class="guest-input-group">
            <i class="bi bi-lock-fill guest-input-icon"></i>
            <input
                type="password"
                name="password"
                id="password"
                value="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="guest-input-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                style="padding-right: 3rem;"
            >
            <button
                type="button"
                id="togglePasswordBtn"
                class="guest-password-toggle"
                aria-label="Toggle password visibility"
            >
                <i class="bi bi-eye fs-5" id="togglePasswordIcon"></i>
            </button>
        </div>
        <div id="capsLockAlert" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill mt-2 py-1 px-2.5 small d-none align-items-center gap-1">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>Caps Lock is ON</span>
        </div>
        @if($errors->has('password'))
            <div class="invalid-feedback d-block small mt-1 fw-semibold text-danger">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first('password') }}
            </div>
        @endif
    </div>

    <!-- Remember Me & Help Links -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check form-switch d-flex align-items-center gap-2 m-0">
            <input class="form-check-input mt-0" type="checkbox" role="switch" id="remember" name="remember" value="1" checked>
            <label class="form-check-label small text-muted user-select-none" for="remember">Remember me</label>
        </div>
        <a href="{{ route('client.access') }}" class="small text-primary text-decoration-none fw-semibold">
            Client Portal <i class="bi bi-arrow-right-short"></i>
        </a>
    </div>

    <!-- Submit Button with Interactive Feedback -->
    <button type="submit" id="loginSubmitBtn" class="guest-submit-btn">
        <i class="bi bi-box-arrow-in-right fs-5" id="submitIcon"></i>
        <span id="submitText">Authorize &amp; Sign In</span>
        <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    </button>
</form>

<!-- Physical Card / NFC Client Token Access Box -->
<div class="guest-alt-box mt-3">
    <div class="d-flex align-items-center justify-content-between gap-2">
        <div class="text-start">
            <div class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                <i class="bi bi-nfc text-primary fs-6"></i>
                <span>Client &amp; NFC Access</span>
            </div>
            <div class="text-muted" style="font-size: 0.74rem;">Have a physical card or access key?</div>
        </div>
        <a href="{{ route('client.access') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
            <span>Enter Code</span>
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const capsLockAlert = document.getElementById('capsLockAlert');
    const quickAdminBtn = document.getElementById('quickFillAdminBtn');
    const quickStaffBtn = document.getElementById('quickFillStaffBtn');

    // Password Visibility Toggle
    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.classList.toggle('bi-eye', !isPassword);
            toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            passwordInput.focus();
        });
    }

    // Caps Lock Warning Detection
    if (passwordInput && capsLockAlert) {
        ['keydown', 'keyup'].forEach(eventType => {
            passwordInput.addEventListener(eventType, function (e) {
                if (e.getModifierState && e.getModifierState('CapsLock')) {
                    capsLockAlert.classList.remove('d-none');
                    capsLockAlert.classList.add('d-inline-flex');
                } else {
                    capsLockAlert.classList.add('d-none');
                    capsLockAlert.classList.remove('d-inline-flex');
                }
            });
        });
    }

    // Quick Fill Demo Credentials Helpers
    function fillCredentials(button, login, password) {
        if (!button || !loginInput || !passwordInput) return;
        
        loginInput.value = login;
        passwordInput.value = password;

        document.querySelectorAll('.quick-fill-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Smooth visual flash on fields
        [loginInput, passwordInput].forEach(field => {
            field.style.transition = 'background-color 0.4s ease';
            field.style.backgroundColor = '#f3f0ff';
            setTimeout(() => {
                field.style.backgroundColor = '#ffffff';
            }, 350);
        });
    }

    if (quickAdminBtn) {
        quickAdminBtn.addEventListener('click', function () {
            fillCredentials(this, this.getAttribute('data-login'), this.getAttribute('data-password'));
        });
    }

    if (quickStaffBtn) {
        quickStaffBtn.addEventListener('click', function () {
            fillCredentials(this, this.getAttribute('data-login'), this.getAttribute('data-password'));
        });
    }

    // Form Submit Feedback
    if (loginForm && submitBtn) {
        loginForm.addEventListener('submit', function () {
            submitBtn.disabled = true;
            if (submitIcon) submitIcon.classList.add('d-none');
            if (submitSpinner) submitSpinner.classList.remove('d-none');
            if (submitText) submitText.textContent = 'Authenticating...';
        });
    }
});
</script>
@endpush
@endsection
