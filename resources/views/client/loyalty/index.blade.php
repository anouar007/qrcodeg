@extends('layouts.client')

@section('title', 'Programme Fidélité - Cartes Membres & Tampons')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Header Section with Quota Alert & Quick Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold text-dark mb-0">Programme Fidélité</h1>
                <span class="badge rounded-pill text-white px-2 py-1" style="background: #5736e9; font-size: 0.75rem;">
                    VIP Pass
                </span>
            </div>
            <p class="text-muted small mb-0">
                Générez des cartes de fidélité digitales (QR Code &amp; NFC) pour vos clients et gérez leurs tampons.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('client.loyalty.scan') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-xs">
                <i class="bi bi-qr-code-scan me-1"></i> Scanner / Tamponner
            </a>

            @if($canGenerate)
                <a href="{{ route('client.loyalty.create') }}" class="btn text-white rounded-pill px-4 shadow-sm" style="background: #5736e9; border-color: #5736e9;">
                    <i class="bi bi-plus-lg me-1"></i> Générer une Carte
                </a>
            @else
                <button type="button" class="btn btn-secondary rounded-pill px-4 opacity-75" disabled title="Quota maximal atteint">
                    <i class="bi bi-lock me-1"></i> Quota Atteint ({{ $totalCards }}/{{ $cardLimit }})
                </button>
            @endif
        </div>
    </div>

    <!-- Admin Quota & Permission Banner -->
    <div class="card border-0 rounded-4 shadow-xs mb-4 overflow-hidden" style="background: linear-gradient(135deg, rgba(87, 54, 233, 0.08) 0%, rgba(16, 185, 129, 0.05) 100%); border-left: 4px solid #5736e9 !important;">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 48px; height: 48px; background: #5736e9;">
                            <i class="bi bi-award fs-4"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark">Quota de Cartes Fidélité Autorisé par l'Administrateur</h6>
                                @if($canGenerate)
                                    <span class="badge bg-success-subtle text-success border px-2 py-0.5 rounded-pill small">Actif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border px-2 py-0.5 rounded-pill small">Limite Atteinte</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                Vous avez généré <strong>{{ $totalCards }}</strong> sur votre plafond autorisé de <strong>{{ $cardLimit }}</strong> cartes.
                                @if($remainingQuota > 0)
                                    Il vous reste <strong>{{ $remainingQuota }}</strong> cartes disponibles à émettre.
                                @else
                                    <span class="text-danger fw-semibold">Contactez l'administrateur pour débloquer des cartes supplémentaires.</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between small fw-semibold mb-1">
                                <span class="text-muted">Consommation du quota</span>
                                <span class="{{ $usagePercent >= 100 ? 'text-danger' : 'text-dark' }} font-mono">{{ $usagePercent }}% ({{ $totalCards }} / {{ $cardLimit }})</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background-color: rgba(0,0,0,0.06);">
                                <div class="progress-bar rounded-pill {{ $usagePercent >= 100 ? 'bg-danger' : ($usagePercent >= 80 ? 'bg-warning' : '') }}" 
                                     role="progressbar" 
                                     style="width: {{ $usagePercent }}%; {{ $usagePercent < 80 ? 'background-color: #5736e9;' : '' }}" 
                                     aria-valuenow="{{ $usagePercent }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        @if($canGenerate)
                            <a href="{{ route('client.loyalty.create') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 flex-shrink-0">
                                + Créer
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border rounded-4 shadow-xs h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-medium text-uppercase tracking-wider">Cartes Générées</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalCards }}</h3>
                            <small class="text-muted font-mono">{{ $remainingQuota }} cartes restantes</small>
                        </div>
                        <div class="rounded-3 p-2 text-white" style="background: #5736e9;">
                            <i class="bi bi-credit-card-2-front fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border rounded-4 shadow-xs h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-medium text-uppercase tracking-wider">Membres Actifs</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $activeMembersCount }}</h3>
                            <small class="text-success fw-medium"><i class="bi bi-check-circle me-1"></i>Cartes en circulation</small>
                        </div>
                        <div class="rounded-3 p-2 bg-success text-white">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border rounded-4 shadow-xs h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-medium text-uppercase tracking-wider">Tampons Distribués</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalStampsIssued) }}</h3>
                            <small class="text-muted">Total cumulé attribué</small>
                        </div>
                        <div class="rounded-3 p-2 bg-warning text-white">
                            <i class="bi bi-check2-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border rounded-4 shadow-xs h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-medium text-uppercase tracking-wider">Cadeaux Débloqués</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalRewardsRedeemed) }}</h3>
                            <small class="text-purple fw-medium"><i class="bi bi-gift me-1"></i>Récompenses réclamées</small>
                        </div>
                        <div class="rounded-3 p-2 text-white" style="background: #8b5cf6;">
                            <i class="bi bi-gift fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border rounded-4 shadow-xs mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('client.loyalty.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Rechercher par nom client, téléphone, code carte, NFC UID...">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les formats (QR &amp; NFC)</option>
                        <option value="both" {{ request('type') === 'both' ? 'selected' : '' }}>QR Code + NFC Tag</option>
                        <option value="qr_code" {{ request('type') === 'qr_code' ? 'selected' : '' }}>QR Code Digital uniquement</option>
                        <option value="nfc" {{ request('type') === 'nfc' ? 'selected' : '' }}>Puce NFC physique</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn text-white w-100 rounded-3" style="background: #5736e9;">Filtrer</button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('client.loyalty.index') }}" class="btn btn-outline-secondary rounded-3" title="Réinitialiser"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Cards Grid -->
    @if($cards->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($cards as $card)
                <div class="col-md-6 col-xl-4">
                    <div class="card border rounded-4 shadow-xs h-100 overflow-hidden bg-white hover-shadow transition-all">
                        
                        <!-- VIP Loyalty Card Mini-Header -->
                        <div class="p-3 text-white position-relative" style="background: linear-gradient(135deg, #2b1055 0%, #5736e9 100%);">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-white-20 text-white border-0 font-mono small px-2 py-0.5 rounded-pill">
                                        {{ $card->card_code }}
                                    </span>
                                    <h5 class="fw-bold mt-2 mb-0 text-white text-truncate" style="max-width: 220px;">
                                        {{ $card->customer_name }}
                                    </h5>
                                </div>
                                <div class="text-end">
                                    @if($card->card_type === 'both')
                                        <span class="badge bg-white text-dark fw-semibold rounded-pill font-mono" style="font-size: 0.7rem;">
                                            <i class="bi bi-qr-code me-1"></i>QR + <i class="bi bi-broadcast"></i>NFC
                                        </span>
                                    @elseif($card->card_type === 'nfc')
                                        <span class="badge bg-warning text-dark fw-semibold rounded-pill font-mono" style="font-size: 0.7rem;">
                                            <i class="bi bi-broadcast me-1"></i>NFC Tag
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark fw-semibold rounded-pill font-mono" style="font-size: 0.7rem;">
                                            <i class="bi bi-qr-code me-1"></i>QR Pass
                                        </span>
                                    @endif
                                    @if($card->nfc_uid)
                                        <div class="font-mono text-white-50 mt-1" style="font-size: 0.68rem;">UID: {{ $card->nfc_uid }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white-10 text-white-75 small">
                                <span><i class="bi bi-telephone me-1"></i>{{ $card->customer_phone ?: 'Sans numéro' }}</span>
                                <span class="badge {{ $card->status === 'active' ? 'bg-success' : 'bg-secondary' }} text-white px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                    {{ ucfirst($card->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body: Stamps Progress & Reward -->
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            
                            <!-- Reward Announcement -->
                            <div class="p-2 rounded-3 mb-3 d-flex align-items-center justify-content-between" style="background: rgba(87, 54, 233, 0.06); border: 1px dashed rgba(87, 54, 233, 0.25);">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-gift-fill text-purple fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">RÉCOMPENSE À DÉBLOQUER</small>
                                        <strong class="text-dark small">{{ $card->reward_text }}</strong>
                                    </div>
                                </div>
                                <span class="badge bg-purple-subtle text-purple font-mono">
                                    {{ $card->max_stamps }} tampons
                                </span>
                            </div>

                            <!-- Stamps Visual Grid (preview up to 10 circles) -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="fw-semibold text-dark">
                                        Tampons : <span class="font-mono text-purple fw-bold fs-6">{{ $card->stamps_count }}</span> / {{ $card->max_stamps }}
                                    </span>
                                    @if($card->isRewardAvailable())
                                        <span class="badge bg-success text-white animate-pulse">
                                            <i class="bi bi-stars me-1"></i>Récompense Prête !
                                        </span>
                                    @else
                                        <span class="text-muted font-mono small">
                                            Plus que {{ $card->remainingStampsForReward() }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Progress bar -->
                                <div class="progress rounded-pill mb-2" style="height: 8px;">
                                    <div class="progress-bar {{ $card->isRewardAvailable() ? 'bg-success' : '' }}" 
                                         style="width: {{ $card->progressPercentage() }}%; {{ ! $card->isRewardAvailable() ? 'background-color: #5736e9;' : '' }}"></div>
                                </div>

                                <!-- Stamp circles mini preview -->
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    @for($i = 1; $i <= min(10, $card->max_stamps); $i++)
                                        <div class="rounded-circle d-flex align-items-center justify-content-center {{ $i <= $card->stamps_count ? 'text-white' : 'text-muted border bg-light' }}"
                                             style="width: 24px; height: 24px; font-size: 0.68rem; {{ $i <= $card->stamps_count ? 'background: #5736e9;' : '' }}">
                                            @if($i <= $card->stamps_count)
                                                <i class="bi bi-check-lg"></i>
                                            @else
                                                {{ $i }}
                                            @endif
                                        </div>
                                    @endfor
                                    @if($card->max_stamps > 10)
                                        <span class="small text-muted font-mono ms-1">+{{ $card->max_stamps - 10 }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons Row -->
                            <div class="pt-2 border-top">
                                <div class="d-flex gap-2">
                                    <!-- Quick +1 Stamp button -->
                                    <form action="{{ route('client.loyalty.stamp', $card) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="count" value="1">
                                        <button type="submit" class="btn btn-sm w-100 rounded-pill text-white fw-semibold" style="background: #5736e9;">
                                            <i class="bi bi-plus-circle me-1"></i> +1 Tampon
                                        </button>
                                    </form>

                                    @if($card->isRewardAvailable())
                                        <!-- Redeem button -->
                                        <form action="{{ route('client.loyalty.redeem', $card) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-xs" title="Réclamer la récompense">
                                                <i class="bi bi-gift me-1"></i> Réclamer
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Link to Detail Console -->
                                    <a href="{{ route('client.loyalty.show', $card) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Gérer la carte">
                                        <i class="bi bi-gear"></i>
                                    </a>

                                    <!-- Link to Customer Pass -->
                                    <a href="{{ route('loyalty.public.show', ['card_code' => $card->card_code]) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Voir le Pass Client">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $cards->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card border rounded-4 shadow-xs p-5 text-center bg-white">
            <div class="rounded-circle d-inline-flex p-4 mb-3 mx-auto" style="background: rgba(87, 54, 233, 0.08); color: #5736e9;">
                <i class="bi bi-award fs-1"></i>
            </div>
            <h4 class="fw-bold text-dark">Aucune carte de fidélité trouvée</h4>
            <p class="text-muted max-w-md mx-auto mb-4">
                Fidélisez votre clientèle en créant dès aujourd'hui votre première carte de fidélité personnalisée avec QR Code et/ou Tag NFC.
            </p>
            @if($canGenerate)
                <div>
                    <a href="{{ route('client.loyalty.create') }}" class="btn text-white rounded-pill px-4 shadow-sm" style="background: #5736e9;">
                        <i class="bi bi-plus-lg me-1"></i> Générer ma Première Carte
                    </a>
                </div>
            @else
                <div class="alert alert-warning max-w-md mx-auto small">
                    Votre quota est configuré à {{ $cardLimit }} cartes. Contactez votre administrateur pour l'augmenter.
                </div>
            @endif
        </div>
    @endif

</div>
@endsection

