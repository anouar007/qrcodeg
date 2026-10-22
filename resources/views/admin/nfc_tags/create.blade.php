@extends('layouts.admin')

@section('title', 'Créer un Tag NFC')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none">Tags NFC</a></li>
                <li class="breadcrumb-item active">Nouveau Tag NFC</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-0">Créer un Tag NFC</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" form="nfcForm" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-broadcast"></i>
            <span>Enregistrer le Tag</span>
        </button>
    </div>
</div>

<form action="{{ route('admin.nfc-tags.store') }}" method="POST" id="nfcForm">
    @csrf

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
                            <input type="text" name="name" class="form-control" placeholder="ex. Carte PVC Métal - Karim Benani" value="{{ old('name') }}" required>
                            <div class="form-text">Ce nom apparaîtra dans votre tableau de bord.</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Client Propriétaire</label>
                            <select name="client_id" class="form-select">
                                <option value="">Aucun client (usage interne)</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>
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
                    <p class="text-muted small mb-0">Définissez ce qui s'ouvre sur le smartphone dès qu'il touche la puce NFC.</p>
                </div>
                <div class="card-body pt-0">
                    {{-- Target Type Selector Tabs/Cards --}}
                    <label class="form-label small fw-semibold mb-2">Type de Cible <span class="text-danger">*</span></label>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center target-type-card active" id="cardTypeDigital" onclick="selectTargetType('digital_card')" style="cursor: pointer; transition: all 0.2s;">
                                <i class="bi bi-person-vcard fs-2 text-primary d-block mb-1"></i>
                                <div class="fw-bold small">Carte Visite Digitale</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Profil connecté complet</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center target-type-card" id="cardTypeQr" onclick="selectTargetType('qr_code')" style="cursor: pointer; transition: all 0.2s;">
                                <i class="bi bi-qr-code fs-2 text-info d-block mb-1"></i>
                                <div class="fw-bold small">QR Dynamique VP</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Lien vers un QR studio</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center target-type-card" id="cardTypeUrl" onclick="selectTargetType('url')" style="cursor: pointer; transition: all 0.2s;">
                                <i class="bi bi-link-45deg fs-2 text-success d-block mb-1"></i>
                                <div class="fw-bold small">URL Directe</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Site, Instagram, Google Maps...</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="target_type" id="targetTypeInput" value="{{ old('target_type', 'digital_card') }}">

                    {{-- Target Type 1: Digital Card --}}
                    <div id="targetWrapDigital" class="target-field-wrap">
                        <label class="form-label small fw-semibold">Sélectionner la Carte Visite Digitale</label>
                        <select name="digital_card_id" class="form-select">
                            <option value="">-- Choisir une Carte Visite Digitale --</option>
                            @foreach($digitalCards as $card)
                                <option value="{{ $card->id }}" {{ old('digital_card_id') == $card->id ? 'selected' : '' }}>
                                    {{ $card->full_name ?: $card->name }} ({{ $card->slug }}) - {{ $card->client ? $card->client->company_name : 'Sans client' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Au tap, le smartphone ouvrira instantanément la carte de visite sélectionnée.</div>
                    </div>

                    {{-- Target Type 2: QR Code --}}
                    <div id="targetWrapQr" class="target-field-wrap d-none">
                        <label class="form-label small fw-semibold">Sélectionner le QR Code Dynamique</label>
                        <select name="qr_code_id" class="form-select">
                            <option value="">-- Choisir un QR Code --</option>
                            @foreach($qrCodes as $qr)
                                <option value="{{ $qr->id }}" {{ old('qr_code_id') == $qr->id ? 'selected' : '' }}>
                                    {{ $qr->public_code }} - {{ $qr->name }} ({{ $qr->type }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Le tap comptera dans les statistiques et suivra la logique de redirection du QR code.</div>
                    </div>

                    {{-- Target Type 3: Custom URL --}}
                    <div id="targetWrapUrl" class="target-field-wrap d-none">
                        <label class="form-label small fw-semibold">URL de Redirection Directe</label>
                        <input type="url" name="target_url" class="form-control font-monospace" placeholder="https://g.page/r/your-google-review-link" value="{{ old('target_url') }}">
                        <div class="form-text">Entrez l'URL complète commençant par https://. Vous pourrez la modifier ultérieurement sans changer la puce NFC !</div>
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
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Actif (Redirige au tap)</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif (Message d'indisponibilité)</option>
                                <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>Verrouillé</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Notes & Emplacement Physique (Optionnel)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="ex. Puce NTAG213 intégrée dans la carte PVC Noir Mat n°04...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Guide & Help Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-broadcast fs-3 text-primary"></i>
                        <h5 class="fw-bold mb-0">Comment programmer la puce NFC ?</h5>
                    </div>
                    <p class="small mb-3">
                        Dès l'enregistrement, un identifiant unique (ex: <code>NFC-8K9M2X</code>) et une URL de redirection <code>{{ url('/n/NFC-XXXXXX') }}</code> seront générés.
                    </p>
                    <ol class="small ps-3 mb-3 d-flex flex-column gap-2">
                        <li>Installez l'application gratuite <strong>NFC Tools</strong> (iOS & Android).</li>
                        <li>Choisissez <strong>Écrire &rarr; Ajouter un enregistrement &rarr; URL / Lien</strong>.</li>
                        <li>Collez le lien généré par la plateforme et approchez votre carte NFC du téléphone.</li>
                        <li><strong>C'est prêt !</strong> Désormais, dès qu'un utilisateur touche la carte, il est instantanément redirigé vers votre cible.</li>
                    </ol>
                    <div class="alert alert-light border small mb-0 p-2">
                        <i class="bi bi-magic text-primary me-1"></i> <strong>Avantage Dynamique :</strong> Vous pourrez modifier la destination à tout moment dans ce tableau de bord sans jamais avoir à reprogrammer la carte physique !
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    const current = document.getElementById('targetTypeInput').value || 'digital_card';
    selectTargetType(current);
});
</script>
@endpush
@endsection
