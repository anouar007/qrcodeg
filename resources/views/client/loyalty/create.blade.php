@extends('layouts.client')

@section('title', 'Générer une Carte de Fidélité')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Header Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('client.loyalty.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-1">
                <i class="bi bi-arrow-left me-1"></i> Retour aux cartes de fidélité
            </a>
            <h1 class="h3 fw-bold text-dark mb-0">Émettre une Carte de Fidélité</h1>
            <p class="text-muted small mb-0">Créez une carte avec QR Code dynamique et/ou puce NFC pour un client final.</p>
        </div>

        <!-- Quota indicator pill -->
        <div class="px-3 py-2 rounded-pill bg-white border shadow-xs d-flex align-items-center gap-2">
            <i class="bi bi-award text-purple"></i>
            <span class="small text-muted">Quota restant :</span>
            <span class="badge text-white font-mono rounded-pill px-2" style="background: #5736e9;">
                {{ $client->remainingLoyaltyCardsCount() }} / {{ $client->loyalty_card_limit ?? 20 }} disponibles
            </span>
        </div>
    </div>

    @if(!$client->canGenerateLoyaltyCard())
        <div class="alert alert-danger rounded-4 shadow-xs mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>
                    <strong>Plafond de cartes atteint !</strong>
                    <div>Votre quota maximal de {{ $client->loyalty_card_limit }} cartes est déjà atteint. Contactez l'administrateur pour débloquer des cartes supplémentaires.</div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-7">
            <div class="card border rounded-4 shadow-xs bg-white">
                <div class="card-body p-4">
                    <form action="{{ route('client.loyalty.store') }}" method="POST" id="loyaltyForm">
                        @csrf

                        <h5 class="fw-bold text-dark mb-3">1. Informations du Membre / Client Final</h5>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label fw-semibold text-dark small mb-1">
                                Nom Complet du Client <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="customer_name" id="customer_name" 
                                   value="{{ old('customer_name') }}" 
                                   class="form-control form-control-custom @error('customer_name') is-invalid @enderror" 
                                   placeholder="Ex: Sarah Benali, Karim El Amrani..." required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label fw-semibold text-dark small mb-1">
                                    Numéro de Téléphone / WhatsApp
                                </label>
                                <input type="text" name="customer_phone" id="customer_phone" 
                                       value="{{ old('customer_phone') }}" 
                                       class="form-control form-control-custom @error('customer_phone') is-invalid @enderror" 
                                       placeholder="+212 600 000 000">
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" style="font-size: 0.75rem;">Permet d'envoyer le pass directement par WhatsApp.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="customer_email" class="form-label fw-semibold text-dark small mb-1">
                                    Adresse Email (Optionnel)
                                </label>
                                <input type="email" name="customer_email" id="customer_email" 
                                       value="{{ old('customer_email') }}" 
                                       class="form-control form-control-custom @error('customer_email') is-invalid @enderror" 
                                       placeholder="client@domaine.com">
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-dark mb-3">2. Format de Support de la Carte (QR / NFC)</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small mb-2">Type de Carte</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="card p-3 border rounded-3 h-100 cursor-pointer text-center position-relative card-type-option selected-type" id="type_both_label">
                                        <input type="radio" name="card_type" value="both" class="form-check-input position-absolute top-2 start-2" checked onchange="updateType('both')">
                                        <i class="bi bi-qr-code fs-3 text-purple d-block mb-1"></i>
                                        <div class="fw-bold small text-dark">QR Code + NFC</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Le plus polyvalent</small>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="card p-3 border rounded-3 h-100 cursor-pointer text-center position-relative card-type-option" id="type_qr_label">
                                        <input type="radio" name="card_type" value="qr_code" class="form-check-input position-absolute top-2 start-2" {{ old('card_type') === 'qr_code' ? 'checked' : '' }} onchange="updateType('qr_code')">
                                        <i class="bi bi-phone fs-3 text-info d-block mb-1"></i>
                                        <div class="fw-bold small text-dark">QR Digital Seul</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Sur smartphone</small>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="card p-3 border rounded-3 h-100 cursor-pointer text-center position-relative card-type-option" id="type_nfc_label">
                                        <input type="radio" name="card_type" value="nfc" class="form-check-input position-absolute top-2 start-2" {{ old('card_type') === 'nfc' ? 'checked' : '' }} onchange="updateType('nfc')">
                                        <i class="bi bi-broadcast fs-3 text-warning d-block mb-1"></i>
                                        <div class="fw-bold small text-dark">Tag / Puce NFC</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Carte physique</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="nfc_uid_container">
                            <label for="nfc_uid" class="form-label fw-semibold text-dark small mb-1">
                                Identifiant UID du Tag NFC (Optionnel)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted font-mono"><i class="bi bi-broadcast"></i></span>
                                <input type="text" name="nfc_uid" id="nfc_uid" 
                                       value="{{ old('nfc_uid') }}" 
                                       class="form-control form-control-custom font-mono @error('nfc_uid') is-invalid @enderror" 
                                       placeholder="Ex: 04:A2:3B:5C:8D:6E:90">
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Si vous associez une carte NFC physique gravée ou un badge porte-clé.</small>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-dark mb-3">3. Règles de Tampons &amp; Récompense</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="max_stamps" class="form-label fw-semibold text-dark small mb-1">
                                    Nombre de Tampons Requis <span class="text-danger">*</span>
                                </label>
                                <select name="max_stamps" id="max_stamps" class="form-select form-control-custom" required onchange="updatePreview()">
                                    <option value="5" {{ old('max_stamps') == '5' ? 'selected' : '' }}>5 tampons</option>
                                    <option value="8" {{ old('max_stamps') == '8' ? 'selected' : '' }}>8 tampons</option>
                                    <option value="10" {{ old('max_stamps', '10') == '10' ? 'selected' : '' }}>10 tampons (Standard)</option>
                                    <option value="12" {{ old('max_stamps') == '12' ? 'selected' : '' }}>12 tampons</option>
                                    <option value="15" {{ old('max_stamps') == '15' ? 'selected' : '' }}>15 tampons</option>
                                    <option value="20" {{ old('max_stamps') == '20' ? 'selected' : '' }}>20 tampons</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="initial_stamps" class="form-label fw-semibold text-dark small mb-1">
                                    Tampon(s) de Bienvenue Offert(s)
                                </label>
                                <input type="number" name="initial_stamps" id="initial_stamps" 
                                       value="{{ old('initial_stamps', 0) }}" 
                                       min="0" max="20" 
                                       class="form-control form-control-custom" onchange="updatePreview()">
                                <small class="text-muted" style="font-size: 0.75rem;">Ex: Offrir 1 tampon dès la création pour fidéliser immédiatement.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reward_text" class="form-label fw-semibold text-dark small mb-1">
                                Intitulé de la Récompense <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="reward_text" id="reward_text" 
                                   value="{{ old('reward_text', '1 Boisson ou Produit Offert') }}" 
                                   class="form-control form-control-custom @error('reward_text') is-invalid @enderror" 
                                   placeholder="Ex: 1 Café offert, 15% de réduction, 1 Dessert au choix..." required oninput="updatePreview()">
                            @error('reward_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold text-dark small mb-1">
                                Notes Internes (Optionnel)
                            </label>
                            <textarea name="notes" id="notes" rows="2" class="form-control form-control-custom" placeholder="Notes réservées à votre équipe...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <a href="{{ route('client.loyalty.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Annuler
                            </a>
                            <button type="submit" class="btn text-white rounded-pill px-5 shadow-sm fw-semibold" style="background: #5736e9;" {{ !$client->canGenerateLoyaltyCard() ? 'disabled' : '' }}>
                                <i class="bi bi-check-lg me-1"></i> Générer la Carte Fidélité
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Visual Pass Mockup Column -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 2rem;">
                <h6 class="fw-bold text-dark text-uppercase tracking-wider small mb-3">
                    <i class="bi bi-eye me-1 text-purple"></i> Aperçu en Direct du Pass Client
                </h6>

                <!-- Apple Wallet Luxury Pass Card Preview -->
                <div class="card border-0 rounded-4 shadow-lg overflow-hidden text-white position-relative" 
                     style="background: linear-gradient(145deg, #180d38 0%, #301777 50%, #5736e9 100%);">
                    
                    <div class="p-4">
                        <!-- Top Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($client->company_name ?? 'C', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-white small leading-tight">{{ $client->company_name ?? 'Votre Établissement' }}</div>
                                    <small class="text-white-50" style="font-size: 0.68rem;">PROGRAMME VIP</small>
                                </div>
                            </div>
                            <span class="badge bg-white-20 text-white border-0 font-mono small rounded-pill px-2 py-0.5">
                                {{ $suggestedCode }}
                            </span>
                        </div>

                        <!-- Customer Name -->
                        <div class="mb-4 text-center py-2">
                            <small class="text-white-50 text-uppercase tracking-wider" style="font-size: 0.7rem;">TITULAIRE DE LA CARTE</small>
                            <h4 class="fw-bold text-white mb-0" id="previewCustomerName">Nom du Client</h4>
                            <span class="badge bg-success-subtle text-success border-0 px-2 py-0.5 rounded-pill small mt-1 font-mono" id="previewCardType">
                                QR Code + NFC Ready
                            </span>
                        </div>

                        <!-- Visual Stamps Area -->
                        <div class="bg-black-30 p-3 rounded-4 mb-3 backdrop-blur border border-white-10">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-white-75 fw-semibold" style="font-size: 0.75rem;">PROGRESSION DES TAMPONS</small>
                                <span class="badge bg-white text-dark font-mono fw-bold" id="previewStampsRatio">0 / 10</span>
                            </div>

                            <!-- Stamps Grid -->
                            <div class="d-flex flex-wrap gap-2 justify-content-center py-2" id="previewStampsGrid">
                                <!-- Populated dynamically by JS -->
                            </div>
                        </div>

                        <!-- Reward Box -->
                        <div class="p-3 rounded-3 mb-2 text-center" style="background: rgba(255,255,255,0.12); border: 1px dashed rgba(255,255,255,0.3);">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                                <i class="bi bi-gift-fill text-warning"></i>
                                <span class="small text-white-75 text-uppercase fw-semibold" style="font-size: 0.72rem;">RÉCOMPENSE À DÉBLOQUER</span>
                            </div>
                            <strong class="text-white d-block" id="previewRewardText">1 Boisson ou Produit Offert</strong>
                        </div>

                        <div class="text-center pt-2">
                            <small class="text-white-50" style="font-size: 0.72rem;">
                                <i class="bi bi-shield-check me-1"></i> Pass digital sécurisé &bull; Compatible smartphone &amp; sans contact
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Guidance Info Box -->
                <div class="card border rounded-4 p-3 mt-3 bg-white shadow-xs">
                    <div class="d-flex gap-2">
                        <i class="bi bi-info-circle text-primary fs-5"></i>
                        <small class="text-muted">
                            Une fois la carte émise, vous recevrez un lien unique et un QR code interactif que vous pourrez envoyer au client par WhatsApp ou imprimer sur un support physique NFC.
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<style>
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); }
.bg-black-30 { background-color: rgba(0, 0, 0, 0.3); }
.backdrop-blur { backdrop-filter: blur(8px); }
.cursor-pointer { cursor: pointer; }
.card-type-option { transition: all 0.2s ease; border-color: #e5e7eb; }
.card-type-option:hover { border-color: #5736e9; transform: translateY(-2px); }
.card-type-option.selected-type { border-color: #5736e9; background-color: rgba(87, 54, 233, 0.04); }
</style>

<script>
function updateType(type) {
    document.querySelectorAll('.card-type-option').forEach(el => el.classList.remove('selected-type'));
    const badge = document.getElementById('previewCardType');
    if (type === 'both') {
        document.getElementById('type_both_label').classList.add('selected-type');
        badge.textContent = 'QR Code + NFC Ready';
    } else if (type === 'qr_code') {
        document.getElementById('type_qr_label').classList.add('selected-type');
        badge.textContent = 'QR Pass Digital';
    } else {
        document.getElementById('type_nfc_label').classList.add('selected-type');
        badge.textContent = 'Badge NFC Sans-Contact';
    }
}

function updatePreview() {
    const nameInput = document.getElementById('customer_name');
    const rewardInput = document.getElementById('reward_text');
    const maxStampsInput = document.getElementById('max_stamps');
    const initialStampsInput = document.getElementById('initial_stamps');

    const name = nameInput.value.trim() || 'Nom du Client';
    const reward = rewardInput.value.trim() || '1 Boisson ou Produit Offert';
    const max = parseInt(maxStampsInput.value) || 10;
    const initial = parseInt(initialStampsInput.value) || 0;

    document.getElementById('previewCustomerName').textContent = name;
    document.getElementById('previewRewardText').textContent = reward;
    document.getElementById('previewStampsRatio').textContent = `${initial} / ${max}`;

    const grid = document.getElementById('previewStampsGrid');
    grid.innerHTML = '';
    const displayCount = Math.min(10, max);
    for (let i = 1; i <= displayCount; i++) {
        const circle = document.createElement('div');
        circle.style.width = '30px';
        circle.style.height = '30px';
        circle.style.borderRadius = '50%';
        circle.style.display = 'flex';
        circle.style.alignItems = 'center';
        circle.style.justifyContent = 'center';
        circle.style.fontSize = '0.75rem';
        circle.style.fontWeight = 'bold';
        circle.style.transition = 'all 0.2s ease';

        if (i <= initial) {
            circle.style.background = '#10b981';
            circle.style.color = '#fff';
            circle.innerHTML = '<i class="bi bi-check-lg"></i>';
        } else {
            circle.style.background = 'rgba(255,255,255,0.15)';
            circle.style.color = 'rgba(255,255,255,0.7)';
            circle.style.border = '1px dashed rgba(255,255,255,0.4)';
            circle.textContent = i;
        }
        grid.appendChild(circle);
    }
    if (max > 10) {
        const more = document.createElement('span');
        more.className = 'small text-white-50 align-self-center';
        more.textContent = `+${max - 10}`;
        grid.appendChild(more);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    document.getElementById('customer_name').addEventListener('input', updatePreview);
});
</script>
@endsection

