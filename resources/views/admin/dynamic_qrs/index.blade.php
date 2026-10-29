@extends('layouts.app')

@section('title', 'Dynamic QR Codes (VP- Engine)')

@push('styles')
<style>
/* ==========================================================================
   DYNAMIC QR ENGINE DESIGN SYSTEM (YNS NFC / NEO-SAAS)
   ========================================================================== */
:root {
    --dyn-primary: #6320ee;
    --dyn-primary-hover: #5015d8;
    --dyn-primary-light: #f3f0ff;
    --dyn-primary-border: #ddd6fe;
    --dyn-primary-glow: rgba(99, 32, 238, 0.15);
    --dyn-success: #10b981;
    --dyn-success-light: #ecfdf5;
    --dyn-success-border: #a7f3d0;
}

/* Radar pulse beacon */
.status-dot-radar {
    position: relative;
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
}
.status-dot-radar::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    border: 1.5px solid #10b981;
    animation: radarRipple 2s cubic-bezier(0.24, 0, 0.38, 1) infinite;
}
@keyframes radarRipple {
    0% { transform: scale(0.6); opacity: 1; }
    100% { transform: scale(2.2); opacity: 0; }
}

/* Monospace VP- Code Badge */
.vp-code-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-family: 'JetBrains Mono', 'SF Mono', Monaco, monospace;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 0.28rem 0.65rem;
    border-radius: 8px;
    background: #f3f0ff;
    color: #6320ee;
    border: 1px solid #ddd6fe;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none;
    line-height: 1.2;
}
.vp-code-badge:hover {
    background: #ede9fe;
    color: #4f46e5;
    border-color: #c4b5fd;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(99, 32, 238, 0.15);
}

/* KPI Stat Cards */
.kpi-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.25rem 1.35rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.kpi-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}
.kpi-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

/* Filter Bar Form Controls */
.filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}
.filter-input {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    font-size: 0.84rem;
    height: 38px;
    border-radius: 10px;
    transition: all 0.2s ease;
}
.filter-input:focus {
    background-color: #ffffff;
    border-color: #6320ee;
    box-shadow: 0 0 0 3px rgba(99, 32, 238, 0.12);
}

/* Segmented View Switcher Capsule */
.dyn-segmented-control {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 3px;
    display: inline-flex;
    align-items: center;
    gap: 2px;
}
.dyn-segmented-btn {
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.85rem;
    padding: 0.35rem 0.65rem;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
}
.dyn-segmented-btn:hover {
    color: #0f172a;
}
.dyn-segmented-btn.active {
    background: #ffffff;
    color: #6320ee;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

/* Table Card & Responsive Overflow Protection */
.dyn-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
    overflow: visible !important;
}
.dyn-table-responsive {
    overflow-x: auto;
    min-height: 260px;
    border-radius: 16px;
}
.dyn-table {
    margin-bottom: 0;
    vertical-align: middle;
}
.dyn-table thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 0.95rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.dyn-table tbody td {
    padding: 0.95rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    background-color: #ffffff;
    transition: background-color 0.15s ease;
}
.dyn-table tbody tr:hover td {
    background-color: #fafbfc;
}
.dyn-table tbody tr:last-child td {
    border-bottom: none;
}

/* Action Icon Buttons Dock */
.dyn-action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.85rem;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    transition: all 0.15s ease;
    text-decoration: none;
}
.dyn-action-btn:hover {
    border-color: #cbd5e1;
    color: #0f172a;
    background: #f8fafc;
    transform: translateY(-1px);
}
.dyn-action-btn.btn-redirect:hover {
    color: #0284c7;
    border-color: #bae6fd;
    background: #f0f9ff;
}
.dyn-action-btn.btn-pdf:hover {
    color: #6320ee;
    border-color: #ddd6fe;
    background: #f3f0ff;
}
.dyn-action-btn.btn-delete:hover {
    color: #ef4444;
    border-color: #fecaca;
    background: #fef2f2;
}

/* Custom Dropdown Menu Styling */
.dyn-dropdown-menu {
    z-index: 1065 !important;
    min-width: 260px;
    padding: 0.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 18px 38px -6px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.04);
}
.dyn-dropdown-menu .dropdown-header {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.4rem 0.75rem 0.2rem;
}
.dyn-dropdown-menu .dropdown-item {
    font-size: 0.82rem;
    padding: 0.45rem 0.75rem;
    border-radius: 7px;
    color: #334155;
    font-weight: 500;
    transition: all 0.15s ease;
}
.dyn-dropdown-menu .dropdown-item:hover {
    background-color: #f3f0ff;
    color: #6320ee;
}

/* Grid Card Item */
.dynamic-qr-grid-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.dynamic-qr-grid-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 32px -8px rgba(15, 23, 42, 0.12);
    border-color: #c4b5fd;
}
.dynamic-qr-stage-thumb {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #eef2f6;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    min-height: 160px;
}
.qr-thumb-box {
    width: 105px;
    height: 105px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s ease;
}
.dynamic-qr-grid-card:hover .qr-thumb-box {
    transform: scale(1.04);
}

/* URL Quick Chips in Modal */
.quick-url-chip {
    font-size: 0.72rem;
    padding: 4px 10px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.15s ease;
    font-weight: 500;
}
.quick-url-chip:hover {
    background: #f3f0ff;
    color: #6320ee;
    border-color: #ddd6fe;
    transform: translateY(-1px);
}

/* Status Interactive Toggle Pill */
.status-pill-toggle {
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
    border: none;
    background: transparent;
    padding: 0;
}
.status-pill-toggle:hover {
    transform: scale(1.05);
}

/* Instant Toast Notification */
.dyn-toast-container {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 1090;
    pointer-events: none;
}
.dyn-toast {
    background: #0f172a;
    color: #ffffff;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    display: flex;
    align-items: center;
    gap: 0.65rem;
    font-size: 0.85rem;
    font-weight: 600;
    opacity: 0;
    transform: translateY(15px);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: auto;
}
.dyn-toast.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Top Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="status-dot-radar"></span>
                <span class="text-uppercase tracking-wider font-mono text-muted" style="font-size: 0.72rem; font-weight: 700;">
                    Dynamic Telemetry Core &bull; 302 Instant Router
                </span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white shadow-xs" style="width: 38px; height: 38px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); font-size: 1.15rem;">
                    <i class="bi bi-qr-code"></i>
                </span>
                <span>Dynamic QR Engine</span>
            </h1>
            <p class="text-muted small mb-0">
                Identifiants permanents <span class="vp-code-badge py-0 px-2" style="font-size: 0.76rem;">VP-XXXXXX</span>. Modifiez l'URL de redirection en temps réel sans réimprimer vos supports.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Single QR Quick Modal Trigger -->
            <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#singleCreateModal" style="border-radius: 10px; font-weight: 600; padding: 0.55rem 1rem;">
                <i class="bi bi-plus-circle"></i>
                <span>Nouveau QR</span>
            </button>

            <!-- Bulk Modal Trigger -->
            <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal" style="border-radius: 10px; font-weight: 600; padding: 0.55rem 1rem;">
                <i class="bi bi-collection text-primary"></i>
                <span>Génération par Lot</span>
            </button>

            <!-- Studio Gabarit Button -->
            <a href="{{ route('admin.dynamic-qrs.create') }}" class="btn text-white d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); border-radius: 10px; font-weight: 600; padding: 0.55rem 1.25rem;">
                <i class="bi bi-palette2"></i>
                <span>Studio Gabarit &amp; PDF</span>
            </a>
        </div>
    </div>

    <!-- Analytics Stat KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.72rem;">Total QR Dynamiques</span>
                    <div class="kpi-icon-box" style="background: #f3f0ff; color: #6320ee;">
                        <i class="bi bi-qr-code"></i>
                    </div>
                </div>
                <div class="fs-3 fw-extrabold text-dark font-mono mb-1">{{ number_format($metrics['total']) }}</div>
                <div class="small text-muted d-flex align-items-center gap-2" style="font-size: 0.76rem;">
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle font-mono">VP- Trackers</span>
                    <span>Enregistrés en base</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.72rem;">Scans Cumulés</span>
                    <div class="kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
                        <i class="bi bi-broadcast"></i>
                    </div>
                </div>
                <div class="fs-3 fw-extrabold text-dark font-mono mb-1">{{ number_format($metrics['scans']) }}</div>
                <div class="small text-muted d-flex align-items-center gap-2" style="font-size: 0.76rem;">
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle font-mono">Télémétrie Live</span>
                    <span>Redirections effectuées</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.72rem;">Codes Actifs</span>
                    <div class="kpi-icon-box" style="background: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="fs-3 fw-extrabold text-dark font-mono mb-1">{{ number_format($metrics['active']) }}</div>
                <div class="small text-muted d-flex align-items-center gap-2" style="font-size: 0.76rem;">
                    <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle font-mono">En Ligne</span>
                    <span>Routage actif</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase font-mono" style="font-size: 0.72rem;">Inactifs / Archivés</span>
                    <div class="kpi-icon-box" style="background: #f8fafc; color: #64748b;">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                </div>
                <div class="fs-3 fw-extrabold text-dark font-mono mb-1">{{ number_format($metrics['inactive']) }}</div>
                <div class="small text-muted d-flex align-items-center gap-2" style="font-size: 0.76rem;">
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle font-mono">Suspendus</span>
                    <span>Pause temporaire</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search, Filters & View Toggle Bar -->
    <div class="filter-card p-3 mb-4">
        <form method="GET" action="{{ route('admin.dynamic-qrs.index') }}" class="row g-2 align-items-center">
            <!-- Search Box -->
            <div class="col-12 col-md-4 col-xl-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #e2e8f0;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control filter-input border-start-0" placeholder="Code VP-, libellé ou URL..." style="border-radius: 0 10px 10px 0;">
                </div>
            </div>

            <!-- Template Filter -->
            <div class="col-6 col-md-3 col-xl-2">
                <select name="template_type" class="form-select filter-input">
                    <option value="">Tous les gabarits</option>
                    <option value="google_review" {{ request('template_type') === 'google_review' ? 'selected' : '' }}>Avis Google</option>
                    <option value="social_media" {{ request('template_type') === 'social_media' ? 'selected' : '' }}>Réseaux Sociaux</option>
                    <option value="menu" {{ request('template_type') === 'menu' ? 'selected' : '' }}>Menu Digital</option>
                    <option value="custom" {{ request('template_type') === 'custom' ? 'selected' : '' }}>Gabarit Sur-Mesure</option>
                </select>
            </div>

            <!-- Client Filter -->
            <div class="col-6 col-md-3 col-xl-2">
                <select name="client_id" class="form-select filter-input">
                    <option value="">Tous les clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} {{ $client->project_name ? "({$client->project_name})" : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-6 col-md-2 col-xl-1">
                <select name="status" class="form-select filter-input">
                    <option value="">Statut</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archivé</option>
                </select>
            </div>

            <!-- Sort Dropdown -->
            <div class="col-6 col-md-3 col-xl-2">
                <select name="sort" class="form-select filter-input">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                    <option value="scans_high" {{ request('sort') === 'scans_high' ? 'selected' : '' }}>Plus scannés</option>
                    <option value="scans_low" {{ request('sort') === 'scans_low' ? 'selected' : '' }}>Moins scannés</option>
                    <option value="code" {{ request('sort') === 'code' ? 'selected' : '' }}>Code VP- (A-Z)</option>
                    <option value="label" {{ request('sort') === 'label' ? 'selected' : '' }}>Libellé (A-Z)</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons & View Switcher -->
            <div class="col-12 col-md-auto col-xl-2 d-flex align-items-center justify-content-end gap-2 ms-auto">
                <button type="submit" class="btn btn-sm text-white px-3 d-inline-flex align-items-center gap-1.5 shadow-xs" style="background-color: #6320ee; height: 38px; border-radius: 10px; font-weight: 600;" title="Appliquer les filtres">
                    <i class="bi bi-funnel"></i>
                    <span>Filtrer</span>
                </button>

                @if(request()->hasAny(['search', 'template_type', 'client_id', 'status', 'sort']))
                    <a href="{{ route('admin.dynamic-qrs.index') }}" class="btn btn-sm btn-light border d-inline-flex align-items-center justify-content-center" title="Réinitialiser les filtres" style="height: 38px; width: 38px; border-radius: 10px;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif

                <!-- View Switcher (Table vs Grid Segmented Capsule) -->
                <div class="dyn-segmented-control" role="group" aria-label="Sélecteur d'affichage">
                    <button type="button" class="dyn-segmented-btn active" id="btnViewTable" title="Affichage tableau">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="dyn-segmented-btn" id="btnViewGrid" title="Affichage visuel en cartes">
                        <i class="bi bi-grid-fill"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ===================================================================== -->
    <!-- 1. TABLE VIEW                                                         -->
    <!-- ===================================================================== -->
    <div id="dynamicQrsTableView" class="dyn-table-card mb-4">
        <div class="dyn-table-responsive">
            <table class="table dyn-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 170px;">Code VP-</th>
                        <th style="min-width: 180px;">Libellé &amp; Client</th>
                        <th style="min-width: 250px;">Destination Active (URL)</th>
                        <th style="min-width: 150px;">Gabarit Support</th>
                        <th class="text-center" style="width: 110px;">Scans</th>
                        <th style="width: 110px;">Statut</th>
                        <th class="text-end pe-4" style="width: 190px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qrCodes as $qr)
                        <tr id="tableRowQr{{ $qr->id }}">
                            <!-- Code VP- -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-1.5">
                                    <a href="{{ route('admin.dynamic-qrs.show', $qr) }}" class="text-decoration-none">
                                        <span class="vp-code-badge">{{ $qr->code }}</span>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-0 copy-btn" data-code="{{ $qr->code }}" title="Copier le code">
                                        <i class="bi bi-clipboard" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                                <div class="text-muted font-mono mt-1" style="font-size: 0.68rem;">
                                    /qr/{{ $qr->code }}
                                </div>
                            </td>

                            <!-- Label & Client -->
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.88rem; max-width: 220px;" title="{{ $qr->label }}">{{ $qr->label }}</div>
                                @if($qr->client)
                                    <a href="{{ route('admin.clients.show', $qr->client) }}" class="small text-muted text-decoration-none d-inline-flex align-items-center gap-1 mt-0.5">
                                        <i class="bi bi-building text-primary" style="font-size: 0.75rem;"></i>
                                        <span class="text-truncate" style="max-width: 180px;">{{ $qr->client->name }}</span>
                                    </a>
                                @else
                                    <span class="small text-muted fst-italic mt-0.5 d-inline-block" style="font-size: 0.75rem;">Non assigné</span>
                                @endif
                            </td>

                            <!-- Target Destination with Instant In-Place Editor -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ $qr->target_url }}" target="_blank" id="qrTargetLink{{ $qr->id }}" class="small text-truncate d-inline-block text-decoration-none text-secondary font-mono" style="max-width: 260px;" title="{{ $qr->target_url }}">
                                        <i class="bi bi-box-arrow-up-right me-1 text-primary small"></i><span class="target-text">{{ $qr->target_url }}</span>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary py-0.5 px-2 rounded-2 edit-target-btn flex-shrink-0" 
                                            style="font-size: 0.72rem; line-height: 1.4;"
                                            data-id="{{ $qr->id }}" 
                                            data-code="{{ $qr->code }}" 
                                            data-target="{{ $qr->target_url }}"
                                            title="Modifier la destination instantanément">
                                        <i class="bi bi-pencil-square"></i> Éditer
                                    </button>
                                </div>
                            </td>

                            <!-- Template Type -->
                            <td>
                                @if($qr->template_type === 'google_review')
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle fw-semibold" style="font-size: 0.74rem;">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Avis Google
                                    </span>
                                @elseif($qr->template_type === 'social_media')
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle fw-semibold" style="font-size: 0.74rem;">
                                        <i class="bi bi-share-fill me-1"></i> Réseaux Sociaux
                                    </span>
                                @elseif($qr->template_type === 'menu')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-semibold" style="font-size: 0.74rem;">
                                        <i class="bi bi-cup-hot-fill me-1"></i> Menu Digital
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-dark border fw-semibold" style="font-size: 0.74rem;">
                                        <i class="bi bi-palette me-1"></i> {{ ucfirst(str_replace('_', ' ', $qr->template_type)) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Scans Count -->
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono fw-bold px-2.5 py-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-eye text-primary me-1"></i>{{ number_format($qr->scans_count) }}
                                </span>
                            </td>

                            <!-- Interactive Status Toggle -->
                            <td>
                                <button type="button" 
                                        class="status-pill-toggle toggle-status-btn" 
                                        data-id="{{ $qr->id }}"
                                        data-status="{{ $qr->status }}"
                                        title="Cliquer pour basculer Actif / Inactif">
                                    @if($qr->status === 'active')
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold" id="statusBadgeTable{{ $qr->id }}" style="font-size: 0.74rem;">
                                            <span class="d-inline-block rounded-circle bg-success me-1" style="width: 6px; height: 6px;"></span>Actif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle fw-semibold" id="statusBadgeTable{{ $qr->id }}" style="font-size: 0.74rem;">
                                            <span class="d-inline-block rounded-circle bg-secondary me-1" style="width: 6px; height: 6px;"></span>Inactif
                                        </span>
                                    @endif
                                </button>
                            </td>

                            <!-- Actions Dock (Clean Individual Buttons + Fixed Strategy Dropdown) -->
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1.5">
                                    <!-- Test Redirect 302 -->
                                    <a href="{{ route('qr.redirect', ['code' => $qr->code]) }}" target="_blank" class="dyn-action-btn btn-redirect" title="Tester le scan 302">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>

                                    <!-- PDF Printable Card Dropdown with Fixed Strategy to Prevent Clipping -->
                                    <div class="dropdown">
                                        <button type="button" class="dyn-action-btn btn-pdf" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false" title="Exporter PDF / SVG">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end dyn-dropdown-menu">
                                            <li class="dropdown-header">Cartes d'impression standard</li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => 'google_review', 'dimension' => '85x55']) }}">
                                                    <i class="bi bi-star-fill text-warning"></i> Carte Avis Google (85×55mm)
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => 'social_media', 'dimension' => '85x55']) }}">
                                                    <i class="bi bi-share-fill text-primary"></i> Carte Réseaux Sociaux (85×55mm)
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => 'menu', 'dimension' => '85x55']) }}">
                                                    <i class="bi bi-cup-hot-fill text-warning"></i> Carte Menu Digital (85×55mm)
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li class="dropdown-header">Format Chevalet &amp; Plaque</li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => 'a6']) }}">
                                                    <i class="bi bi-printer text-success"></i> Plaque A6 (105×148mm)
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li class="dropdown-header">Fichiers Bruts Vectoriels</li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.svg', $qr) }}">
                                                    <i class="bi bi-filetype-svg text-primary"></i> Télécharger SVG Vectoriel
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dynamic-qrs.png', $qr) }}">
                                                    <i class="bi bi-filetype-png text-info"></i> Télécharger PNG Haute Résolution
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Details & Edit -->
                                    <a href="{{ route('admin.dynamic-qrs.show', $qr) }}" class="dyn-action-btn" title="Détails &amp; Télémétrie">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.dynamic-qrs.edit', $qr) }}" class="dyn-action-btn" title="Configuration complète">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="dyn-action-btn btn-delete delete-qr-btn" data-id="{{ $qr->id }}" data-code="{{ $qr->code }}" data-label="{{ $qr->label }}" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-qr-code text-muted fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Aucun QR Code Dynamique Trouvé</h6>
                                <p class="small text-muted mb-3">Créez votre premier code unitaire ou lancez un lot avec gabarit studio.</p>
                                <button type="button" class="btn btn-sm text-white px-3" data-bs-toggle="modal" data-bs-target="#singleCreateModal" style="background-color: #6320ee; border-radius: 8px;">
                                    <i class="bi bi-plus-lg me-1"></i> Créer un QR Code
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($qrCodes->hasPages())
            <div class="p-3 border-top bg-light-subtle d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="small text-muted font-mono">
                    Affichage de {{ $qrCodes->firstItem() }} à {{ $qrCodes->lastItem() }} sur {{ $qrCodes->total() }} codes
                </span>
                <div>
                    {{ $qrCodes->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- ===================================================================== -->
    <!-- 2. GRID CARDS VIEW                                                    -->
    <!-- ===================================================================== -->
    <div id="dynamicQrsGridView" class="row g-3 mb-4 d-none">
        @forelse($qrCodes as $qr)
            <div class="col-12 col-md-6 col-xl-4" id="gridCardQr{{ $qr->id }}">
                <div class="dynamic-qr-grid-card shadow-xs">
                    <!-- Stage Thumb with Live Sharp SVG Thumbnail -->
                    <div class="dynamic-qr-stage-thumb">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="vp-code-badge">{{ $qr->code }}</span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge rounded-pill bg-white text-dark border font-mono fw-bold shadow-xs">
                                <i class="bi bi-eye text-primary me-1"></i>{{ number_format($qr->scans_count) }}
                            </span>
                        </div>

                        <!-- Lazy Sharp SVG Thumbnail -->
                        <div class="qr-thumb-box">
                            <img src="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 140]) }}" 
                                 alt="{{ $qr->code }}" 
                                 loading="lazy" 
                                 class="w-100 h-100 object-fit-contain">
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-3 flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1.5">
                            <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $qr->label }}">{{ $qr->label }}</h6>
                            <button type="button" 
                                    class="status-pill-toggle toggle-status-btn" 
                                    data-id="{{ $qr->id }}"
                                    data-status="{{ $qr->status }}"
                                    title="Basculer le statut">
                                @if($qr->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle small py-0.5 px-2" id="statusBadgeGrid{{ $qr->id }}">Actif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle small py-0.5 px-2" id="statusBadgeGrid{{ $qr->id }}">Inactif</span>
                                @endif
                            </button>
                        </div>

                        <!-- Client -->
                        <div class="small text-muted mb-2 text-truncate">
                            @if($qr->client)
                                <i class="bi bi-building text-primary me-1"></i>{{ $qr->client->name }}
                            @else
                                <i class="bi bi-dash text-muted me-1"></i>Non assigné
                            @endif
                        </div>

                        <!-- Target URL box with quick edit -->
                        <div class="mt-auto p-2 rounded-3 bg-light border d-flex align-items-center justify-content-between gap-2">
                            <div class="min-w-0 text-truncate font-mono small text-muted" style="font-size: 0.74rem;" id="qrTargetGridText{{ $qr->id }}">
                                <i class="bi bi-link-45deg text-primary me-1"></i><span class="target-text">{{ $qr->target_url }}</span>
                            </div>
                            <button type="button" 
                                    class="btn btn-sm btn-light border py-0.5 px-1.5 rounded-2 edit-target-btn flex-shrink-0" 
                                    style="font-size: 0.72rem;"
                                    data-id="{{ $qr->id }}" 
                                    data-code="{{ $qr->code }}" 
                                    data-target="{{ $qr->target_url }}"
                                    title="Changer l'URL">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Card Footer Actions Dock -->
                    <div class="card-footer bg-white border-top p-2.5 px-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-1.5">
                            <a href="{{ route('qr.redirect', ['code' => $qr->code]) }}" target="_blank" class="dyn-action-btn btn-redirect" title="Tester scan 302">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <a href="{{ route('admin.dynamic-qrs.svg', $qr) }}" class="dyn-action-btn" title="Télécharger SVG">
                                <i class="bi bi-filetype-svg"></i>
                            </a>
                            <a href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => '85x55']) }}" class="dyn-action-btn btn-pdf" title="Télécharger PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <a href="{{ route('admin.dynamic-qrs.show', $qr) }}" class="dyn-action-btn" title="Consulter">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.dynamic-qrs.edit', $qr) }}" class="dyn-action-btn" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="dyn-action-btn btn-delete delete-qr-btn" data-id="{{ $qr->id }}" data-code="{{ $qr->code }}" data-label="{{ $qr->label }}" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Aucun QR code trouvé dans la sélection.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. MODAL: SINGLE QR CODE QUICK CREATION                                   -->
<!-- ========================================================================= -->
<div class="modal fade" id="singleCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light bg-opacity-75">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                        <i class="bi bi-plus-circle-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark fs-6 mb-0">Créer un QR Code Dynamique</h5>
                        <small class="text-muted">Génération instantanée avec code VP- unique</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('admin.dynamic-qrs.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark mb-1">Libellé du QR <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control filter-input" placeholder="ex: Stand VIP, Réception Hôtel, Menu Table" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark mb-1">Destination par défaut (URL) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px; border-color: #e2e8f0;"><i class="bi bi-globe"></i></span>
                            <input type="text" name="target_url" class="form-control filter-input" placeholder="https://..." required style="border-radius: 0 10px 10px 0;">
                        </div>
                        <div class="form-text small text-muted mt-1">Vous pourrez la changer à tout moment en 1 clic.</div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Assigner à un client</label>
                            <select name="client_id" class="form-select filter-input">
                                <option value="">Général (Aucun client)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Gabarit Support</label>
                            <select name="template_type" class="form-select filter-input" required>
                                <option value="google_review" selected>Avis Google (5 étoiles)</option>
                                <option value="social_media">Réseaux Sociaux</option>
                                <option value="menu">Menu Digital</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Statut initial</label>
                        <select name="status" class="form-select filter-input">
                            <option value="active" selected>Actif (Routage immédiat)</option>
                            <option value="inactive">Inactif (En attente d'activation)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm text-white px-4 fw-semibold shadow-sm" style="background-color: #6320ee; border-radius: 8px;">
                        <i class="bi bi-check-lg me-1"></i> Créer le QR Code
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 4. MODAL: INSTANT TARGET MANAGEMENT (AJAX POWERED)                        -->
<!-- ========================================================================= -->
<div class="modal fade" id="targetEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light bg-opacity-75">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-lightning-charge-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark fs-6 mb-0">Routage Immédiat du QR</h5>
                        <small class="text-muted">Mise à jour en temps réel sans réimpression</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form id="targetEditForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 mb-3" style="background: #f3f0ff; border: 1px solid #ddd6fe;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill fs-5 text-primary mt-0.5"></i>
                            <div class="small" style="color: #4338ca;">
                                Le QR physique <strong id="modalQrCodePill" class="vp-code-badge py-0 px-2"></strong> reste 100% inchangé. Seule la cible de redirection vers laquelle l'utilisateur est envoyé sera modifiée immédiatement.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Nouvelle URL de destination <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px; border-color: #e2e8f0;"><i class="bi bi-globe"></i></span>
                            <input type="text" name="target_url" id="modalTargetUrlInput" class="form-control filter-input" placeholder="https://..." required style="border-radius: 0 10px 10px 0;">
                        </div>
                        <div class="form-text small text-muted mt-2">
                            Raccourcis rapides :
                            <div class="d-inline-flex flex-wrap gap-1 mt-1">
                                <span class="quick-url-chip" onclick="applyTargetPrefix('https://')">https://</span>
                                <span class="quick-url-chip" onclick="applyTargetPrefix('https://g.page/r/')">Google Avis</span>
                                <span class="quick-url-chip" onclick="applyTargetPrefix('https://instagram.com/')">Instagram</span>
                                <span class="quick-url-chip" onclick="applyTargetPrefix('https://wa.me/')">WhatsApp</span>
                                <span class="quick-url-chip" onclick="applyTargetPrefix('https://tiktok.com/@')">TikTok</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="targetSubmitBtn" class="btn btn-sm text-white px-3 fw-semibold shadow-sm" style="background-color: #6320ee; border-radius: 8px;">
                        <i class="bi bi-check-lg me-1" id="targetSubmitIcon"></i>
                        <span id="targetSubmitText">Mettre à jour immédiatement</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 5. MODAL: BULK GENERATION                                                 -->
<!-- ========================================================================= -->
<div class="modal fade" id="bulkGenerateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light bg-opacity-75">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%);">
                        <i class="bi bi-collection-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark fs-6 mb-0">Génération par Lot de QR Dynamiques</h5>
                        <small class="text-muted">Création rapide de codes multiples assignables</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.dynamic-qrs.bulk') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Assigner à un client (Optionnel)</label>
                            <select name="client_id" class="form-select filter-input">
                                <option value="">Général (Aucun client assigné)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Quantité à générer <span class="text-danger">*</span></label>
                            <select name="quantity" class="form-select filter-input" required>
                                <option value="5">5 QR Codes</option>
                                <option value="10" selected>10 QR Codes</option>
                                <option value="25">25 QR Codes</option>
                                <option value="50">50 QR Codes</option>
                                <option value="100">100 QR Codes</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Préfixe du libellé <span class="text-danger">*</span></label>
                            <input type="text" name="label_prefix" class="form-control filter-input" placeholder="ex: Chevalet Table, Plaque Avis" required>
                            <div class="form-text small" style="font-size: 0.72rem;">Chaque code sera numéroté : [Préfixe] #01, #02, etc.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Gabarit d'impression</label>
                            <select name="template_type" class="form-select filter-input" required>
                                <option value="google_review" selected>Avis Google (5 étoiles)</option>
                                <option value="social_media">Réseaux Sociaux Multi-plateformes</option>
                                <option value="menu">Menu Digital Restaurant / Café</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Destination par défaut (URL) <span class="text-danger">*</span></label>
                            <input type="text" name="target_url" class="form-control filter-input" placeholder="https://example.com" required>
                            <div class="form-text small" style="font-size: 0.72rem;">Modifiable individuellement à tout moment après création.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Style de séquence du code</label>
                            <select name="code_style" class="form-select filter-input">
                                <option value="unique" selected>Aléatoire unique (ex: VP-984210)</option>
                                <option value="sequential">Séquentiel linéaire (ex: VP-000001, VP-000002)</option>
                                <option value="numeric">Chiffres stricts à 6 digits (ex: VP-519283)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm text-white px-4 shadow-sm fw-semibold" style="background-color: #6320ee; border-radius: 8px;">
                        <i class="bi bi-magic me-1"></i> Générer le lot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. MODAL: UNIFIED DELETE CONFIRMATION                                     -->
<!-- ========================================================================= -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-3 px-3">
                <h5 class="modal-title fw-bold text-dark fs-6">Supprimer le QR code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start small text-muted px-3 py-2">
                Confirmez-vous la suppression de <strong class="text-dark" id="deleteModalCodeText"></strong> (<span id="deleteModalLabelText"></span>) ? La redirection cessera immédiatement de fonctionner.
            </div>
            <div class="modal-footer border-0 pt-2 px-3 pb-3">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteConfirmForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger px-3">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Toast Notification Container -->
<div class="dyn-toast-container">
    <div id="dynToast" class="dyn-toast">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <span id="dynToastMessage">Action effectuée</span>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Toast Notification Helper
    const dynToast = document.getElementById('dynToast');
    const dynToastMessage = document.getElementById('dynToastMessage');
    let toastTimeout = null;

    function showToast(message, isSuccess = true) {
        if (!dynToast || !dynToastMessage) return;
        dynToastMessage.textContent = message;
        const icon = dynToast.querySelector('i');
        if (icon) {
            icon.className = isSuccess ? 'bi bi-check-circle-fill text-success fs-5' : 'bi bi-exclamation-triangle-fill text-danger fs-5';
        }
        dynToast.classList.add('show');
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            dynToast.classList.remove('show');
        }, 3000);
    }

    // 1. Instant target edit modal handler with AJAX
    const targetModalEl = document.getElementById('targetEditModal');
    const targetModal = targetModalEl ? new bootstrap.Modal(targetModalEl) : null;
    const targetForm = document.getElementById('targetEditForm');
    const targetInput = document.getElementById('modalTargetUrlInput');
    const pill = document.getElementById('modalQrCodePill');
    const targetSubmitBtn = document.getElementById('targetSubmitBtn');
    let activeEditQrId = null;

    document.querySelectorAll('.edit-target-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            activeEditQrId = this.getAttribute('data-id');
            const code = this.getAttribute('data-code');
            const target = this.getAttribute('data-target');

            targetForm.action = `/admin/dynamic-qrs/${activeEditQrId}/target`;
            targetInput.value = target;
            pill.textContent = code;
            if (targetModal) targetModal.show();
        });
    });

    window.applyTargetPrefix = function(prefix) {
        if (!targetInput) return;
        targetInput.value = prefix;
        targetInput.focus();
    };

    if (targetForm) {
        targetForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const actionUrl = targetForm.action;
            const newUrl = targetInput.value.trim();

            if (!newUrl) return;

            if (targetSubmitBtn) {
                targetSubmitBtn.disabled = true;
                targetSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mise à jour...';
            }

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    target_url: newUrl
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update table row
                    const tableLink = document.getElementById(`qrTargetLink${activeEditQrId}`);
                    if (tableLink) {
                        tableLink.href = data.target_url;
                        const textSpan = tableLink.querySelector('.target-text');
                        if (textSpan) textSpan.textContent = data.target_url;
                    }
                    // Update grid card
                    const gridTarget = document.getElementById(`qrTargetGridText${activeEditQrId}`);
                    if (gridTarget) {
                        const textSpan = gridTarget.querySelector('.target-text');
                        if (textSpan) textSpan.textContent = data.target_url;
                    }
                    // Update edit buttons data attributes
                    document.querySelectorAll(`.edit-target-btn[data-id="${activeEditQrId}"]`).forEach(btn => {
                        btn.setAttribute('data-target', data.target_url);
                    });

                    if (targetModal) targetModal.hide();
                    showToast(`URL mise à jour : ${data.target_url}`);
                } else {
                    alert(data.message || 'Une erreur est survenue.');
                }
            })
            .catch(() => {
                // Fallback to traditional form submission
                targetForm.submit();
            })
            .finally(() => {
                if (targetSubmitBtn) {
                    targetSubmitBtn.disabled = false;
                    targetSubmitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Mettre à jour immédiatement';
                }
            });
        });
    }

    // 2. Interactive Status Toggle with AJAX
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const qrId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');

            fetch(`/admin/dynamic-qrs/${qrId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const newStatus = data.status;
                    const isActive = (newStatus === 'active');

                    // Update Table Badge
                    const tableBadge = document.getElementById(`statusBadgeTable${qrId}`);
                    if (tableBadge) {
                        tableBadge.className = isActive 
                            ? 'badge rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold'
                            : 'badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle fw-semibold';
                        tableBadge.innerHTML = `<span class="d-inline-block rounded-circle ${isActive ? 'bg-success' : 'bg-secondary'} me-1" style="width: 6px; height: 6px;"></span>${isActive ? 'Actif' : 'Inactif'}`;
                    }

                    // Update Grid Badge
                    const gridBadge = document.getElementById(`statusBadgeGrid${qrId}`);
                    if (gridBadge) {
                        gridBadge.className = isActive
                            ? 'badge bg-success-subtle text-success border border-success-subtle small py-0.5 px-2'
                            : 'badge bg-secondary-subtle text-secondary border border-secondary-subtle small py-0.5 px-2';
                        gridBadge.textContent = isActive ? 'Actif' : 'Inactif';
                    }

                    // Update buttons data-status
                    document.querySelectorAll(`.toggle-status-btn[data-id="${qrId}"]`).forEach(b => {
                        b.setAttribute('data-status', newStatus);
                    });

                    showToast(data.message);
                }
            })
            .catch(err => {
                console.error('Erreur bascule statut:', err);
            });
        });
    });

    // 3. Unified Delete Modal
    const deleteModalEl = document.getElementById('deleteConfirmModal');
    const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
    const deleteForm = document.getElementById('deleteConfirmForm');
    const deleteCodeText = document.getElementById('deleteModalCodeText');
    const deleteLabelText = document.getElementById('deleteModalLabelText');

    document.querySelectorAll('.delete-qr-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const code = this.getAttribute('data-code');
            const label = this.getAttribute('data-label');

            if (deleteForm) deleteForm.action = `/admin/dynamic-qrs/${id}`;
            if (deleteCodeText) deleteCodeText.textContent = code;
            if (deleteLabelText) deleteLabelText.textContent = label;
            if (deleteModal) deleteModal.show();
        });
    });

    // 4. Copy to clipboard helper with animated checkmark
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const code = this.getAttribute('data-code');
            if (!code) return;

            navigator.clipboard.writeText(code).then(() => {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-check2 text-success';
                    setTimeout(() => {
                        icon.className = 'bi bi-clipboard';
                    }, 1500);
                }
                showToast(`Code copié : ${code}`);
            }).catch(() => {
                prompt('Copier le code :', code);
            });
        });
    });

    // 5. Segmented View Switcher (Table vs Grid)
    const btnTable = document.getElementById('btnViewTable');
    const btnGrid = document.getElementById('btnViewGrid');
    const tableView = document.getElementById('dynamicQrsTableView');
    const gridView = document.getElementById('dynamicQrsGridView');

    function setView(view) {
        if (view === 'grid') {
            tableView.classList.add('d-none');
            gridView.classList.remove('d-none');
            btnGrid.classList.add('active');
            btnTable.classList.remove('active');
            localStorage.setItem('dyn_qr_view_pref', 'grid');
        } else {
            gridView.classList.add('d-none');
            tableView.classList.remove('d-none');
            btnTable.classList.add('active');
            btnGrid.classList.remove('active');
            localStorage.setItem('dyn_qr_view_pref', 'table');
        }
    }

    if (btnTable && btnGrid) {
        btnTable.addEventListener('click', () => setView('table'));
        btnGrid.addEventListener('click', () => setView('grid'));

        const savedPref = localStorage.getItem('dyn_qr_view_pref');
        if (savedPref === 'grid') {
            setView('grid');
        }
    }
});
</script>
@endpush
@endsection
