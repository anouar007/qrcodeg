<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $styles = $card->theme_config ?? ($card->style_settings ?? []);
        $font = $styles['font_family'] ?? 'Outfit';
        $cardBgColor = $styles['background_color'] ?? ($styles['bg_color'] ?? '#0f172a');
        $cardPrimaryColor = $styles['primary_color'] ?? '#4f46e5';
        $borderRadius = $styles['border_radius'] ?? '16px';

        $profilePhoto = $card->profile_photo_path ?: $card->profile_image;
        $coverPhoto = $card->cover_photo_path ?: $card->cover_image;

        $menuConfig = $card->menu_config ?? [];
        $menuTitle = $menuConfig['title'] ?? 'Menu & Catalogue';
        $menuSubtitle = $menuConfig['subtitle'] ?? 'Parcourez nos catégories et découvrez nos offres.';
        $currency = $menuConfig['currency'] ?? 'MAD';
        $navigationMode = $menuConfig['navigation_mode'] ?? 'vertical'; // 'vertical' | 'horizontal' | 'catalog'
        $itemLayout = $menuConfig['item_layout'] ?? 'list'; // 'list' | 'grid' | 'compact'
        $designPreset = $menuConfig['design_preset'] ?? 'card_theme'; // 'card_theme' | 'bistro_dark' | 'clean_light' | 'luxury_gold' | 'custom'
        $showSearch = isset($menuConfig['show_search']) ? (bool) $menuConfig['show_search'] : true;
        $showImages = isset($menuConfig['show_images']) ? (bool) $menuConfig['show_images'] : true;

        // Resolve menu visual colors according to design_preset
        $hasCustomAccent = !empty($menuConfig['accent_color']);
        $primaryColor = $hasCustomAccent ? $menuConfig['accent_color'] : $cardPrimaryColor;
        $bgColor = $cardBgColor;
        $textColor = '#ffffff';
        $surfaceColor = 'rgba(255, 255, 255, 0.06)';
        $surfaceBorder = 'rgba(255, 255, 255, 0.11)';
        $mutedText = 'rgba(255, 255, 255, 0.65)';
        $isLightMode = false;

        if ($designPreset === 'bistro_dark') {
            $primaryColor = $hasCustomAccent ? $menuConfig['accent_color'] : '#ea580c';
            $bgColor = '#0c0a09';
            $surfaceColor = 'rgba(255, 255, 255, 0.05)';
            $surfaceBorder = 'rgba(234, 88, 12, 0.22)';
        } elseif ($designPreset === 'clean_light') {
            $primaryColor = $hasCustomAccent ? $menuConfig['accent_color'] : $cardPrimaryColor;
            $bgColor = '#f8fafc';
            $textColor = '#0f172a';
            $surfaceColor = '#ffffff';
            $surfaceBorder = '#e2e8f0';
            $mutedText = '#64748b';
            $isLightMode = true;
        } elseif ($designPreset === 'luxury_gold') {
            $primaryColor = $hasCustomAccent ? $menuConfig['accent_color'] : '#d4af37';
            $bgColor = '#090d16';
            $surfaceColor = 'rgba(212, 175, 55, 0.06)';
            $surfaceBorder = 'rgba(212, 175, 55, 0.25)';
        } elseif ($designPreset === 'custom') {
            $primaryColor = $hasCustomAccent ? $menuConfig['accent_color'] : $cardPrimaryColor;
            $bgColor = $menuConfig['bg_color'] ?? $cardBgColor;
        }

        $categories = array_values($menuConfig['categories'] ?? []);
        $totalCategories = count($categories);
        $totalItems = collect($categories)->sum(fn ($cat) => count($cat['items'] ?? []));
    @endphp

    <title>{{ $menuTitle }} — {{ $card->full_name ?: $card->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;600;700&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --bg-color: {{ $bgColor }};
            --text-color: {{ $textColor }};
            --surface-color: {{ $surfaceColor }};
            --surface-border: {{ $surfaceBorder }};
            --muted-text: {{ $mutedText }};
            --border-radius: {{ $borderRadius }};
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
        }

        .menu-app-container {
            width: 100%;
            max-width: 600px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            background: {{ $isLightMode ? '#f8fafc' : 'rgba(255, 255, 255, 0.015)' }};
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.25);
        }

        /* Top Sticky Navigation Bar */
        .menu-top-bar {
            position: sticky;
            top: 0;
            z-index: 70;
            background: {{ $isLightMode ? 'rgba(255, 255, 255, 0.9)' : 'rgba(15, 23, 42, 0.88)' }};
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--surface-border);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .btn-back-card {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.42rem 0.95rem;
            border-radius: 999px;
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-back-card:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
            transform: translateX(-2px);
        }

        /* Navigation Mode Switcher Pill in Header */
        .nav-mode-switcher {
            display: inline-flex;
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            border-radius: 999px;
            padding: 3px;
            gap: 2px;
        }
        .nav-mode-btn {
            border: none;
            background: transparent;
            color: var(--muted-text);
            border-radius: 999px;
            padding: 0.25rem 0.65rem;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .nav-mode-btn.active {
            background: var(--primary-color);
            color: #ffffff;
        }

        /* Hero Banner */
        .menu-hero {
            position: relative;
            padding: 1.65rem 1.4rem 1.35rem 1.4rem;
            background: linear-gradient(145deg, {{ $primaryColor }}28 0%, {{ $isLightMode ? '#ffffff' : 'rgba(15, 23, 42, 0.95)' }} 100%);
            border-bottom: 1px solid var(--surface-border);
        }

        .brand-mini-avatar {
            width: 52px;
            height: 52px;
            border-radius: 15px;
            object-fit: cover;
            border: 2px solid var(--primary-color);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        }

        /* Search Input */
        .menu-search-wrap {
            position: relative;
            margin-top: 1rem;
        }
        .menu-search-input {
            width: 100%;
            height: 44px;
            border-radius: 14px;
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            color: var(--text-color);
            padding: 0 1rem 0 2.6rem;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }
        .menu-search-input::placeholder {
            color: var(--muted-text);
        }
        .menu-search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px {{ $primaryColor }}33;
        }
        .menu-search-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-text);
            font-size: 0.9rem;
        }

        /* Sticky Category Filter Pills */
        .category-nav-wrap {
            position: sticky;
            top: 57px;
            z-index: 55;
            background: {{ $isLightMode ? 'rgba(255, 255, 255, 0.92)' : 'rgba(15, 23, 42, 0.92)' }};
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 0.7rem 1.25rem;
            border-bottom: 1px solid var(--surface-border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .category-nav-wrap::-webkit-scrollbar { display: none; }

        .cat-pill-btn {
            white-space: nowrap;
            border: 1px solid var(--surface-border);
            background: var(--surface-color);
            color: var(--text-color);
            border-radius: 999px;
            padding: 0.42rem 0.95rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .cat-pill-btn:hover {
            border-color: var(--primary-color);
        }
        .cat-pill-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 4px 12px {{ $primaryColor }}44;
        }

        /* Category Section & Item Layouts */
        .menu-content-body {
            padding: 1.25rem 1.25rem 2.5rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1.65rem;
            flex-grow: 1;
        }

        .menu-category-section {
            transition: opacity 0.22s ease, transform 0.22s ease;
        }

        .menu-category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.85rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--surface-border);
        }

        /* Page Cover - Uses 'Image de Couverture pour cette Page / Catégorie' as the Cover of the Page */
        .page-cover-banner,
        .category-cover-banner {
            position: relative;
            width: 100%;
            min-height: 200px;
            height: 220px;
            border-radius: var(--border-radius);
            overflow: hidden;
            margin-bottom: 1.25rem;
            border: 1px solid var(--surface-border);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.22);
            background: linear-gradient(135deg, {{ $primaryColor }}33 0%, {{ $bgColor }} 100%);
        }
        .page-cover-img,
        .category-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .page-cover-banner:hover .page-cover-img,
        .category-cover-banner:hover .category-cover-img {
            transform: scale(1.025);
        }
        .page-cover-overlay,
        .category-cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.88) 0%, rgba(0, 0, 0, 0.45) 55%, rgba(0, 0, 0, 0.2) 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.1rem 1.25rem;
            color: #ffffff;
        }
        .page-cover-top-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }
        .page-cover-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.75rem;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
        }
        .page-cover-pill.page-number-pill {
            background: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }
        .page-cover-title {
            font-size: 1.28rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            color: #ffffff;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .page-cover-desc {
            color: rgba(255, 255, 255, 0.94);
            font-size: 0.82rem;
            margin-bottom: 0;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.8);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.35;
        }
        .page-cover-fallback {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $isLightMode ? '#334155' : '#0f172a' }} 100%);
            min-height: 140px;
            height: 150px;
        }

        /* Item Layouts & Real Product Card UI */
        .items-layout-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .items-layout-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }
        @media (max-width: 480px) {
            .items-layout-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.65rem;
            }
            .product-card-img-header {
                height: 110px;
            }
            .items-layout-grid .menu-item-card .product-card-body {
                padding: 0.65rem 0.65rem 0.75rem 0.65rem;
            }
            .menu-item-title {
                font-size: 0.84rem;
            }
            .menu-item-desc {
                font-size: 0.72rem;
            }
            .menu-item-price-tag {
                font-size: 0.82rem;
                padding: 0.32rem 0.5rem;
            }
        }
        .items-layout-compact {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* The Product Card Container */
        .menu-item-card {
            background: {{ $isLightMode ? '#ffffff' : 'rgba(255, 255, 255, 0.05)' }};
            border: 1px solid var(--surface-border);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 16px rgba(0, 0, 0, {{ $isLightMode ? '0.06' : '0.2' }});
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .menu-item-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 26px rgba(0, 0, 0, {{ $isLightMode ? '0.12' : '0.35' }});
        }
        .menu-item-card.is-unavailable {
            opacity: 0.55;
        }

        /* Grid Mode: Full Card with Image Header */
        .items-layout-grid .menu-item-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 0;
        }
        .items-layout-grid .menu-item-card .product-card-body {
            padding: 0.85rem 0.95rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        /* List Mode: Card with Generous Padding & Thumb */
        .items-layout-list .menu-item-card {
            padding: 0.95rem 1.1rem;
        }

        /* Compact Mode: Card */
        .items-layout-compact .menu-item-card {
            padding: 0.65rem 0.9rem;
            border-radius: 12px;
        }

        /* Card Image Elements */
        .product-card-img-header {
            width: 100%;
            height: 145px;
            position: relative;
            background: rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }
        .product-card-img-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .menu-item-card:hover .product-card-img-header img {
            transform: scale(1.04);
        }

        .menu-item-thumb {
            width: 82px;
            height: 82px;
            border-radius: 14px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid var(--surface-border);
        }

        /* Floating Badges on Cards */
        .card-floating-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.22rem 0.65rem;
            border-radius: 999px;
            background: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
            z-index: 2;
        }
        .card-floating-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.22rem 0.6rem;
            border-radius: 999px;
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
            z-index: 2;
        }

        .menu-item-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-color);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .menu-item-badge {
            font-size: 0.64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.18rem 0.55rem;
            border-radius: 999px;
            background: {{ $primaryColor }}25;
            color: {{ $isLightMode ? $primaryColor : '#ffffff' }};
            border: 1px solid {{ $primaryColor }}77;
        }

        .menu-item-desc {
            font-size: 0.8rem;
            color: var(--muted-text);
            margin-bottom: 0;
            line-height: 1.45;
        }
        .items-layout-grid .menu-item-desc {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .items-layout-compact .menu-item-desc {
            font-size: 0.74rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 260px;
        }

        .menu-item-price-tag {
            background: {{ $primaryColor }}18;
            border: 1px solid {{ $primaryColor }}55;
            border-radius: 10px;
            padding: 0.38rem 0.75rem;
            font-weight: 800;
            font-size: 0.88rem;
            color: {{ $primaryColor }};
            white-space: nowrap;
            flex-shrink: 0;
            box-shadow: 0 1px 4px {{ $primaryColor }}20;
        }
        .menu-item-price-tag .currency {
            font-size: 0.7rem;
            font-weight: 600;
            opacity: 0.85;
            margin-left: 2px;
        }

        /* Catalog Page Flipper Bar (for navigation_mode === 'catalog') */
        .catalog-pager-bar {
            position: sticky;
            bottom: 14px;
            z-index: 65;
            margin: 0 1.25rem 1rem 1.25rem;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            background: {{ $isLightMode ? 'rgba(15, 23, 42, 0.92)' : 'rgba(15, 23, 42, 0.94)' }};
            color: #ffffff;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .catalog-page-btn {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-radius: 999px;
            padding: 0.38rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .catalog-page-btn:hover:not(:disabled) {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        .catalog-page-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .catalog-page-dots {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .catalog-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .catalog-dot.active {
            width: 20px;
            border-radius: 99px;
            background: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="menu-app-container" id="menuAppContainer" data-nav-mode="{{ $navigationMode }}">
        <!-- Sticky Top Bar -->
        <div class="menu-top-bar">
            <a href="{{ route('digital-cards.public.show', $card->slug) }}" class="btn-back-card">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Retour à la Carte</span>
            </a>

            <!-- Visitor Interactive Navigation Mode Switcher (Vertical / Horizontal / Catalog Pages) -->
            <div class="nav-mode-switcher" title="Mode de navigation">
                <button type="button" class="nav-mode-btn {{ $navigationMode === 'vertical' ? 'active' : '' }}" data-mode="vertical" onclick="switchPublicNavigationMode('vertical')">
                    <i class="fa-solid fa-arrows-up-down"></i> <span class="d-none d-sm-inline">Vertical</span>
                </button>
                <button type="button" class="nav-mode-btn {{ $navigationMode === 'horizontal' ? 'active' : '' }}" data-mode="horizontal" onclick="switchPublicNavigationMode('horizontal')">
                    <i class="fa-solid fa-arrows-left-right"></i> <span class="d-none d-sm-inline">Onglets</span>
                </button>
                <button type="button" class="nav-mode-btn {{ $navigationMode === 'catalog' ? 'active' : '' }}" data-mode="catalog" onclick="switchPublicNavigationMode('catalog')">
                    <i class="fa-solid fa-book-open"></i> <span class="d-none d-sm-inline">Catalogue</span>
                </button>
            </div>
        </div>

        <!-- Menu Hero Header -->
        <div class="menu-hero">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                <div class="d-flex align-items-center gap-3 min-w-0">
                    @if($profilePhoto)
                        <img src="{{ asset('storage/' . $profilePhoto) }}" alt="{{ $card->full_name }}" class="brand-mini-avatar">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($card->full_name ?: ($card->title ?: $card->name)) }}&background={{ substr($primaryColor, 1) }}&color=fff&size=120" alt="{{ $card->full_name }}" class="brand-mini-avatar">
                    @endif
                    <div class="min-w-0">
                        <div class="small text-uppercase fw-bold opacity-75 text-truncate" style="font-size: 0.7rem; letter-spacing: 0.06em;">
                            {{ $card->company ?: ($card->full_name ?: $card->name) }}
                        </div>
                        <h1 class="h4 fw-extrabold mb-0 text-truncate">{{ $menuTitle }}</h1>
                    </div>
                </div>
                <span class="badge rounded-pill font-monospace flex-shrink-0" style="background: var(--surface-color); color: var(--text-color); border: 1px solid var(--surface-border); font-size: 0.72rem;">
                    <i class="fa-solid fa-utensils me-1" style="color: var(--primary-color);"></i>{{ $totalItems }} article{{ $totalItems > 1 ? 's' : '' }}
                </span>
            </div>

            @if($menuSubtitle)
                <p class="small mb-0" style="color: var(--muted-text);">{{ $menuSubtitle }}</p>
            @endif

            <!-- Instant Search Bar -->
            @if($showSearch)
                <div class="menu-search-wrap">
                    <i class="fa-solid fa-magnifying-glass menu-search-icon"></i>
                    <input type="text" id="publicMenuSearch" class="menu-search-input" placeholder="Rechercher un plat, produit, service..." oninput="filterPublicMenu()">
                </div>
            @endif
        </div>

        <!-- Sticky Category Filter Pills (Shown in Vertical & Horizontal modes) -->
        @if($totalCategories > 0)
            <div class="category-nav-wrap" id="publicCategoryTabsBar">
                <button type="button" class="cat-pill-btn" id="catPillAll" onclick="selectPublicCategory('all', this)">
                    <i class="fa-solid fa-layer-group"></i> Tout voir
                </button>
                @foreach($categories as $index => $cat)
                    <button type="button" class="cat-pill-btn" data-cat-index="{{ $index }}" onclick="selectPublicCategory('cat_{{ $index }}', this)">
                        @if(!empty($cat['icon']))
                            <i class="{{ $cat['icon'] }}"></i>
                        @endif
                        <span>{{ $cat['name'] ?? 'Catégorie' }}</span>
                    </button>
                @endforeach
            </div>
        @endif

        <!-- Menu Categories & Items -->
        <div class="menu-content-body" id="publicMenuBody">
            @forelse($categories as $index => $cat)
                <div class="menu-category-section" data-cat-id="cat_{{ $index }}" data-cat-index="{{ $index }}">
                    <!-- Page Cover - The Category Cover Image serves as the Cover of the Page -->
                    <div class="page-cover-banner category-cover-banner {{ empty($cat['cover_image']) ? 'page-cover-fallback' : '' }}">
                        @if(!empty($cat['cover_image']))
                            <img src="{{ $cat['cover_image'] }}" alt="{{ $cat['name'] ?? 'Couverture' }}" class="page-cover-img category-cover-img" loading="lazy">
                        @endif
                        <div class="page-cover-overlay category-cover-overlay">
                            <div class="page-cover-top-meta">
                                <span class="page-cover-pill page-number-pill">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>Page {{ $index + 1 }} / {{ $totalCategories }}</span>
                                </span>
                                <span class="page-cover-pill">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>{{ count($cat['items'] ?? []) }} article{{ count($cat['items'] ?? []) > 1 ? 's' : '' }}</span>
                                </span>
                            </div>
                            <div>
                                <h2 class="page-cover-title">
                                    @if(!empty($cat['icon']))
                                        <i class="{{ $cat['icon'] }}" style="color: var(--primary-color);"></i>
                                    @else
                                        <i class="fa-solid fa-tag" style="color: var(--primary-color);"></i>
                                    @endif
                                    <span>{{ $cat['name'] ?? 'Catégorie' }}</span>
                                </h2>
                                @if(!empty($cat['description']))
                                    <p class="page-cover-desc category-cover-desc">{{ $cat['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="items-layout-{{ $itemLayout }}">
                        @forelse(($cat['items'] ?? []) as $item)
                            @php
                                $isAvailable = !isset($item['available']) || (bool) $item['available'];
                            @endphp
                            @if($itemLayout === 'grid')
                                <div class="menu-item-card {{ !$isAvailable ? 'is-unavailable' : '' }}" data-search="{{ strtolower(($item['name'] ?? '') . ' ' . ($item['description'] ?? '') . ' ' . ($item['badge'] ?? '')) }}">
                                    @if($showImages && !empty($item['image_url']))
                                        <div class="product-card-img-header">
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] ?? 'Photo' }}" loading="lazy" onerror="this.parentElement.style.display='none'">
                                            @if(!empty($item['badge']))
                                                <span class="card-floating-badge">{{ $item['badge'] }}</span>
                                            @endif
                                            @if(!$isAvailable)
                                                <span class="card-floating-stock">Épuisé</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="product-card-body">
                                        <div class="menu-item-title">
                                            <span>{{ $item['name'] ?? 'Article' }}</span>
                                            @if((!$showImages || empty($item['image_url'])) && !empty($item['badge']))
                                                <span class="menu-item-badge">{{ $item['badge'] }}</span>
                                            @endif
                                            @if((!$showImages || empty($item['image_url'])) && !$isAvailable)
                                                <span class="badge bg-danger rounded-pill" style="font-size: 0.62rem;">Épuisé</span>
                                            @endif
                                        </div>
                                        @if(!empty($item['description']))
                                            <p class="menu-item-desc mb-2">{{ $item['description'] }}</p>
                                        @endif

                                        @if(isset($item['price']) && $item['price'] !== '')
                                            <div class="mt-auto pt-2 border-top" style="border-color: var(--surface-border) !important;">
                                                <div class="menu-item-price-tag w-100 text-center">
                                                    {{ $item['price'] }}<span class="currency">{{ $currency }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif($itemLayout === 'compact')
                                <div class="menu-item-card {{ !$isAvailable ? 'is-unavailable' : '' }}" data-search="{{ strtolower(($item['name'] ?? '') . ' ' . ($item['description'] ?? '') . ' ' . ($item['badge'] ?? '')) }}">
                                    <div class="d-flex align-items-center gap-2.5 flex-grow-1 min-w-0">
                                        @if($showImages && !empty($item['image_url']))
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] ?? 'Photo' }}" class="rounded-2 flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover; border: 1px solid var(--surface-border);" onerror="this.style.display='none'">
                                        @endif
                                        <div class="min-w-0 flex-grow-1">
                                            <div class="menu-item-title mb-0">
                                                <span class="text-truncate">{{ $item['name'] ?? 'Article' }}</span>
                                                @if(!empty($item['badge']))
                                                    <span class="menu-item-badge">{{ $item['badge'] }}</span>
                                                @endif
                                                @if(!$isAvailable)
                                                    <span class="badge bg-danger rounded-pill" style="font-size: 0.62rem;">Épuisé</span>
                                                @endif
                                            </div>
                                            @if(!empty($item['description']))
                                                <p class="menu-item-desc text-truncate mb-0">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if(isset($item['price']) && $item['price'] !== '')
                                        <div class="menu-item-price-tag ms-2">
                                            {{ $item['price'] }}<span class="currency">{{ $currency }}</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                {{-- list default: Card with thumbnail and clear structure --}}
                                <div class="menu-item-card {{ !$isAvailable ? 'is-unavailable' : '' }}" data-search="{{ strtolower(($item['name'] ?? '') . ' ' . ($item['description'] ?? '') . ' ' . ($item['badge'] ?? '')) }}">
                                    <div class="d-flex align-items-center gap-3 w-100">
                                        @if($showImages && !empty($item['image_url']))
                                            <div class="position-relative flex-shrink-0">
                                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] ?? 'Photo' }}" class="menu-item-thumb" onerror="this.parentElement.style.display='none'">
                                                @if(!empty($item['badge']))
                                                    <span class="card-floating-badge" style="top: 4px; left: 4px; font-size: 0.58rem; padding: 0.15rem 0.45rem;">{{ $item['badge'] }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="min-w-0 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between gap-2">
                                                <div class="menu-item-title">
                                                    <span>{{ $item['name'] ?? 'Article' }}</span>
                                                    @if((!$showImages || empty($item['image_url'])) && !empty($item['badge']))
                                                        <span class="menu-item-badge">{{ $item['badge'] }}</span>
                                                    @endif
                                                    @if(!$isAvailable)
                                                        <span class="badge bg-danger rounded-pill" style="font-size: 0.62rem;">Épuisé</span>
                                                    @endif
                                                </div>
                                                @if(isset($item['price']) && $item['price'] !== '')
                                                    <div class="menu-item-price-tag">
                                                        {{ $item['price'] }}<span class="currency">{{ $currency }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            @if(!empty($item['description']))
                                                <p class="menu-item-desc mt-1">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="small fst-italic py-2" style="color: var(--muted-text);">Aucun article dans cette catégorie.</div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center py-5 opacity-75">
                    <i class="fa-solid fa-book-open fs-1 mb-3 d-block opacity-50"></i>
                    <h6 class="fw-bold">Menu en cours de préparation</h6>
                    <p class="small opacity-75 mb-3">Les articles de ce catalogue seront bientôt disponibles.</p>
                    <a href="{{ route('digital-cards.public.show', $card->slug) }}" class="btn-back-card">
                        <i class="fa-solid fa-arrow-left"></i> Retour au profil
                    </a>
                </div>
            @endforelse

            <!-- Empty Search Result State -->
            <div id="publicMenuNoResults" class="text-center py-5" style="display: none;">
                <i class="fa-solid fa-magnifying-glass fs-2 mb-2 opacity-50"></i>
                <h6 class="fw-bold mb-1">Aucun résultat trouvé</h6>
                <p class="small mb-0" style="color: var(--muted-text);">Essayez un autre terme de recherche ou changez de catégorie.</p>
            </div>
        </div>

        <!-- Catalog Page Flipper Bar (Interactive Book / Catalog Mode) -->
        @if($totalCategories > 0)
            <div class="catalog-pager-bar" id="catalogPagerBar" style="display: none;">
                <button type="button" class="catalog-page-btn" id="btnPrevCatalogPage" onclick="stepCatalogPage(-1)">
                    <i class="fa-solid fa-chevron-left"></i> Précédent
                </button>
                <div class="d-flex flex-column align-items-center">
                    <div class="small fw-bold font-monospace" style="font-size: 0.74rem;" id="catalogPageIndicator">Page 1 / {{ $totalCategories }}</div>
                    <div class="catalog-page-dots mt-1" id="catalogPageDots">
                        @foreach($categories as $index => $cat)
                            <span class="catalog-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToCatalogPage({{ $index }})"></span>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="catalog-page-btn" id="btnNextCatalogPage" onclick="stepCatalogPage(1)">
                    Suivant <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        @endif

        <!-- Footer -->
        <div class="text-center py-3 border-top small opacity-50 font-monospace" style="border-color: var(--surface-border) !important; font-size: 0.7rem;">
            MENU & CATALOGUE DIGITAL &bull; {{ strtoupper($card->company ?: ($card->full_name ?: $card->name)) }}
        </div>
    </div>

    <script>
        let currentNavMode = @json($navigationMode);
        const totalCategoriesCount = {{ $totalCategories }};
        let activePublicCategory = 'all';
        let currentCatalogPageIndex = 0;

        function initPublicMenuNavigation() {
            switchPublicNavigationMode(currentNavMode);
        }

        function switchPublicNavigationMode(mode) {
            currentNavMode = mode || 'vertical';
            document.querySelectorAll('.nav-mode-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.mode === currentNavMode);
            });

            const pillAll = document.getElementById('catPillAll');
            const pagerBar = document.getElementById('catalogPagerBar');

            if (currentNavMode === 'vertical') {
                if (pillAll) pillAll.style.display = 'inline-flex';
                if (pagerBar) pagerBar.style.display = 'none';
                selectPublicCategory('all', pillAll);
            } else if (currentNavMode === 'horizontal') {
                if (pillAll) pillAll.style.display = 'none';
                if (pagerBar) pagerBar.style.display = 'none';
                const firstCatBtn = document.querySelector('.cat-pill-btn[data-cat-index="0"]');
                selectPublicCategory('cat_0', firstCatBtn);
            } else if (currentNavMode === 'catalog') {
                if (pillAll) pillAll.style.display = 'none';
                if (pagerBar) pagerBar.style.display = 'flex';
                goToCatalogPage(currentCatalogPageIndex);
            }
        }

        function selectPublicCategory(catId, btn) {
            activePublicCategory = catId;
            document.querySelectorAll('.cat-pill-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            if (catId.startsWith('cat_')) {
                currentCatalogPageIndex = parseInt(catId.replace('cat_', ''), 10) || 0;
                updateCatalogPagerUI();
            }
            filterPublicMenu();
        }

        function goToCatalogPage(pageIndex) {
            if (totalCategoriesCount === 0) return;
            currentCatalogPageIndex = Math.max(0, Math.min(totalCategoriesCount - 1, pageIndex));
            activePublicCategory = 'cat_' + currentCatalogPageIndex;

            const matchingPill = document.querySelector(`.cat-pill-btn[data-cat-index="${currentCatalogPageIndex}"]`);
            document.querySelectorAll('.cat-pill-btn').forEach(b => b.classList.remove('active'));
            if (matchingPill) matchingPill.classList.add('active');

            updateCatalogPagerUI();
            filterPublicMenu();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function stepCatalogPage(delta) {
            goToCatalogPage(currentCatalogPageIndex + delta);
        }

        function updateCatalogPagerUI() {
            const indicator = document.getElementById('catalogPageIndicator');
            const prevBtn = document.getElementById('btnPrevCatalogPage');
            const nextBtn = document.getElementById('btnNextCatalogPage');

            if (indicator) {
                indicator.textContent = `Page ${currentCatalogPageIndex + 1} / ${totalCategoriesCount}`;
            }
            if (prevBtn) prevBtn.disabled = (currentCatalogPageIndex <= 0);
            if (nextBtn) nextBtn.disabled = (currentCatalogPageIndex >= totalCategoriesCount - 1);

            document.querySelectorAll('#catalogPageDots .catalog-dot').forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentCatalogPageIndex);
            });
        }

        function filterPublicMenu() {
            const query = (document.getElementById('publicMenuSearch')?.value || '').toLowerCase().trim();
            let totalVisibleItems = 0;

            document.querySelectorAll('.menu-category-section').forEach(section => {
                const catId = section.dataset.catId;
                const matchesCat = (query.length > 0) ? true : (activePublicCategory === 'all' || activePublicCategory === catId);

                if (!matchesCat) {
                    section.style.display = 'none';
                    return;
                }

                let sectionVisibleItems = 0;
                section.querySelectorAll('.menu-item-card').forEach(item => {
                    const searchStr = item.dataset.search || '';
                    const matchesQuery = (!query || searchStr.includes(query));
                    item.style.display = matchesQuery ? 'flex' : 'none';
                    if (matchesQuery) {
                        sectionVisibleItems++;
                        totalVisibleItems++;
                    }
                });

                section.style.display = sectionVisibleItems > 0 ? 'block' : 'none';
            });

            const noRes = document.getElementById('publicMenuNoResults');
            if (noRes) {
                noRes.style.display = totalVisibleItems === 0 ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', initPublicMenuNavigation);
    </script>
</body>
</html>
