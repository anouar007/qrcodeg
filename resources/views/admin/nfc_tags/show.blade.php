@extends('layouts.admin')

@section('title', 'Détails Tag NFC: ' . $nfcTag->name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none">Tags NFC</a></li>
                <li class="breadcrumb-item active">{{ $nfcTag->tag_uid }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <h1 class="h3 fw-bold mb-0">{{ $nfcTag->name }}</h1>
            <span class="badge bg-dark-subtle text-dark border font-monospace">{{ $nfcTag->tag_uid }}</span>
            @if($nfcTag->status === 'active')
                <span class="badge bg-success-subtle text-success border border-success-subtle">Actif</span>
            @elseif($nfcTag->status === 'locked')
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Verrouillé</span>
            @else
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactif</span>
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $nfcTag->public_url }}" target="_blank" class="btn btn-outline-info d-flex align-items-center gap-2">
            <i class="bi bi-play-circle"></i>
            <span>Tester le Tap</span>
        </a>
        <a href="{{ route('admin.nfc-tags.edit', $nfcTag) }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i>
            <span>Modifier</span>
        </a>
        <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-outline-secondary">
            <span>Retour</span>
        </a>
    </div>
</div>

{{-- Metric Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                    <i class="bi bi-phone-vibrate fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Taps Détectés</div>
                    <div class="fs-4 fw-bold text-dark">{{ $nfcTag->tap_count }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success-subtle text-success">
                    <i class="bi bi-broadcast-pin fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Type de Cible</div>
                    <div class="fs-6 fw-bold text-dark text-capitalize">{{ str_replace('_', ' ', $nfcTag->target_type) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info-subtle text-info">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Dernier Tap</div>
                    <div class="fs-6 fw-bold text-dark">{{ $nfcTag->last_tapped_at ? $nfcTag->last_tapped_at->diffForHumans() : 'Jamais' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning-subtle text-warning">
                    <i class="bi bi-building fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Client</div>
                    <div class="fs-6 fw-bold text-dark text-truncate" style="max-width: 140px;">
                        {{ $nfcTag->client ? $nfcTag->client->company_name : 'Usage Interne' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left: Details & Link --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="fw-bold mb-0">Paramètres du Tag</h5>
            </div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="small text-muted mb-1">Lien NFC Programmé</label>
                    <div class="input-group">
                        <input type="text" class="form-control font-monospace small bg-light" value="{{ $nfcTag->public_url }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyNfcUrl('{{ $nfcTag->public_url }}')">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted mb-1">Cible Actuelle Résolue</label>
                    <div class="p-3 bg-light rounded-3">
                        <div class="fw-semibold text-dark mb-1 d-flex align-items-center gap-2">
                            @if($nfcTag->target_type === 'digital_card')
                                <i class="bi bi-person-vcard text-primary"></i> Carte Visite Digitale
                            @elseif($nfcTag->target_type === 'qr_code')
                                <i class="bi bi-qr-code text-info"></i> Dynamic QR Code VP
                            @else
                                <i class="bi bi-link-45deg text-success"></i> URL Directe
                            @endif
                        </div>
                        <a href="{{ $nfcTag->destination_url }}" target="_blank" class="small font-monospace text-break text-decoration-none">
                            {{ $nfcTag->destination_url }}
                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </div>
                </div>

                @if($nfcTag->notes)
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Notes / Emplacement Physique</label>
                        <p class="small text-dark mb-0 bg-light p-2 rounded-2">{{ $nfcTag->notes }}</p>
                    </div>
                @endif

                <div class="d-flex justify-content-between py-2 border-top text-muted small">
                    <span>Créé par</span>
                    <span class="text-dark">{{ $nfcTag->creator ? $nfcTag->creator->name : 'Système' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-top text-muted small">
                    <span>Date d'enregistrement</span>
                    <span class="text-dark">{{ $nfcTag->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Recent Taps Log --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Historique des Derniers Taps</h5>
                <span class="badge bg-light text-muted border">{{ $nfcTag->taps->count() }} détections récentes</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 py-2">Date & Heure</th>
                            <th>Appareil</th>
                            <th>OS & Navigateur</th>
                            <th class="text-end pe-3">IP Hash</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nfcTag->taps as $tap)
                            <tr>
                                <td class="ps-3 py-2">
                                    <div class="small fw-semibold text-dark">{{ $tap->tapped_at->format('d/m/Y H:i:s') }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $tap->tapped_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border text-capitalize">
                                        <i class="bi bi-{{ $tap->device_type === 'mobile' ? 'phone' : ($tap->device_type === 'tablet' ? 'tablet' : 'laptop') }} me-1"></i>
                                        {{ $tap->device_type ?? 'Mobile' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark">{{ $tap->operating_system ?? 'Inconnu' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $tap->browser ?? 'Inconnu' }}</div>
                                </td>
                                <td class="text-end pe-3 font-monospace text-muted small">
                                    {{ substr($tap->ip_hash, 0, 10) }}...
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">
                                    Aucun tap enregistré pour le moment. Approchez un smartphone pour tester la détection !
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="copyToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body small">
                <i class="bi bi-check-circle-fill text-success me-2"></i> Lien NFC copié !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyNfcUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        const toastEl = document.getElementById('copyToast');
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    });
}
</script>
@endpush
@endsection
