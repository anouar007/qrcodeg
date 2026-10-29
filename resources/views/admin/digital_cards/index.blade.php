@extends('layouts.app')

@section('title', 'Cartes Visite Digitales')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header Matching Screenshot -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="font-size: 1.65rem; letter-spacing: -0.02em;">Cartes Visite Digitales</h2>
            <p class="text-muted small mb-0" style="color: #64748b;">
                {{ $cards->total() }} cartes au total
            </p>
        </div>
        <div>
            <a href="{{ route('admin.digital-cards.create') }}" class="btn text-white fw-medium d-flex align-items-center gap-1.5 shadow-sm" style="background-color: #6320ee; border-radius: 8px; padding: 0.55rem 1.25rem; font-size: 0.9rem;">
                <i class="bi bi-plus-lg fs-6"></i>
                <span>Nouvelle carte</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Matching Screenshot -->
    <div class="card bg-white border mb-4 shadow-xs" style="border-radius: 14px; border-color: #e2e8f0;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.digital-cards.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                <!-- Search Input with Magnifier -->
                <div class="position-relative flex-grow-1" style="min-width: 260px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.85rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="border-radius: 10px; border-color: #e2e8f0; padding-left: 2.25rem; font-size: 0.88rem; background-color: #ffffff;" placeholder="Rechercher par code, client ou profil...">
                </div>

                <!-- Status Select -->
                <div style="min-width: 140px;">
                    <select name="status" class="form-select" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem;" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Assigned / Non Assigned Select -->
                <div style="min-width: 175px;">
                    <select name="assigned" class="form-select" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem;" onchange="this.form.submit()">
                        <option value="">Assignée / non assignée</option>
                        <option value="assigned" {{ request('assigned') === 'assigned' ? 'selected' : '' }}>Assignée</option>
                        <option value="unassigned" {{ request('assigned') === 'unassigned' ? 'selected' : '' }}>Non assignée</option>
                    </select>
                </div>

                <!-- Sort Select -->
                <div style="min-width: 140px;">
                    <select name="sort" class="form-select" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem;" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>Trier par date</option>
                        <option value="views_high" {{ request('sort') === 'views_high' ? 'selected' : '' }}>Trier par scans</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Trier par nom</option>
                    </select>
                </div>

                <!-- Export CSV Button -->
                <a href="{{ route('admin.digital-cards.export') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1.5" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem; color: #334155;">
                    <i class="bi bi-download"></i>
                    <span>Export CSV</span>
                </a>

                <!-- Import CSV Button -->
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1.5" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem; color: #334155;" onclick="alert('Import CSV pour cartes digitales actif.');">
                    <i class="bi bi-upload"></i>
                    <span>Import CSV</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Digital Cards Table Card Matching Screenshot Exactly -->
    <div class="card bg-white border shadow-xs mb-4" style="border-radius: 16px; border-color: #e2e8f0; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="border-color: #f1f5f9;">
                    <thead style="background-color: #ffffff; border-bottom: 1px solid #f1f5f9;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-normal" style="font-size: 0.82rem; width: 180px;">Code</th>
                            <th class="py-3 text-muted fw-normal" style="font-size: 0.82rem; width: 120px;">Statut</th>
                            <th class="py-3 text-muted fw-normal" style="font-size: 0.82rem; width: 160px;">Client</th>
                            <th class="py-3 text-muted fw-normal" style="font-size: 0.82rem;">Profil / Lien direct</th>
                            <th class="py-3 text-muted fw-normal text-center" style="font-size: 0.82rem; width: 90px;">Scans</th>
                            <th class="pe-4 py-3 text-muted fw-normal text-end" style="font-size: 0.82rem; width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody style="border-top: none;">
                        @forelse($cards as $card)
                            @php
                                $dest = $card->public_url;
                                $codeDisplay = $card->slug ?: ('CD-' . str_pad((string) $card->id, 6, '0', STR_PAD_LEFT));
                            @endphp
                            <tr style="height: 60px;">
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark" style="font-size: 0.92rem; letter-spacing: 0.02em;">
                                        <a href="{{ route('admin.digital-cards.edit', $card) }}" class="text-dark text-decoration-none" title="Éditer cette carte">
                                            {{ $codeDisplay }}
                                        </a>
                                    </div>
                                    @if($card->full_name && $card->full_name !== $codeDisplay)
                                        <div class="text-muted text-truncate" style="font-size: 0.78rem; max-width: 170px;">{{ $card->full_name }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($card->status === 'active')
                                        <span class="badge" style="background-color: #dcfce7; color: #16a34a; font-weight: 500; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #fee2e2; color: #dc2626; font-weight: 500; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($card->client)
                                        <span class="text-dark small fw-medium">{{ $card->client->company_name ?: $card->client->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dest)
                                        <a href="{{ $dest }}" target="_blank" class="text-decoration-none text-truncate d-inline-block" style="color: #475569; max-width: 480px; font-size: 0.88rem;" title="{{ $dest }}">
                                            {{ $dest }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="text-dark fw-medium" style="font-size: 0.9rem;">
                                        {{ number_format($card->views_count ?? 0) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <form action="{{ route('admin.digital-cards.destroy', $card) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmez-vous la suppression de cette carte ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-muted p-1 border-0" title="Supprimer">
                                            <i class="bi bi-trash3" style="font-size: 1rem; color: #94a3b8;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-vcard fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    <div>Aucune carte visite digitale trouvée</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($cards->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $cards->links() }}
        </div>
    @endif
</div>
@endsection
