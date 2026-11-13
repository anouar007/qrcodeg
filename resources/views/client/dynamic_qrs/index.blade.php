@extends('layouts.client')

@section('title', 'Mes QR Codes Dynamiques')
@section('page_title', 'QR Codes Dynamiques (VP-Engine)')
@section('page_subtitle', 'Modifiez la destination de vos QR codes en direct sans jamais réimprimer vos supports physiques')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Vos QR Codes Dynamiques</h4>
            <p class="text-muted small mb-0">Tous vos QR codes restent valables à vie : vous pouvez rediriger leur cible instantanément.</p>
        </div>
        <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Tous mes produits
        </a>
    </div>

    <!-- Filters Strip -->
    <div class="card border-0 shadow-2xs rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('client.dynamic-qrs.index') }}" class="row g-2 align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Rechercher par code (VP-XXXX) ou libellé...">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs uniquement</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary-custom rounded-pill px-3 w-100">Filtrer</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('client.dynamic-qrs.index') }}" class="btn btn-sm btn-light border rounded-pill" title="Réinitialiser">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($dynamicQrs->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="bi bi-lightning-charge fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Aucun QR code dynamique trouvé</h5>
                <p class="text-muted small">Aucun QR dynamique ne correspond à vos critères de recherche.</p>
                <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-primary-custom rounded-pill px-4">
                    Voir tous mes produits
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($dynamicQrs as $dqr)
                <div class="col-md-6 col-xl-4">
                    <div class="card border rounded-3 p-3 shadow-2xs h-100 d-flex flex-column justify-content-between position-relative" style="border-top: 4px solid #5736e9 !important;">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge rounded-pill font-mono text-white fw-bold" style="background: #5736e9; font-size: 0.72rem;">
                                    {{ $dqr->code }}
                                </span>
                                <span class="badge {{ $dqr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono" style="font-size: 0.65rem;">
                                    ● {{ ucfirst($dqr->status) }}
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $dqr->label }}</h5>
                            <div class="text-muted small font-mono mb-2" style="font-size: 0.7rem;">
                                Modèle : {{ ucfirst(str_replace('_', ' ', $dqr->template_type ?: 'Standard')) }}
                            </div>

                            <div class="p-2.5 rounded-3 bg-light border mb-3">
                                <small class="text-muted d-block text-uppercase font-mono" style="font-size: 0.65rem;">URL de destination actuelle :</small>
                                <div class="font-mono text-primary text-truncate fw-semibold small mt-0.5">
                                    {{ $dqr->target_url }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3 small text-muted">
                                <span><i class="bi bi-qr-code-scan me-1 text-primary"></i><strong>{{ number_format($dqr->scans_count) }}</strong> scans</span>
                                <span>Créé le {{ $dqr->created_at->format('d/m/Y') }}</span>
                            </div>

                            <div class="d-flex gap-1.5 flex-wrap">
                                <a href="{{ route('client.dynamic-qrs.edit', $dqr) }}" class="btn btn-sm btn-primary-custom rounded-pill flex-fill fw-semibold">
                                    <i class="bi bi-pencil-square me-1"></i>Modifier la Cible
                                </a>
                                <a href="{{ route('qr.redirect', ['code' => $dqr->code]) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill" title="Tester redirection">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-download me-1"></i>Télécharger
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-menu-item dropdown-item small" href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dqr, 'format' => 'png']) }}"><i class="bi bi-file-image me-1 text-primary"></i> Image PNG Haute Définition</a></li>
                                        <li><a class="dropdown-menu-item dropdown-item small" href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dqr, 'format' => 'svg']) }}"><i class="bi bi-bezier2 me-1 text-info"></i> Fichier Vectoriel SVG</a></li>
                                        <li><a class="dropdown-menu-item dropdown-item small" href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dqr, 'format' => 'pdf']) }}"><i class="bi bi-file-pdf me-1 text-danger"></i> Chevalet / Carte PDF</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $dynamicQrs->links() }}
        </div>
    @endif
</div>
@endsection

