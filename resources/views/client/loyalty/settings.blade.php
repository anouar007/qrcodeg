@extends('layouts.client')

@section('title', 'Gestion des Points & Règles de Calcul')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('client.loyalty.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-1">
                <i class="bi bi-arrow-left me-1"></i> Retour aux cartes de fidélité
            </a>
            <h1 class="h3 fw-bold text-dark mb-0">Règles de Calcul des Points</h1>
            <p class="text-muted small mb-0">
                Configurez le barème de points selon le montant dépensé par vos clients ou par prestations et services effectués.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('client.loyalty.terminal') }}" class="btn text-white rounded-pill px-4 shadow-sm" style="background: #5736e9;">
                <i class="bi bi-upc-scan me-1"></i> Ouvrir le Terminal Scanner POS
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-xs mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('client.loyalty.settings.update') }}" method="POST" id="loyaltySettingsForm">
        @csrf

        <div class="row g-4">
            
            <!-- Left Column: Calculation Rules Configuration -->
            <div class="col-lg-7">
                
                <!-- 1. GENERAL ACTIVATION & CALCULATION MODE -->
                <div class="card border rounded-4 shadow-xs bg-white mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">1. Mode de Calcul des Points</h5>
                                <small class="text-muted">Choisissez comment vos clients cumulent leurs points fidélité.</small>
                            </div>
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" name="points_enabled" id="points_enabled" value="1" {{ old('points_enabled', $settings->points_enabled) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="card p-3 border rounded-3 h-100 cursor-pointer text-center calculation-card {{ old('calculation_mode', $settings->calculation_mode) === 'spend_amount' ? 'border-primary bg-light-primary' : '' }}">
                                    <input type="radio" name="calculation_mode" value="spend_amount" class="d-none" {{ old('calculation_mode', $settings->calculation_mode) === 'spend_amount' ? 'checked' : '' }} onchange="onModeChange('spend_amount')">
                                    <i class="bi bi-cash-coin fs-3 text-purple d-block mb-1"></i>
                                    <strong class="d-block small text-dark">Montant Dépensé</strong>
                                    <small class="text-muted" style="font-size: 0.7rem;">Points selon la facture</small>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="card p-3 border rounded-3 h-100 cursor-pointer text-center calculation-card {{ old('calculation_mode', $settings->calculation_mode) === 'services' ? 'border-primary bg-light-primary' : '' }}">
                                    <input type="radio" name="calculation_mode" value="services" class="d-none" {{ old('calculation_mode', $settings->calculation_mode) === 'services' ? 'checked' : '' }} onchange="onModeChange('services')">
                                    <i class="bi bi-scissors fs-3 text-info d-block mb-1"></i>
                                    <strong class="d-block small text-dark">Services / Articles</strong>
                                    <small class="text-muted" style="font-size: 0.7rem;">Points par prestation</small>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="card p-3 border rounded-3 h-100 cursor-pointer text-center calculation-card {{ old('calculation_mode', $settings->calculation_mode) === 'both' ? 'border-primary bg-light-primary' : '' }}">
                                    <input type="radio" name="calculation_mode" value="both" class="d-none" {{ old('calculation_mode', $settings->calculation_mode) === 'both' ? 'checked' : '' }} onchange="onModeChange('both')">
                                    <i class="bi bi-layers-half fs-3 text-success d-block mb-1"></i>
                                    <strong class="d-block small text-dark">Hybride (Les Deux)</strong>
                                    <small class="text-muted" style="font-size: 0.7rem;">Montant &amp; Prestations</small>
                                </label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="currency" class="form-label fw-semibold text-dark small mb-1">Devise Monétaire</label>
                                <input type="text" name="currency" id="currency" value="{{ old('currency', $settings->currency) }}" class="form-control form-control-custom" placeholder="DH, MAD, EUR, USD..." required oninput="updateFormulaPreview()">
                                <small class="text-muted" style="font-size: 0.72rem;">Symbole affiché sur vos reçus et passes clients.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="min_spend_amount" class="form-label fw-semibold text-dark small mb-1">Achat Minimum pour Cumuler</label>
                                <div class="input-group">
                                    <input type="number" step="0.5" name="min_spend_amount" id="min_spend_amount" value="{{ old('min_spend_amount', $settings->min_spend_amount) }}" class="form-control form-control-custom" placeholder="0">
                                    <span class="input-group-text bg-light currency-badge">{{ $settings->currency }}</span>
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">0 = aucun seuil minimum requis.</small>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 2. SPEND-BASED CONVERSION FORMULA -->
                <div class="card border rounded-4 shadow-xs bg-white mb-4" id="spendRuleCard">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-calculator text-purple fs-5"></i>
                            <h5 class="fw-bold text-dark mb-0">2. Barème d'Attribution par Dépense</h5>
                        </div>
                        <p class="text-muted small mb-3">Définissez combien de points le client reçoit pour chaque tranche d'achat.</p>

                        <div class="p-3 rounded-4 mb-3" style="background: rgba(87, 54, 233, 0.05); border: 1px dashed rgba(87, 54, 233, 0.25);">
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-5">
                                    <label class="form-label small text-muted mb-1">Pour chaque tranche de :</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" name="spend_amount_ratio" id="spend_amount_ratio" value="{{ old('spend_amount_ratio', $settings->spend_amount_ratio) }}" class="form-control form-control-custom fw-bold font-mono" required oninput="updateFormulaPreview()">
                                        <span class="input-group-text bg-white font-mono currency-badge">{{ $settings->currency }}</span>
                                    </div>
                                </div>

                                <div class="col-sm-2 text-center text-muted fw-bold">
                                    <i class="bi bi-arrow-right fs-4 d-none d-sm-inline"></i>
                                    <i class="bi bi-arrow-down fs-4 d-sm-none"></i>
                                </div>

                                <div class="col-sm-5">
                                    <label class="form-label small text-muted mb-1">Le client gagne :</label>
                                    <div class="input-group">
                                        <input type="number" name="points_per_ratio" id="points_per_ratio" value="{{ old('points_per_ratio', $settings->points_per_ratio) }}" min="1" class="form-control form-control-custom fw-bold font-mono" required oninput="updateFormulaPreview()">
                                        <span class="input-group-text bg-white font-mono">Point(s)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 p-2 bg-white rounded-3 small text-dark d-flex align-items-center justify-content-between">
                                <span class="text-muted">Exemple en caisse :</span>
                                <strong id="exampleCalcPreview">100 DH dépensés = 10 points cumulés</strong>
                            </div>
                        </div>

                        <!-- REDEMPTION RULE (USING POINTS FOR DISCOUNTS) -->
                        <h6 class="fw-bold text-dark mb-2 mt-4">Barème d'Utilisation / Déduction (Remise)</h6>
                        <p class="text-muted small mb-3">Valeur monétaire des points lorsqu'un client souhaite les déduire de son addition.</p>

                        <div class="row g-3 align-items-center">
                            <div class="col-sm-5">
                                <label class="form-label small text-muted mb-1">Lorsque le client utilise :</label>
                                <div class="input-group">
                                    <input type="number" name="redemption_points" id="redemption_points" value="{{ old('redemption_points', $settings->redemption_points) }}" min="1" class="form-control form-control-custom font-mono fw-bold" required oninput="updateFormulaPreview()">
                                    <span class="input-group-text bg-white font-mono">Points</span>
                                </div>
                            </div>

                            <div class="col-sm-2 text-center text-muted fw-bold">
                                <i class="bi bi-arrow-right fs-4 d-none d-sm-inline"></i>
                                <i class="bi bi-arrow-down fs-4 d-sm-none"></i>
                            </div>

                            <div class="col-sm-5">
                                <label class="form-label small text-muted mb-1">Il bénéficie d'une remise de :</label>
                                <div class="input-group">
                                    <input type="number" step="0.5" name="redemption_value" id="redemption_value" value="{{ old('redemption_value', $settings->redemption_value) }}" min="0.1" class="form-control form-control-custom font-mono fw-bold text-success" required oninput="updateFormulaPreview()">
                                    <span class="input-group-text bg-white font-mono currency-badge">{{ $settings->currency }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 3. SERVICES & PRESTATIONS CATALOGUE -->
                <div class="card border rounded-4 shadow-xs bg-white mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">3. Catalogue de Services / Prestations</h5>
                                <small class="text-muted">Créez des boutons rapides pour vos prestations les plus courantes.</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addServiceRow()">
                                <i class="bi bi-plus-lg me-1"></i> Ajouter un Service
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="servicesTable">
                                <thead class="table-light small">
                                    <tr>
                                        <th style="min-width: 180px;">Nom de la Prestation / Service</th>
                                        <th style="width: 130px;">Prix Tarif (<span class="currency-badge">{{ $settings->currency }}</span>)</th>
                                        <th style="width: 130px;">Points Gagnés</th>
                                        <th style="width: 50px;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="servicesTableBody">
                                    @php
                                        $catalog = $settings->services_catalog ?: [];
                                    @endphp
                                    @forelse($catalog as $idx => $svc)
                                        <tr>
                                            <td>
                                                <input type="text" name="services[{{ $idx }}][name]" value="{{ $svc['name'] ?? '' }}" class="form-control form-control-sm" placeholder="Ex: Coupe Cheveux, Lavage Auto..." required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.5" name="services[{{ $idx }}][price]" value="{{ $svc['price'] ?? 0 }}" class="form-control form-control-sm font-mono">
                                            </td>
                                            <td>
                                                <input type="number" name="services[{{ $idx }}][points]" value="{{ $svc['points'] ?? 5 }}" min="1" class="form-control form-control-sm font-mono fw-bold text-purple" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeServiceRow(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                <input type="text" name="services[0][name]" value="Prestation Standard" class="form-control form-control-sm" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.5" name="services[0][price]" value="50" class="form-control form-control-sm font-mono">
                                            </td>
                                            <td>
                                                <input type="number" name="services[0][points]" value="5" min="1" class="form-control form-control-sm font-mono fw-bold text-purple" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeServiceRow(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn text-white rounded-pill px-5 py-2.5 shadow-sm fw-bold fs-6" style="background: #5736e9;">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer les Paramètres de Calcul
                    </button>
                </div>

            </div>

            <!-- Right Column: Live POS Simulator & Calculation Preview -->
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 2rem;">
                    
                    <h6 class="fw-bold text-dark text-uppercase tracking-wider small mb-3">
                        <i class="bi bi-phone me-1 text-purple"></i> Simulateur en Caisse (Aperçu)
                    </h6>

                    <!-- POS Simulator Box -->
                    <div class="card border rounded-4 shadow-sm bg-white overflow-hidden mb-3">
                        <div class="p-3 text-white" style="background: linear-gradient(135deg, #180d38 0%, #5736e9 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-white-50 text-uppercase" style="font-size: 0.68rem;">SIMULATION D'ENCAISSEMENT</small>
                                    <h6 class="fw-bold text-white mb-0">Calculateur en Direct</h6>
                                </div>
                                <span class="badge bg-white-20 text-white rounded-pill font-mono small">POS Mode</span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            
                            <!-- Test Spend Input -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">Simuler une Facture / Addition</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" id="testBillAmount" value="250" class="form-control font-mono fw-bold" oninput="runSimulation()">
                                    <span class="input-group-text bg-light font-mono currency-badge">{{ $settings->currency }}</span>
                                </div>
                            </div>

                            <!-- Live Calculation Results -->
                            <div class="p-3 rounded-4 bg-light border mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Points attribués :</span>
                                    <span class="display-6 fw-bold text-purple font-mono" id="simulatedPoints">+25</span>
                                </div>
                                <div class="small text-muted" id="simulatedFormulaDetail">
                                    Calcul : 250 / 10 = 25 tranche(s) &times; 1 pt
                                </div>
                            </div>

                            <!-- Test Redemption Preview -->
                            <div class="p-3 rounded-4 bg-success-subtle border border-success-subtle mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-success fw-bold d-block">SI LE CLIENT UTILISE 100 POINTS :</small>
                                        <span class="text-dark small">Remise déduite sur le ticket :</span>
                                    </div>
                                    <span class="fs-4 fw-bold text-success font-mono" id="simulatedDiscount">-10.00 DH</span>
                                </div>
                            </div>

                            <a href="{{ route('client.loyalty.terminal') }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-semibold small">
                                <i class="bi bi-upc-scan me-1"></i> Ouvrir le Terminal Réel
                            </a>

                        </div>
                    </div>

                    <!-- Guidance Info -->
                    <div class="card border rounded-4 p-3 bg-white shadow-xs">
                        <div class="d-flex gap-2">
                            <i class="bi bi-lightbulb text-warning fs-5"></i>
                            <small class="text-muted">
                                Ces paramètres sont synchronisés instantanément avec le <strong>Terminal Scanner POS</strong> de vos caisses ainsi qu'avec les passes digitaux de vos clients.
                            </small>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </form>

</div>

<style>
.cursor-pointer { cursor: pointer; }
.calculation-card { transition: all 0.2s ease; border-color: #e5e7eb; }
.calculation-card:hover { border-color: #5736e9; transform: translateY(-2px); }
.bg-light-primary { background-color: rgba(87, 54, 233, 0.05); }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); }
</style>

<script>
function onModeChange(mode) {
    document.querySelectorAll('.calculation-card').forEach(el => {
        el.classList.remove('border-primary', 'bg-light-primary');
    });
    event.currentTarget.classList.add('border-primary', 'bg-light-primary');
}

function updateFormulaPreview() {
    const currency = document.getElementById('currency').value.trim() || 'DH';
    document.querySelectorAll('.currency-badge').forEach(el => el.textContent = currency);

    const spendRatio = parseFloat(document.getElementById('spend_amount_ratio').value) || 10;
    const pointsRatio = parseInt(document.getElementById('points_per_ratio').value) || 1;
    const redeemPts = parseInt(document.getElementById('redemption_points').value) || 100;
    const redeemVal = parseFloat(document.getElementById('redemption_value').value) || 10;

    // Example calc preview
    const exampleSpend = spendRatio * 10;
    const exampleEarn = 10 * pointsRatio;
    document.getElementById('exampleCalcPreview').textContent = 
        `${exampleSpend} ${currency} dépensés = ${exampleEarn} points cumulés`;

    runSimulation();
}

function runSimulation() {
    const currency = document.getElementById('currency').value.trim() || 'DH';
    const spendRatio = parseFloat(document.getElementById('spend_amount_ratio').value) || 10;
    const pointsRatio = parseInt(document.getElementById('points_per_ratio').value) || 1;
    const minSpend = parseFloat(document.getElementById('min_spend_amount').value) || 0;
    const bill = parseFloat(document.getElementById('testBillAmount').value) || 0;

    let points = 0;
    if (bill >= minSpend && spendRatio > 0) {
        const units = Math.floor(bill / spendRatio);
        points = units * pointsRatio;
    }

    document.getElementById('simulatedPoints').textContent = `+${points}`;
    document.getElementById('simulatedFormulaDetail').textContent = 
        `Calcul : ${bill} / ${spendRatio} = ${Math.floor(bill/spendRatio)} tranche(s) × ${pointsRatio} pt(s)`;

    const redeemPts = parseInt(document.getElementById('redemption_points').value) || 100;
    const redeemVal = parseFloat(document.getElementById('redemption_value').value) || 10;
    const discount100 = redeemPts > 0 ? ((100 / redeemPts) * redeemVal).toFixed(2) : '0.00';
    document.getElementById('simulatedDiscount').textContent = `-${discount100} ${currency}`;
}

function addServiceRow() {
    const tbody = document.getElementById('servicesTableBody');
    const index = tbody.querySelectorAll('tr').length;
    const currency = document.getElementById('currency').value.trim() || 'DH';

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <input type="text" name="services[${index}][name]" class="form-control form-control-sm" placeholder="Nom du service..." required>
        </td>
        <td>
            <input type="number" step="0.5" name="services[${index}][price]" value="0" class="form-control form-control-sm font-mono">
        </td>
        <td>
            <input type="number" name="services[${index}][points]" value="10" min="1" class="form-control form-control-sm font-mono fw-bold text-purple" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeServiceRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function removeServiceRow(btn) {
    const tbody = document.getElementById('servicesTableBody');
    if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
    } else {
        alert('Vous devez conserver au moins un service ou laisser vide.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateFormulaPreview();
});
</script>
@endsection

