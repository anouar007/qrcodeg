<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $qr->name }}</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0.75rem 2rem 0.75rem;
        }
        .scan-container {
            width: 100%;
            max-width: 480px;
            margin: auto;
        }
        .scan-card {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .scan-header {
            background: {{ $design->foreground_color ?? '#1e3a8a' }};
            color: #ffffff;
            padding: 2.25rem 1.5rem 1.75rem 1.5rem;
            text-align: center;
            position: relative;
        }
        .scan-body {
            padding: 1.75rem 1.5rem;
        }
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            width: 100%;
            margin-bottom: 0.75rem;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

<div class="scan-container">
    <div class="scan-card">
        <!-- Header Banner -->
        <div class="scan-header">
            @if(!empty($content['logo_url']) || ($qr->client && $qr->client->logo))
                <img src="{{ !empty($content['logo_url']) ? $content['logo_url'] : asset('storage/' . $qr->client->logo) }}"
                     alt="Logo" class="rounded-circle bg-white p-1 shadow-sm mb-3" style="width: 68px; height: 68px; object-fit: contain;">
            @else
                <div class="rounded-circle bg-white text-dark d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-qr-code-scan fs-3" style="color: {{ $design->foreground_color ?? '#1e3a8a' }};"></i>
                </div>
            @endif

            <h3 class="fw-bold mb-1 brand-font">{{ $qr->name }}</h3>
            @if($qr->client)
                <small class="text-white-50 d-block">{{ $qr->client->company_name }}</small>
            @endif
        </div>

        <!-- Body Content Based On Dynamic Type -->
        <div class="scan-body">
            @switch($type)
                {{-- 1. WEBSITE --}}
                @case('website')
                    <div class="text-center py-2">
                        <div class="mb-3 text-muted">
                            {{ $content['description'] ?? 'Tap below to visit the official destination page.' }}
                        </div>
                        <a href="{{ $content['url'] ?? '#' }}" target="_blank" class="action-btn btn btn-primary text-white" style="background-color: {{ $design->foreground_color ?? '#1e3a8a' }};">
                            <i class="bi bi-box-arrow-up-right"></i> Open Link
                        </a>
                    </div>
                    @break

                {{-- 2. CONTACT / VCARD --}}
                @case('contact')
                    <div class="mb-4 text-center">
                        <h4 class="fw-bold text-dark mb-1">{{ $content['name'] ?? $qr->name }}</h4>
                        @if(!empty($content['job_title']) || !empty($content['company']))
                            <div class="text-muted small mb-2">
                                {{ $content['job_title'] ?? '' }} {{ !empty($content['job_title']) && !empty($content['company']) ? 'at' : '' }} {{ $content['company'] ?? '' }}
                            </div>
                        @endif
                        @if(!empty($content['address']))
                            <div class="text-muted small"><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $content['address'] }}</div>
                        @endif
                    </div>

                    <!-- Direct contact triggers -->
                    <div class="d-flex flex-column gap-2 mb-3">
                        <a href="{{ route('qr.vcard', $qr->public_code) }}" class="action-btn btn btn-success text-white shadow-sm">
                            <i class="bi bi-person-plus-fill fs-5"></i> Save to Contacts (.vcf)
                        </a>

                        @if(!empty($content['phone']))
                            <a href="tel:{{ $content['phone'] }}" class="action-btn btn btn-light border text-dark">
                                <i class="bi bi-telephone-fill text-primary"></i> Call {{ $content['phone'] }}
                            </a>
                        @endif

                        @if(!empty($content['email']))
                            <a href="mailto:{{ $content['email'] }}" class="action-btn btn btn-light border text-dark">
                                <i class="bi bi-envelope-fill text-danger"></i> Email {{ $content['email'] }}
                            </a>
                        @endif

                        @if(!empty($content['website']))
                            <a href="{{ $content['website'] }}" target="_blank" class="action-btn btn btn-light border text-dark">
                                <i class="bi bi-globe text-info"></i> Visit Website
                            </a>
                        @endif
                    </div>
                    @break

                {{-- 3. WHATSAPP --}}
                @case('whatsapp')
                    <div class="text-center py-3">
                        <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-whatsapp fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Connect on WhatsApp</h5>
                        <p class="text-muted small mb-4">Start an instant chat with our concierge or support team.</p>

                        @php
                            $waPhone = preg_replace('/[^0-9]/', '', $content['phone'] ?? '');
                            $waMsg = urlencode($content['message'] ?? 'Hello! I scanned your QR code.');
                        @endphp
                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="action-btn btn btn-success text-white fs-6 py-3">
                            <i class="bi bi-whatsapp fs-5"></i> Start WhatsApp Chat
                        </a>
                    </div>
                    @break

                {{-- 4. WIFI CONNECT --}}
                @case('wifi')
                    <div class="text-center py-2">
                        <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-wifi fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Guest WiFi Login</h4>
                        <p class="text-muted small mb-4">Connect easily to the guest network.</p>

                        <div class="p-3 bg-light rounded-4 text-start border mb-4">
                            <div class="mb-2">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">NETWORK NAME (SSID)</small>
                                <span class="fw-bold fs-5 text-dark font-mono">{{ $content['ssid'] ?? 'Guest_WiFi' }}</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">SECURITY</small>
                                <span class="badge bg-secondary font-mono">{{ $content['encryption'] ?? 'WPA/WPA2' }}</span>
                            </div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">PASSWORD</small>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-primary font-mono fs-6" id="wifiPassText">{{ $content['password'] ?? 'None' }}</span>
                                    @if(!empty($content['password']))
                                        <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $content['password'] }}'); alert('Password copied to clipboard!');">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="small text-muted">
                            <i class="bi bi-info-circle me-1"></i> Most modern smartphones automatically offer a "Join Network" prompt.
                        </div>
                    </div>
                    @break

                {{-- 5. BUSINESS PROFILE --}}
                @case('business')
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-1">{{ $content['business_name'] ?? $qr->name }}</h4>
                        @if(!empty($content['description']))
                            <p class="text-muted small mb-3">{{ $content['description'] }}</p>
                        @endif

                        <div class="d-flex flex-column gap-2 small text-secondary p-3 bg-light rounded-3 border">
                            @if(!empty($content['opening_hours']))
                                <div><i class="bi bi-clock me-2 text-primary"></i> <strong>Hours:</strong> {{ $content['opening_hours'] }}</div>
                            @endif
                            @if(!empty($content['address']))
                                <div><i class="bi bi-geo-alt me-2 text-danger"></i> <strong>Address:</strong> {{ $content['address'] }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @if(!empty($content['phone']))
                            <a href="tel:{{ $content['phone'] }}" class="action-btn btn btn-primary text-white">
                                <i class="bi bi-telephone-fill"></i> Call Now ({{ $content['phone'] }})
                            </a>
                        @endif

                        @if(!empty($content['website']))
                            <a href="{{ $content['website'] }}" target="_blank" class="action-btn btn btn-light border text-dark">
                                <i class="bi bi-globe text-primary"></i> Visit Website
                            </a>
                        @endif

                        @if(!empty($content['address']))
                            <a href="https://maps.google.com/?q={{ urlencode($content['address']) }}" target="_blank" class="action-btn btn btn-light border text-dark">
                                <i class="bi bi-map text-success"></i> Open in Maps
                            </a>
                        @endif
                    </div>
                    @break

                {{-- 6. PRODUCT SHOWCASE --}}
                @case('product')
                    <div class="text-center mb-4">
                        @if(!empty($content['image_url']))
                            <img src="{{ $content['image_url'] }}" alt="Product Image" class="img-fluid rounded-4 mb-3 border shadow-sm" style="max-height: 220px; object-fit: cover;">
                        @endif
                        <h4 class="fw-bold text-dark mb-1">{{ $content['product_name'] ?? $qr->name }}</h4>
                        @if(!empty($content['price']))
                            <div class="display-6 fw-bold text-primary my-2">{{ $content['price'] }}</div>
                        @endif
                        @if(!empty($content['description']))
                            <p class="text-muted small text-start p-3 bg-light rounded-3 border">{{ $content['description'] }}</p>
                        @endif
                    </div>

                    @if(!empty($content['website']))
                        <a href="{{ $content['website'] }}" target="_blank" class="action-btn btn btn-primary text-white py-3">
                            <i class="bi bi-cart-fill"></i> Buy or Inquire Online
                        </a>
                    @endif
                    @break

                {{-- 7. SOCIAL MEDIA HUB --}}
                @case('social')
                    <div class="text-center mb-4">
                        <p class="text-muted small">Follow and connect with our social profiles across all platforms:</p>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @if(!empty($content['instagram']))
                            <a href="{{ $content['instagram'] }}" target="_blank" class="action-btn btn text-white" style="background: #E1306C;">
                                <i class="bi bi-instagram fs-5"></i> Instagram
                            </a>
                        @endif
                        @if(!empty($content['tiktok']))
                            <a href="{{ $content['tiktok'] }}" target="_blank" class="action-btn btn text-white bg-dark">
                                <i class="bi bi-tiktok fs-5"></i> TikTok
                            </a>
                        @endif
                        @if(!empty($content['linkedin']))
                            <a href="{{ $content['linkedin'] }}" target="_blank" class="action-btn btn text-white" style="background: #0077b5;">
                                <i class="bi bi-linkedin fs-5"></i> LinkedIn
                            </a>
                        @endif
                        @if(!empty($content['youtube']))
                            <a href="{{ $content['youtube'] }}" target="_blank" class="action-btn btn text-white" style="background: #FF0000;">
                                <i class="bi bi-youtube fs-5"></i> YouTube
                            </a>
                        @endif
                        @if(!empty($content['facebook']))
                            <a href="{{ $content['facebook'] }}" target="_blank" class="action-btn btn text-white" style="background: #1877F2;">
                                <i class="bi bi-facebook fs-5"></i> Facebook
                            </a>
                        @endif
                        @if(!empty($content['x']))
                            <a href="{{ $content['x'] }}" target="_blank" class="action-btn btn text-white bg-black">
                                <i class="bi bi-twitter-x fs-5"></i> X (Twitter)
                            </a>
                        @endif
                    </div>
                    @break

                {{-- 8. CUSTOM LANDING PAGE / MODULAR BLOCKS --}}
                @case('custom_landing')
                @default
                    <div class="mb-4">
                        @if(!empty($content['heading']))
                            <h4 class="fw-bold text-dark mb-2 text-center">{{ $content['heading'] }}</h4>
                        @endif
                        @if(!empty($content['paragraph']))
                            <p class="text-muted text-center small mb-4">{{ $content['paragraph'] }}</p>
                        @endif

                        @if(!empty($content['blocks']) && is_array($content['blocks']))
                            @foreach($content['blocks'] as $block)
                                @if(($block['type'] ?? '') === 'heading')
                                    <h6 class="fw-bold text-dark mt-3 mb-1">{{ $block['text'] ?? '' }}</h6>
                                @elseif(($block['type'] ?? '') === 'paragraph')
                                    <p class="text-muted small mb-2">{{ $block['text'] ?? '' }}</p>
                                @elseif(($block['type'] ?? '') === 'button')
                                    <a href="{{ $block['url'] ?? '#' }}" target="_blank" class="action-btn btn text-white my-2" style="background-color: {{ $block['color'] ?? ($design->foreground_color ?? '#1e3a8a') }};">
                                        {{ $block['text'] ?? 'Click Here' }}
                                    </a>
                                @elseif(($block['type'] ?? '') === 'whatsapp')
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $block['phone'] ?? '') }}" target="_blank" class="action-btn btn btn-success text-white my-2">
                                        <i class="bi bi-whatsapp"></i> {{ $block['text'] ?? 'Chat on WhatsApp' }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    @break
            @endswitch
        </div>

        <!-- Footer watermark -->
        <div class="p-3 bg-light text-center border-top">
            <small class="text-muted" style="font-size: 0.72rem;">
                Powered by <strong class="text-dark">QR Studio</strong> &bull; Dynamic QR Technology
            </small>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
