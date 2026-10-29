@extends('layouts.app')

@section('title', 'Carte Visite: ' . ($digitalCard->full_name ?: $digitalCard->name))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.digital-cards.index') }}">Cartes Visite Digitales</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $digitalCard->full_name ?: $digitalCard->name }}</li>
@endsection

@section('content')
<div class="container-fluid px-0">

    <!-- Header bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill px-3 py-1 font-mono text-uppercase" style="background: rgba(99, 32, 238, 0.1); color: #6320ee; border: 1px solid rgba(99, 32, 238, 0.2); font-size: 0.72rem;">
                    Profil Digital /c/{{ $digitalCard->slug }}
                </span>
                @if($digitalCard->status === 'active')
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">
                        <span class="status-dot d-inline-block rounded-circle me-1" style="width: 6px; height: 6px; background: #10b981;"></span> Actif & En Ligne
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small">Inactif</span>
                @endif
            </div>
            <h2 class="h3 fw-extrabold text-dark mb-0">{{ $digitalCard->full_name ?: $digitalCard->name }}</h2>
            <div class="text-muted small">
                @if($digitalCard->job_title) {{ $digitalCard->job_title }} @endif
                @if($digitalCard->company) &bull; {{ $digitalCard->company }} @endif
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.digital-cards.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
            <a href="{{ route('digital-cards.public.vcard', $digitalCard->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-person-lines-fill me-1"></i>vCard (.vcf)
            </a>
            <a href="{{ $digitalCard->public_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i>Voir en Direct
            </a>
            <a href="{{ route('admin.digital-cards.edit', $digitalCard) }}" class="btn btn-sm text-white rounded-pill px-3.5 shadow-sm" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                <i class="bi bi-pencil-square me-1"></i>Modifier dans le Studio
            </a>
        </div>
    </div>

    @php
        $photo = $digitalCard->profile_photo_path ?: $digitalCard->profile_image;
        $cover = $digitalCard->cover_photo_path ?: $digitalCard->cover_image;
        $template = strtolower($digitalCard->template_id ?: ($digitalCard->template ?: 'modern'));
        $links = $digitalCard->links ?: ($digitalCard->content_blocks['social_links'] ?? ($digitalCard->content_blocks['links'] ?? []));
    @endphp

    <div class="row g-4">
        <!-- Left: Visual Mobile Card Preview -->
        <div class="col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-sticky" style="top: 24px;">
                <!-- Cover Banner -->
                <div style="height: 120px; {{ $cover ? "background: url('".asset('storage/' . $cover)."') center/cover no-repeat;" : "background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);" }}"></div>

                <!-- Avatar & Details -->
                <div class="card-body p-4 pt-0 text-center position-relative">
                    <div style="margin-top: -46px;" class="mb-3">
                        @if($photo)
                            <img src="{{ asset('storage/' . $photo) }}" alt="{{ $digitalCard->full_name }}" class="rounded-circle border border-4 border-white shadow-sm object-fit-cover bg-white" style="width: 88px; height: 88px;">
                        @else
                            <div class="rounded-circle border border-4 border-white shadow-sm d-flex align-items-center justify-content-center text-white fw-bold mx-auto" style="width: 88px; height: 88px; font-size: 1.8rem; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                                {{ strtoupper(substr($digitalCard->full_name ?: $digitalCard->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <h4 class="fw-bold text-dark mb-1">{{ $digitalCard->full_name ?: $digitalCard->name }}</h4>
                    <p class="text-secondary small mb-2">{{ $digitalCard->job_title }} @ {{ $digitalCard->company }}</p>

                    @if($digitalCard->bio)
                        <p class="text-muted small px-3 mb-3">{{ $digitalCard->bio }}</p>
                    @endif

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-light text-muted border text-capitalize px-3 py-1.5 rounded-pill">
                            <i class="bi bi-palette me-1"></i>{{ $template }}
                        </span>
                        <span class="badge bg-light text-primary border px-3 py-1.5 rounded-pill">
                            <i class="bi bi-eye me-1"></i>{{ number_format($digitalCard->views_count) }} consultations
                        </span>
                    </div>

                    <!-- Instant QR Code Display -->
                    <div class="bg-light p-3 rounded-4 border d-inline-block my-2">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=10&data={{ urlencode($digitalCard->public_url) }}" alt="QR Code" class="img-fluid rounded-3" style="width: 170px; height: 170px;">
                    </div>
                    <div class="small font-monospace text-muted mt-1">{{ $digitalCard->public_url }}</div>
                </div>
            </div>
        </div>

        <!-- Right: Telemetry & Configuration Info -->
        <div class="col-lg-7 col-xl-8">
            <!-- 1. Metadata Details -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary"></i>
                        Informations Générales
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="small text-muted mb-1">Client Rattaché</div>
                            <div class="fw-semibold text-dark">
                                @if($digitalCard->client)
                                    <a href="{{ route('admin.clients.show', $digitalCard->client) }}" class="text-decoration-none">
                                        <i class="bi bi-building me-1"></i>{{ $digitalCard->client->company_name ?: $digitalCard->client->name }}
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border">Non assigné</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted mb-1">Identifiant Slug Unique</div>
                            <div class="fw-semibold text-dark font-mono">
                                <code>{{ $digitalCard->slug }}</code>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted mb-1">Date de Création</div>
                            <div class="fw-semibold text-dark">
                                {{ $digitalCard->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted mb-1">Dernière Consultation</div>
                            <div class="fw-semibold text-dark">
                                {{ $digitalCard->last_viewed_at ? $digitalCard->last_viewed_at->format('d/m/Y à H:i') . ' (' . $digitalCard->last_viewed_at->diffForHumans() . ')' : 'Jamais' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Linked NFC Tags -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-broadcast-pin text-warning"></i>
                        Tags NFC Physiques Associés ({{ $digitalCard->nfcTags->count() }})
                    </h6>
                    <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-plus me-1"></i>Associer un Tag
                    </a>
                </div>
                <div class="card-body p-4">
                    @if($digitalCard->nfcTags->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>UID NFC</th>
                                        <th>Libellé</th>
                                        <th>Statut</th>
                                        <th class="text-center">Scans</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($digitalCard->nfcTags as $tag)
                                        <tr>
                                            <td class="font-mono small fw-bold">{{ $tag->uid }}</td>
                                            <td>{{ $tag->name ?: 'Carte Sans Fil' }}</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">Actif</span>
                                            </td>
                                            <td class="text-center fw-bold">{{ $tag->scans_count ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-broadcast fs-3 d-block mb-1 text-secondary opacity-50"></i>
                            <div class="small">Aucun tag ou carte physique NFC n'est encore jumelé à ce profil.</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Links & Social Channels -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-link-45deg text-primary"></i>
                        Blocs de Contenu & Liens ({{ is_array($links) ? count($links) : 0 }})
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if(is_array($links) && count($links) > 0)
                        <div class="d-flex flex-column gap-2">
                            @foreach($links as $link)
                                <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-light border">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary bg-white shadow-2xs" style="width: 36px; height: 36px;">
                                            <i class="bi {{ $link['icon'] ?? 'bi-link' }} fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $link['label'] ?? ($link['title'] ?? 'Lien') }}</div>
                                            <div class="text-muted font-mono" style="font-size: 0.75rem;">{{ $link['url'] ?? ($link['value'] ?? '') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ $link['url'] ?? ($link['value'] ?? '#') }}" target="_blank" class="btn btn-sm btn-white border shadow-2xs rounded-2 text-muted">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0">Aucun lien n'a encore été configuré pour ce profil.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
