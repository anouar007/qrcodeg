@extends('layouts.app')

@section('title', 'Carte de Fidélité #' . $loyaltyCard->card_code)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.index') }}">Fidélisation</a></li>
    <li class="breadcrumb-item active" aria-current="page">#{{ $loyaltyCard->card_code }}</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.loyalty.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle p-1" style="width: 32px; height: 32px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="h3 fw-bold text-dark mb-0">Carte de Fidélité #{{ $loyaltyCard->card_code }}</h1>
                <span class="badge {{ $loyaltyCard->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fs-6">
                    {{ ucfirst($loyaltyCard->status) }}
                </span>
            </div>
            <p class="text-muted small mb-0 ms-5">Détails de la carte, identifiants NFC/QR et historique complet des transactions.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('loyalty.public.show', $loyaltyCard->card_code) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-phone"></i>
                <span>Voir Pass Mobile</span>
            </a>
            <form method="POST" action="{{ route('admin.loyalty.toggle-status', $loyaltyCard) }}">
                @csrf
                <button type="submit" class="btn btn-sm {{ $loyaltyCard->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                    <i class="bi bi-power me-1"></i>{{ $loyaltyCard->status === 'active' ? 'Suspendre la carte' : 'Activer la carte' }}
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Card Profile & QR preview -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 mb-4">
                <div class="rounded-pill px-3 py-1 d-inline-block text-uppercase fw-bold small mb-3 text-white"
                     style="background: {{ $loyaltyCard->tier === 'gold' ? '#f59e0b' : ($loyaltyCard->tier === 'platinum' ? '#0f172a' : ($loyaltyCard->tier === 'silver' ? '#64748b' : '#5736e9')) }};">
                    MEMBRE {{ strtoupper($loyaltyCard->tier) }}
                </div>

                <div class="display-5 fw-bold text-dark mb-1">{{ number_format($loyaltyCard->points) }}</div>
                <div class="text-muted small mb-4">Points disponibles</div>

                <!-- QR code preview -->
                <div class="p-3 bg-light rounded-3 d-inline-block mx-auto mb-3 border">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('loyalty.public.show', $loyaltyCard->card_code)) }}"
                         alt="QR Pass"
                         class="img-fluid rounded"
                         style="width: 160px; height: 160px;">
                </div>
                <div class="font-mono text-dark fw-bold mb-2">{{ $loyaltyCard->card_code }}</div>
                <div class="text-muted small">Scanner pour ouvrir le pass ou créditer au terminal</div>
            </div>

            <!-- Details box -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold text-dark mb-3">Informations Titulaire</h6>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Nom &amp; Prénom</span>
                    <span class="fw-semibold text-dark">{{ $loyaltyCard->customer_name }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Téléphone</span>
                    <span class="fw-semibold text-dark">{{ $loyaltyCard->customer_phone ?: '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Email</span>
                    <span class="fw-semibold text-dark">{{ $loyaltyCard->customer_email ?: '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">NFC Hardware UID</span>
                    @if($loyaltyCard->nfc_uid)
                        <span class="font-mono text-primary fw-bold">{{ $loyaltyCard->nfc_uid }}</span>
                    @else
                        <span class="text-muted small">Non encodé</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Entreprise</span>
                    <span class="fw-semibold text-dark">{{ $loyaltyCard->client?->company_name ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted small">Date d'adhésion</span>
                    <span class="text-dark">{{ $loyaltyCard->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Transactions history -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">Historique des Opérations ({{ $transactions->total() }})</h5>
                    <span class="badge bg-light text-secondary">Traçabilité complète</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Date</th>
                                <th class="py-3">Type</th>
                                <th class="py-3 text-center">Points</th>
                                <th class="py-3 text-center">Nouveau Solde</th>
                                <th class="py-3">Description / Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                                <tr>
                                    <td class="ps-3 small text-muted">
                                        {{ $tx->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        @if($tx->type === 'add')
                                            <span class="badge bg-success-subtle text-success">Crédit</span>
                                        @elseif($tx->type === 'redeem')
                                            <span class="badge bg-warning-subtle text-warning">Débit</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Ajustement</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold {{ $tx->points_delta >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $tx->points_delta >= 0 ? '+' : '' }}{{ $tx->points_delta }} pts
                                    </td>
                                    <td class="text-center font-mono">
                                        {{ number_format($tx->balance_after) }} pts
                                    </td>
                                    <td class="small text-muted">
                                        {{ $tx->description ?: ($tx->spend_amount ? 'Achat : ' . number_format($tx->spend_amount, 2) . ' MAD' : 'Caisse') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Aucune opération enregistrée sur cette carte.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

