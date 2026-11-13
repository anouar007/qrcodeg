@extends('layouts.client')

@section('title', 'Mes Menus Digitaux')
@section('page_title', 'Mes Menus Digitaux')
@section('page_subtitle', 'Gérez vos cartes restaurants, fast-food, cafés & tarifs en direct')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-journal-richtext text-primary me-2"></i>Mes Menus &amp; Cartes
            </h4>
            <p class="text-muted small mb-0">Mettez à jour vos menus et cartes assignés en temps réel pour vos clients au restaurant.</p>
        </div>
    </div>

    @if($menus->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px; background: rgba(87, 54, 233, 0.1); color: #5736e9;">
                <i class="bi bi-shop fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Aucun menu digital assigné pour l'instant</h5>
            <p class="text-muted small mb-0" style="max-width: 440px; margin: 0 auto;">
                Vos menus digitaux sont créés et assignés par l'administrateur. Une fois assignés, vous pourrez modifier vos plats, prix et visuels ici.
            </p>
        </div>
    @else
        <div class="row g-4">
            @foreach($menus as $menu)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-3 p-1 text-white text-center d-flex align-items-center justify-content-center"
                                         style="background: {{ $menu->theme_color ?: '#5736e9' }}; width: 44px; height: 44px; flex-shrink: 0;">
                                        @if($menu->logo_url)
                                            <img src="{{ $menu->logo_url }}" alt="Logo" class="rounded-2" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="bi bi-cup-hot-fill fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 170px;">{{ $menu->title }}</h6>
                                        <small class="text-muted">{{ $menu->brand_name }}</small>
                                    </div>
                                </div>

                                <span class="badge {{ $menu->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-2 py-1">
                                    {{ $menu->status === 'active' ? 'En Ligne' : 'Brouillon' }}
                                </span>
                            </div>

                            @if($menu->description)
                                <p class="text-muted small mb-3 text-truncate-2" style="font-size: 0.82rem;">{{ $menu->description }}</p>
                            @endif

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-folder me-1 text-primary"></i>{{ $menu->total_categories_count }} catégories
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-tag me-1 text-success"></i>{{ $menu->total_items_count }} plats
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-eye me-1 text-warning"></i>{{ number_format($menu->views_count) }} vues
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <a href="{{ route('menu.public.show', $menu->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Voir sur mobile">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Ouvrir
                            </a>
                            <div class="d-inline-flex gap-1.5">
                                <a href="{{ route('client.menus.show', $menu) }}" class="btn btn-sm btn-outline-primary" title="QR Code &amp; Impression">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                                <a href="{{ route('client.menus.edit', $menu) }}" class="btn btn-sm btn-primary-custom" title="Modifier les plats &amp; prix">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($menus->hasPages())
            <div class="mt-4">
                {{ $menus->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

