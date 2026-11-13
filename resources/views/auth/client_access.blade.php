@extends('layouts.public')

@section('title', 'Connexion Espace Client — YNS Studio')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom p-4 p-md-5 border-0 shadow-xl rounded-4" style="background: #ffffff; border-top: 4px solid #5736e9 !important;">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm text-white" style="width: 64px; height: 64px; background: linear-gradient(135deg, #5736e9 0%, #7e63f5 100%);">
                        <i class="bi bi-person-badge fs-2"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-1">Espace Client</h3>
                    <p class="text-muted small mb-0">Connectez-vous pour gérer vos cartes NFC, menus digitaux et QR codes dynamiques.</p>
                </div>

                <!-- Navigation Tabs: Email/Password vs Access Code -->
                <ul class="nav nav-pills nav-fill p-1 bg-light rounded-pill mb-4 border" id="clientAuthTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill py-2 fw-bold small active" id="email-tab" data-bs-toggle="pill" data-bs-target="#email-pane" type="button" role="tab" style="font-size: 0.82rem;">
                            <i class="bi bi-envelope-fill me-1"></i> Email &amp; Mot de passe
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill py-2 fw-bold small" id="access-code-tab" data-bs-toggle="pill" data-bs-target="#access-code-pane" type="button" role="tab" style="font-size: 0.82rem;">
                            <i class="bi bi-key-fill me-1"></i> Clé d'Accès Produit
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="clientAuthTabsContent">
                    <!-- Tab 1: Email & Password Login -->
                    <div class="tab-pane fade show active" id="email-pane" role="tabpanel">
                        <form method="POST" action="{{ route('client.login.submit') }}" id="clientEmailLoginForm">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-dark small">Login (Email ou Code d'accès)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary border-end-0"><i class="bi bi-person-fill"></i></span>
                                    <input id="email" type="text"
                                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required autofocus
                                        placeholder="email@entreprise.com ou CODE-CLIENT">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1 fw-semibold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold text-dark small">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary border-end-0"><i class="bi bi-lock-fill"></i></span>
                                    <input id="password" type="password"
                                        class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                        name="password"
                                        required
                                        placeholder="••••••••">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePasswordBtn">
                                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1 fw-semibold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" checked>
                                    <label class="form-check-label small text-muted user-select-none" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-in-right fs-5"></i> Se connecter au Dashboard
                            </button>
                        </form>
                    </div>

                    <!-- Tab 2: Access Code Login -->
                    <div class="tab-pane fade" id="access-code-pane" role="tabpanel">
                        <form method="POST" action="{{ route('client.access.verify') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="access_code" class="form-label fw-semibold text-dark small text-uppercase tracking-wider">Clé d'Accès Unique (Carte / Chevalet)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-primary"></i></span>
                                    <input id="access_code" type="text"
                                        class="form-control border-start-0 font-mono fw-bold fs-5 text-uppercase @error('access_code') is-invalid @enderror"
                                        name="access_code"
                                        value="{{ old('access_code', 'X7K9-P2M4-81QZ') }}"
                                        required
                                        placeholder="XXXX-XXXX-XXXX"
                                        style="letter-spacing: 0.1em;">
                                </div>
                                <small class="text-muted mt-1.5 d-block">
                                    Code imprimé au verso de votre carte NFC ou sur votre chevalet connecté.
                                </small>
                                @error('access_code')
                                    <div class="text-danger small mt-1 fw-semibold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-right-circle me-1"></i> Vérifier le Code Produit
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 pt-2 text-center text-muted small">
                    Besoin d'aide pour accéder à votre espace ? <a href="{{ route('contact') }}" class="text-primary fw-semibold">Contactez notre support</a>.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pwdInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && pwdInput && toggleIcon) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = pwdInput.getAttribute('type') === 'password';
            pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.classList.toggle('bi-eye', !isPassword);
            toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            pwdInput.focus();
        });
    }

    // Switch tab automatically if access_code error occurs
    @if($errors->has('access_code'))
        const accessTab = document.getElementById('access-code-tab');
        if (accessTab) {
            bootstrap.Tab.getOrCreateInstance(accessTab).show();
        }
    @endif
});
</script>
@endpush
@endsection
