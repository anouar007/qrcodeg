@extends('layouts.admin')

@section('title', 'Modifier le Tag NFC: ' . $nfcTag->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none">Tags NFC</a></li>
                <li class="breadcrumb-item active">{{ $nfcTag->tag_uid }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <h1 class="h3 fw-bold mb-0">Modifier Tag NFC</h1>
            <span class="badge bg-dark-subtle text-dark border font-monospace">{{ $nfcTag->tag_uid }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" form="nfcForm" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-check2-circle"></i>
            <span>Mettre à jour</span>
        </button>
    </div>
</div>

<form action="{{ route('admin.nfc-tags.update', $nfcTag) }}" method="POST" id="nfcForm">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Tag Identity --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0">1. Identification du Tag</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold">Nom ou Description du Tag <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $nfcTag->name) }}" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Client Propriétaire</label>
                            <select name="client_id" class="form-select">
                                <option value="">Aucun client (usage interne)</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" {{ old('client_id', $nfcTag->client_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->company_name }} ({{ $c->contact_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Target Redirection --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0">2. Destination au Tap / Détection NFC</h5>
                    <p class="text-muted small mb-0">Modifiez la cible instantanément sans avoir besoin de reprogrammer la puce physique.</p>
                </div>
                <div class="card-body pt-0">
                    <label class="form-label small fw-semibold mb-2">Type de Cible <span class="text-danger">*</span></label>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center target-type-card {{ $nfcTag->target_type === 'digital_card' ? 'active border-primary bg-primary-subtle' : '' }}" id="cardTypeDigital" onclick="selectTargetType('digital_card')" style="cursor: pointer; transition: all 0.2s;">
                                <i class="bi bi-person-vcard fs-2 text-primary d-block mb-1"></i>
                                <div class="fw-bold small">Carte Visite Digitale</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Profil connecté complet</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center target-type-card {{ $nfcTag->target_type === 'qr_code' ? 'active border-primary bg-primary-subtle' : '' }}" id="cardTypeQr" onclick="selectTargetType('qr_code')" style="cursor: pointer; transition: all 0.2s;">
                                <i class="bi bi-qr-code fs-2 text-info d-block mb-1"></i>
                                <div class="fw-bold small">QR Dynamique VP</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Lien vers un QR studio</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center target-type-card {{ $nfcTag->target_type === 'url' ? 'active border-primary bg-primary-subtle' : '' }}" id="cardTypeUrl" onclick="selectTargetType('url')" style="cursor: pointer; transition: all 0.2s;">
                                <i class="bi bi-link-45deg fs-2 text-success d-block mb-1"></i>
                                <div class="fw-bold small">URL Directe</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Site, Instagram, Google Maps...</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="target_type" id="targetTypeInput" value="{{ old('target_type', $nfcTag->target_type) }}">

                    {{-- Target Type 1: Digital Card --}}
                    <div id="targetWrapDigital" class="target-field-wrap {{ $nfcTag->target_type === 'digital_card' ? '' : 'd-none' }}">
                        <label class="form-label small fw-semibold">Sélectionner la Carte Visite Digitale</label>
                        <select name="digital_card_id" class="form-select">
                            <option value="">-- Choisir une Carte Visite Digitale --</option>
                            @foreach($digitalCards as $card)
                                <option value="{{ $card->id }}" {{ old('digital_card_id', $nfcTag->digital_card_id) == $card->id ? 'selected' : '' }}>
                                    {{ $card->full_name ?: $card->name }} ({{ $card->slug }}) - {{ $card->client ? $card->client->company_name : 'Sans client' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Target Type 2: QR Code --}}
                    <div id="targetWrapQr" class="target-field-wrap {{ $nfcTag->target_type === 'qr_code' ? '' : 'd-none' }}">
                        <label class="form-label small fw-semibold">Sélectionner le QR Code Dynamique</label>
                        <select name="qr_code_id" class="form-select">
                            <option value="">-- Choisir un QR Code --</option>
                            @foreach($qrCodes as $qr)
                                <option value="{{ $qr->id }}" {{ old('qr_code_id', $nfcTag->qr_code_id) == $qr->id ? 'selected' : '' }}>
                                    {{ $qr->public_code }} - {{ $qr->name }} ({{ $qr->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Target Type 3: Custom URL --}}
                    <div id="targetWrapUrl" class="target-field-wrap {{ $nfcTag->target_type === 'url' ? '' : 'd-none' }}">
                        <label class="form-label small fw-semibold">URL de Redirection Directe</label>
                        <input type="url" name="target_url" class="form-control font-monospace" placeholder="https://..." value="{{ old('target_url', $nfcTag->target_url) }}">
                    </div>
                </div>
            </div>

            {{-- Status & Notes --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0">3. Statut & Remarques</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Statut du Tag</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $nfcTag->status) == 'active' ? 'selected' : '' }}>Actif (Redirige au tap)</option>
                                <option value="inactive" {{ old('status', $nfcTag->status) == 'inactive' ? 'selected' : '' }}>Inactif (Message d'indisponibilité)</option>
                                <option value="locked" {{ old('status', $nfcTag->status) == 'locked' ? 'selected' : '' }}>Verrouillé</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Notes & Emplacement Physique (Optionnel)</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $nfcTag->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Quick info & test tap --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0">Lien Programmé sur la Puce</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 bg-light rounded-3 font-monospace small mb-3 text-break">
                        {{ $nfcTag->public_url }}
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="copyNfcUrl('{{ $nfcTag->public_url }}')">
                            <i class="bi bi-clipboard me-1"></i> Copier l'URL NFC
                        </button>
                        <a href="{{ $nfcTag->public_url }}" target="_blank" class="btn btn-outline-success w-100">
                            <i class="bi bi-play-circle me-1"></i> Simuler le Tap NFC
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0">Statistiques Récentes</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Total Détections (Taps)</span>
                        <span class="fw-bold fs-5">{{ $nfcTag->tap_count }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Dernier Tap</span>
                        <span class="small fw-semibold">{{ $nfcTag->last_tapped_at ? $nfcTag->last_tapped_at->diffForHumans() : 'Aucun tap' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted small">Date de Création</span>
                        <span class="small">{{ $nfcTag->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

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
function selectTargetType(type) {
    document.querySelectorAll('.target-type-card').forEach(c => {
        c.classList.remove('active', 'border-primary', 'bg-primary-subtle');
    });

    document.querySelectorAll('.target-field-wrap').forEach(w => w.classList.add('d-none'));

    document.getElementById('targetTypeInput').value = type;

    if (type === 'digital_card') {
        const el = document.getElementById('cardTypeDigital');
        el.classList.add('active', 'border-primary', 'bg-primary-subtle');
        document.getElementById('targetWrapDigital').classList.remove('d-none');
    } else if (type === 'qr_code') {
        const el = document.getElementById('cardTypeQr');
        el.classList.add('active', 'border-primary', 'bg-primary-subtle');
        document.getElementById('targetWrapQr').classList.remove('d-none');
    } else if (type === 'url') {
        const el = document.getElementById('cardTypeUrl');
        el.classList.add('active', 'border-primary', 'bg-primary-subtle');
        document.getElementById('targetWrapUrl').classList.remove('d-none');
    }
}

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
