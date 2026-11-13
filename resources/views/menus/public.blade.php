<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $menu->brand_name }} — {{ $menu->title }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-primary: {{ $menu->theme_color ?: '#5736e9' }};
            --brand-light: {{ $menu->theme_color ?: '#5736e9' }}18;
            --brand-dark: #0f172a;
            --menu-bg: {{ $menu->bg_color ?: '#f8fafc' }};
            --item-bg: {{ $menu->item_bg_color ?: '#ffffff' }};
            --item-text: {{ $menu->item_text_color ?: '#1e293b' }};
            --service-bg: {{ $menu->service_bg_color ?: ($menu->item_bg_color ?: '#ffffff') }};
            --service-text: {{ $menu->service_text_color ?: ($menu->item_text_color ?: '#1e293b') }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--menu-bg);
            color: var(--item-text);
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        .menu-container {
            max-width: 580px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--menu-bg);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .brand-header {
            position: relative;
            background: linear-gradient(135deg, var(--brand-primary), #1e1b4b);
            color: white;
            padding: 2.2rem 1.4rem 1.6rem 1.4rem;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
        }

        .brand-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .brand-logo {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            background: white;
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            object-fit: cover;
            flex-shrink: 0;
            overflow: hidden;
        }
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sticky-nav-bar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f1f5f9;
            padding: 0.6rem 0.5rem;
        }

        /* Option 1: Horizontal Pills */
        .category-pills {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 0.2rem 0.8rem;
            margin: 0;
            white-space: nowrap;
        }
        .category-pills::-webkit-scrollbar { display: none; }

        .cat-pill {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            background: #f1f5f9;
            transition: all 0.2s ease;
            flex-shrink: 0;
            cursor: pointer;
        }

        .cat-pill.active, .cat-pill:hover {
            background: var(--brand-primary);
            color: white;
            box-shadow: 0 4px 12px var(--brand-light);
        }

        /* Price Filter Bar */
        .price-filter-chip {
            padding: 0.3rem 0.72rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .price-filter-chip.active {
            background: var(--brand-primary);
            color: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: 0 2px 8px var(--brand-light);
        }

        /* Option 2: Tab Navigation / Pages */
        .category-tabs-bar {
            display: flex;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 4px;
            overflow-x: auto;
            scrollbar-width: none;
            gap: 4px;
        }
        .category-tabs-bar::-webkit-scrollbar { display: none; }

        .cat-tab-btn {
            flex: 1;
            min-width: 105px;
            padding: 0.5rem 0.8rem;
            border-radius: 10px;
            border: none;
            background: transparent;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-align: center;
        }
        .cat-tab-btn .page-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: rgba(100, 116, 139, 0.15);
            color: #475569;
            font-size: 0.68rem;
            font-weight: 800;
            transition: all 0.2s ease;
        }
        .cat-tab-btn.active {
            background: #ffffff;
            color: var(--brand-primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .cat-tab-btn.active .page-pill {
            background: var(--brand-primary);
            color: #ffffff;
        }
        .public-page-hero {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.95));
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 14px;
            padding: 1rem 1.15rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            animation: pubPageFadeIn 0.25s ease-out;
        }
        @keyframes pubPageFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Option 3: Visual Grid Cards */
        .cat-grid-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.75rem 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .cat-grid-card:hover, .cat-grid-card.active {
            border-color: var(--brand-primary);
            background: #f5f3ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--brand-light);
        }

        /* Bottom Floating Category Drawer Trigger */
        .floating-drawer-trigger {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 1030;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 999px;
            padding: 0.65rem 1.15rem;
            font-size: 0.82rem;
            font-weight: 700;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .floating-drawer-trigger:hover {
            transform: scale(1.05);
            color: white;
        }

        /* Bottom Sheet Drawer Modal */
        .bottom-sheet-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .bottom-sheet-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
        .bottom-sheet-content {
            background: #ffffff;
            width: 100%;
            max-width: 580px;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            padding: 1.5rem 1.25rem 2rem 1.25rem;
            max-height: 80vh;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 -10px 40px rgba(0,0,0,0.2);
        }
        .bottom-sheet-overlay.open .bottom-sheet-content {
            transform: translateY(0);
        }

        /* Horizontal Scroll Mode */
        .horizontal-scroll-track {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            gap: 16px;
            padding: 8px 4px 20px 4px;
            scroll-behavior: smooth;
            scrollbar-width: thin;
        }
        .horizontal-category-slide {
            flex: 0 0 calc(100% - 24px);
            max-width: 480px;
            scroll-snap-align: center;
            background: #ffffff;
            border-radius: 20px;
            padding: 1.2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            min-height: 380px;
        }
        @media (min-width: 600px) {
            .horizontal-category-slide {
                flex: 0 0 380px;
            }
        }

        .item-card {
            background: var(--item-bg);
            color: var(--item-text);
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 16px;
            padding: 0.95rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            margin-bottom: 0.85rem;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden; /* Ensures content never leaves the card */
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .item-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }

        .item-info-col {
            min-width: 0;
            flex: 1 1 0%;
            overflow: hidden;
        }

        .item-name-text {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--item-text);
            word-break: break-word;
            overflow-wrap: anywhere;
            line-height: 1.3;
        }

        .item-desc-text {
            font-size: 0.82rem;
            line-height: 1.35;
            color: var(--item-text);
            opacity: 0.82;
            word-break: break-word;
            overflow-wrap: anywhere;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f1f5f9;
        }

        .item-price {
            font-weight: 800;
            color: var(--brand-primary);
            font-size: 1.05rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .badge-special {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .whatsapp-floating {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1040;
            width: 100%;
            max-width: 580px;
            padding: 10px 16px max(14px, env(safe-area-inset-bottom));
            background: linear-gradient(to top, var(--menu-bg) 70%, transparent 100%);
            pointer-events: none;
        }
        .whatsapp-floating > * {
            pointer-events: auto;
        }

        .whatsapp-bottom-spacer {
            height: 110px;
            width: 100%;
            flex-shrink: 0;
            pointer-events: none;
        }

        /* Quick Info Badges in Header */
        .info-badge {
            background: var(--service-bg);
            color: var(--service-text);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
        .info-badge:hover {
            color: var(--service-text);
            opacity: 0.92;
        }

        /* WhatsApp Button - ALWAYS OFFICIAL GREEN */
        .btn-whatsapp-official {
            background-color: #25D366 !important;
            border-color: #25D366 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 18px rgba(37, 211, 102, 0.38) !important;
            transition: transform 0.15s ease, background-color 0.15s ease !important;
        }
        .btn-whatsapp-official:hover, .btn-whatsapp-official:focus {
            background-color: #20ba59 !important;
            border-color: #20ba59 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .search-box {
            background: #f8fafc;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="menu-container">
    <!-- Brand Header -->
    <header class="brand-header" @if($menu->cover_image) style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.5) 0%, rgba(15, 23, 42, 0.92) 100%), url('{{ $menu->cover_image }}') center/cover no-repeat;" @endif>
        <div class="d-flex align-items-center gap-3 mb-2.5">
            @if($menu->logo_url)
                <img src="{{ $menu->logo_url }}" alt="Logo" class="brand-logo">
            @else
                <div class="brand-logo">
                    <i class="bi bi-cup-hot-fill"></i>
                </div>
            @endif

            <div>
                <h1 class="h4 fw-bold mb-0 text-white">{{ $menu->brand_name }}</h1>
                <div class="small opacity-75 text-white mb-1">{{ $menu->title }}</div>
                <span class="badge bg-white text-dark rounded-pill fw-semibold" style="font-size: 0.7rem; opacity: 0.95;">
                    {{ strtoupper(str_replace('_', ' ', $menu->brand_type)) }}
                </span>
            </div>
        </div>

        @if($menu->description)
            <p class="small text-white-50 mb-3" style="line-height: 1.4;">{{ $menu->description }}</p>
        @endif

        <!-- Quick Info Badges (WiFi, Phone, Instagram, Address) -->
        <div class="d-flex flex-wrap gap-2 pt-1" id="menuInfoBadges">
            @if($menu->wifi_ssid)
                <span class="info-badge" title="WiFi">
                    <i class="bi bi-wifi me-1"></i>{{ $menu->wifi_ssid }}@if($menu->wifi_password) ({{ $menu->wifi_password }})@endif
                </span>
            @endif

            @if($menu->phone)
                <a href="tel:{{ $menu->phone }}" class="info-badge text-decoration-none" title="Téléphone">
                    <i class="bi bi-telephone me-1"></i>{{ $menu->phone }}
                </a>
            @endif

            @if($menu->instagram)
                <a href="https://instagram.com/{{ ltrim($menu->instagram, '@') }}" target="_blank" class="info-badge text-decoration-none" title="Instagram">
                    <i class="bi bi-instagram me-1"></i>{{ $menu->instagram }}
                </a>
            @endif

            @if($menu->address)
                <span class="info-badge" title="Adresse">
                    <i class="bi bi-geo-alt me-1"></i>{{ $menu->address }}
                </span>
            @endif
        </div>
    </header>

    @php
        $allPrices = [];
        if (is_array($menu->categories)) {
            foreach ($menu->categories as $c) {
                if (!empty($c['items']) && is_array($c['items'])) {
                    foreach ($c['items'] as $it) {
                        if (isset($it['price']) && is_numeric($it['price']) && (float) $it['price'] > 0) {
                            $allPrices[] = (float) $it['price'];
                        }
                    }
                }
            }
        }
        $maxMenuPrice = count($allPrices) > 0 ? max($allPrices) : 200;
        $step1 = max(10, (int) (ceil(($maxMenuPrice * 0.35) / 5) * 5));
        $step2 = max($step1 + 10, (int) (ceil(($maxMenuPrice * 0.65) / 5) * 5));
        $step3 = max($step2 + 10, (int) (ceil(($maxMenuPrice * 0.90) / 5) * 5));
    @endphp

    <!-- Search Bar & Price Filter -->
    <div class="px-3 pt-3">
        <div class="input-group search-box align-items-center">
            <span class="input-group-text bg-transparent border-0 p-0 me-2 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" id="menuSearch" class="form-control bg-transparent border-0 p-0 text-dark" placeholder="Rechercher un plat, boisson, dessert..." oninput="filterMenu()">
            <button type="button" id="clearSearchBtn" class="btn btn-sm border-0 p-0 px-1 text-muted d-none" onclick="resetMenuFilters()" title="Effacer">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            <button type="button" class="btn btn-sm border-0 p-0 ps-2 text-muted d-flex align-items-center gap-1" onclick="document.getElementById('priceRangeInputs').classList.toggle('d-none')" title="Filtrer par prix">
                <i class="bi bi-sliders"></i>
            </button>
        </div>

        <!-- Quick Price Filter Chips -->
        <div class="d-flex align-items-center gap-1.5 overflow-x-auto pt-2 pb-1" style="scrollbar-width: none;">
            <button type="button" class="price-filter-chip active" data-max="" onclick="setQuickPriceFilter(null, this)">Tous les prix</button>
            <button type="button" class="price-filter-chip" data-max="{{ $step1 }}" onclick="setQuickPriceFilter({{ $step1 }}, this)">&le; {{ $step1 }} {{ $menu->currency }}</button>
            <button type="button" class="price-filter-chip" data-max="{{ $step2 }}" onclick="setQuickPriceFilter({{ $step2 }}, this)">&le; {{ $step2 }} {{ $menu->currency }}</button>
            <button type="button" class="price-filter-chip" data-max="{{ $step3 }}" onclick="setQuickPriceFilter({{ $step3 }}, this)">&le; {{ $step3 }} {{ $menu->currency }}</button>
        </div>

        <!-- Custom Min/Max Price Inputs (Collapsible) -->
        <div id="priceRangeInputs" class="d-none mt-2 p-2.5 rounded-3 bg-light border">
            <div class="row g-2 align-items-center">
                <div class="col-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted" style="font-size: 0.72rem;">Min</span>
                        <input type="number" id="minPriceInput" min="0" step="1" class="form-control font-mono" placeholder="0" oninput="onManualPriceInput()">
                    </div>
                </div>
                <div class="col-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted" style="font-size: 0.72rem;">Max</span>
                        <input type="number" id="maxPriceInput" min="0" step="1" class="form-control font-mono" placeholder="{{ (int) ceil($maxMenuPrice) }}" oninput="onManualPriceInput()">
                    </div>
                </div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 px-1" onclick="resetMenuFilters()" title="Réinitialiser">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 NAVIGATION OPTIONS RENDERER (Vertical scroll, Horizontal scroll, Pages) -->
    @php
        $rawStyle = $menu->nav_style ?? 'vertical_scroll';
        $navStyle = match($rawStyle) {
            'horizontal_scroll', 'grid_drawer' => 'horizontal_scroll',
            'pages', 'tabs' => 'pages',
            default => 'vertical_scroll',
        };
        $hasCategories = is_array($menu->categories) && count($menu->categories) > 0;
    @endphp

    @if($hasCategories)
        <!-- 1. Vertical scroll: Sticky Horizontal Pills Navigation -->
        <div id="navStyleVerticalContainer" class="sticky-nav-bar mt-2 {{ $navStyle === 'vertical_scroll' ? '' : 'd-none' }}">
            <nav class="category-pills">
                <a href="#all" class="cat-pill active" onclick="activateCat(this, 'all'); return false;">Tout</a>
                @foreach($menu->categories as $idx => $cat)
                    <a href="#cat-{{ $idx }}" class="cat-pill" onclick="activateCat(this, 'cat-{{ $idx }}');">
                        {{ $cat['name'] ?? 'Catégorie' }}
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- 2. Horizontal scroll: Top Slide Selector + Snap Controls -->
        <div id="navStyleHorizontalContainer" class="sticky-nav-bar mt-2 {{ $navStyle === 'horizontal_scroll' ? '' : 'd-none' }}">
            <nav class="category-pills">
                @foreach($menu->categories as $idx => $cat)
                    <a href="javascript:void(0)" class="cat-pill {{ $idx === 0 ? 'active' : '' }}" id="horiz-pill-{{ $idx }}" onclick="scrollToHorizontalSlide({{ $idx }});">
                        {{ $cat['name'] ?? 'Catégorie' }}
                    </a>
                @endforeach
            </nav>
            <div class="d-flex align-items-center justify-content-between px-3 py-1 bg-white border-top">
                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-0.5" onclick="scrollHorizontalTrack(-1)">
                    <i class="bi bi-chevron-left me-0.5"></i>Précédent
                </button>
                <span class="small text-muted fw-semibold" id="horizCounterText" style="font-size: 0.72rem;">Rubrique 1 sur {{ count($menu->categories) }}</span>
                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-0.5" onclick="scrollHorizontalTrack(1)">
                    Suivant<i class="bi bi-chevron-right ms-0.5"></i>
                </button>
            </div>
        </div>

        <!-- 3. Pages: Segmented Page Tabs Navigation -->
        <div id="navStylePagesContainer" class="px-3 pt-2 sticky-nav-bar {{ $navStyle === 'pages' ? '' : 'd-none' }}">
            <div class="category-tabs-bar">
                @foreach($menu->categories as $idx => $cat)
                    <button type="button" class="cat-tab-btn {{ $idx === 0 ? 'active' : '' }}" onclick="selectCategoryPage({{ $idx }})">
                        <span class="page-pill">{{ $idx + 1 }}</span>
                        <span class="text-truncate">{{ $cat['name'] ?? 'Catégorie' }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Menu Items Body -->
    <main class="px-3 py-3 flex-grow-1" style="{{ $menu->whatsapp ? 'padding-bottom: 90px !important;' : '' }}">
        @if(!is_array($menu->categories) || count($menu->categories) === 0)
            <div class="text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                Le menu est en cours de préparation.
            </div>
        @else
            @if($navStyle === 'horizontal_scroll')
                <!-- Horizontal Scroll Track (Horizontal Swipe / Snap) -->
                <div class="horizontal-scroll-track" id="horizontalScrollTrack">
                    @foreach($menu->categories as $idx => $cat)
                        <section class="horizontal-category-slide" id="cat-{{ $idx }}" data-cat-idx="{{ $idx }}">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h2 class="h6 fw-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.04em;">
                                    {{ $cat['name'] ?? 'Catégorie' }}
                                </h2>
                                @if(isset($cat['items']) && is_array($cat['items']))
                                    <span class="badge bg-light text-muted small">{{ count($cat['items']) }}</span>
                                @endif
                            </div>

                            @if(!empty($cat['description']))
                                <p class="text-muted small mb-3" style="font-size: 0.8rem;">{{ $cat['description'] }}</p>
                            @endif

                            <div class="items-list flex-grow-1 overflow-y-auto" style="max-height: calc(100vh - 340px);">
                                @if(isset($cat['items']) && is_array($cat['items']))
                                    @foreach($cat['items'] as $item)
                                        <div class="item-card d-flex gap-3 align-items-center {{ (($item['is_available'] ?? true) === false) ? 'opacity-50' : '' }}" data-item-name="{{ strtolower($item['name'] ?? '') }} {{ strtolower($item['description'] ?? '') }}" data-item-price="{{ (float) ($item['price'] ?? 0) }}">
                                            @if(!empty($item['image_url']))
                                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] ?? '' }}" class="item-img" onerror="this.style.display='none'">
                                            @endif

                                            <div class="item-info-col">
                                                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                                    <div class="item-name-text">
                                                        {{ $item['name'] ?? 'Article' }}
                                                    </div>
                                                    <div class="item-price">
                                                        @if(!empty($item['original_price']))
                                                            <span class="text-muted text-decoration-line-through small me-1 fw-normal" style="font-size: 0.8rem;">{{ number_format((float)$item['original_price'], 2) }}</span>
                                                        @endif
                                                        {{ number_format((float) ($item['price'] ?? 0), 2) }} <small style="font-size: 0.72rem;">{{ $menu->currency }}</small>
                                                    </div>
                                                </div>

                                                @if(!empty($item['description']))
                                                    <p class="item-desc-text">
                                                        {{ $item['description'] }}
                                                    </p>
                                                @endif

                                                <div class="d-flex align-items-center gap-2">
                                                    @if(!empty($item['badge']))
                                                        <span class="badge badge-special bg-warning-subtle text-warning-emphasis">
                                                            {{ $item['badge'] }}
                                                        </span>
                                                    @endif

                                                    @if(($item['is_available'] ?? true) === false)
                                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">Victime de son succès</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </section>
                    @endforeach
                </div>
            @else
                <!-- Vertical scroll or Pages container -->
                @foreach($menu->categories as $idx => $cat)
                    <section class="category-section mb-4 {{ ($navStyle === 'pages' && $idx !== 0) ? 'd-none' : '' }}" id="cat-{{ $idx }}" data-cat-idx="{{ $idx }}">
                        @if($navStyle === 'pages')
                            <div class="public-page-hero">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge rounded-pill fw-bold" style="background: rgba(87, 54, 233, 0.12); color: var(--brand-primary); font-size: 0.72rem; letter-spacing: 0.05em; padding: 0.35em 0.8em;">
                                        <i class="bi bi-file-earmark-text me-1"></i>PAGE {{ $idx + 1 }} SUR {{ count($menu->categories) }}
                                    </span>
                                    @if(isset($cat['items']) && is_array($cat['items']))
                                        <span class="badge bg-white border text-muted" style="font-size: 0.72rem; padding: 0.35em 0.7em;">
                                            {{ count($cat['items']) }} article{{ count($cat['items']) > 1 ? 's' : '' }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="h5 fw-bolder text-dark mb-1" style="letter-spacing: -0.01em;">
                                    {{ $cat['name'] ?? 'Catégorie' }}
                                </h2>
                                @if(!empty($cat['description']))
                                    <p class="text-muted small mb-0" style="font-size: 0.85rem; line-height: 1.4;">{{ $cat['description'] }}</p>
                                @endif
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h2 class="h6 fw-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.04em;">
                                    {{ $cat['name'] ?? 'Catégorie' }}
                                </h2>
                                @if(isset($cat['items']) && is_array($cat['items']))
                                    <span class="badge bg-light text-muted small">{{ count($cat['items']) }}</span>
                                @endif
                            </div>

                            @if(!empty($cat['description']))
                                <p class="text-muted small mb-3" style="font-size: 0.8rem;">{{ $cat['description'] }}</p>
                            @endif
                        @endif

                        <div class="items-list">
                            @if(isset($cat['items']) && is_array($cat['items']))
                                @foreach($cat['items'] as $item)
                                    <div class="item-card d-flex gap-3 align-items-center {{ (($item['is_available'] ?? true) === false) ? 'opacity-50' : '' }}" data-item-name="{{ strtolower($item['name'] ?? '') }} {{ strtolower($item['description'] ?? '') }}" data-item-price="{{ (float) ($item['price'] ?? 0) }}">
                                        @if(!empty($item['image_url']))
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] ?? '' }}" class="item-img" onerror="this.style.display='none'">
                                        @endif

                                        <div class="item-info-col">
                                            <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                                <div class="item-name-text">
                                                    {{ $item['name'] ?? 'Article' }}
                                                </div>
                                                <div class="item-price">
                                                    @if(!empty($item['original_price']))
                                                        <span class="text-muted text-decoration-line-through small me-1 fw-normal" style="font-size: 0.8rem;">{{ number_format((float)$item['original_price'], 2) }}</span>
                                                    @endif
                                                    {{ number_format((float) ($item['price'] ?? 0), 2) }} <small style="font-size: 0.72rem;">{{ $menu->currency }}</small>
                                                </div>
                                            </div>

                                            @if(!empty($item['description']))
                                                <p class="item-desc-text">
                                                    {{ $item['description'] }}
                                                </p>
                                            @endif

                                            <div class="d-flex align-items-center gap-2">
                                                @if(!empty($item['badge']))
                                                    <span class="badge badge-special bg-warning-subtle text-warning-emphasis">
                                                        {{ $item['badge'] }}
                                                    </span>
                                                @endif

                                                @if(($item['is_available'] ?? true) === false)
                                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">Victime de son succès</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                @endforeach

                <!-- Pages Pagination Bar (Only active in pages mode) -->
                <div id="pageNavArrows" class="{{ $navStyle === 'pages' ? 'd-flex' : 'd-none' }} align-items-center justify-content-between gap-2 mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" id="publicPrevBtn" onclick="navigatePage(-1)">
                        <i class="bi bi-arrow-left me-1"></i>Page Précédente
                    </button>
                    <span class="text-muted small fw-semibold" id="pageNavCounter">Page 1 sur {{ count($menu->categories) }}</span>
                    <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill text-white" id="publicNextBtn" style="background: var(--brand-primary); border-color: var(--brand-primary);" onclick="navigatePage(1)">
                        <span>Page Suivante</span><i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            @endif
        @endif

        <!-- Empty Search / Price Filter State -->
        <div id="menuNoResults" class="text-center py-5 d-none">
            <i class="bi bi-search fs-1 text-muted opacity-50 d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Aucun article trouvé</h6>
            <p class="text-muted small mb-3">Aucun plat ne correspond à votre recherche ou fourchette de prix.</p>
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="resetMenuFilters()">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser les filtres
            </button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 text-muted small border-top bg-light mt-auto">
        <div>{{ $menu->brand_name }} &bull; {{ $menu->title }}</div>
        <div class="mt-1" style="font-size: 0.72rem;">Créé avec la technologie QR Studio Pro</div>
    </footer>

    <!-- Dedicated Bottom Spacer so WhatsApp Button NEVER Hides the Content -->
    @if($menu->whatsapp)
        <div class="whatsapp-bottom-spacer" aria-hidden="true"></div>

        <!-- Sticky WhatsApp Button (Official WhatsApp Branding #25D366) -->
        <div class="whatsapp-floating">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $menu->whatsapp) }}" target="_blank" class="btn btn-whatsapp-official w-100 py-3 rounded-pill shadow-lg d-flex align-items-center justify-content-center gap-2 text-white fw-bold text-decoration-none">
                <i class="bi bi-whatsapp fs-5"></i>
                <span>Commander via WhatsApp</span>
            </a>
        </div>
    @endif
</div>

<script>
let currentNavStyle = "{{ $navStyle }}";
let activeTabIdx = 0;
const totalCategories = {{ count($menu->categories ?? []) }};

let activeMinPrice = null;
let activeMaxPrice = null;

function setQuickPriceFilter(maxPrice, btn) {
    activeMinPrice = null;
    activeMaxPrice = maxPrice;

    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');
    if (minInput) minInput.value = '';
    if (maxInput) maxInput.value = maxPrice !== null ? maxPrice : '';

    document.querySelectorAll('.price-filter-chip').forEach(c => c.classList.remove('active'));
    if (btn) btn.classList.add('active');

    filterMenu();
}

function onManualPriceInput() {
    const minVal = document.getElementById('minPriceInput')?.value;
    const maxVal = document.getElementById('maxPriceInput')?.value;

    activeMinPrice = (minVal !== '' && minVal !== undefined) ? parseFloat(minVal) : null;
    activeMaxPrice = (maxVal !== '' && maxVal !== undefined) ? parseFloat(maxVal) : null;

    document.querySelectorAll('.price-filter-chip').forEach(c => c.classList.remove('active'));
    if (activeMinPrice === null && activeMaxPrice === null) {
        const allChip = document.querySelector('.price-filter-chip[data-max=""]');
        if (allChip) allChip.classList.add('active');
    }

    filterMenu();
}

function resetMenuFilters() {
    const searchInput = document.getElementById('menuSearch');
    if (searchInput) searchInput.value = '';

    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');
    if (minInput) minInput.value = '';
    if (maxInput) maxInput.value = '';

    activeMinPrice = null;
    activeMaxPrice = null;

    document.querySelectorAll('.price-filter-chip').forEach(c => c.classList.remove('active'));
    const allChip = document.querySelector('.price-filter-chip[data-max=""]');
    if (allChip) allChip.classList.add('active');

    filterMenu();
}

function filterMenu() {
    const q = (document.getElementById('menuSearch')?.value || '').toLowerCase().trim();
    const hasFilter = q !== '' || activeMinPrice !== null || activeMaxPrice !== null;

    const clearBtn = document.getElementById('clearSearchBtn');
    if (clearBtn) {
        clearBtn.classList.toggle('d-none', !hasFilter);
    }

    const items = document.querySelectorAll('.item-card');
    const sections = document.querySelectorAll('.category-section, .horizontal-category-slide');

    // First filter every item card by search text & price range
    let totalVisible = 0;
    items.forEach(card => {
        const text = card.getAttribute('data-item-name') || '';
        const price = parseFloat(card.getAttribute('data-item-price') || '0');

        const matchesText = !q || text.includes(q);
        const matchesMin = activeMinPrice === null || price >= activeMinPrice;
        const matchesMax = activeMaxPrice === null || price <= activeMaxPrice;

        if (matchesText && matchesMin && matchesMax) {
            card.style.setProperty('display', 'flex', 'important');
            totalVisible++;
        } else {
            card.style.setProperty('display', 'none', 'important');
        }
    });

    if (hasFilter) {
        sections.forEach(sec => {
            sec.classList.remove('d-none');
            const visibleItems = Array.from(sec.querySelectorAll('.item-card')).filter(c => c.style.display !== 'none');
            sec.style.display = visibleItems.length === 0 ? 'none' : '';
        });

        const pageArrows = document.getElementById('pageNavArrows');
        if (pageArrows) pageArrows.classList.add('d-none');
    } else {
        sections.forEach(sec => {
            sec.style.display = '';
        });
        if (currentNavStyle === 'pages') {
            selectCategoryPage(activeTabIdx);
            const pageArrows = document.getElementById('pageNavArrows');
            if (pageArrows) {
                pageArrows.classList.remove('d-none');
                pageArrows.classList.add('d-flex');
            }
        }
    }

    const noResults = document.getElementById('menuNoResults');
    if (noResults) {
        noResults.classList.toggle('d-none', !(hasFilter && totalVisible === 0));
    }
}

// 1. Vertical scroll: Scrollspy & Pills
function activateCat(el, targetId) {
    document.querySelectorAll('#navStyleVerticalContainer .cat-pill').forEach(p => p.classList.remove('active'));
    if (el) el.classList.add('active');

    if (targetId === 'all') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        const target = document.getElementById(targetId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

// 2. Horizontal scroll: Slide Navigation
function scrollToHorizontalSlide(idx) {
    activeTabIdx = idx;
    document.querySelectorAll('#navStyleHorizontalContainer .cat-pill').forEach((p, i) => {
        if (i === idx) p.classList.add('active');
        else p.classList.remove('active');
    });

    const counter = document.getElementById('horizCounterText');
    if (counter) counter.textContent = `Rubrique ${idx + 1} sur ${totalCategories}`;

    const slide = document.getElementById('cat-' + idx);
    if (slide) {
        slide.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    }
}

function scrollHorizontalTrack(direction) {
    let next = activeTabIdx + direction;
    if (next < 0) next = totalCategories - 1;
    if (next >= totalCategories) next = 0;
    scrollToHorizontalSlide(next);
}

// 3. Pages: Segmented Page Navigation
function selectCategoryPage(idx) {
    activeTabIdx = idx;
    document.querySelectorAll('.cat-tab-btn').forEach((b, i) => {
        if (i === idx) b.classList.add('active');
        else b.classList.remove('active');
    });

    document.querySelectorAll('.category-section').forEach((sec, i) => {
        if (i === idx) sec.classList.remove('d-none');
        else sec.classList.add('d-none');
    });

    const counter = document.getElementById('pageNavCounter');
    if (counter) counter.textContent = `Page ${idx + 1} sur ${totalCategories}`;

    const prevBtn = document.getElementById('publicPrevBtn');
    const nextBtn = document.getElementById('publicNextBtn');
    if (prevBtn) {
        if (idx === 0) {
            prevBtn.style.opacity = '0.4';
            prevBtn.style.pointerEvents = 'none';
        } else {
            prevBtn.style.opacity = '1';
            prevBtn.style.pointerEvents = 'auto';
        }
    }
    if (nextBtn) {
        if (idx === totalCategories - 1) {
            nextBtn.innerHTML = `<span>Recommencer</span><i class="bi bi-arrow-repeat ms-1"></i>`;
        } else {
            nextBtn.innerHTML = `<span>Page Suivante</span><i class="bi bi-arrow-right ms-1"></i>`;
        }
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function navigatePage(direction) {
    let next = activeTabIdx + direction;
    if (next < 0) next = totalCategories - 1;
    if (next >= totalCategories) next = 0;
    selectCategoryPage(next);
}

document.addEventListener('DOMContentLoaded', function() {
    if (currentNavStyle === 'pages') {
        selectCategoryPage(0);
    } else if (currentNavStyle === 'horizontal_scroll') {
        // Observer to sync active pill as user swipes horizontally
        const slides = document.querySelectorAll('.horizontal-category-slide');
        if ('IntersectionObserver' in window && slides.length > 0) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
                        const idx = parseInt(entry.target.getAttribute('data-cat-idx'), 10);
                        if (!isNaN(idx)) {
                            activeTabIdx = idx;
                            document.querySelectorAll('#navStyleHorizontalContainer .cat-pill').forEach((p, i) => {
                                if (i === idx) p.classList.add('active');
                                else p.classList.remove('active');
                            });
                            const counter = document.getElementById('horizCounterText');
                            if (counter) counter.textContent = `Rubrique ${idx + 1} sur ${totalCategories}`;
                        }
                    }
                });
            }, {
                root: document.getElementById('horizontalScrollTrack'),
                threshold: 0.5
            });
            slides.forEach(slide => observer.observe(slide));
        }
    }
});
</script>

</body>
</html>
