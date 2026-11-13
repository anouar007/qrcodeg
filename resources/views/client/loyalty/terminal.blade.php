@extends('layouts.client')

@section('title', 'Terminal Scanner POS - Points & Tampons')

@section('content')
<div class="container-fluid px-2 px-md-4 py-3">

    <!-- Top Status Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border px-2.5 py-1 rounded-pill small font-mono">
                    <span class="spinner-grow spinner-grow-sm me-1 text-success" role="status" style="width: 8px; height: 8px;"></span>
                    TERMINAL POS ACTIF
                </span>
                <h1 class="h4 fw-bold text-dark mb-0">Scanner &amp; Gestion des Points</h1>
            </div>
            <p class="text-muted small mb-0">
                Approchez la carte NFC du client ou scannez son QR Code pour créditer ou déduire des points en 1 seconde.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- PWA Native Install Button -->
            <button id="pwaInstallBtn" class="btn btn-sm text-white rounded-pill px-3 shadow-xs d-none" style="background: #10b981;" onclick="triggerPwaInstall()">
                <i class="bi bi-download me-1"></i> Installer l'App POS
            </button>

            <!-- Web NFC Encoder Button -->
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" onclick="openTerminalNfcEncoder()">
                <i class="bi bi-broadcast me-1"></i> Encodeur NFC
            </button>

            <a href="{{ route('client.loyalty.settings') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-xs">
                <i class="bi bi-sliders me-1"></i> Règles ({{ $settings->spend_amount_ratio }} {{ $settings->currency }} = {{ $settings->points_per_ratio }} pt)
            </a>
            <a href="{{ route('client.loyalty.index') }}" class="btn btn-sm btn-link text-decoration-none text-muted">
                <i class="bi bi-x-lg me-1"></i> Quitter
            </a>
        </div>
    </div>

    <!-- MAIN TERMINAL WORKSPACE -->
    <div class="row g-3">
        
        <!-- LEFT COLUMN: LIVE SCANNER & INPUT CONTROLS -->
        <div class="col-lg-5 col-xl-4">
            
            <div class="card border rounded-4 shadow-sm bg-white overflow-hidden mb-3">
                <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-camera-video fs-5 text-purple"></i>
                        <span class="fw-bold small">Scanner QR &amp; Récepteur NFC</span>
                    </div>
                    <span class="badge bg-secondary font-mono small" id="nfcStatusBadge">
                        <i class="bi bi-broadcast me-1"></i>NFC Prêt
                    </span>
                </div>

                <div class="card-body p-3 text-center">
                    
                    <!-- CAMERA SCANNER VIEWFINDER -->
                    <div class="position-relative rounded-4 overflow-hidden bg-black mb-3" style="min-height: 260px; max-height: 320px;">
                        <div id="qr-reader" style="width: 100%;"></div>
                        
                        <!-- Overlay Viewfinder Graphics -->
                        <div id="scannerOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center pointer-events-none" style="pointer-events: none;">
                            <div class="scanner-frame">
                                <div class="scanner-laser"></div>
                            </div>
                            <span class="badge bg-black-50 text-white mt-2 small backdrop-blur">
                                Cadrez le QR Code ici
                            </span>
                        </div>
                    </div>

                    <!-- Scanner Controls -->
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" id="startScanBtn" onclick="initQrScanner()">
                            <i class="bi bi-camera me-1"></i> Démarrer Caméra
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 d-none" id="stopScanBtn" onclick="stopQrScanner()">
                            <i class="bi bi-stop-circle me-1"></i> Arrêter
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2" onclick="toggleCameraSwitch()" title="Changer de caméra">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>

                    <div class="position-relative text-center my-3">
                        <hr class="m-0">
                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted font-mono">OU ENTRÉE MANUELLE</span>
                    </div>

                    <!-- Manual Quick Search Bar -->
                    <form onsubmit="handleManualSearch(event)" class="mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-upc-scan"></i>
                            </span>
                            <input type="text" id="manualCardInput" class="form-control border-start-0 ps-0 font-mono" placeholder="Code carte, tél ou UID NFC..." autofocus>
                            <button type="submit" class="btn text-white px-3 fw-bold" style="background: #5736e9;">
                                Valider
                            </button>
                        </div>
                    </form>

                    <!-- NFC Touch Prompt -->
                    <div class="p-2.5 rounded-3 bg-light border text-start d-flex align-items-center gap-2 small text-muted">
                        <i class="bi bi-broadcast text-warning fs-4 flex-shrink-0"></i>
                        <div style="font-size: 0.72rem;">
                            <strong>Lecteur NFC actif :</strong> Approchez une carte physique ou un badge NFC pour charger le profil client instantanément.
                        </div>
                    </div>

                </div>
            </div>

            <!-- Predefined Services Quick Selector for Cashier -->
            @if(!empty($settings->services_catalog))
                <div class="card border rounded-4 shadow-2xs bg-white mb-3">
                    <div class="card-header bg-white border-0 py-2.5 px-3">
                        <h6 class="fw-bold mb-0 text-dark small">
                            <i class="bi bi-grid-fill me-1 text-purple"></i> Prestations / Services Rapides
                        </h6>
                    </div>
                    <div class="card-body p-3 pt-0">
                        <div class="d-flex flex-wrap gap-1.5" id="servicesChipsContainer">
                            @foreach($settings->services_catalog as $svc)
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 small service-chip" 
                                        onclick="applyServiceChip('{{ addslashes($svc['name']) }}', {{ (float)($svc['price'] ?? 0) }}, {{ (int)$svc['points'] }})">
                                    <span class="fw-semibold">{{ $svc['name'] }}</span>
                                    <span class="badge bg-purple-subtle text-purple ms-1">+{{ $svc['points'] }} pts</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- RIGHT COLUMN: CLIENT IDENTITY & ACTIVE POINTS TRANSACTOR -->
        <div class="col-lg-7 col-xl-8">
            
            <!-- EMPTY STATE WHEN NO CARD IDENTIFIED YET -->
            <div id="noCardSelectedPanel" class="card border rounded-4 shadow-sm bg-white p-5 text-center {{ $initialCard ? 'd-none' : '' }}" style="min-height: 520px; display: flex; align-items: center; justify-content: center;">
                <div class="max-w-md mx-auto">
                    <div class="rounded-circle d-inline-flex p-4 mb-3" style="background: rgba(87, 54, 233, 0.08); color: #5736e9;">
                        <i class="bi bi-qr-code-scan display-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">En Attente de Scan Client</h4>
                    <p class="text-muted small mb-4">
                        Scannez le QR Code de la carte fidélité ou approchez la puce NFC pour créditer les points sur la facture du client ou appliquer une déduction.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2 small text-muted">
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="bi bi-calculator me-1"></i>Calcul automatique par montant</span>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="bi bi-scissors me-1"></i>Sélection par prestation</span>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="bi bi-gift me-1"></i>Déduction de remise directe</span>
                    </div>
                </div>
            </div>

            <!-- ACTIVE TRANSACTION PANEL (SHOWN ONCE CARD IS IDENTIFIED) -->
            <div id="activeCardPanel" class="card border rounded-4 shadow-sm bg-white overflow-hidden {{ $initialCard ? '' : 'd-none' }}">
                
                <!-- Customer Header Banner -->
                <div class="p-4 text-white position-relative" style="background: linear-gradient(135deg, #180d38 0%, #301777 50%, #5736e9 100%);">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center fw-bold fs-4 flex-shrink-0" style="width: 52px; height: 52px;" id="clientAvatarLetter">
                                {{ $initialCard ? substr($initialCard->customer_name, 0, 1) : 'C' }}
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold text-white mb-0" id="cardCustomerName">{{ $initialCard->customer_name ?? 'Client' }}</h3>
                                    <span class="badge bg-white-20 text-white font-mono small rounded-pill" id="cardCodeBadge">{{ $initialCard->card_code ?? 'LC-XXXX-XXXX' }}</span>
                                </div>
                                <div class="small text-white-75 mt-1">
                                    <span id="cardCustomerPhone"><i class="bi bi-telephone me-1"></i>{{ $initialCard->customer_phone ?? 'Sans téléphone' }}</span>
                                    <span class="ms-2 font-mono" id="cardNfcUid" style="font-size: 0.72rem;">{{ $initialCard && $initialCard->nfc_uid ? 'UID: ' . $initialCard->nfc_uid : '' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Balances Overview -->
                        <div class="text-sm-end bg-black-30 p-2.5 px-3 rounded-3 border border-white-10">
                            <small class="text-white-50 text-uppercase d-block" style="font-size: 0.65rem;">SOLDE DE POINTS ACTUEL</small>
                            <span class="display-6 fw-bold text-white font-mono" id="cardPointsBalance">{{ $initialCard->points_balance ?? 0 }}</span>
                            <span class="text-white-75 small font-mono">pts</span>
                            <div class="text-warning small fw-bold font-mono" id="cardPointsValue">
                                &approx; {{ $initialCard ? $settings->calculateDiscountForPoints($initialCard->points_balance) : 0 }} {{ $settings->currency }} de remise
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Tabs & Forms -->
                <div class="card-body p-4">
                    
                    <!-- Reward Ready Banner if applicable -->
                    <div id="rewardReadyAlert" class="alert alert-success border-0 rounded-4 p-3 mb-3 d-flex justify-content-between align-items-center {{ $initialCard && $initialCard->isRewardAvailable() ? '' : 'd-none' }}" style="background: #10b981; color: white;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-gift-fill fs-4"></i>
                            <div>
                                <strong class="d-block">Cadeau Fidélité Prêt !</strong>
                                <small class="text-white-75" id="rewardTextDescription">{{ $initialCard->reward_text ?? 'Récompense' }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light text-success fw-bold rounded-pill px-3 shadow-xs" onclick="processQuickRedeem()">
                            Réclamer Récompense
                        </button>
                    </div>

                    <!-- Navigation Action Tabs -->
                    <ul class="nav nav-pills nav-fill bg-light p-1.5 rounded-pill mb-4" id="terminalTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill fw-bold py-2 small" id="tab-add-points-btn" data-bs-toggle="pill" data-bs-target="#tab-add-points" type="button">
                                <i class="bi bi-plus-circle me-1 text-success"></i> Créditer des Points (Ajouter)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill fw-bold py-2 small" id="tab-deduct-points-btn" data-bs-toggle="pill" data-bs-target="#tab-deduct-points" type="button">
                                <i class="bi bi-dash-circle me-1 text-danger"></i> Déduire des Points (Remise)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill fw-bold py-2 small" id="tab-stamp-btn" data-bs-toggle="pill" data-bs-target="#tab-stamp" type="button">
                                <i class="bi bi-check2-circle me-1 text-purple"></i> Tamponner (+1)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="terminalTabsContent">
                        
                        <!-- TAB 1: ADD POINTS (SPEND / SERVICES / MANUAL) -->
                        <div class="tab-pane fade show active" id="tab-add-points" role="tabpanel">
                            
                            <!-- Sub-methods selector: Spend or Services or Manual -->
                            <div class="btn-group w-100 mb-3" role="group">
                                <input type="radio" class="btn-check" name="add_method" id="method_spend" value="spend" checked onchange="toggleAddMethod('spend')">
                                <label class="btn btn-outline-primary small py-2 fw-semibold" for="method_spend">
                                    <i class="bi bi-cash me-1"></i> Par Montant Dépensé (Facture)
                                </label>

                                <input type="radio" class="btn-check" name="add_method" id="method_services" value="services" onchange="toggleAddMethod('services')">
                                <label class="btn btn-outline-primary small py-2 fw-semibold" for="method_services">
                                    <i class="bi bi-scissors me-1"></i> Par Prestation / Services
                                </label>

                                <input type="radio" class="btn-check" name="add_method" id="method_manual" value="manual" onchange="toggleAddMethod('manual')">
                                <label class="btn btn-outline-primary small py-2 fw-semibold" for="method_manual">
                                    <i class="bi bi-123 me-1"></i> Saisie Directe Points
                                </label>
                            </div>

                            <!-- Method 1: By Spend Amount -->
                            <div id="addSpendSection">
                                <div class="p-3.5 rounded-4 bg-light border mb-3">
                                    <label class="form-label fw-bold text-dark mb-1">Montant Total Payé par le Client</label>
                                    <div class="input-group input-group-lg mb-2">
                                        <input type="number" step="0.5" id="spendBillAmount" class="form-control font-mono fw-bold fs-3 text-dark" placeholder="0.00" oninput="calculateSpendPoints()">
                                        <span class="input-group-text bg-white font-mono currency-badge fw-bold fs-5">{{ $settings->currency }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center small">
                                        <span class="text-muted">Règle active : 1 pt pour chaque {{ $settings->spend_amount_ratio }} {{ $settings->currency }}</span>
                                        <span class="fw-bold text-success fs-5 font-mono" id="spendCalculatedResult">+0 Points</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 2: By Predefined Services / Items -->
                            <div id="addServicesSection" class="d-none">
                                <div class="p-3.5 rounded-4 bg-light border mb-3">
                                    <label class="form-label fw-bold text-dark mb-2">Sélectionnez les Prestations Effectuées</label>
                                    <div class="d-flex flex-wrap gap-2 mb-3" id="serviceSelectChips">
                                        @foreach($settings->services_catalog ?? [] as $s)
                                            <button type="button" class="btn btn-outline-dark rounded-pill px-3 py-1.5 small service-picker-btn" 
                                                    data-name="{{ $s['name'] }}" 
                                                    data-points="{{ $s['points'] }}" 
                                                    onclick="toggleServicePicker(this)">
                                                {{ $s['name'] }} <span class="badge bg-purple-subtle text-purple ms-1">+{{ $s['points'] }} pts</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center small pt-2 border-top">
                                        <span class="text-muted" id="selectedServicesSummary">Aucun service sélectionné</span>
                                        <span class="fw-bold text-success fs-5 font-mono" id="servicesCalculatedResult">+0 Points</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 3: Manual Direct Points -->
                            <div id="addManualSection" class="d-none">
                                <div class="p-3.5 rounded-4 bg-light border mb-3">
                                    <label class="form-label fw-bold text-dark mb-1">Nombre de Points à Créditer</label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" id="manualPointsAmount" min="1" class="form-control font-mono fw-bold fs-3 text-dark" placeholder="10">
                                        <span class="input-group-text bg-white font-mono">Points</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="text" id="addPointsNotes" class="form-control" placeholder="Note ou motif optionnel (ex: Ticket #1042, Promo Weekend...)">
                            </div>

                            <button type="button" class="btn w-100 py-3 rounded-pill text-white fw-bold fs-5 shadow-sm" style="background: #5736e9;" onclick="submitAddPoints()">
                                <i class="bi bi-check-circle-fill me-1"></i> Valider &amp; Créditer les Points
                            </button>

                        </div>

                        <!-- TAB 2: DEDUCT / REDEEM POINTS (DISCOUNT) -->
                        <div class="tab-pane fade" id="tab-deduct-points" role="tabpanel">
                            <div class="p-3.5 rounded-4 bg-light border mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold text-dark mb-0">Points à Déduire / Utiliser</label>
                                    <span class="small text-muted font-mono">Solde max : <strong id="maxDeductiblePts">0</strong> pts</span>
                                </div>

                                <div class="input-group input-group-lg mb-2">
                                    <input type="number" id="deductPointsAmount" min="1" class="form-control font-mono fw-bold fs-3 text-danger" placeholder="0" oninput="calculateDiscountResult()">
                                    <span class="input-group-text bg-white font-mono">Points</span>
                                </div>

                                <!-- Quick Percentage Chips -->
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDeductPointsFraction(0.25)">25%</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDeductPointsFraction(0.50)">50%</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDeductPointsFraction(1.00)">Tout Utiliser (100%)</button>
                                </div>

                                <!-- Live Discount Conversion Result -->
                                <div class="p-3 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted text-uppercase d-block" style="font-size: 0.68rem;">REMISE DÉDUITE SUR L'ADDITION</small>
                                        <span class="small text-dark">Barème : {{ $settings->redemption_points }} pts = {{ $settings->redemption_value }} {{ $settings->currency }}</span>
                                    </div>
                                    <span class="display-6 fw-bold text-success font-mono" id="deductDiscountResult">-0.00 {{ $settings->currency }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="text" id="deductPointsNotes" class="form-control" placeholder="Note ou référence facture (ex: Remise déduite sur Addition #410)">
                            </div>

                            <button type="button" class="btn btn-danger w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm" onclick="submitDeductPoints()">
                                <i class="bi bi-dash-circle-fill me-1"></i> Valider la Déduction de Remise
                            </button>
                        </div>

                        <!-- TAB 3: STAMP PUNCH (+1) -->
                        <div class="tab-pane fade" id="tab-stamp" role="tabpanel">
                            <div class="p-4 bg-light rounded-4 border text-center mb-3">
                                <div class="d-flex justify-content-between align-items-center small mb-3">
                                    <span class="text-muted">Progression actuelle :</span>
                                    <span class="font-mono fw-bold text-dark fs-5" id="stampCounterDisplay">0 / 10</span>
                                </div>

                                <div class="progress rounded-pill mb-3" style="height: 10px;">
                                    <div class="progress-bar" id="stampProgressBar" style="width: 0%; background: #5736e9;"></div>
                                </div>

                                <p class="text-muted small mb-4">
                                    Récompense : <strong class="text-purple" id="stampRewardLabel">{{ $initialCard->reward_text ?? 'Récompense' }}</strong>
                                </p>

                                <button type="button" class="btn text-white w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm" style="background: #5736e9;" onclick="submitAddStamp()">
                                    <i class="bi bi-plus-circle me-1"></i> Valider +1 Tampon
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Client Profile Footer Links -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top mt-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="#" id="viewFullCardLink" target="_blank" class="btn btn-sm btn-link text-decoration-none text-muted p-0">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Fiche client
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="encodeCurrentMemberNfc()">
                                <i class="bi bi-broadcast me-1"></i> Graver Puce NFC
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="clearActiveCard()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Nouveau Scan (Client Suivant)
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- HTML5-QRCode Library via CDN for rock-solid camera scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
.bg-black-30 { background-color: rgba(0, 0, 0, 0.3); }
.bg-black-50 { background-color: rgba(0, 0, 0, 0.5); }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); }
.backdrop-blur { backdrop-filter: blur(8px); }

/* Scanner Laser Animation */
.scanner-frame {
    width: 200px;
    height: 200px;
    border: 2px solid rgba(87, 54, 233, 0.85);
    border-radius: 20px;
    position: relative;
    box-shadow: 0 0 25px rgba(87, 54, 233, 0.4);
    overflow: hidden;
}
.scanner-laser {
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #ec4899 50%, transparent 100%);
    box-shadow: 0 0 10px #ec4899;
    position: absolute;
    top: 0;
    animation: scanningLaser 2s infinite ease-in-out;
}
@keyframes scanningLaser {
    0% { top: 5%; opacity: 0.2; }
    50% { top: 92%; opacity: 1; }
    100% { top: 5%; opacity: 0.2; }
}

.service-picker-btn.active {
    background-color: #5736e9 !important;
    border-color: #5736e9 !important;
    color: white !important;
}
.service-picker-btn.active .badge {
    background-color: white !important;
    color: #5736e9 !important;
}
</style>

<script>
// Active card in state
let currentCard = @json($initialCard);
let activeSettings = @json($settings);
let html5QrScanner = null;
let currentCameraIndex = 0;
let availableCameras = [];
let selectedServices = [];

// Synthesize pleasant POS sound effects via Web Audio API (no assets needed)
function playBeep(type = 'success') {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);

        if (type === 'success') {
            osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
            osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.08); // A5
            gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.25);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 0.25);
        } else if (type === 'error') {
            osc.frequency.setValueAtTime(220, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 0.3);
        }
    } catch(e) {}
}

// -------------------------------------------------------------
// 1. QR CODE CAMERA SCANNER
// -------------------------------------------------------------
function initQrScanner() {
    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
            availableCameras = devices;
            // Prefer back camera if available
            let camId = devices[0].id;
            const backCam = devices.find(d => d.label.toLowerCase().includes('back') || d.label.toLowerCase().includes('arriere') || d.label.toLowerCase().includes('rear'));
            if (backCam) camId = backCam.id;

            html5QrScanner = new Html5Qrcode("qr-reader");
            html5QrScanner.start(
                camId,
                { fps: 10, qrbox: { width: 220, height: 220 } },
                (decodedText) => onQrCodeDetected(decodedText),
                (errorMessage) => {}
            ).then(() => {
                document.getElementById('startScanBtn').classList.add('d-none');
                document.getElementById('stopScanBtn').classList.remove('d-none');
                document.getElementById('scannerOverlay').classList.remove('d-none');
            }).catch(err => {
                alert('Impossible d\'accéder à la caméra : ' + err);
            });
        } else {
            alert('Aucune caméra détectée sur cet appareil.');
        }
    }).catch(err => {
        alert('Erreur lors de la détection de caméra : ' + err);
    });
}

function stopQrScanner() {
    if (html5QrScanner) {
        html5QrScanner.stop().then(() => {
            document.getElementById('startScanBtn').classList.remove('d-none');
            document.getElementById('stopScanBtn').classList.add('d-none');
            document.getElementById('scannerOverlay').classList.add('d-none');
        });
    }
}

function toggleCameraSwitch() {
    if (availableCameras.length > 1 && html5QrScanner) {
        currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
        const nextCamId = availableCameras[currentCameraIndex].id;
        html5QrScanner.stop().then(() => {
            html5QrScanner.start(
                nextCamId,
                { fps: 10, qrbox: { width: 220, height: 220 } },
                (decodedText) => onQrCodeDetected(decodedText),
                (errorMessage) => {}
            );
        });
    }
}

function onQrCodeDetected(rawText) {
    playBeep('success');
    
    // Extract card_code from URL if public pass URL was scanned (e.g. https://domain.com/loyalty/LC-XXXX-XXXX)
    let cardCode = rawText.trim();
    if (cardCode.includes('/loyalty/')) {
        const parts = cardCode.split('/loyalty/');
        cardCode = parts[parts.length - 1];
    }
    
    fetchCardDetails(cardCode);
}

// -------------------------------------------------------------
// 2. NFC TOUCH LISTENER (Web NFC NDEFReader)
// -------------------------------------------------------------
function initNfcListener() {
    const badge = document.getElementById('nfcStatusBadge');
    if ('NDEFReader' in window) {
        try {
            const ndef = new NDEFReader();
            ndef.scan().then(() => {
                badge.className = 'badge bg-success font-mono small';
                badge.innerHTML = '<i class="bi bi-broadcast me-1"></i>NFC Écoute Active';

                ndef.onreading = event => {
                    playBeep('success');
                    const serialNumber = event.serialNumber;
                    if (serialNumber) {
                        fetchCardDetails(serialNumber);
                    }
                };
            }).catch(err => {
                badge.className = 'badge bg-secondary font-mono small';
                badge.innerHTML = '<i class="bi bi-broadcast me-1"></i>NFC Disponible';
            });
        } catch(e) {}
    } else {
        badge.className = 'badge bg-light text-muted border font-mono small';
        badge.innerHTML = '<i class="bi bi-broadcast me-1"></i>NFC Prêt (USB/HID)';
    }
}

// -------------------------------------------------------------
// 3. MANUAL SEARCH & AJAX LOOKUP
// -------------------------------------------------------------
function handleManualSearch(e) {
    e.preventDefault();
    const query = document.getElementById('manualCardInput').value.trim();
    if (!query) return;
    fetchCardDetails(query);
}

function fetchCardDetails(code) {
    fetch('{{ route("client.loyalty.terminal.lookup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            playBeep('success');
            renderCardData(data.card, data.settings);
        } else {
            playBeep('error');
            alert(data.message || 'Carte non trouvée.');
        }
    })
    .catch(err => {
        playBeep('error');
        alert('Erreur de communication avec le serveur.');
    });
}

function renderCardData(card, settings) {
    currentCard = card;
    if (settings) activeSettings = settings;

    document.getElementById('noCardSelectedPanel').classList.add('d-none');
    document.getElementById('activeCardPanel').classList.remove('d-none');

    document.getElementById('clientAvatarLetter').textContent = card.customer_name ? card.customer_name.charAt(0).toUpperCase() : 'C';
    document.getElementById('cardCustomerName').textContent = card.customer_name;
    document.getElementById('cardCodeBadge').textContent = card.card_code;
    document.getElementById('cardCustomerPhone').innerHTML = `<i class="bi bi-telephone me-1"></i>${card.customer_phone || 'Sans numéro'}`;
    document.getElementById('cardNfcUid').textContent = card.nfc_uid ? `NFC: ${card.nfc_uid}` : '';

    // Balances
    document.getElementById('cardPointsBalance').textContent = card.points_balance;
    document.getElementById('cardPointsValue').textContent = `≈ ${card.points_discount_value} ${card.currency} de remise`;
    document.getElementById('maxDeductiblePts').textContent = card.points_balance;

    // Stamps
    document.getElementById('stampCounterDisplay').textContent = `${card.stamps_count} / ${card.max_stamps}`;
    document.getElementById('stampProgressBar').style.width = `${card.progress_percentage}%`;
    document.getElementById('stampRewardLabel').textContent = card.reward_text;

    // Reward banner
    const rewardAlert = document.getElementById('rewardReadyAlert');
    if (card.is_reward_available) {
        rewardAlert.classList.remove('d-none');
        document.getElementById('rewardTextDescription').textContent = card.reward_text;
    } else {
        rewardAlert.classList.add('d-none');
    }

    // View link
    document.getElementById('viewFullCardLink').href = `/client/loyalty/${card.id}`;

    // Reset inputs
    document.getElementById('spendBillAmount').value = '';
    document.getElementById('spendCalculatedResult').textContent = '+0 Points';
    document.getElementById('deductPointsAmount').value = '';
    document.getElementById('deductDiscountResult').textContent = `-0.00 ${activeSettings.currency}`;
    selectedServices = [];
    document.querySelectorAll('.service-picker-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('servicesCalculatedResult').textContent = '+0 Points';
    document.getElementById('selectedServicesSummary').textContent = 'Aucun service sélectionné';
}

function clearActiveCard() {
    currentCard = null;
    document.getElementById('activeCardPanel').classList.add('d-none');
    document.getElementById('noCardSelectedPanel').classList.remove('d-none');
    document.getElementById('manualCardInput').value = '';
    document.getElementById('manualCardInput').focus();
}

// -------------------------------------------------------------
// 4. POINT CALCULATION MODES (SPEND / SERVICES / MANUAL)
// -------------------------------------------------------------
function toggleAddMethod(method) {
    document.getElementById('addSpendSection').classList.add('d-none');
    document.getElementById('addServicesSection').classList.add('d-none');
    document.getElementById('addManualSection').classList.add('d-none');

    if (method === 'spend') {
        document.getElementById('addSpendSection').classList.remove('d-none');
    } else if (method === 'services') {
        document.getElementById('addServicesSection').classList.remove('d-none');
    } else {
        document.getElementById('addManualSection').classList.remove('d-none');
    }
}

function calculateSpendPoints() {
    const amount = parseFloat(document.getElementById('spendBillAmount').value) || 0;
    const ratio = parseFloat(activeSettings.spend_amount_ratio) || 10;
    const ptsPerRatio = parseInt(activeSettings.points_per_ratio) || 1;
    const minSpend = parseFloat(activeSettings.min_spend_amount) || 0;

    let pts = 0;
    if (amount >= minSpend && ratio > 0) {
        pts = Math.floor(amount / ratio) * ptsPerRatio;
    }
    document.getElementById('spendCalculatedResult').textContent = `+${pts} Points`;
}

function toggleServicePicker(btn) {
    const name = btn.getAttribute('data-name');
    const pts = parseInt(btn.getAttribute('data-points'));

    if (btn.classList.contains('active')) {
        btn.classList.remove('active');
        selectedServices = selectedServices.filter(s => s.name !== name);
    } else {
        btn.classList.add('active');
        selectedServices.push({ name: name, points: pts });
    }

    const totalPts = selectedServices.reduce((acc, curr) => acc + curr.points, 0);
    document.getElementById('servicesCalculatedResult').textContent = `+${totalPts} Points`;
    document.getElementById('selectedServicesSummary').textContent = selectedServices.length > 0 
        ? `${selectedServices.length} prestation(s) sélectionnée(s)` 
        : 'Aucun service sélectionné';
}

function applyServiceChip(name, price, points) {
    if (!currentCard) {
        alert('Veuillez d\'abord scanner ou rechercher la carte du client.');
        return;
    }
    // Switch to tab 1 and services mode
    document.getElementById('tab-add-points-btn').click();
    document.getElementById('method_services').checked = true;
    toggleAddMethod('services');

    // Auto-select chip
    const chipBtn = Array.from(document.querySelectorAll('.service-picker-btn')).find(b => b.getAttribute('data-name') === name);
    if (chipBtn) {
        toggleServicePicker(chipBtn);
    }
}

// -------------------------------------------------------------
// 5. DEDUCT POINTS & DISCOUNT CALCULATION
// -------------------------------------------------------------
function calculateDiscountResult() {
    const pts = parseInt(document.getElementById('deductPointsAmount').value) || 0;
    const rPts = parseInt(activeSettings.redemption_points) || 100;
    const rVal = parseFloat(activeSettings.redemption_value) || 10;
    const discount = rPts > 0 ? ((pts / rPts) * rVal).toFixed(2) : '0.00';
    document.getElementById('deductDiscountResult').textContent = `-${discount} ${activeSettings.currency}`;
}

function setDeductPointsFraction(fraction) {
    if (!currentCard) return;
    const pts = Math.floor(currentCard.points_balance * fraction);
    document.getElementById('deductPointsAmount').value = pts;
    calculateDiscountResult();
}

// -------------------------------------------------------------
// 6. PROCESS TRANSACTIONS VIA AJAX
// -------------------------------------------------------------
function submitAddPoints() {
    if (!currentCard) return;

    const method = document.querySelector('input[name="add_method"]:checked').value;
    let payload = {
        card_id: currentCard.id,
        notes: document.getElementById('addPointsNotes').value.trim()
    };

    if (method === 'spend') {
        const amount = parseFloat(document.getElementById('spendBillAmount').value);
        if (!amount || amount <= 0) {
            alert('Veuillez saisir un montant de facture valide.');
            return;
        }
        payload.operation = 'add_spend';
        payload.amount = amount;
    } else if (method === 'services') {
        const totalPts = selectedServices.reduce((acc, curr) => acc + curr.points, 0);
        if (totalPts <= 0) {
            alert('Veuillez sélectionner au moins une prestation.');
            return;
        }
        payload.operation = 'add_services';
        payload.points = totalPts;
        payload.notes = selectedServices.map(s => s.name).join(', ') + (payload.notes ? ' - ' + payload.notes : '');
    } else {
        const pts = parseInt(document.getElementById('manualPointsAmount').value);
        if (!pts || pts <= 0) {
            alert('Veuillez saisir un nombre de points valide.');
            return;
        }
        payload.operation = 'add_manual';
        payload.points = pts;
    }

    sendProcessRequest(payload);
}

function submitDeductPoints() {
    if (!currentCard) return;
    const pts = parseInt(document.getElementById('deductPointsAmount').value);
    if (!pts || pts <= 0) {
        alert('Veuillez saisir un nombre de points à déduire.');
        return;
    }
    if (pts > currentCard.points_balance) {
        alert(`Solde insuffisant ! Le client ne dispose que de ${currentCard.points_balance} points.`);
        return;
    }

    sendProcessRequest({
        card_id: currentCard.id,
        operation: 'deduct_points',
        points: pts,
        notes: document.getElementById('deductPointsNotes').value.trim()
    });
}

function submitAddStamp() {
    if (!currentCard) return;
    sendProcessRequest({
        card_id: currentCard.id,
        operation: 'add_stamp'
    });
}

function processQuickRedeem() {
    if (!currentCard) return;
    fetch(`/client/loyalty/${currentCard.id}/redeem`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            playBeep('success');
            alert(data.message || 'Récompense débloquée avec succès !');
            currentCard.stamps_count = data.stamps_count;
            currentCard.is_reward_available = false;
            renderCardData(currentCard);
        } else {
            playBeep('error');
            alert(data.message || 'Erreur lors de la réclamation.');
        }
    });
}

function sendProcessRequest(payload) {
    fetch('{{ route("client.loyalty.terminal.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            playBeep('success');
            alert(data.message);
            // Update local state and re-render
            renderCardData(data.card);
        } else {
            playBeep('error');
            alert(data.message || 'Erreur lors du traitement.');
        }
    })
    .catch(err => {
        playBeep('error');
        alert('Erreur serveur lors de l\'enregistrement de la transaction.');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initNfcListener();
    @if($initialCard)
        calculateSpendPoints();
    @endif
});

// -------------------------------------------------------------
// 7. PWA INSTALLATION PROMPT & OFFLINE DETECTION
// -------------------------------------------------------------
let deferredPwaPrompt = null;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPwaPrompt = e;
    const installBtn = document.getElementById('pwaInstallBtn');
    if (installBtn) {
        installBtn.classList.remove('d-none');
    }
});

function triggerPwaInstall() {
    if (deferredPwaPrompt) {
        deferredPwaPrompt.prompt();
        deferredPwaPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                document.getElementById('pwaInstallBtn').classList.add('d-none');
            }
            deferredPwaPrompt = null;
        });
    } else {
        alert("Pour installer cette application sur votre écran d'accueil :\n- Sur iPhone/iPad (Safari) : Appuyez sur Partager (carré avec flèche) puis « Sur l'écran d'accueil ».\n- Sur Android : Ouvrez le menu ⋮ du navigateur puis « Installer l'application ».");
    }
}

// -------------------------------------------------------------
// 8. BROWSER NFC ENCODER
// -------------------------------------------------------------
function openTerminalNfcEncoder() {
    if (currentCard) {
        encodeCurrentMemberNfc();
    } else {
        const url = prompt("Entrez l'URL à graver sur la puce NFC :", window.location.origin);
        if (url) {
            window.nfcEncoder.writeUrl(url, 'Puce NFC Personnalisée');
        }
    }
}

function encodeCurrentMemberNfc() {
    if (!currentCard) {
        alert('Veuillez d\'abord scanner ou rechercher un membre.');
        return;
    }

    window.nfcEncoder.writeUrl(
        currentCard.public_url,
        `Pass VIP - ${currentCard.customer_name}`,
        {
            bindEndpoint: `/client/loyalty/${currentCard.id}/bind-nfc`,
            onSuccess: (uid) => {
                currentCard.nfc_uid = uid;
                document.getElementById('cardNfcUid').textContent = `NFC: ${uid}`;
            }
        }
    );
}
</script>
@endsection

