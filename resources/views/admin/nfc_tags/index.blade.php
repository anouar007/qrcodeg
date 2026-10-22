@extends('layouts.admin')

@section('title', 'Gestion des Tags NFC')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Tableau de bord</a></li>
                <li class="breadcrumb-item active">Tags & Cartes NFC</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-1">Tags & Cartes NFC</h1>
        <p class="text-muted small mb-0">Gérez les puces NFC programmées, cartes sans-contact et redirection instantanée au tap.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-broadcast"></i>
            <span>Créer un Tag NFC</span>
        </a>
    </div>
</div>

{{-- Top Metrics --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                    <i class="bi bi-broadcast fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Tags NFC</div>
                    <div class="fs-4 fw-bold text-dark">{{ $nfcTags->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success-subtle text-success">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Tags Actifs</div>
                    <div class="fs-4 fw-bold text-dark">{{ $nfcTags->where('status', 'active')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info-subtle text-info">
                    <i class="bi bi-phone-vibrate fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Détections (Taps)</div>
                    <div class="fs-4 fw-bold text-dark">{{ $nfcTags->sum('tap_count') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning-subtle text-warning">
                    <i class="bi bi-lightning-charge fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Technologie Puce</div>
                    <div class="fs-6 fw-bold text-dark">NTAG 213 / 215 / 216</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters Card --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.nfc-tags.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Rechercher par UID, nom, cible...">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="target_type" class="form-select">
                    <option value="">Tous les types de cible</option>
                    <option value="url" {{ request('target_type') == 'url' ? 'selected' : '' }}>Lien Web Personnalisé</option>
                    <option value="digital_card" {{ request('target_type') == 'digital_card' ? 'selected' : '' }}>Carte Visite Digitale</option>
                    <option value="qr_code" {{ request('target_type') == 'qr_code' ? 'selected' : '' }}>QR Code Dynamique VP</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Tous statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Verrouillé</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-grow-1">Filtrer</button>
                @if(request()->hasAny(['search', 'target_type', 'status', 'client_id']))
                    <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- NFC List Table --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3 py-3">Tag NFC</th>
                    <th>Client Associé</th>
                    <th>Cible / Redirection au Tap</th>
                    <th class="text-center">Détections (Taps)</th>
                    <th class="text-center">Statut</th>
                    <th>Dernier Tap</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nfcTags as $tag)
                    <tr>
                        <td class="ps-3 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle p-2 bg-primary-subtle text-primary flex-shrink-0" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-broadcast fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $tag->name }}</div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-dark-subtle text-dark border font-monospace" style="font-size: 0.75rem;">{{ $tag->tag_uid }}</span>
                                        <button class="btn btn-sm btn-link p-0 text-muted" title="Copier le lien NFC pour programmation" onclick="copyNfcUrl('{{ $tag->public_url }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($tag->client)
                                <a href="{{ route('admin.clients.show', $tag->client_id) }}" class="fw-semibold text-decoration-none">
                                    {{ $tag->client->company_name }}
                                </a>
                                <div class="small text-muted">{{ $tag->client->contact_name }}</div>
                            @else
                                <span class="text-muted small">Aucun client</span>
                            @endif
                        </td>
                        <td>
                            @if($tag->target_type === 'digital_card' && $tag->digitalCard)
                                <span class="badge bg-purple-subtle text-purple border mb-1 d-inline-flex align-items-center gap-1" style="background-color: #ede9fe; color: #6d28d9;">
                                    <i class="bi bi-person-vcard"></i> Carte Visite
                                </span>
                                <div class="small fw-semibold text-dark text-truncate" style="max-width: 220px;">
                                    <a href="{{ route('digital-cards.public.show', $tag->digitalCard->slug) }}" target="_blank" class="text-decoration-none text-dark">
                                        {{ $tag->digitalCard->full_name ?: $tag->digitalCard->name }}
                                        <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size: 0.7rem;"></i>
                                    </a>
                                </div>
                            @elseif($tag->target_type === 'qr_code' && $tag->qrCode)
                                <span class="badge bg-info-subtle text-info border mb-1 d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-qr-code"></i> Dynamic QR
                                </span>
                                <div class="small fw-semibold text-dark text-truncate font-monospace" style="max-width: 220px;">
                                    <a href="{{ $tag->qrCode->dynamic_url }}" target="_blank" class="text-decoration-none text-dark">
                                        {{ $tag->qrCode->public_code }}
                                        <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size: 0.7rem;"></i>
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border mb-1 d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-link-45deg"></i> URL Directe
                                </span>
                                <div class="small text-muted text-truncate font-monospace" style="max-width: 220px;" title="{{ $tag->target_url }}">
                                    <a href="{{ $tag->target_url }}" target="_blank" class="text-decoration-none text-muted">
                                        {{ $tag->target_url }}
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1 fw-bold fs-6">
                                {{ $tag->tap_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($tag->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Actif</span>
                            @elseif($tag->status === 'locked')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Verrouillé</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Inactif</span>
                            @endif
                        </td>
                        <td>
                            @if($tag->last_tapped_at)
                                <div class="small text-dark">{{ $tag->last_tapped_at->diffForHumans() }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $tag->last_tapped_at->format('d/m/Y H:i') }}</div>
                            @else
                                <span class="text-muted small">Jamais</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ $tag->public_url }}" target="_blank" class="btn btn-sm btn-outline-info" title="Tester la détection / tap NFC">
                                    <i class="bi bi-play-circle"></i>
                                </a>
                                <a href="{{ route('admin.nfc-tags.show', $tag) }}" class="btn btn-sm btn-outline-secondary" title="Détails & Analytics">
                                    <i class="bi bi-graph-up"></i>
                                </a>
                                <a href="{{ route('admin.nfc-tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary" title="Modifier le Tag">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.nfc-tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tag NFC ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-broadcast display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h5 class="fw-bold">Aucun Tag NFC enregistré</h5>
                            <p class="small text-muted mb-3">Créez votre premier tag pour relier des cartes NFC physiques à vos Cartes Visite ou QR Codes.</p>
                            <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Créer un Tag NFC
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($nfcTags->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $nfcTags->links() }}
        </div>
    @endif
</div>

<!-- Toast for clipboard copy -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="copyToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body small">
                <i class="bi bi-check-circle-fill text-success me-2"></i> Lien NFC copié ! Programmez votre puce avec ce lien.
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
