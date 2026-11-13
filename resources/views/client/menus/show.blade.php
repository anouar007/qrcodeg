@extends('layouts.client')

@section('title', 'Menu : ' . $menu->title)
@section('page_title', $menu->title)
@section('page_subtitle', 'QR Code de table & consultation de votre carte')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('client.menus.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle p-1" style="width: 32px; height: 32px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold text-dark mb-0">{{ $menu->title }}</h4>
                <span class="badge {{ $menu->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                    {{ $menu->status === 'active' ? 'En Ligne' : 'Brouillon' }}
                </span>
            </div>
            <p class="text-muted small mb-0 ms-5">{{ $menu->brand_name }} &bull; Lien : <a href="{{ $publicUrl }}" target="_blank" class="fw-semibold text-primary">{{ $publicUrl }}</a></p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ $publicUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Voir sur Mobile</span>
            </a>
            <a href="{{ route('client.menus.edit', $menu) }}" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-pencil"></i>
                <span>Modifier la Carte</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <!-- QR Code Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4">
                <h5 class="fw-bold text-dark mb-1">QR Code de Table</h5>
                <p class="text-muted small mb-3">Téléchargez le QR code pour vos chevalets de table, menus papier ou vitrine.</p>

                <div class="p-3 bg-light rounded-3 d-inline-block mx-auto mb-3 border">
                    <div style="width: 220px; height: 220px;" class="mx-auto">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <a href="{{ route('client.menus.download-qr', ['menu' => $menu, 'format' => 'svg']) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>SVG
                    </a>
                    <a href="{{ route('client.menus.download-qr', ['menu' => $menu, 'format' => 'png']) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-image me-1"></i>PNG
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i>Imprimer
                    </button>
                </div>

                <div class="input-group input-group-sm">
                    <input type="text" class="form-control font-mono" id="menuUrlInput" value="{{ $publicUrl }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('menuUrlInput').value); alert('Lien copié !');">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>

            <!-- Stats & Quick info -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold text-dark mb-3">Statistiques &amp; Infos</h6>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Consultations</span>
                    <span class="fw-bold text-dark">{{ number_format($menu->views_count) }} vues</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Devise</span>
                    <span class="badge bg-light text-dark font-mono">{{ $menu->currency }}</span>
                </div>
                @if($menu->whatsapp)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Commandes WhatsApp</span>
                        <span class="text-success fw-semibold">{{ $menu->whatsapp }}</span>
                    </div>
                @endif
                @if($menu->wifi_ssid)
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">WiFi</span>
                        <span class="font-mono text-dark">{{ $menu->wifi_ssid }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Plats &amp; Boissons Enregistrés</h5>
                        <small class="text-muted">{{ $menu->total_categories_count }} Catégories &bull; {{ $menu->total_items_count }} Articles</small>
                    </div>

                    <a href="{{ route('client.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>

                @if(!is_array($menu->categories) || count($menu->categories) === 0)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                        Votre carte est vide. Cliquez sur "Modifier" pour ajouter vos plats.
                    </div>
                @else
                    <div class="d-flex flex-column gap-4">
                        @foreach($menu->categories as $category)
                            <div class="border rounded-3 p-3 bg-white">
                                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                                    <h6 class="fw-bold text-dark mb-0">{{ $category['name'] ?? 'Catégorie' }}</h6>
                                    <span class="badge bg-light text-secondary border">
                                        {{ isset($category['items']) ? count($category['items']) : 0 }} plat(s)
                                    </span>
                                </div>

                                <div class="row g-3">
                                    @if(isset($category['items']) && is_array($category['items']))
                                        @foreach($category['items'] as $item)
                                            <div class="col-12 col-md-6">
                                                <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                                            <div class="fw-bold text-dark">{{ $item['name'] ?? 'Plat' }}</div>
                                                            <div class="fw-bold text-primary font-mono text-nowrap">
                                                                {{ number_format((float) ($item['price'] ?? 0), 2) }} {{ $menu->currency }}
                                                            </div>
                                                        </div>
                                                        @if(!empty($item['description']))
                                                            <p class="text-muted small mb-2">{{ $item['description'] }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                                        <div>
                                                            @if(!empty($item['badge']))
                                                                <span class="badge bg-primary-subtle text-primary" style="font-size: 0.68rem;">
                                                                    {{ $item['badge'] }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            @if(($item['is_available'] ?? true) !== false)
                                                                <span class="badge bg-success-subtle text-success" style="font-size: 0.68rem;">En Stock</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger" style="font-size: 0.68rem;">Épuisé</span>
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

