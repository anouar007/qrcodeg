<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $card->full_name ?: $card->name }} | Carte Visite Digitale</title>
    @if($card->bio)
        <meta name="description" content="{{ Str::limit($card->bio, 150) }}">
    @endif

    <!-- OpenGraph Meta Tags for Rich Sharing -->
    <meta property="og:title" content="{{ $card->full_name ?: $card->name }} - {{ $card->job_title ?? 'Carte Digitale' }}">
    <meta property="og:description" content="{{ $card->bio ?? ($card->company ? $card->company.' - Contact connecté' : 'Carte de visite digitale connectée') }}">
    @if($card->profile_image)
        <meta property="og:image" content="{{ asset('storage/' . $card->profile_image) }}">
    @endif
    <meta property="og:url" content="{{ $card->public_url }}">
    <meta property="og:type" content="profile">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;600;700&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 Free (2,163 icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @php
        $styles = $card->theme_config ?? ($card->style_settings ?? []);
        $layout = $card->layout_settings ?? [];
        $font = $styles['font_family'] ?? 'Outfit';
        $bgColor = $styles['background_color'] ?? ($styles['bg_color'] ?? '#0f172a');
        $primaryColor = $styles['primary_color'] ?? '#4f46e5';
        $textColor = $styles['text_color'] ?? '#ffffff';
        $cardBg = $styles['card_bg'] ?? '#1e293b';
        $borderRadius = $styles['border_radius'] ?? '16px';
        $shadow = $styles['shadow_style'] ?? 'soft';
        $bgStyle = $styles['bg_style'] ?? 'solid';
        $elementSpacing = $styles['element_spacing'] ?? '14px';

        $profilePhoto = $card->profile_photo_path ?: $card->profile_image;
        $coverPhoto = $card->cover_photo_path ?: $card->cover_image;

        $avatarAlign = $layout['avatar_align'] ?? 'center';
        $avatarShape = $layout['avatar_shape'] ?? 'circle';
        $showCover = $layout['show_cover'] ?? true;
        $showBadge = $layout['show_badge'] ?? true;
        $showVcard = $layout['show_vcard_button'] ?? true;

        $shadowMap = [
            'soft' => '0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.2)',
            'deep' => '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
            'glow' => '0 0 20px ' . $primaryColor . '44',
            'flat' => 'none'
        ];
        $boxShadowCss = $shadowMap[$shadow] ?? $shadowMap['soft'];

        $avatarShapeRadius = '50%';
        if ($avatarShape === 'rounded') $avatarShapeRadius = '16px';
        if ($avatarShape === 'squircle') $avatarShapeRadius = '28%';
    @endphp

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --bg-color: {{ $bgColor }};
            --text-color: {{ $textColor }};
            --card-bg: {{ $cardBg }};
            --border-radius: {{ $borderRadius }};
            --box-shadow: {{ $boxShadowCss }};
        }

        body {
            font-family: '{{ $font }}', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            @if($bgStyle === 'gradient')
                background: linear-gradient(145deg, {{ $bgColor }} 0%, #030712 100%);
            @elseif($bgStyle === 'dots')
                background-color: {{ $bgColor }};
                background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
                background-size: 20px 20px;
            @elseif($bgStyle === 'mesh')
                background: radial-gradient(circle at 15% 20%, {{ $primaryColor }}33 0%, transparent 40%),
                            radial-gradient(circle at 85% 80%, #38bdf822 0%, transparent 40%),
                            {{ $bgColor }};
            @endif
        }

        .card-container {
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            background: {{ $card->template === 'glassmorphism' ? 'rgba(255, 255, 255, 0.04)' : 'transparent' }};
            @if($card->template === 'glassmorphism')
                backdrop-filter: blur(20px);
                border-left: 1px solid rgba(255, 255, 255, 0.08);
                border-right: 1px solid rgba(255, 255, 255, 0.08);
            @endif
        }

        .cover-banner {
            width: 100%;
            height: 170px;
            position: relative;
            background-size: cover;
            background-position: center;
            @if($coverPhoto)
                background-image: url('{{ asset("storage/" . $coverPhoto) }}');
            @else
                background: linear-gradient(135deg, {{ $primaryColor }} 0%, #1e1b4b 100%);
            @endif
        }

        .cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.6) 100%);
        }

        .profile-section {
            padding: 0 1.5rem 1rem 1.5rem;
            position: relative;
            margin-top: {{ $showCover ? '-60px' : '2rem' }};
            text-align: {{ $avatarAlign }};
        }

        .profile-avatar-wrap {
            display: inline-block;
            position: relative;
            margin-bottom: 0.75rem;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: {{ $avatarShapeRadius }};
            border: 4px solid var(--card-bg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            background-color: var(--card-bg);
        }

        .badge-pill {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            background: {{ $primaryColor }}22;
            color: {{ $primaryColor }};
            border: 1px solid {{ $primaryColor }}44;
            margin-bottom: 0.5rem;
        }

        .profile-name {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
            line-height: 1.2;
            color: var(--text-color);
        }

        .profile-job {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 0.15rem;
        }

        .profile-company {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .profile-bio {
            font-size: 0.9rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.7);
            margin: 0.75rem 0 1.25rem 0;
            white-space: pre-line;
        }

        /* Action Buttons */
        .btn-vcard-save {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
            font-weight: 700;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 8px 20px -4px var(--primary-color);
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-vcard-save:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            color: #ffffff;
        }

        /* Links List */
        .links-wrapper {
            padding: 0 1.5rem 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .custom-link-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.9rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .custom-link-card:hover {
            transform: translateY(-3px) scale(1.01);
            border-color: var(--primary-color);
            color: var(--text-color);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }

        .link-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
            color: var(--primary-color);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .link-content {
            flex-grow: 1;
            padding: 0 0.85rem;
            overflow: hidden;
        }

        .link-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .link-subtitle {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.55);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .share-bar {
            padding: 1.5rem;
            margin-top: auto;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .powered-by {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="card-container">
        {{-- Cover Banner --}}
        @if($showCover)
            <div class="cover-banner">
                <div class="cover-overlay"></div>
            </div>
        @endif

        {{-- Profile Information --}}
        <div class="profile-section">
            <div class="profile-avatar-wrap">
                @if($profilePhoto)
                    <img src="{{ asset('storage/' . $profilePhoto) }}" alt="{{ $card->full_name }}" class="profile-avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($card->full_name ?: ($card->title ?: $card->name)) }}&background={{ substr($primaryColor, 1) }}&color=fff&size=150" alt="{{ $card->full_name }}" class="profile-avatar">
                @endif
            </div>

            @if($showBadge && $card->badge_text)
                <div>
                    <span class="badge-pill">{{ $card->badge_text }}</span>
                </div>
            @endif

            <h1 class="profile-name">{{ $card->full_name ?: $card->name }}</h1>

            @if($card->job_title)
                <div class="profile-job">{{ $card->job_title }}</div>
            @endif

            @if($card->company)
                <div class="profile-company">{{ $card->company }}</div>
            @endif

            @if($card->bio)
                <p class="profile-bio">{{ $card->bio }}</p>
            @endif

            {{-- vCard Save Contact Button --}}
            @if($showVcard)
                <div class="mt-3">
                    <a href="{{ route('digital-cards.public.vcard', $card->slug) }}" class="btn-vcard-save w-100">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                        <span>Enregistrer dans mes Contacts</span>
                    </a>
                </div>
            @endif
        </div>

        @php
            $displayBlocks = $card->content_blocks ?: ($card->links ?: []);
        @endphp

        {{-- Custom Links List --}}
        <div class="links-wrapper" style="gap: {{ $elementSpacing }};">
            @if(is_array($displayBlocks) && count($displayBlocks) > 0)
                @foreach($displayBlocks as $link)
                    @php
                        $type = $link['type'] ?? 'custom';
                        $val = $link['url'] ?? ($link['value'] ?? '#');
                        $title = $link['label'] ?? ($link['title'] ?? 'Lien');
                        $desc = $link['description'] ?? '';

                        // Format target URL based on type
                        $url = $val;
                        $target = '_blank';
                        $iconClass = 'bi bi-link-45deg';

                        if ($type === 'phone') {
                            $url = 'tel:' . preg_replace('/[^0-9+]/', '', $val);
                            $target = '_self';
                            $iconClass = 'bi bi-telephone-fill';
                        } elseif ($type === 'whatsapp') {
                            $cleanPhone = preg_replace('/[^0-9]/', '', $val);
                            $url = 'https://wa.me/' . $cleanPhone;
                            $iconClass = 'bi bi-whatsapp';
                        } elseif ($type === 'email') {
                            $url = 'mailto:' . $val;
                            $target = '_self';
                            $iconClass = 'bi bi-envelope-fill';
                        } elseif ($type === 'instagram') {
                            $user = ltrim($val, '@');
                            $url = str_starts_with($val, 'http') ? $val : 'https://instagram.com/' . $user;
                            $iconClass = 'bi bi-instagram';
                        } elseif ($type === 'linkedin') {
                            $url = str_starts_with($val, 'http') ? $val : 'https://linkedin.com/in/' . $val;
                            $iconClass = 'bi bi-linkedin';
                        } elseif ($type === 'location') {
                            $url = str_starts_with($val, 'http') ? $val : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($val);
                            $iconClass = 'bi bi-geo-alt-fill';
                        } elseif ($type === 'facebook') {
                            $url = str_starts_with($val, 'http') ? $val : 'https://facebook.com/' . $val;
                            $iconClass = 'bi bi-facebook';
                        } elseif ($type === 'tiktok') {
                            $url = str_starts_with($val, 'http') ? $val : 'https://tiktok.com/@' . ltrim($val, '@');
                            $iconClass = 'bi bi-tiktok';
                        } elseif ($type === 'youtube') {
                            $url = str_starts_with($val, 'http') ? $val : 'https://youtube.com/' . $val;
                            $iconClass = 'bi bi-youtube';
                        } elseif ($type === 'website') {
                            if (!str_starts_with($val, 'http')) $url = 'https://' . $val;
                            $iconClass = 'bi bi-globe';
                        } else {
                            if (!str_starts_with($val, 'http') && !str_starts_with($val, 'mailto:') && !str_starts_with($val, 'tel:')) {
                                $url = 'https://' . $val;
                            }
                        }

                        if (!empty($link['icon'])) {
                            $rawIcon = trim($link['icon']);
                            if (str_contains($rawIcon, 'fa-') || str_starts_with($rawIcon, 'fas ') || str_starts_with($rawIcon, 'fab ') || str_starts_with($rawIcon, 'far ')) {
                                $iconClass = $rawIcon;
                            } else {
                                $iconClass = str_starts_with($rawIcon, 'bi ') ? $rawIcon : 'bi ' . $rawIcon;
                            }
                        }
                    @endphp

                    <a href="{{ $url }}" target="{{ $target }}" rel="noopener noreferrer" class="custom-link-card">
                        <div class="link-icon-wrap">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <div class="link-content">
                            <div class="link-title">{{ $title }}</div>
                            @if($desc)
                                <div class="link-subtitle">{{ $desc }}</div>
                            @endif
                        </div>
                        <i class="bi bi-chevron-right opacity-50"></i>
                    </a>
                @endforeach
            @else
                <div class="text-center py-4 text-white-50">
                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                    <p class="small mb-0">Aucun lien additionnel configuré.</p>
                </div>
            @endif
        </div>

        {{-- Share & Footer Bar --}}
        <div class="share-bar">
            <div class="d-flex justify-content-center gap-2 mb-3">
                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="shareCard()">
                    <i class="bi bi-share-fill me-1"></i> Partager
                </button>
                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="copyCardLink()">
                    <i class="bi bi-link-45deg me-1"></i> Copier le Lien
                </button>
            </div>
            <div class="powered-by">
                PROPULSÉ PAR <strong class="text-white">QR STUDIO</strong> &bull; CARTE CONNECTÉE
            </div>
        </div>
    </div>

    <!-- Toast for feedback -->
    <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 1080">
        <div id="copyToast" class="toast align-items-center text-bg-dark border-0 rounded-pill px-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body small py-2">
                    <i class="bi bi-check2-circle text-success me-1"></i> Lien copié dans le presse-papier !
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyCardLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const toastEl = document.getElementById('copyToast');
                const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
                toast.show();
            });
        }

        function shareCard() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($card->full_name ?: $card->name) }}',
                    text: 'Découvrez la carte de visite digitale de {{ addslashes($card->full_name ?: $card->name) }}',
                    url: window.location.href
                }).catch(() => {});
            } else {
                copyCardLink();
            }
        }
    </script>
</body>
</html>
