@extends('layouts.admin')

@section('title', 'Cartes Visite Digitales')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Cartes Visite Digitales</h1>
        <p class="text-muted mb-0">Créez et personnalisez des profils de contact connectés avec liens, images et vCard.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            <span>Créer une Carte Visite</span>
        </a>
    </div>
</div>

{{-- Search & Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.digital-cards.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Nom, entreprise, slug..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Client</label>
                <select name="client_id" class="form-select">
                    <option value="">Tous les clients</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->company_name }} ({{ $c->contact_name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Template</label>
                <select name="template" class="form-select">
                    <option value="">Tous</option>
                    <option value="modern" {{ request('template') == 'modern' ? 'selected' : '' }}>Modern</option>
                    <option value="executive" {{ request('template') == 'executive' ? 'selected' : '' }}>Executive</option>
                    <option value="creative" {{ request('template') == 'creative' ? 'selected' : '' }}>Creative</option>
                    <option value="cyberpunk" {{ request('template') == 'cyberpunk' ? 'selected' : '' }}>Cyberpunk</option>
                    <option value="glassmorphism" {{ request('template') == 'glassmorphism' ? 'selected' : '' }}>Glassmorphism</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-secondary w-100" title="Filtrer">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Cards Grid / Table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">Visuel</th>
                    <th>Carte & Titulaire</th>
                    <th>Client Associé</th>
                    <th>Template</th>
                    <th>Lien Unique</th>
                    <th>Vues</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cards as $card)
                    <tr>
                        <td>
                            @if($card->profile_image)
                                <img src="{{ asset('storage/' . $card->profile_image') }}" alt="{{ $card->full_name }}" class="rounded-circle object-fit-cover border" style="width: 44px; height: 44px;">
                            @else
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                    {{ substr($card->full_name ?: $card->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $card->name }}</div>
                            <div class="small text-muted">
                                {{ $card->full_name ?: 'Non renseigné' }}
                                @if($card->job_title) &bull; <span class="text-secondary">{{ $card->job_title }}</span> @endif
                            </div>
                        </td>
                        <td>
                            @if($card->client)
                                <a href="{{ route('admin.clients.show', $card->client) }}" class="text-decoration-none fw-semibold">
                                    {{ $card->client->company_name }}
                                </a>
                                <div class="small text-muted">{{ $card->client->contact_name }}</div>
                            @else
                                <span class="badge bg-light text-muted border">Non assigné</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info text-capitalize px-2 py-1">
                                {{ $card->template }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ $card->public_url }}" target="_blank" class="small font-monospace text-decoration-none bg-light px-2 py-1 rounded border">
                                    /c/{{ $card->slug }}
                                    <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size: 0.75rem;"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-link text-muted p-1" onclick="navigator.clipboard.writeText('{{ $card->public_url }}'); alert('Lien copié !');" title="Copier le lien">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">{{ number_format($card->views_count) }}</span>
                            <div class="small text-muted" style="font-size: 0.75rem;">
                                {{ $card->last_viewed_at ? $card->last_viewed_at->diffForHumans() : 'Jamais' }}
                            </div>
                        </td>
                        <td>
                            @if($card->status === 'active')
                                <span class="badge bg-success-subtle text-success">Actif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Inactif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.digital-cards.edit', $card) }}" class="btn btn-sm btn-outline-primary" title="Personnaliser & Live Preview">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{ $card->public_url }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Voir la page publique">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.digital-cards.destroy', $card) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette carte de visite ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-person-vcard fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                <h6 class="fw-bold">Aucune Carte Visite Digitale</h6>
                                <p class="small text-muted mb-3">Créez votre première carte connectée avec liens, images et vCard personnalisable.</p>
                                <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Créer une Carte Visite
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cards->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $cards->links() }}
        </div>
    @endif
</div>
@endsection
