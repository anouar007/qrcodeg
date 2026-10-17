<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'QR Code Unavailable' }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 p-3">

<div class="card card-custom p-4 p-md-5 text-center shadow-lg bg-white" style="max-width: 440px; width: 100%;">
    <div class="rounded-circle bg-secondary-subtle text-secondary d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 72px; height: 72px;">
        <i class="bi bi-qr-code fs-1"></i>
    </div>

    <h4 class="fw-bold text-dark mb-2">{{ $title ?? 'QR Code Unavailable' }}</h4>
    <p class="text-muted small mb-4">
        {{ $message ?? 'This QR code is currently unavailable. Please check back later or contact the publisher.' }}
    </p>

    <div class="p-3 bg-light rounded-3 text-secondary small border mb-4">
        <i class="bi bi-info-circle me-1"></i> The administrator or owner of this QR code has paused or archived this destination.
    </div>

    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
        <i class="bi bi-house me-1"></i> Return to Homepage
    </a>
</div>

</body>
</html>
