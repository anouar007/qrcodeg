@extends('layouts.client')

@section('title', 'Gestion Carte - ' . $loyalty->customer_name)

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Top Navigation Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('client.loyalty.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-1">
                <i class="bi bi-arrow-left me-1"></i> Retour aux cartes de fidélité
            </a>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold text-dark mb-0">{{ $loyalty->customer_name }}</h1>
                <span class="badge bg-dark font-mono rounded-pill px-2.5 py-1">{{ $loyalty->card_code }}</span>
                <span class="badge {{ $loyalty->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-2 py-0.5 small">
                    {{ ucfirst($loyalty->status) }}
                </span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('loyalty.public.show', ['card_code' => $loyalty->card_code]) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 shadow-xs">
                <i class="bi bi-box-arrow-up-right me-1"></i> Ouvrir Pass Client
            </a>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#editCardModal">
                <i class="bi bi-pencil me-1"></i> Modifier
            </button>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="row g-4">
        
        <!-- Left Column: Interactive Stamp Puncher & Status -->
        <div class="col-lg-7">
            
            <!-- STAMP PUNCHER CONSOLE -->
            <div class="card border rounded-4 shadow-xs bg-white mb-4 overflow-hidden">
                <div class="p-4" style="background: linear-gradient(135deg, rgba(87, 54, 233, 0.08) 0%, rgba(139, 92, 246, 0.04) 100%); border-bottom: 1px solid rgba(87, 54, 233, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase tracking-wider small text-purple fw-bold">Console de Tamponnage</span>
                            <h4 class="fw-bold text-dark mb-0">Attribuer des Tampons</h4>
                        </div>
                        <div class="text-end">
                            <span class="display-6 fw-bold text-purple font-mono">{{ $loyalty->stamps_count }}</span>
                            <span class="text-muted fs-5 font-mono">/ {{ $loyalty->max_stamps }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- Reward Ready Banner if threshold reached -->
                    @if($loyalty->isRewardAvailable())
                        <div class="alert alert-success border-0 rounded-4 shadow-xs p-3 mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center p-2 fs-4 flex-shrink-0" style="width: 44px; height: 44px;">
                                    <i class="bi bi-stars"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-white">Récompense Prête à être Réclamée !</h6>
                                    <small class="text-white-75">Le client a complété {{ $loyalty->stamps_count }} tampons. Récompense : <strong>{{ $loyalty->reward_text }}</strong></small>
                                </div>
                            </div>
                            <form action="{{ route('client.loyalty.redeem', $loyalty) }}" method="POST" class="m-0 flex-shrink-0">
                                @csrf
                                <button type="submit" class="btn btn-light text-success fw-bold rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-gift-fill me-1"></i> Valider la Récompense
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Visual Stamps Grid -->
                    <div class="p-3 bg-light rounded-4 border mb-4">
                        <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
                            <span>Grille de Tampons Client</span>
                            <span class="font-mono fw-semibold text-dark">{{ $loyalty->progressPercentage() }}% atteint</span>
                        </div>

                        <div class="d-flex flex-wrap gap-2.5 justify-content-center py-2">
                            @for($i = 1; $i <= $loyalty->max_stamps; $i++)
                                <div class="rounded-circle d-flex align-items-center justify-content-center transition-all shadow-xs" 
                                     style="width: 46px; height: 46px; font-size: 0.95rem; font-weight: bold;
                                     {{ $i <= $loyalty->stamps_count ? 'background: #5736e9; color: white; transform: scale(1.05);' : 'background: white; color: #9ca3af; border: 2px dashed #cbd5e1;' }}">
                                    @if($i <= $loyalty->stamps_count)
                                        <i class="bi bi-check-lg fs-5"></i>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                            @endfor
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress rounded-pill mt-3" style="height: 10px;">
                            <div class="progress-bar rounded-pill {{ $loyalty->isRewardAvailable() ? 'bg-success' : '' }}" 
                                 style="width: {{ $loyalty->progressPercentage() }}%; {{ ! $loyalty->isRewardAvailable() ? 'background-color: #5736e9;' : '' }}"></div>
                        </div>

                        <div class="text-center mt-2 small text-muted">
                            @if($loyalty->isRewardAvailable())
                                <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Plafond atteint ! Cliquez sur "Valider la Récompense" ci-dessus pour déduire {{ $loyalty->max_stamps }} tampons.</span>
                            @else
                                Plus que <strong class="text-dark">{{ $loyalty->remainingStampsForReward() }}</strong> tampons pour débloquer : <strong class="text-purple">{{ $loyalty->reward_text }}</strong>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Punch Buttons -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark mb-2">Ajout Rapide de Tampons</label>
                        <div class="row g-2">
                            <div class="col-4">
                                <form action="{{ route('client.loyalty.stamp', $loyalty) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="count" value="1">
                                    <button type="submit" class="btn w-100 rounded-3 py-2 text-white fw-bold shadow-xs" style="background: #5736e9;">
                                        <i class="bi bi-plus-lg me-1"></i> +1 Tampon
                                    </button>
                                </form>
                            </div>
                            <div class="col-4">
                                <form action="{{ route('client.loyalty.stamp', $loyalty) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="count" value="2">
                                    <button type="submit" class="btn w-100 rounded-3 py-2 btn-outline-primary fw-bold">
                                        <i class="bi bi-plus-lg me-1"></i> +2 Tampons
                                    </button>
                                </form>
                            </div>
                            <div class="col-4">
                                <form action="{{ route('client.loyalty.stamp', $loyalty) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="count" value="3">
                                    <button type="submit" class="btn w-100 rounded-3 py-2 btn-outline-primary fw-bold">
                                        <i class="bi bi-plus-lg me-1"></i> +3 Tampons
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Punch Form with Note -->
                    <form action="{{ route('client.loyalty.stamp', $loyalty) }}" method="POST" class="border-top pt-3">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white small text-muted"><i class="bi bi-calculator"></i></span>
                                    <input type="number" name="count" value="1" min="1" max="20" class="form-control" placeholder="Qté">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="notes" class="form-control" placeholder="Motif (ex: Achat 150 DH, Menu VIP...)">
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-dark w-100 rounded-3">
                                    Tamponner
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <!-- POINTS MANAGEMENT (INCREASE / DECREASE) -->
            <div class="card border rounded-4 shadow-xs bg-white mb-4 overflow-hidden">
                <div class="p-4" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(87, 54, 233, 0.05) 100%); border-bottom: 1px solid rgba(16, 185, 129, 0.15);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase tracking-wider small text-success fw-bold">Gestion du Solde de Points</span>
                            <h4 class="fw-bold text-dark mb-0">Créditer ou Déduire des Points</h4>
                            <small class="text-muted">Règle active : 1 pt pour chaque {{ $settings->spend_amount_ratio }} {{ $settings->currency }} dépensés</small>
                        </div>
                        <div class="text-end">
                            <span class="display-6 fw-bold text-dark font-mono">{{ $loyalty->points_balance }}</span>
                            <span class="text-muted small font-mono">pts</span>
                            <div class="text-success small fw-bold font-mono">
                                &approx; {{ $pointsDiscountValue }} {{ $settings->currency }} de remise
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Add Points Form -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 border h-100">
                                <h6 class="fw-bold text-success mb-2"><i class="bi bi-plus-circle me-1"></i> Ajouter / Créditer</h6>
                                <form action="{{ route('client.loyalty.points.adjust', $loyalty) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="add">
                                    <div class="mb-2">
                                        <label class="form-label small text-muted mb-1">Points à ajouter</label>
                                        <div class="input-group">
                                            <input type="number" name="points" min="1" max="100000" class="form-control font-mono fw-bold" placeholder="Ex: 25" required>
                                            <span class="input-group-text bg-white">Points</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="Motif (ex: Achat 250 DH)">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill fw-semibold">
                                        <i class="bi bi-plus-lg me-1"></i> Créditer les Points
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Deduct Points Form -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 border h-100">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-dash-circle me-1"></i> Déduire / Utiliser (Remise)</h6>
                                <form action="{{ route('client.loyalty.points.adjust', $loyalty) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="deduct">
                                    <div class="mb-2">
                                        <label class="form-label small text-muted mb-1">Points à déduire (Max: {{ $loyalty->points_balance }})</label>
                                        <div class="input-group">
                                            <input type="number" name="points" min="1" max="{{ max(1, $loyalty->points_balance) }}" class="form-control font-mono fw-bold text-danger" placeholder="Ex: 50" required>
                                            <span class="input-group-text bg-white">Points</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="Motif (ex: Remise déduite sur ticket)">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill fw-semibold" {{ $loyalty->points_balance <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-dash-lg me-1"></i> Déduire du Solde
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRANSACTION HISTORY TIMELINE -->
            <div class="card border rounded-4 shadow-xs bg-white mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-clock-history me-1 text-purple"></i> Historique des Activités
                    </h6>
                    <span class="badge bg-light text-muted border font-mono small">
                        {{ $loyalty->transactions->count() }} opération(s)
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Date &amp; Heure</th>
                                <th>Action</th>
                                <th>Variation</th>
                                <th>Détails / Note</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($loyalty->transactions as $tx)
                                <tr>
                                    <td class="text-muted font-mono" style="font-size: 0.78rem;">
                                        {{ $tx->created_at ? $tx->created_at->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td>
                                        @if($tx->action === 'stamp_added')
                                            <span class="badge bg-primary-subtle text-primary border">Tampon ajouté</span>
                                        @elseif($tx->action === 'reward_redeemed')
                                            <span class="badge bg-success-subtle text-success border">Récompense réclamée</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border">{{ $tx->action }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tx->stamps_delta > 0)
                                            <span class="fw-bold text-success font-mono">+{{ $tx->stamps_delta }}</span>
                                        @elseif($tx->stamps_delta < 0)
                                            <span class="fw-bold text-danger font-mono">{{ $tx->stamps_delta }}</span>
                                        @else
                                            <span class="text-muted font-mono">0</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ $tx->notes ?: '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Aucune activité enregistrée sur cette carte.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column: Pass Visual, QR, NFC & Sharing -->
        <div class="col-lg-5">
            
            <!-- PASS DIGITAL CARD PREVIEW -->
            <div class="card border-0 rounded-4 shadow-lg text-white mb-4 overflow-hidden" 
                 style="background: linear-gradient(145deg, #180d38 0%, #301777 50%, #5736e9 100%);">
                <div class="p-4 text-center">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-white-20 text-white rounded-pill px-2 py-0.5 font-mono small">
                            {{ $loyalty->card_code }}
                        </span>
                        @if($loyalty->card_type === 'both')
                            <span class="badge bg-white text-dark rounded-pill font-mono small"><i class="bi bi-qr-code me-1"></i>QR + <i class="bi bi-broadcast"></i>NFC</span>
                        @elseif($loyalty->card_type === 'nfc')
                            <span class="badge bg-warning text-dark rounded-pill font-mono small"><i class="bi bi-broadcast me-1"></i>NFC Only</span>
                        @else
                            <span class="badge bg-light text-dark rounded-pill font-mono small"><i class="bi bi-qr-code me-1"></i>QR Digital</span>
                        @endif
                    </div>

                    <h4 class="fw-bold text-white mb-1">{{ $loyalty->customer_name }}</h4>
                    <p class="text-white-75 small mb-3">
                        @if($loyalty->customer_phone) <span><i class="bi bi-telephone me-1"></i>{{ $loyalty->customer_phone }}</span> @endif
                        @if($loyalty->nfc_uid) <span class="d-block font-mono text-white-50 mt-1" style="font-size: 0.72rem;">NFC UID: {{ $loyalty->nfc_uid }}</span> @endif
                    </p>

                    <!-- Embedded QR Code SVG -->
                    <div class="p-3 bg-white rounded-4 d-inline-block shadow-sm mb-3">
                        {!! $qrSvg !!}
                    </div>

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-white text-dark font-mono px-3 py-1.5 rounded-pill shadow-xs">
                            <i class="bi bi-coin text-warning me-1"></i><strong>{{ $loyalty->points_balance }}</strong> Points
                        </span>
                        <span class="badge bg-white-20 text-white font-mono px-3 py-1.5 rounded-pill">
                            &approx; {{ $pointsDiscountValue }} {{ $settings->currency }} remise
                        </span>
                    </div>

                    <div class="p-2 rounded-3 text-center mb-2" style="background: rgba(255, 255, 255, 0.12); border: 1px dashed rgba(255,255,255,0.3);">
                        <small class="text-white-75 d-block" style="font-size: 0.7rem;">RÉCOMPENSE</small>
                        <strong class="text-white">{{ $loyalty->reward_text }}</strong>
                    </div>

                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">
                        Scannez ce code ou touchez la puce NFC pour identifier ce membre
                    </small>
                </div>
            </div>

            <!-- SHARING & PASS LINK TOOLS -->
            <div class="card border rounded-4 shadow-xs bg-white mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-share me-1 text-purple"></i> Partager le Pass au Client
                    </h6>

                    <!-- Copy Pass Link -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Lien Public du Pass Smartphone</label>
                        <div class="input-group">
                            <input type="text" id="publicPassUrl" value="{{ $loyalty->public_url }}" class="form-control font-mono small bg-light" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="copyPassLink()">
                                <i class="bi bi-clipboard"></i> Copier
                            </button>
                        </div>
                        <div id="copySuccessMsg" class="text-success small mt-1 d-none">
                            <i class="bi bi-check-circle me-1"></i> Lien copié dans le presse-papiers !
                        </div>
                    </div>

                    <!-- WhatsApp Sharing Button -->
                    @php
                        $phoneClean = preg_replace('/[^0-9]/', '', $loyalty->customer_phone ?? '');
                        $merchantName = auth('client')->user()->company_name ?? 'notre établissement';
                        $waText = urlencode("Bonjour {$loyalty->customer_name}, voici votre carte de fidélité digitale chez {$merchantName} : {$loyalty->public_url}");
                    @endphp

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary rounded-pill fw-semibold" onclick="window.nfcEncoder.writeUrl('{{ $loyalty->public_url }}', 'Pass VIP - {{ addslashes($loyalty->customer_name) }}', { bindEndpoint: '{{ route('client.loyalty.bind-nfc', $loyalty) }}', onSuccess: (uid) => { alert('Puce NFC gravée avec succès ! UID : ' + uid); window.location.reload(); } })">
                            <i class="bi bi-broadcast me-1"></i> Graver sur une Puce / Carte NFC
                        </button>

                        <a href="https://wa.me/{{ $phoneClean }}?text={{ $waText }}" target="_blank" class="btn btn-success rounded-pill fw-semibold shadow-xs">
                            <i class="bi bi-whatsapp me-1"></i> Envoyer le Pass par WhatsApp
                        </a>

                        <a href="{{ route('loyalty.public.show', ['card_code' => $loyalty->card_code]) }}" target="_blank" class="btn btn-outline-dark rounded-pill">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Ouvrir le Pass Client (Plein Écran)
                        </a>
                    </div>
                </div>
            </div>

            <!-- DANGER ZONE: DELETE CARD -->
            <div class="card border border-danger-subtle rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-danger mb-0 small">Supprimer cette carte</h6>
                        <small class="text-muted" style="font-size: 0.72rem;">Libère 1 place sur votre quota de cartes fidélité.</small>
                    </div>
                    <form action="{{ route('client.loyalty.destroy', $loyalty) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la carte fidélité de {{ $loyalty->customer_name }} ? Cette action libérera votre quota mais supprimera les tampons associés.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="bi bi-trash me-1"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- EDIT CARD MODAL -->
<div class="modal fade" id="editCardModal" tabindex="-1" aria-labelledby="editCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('client.loyalty.update', $loyalty) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="editCardModalLabel">
                        <i class="bi bi-pencil me-1 text-purple"></i> Modifier la Carte de Fidélité
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_customer_name" class="form-label fw-semibold text-dark small mb-1">Nom du Membre <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" id="edit_customer_name" value="{{ old('customer_name', $loyalty->customer_name) }}" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="edit_customer_phone" class="form-label fw-semibold text-dark small mb-1">Téléphone</label>
                            <input type="text" name="customer_phone" id="edit_customer_phone" value="{{ old('customer_phone', $loyalty->customer_phone) }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="edit_customer_email" class="form-label fw-semibold text-dark small mb-1">Email</label>
                            <input type="email" name="customer_email" id="edit_customer_email" value="{{ old('customer_email', $loyalty->customer_email) }}" class="form-control">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="edit_card_type" class="form-label fw-semibold text-dark small mb-1">Format de Carte</label>
                            <select name="card_type" id="edit_card_type" class="form-select">
                                <option value="both" {{ old('card_type', $loyalty->card_type) === 'both' ? 'selected' : '' }}>QR Code + NFC</option>
                                <option value="qr_code" {{ old('card_type', $loyalty->card_type) === 'qr_code' ? 'selected' : '' }}>QR Code Seul</option>
                                <option value="nfc" {{ old('card_type', $loyalty->card_type) === 'nfc' ? 'selected' : '' }}>Puce NFC Seule</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="edit_status" class="form-label fw-semibold text-dark small mb-1">Statut</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="active" {{ old('status', $loyalty->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="suspended" {{ old('status', $loyalty->status) === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nfc_uid" class="form-label fw-semibold text-dark small mb-1">Identifiant UID Tag NFC</label>
                        <input type="text" name="nfc_uid" id="edit_nfc_uid" value="{{ old('nfc_uid', $loyalty->nfc_uid) }}" class="form-control font-mono" placeholder="Ex: 04:A2:3B:5C:8D:6E:90">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label for="edit_max_stamps" class="form-label fw-semibold text-dark small mb-1">Max Tampons</label>
                            <input type="number" name="max_stamps" id="edit_max_stamps" value="{{ old('max_stamps', $loyalty->max_stamps) }}" min="2" max="50" class="form-control" required>
                        </div>
                        <div class="col-8">
                            <label for="edit_reward_text" class="form-label fw-semibold text-dark small mb-1">Récompense <span class="text-danger">*</span></label>
                            <input type="text" name="reward_text" id="edit_reward_text" value="{{ old('reward_text', $loyalty->reward_text) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="edit_notes" class="form-label fw-semibold text-dark small mb-1">Notes</label>
                        <textarea name="notes" id="edit_notes" rows="2" class="form-control">{{ old('notes', $loyalty->notes) }}</textarea>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn text-white rounded-pill px-4" style="background: #5736e9;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyPassLink() {
    const input = document.getElementById('publicPassUrl');
    navigator.clipboard.writeText(input.value).then(() => {
        const msg = document.getElementById('copySuccessMsg');
        msg.classList.remove('d-none');
        setTimeout(() => msg.classList.add('d-none'), 3500);
    }).catch(err => {
        input.select();
        document.execCommand('copy');
    });
}
</script>
@endsection

