@extends('layouts.app')

@section('title', 'Encodeur NFC Intégré')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none text-muted">Cartes &amp; Puces NFC</a></li>
    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Encodeur NFC</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Top Executive Header -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0" style="width: 52px; height: 52px; background: linear-gradient(135deg, #5736e9 0%, #3b22b3 100%);">
                    <i class="bi bi-broadcast fs-4"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h1 class="h4 fw-bold text-dark mb-0">Encodeur NFC Intégré</h1>
                        <span id="nfcBrowserSupportBadge" class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-hourglass-split me-1"></i>Vérification Web NFC...
                        </span>
                    </div>
                    <p class="text-muted small mb-0">Gravez instantanément vos liens, cartes de visite, menus et tags NFC sur des puces physiques depuis votre navigateur.</p>
                </div>
            </div>

            <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-credit-card me-1"></i>Gérer les Tags NFC
            </a>
        </div>
    </div>

    <div class="row g-4 align-items-start">
        <!-- LEFT COLUMN: Custom / Direct NFC Writer -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-2.5 mb-3 pb-3 border-bottom">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Encodage Direct (URL Libre)</h5>
                        <p class="text-muted small mb-0">Saisissez n'importe quelle URL ou choisissez à droite</p>
                    </div>
                </div>

                <form id="customNfcEncodeForm" onsubmit="event.preventDefault(); launchCustomNfcEncode();">
                    <div class="mb-3">
                        <label for="nfcCustomLabel" class="form-label fw-semibold small text-dark">Libellé de l'encodage</label>
                        <input type="text" id="nfcCustomLabel" class="form-control" value="Puce NFC Personnalisée" placeholder="Ex: Carte VIP Table 4, Badge Accueil...">
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label for="nfcCustomUrl" class="form-label fw-semibold small text-dark mb-0">URL / Lien à graver sur la puce <span class="text-danger">*</span></label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2" style="font-size: 0.68rem;" onclick="setNfcPrefix('https://')">https://</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2" style="font-size: 0.68rem;" onclick="setNfcPrefix('tel:')">tel:</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2" style="font-size: 0.68rem;" onclick="setNfcPrefix('mailto:')">mailto:</button>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                            <input type="text" id="nfcCustomUrl" class="form-control font-mono" placeholder="https://votre-domaine.com/..." required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-lg w-100 text-white fw-semibold rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #5736e9 0%, #4324c7 100%);">
                        <i class="bi bi-broadcast"></i>
                        <span>Lancer l'Encodage NFC</span>
                    </button>
                </form>
            </div>

            <!-- Compatibility & Instructions Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h6 class="fw-bold text-dark mb-2.5 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-primary"></i>
                    <span>Comment graver une puce NFC ?</span>
                </h6>
                <ol class="small text-muted ps-3 mb-3">
                    <li class="mb-1.5">Ouvrez cette page sur un <strong>smartphone Android</strong> via <strong>Chrome, Edge ou Samsung Internet</strong> (en HTTPS) avec le <strong>NFC activé</strong>.</li>
                    <li class="mb-1.5">Choisissez une ressource à droite ou saisissez une URL ci-dessus, puis cliquez sur <strong>Encoder NFC</strong>.</li>
                    <li class="mb-1.5">Approchez la carte, le sticker ou le porte-clé NFC contre l'arrière du téléphone jusqu'au signal sonore.</li>
                </ol>
                <div class="p-2.5 rounded-3 bg-white border small text-muted">
                    <i class="bi bi-apple text-dark me-1"></i>
                    <strong>Sur iPhone (iOS) ou PC :</strong> L'API Web NFC étant restreinte par Apple/Desktop, le bouton vous permet de copier instantanément l'URL pour la graver avec une app (ex : <em>NFC Tools</em>).
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Quick Picker from Existing Resources -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                            <i class="bi bi-collection fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Sélection Rapide (1-Clic)</h5>
                            <p class="text-muted small mb-0">Encodez directement un de vos tags, cartes, menus ou QR dynamiques</p>
                        </div>
                    </div>

                    <div style="min-width: 210px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="nfcResourceSearch" class="form-control border-start-0" placeholder="Rechercher..." oninput="filterNfcResources(this.value)">
                        </div>
                    </div>
                </div>

                <!-- Resource Tabs -->
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-3 mb-3" id="nfcResourceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold small py-1.5" id="tab-tags" data-bs-toggle="pill" data-bs-target="#pane-tags" type="button" role="tab">
                            <i class="bi bi-credit-card me-1"></i>Tags NFC ({{ $nfcTags->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold small py-1.5" id="tab-cards" data-bs-toggle="pill" data-bs-target="#pane-cards" type="button" role="tab">
                            <i class="bi bi-person-badge me-1"></i>Cartes ({{ $digitalCards->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold small py-1.5" id="tab-menus" data-bs-toggle="pill" data-bs-target="#pane-menus" type="button" role="tab">
                            <i class="bi bi-journal-richtext me-1"></i>Menus ({{ $menus->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold small py-1.5" id="tab-qrs" data-bs-toggle="pill" data-bs-target="#pane-qrs" type="button" role="tab">
                            <i class="bi bi-lightning-charge me-1"></i>QR Dyn. ({{ $dynamicQrs->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" style="max-height: 520px; overflow-y: auto;">
                    <!-- 1. NFC Tags -->
                    <div class="tab-pane fade show active" id="pane-tags" role="tabpanel">
                        @forelse($nfcTags as $tag)
                            @php
                                $tagUrl = route('nfc.redirect', ['id_or_uid' => $tag->tag_uid ?: $tag->id]);
                                $tagLabel = $tag->label ?: ($tag->name ?: $tag->tag_uid);
                            @endphp
                            <div class="nfc-resource-item d-flex align-items-center justify-content-between gap-2 p-2.5 border rounded-3 mb-2 bg-white" data-search="{{ strtolower($tagLabel . ' ' . $tag->tag_uid . ' ' . $tagUrl) }}">
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark small text-truncate">{{ $tagLabel }}</div>
                                    <div class="font-mono text-muted text-truncate" style="font-size: 0.72rem;">{{ $tagUrl }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" title="Pré-remplir à gauche" onclick="prefillNfcEncoder('{{ addslashes($tagUrl) }}', 'Tag NFC - {{ addslashes($tagLabel) }}')">
                                        <i class="bi bi-arrow-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 d-inline-flex align-items-center gap-1" onclick="window.nfcEncoder.writeUrl('{{ $tagUrl }}', 'Tag NFC - {{ addslashes($tagLabel) }}')">
                                        <i class="bi bi-broadcast"></i>
                                        <span>Encoder</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Aucun tag NFC disponible.</div>
                        @endforelse
                    </div>

                    <!-- 2. Digital Cards -->
                    <div class="tab-pane fade" id="pane-cards" role="tabpanel">
                        @forelse($digitalCards as $card)
                            @php
                                $cardUrl = $card->public_url;
                                $cardLabel = $card->full_name ?: $card->name;
                            @endphp
                            <div class="nfc-resource-item d-flex align-items-center justify-content-between gap-2 p-2.5 border rounded-3 mb-2 bg-white" data-search="{{ strtolower($cardLabel . ' ' . $cardUrl) }}">
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark small text-truncate">{{ $cardLabel }}</div>
                                    <div class="font-mono text-muted text-truncate" style="font-size: 0.72rem;">{{ $cardUrl }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" title="Pré-remplir à gauche" onclick="prefillNfcEncoder('{{ addslashes($cardUrl) }}', 'Carte Digitale - {{ addslashes($cardLabel) }}')">
                                        <i class="bi bi-arrow-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 d-inline-flex align-items-center gap-1" onclick="window.nfcEncoder.writeUrl('{{ $cardUrl }}', 'Carte Digitale - {{ addslashes($cardLabel) }}')">
                                        <i class="bi bi-broadcast"></i>
                                        <span>Encoder</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Aucune carte de visite digitale disponible.</div>
                        @endforelse
                    </div>

                    <!-- 3. Menus Digitaux -->
                    <div class="tab-pane fade" id="pane-menus" role="tabpanel">
                        @forelse($menus as $menu)
                            @php
                                $menuUrl = route('menu.public.show', $menu->slug);
                                $menuLabel = $menu->brand_name . ' — ' . $menu->title;
                            @endphp
                            <div class="nfc-resource-item d-flex align-items-center justify-content-between gap-2 p-2.5 border rounded-3 mb-2 bg-white" data-search="{{ strtolower($menuLabel . ' ' . $menuUrl) }}">
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark small text-truncate">{{ $menuLabel }}</div>
                                    <div class="font-mono text-muted text-truncate" style="font-size: 0.72rem;">{{ $menuUrl }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" title="Pré-remplir à gauche" onclick="prefillNfcEncoder('{{ addslashes($menuUrl) }}', 'Menu Digital - {{ addslashes($menu->brand_name) }}')">
                                        <i class="bi bi-arrow-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 d-inline-flex align-items-center gap-1" onclick="window.nfcEncoder.writeUrl('{{ $menuUrl }}', 'Menu Digital - {{ addslashes($menu->brand_name) }}')">
                                        <i class="bi bi-broadcast"></i>
                                        <span>Encoder</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Aucun menu digital disponible.</div>
                        @endforelse
                    </div>

                    <!-- 4. Dynamic QRs -->
                    <div class="tab-pane fade" id="pane-qrs" role="tabpanel">
                        @forelse($dynamicQrs as $qr)
                            @php
                                $qrUrl = route('qr.redirect', ['code' => $qr->code]);
                                $qrLabel = $qr->label ?: $qr->code;
                            @endphp
                            <div class="nfc-resource-item d-flex align-items-center justify-content-between gap-2 p-2.5 border rounded-3 mb-2 bg-white" data-search="{{ strtolower($qrLabel . ' ' . $qrUrl) }}">
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark small text-truncate">{{ $qrLabel }}</div>
                                    <div class="font-mono text-muted text-truncate" style="font-size: 0.72rem;">{{ $qrUrl }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" title="Pré-remplir à gauche" onclick="prefillNfcEncoder('{{ addslashes($qrUrl) }}', 'QR Dynamique - {{ addslashes($qrLabel) }}')">
                                        <i class="bi bi-arrow-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 d-inline-flex align-items-center gap-1" onclick="window.nfcEncoder.writeUrl('{{ $qrUrl }}', 'QR Dynamique - {{ addslashes($qrLabel) }}')">
                                        <i class="bi bi-broadcast"></i>
                                        <span>Encoder</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Aucun QR code dynamique disponible.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const badge = document.getElementById('nfcBrowserSupportBadge');
    if (badge) {
        if ('NDEFReader' in window) {
            badge.className = 'badge bg-success-subtle text-success rounded-pill px-2.5 py-1';
            badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Web NFC Prêt (Android)';
        } else {
            badge.className = 'badge bg-warning-subtle text-warning-emphasis rounded-pill px-2.5 py-1';
            badge.innerHTML = '<i class="bi bi-phone me-1"></i>Android Chrome requis pour gravure directe';
        }
    }
});

function setNfcPrefix(prefix) {
    const input = document.getElementById('nfcCustomUrl');
    if (!input) return;
    if (!input.value.startsWith(prefix)) {
        input.value = prefix + input.value.replace(/^(https?:\/\/|tel:|mailto:)/i, '');
    }
    input.focus();
}

function prefillNfcEncoder(url, label) {
    document.getElementById('nfcCustomUrl').value = url;
    document.getElementById('nfcCustomLabel').value = label;
}

function launchCustomNfcEncode() {
    const url = document.getElementById('nfcCustomUrl').value.trim();
    const label = document.getElementById('nfcCustomLabel').value.trim() || 'Puce NFC';
    if (!url) {
        alert('Veuillez saisir une URL valide à graver.');
        return;
    }
    window.nfcEncoder.writeUrl(url, label);
}

function filterNfcResources(query) {
    const q = (query || '').trim().toLowerCase();
    document.querySelectorAll('.nfc-resource-item').forEach(el => {
        const hay = el.getAttribute('data-search') || '';
        el.classList.toggle('d-none', q !== '' && !hay.includes(q));
    });
}
</script>
@endpush

