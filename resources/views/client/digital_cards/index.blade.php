@extends('layouts.client')

@section('title', 'Mes Cartes Visite & Menus')
@section('page_title', 'Cartes de Visite Digitales & Menus')
@section('page_subtitle', 'Gérez vos profils professionnels connectés, catalogues et menus interactifs en temps réel')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Vos Cartes &amp; Profils Connectés</h4>
            <p class="text-muted small mb-0">Partagez vos coordonnées instantanément d'un simple geste sans contact (NFC) ou via QR code.</p>
        </div>
        <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Tous mes produits
        </a>
    </div>

    @if($digitalCards->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="bi bi-person-badge fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Aucune carte digitale assignée</h5>
                <p class="text-muted small">Vos cartes et menus digitaux apparaîtront ici dès leur activation par notre équipe technique.</p>
                <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-primary-custom rounded-pill px-4">
                    Voir mes autres produits
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($digitalCards as $card)
                <div class="col-md-6 col-xl-4">
                    <div class="card border rounded-3 p-3 shadow-2xs h-100 d-flex flex-column justify-content-between position-relative" style="border-top: 4px solid #5736e9 !important;">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge rounded-pill font-mono text-white" style="background: #5736e9; font-size: 0.68rem;">
                                    {{ ucfirst($card->template ?: 'Modern Executive') }}
                                </span>
                                <span class="badge {{ $card->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                    ● {{ ucfirst($card->status) }}
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $card->title ?: $card->full_name }}</h5>
                            <div class="text-muted small mb-3">
                                <span>{{ $card->job_title ?: 'Directeur' }}</span> &bull; 
                                <span class="fw-semibold">{{ $card->company ?: $client->name }}</span>
                            </div>

                            @if($card->bio)
                                <p class="text-muted small mb-3 text-truncate-2" style="font-size: 0.78rem;">
                                    {{ $card->bio }}
                                </p>
                            @endif

                            <div class="p-2 rounded-2 bg-light border font-mono small text-truncate mb-3" style="font-size: 0.72rem;">
                                <span class="text-muted">URL Publique :</span> 
                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="text-primary text-decoration-none">
                                    {{ url('/c/'.$card->slug) }}
                                </a>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3 small text-muted">
                                <span><i class="bi bi-eye me-1 text-primary"></i><strong>{{ number_format($card->views_count) }}</strong> consultations</span>
                                <span>{{ $card->updated_at->diffForHumans() }}</span>
                            </div>

                            <div class="d-flex gap-1.5 flex-wrap">
                                <a href="{{ route('client.digital-cards.edit', $card) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-fill fw-semibold">
                                    <i class="bi bi-pencil-square me-1"></i>Coordonnées
                                </a>
                                <a href="{{ route('client.digital-cards.menu', $card) }}" class="btn btn-sm btn-primary-custom rounded-pill flex-fill fw-semibold">
                                    <i class="fa-solid fa-utensils me-1"></i>Menu / Catalogue
                                </a>
                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill" title="Ouvrir la page publique">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $digitalCards->links() }}
        </div>
    @endif
</div>
@endsection

