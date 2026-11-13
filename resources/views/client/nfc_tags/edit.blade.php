@extends('layouts.client')

@section('title', 'Modifier Puce NFC — ' . ($nfcTag->label ?: $nfcTag->tag_uid))
@section('page_title', 'Configuration de la Puce NFC')
@section('page_subtitle', 'Modifiez la destination déclenchée lors du contact avec un smartphone')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="mb-3">
        <a href="{{ route('client.nfc-tags.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour aux puces NFC
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Routage du Signal NFC</h5>
                        <small class="text-muted font-mono">UID Matériel : <strong class="text-success">{{ $nfcTag->tag_uid ?: 'Non assigné' }}</strong></small>
                    </div>
                    <span class="badge {{ $nfcTag->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono px-3 py-1.5">
                        ● {{ ucfirst($nfcTag->status) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('client.nfc-tags.update', $nfcTag) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="label" class="form-label fw-semibold text-dark small">Nom / Emplacement de la Puce NFC <span class="text-danger">*</span></label>
                        <input type="text" name="label" id="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $nfcTag->label ?: $nfcTag->name) }}" required>
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Type de Destination</label>
                        <select name="target_type" id="targetTypeSelect" class="form-select" onchange="toggleTargetType(this.value)">
                            <option value="url" {{ old('target_type', $nfcTag->target_type) === 'url' ? 'selected' : '' }}>URL Web Externe / Réseau Social</option>
                            <option value="digital_card" {{ old('target_type', $nfcTag->target_type) === 'digital_card' ? 'selected' : '' }}>Lier à l'une de mes Cartes Digitales ou Menus</option>
                            <option value="qr_code" {{ old('target_type', $nfcTag->target_type) === 'qr_code' ? 'selected' : '' }}>Lier à l'un de mes QR Codes Dynamiques</option>
                        </select>
                    </div>

                    <!-- Custom URL field -->
                    <div class="mb-3" id="urlFieldGroup">
                        <label for="destination_url" class="form-label fw-semibold text-dark small">URL de Destination</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success"><i class="bi bi-broadcast"></i></span>
                            <input type="url" name="destination_url" id="destination_url" class="form-control font-mono" value="{{ old('destination_url', $nfcTag->destination_url ?: $nfcTag->target_url) }}" placeholder="https://monsite.com">
                        </div>
                        <small class="text-muted mt-1 d-block">L'adresse web ouverte immédiatement lorsque la puce est touchée par un iPhone ou Android.</small>
                    </div>

                    <!-- Digital Card picker (strictly client's own cards) -->
                    <div class="mb-3 {{ old('target_type', $nfcTag->target_type) === 'digital_card' ? '' : 'd-none' }}" id="cardFieldGroup">
                        <label for="digital_card_id" class="form-label fw-semibold text-dark small">Sélectionner votre Carte Digitale</label>
                        <select name="digital_card_id" id="digital_card_id" class="form-select">
                            <option value="">-- Choisir parmi vos cartes --</option>
                            @foreach($clientCards as $card)
                                <option value="{{ $card->id }}" {{ old('digital_card_id', $nfcTag->digital_card_id) == $card->id ? 'selected' : '' }}>
                                    {{ $card->title ?: $card->full_name }} ({{ url('/c/'.$card->slug) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic QR picker (strictly client's own QRs) -->
                    <div class="mb-3 {{ old('target_type', $nfcTag->target_type) === 'qr_code' ? '' : 'd-none' }}" id="qrFieldGroup">
                        <label for="dynamic_qr_id" class="form-label fw-semibold text-dark small">Sélectionner votre QR Dynamique</label>
                        <select name="dynamic_qr_id" id="dynamic_qr_id" class="form-select">
                            <option value="">-- Choisir parmi vos QR dynamiques --</option>
                            @foreach($clientDynamicQrs as $dqr)
                                <option value="{{ $dqr->id }}">
                                    {{ $dqr->code }} &bull; {{ $dqr->label }} ({{ $dqr->target_url }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('client.nfc-tags.index') }}" class="btn btn-sm btn-light border rounded-pill px-4">Annuler</a>
                        <button type="submit" class="btn btn-sm btn-primary-custom rounded-pill px-4 py-2 fw-bold shadow-xs">
                            <i class="bi bi-check-circle-fill me-1"></i>Enregistrer la Destination NFC
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white sticky-top" style="top: 20px;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-success mb-3 shadow-sm mx-auto" style="width: 64px; height: 64px; background: #f0fdf4;">
                    <i class="bi bi-broadcast fs-2"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0">{{ $nfcTag->label ?: $nfcTag->name }}</h6>
                <div class="text-muted small font-mono mt-1 mb-3">Puce NTAG216 sans contact</div>

                <div class="p-3 bg-light rounded-3 font-mono text-truncate small mb-3 text-start">
                    <span class="text-muted d-block" style="font-size: 0.65rem;">Cible active :</span>
                    <span class="text-success fw-bold">{{ $nfcTag->destination_url ?: $nfcTag->target_url }}</span>
                </div>

                <div class="text-muted small">
                    <i class="bi bi-check2-circle text-success me-1"></i>Compatible 100% avec tous les smartphones récents sans aucune application installée.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleTargetType(val) {
    const urlGroup = document.getElementById('urlFieldGroup');
    const cardGroup = document.getElementById('cardFieldGroup');
    const qrGroup = document.getElementById('qrFieldGroup');

    if (val === 'digital_card') {
        urlGroup.classList.add('d-none');
        cardGroup.classList.remove('d-none');
        qrGroup.classList.add('d-none');
    } else if (val === 'qr_code') {
        urlGroup.classList.add('d-none');
        cardGroup.classList.add('d-none');
        qrGroup.classList.remove('d-none');
    } else {
        urlGroup.classList.remove('d-none');
        cardGroup.classList.add('d-none');
        qrGroup.classList.add('d-none');
    }
}
</script>
@endpush
@endsection

