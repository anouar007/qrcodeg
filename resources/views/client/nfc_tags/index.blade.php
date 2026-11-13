@extends('layouts.client')

@section('title', 'Mes Cartes & Puces NFC')
@section('page_title', 'Puces & Cartes NFC Sans Contact')
@section('page_subtitle', 'Gérez les puces physiques NFC intégrées à vos cartes de visite métal/bois ou chevalets connectés')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Vos Cartes &amp; Supports NFC</h4>
            <p class="text-muted small mb-0">Un simple contact du smartphone déclenche instantanément l'ouverture de votre lien ou carte digitale.</p>
        </div>
        <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Tous mes produits
        </a>
    </div>

    @if($nfcTags->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="bi bi-broadcast fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Aucune puce NFC assignée</h5>
                <p class="text-muted small">Vos cartes et tags NFC programmés apparaîtront ici dès leur expédition et activation.</p>
                <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-primary-custom rounded-pill px-4">
                    Voir mes autres produits
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($nfcTags as $nfc)
                <div class="col-md-6 col-xl-4">
                    <div class="card border rounded-3 p-3 shadow-2xs h-100 d-flex flex-column justify-content-between position-relative" style="border-top: 4px solid #16a34a !important;">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge rounded-pill bg-success-subtle text-success font-mono" style="font-size: 0.68rem;">
                                    <i class="bi bi-broadcast me-1"></i>{{ $nfc->tag_uid ?: 'NFC' }}
                                </span>
                                <span class="badge {{ $nfc->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                    ● {{ ucfirst($nfc->status) }}
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $nfc->label ?: $nfc->name }}</h5>
                            <div class="text-muted small mb-2">
                                Type : <strong>{{ $nfc->target_type === 'digital_card' ? 'Carte Visite / Menu' : 'URL Personnalisée' }}</strong>
                            </div>

                            <div class="p-2.5 rounded-3 bg-light border mb-3">
                                <small class="text-muted d-block text-uppercase font-mono" style="font-size: 0.65rem;">Destination sans contact :</small>
                                <div class="font-mono text-success text-truncate fw-semibold small mt-0.5">
                                    {{ $nfc->destination_url ?: $nfc->target_url }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3 small text-muted">
                                <span><i class="bi bi-hand-index-thumb me-1 text-success"></i><strong>{{ number_format($nfc->scans_count ?: $nfc->tap_count) }}</strong> touches NFC</span>
                                <span>{{ $nfc->last_tapped_at ? $nfc->last_tapped_at->diffForHumans() : 'Jamais scanné' }}</span>
                            </div>

                            <div class="d-flex gap-1.5 flex-wrap">
                                <a href="{{ route('client.nfc-tags.edit', $nfc) }}" class="btn btn-sm btn-primary-custom rounded-pill flex-fill fw-semibold">
                                    <i class="bi bi-arrow-repeat me-1"></i>Changer Destination
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2.5" title="Graver cette puce NFC depuis votre smartphone" onclick="window.nfcEncoder.writeUrl('{{ $nfc->destination_url ?: $nfc->target_url }}', 'Tag NFC - {{ addslashes($nfc->label ?: $nfc->name) }}', { bindEndpoint: '{{ route('client.nfc-tags.bind', $nfc) }}', onSuccess: (uid) => { alert('Puce gravée avec succès ! UID : ' + uid); window.location.reload(); } })">
                                    <i class="bi bi-broadcast me-1"></i> Graver
                                </button>
                                @if($nfc->tag_uid)
                                    <a href="{{ route('nfc.redirect', $nfc->tag_uid) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill" title="Tester le lien NFC">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $nfcTags->links() }}
        </div>
    @endif
</div>
@endsection

