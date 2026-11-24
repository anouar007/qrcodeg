@extends('layouts.app')

@section('title', 'Menu : ' . $menu->title . ' — ' . $menu->brand_name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus Digitaux</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $menu->title }}</li>
@endsection

@push('styles')
<style>
    .menu-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 60%, #eef2ff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }
    .menu-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 0.95rem 1.1rem;
        height: 100%;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }
    .menu-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
    }
    .menu-kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .menu-dish-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        background: #ffffff;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .menu-dish-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
    }
    .menu-dish-thumb {
        width: 56px;
        height: 56px;
        border-radius: 0.65rem;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }
    .menu-qr-box {
        width: 220px;
        max-width: 100%;
    }
    .menu-qr-box svg {
        width: 100% !important;
        height: auto !important;
        display: block;
    }
    @media print {
        body * { visibility: hidden !important; }
        #printableQrCard, #printableQrCard * { visibility: visible !important; }
        #printableQrCard {
            position: fixed;
            inset: 0;
            margin: auto;
            width: 340px;
            height: fit-content;
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
        }
        #printableQrCard .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Executive Hero Header -->
    <div class="menu-hero-card p-3 p-md-4 mb-4">
        <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3">
            <div class="d-flex align-items-start align-items-sm-center gap-3 min-w-0">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-sm btn-light border rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;" title="Retour à la liste">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0 overflow-hidden"
                     style="width: 52px; height: 52px; background: {{ $menu->theme_color ?: '#5736e9' }};">
                    @if($menu->logo_url)
                        <img src="{{ $menu->logo_url }}" alt="{{ $menu->brand_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="bi bi-cup-hot-fill fs-4"></i>
                    @endif
                </div>

                <div class="min-w-0">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h1 class="h4 fw-bold text-dark mb-0 text-truncate">{{ $menu->title }}</h1>
                        <span class="badge rounded-pill {{ $menu->status === 'active' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border' }} px-2.5 py-1">
                            {{ $menu->status === 'active' ? 'En Ligne' : ucfirst($menu->status) }}
                        </span>
                        <span class="badge bg-light text-secondary border rounded-pill text-uppercase" style="font-size: 0.7rem;">
                            {{ str_replace('_', ' ', $menu->brand_type) }}
                        </span>
                    </div>
                    <p class="text-muted small mb-0 text-truncate">
                        <strong class="text-dark">{{ $menu->brand_name }}</strong> &bull;
                        <a href="{{ $publicUrl }}" target="_blank" class="fw-semibold text-primary text-decoration-none">{{ $publicUrl }}</a>
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <form action="{{ route('admin.menus.toggle-status', $menu) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold">
                        <i class="bi {{ $menu->status === 'active' ? 'bi-pause-circle text-warning' : 'bi-play-circle text-success' }} me-1"></i>
                        {{ $menu->status === 'active' ? 'Passer en Brouillon' : 'Publier' }}
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="window.nfcEncoder.writeUrl('{{ $publicUrl }}', 'Menu Digital - {{ addslashes($menu->brand_name) }}')">
                    <i class="bi bi-broadcast"></i>
                    <span>Encoder NFC</span>
                </button>
                <a href="{{ $publicUrl }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1.5">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Voir en Direct</span>
                </a>
                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-primary rounded-pill px-3.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" style="background: #5736e9; border-color: #5736e9;">
                    <i class="bi bi-pencil"></i>
                    <span>Modifier la Carte</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4-Card KPI Strip -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="menu-kpi-card d-flex align-items-center gap-3">
                <div class="menu-kpi-icon bg-primary-subtle text-primary">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Consultations</div>
                    <div class="fs-5 fw-extrabold text-dark lh-1 mt-1">{{ number_format($menu->views_count) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="menu-kpi-card d-flex align-items-center gap-3">
                <div class="menu-kpi-icon bg-info-subtle text-info">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Catégories</div>
                    <div class="fs-5 fw-extrabold text-dark lh-1 mt-1">{{ $menu->total_categories_count }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="menu-kpi-card d-flex align-items-center gap-3">
                <div class="menu-kpi-icon bg-success-subtle text-success">
                    <i class="bi bi-tag-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Articles / Plats</div>
                    <div class="fs-5 fw-extrabold text-dark lh-1 mt-1">{{ $menu->total_items_count }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="menu-kpi-card d-flex align-items-center gap-3">
                <div class="menu-kpi-icon bg-warning-subtle text-warning">
                    <i class="bi bi-layout-text-window"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-muted small fw-semibold">Navigation</div>
                    <div class="fw-bold text-dark small text-truncate mt-1">{{ str_replace('_', ' ', ucfirst($menu->nav_style ?? 'vertical_scroll')) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: QR Code & Brand details -->
        <div class="col-12 col-lg-4">
            <!-- Table QR Code Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4" id="printableQrCard">
                <h5 class="fw-bold text-dark mb-1">QR Code Table</h5>
                <p class="text-muted small mb-3">À imprimer et poser sur les tables ou le comptoir.</p>

                <div class="p-3 bg-light rounded-4 d-inline-block mx-auto mb-3 border mw-100">
                    <div class="menu-qr-box mx-auto">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <div class="fw-bold text-dark small mb-2">{{ $menu->brand_name }} &mdash; {{ $menu->title }}</div>

                <div class="d-flex justify-content-center gap-2 mb-3 no-print">
                    <a href="{{ route('admin.menus.download-qr', ['menu' => $menu, 'format' => 'svg']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-download me-1"></i>SVG
                    </a>
                    <a href="{{ route('admin.menus.download-qr', ['menu' => $menu, 'format' => 'png']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-image me-1"></i>PNG
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i>Imprimer
                    </button>
                </div>

                <div class="input-group input-group-sm no-print">
                    <input type="text" class="form-control font-mono" id="menuUrlInput" value="{{ $publicUrl }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="copyMenuUrlBtn" onclick="copyMenuPublicUrl(this)">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>

            <!-- Establishment Info -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-1.5"></i>Informations Établissement</h6>

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Client / Propriétaire</span>
                    @if($menu->client)
                        <a href="{{ route('admin.clients.show', $menu->client) }}" class="fw-semibold text-primary text-decoration-none">
                            {{ $menu->client->company_name }}
                        </a>
                    @else
                        <span class="text-muted small">Non assigné</span>
                    @endif
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Devise</span>
                    <span class="badge bg-light text-dark border font-mono">{{ $menu->currency }}</span>
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Services de Table</span>
                    <span class="badge {{ ($menu->table_service_enabled ?? true) ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                        {{ ($menu->table_service_enabled ?? true) ? 'Actifs (Appel & Addition)' : 'Désactivés' }}
                    </span>
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Couleurs Éléments</span>
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="d-inline-block rounded-circle border" style="width: 14px; height: 14px; background: {{ $menu->item_bg_color ?? '#ffffff' }};" title="Fond Plats"></span>
                        <span class="d-inline-block rounded-circle border" style="width: 14px; height: 14px; background: {{ $menu->service_bg_color ?? '#ffffff' }};" title="Fond Services"></span>
                        <span class="badge text-white px-2 py-0.5 rounded-pill" style="background: #25D366; font-size: 0.65rem;" title="Bouton WhatsApp officiel">WA Vert</span>
                    </div>
                </div>

                @if($menu->whatsapp)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Commande WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $menu->whatsapp) }}" target="_blank" class="fw-semibold text-success text-decoration-none">
                            <i class="bi bi-whatsapp me-1"></i>{{ $menu->whatsapp }}
                        </a>
                    </div>
                @endif

                @if($menu->phone)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Téléphone</span>
                        <span class="text-dark fw-semibold small">{{ $menu->phone }}</span>
                    </div>
                @endif

                @if($menu->wifi_ssid)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">WiFi</span>
                        <span class="font-mono text-dark small">{{ $menu->wifi_ssid }} ({{ $menu->wifi_password }})</span>
                    </div>
                @endif

                @if($menu->address)
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Adresse</span>
                        <span class="text-dark text-end small" style="max-width: 190px;">{{ $menu->address }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Menu Categories & Dishes -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom pb-3 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Carte &amp; Plats Enregistrés</h5>
                        <small class="text-muted">{{ $menu->total_categories_count }} Catégories &bull; {{ $menu->total_items_count }} Articles au total</small>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        @if(is_array($menu->categories) && count($menu->categories) > 0)
                            <div class="input-group input-group-sm" style="max-width: 220px;">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Rechercher un plat..." oninput="filterMenuDishes(this.value)">
                            </div>
                        @endif
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            <i class="bi bi-pencil me-1"></i>Modifier les articles
                        </a>
                    </div>
                </div>

                @if(!is_array($menu->categories) || count($menu->categories) === 0)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-50"></i>
                        <div class="fw-semibold text-dark mb-1">Aucun article dans ce menu</div>
                        <p class="small mb-3">Cliquez sur "Modifier les articles" pour composer votre carte.</p>
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-primary rounded-pill px-4">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter des plats
                        </a>
                    </div>
                @else
                    <div class="d-flex flex-column gap-4" id="menuCategoriesContainer">
                        @foreach($menu->categories as $category)
                            <div class="border rounded-4 p-3 p-md-3.5 bg-light-subtle menu-category-block">
                                <div class="d-flex align-items-center justify-content-between border-bottom pb-2.5 mb-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $category['name'] ?? 'Catégorie' }}</h6>
                                        @if(!empty($category['description']))
                                            <small class="text-muted">{{ $category['description'] }}</small>
                                        @endif
                                    </div>
                                    <span class="badge bg-white text-secondary border rounded-pill px-2.5">
                                        {{ isset($category['items']) ? count($category['items']) : 0 }} plat(s)
                                    </span>
                                </div>

                                <div class="row g-3">
                                    @if(isset($category['items']) && is_array($category['items']))
                                        @foreach($category['items'] as $item)
                                            @php
                                                $itemName = $item['name'] ?? 'Plat';
                                                $itemDesc = $item['description'] ?? '';
                                                $itemThumb = $item['image_url'] ?? ($item['image'] ?? null);
                                            @endphp
                                            <div class="col-12 col-md-6 menu-dish-col" data-dish-search="{{ strtolower($itemName . ' ' . $itemDesc . ' ' . ($category['name'] ?? '')) }}">
                                                <div class="menu-dish-card p-3 h-100 d-flex flex-column justify-content-between">
                                                    <div class="d-flex align-items-start gap-3">
                                                        @if($itemThumb)
                                                            <img src="{{ $itemThumb }}" alt="{{ $itemName }}" class="menu-dish-thumb">
                                                        @endif
                                                        <div class="min-w-0 flex-grow-1">
                                                            <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                                                <div class="fw-bold text-dark">{{ $itemName }}</div>
                                                                <span class="badge bg-primary-subtle text-primary font-mono text-nowrap px-2 py-1">
                                                                    {{ number_format((float) ($item['price'] ?? 0), 2) }} {{ $menu->currency }}
                                                                </span>
                                                            </div>

                                                            @if(!empty($itemDesc))
                                                                <p class="text-muted small mb-0">{{ $itemDesc }}</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                                        <div>
                                                            @if(!empty($item['badge']))
                                                                <span class="badge bg-primary-subtle text-primary rounded-pill" style="font-size: 0.68rem;">
                                                                    {{ $item['badge'] }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            @if(($item['is_available'] ?? true) !== false)
                                                                <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.68rem;">En Stock</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 0.68rem;">Épuisé</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyMenuPublicUrl(btn) {
        const input = document.getElementById('menuUrlInput');
        if (!input) return;

        const onCopied = () => {
            const icon = btn.querySelector('i');
            if (!icon) return;
            icon.className = 'bi bi-check2 text-success';
            setTimeout(() => {
                icon.className = 'bi bi-clipboard';
            }, 1500);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(onCopied).catch(() => fallbackCopyInput(input, onCopied));
        } else {
            fallbackCopyInput(input, onCopied);
        }
    }

    function fallbackCopyInput(input, onCopied) {
        input.select();
        input.setSelectionRange(0, 99999);
        try {
            if (document.execCommand('copy') && typeof onCopied === 'function') {
                onCopied();
            }
        } catch (e) {}
    }

    function filterMenuDishes(query) {
        const q = (query || '').trim().toLowerCase();
        document.querySelectorAll('.menu-category-block').forEach(cat => {
            let visibleCount = 0;
            cat.querySelectorAll('.menu-dish-col').forEach(col => {
                const hay = col.getAttribute('data-dish-search') || '';
                const match = q === '' || hay.includes(q);
                col.classList.toggle('d-none', !match);
                if (match) visibleCount++;
            });
            cat.classList.toggle('d-none', q !== '' && visibleCount === 0);
        });
    }
</script>
@endpush
