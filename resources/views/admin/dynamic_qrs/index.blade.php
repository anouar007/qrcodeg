@extends('layouts.app')

@section('title', 'Dynamic QR Codes (VP- Engine)')

@push('styles')
<style>
/* ==========================================================================
   DYNAMIC QR ENGINE PRO DESIGN SYSTEM (YNS NFC / NEO-SAAS ENTERPRISE)
   ========================================================================== */
:root {
    --dyn-primary: #6320ee;
    --dyn-primary-hover: #5015d8;
    --dyn-primary-light: #f3f0ff;
    --dyn-primary-border: #ddd6fe;
    --dyn-primary-glow: rgba(99, 32, 238, 0.14);
    --dyn-success: #10b981;
    --dyn-success-light: #ecfdf5;
    --dyn-success-border: #a7f3d0;
    --dyn-slate-900: #0f172a;
    --dyn-slate-700: #334155;
    --dyn-slate-500: #64748b;
    --dyn-slate-200: #e2e8f0;
    --dyn-slate-100: #f1f5f9;
    --dyn-slate-50: #f8fafc;
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

/* Executive Hero Banner */
.dyn-hero-card {
    background: linear-gradient(135deg, #ffffff 0%, #faf8ff 65%, #f3f0ff 100%);
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.35rem 1.6rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px -4px rgba(15, 23, 42, 0.04);
}
.dyn-hero-card::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(99, 32, 238, 0.08) 0%, transparent 70%);
    pointer-events: none;
}

/* Monospace VP- Code Badge */
.vp-code-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'JetBrains Mono', 'SF Mono', Monaco, monospace;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.4px;
    padding: 0.28rem 0.65rem;
    border-radius: 8px;
    background: #f3f0ff !important;
    color: #6320ee !important;
    border: 1px solid #ddd6fe !important;
    box-shadow: none !important;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none;
    line-height: 1.25;
}
.vp-code-badge:hover {
    background: #ede9fe !important;
    color: #4f46e5 !important;
    border-color: #c4b5fd !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(99, 32, 238, 0.14) !important;
}

/* Interactive Mini QR Thumbnail in Table Row */
.row-qr-thumb {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    cursor: pointer;
    position: relative;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.row-qr-thumb:hover {
    border-color: #6320ee;
    transform: scale(1.06);
    box-shadow: 0 6px 14px rgba(99, 32, 238, 0.18);
}
.row-qr-thumb .zoom-badge {
    position: absolute;
    inset: 0;
    border-radius: 9px;
    background: rgba(99, 32, 238, 0.8);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.15s ease;
    font-size: 0.9rem;
}
.row-qr-thumb:hover .zoom-badge {
    opacity: 1;
}

/* KPI Stat Cards */
.kpi-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.2rem 1.35rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.kpi-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px -6px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}
.kpi-icon-box {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.kpi-progress-track {
    height: 4px;
    background: #f1f5f9;
    border-radius: 99px;
    overflow: hidden;
    margin-top: 0.7rem;
}
.kpi-progress-bar {
    height: 100%;
    border-radius: 99px;
    transition: width 0.5s ease;
}

/* Command & Filter Bar */
.filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}
.filter-input {
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 0.84rem !important;
    height: 38px !important;
    padding-top: 0.35rem !important;
    padding-bottom: 0.35rem !important;
    border-radius: 10px !important;
    transition: all 0.2s ease;
}
.filter-input:focus {
    background-color: #ffffff !important;
    border-color: #6320ee !important;
    box-shadow: 0 0 0 3px rgba(99, 32, 238, 0.12) !important;
}

/* Quick Status Filter Tabs */
.quick-status-pills {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}
.quick-status-pill {
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    padding: 0.32rem 0.75rem;
    border-radius: 7px;
    text-decoration: none;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.quick-status-pill:hover {
    color: #0f172a;
}
.quick-status-pill.active {
    background: #ffffff;
    color: #6320ee;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
}

/* Segmented View Switcher Capsule */
.dyn-segmented-control {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 3px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    flex-shrink: 0;
}
.dyn-segmented-btn {
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 600;
    height: 30px;
    padding: 0 0.7rem;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    white-space: nowrap;
}
.dyn-segmented-btn:hover {
    color: #0f172a;
}
.dyn-segmented-btn.active {
    background: #ffffff;
    color: #6320ee;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
}

/* Per-Page Top Capsule */
.dyn-perpage-capsule {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    height: 38px;
    padding: 0 0.35rem 0 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.dyn-perpage-capsule label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
}
.dyn-perpage-capsule select {
    border: none !important;
    background-color: #ffffff !important;
    border-radius: 7px !important;
    height: 28px !important;
    min-height: 28px !important;
    padding: 0 1.65rem 0 0.55rem !important;
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05) !important;
    cursor: pointer;
}

/* Table Card & Zero-Horizontal-Scroll Fluid Layout */
.dyn-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
    overflow: visible !important;
}
.dyn-table-responsive {
    overflow: visible !important;
    min-height: 260px;
    border-radius: 16px;
}
.dyn-table {
    margin-bottom: 0;
    vertical-align: middle;
    width: 100%;
    table-layout: fixed;
}
.dyn-table thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 700;
    font-size: 0.7rem;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    padding: 0.85rem 0.65rem;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
    vertical-align: middle;
}
.dyn-table tbody td {
    padding: 0.8rem 0.65rem;
    border-bottom: 1px solid #f1f5f9;
    background-color: #ffffff;
    vertical-align: middle;
    transition: background-color 0.15s ease;
    min-width: 0;
}
.dyn-table tbody tr:hover td {
    background-color: #fafbfc;
}
.dyn-table tbody tr.row-selected td {
    background-color: #f8f5ff !important;
}
.dyn-table tbody tr:last-child td {
    border-bottom: none;
}

/* Custom Checkbox */
.dyn-check {
    width: 17px !important;
    height: 17px !important;
    margin-top: 0 !important;
    border-radius: 5px !important;
    border: 1.5px solid #cbd5e1 !important;
    cursor: pointer;
    vertical-align: middle;
}
.dyn-check:checked {
    background-color: #6320ee !important;
    border-color: #6320ee !important;
}

/* Small Copy Icon Buttons */
.dyn-mini-copy-btn {
    width: 24px;
    height: 24px;
    padding: 0 !important;
    border: 1px solid transparent;
    border-radius: 6px;
    background: transparent;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    cursor: pointer;
}
.dyn-mini-copy-btn:hover {
    background: #f1f5f9;
    border-color: #e2e8f0;
    color: #6320ee;
    transform: none !important;
}

/* Target URL Pill Box in Row */
.target-url-capsule {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.28rem 0.35rem 0.28rem 0.6rem;
    width: 100%;
    min-width: 0;
    transition: all 0.15s ease;
}
.target-url-capsule:hover {
    border-color: #cbd5e1;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
}
.target-edit-pill-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.2rem 0.55rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}
.target-edit-pill-btn:hover {
    border-color: #c4b5fd;
    background: #f3f0ff;
    color: #6320ee;
}

/* Action Dropdown Trigger & Menu Styling */
.dyn-actions-trigger {
    height: 34px;
    padding: 0 0.75rem !important;
    border-radius: 9px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
}
.dyn-actions-trigger:hover,
.dyn-actions-trigger[aria-expanded="true"] {
    border-color: #c4b5fd;
    background: #f3f0ff;
    color: #6320ee;
    transform: none !important;
    box-shadow: 0 2px 8px rgba(99, 32, 238, 0.12);
}
.dyn-actions-trigger::after {
    display: none !important;
}

.dyn-dropdown-menu {
    z-index: 1065 !important;
    min-width: 265px;
    padding: 0.45rem;
    border-radius: 13px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 20px 42px -8px rgba(15, 23, 42, 0.18), 0 0 0 1px rgba(15, 23, 42, 0.04);
}
.dyn-dropdown-menu .dropdown-header {
    font-size: 0.67rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.4rem 0.75rem 0.2rem;
}
.dyn-dropdown-menu .dropdown-item {
    font-size: 0.81rem;
    padding: 0.45rem 0.75rem;
    border-radius: 8px;
    color: #334155;
    font-weight: 500;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
}
.dyn-dropdown-menu .dropdown-item i {
    font-size: 0.92rem;
    width: 18px;
    text-align: center;
}
.dyn-dropdown-menu .dropdown-item:hover {
    background-color: #f3f0ff;
    color: #6320ee;
    transform: translateX(2px);
}
.dyn-dropdown-menu .dropdown-item.dropdown-item-danger {
    color: #dc2626;
}
.dyn-dropdown-menu .dropdown-item.dropdown-item-danger:hover {
    background-color: #fef2f2;
    color: #b91c1c;
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
    min-height: 165px;
}
.qr-thumb-box {
    width: 108px;
    height: 108px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s ease, border-color 0.2s ease;
    cursor: pointer;
}
.dynamic-qr-grid-card:hover .qr-thumb-box {
    transform: scale(1.05);
    border-color: #c4b5fd;
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
    display: inline-flex;
    align-items: center;
}
.status-pill-toggle:hover {
    transform: scale(1.05);
}

/* Floating Bulk Selection Action Bar */
.dyn-bulk-action-bar {
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%) translateY(120%);
    z-index: 1080;
    background: #0f172a;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    padding: 0.65rem 1.15rem;
    box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.45);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
    opacity: 0;
    pointer-events: none;
}
.dyn-bulk-action-bar.active {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
    pointer-events: auto;
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

/* Unified Pagination Links */
.dyn-pagination-links nav > div:first-child {
    display: none !important;
}
.dyn-pagination-links nav > div:last-child > div:first-child {
    display: none !important;
}
.dyn-pagination-links .pagination {
    margin-bottom: 0 !important;
    gap: 4px;
}
.dyn-pagination-links .page-link {
    border-radius: 8px !important;
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
    border: 1px solid #e2e8f0;
    padding: 0.35rem 0.7rem;
}
.dyn-pagination-links .page-item.active .page-link {
    background-color: #6320ee !important;
    border-color: #6320ee !important;
    color: #ffffff !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Executive Hero Header -->
    <div class="dyn-hero-card mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="status-dot-radar"></span>
                    <span class="text-uppercase font-mono text-muted" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em;">
                        Dynamic Telemetry Core &bull; 302 Instant Router &bull; Vector Print Engine
                    </span>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); font-size: 1.1rem;">
                        <i class="bi bi-qr-code-scan"></i>
                    </span>
                    <h1 class="h3 fw-extrabold text-dark mb-0" style="letter-spacing: -0.02em;">Dynamic QR Engine</h1>
                    <span class="badge rounded-pill bg-white text-dark border font-mono fw-semibold shadow-xs" style="font-size: 0.75rem; padding: 0.35rem 0.75rem;">
                        {{ number_format($qrCodes->total()) }} codes
                    </span>
                </div>
                <p class="text-muted small mb-0">
                    Identifiants permanents <span class="vp-code-badge py-0 px-2" style="font-size: 0.74rem;">VP-XXXXXX</span>. Modifiez la destination en 1 clic sans jamais réimprimer vos plaques ou cartes.
                </p>
            </div>

            <!-- Primary Action Suite -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Export CSV Button -->
                <a href="{{ route('admin.dynamic-qrs.export', request()->query()) }}" class="btn btn-light bg-white border d-inline-flex align-items-center gap-2 shadow-xs" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; height: 40px; padding: 0 0.95rem; color: #334155;" title="Exporter la sélection en CSV">
                    <i class="bi bi-download text-secondary"></i>
                    <span>Export CSV</span>
                </a>

                <!-- Single QR Quick Modal Trigger -->
                <button type="button" class="btn btn-light bg-white border border-primary-subtle text-primary d-inline-flex align-items-center gap-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#singleCreateModal" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; height: 40px; padding: 0 1rem;">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Nouveau QR</span>
                </button>

                <!-- Bulk Modal Trigger -->
                <button type="button" class="btn btn-light bg-white border d-inline-flex align-items-center gap-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal" style="border-radius: 10px; font-weight: 600; font-size: 0.85rem; height: 40px; padding: 0 1rem; color: #1e293b;">
                    <i class="bi bi-layers-fill text-primary"></i>
                    <span>Génération par Lot</span>
                </button>

                <!-- Studio Gabarit Button -->
                <a href="{{ route('admin.dynamic-qrs.create') }}" class="btn text-white d-inline-flex align-items-center gap-2 shadow-sm border-0" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); border-radius: 10px; font-weight: 600; font-size: 0.85rem; height: 40px; padding: 0 1.2rem;">
                    <i class="bi bi-palette2"></i>
                    <span>Studio Gabarit &amp; PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics Stat KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.4px;">Total QR Dynamiques</span>
                        <div class="kpi-icon-box" style="background: #f3f0ff; color: #6320ee;">
                            <i class="bi bi-qr-code"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div class="fs-3 fw-extrabold text-dark font-mono">{{ number_format($metrics['total']) }}</div>
                        <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle font-mono" style="font-size: 0.7rem;">
                            {{ number_format($metrics['assigned'] ?? 0) }} assignés
                        </span>
                    </div>
                </div>
                <div class="kpi-progress-track">
                    <div class="kpi-progress-bar" style="width: {{ $metrics['total'] > 0 ? min(100, max(12, round((($metrics['assigned'] ?? 0) / $metrics['total']) * 100))) : 0 }}%; background: #6320ee;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.4px;">Scans Cumulés</span>
                        <div class="kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
                            <i class="bi bi-broadcast"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div class="fs-3 fw-extrabold text-dark font-mono">{{ number_format($metrics['scans']) }}</div>
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle font-mono" style="font-size: 0.7rem;">
                            ~{{ $metrics['avg_scans'] ?? 0 }} / code
                        </span>
                    </div>
                </div>
                <div class="kpi-progress-track">
                    <div class="kpi-progress-bar" style="width: {{ $metrics['scans'] > 0 ? 85 : 8 }}%; background: #10b981;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.4px;">Codes Actifs (En Ligne)</span>
                        <div class="kpi-icon-box" style="background: #e0f2fe; color: #0284c7;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div class="fs-3 fw-extrabold text-dark font-mono">{{ number_format($metrics['active']) }}</div>
                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle font-mono" style="font-size: 0.7rem;">
                            {{ $metrics['active_rate'] ?? 0 }}% actifs
                        </span>
                    </div>
                </div>
                <div class="kpi-progress-track">
                    <div class="kpi-progress-bar" style="width: {{ $metrics['active_rate'] ?? 0 }}%; background: #0284c7;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-stat-card shadow-xs">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold text-uppercase font-mono" style="font-size: 0.7rem; letter-spacing: 0.4px;">Inactifs / Archivés</span>
                        <div class="kpi-icon-box" style="background: #f8fafc; color: #64748b;">
                            <i class="bi bi-pause-circle"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div class="fs-3 fw-extrabold text-dark font-mono">{{ number_format($metrics['inactive']) }}</div>
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle font-mono" style="font-size: 0.7rem;">
                            En pause
                        </span>
                    </div>
                </div>
                <div class="kpi-progress-track">
                    <div class="kpi-progress-bar" style="width: {{ $metrics['total'] > 0 ? round(($metrics['inactive'] / $metrics['total']) * 100) : 0 }}%; background: #94a3b8;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Command Bar: Quick Status Tabs + Search, Filters & View Toggle -->
    @php
        $activePerPage = (string) ($perPage ?? request('per_page', '20'));
    @endphp
    <div class="filter-card p-3 mb-4">
        <!-- Top Row: Quick Status Filter Pills + Per-Page & View Switcher -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2.5 pb-3 mb-3 border-bottom" style="border-color: #f1f5f9 !important;">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="quick-status-pills">
                    <a href="{{ route('admin.dynamic-qrs.index', array_filter(array_merge(request()->query(), ['status' => null, 'page' => null]))) }}"
                       class="quick-status-pill {{ !request('status') ? 'active' : '' }}">
                        <span>Tous</span>
                        <span class="badge rounded-pill bg-light text-secondary border font-mono" style="font-size: 0.68rem;">{{ $metrics['total'] }}</span>
                    </a>
                    <a href="{{ route('admin.dynamic-qrs.index', array_merge(request()->query(), ['status' => 'active', 'page' => null])) }}"
                       class="quick-status-pill {{ request('status') === 'active' ? 'active' : '' }}">
                        <span class="d-inline-block rounded-circle bg-success" style="width: 6px; height: 6px;"></span>
                        <span>Actifs</span>
                        <span class="badge rounded-pill bg-light text-secondary border font-mono" style="font-size: 0.68rem;">{{ $metrics['active'] }}</span>
                    </a>
                    <a href="{{ route('admin.dynamic-qrs.index', array_merge(request()->query(), ['status' => 'inactive', 'page' => null])) }}"
                       class="quick-status-pill {{ request('status') === 'inactive' ? 'active' : '' }}">
                        <span class="d-inline-block rounded-circle bg-secondary" style="width: 6px; height: 6px;"></span>
                        <span>Inactifs</span>
                        <span class="badge rounded-pill bg-light text-secondary border font-mono" style="font-size: 0.68rem;">{{ $metrics['inactive'] }}</span>
                    </a>
                </div>

                <span class="text-muted d-none d-xxl-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>                </span>
            </div>

            <!-- Right Controls: Items Per Page Capsule + View Switcher (Table / Cards) -->
            <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                <div class="dyn-perpage-capsule">
                    <label for="dynPerPageSelectTop"><i class="bi bi-eye me-1 text-primary"></i>Afficher :</label>
                    <select id="dynPerPageSelectTop" class="form-select" onchange="changeDynPerPage(this.value)" title="Nombre d'éléments affichés par page">
                        <option value="12" {{ $activePerPage === '12' ? 'selected' : '' }}>12 / page</option>
                        <option value="20" {{ $activePerPage === '20' ? 'selected' : '' }}>20 / page</option>
                        <option value="24" {{ $activePerPage === '24' ? 'selected' : '' }}>24 / page</option>
                        <option value="48" {{ $activePerPage === '48' ? 'selected' : '' }}>48 / page</option>
                        <option value="100" {{ $activePerPage === '100' ? 'selected' : '' }}>100 / page</option>
                        <option value="all" {{ $activePerPage === 'all' ? 'selected' : '' }}>Tout ({{ $qrCodes->total() }})</option>
                    </select>
                </div>

                <div class="dyn-segmented-control" role="group" aria-label="Sélecteur d'affichage">
                    <button type="button" class="dyn-segmented-btn active" id="btnViewTable" title="Affichage tableau">
                        <i class="bi bi-list-ul"></i>
                        <span>Tableau</span>
                    </button>
                    <button type="button" class="dyn-segmented-btn" id="btnViewGrid" title="Affichage visuel en cartes">
                        <i class="bi bi-grid-fill"></i>
                        <span>Cartes</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Balanced Flex Filter Toolbar (Zero Overlap) -->
        <form method="GET" action="{{ route('admin.dynamic-qrs.index') }}" class="d-flex flex-wrap align-items-center gap-2" id="dynFilterForm">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="hidden" name="per_page" id="dynPerPageHiddenInput" value="{{ $activePerPage }}">

            <!-- Search Box -->
            <div class="position-relative" style="flex: 1 1 260px; min-width: 220px;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.84rem; pointer-events: none;"></i>
                <input type="text" name="search" id="dynSearchInput" value="{{ request('search') }}" class="form-control filter-input" placeholder="Code VP-, libellé, client ou URL..." style="padding-left: 2.25rem !important;">
            </div>

            <!-- Template Filter -->
            <div style="flex: 0 1 185px; min-width: 155px;">
                <select name="template_type" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Tous les gabarits</option>
                    <option value="google_review" {{ request('template_type') === 'google_review' ? 'selected' : '' }}>Avis Google</option>
                    <option value="social_media" {{ request('template_type') === 'social_media' ? 'selected' : '' }}>Réseaux Sociaux</option>
                    <option value="menu" {{ request('template_type') === 'menu' ? 'selected' : '' }}>Menu Digital</option>
                    <option value="custom" {{ request('template_type') === 'custom' ? 'selected' : '' }}>Gabarit Sur-Mesure</option>
                </select>
            </div>

            <!-- Client Filter -->
            <div style="flex: 0 1 210px; min-width: 170px;">
                <select name="client_id" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="">Tous les clients</option>
                    <option value="unassigned" {{ request('client_id') === 'unassigned' ? 'selected' : '' }}>— Non assignés —</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (string) request('client_id') === (string) $client->id ? 'selected' : '' }}>
                            {{ $client->name }} {{ $client->project_name ? "({$client->project_name})" : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Dropdown -->
            <div style="flex: 0 1 180px; min-width: 155px;">
                <select name="sort" class="form-select filter-input" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Trier : Plus récents</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Trier : Plus anciens</option>
                    <option value="scans_high" {{ request('sort') === 'scans_high' ? 'selected' : '' }}>Trier : Plus scannés</option>
                    <option value="scans_low" {{ request('sort') === 'scans_low' ? 'selected' : '' }}>Trier : Moins scannés</option>
                    <option value="code" {{ request('sort') === 'code' ? 'selected' : '' }}>Trier : Code VP- (A-Z)</option>
                    <option value="label" {{ request('sort') === 'label' ? 'selected' : '' }}>Trier : Libellé (A-Z)</option>
                </select>
            </div>

            <!-- Submit & Reset Actions -->
            <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                <button type="submit" class="btn text-white d-inline-flex align-items-center justify-content-center gap-1.5 shadow-xs border-0" style="background: linear-gradient(135deg, #6320ee 0%, #4f46e5 100%); height: 38px !important; padding: 0 1.1rem !important; border-radius: 10px !important; font-weight: 600 !important; font-size: 0.83rem !important; white-space: nowrap;">
                    <i class="bi bi-funnel-fill" style="font-size: 0.78rem;"></i>
                    <span>Filtrer</span>
                </button>

                @if(request()->hasAny(['search', 'template_type', 'client_id', 'status', 'sort', 'per_page']))
                    <a href="{{ route('admin.dynamic-qrs.index') }}" class="btn btn-light border d-inline-flex align-items-center justify-content-center gap-1" title="Réinitialiser tous les filtres" style="height: 38px !important; padding: 0 0.8rem !important; border-radius: 10px !important; font-size: 0.8rem !important; font-weight: 600 !important; color: #64748b; white-space: nowrap;">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span class="d-none d-sm-inline">Réinitialiser</span>
                    </a>
                @endif
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
                        <th class="ps-3" style="width: 40px;">
                            <input type="checkbox" class="form-check-input dyn-check" id="selectAllQrs" title="Tout sélectionner">
                        </th>
                        <th style="width: 195px;">QR &amp; Code VP-</th>
                        <th style="width: 19%;">Libellé &amp; Client</th>
                        <th>Destination Active (302)</th>
                        <th style="width: 130px;">Gabarit</th>
                        <th class="text-center" style="width: 90px;">Scans</th>
                        <th style="width: 90px;">Statut</th>
                        <th class="text-end pe-3" style="width: 108px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qrCodes as $qr)
                        @php
                            $lastScan = $qr->scanLogs->sortByDesc('scanned_at')->first();
                        @endphp
                        <tr id="tableRowQr{{ $qr->id }}" data-qr-id="{{ $qr->id }}">
                            <!-- Multi-select Checkbox -->
                            <td class="ps-3">
                                <input type="checkbox" class="form-check-input dyn-check qr-row-checkbox" value="{{ $qr->id }}" data-code="{{ $qr->code }}">
                            </td>

                            <!-- Live Mini QR Thumbnail + Code VP- -->
                            <td>
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <div class="row-qr-thumb qr-lightbox-trigger"
                                         data-id="{{ $qr->id }}"
                                         data-code="{{ $qr->code }}"
                                         data-label="{{ $qr->label }}"
                                         data-target="{{ $qr->target_url }}"
                                         data-encoded="{{ $qr->encoded_url }}"
                                         data-svg-url="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 320]) }}"
                                         data-png-download="{{ route('admin.dynamic-qrs.png', [$qr, 'size' => 1000]) }}"
                                         data-svg-download="{{ route('admin.dynamic-qrs.svg', [$qr, 'size' => 1000]) }}"
                                         data-pdf-download="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => '85x55']) }}"
                                         title="Aperçu HD & Téléchargement rapide">
                                        <img src="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 90]) }}"
                                             alt="{{ $qr->code }}"
                                             loading="lazy"
                                             class="w-100 h-100 object-fit-contain">
                                        <span class="zoom-badge"><i class="bi bi-arrows-angle-expand"></i></span>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="{{ route('admin.dynamic-qrs.show', $qr) }}" class="text-decoration-none">
                                                <span class="vp-code-badge">{{ $qr->code }}</span>
                                            </a>
                                            <button type="button" class="dyn-mini-copy-btn copy-btn" data-code="{{ $qr->code }}" title="Copier le code {{ $qr->code }}">
                                                <i class="bi bi-clipboard" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button type="button" class="dyn-mini-copy-btn copy-url-btn" data-url="{{ $qr->encoded_url }}" title="Copier le lien permanent {{ $qr->encoded_url }}">
                                                <i class="bi bi-link-45deg" style="font-size: 0.9rem;"></i>
                                            </button>
                                        </div>
                                        <div class="text-muted font-mono mt-1 text-truncate" style="font-size: 0.66rem;">
                                            /qr/{{ $qr->code }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Label & Client -->
                            <td>
                                <a href="{{ route('admin.dynamic-qrs.show', $qr) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="font-size: 0.86rem;" title="{{ $qr->label }}">
                                    {{ $qr->label }}
                                </a>
                                @if($qr->client)
                                    <a href="{{ route('admin.clients.show', $qr->client) }}" class="small text-muted text-decoration-none d-flex align-items-center gap-1 mt-1 min-w-0">
                                        <i class="bi bi-building text-primary flex-shrink-0" style="font-size: 0.72rem;"></i>
                                        <span class="text-truncate">{{ $qr->client->name }}</span>
                                    </a>
                                @else
                                    <span class="small text-muted fst-italic mt-1 d-block text-truncate" style="font-size: 0.72rem;">Non assigné</span>
                                @endif
                            </td>

                            <!-- Target Destination with Instant In-Place Editor -->
                            <td>
                                <div class="target-url-capsule">
                                    <a href="{{ $qr->target_url }}" target="_blank" id="qrTargetLink{{ $qr->id }}" class="small text-truncate d-block text-decoration-none text-secondary font-mono min-w-0 flex-grow-1" style="font-size: 0.75rem;" title="{{ $qr->target_url }}">
                                        <i class="bi bi-box-arrow-up-right me-1 text-primary"></i><span class="target-text">{{ $qr->target_url }}</span>
                                    </a>
                                    <button type="button"
                                            class="target-edit-pill-btn edit-target-btn"
                                            data-id="{{ $qr->id }}"
                                            data-code="{{ $qr->code }}"
                                            data-target="{{ $qr->target_url }}"
                                            title="Modifier la destination instantanément">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                        <span>Éditer</span>
                                    </button>
                                </div>
                            </td>

                            <!-- Template Type -->
                            <td>
                                @if($qr->template_type === 'google_review')
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle fw-semibold" style="font-size: 0.73rem;">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Avis Google
                                    </span>
                                @elseif($qr->template_type === 'social_media')
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle fw-semibold" style="font-size: 0.73rem;">
                                        <i class="bi bi-share-fill me-1"></i> Réseaux Sociaux
                                    </span>
                                @elseif($qr->template_type === 'menu')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-semibold" style="font-size: 0.73rem;">
                                        <i class="bi bi-cup-hot-fill me-1"></i> Menu Digital
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-dark border fw-semibold" style="font-size: 0.73rem;">
                                        <i class="bi bi-palette me-1 text-primary"></i> {{ ucfirst(str_replace('_', ' ', $qr->template_type)) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Scans Count & Last Scan Telemetry -->
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono fw-bold px-2 py-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-broadcast text-success me-1"></i>{{ number_format($qr->scans_count) }}
                                </span>
                                @if($lastScan && $lastScan->scanned_at)
                                    <div class="text-muted font-mono mt-1" style="font-size: 0.65rem;" title="Dernier scan : {{ $lastScan->scanned_at->format('d/m/Y H:i') }}">
                                        {{ $lastScan->scanned_at->diffForHumans(null, true, true) }}
                                    </div>
                                @endif
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

                            <!-- Unified Actions Dropdown -->
                            <td class="text-end pe-3">
                                <div class="dropdown d-inline-block">
                                    <button type="button"
                                            class="dyn-actions-trigger"
                                            data-bs-toggle="dropdown"
                                            data-bs-popper-config='{"strategy":"fixed"}'
                                            aria-expanded="false">
                                        <span>Actions</span>
                                        <i class="bi bi-three-dots-vertical text-muted" style="font-size: 0.82rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dyn-dropdown-menu">
                                        <li class="dropdown-header">Gestion &amp; Routage</li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item qr-lightbox-trigger"
                                                    data-id="{{ $qr->id }}"
                                                    data-code="{{ $qr->code }}"
                                                    data-label="{{ $qr->label }}"
                                                    data-target="{{ $qr->target_url }}"
                                                    data-encoded="{{ $qr->encoded_url }}"
                                                    data-svg-url="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 320]) }}"
                                                    data-png-download="{{ route('admin.dynamic-qrs.png', [$qr, 'size' => 1000]) }}"
                                                    data-svg-download="{{ route('admin.dynamic-qrs.svg', [$qr, 'size' => 1000]) }}"
                                                    data-pdf-download="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => '85x55']) }}">
                                                <i class="bi bi-qr-code-scan text-primary"></i>
                                                <span>Aperçu QR Grand Format</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item edit-target-btn"
                                                    data-id="{{ $qr->id }}"
                                                    data-code="{{ $qr->code }}"
                                                    data-target="{{ $qr->target_url }}">
                                                <i class="bi bi-lightning-charge-fill text-warning"></i>
                                                <span>Modifier l'URL cible (302)</span>
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('qr.redirect', ['code' => $qr->code]) }}" target="_blank">
                                                <i class="bi bi-box-arrow-up-right text-info"></i>
                                                <span>Tester la redirection live</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.show', $qr) }}">
                                                <i class="bi bi-eye text-secondary"></i>
                                                <span>Détails &amp; Télémétrie</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.edit', $qr) }}">
                                                <i class="bi bi-pencil text-secondary"></i>
                                                <span>Configuration complète</span>
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li class="dropdown-header">Cartes PDF Prêtes à Imprimer</li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => 'google_review', 'dimension' => '85x55']) }}">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <span>Carte Avis Google (85×55mm)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => 'social_media', 'dimension' => '85x55']) }}">
                                                <i class="bi bi-share-fill text-primary"></i>
                                                <span>Carte Réseaux Sociaux (85×55mm)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => 'menu', 'dimension' => '85x55']) }}">
                                                <i class="bi bi-cup-hot-fill text-warning"></i>
                                                <span>Carte Menu Digital (85×55mm)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => 'a6']) }}">
                                                <i class="bi bi-printer text-success"></i>
                                                <span>Plaque A6 (105×148mm)</span>
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li class="dropdown-header">Fichiers Bruts Haute Définition</li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.svg', $qr) }}">
                                                <i class="bi bi-filetype-svg text-primary"></i>
                                                <span>Télécharger SVG Vectoriel</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.png', $qr) }}">
                                                <i class="bi bi-filetype-png text-info"></i>
                                                <span>Télécharger PNG HD</span>
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item dropdown-item-danger delete-qr-btn"
                                                    data-id="{{ $qr->id }}"
                                                    data-code="{{ $qr->code }}"
                                                    data-label="{{ $qr->label }}">
                                                <i class="bi bi-trash"></i>
                                                <span>Supprimer ce QR code</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 68px; height: 68px;">
                                    <i class="bi bi-qr-code-scan text-muted fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Aucun QR Code Dynamique Trouvé</h6>
                                <p class="small text-muted mb-3">Créez votre premier code unitaire ou lancez un lot avec gabarit studio.</p>
                                <div class="d-inline-flex gap-2">
                                    <button type="button" class="btn btn-sm text-white px-3" data-bs-toggle="modal" data-bs-target="#singleCreateModal" style="background-color: #6320ee; border-radius: 8px;">
                                        <i class="bi bi-plus-lg me-1"></i> Créer un QR Code
                                    </button>
                                    <a href="{{ route('admin.dynamic-qrs.create') }}" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 8px;">
                                        <i class="bi bi-palette2 me-1"></i> Ouvrir le Studio
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                        <div class="position-absolute top-0 start-0 m-3 d-flex align-items-center gap-1">
                            <span class="vp-code-badge">{{ $qr->code }}</span>
                            <button type="button" class="dyn-mini-copy-btn bg-white border shadow-xs copy-btn" data-code="{{ $qr->code }}" title="Copier le code">
                                <i class="bi bi-clipboard" style="font-size: 0.72rem;"></i>
                            </button>
                        </div>
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge rounded-pill bg-white text-dark border font-mono fw-bold shadow-xs">
                                <i class="bi bi-broadcast text-success me-1"></i>{{ number_format($qr->scans_count) }}
                            </span>
                        </div>

                        <!-- Lazy Sharp SVG Thumbnail (Click to open Lightbox) -->
                        <div class="qr-thumb-box qr-lightbox-trigger"
                             data-id="{{ $qr->id }}"
                             data-code="{{ $qr->code }}"
                             data-label="{{ $qr->label }}"
                             data-target="{{ $qr->target_url }}"
                             data-encoded="{{ $qr->encoded_url }}"
                             data-svg-url="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 320]) }}"
                             data-png-download="{{ route('admin.dynamic-qrs.png', [$qr, 'size' => 1000]) }}"
                             data-svg-download="{{ route('admin.dynamic-qrs.svg', [$qr, 'size' => 1000]) }}"
                             data-pdf-download="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => '85x55']) }}"
                             title="Cliquer pour agrandir">
                            <img src="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 140]) }}"
                                 alt="{{ $qr->code }}"
                                 loading="lazy"
                                 class="w-100 h-100 object-fit-contain">
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-3 flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $qr->label }}">{{ $qr->label }}</h6>
                            <button type="button"
                                    class="status-pill-toggle toggle-status-btn"
                                    data-id="{{ $qr->id }}"
                                    data-status="{{ $qr->status }}"
                                    title="Basculer le statut">
                                @if($qr->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle small py-1 px-2" id="statusBadgeGrid{{ $qr->id }}">Actif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle small py-1 px-2" id="statusBadgeGrid{{ $qr->id }}">Inactif</span>
                                @endif
                            </button>
                        </div>

                        <!-- Client & Template -->
                        <div class="d-flex align-items-center justify-content-between small text-muted mb-3">
                            <span class="text-truncate" style="max-width: 170px;">
                                @if($qr->client)
                                    <i class="bi bi-building text-primary me-1"></i>{{ $qr->client->name }}
                                @else
                                    <i class="bi bi-dash text-muted me-1"></i>Non assigné
                                @endif
                            </span>
                            <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.68rem;">
                                {{ ucfirst(str_replace('_', ' ', $qr->template_type)) }}
                            </span>
                        </div>

                        <!-- Target URL box with quick edit -->
                        <div class="mt-auto p-2 rounded-3 bg-light border d-flex align-items-center justify-content-between gap-2">
                            <div class="min-w-0 text-truncate font-mono small text-muted" style="font-size: 0.74rem;" id="qrTargetGridText{{ $qr->id }}">
                                <i class="bi bi-link-45deg text-primary me-1"></i><span class="target-text">{{ $qr->target_url }}</span>
                            </div>
                            <button type="button"
                                    class="target-edit-pill-btn edit-target-btn"
                                    data-id="{{ $qr->id }}"
                                    data-code="{{ $qr->code }}"
                                    data-target="{{ $qr->target_url }}"
                                    title="Changer l'URL">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Card Footer Unified Actions Dropdown -->
                    <div class="card-footer bg-white border-top p-2.5 px-3 d-flex align-items-center justify-content-between">
                        <span class="text-muted font-mono" style="font-size: 0.72rem;">
                            <i class="bi bi-clock me-1"></i>{{ $qr->created_at?->format('d/m/Y') }}
                        </span>
                        <div class="dropdown">
                            <button type="button"
                                    class="dyn-actions-trigger"
                                    data-bs-toggle="dropdown"
                                    data-bs-popper-config='{"strategy":"fixed"}'
                                    aria-expanded="false">
                                <span>Actions</span>
                                <i class="bi bi-three-dots-vertical text-muted" style="font-size: 0.82rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dyn-dropdown-menu">
                                <li class="dropdown-header">Gestion &amp; Routage</li>
                                <li>
                                    <button type="button"
                                            class="dropdown-item qr-lightbox-trigger"
                                            data-id="{{ $qr->id }}"
                                            data-code="{{ $qr->code }}"
                                            data-label="{{ $qr->label }}"
                                            data-target="{{ $qr->target_url }}"
                                            data-encoded="{{ $qr->encoded_url }}"
                                            data-svg-url="{{ route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 320]) }}"
                                            data-png-download="{{ route('admin.dynamic-qrs.png', [$qr, 'size' => 1000]) }}"
                                            data-svg-download="{{ route('admin.dynamic-qrs.svg', [$qr, 'size' => 1000]) }}"
                                            data-pdf-download="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => '85x55']) }}">
                                        <i class="bi bi-qr-code-scan text-primary"></i>
                                        <span>Aperçu QR Grand Format</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button"
                                            class="dropdown-item edit-target-btn"
                                            data-id="{{ $qr->id }}"
                                            data-code="{{ $qr->code }}"
                                            data-target="{{ $qr->target_url }}">
                                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                                        <span>Modifier l'URL cible (302)</span>
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('qr.redirect', ['code' => $qr->code]) }}" target="_blank">
                                        <i class="bi bi-box-arrow-up-right text-info"></i>
                                        <span>Tester la redirection live</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.show', $qr) }}">
                                        <i class="bi bi-eye text-secondary"></i>
                                        <span>Détails &amp; Télémétrie</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.edit', $qr) }}">
                                        <i class="bi bi-pencil text-secondary"></i>
                                        <span>Configuration complète</span>
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-1"></li>
                                <li class="dropdown-header">Exports &amp; Impression</li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => '85x55']) }}">
                                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                                        <span>Carte PDF (85×55mm)</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $qr->id, 'template' => $qr->template_type, 'dimension' => 'a6']) }}">
                                        <i class="bi bi-printer text-success"></i>
                                        <span>Plaque A6 (105×148mm)</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.svg', $qr) }}">
                                        <i class="bi bi-filetype-svg text-primary"></i>
                                        <span>Télécharger SVG Vectoriel</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dynamic-qrs.png', $qr) }}">
                                        <i class="bi bi-filetype-png text-info"></i>
                                        <span>Télécharger PNG HD</span>
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <button type="button"
                                            class="dropdown-item dropdown-item-danger delete-qr-btn"
                                            data-id="{{ $qr->id }}"
                                            data-code="{{ $qr->code }}"
                                            data-label="{{ $qr->label }}">
                                        <i class="bi bi-trash"></i>
                                        <span>Supprimer ce QR code</span>
                                    </button>
                                </li>
                            </ul>
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

    <!-- ===================================================================== -->
    <!-- SHARED PAGINATION & ITEMS PER PAGE FOOTER (TABLE & VISUAL CARDS)      -->
    <!-- ===================================================================== -->
    @if($qrCodes->total() > 0)
        <div id="dynamicQrsPaginationBar" class="filter-card p-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="small text-muted font-mono">
                    Affichage de <strong class="text-dark">{{ $qrCodes->firstItem() ?? 1 }}</strong> à <strong class="text-dark">{{ $qrCodes->lastItem() ?? $qrCodes->count() }}</strong> sur <strong class="text-dark">{{ number_format($qrCodes->total()) }}</strong> codes
                </span>

                <div class="d-flex align-items-center gap-2">
                    <label for="dynPerPageSelectBottom" class="small text-muted mb-0" style="font-size: 0.78rem; white-space: nowrap;">Éléments par page :</label>
                    <select id="dynPerPageSelectBottom" class="form-select filter-input py-1 px-2" style="width: auto; min-width: 115px; height: 34px !important; min-height: 34px !important; font-size: 0.8rem;" onchange="changeDynPerPage(this.value)">
                        <option value="12" {{ $activePerPage === '12' ? 'selected' : '' }}>12 éléments</option>
                        <option value="20" {{ $activePerPage === '20' ? 'selected' : '' }}>20 éléments</option>
                        <option value="24" {{ $activePerPage === '24' ? 'selected' : '' }}>24 éléments</option>
                        <option value="48" {{ $activePerPage === '48' ? 'selected' : '' }}>48 éléments</option>
                        <option value="100" {{ $activePerPage === '100' ? 'selected' : '' }}>100 éléments</option>
                        <option value="all" {{ $activePerPage === 'all' ? 'selected' : '' }}>Tout ({{ $qrCodes->total() }})</option>
                    </select>
                </div>
            </div>

            @if($qrCodes->hasPages())
                <div class="dyn-pagination-links">
                    {{ $qrCodes->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<!-- ========================================================================= -->
<!-- FLOATING BULK SELECTION COMMAND BAR                                       -->
<!-- ========================================================================= -->
<div class="dyn-bulk-action-bar" id="dynBulkActionBar">
    <div class="d-flex align-items-center gap-2 pe-2 border-end border-secondary">
        <span class="badge rounded-pill text-white font-mono" style="background-color: #6320ee;" id="bulkSelectedCount">0</span>
        <span class="small fw-semibold">sélectionné(s)</span>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-outline-light py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;" onclick="submitBulkAction('activate')">
            <i class="bi bi-check-circle text-success"></i> Activer
        </button>
        <button type="button" class="btn btn-sm btn-outline-light py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;" onclick="submitBulkAction('deactivate')">
            <i class="bi bi-pause-circle text-warning"></i> Suspendre
        </button>
        <button type="button" class="btn btn-sm btn-outline-light py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#bulkTargetModal">
            <i class="bi bi-lightning-charge text-info"></i> Changer l'URL
        </button>
        <button type="button" class="btn btn-sm btn-outline-light py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#bulkAssignClientModal">
            <i class="bi bi-building"></i> Assigner Client
        </button>
        <button type="button" class="btn btn-sm btn-danger py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;" onclick="confirmBulkDelete()">
            <i class="bi bi-trash"></i> Supprimer
        </button>
    </div>

    <button type="button" class="btn btn-sm btn-link text-secondary p-0 ms-1" id="btnClearBulkSelection" title="Désélectionner tout">
        <i class="bi bi-x-lg"></i>
    </button>
</div>

<!-- ========================================================================= -->
<!-- MODAL: QUICK QR LIGHTBOX PREVIEW & DOWNLOAD                               -->
<!-- ========================================================================= -->
<div class="modal fade" id="qrLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light bg-opacity-75">
                <div class="d-flex align-items-center gap-2">
                    <span class="vp-code-badge" id="lightboxCodeBadge">VP-XXXXXX</span>
                    <h6 class="modal-title fw-bold text-dark mb-0 text-truncate" style="max-width: 210px;" id="lightboxLabelText">Aperçu QR</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="p-3 bg-white border rounded-4 shadow-xs d-inline-flex align-items-center justify-content-center mb-3" style="width: 240px; height: 240px;">
                    <img src="" id="lightboxSvgImg" alt="QR Preview" class="w-100 h-100 object-fit-contain">
                </div>

                <!-- Encoded Permanent Link -->
                <div class="mb-2 text-start">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Lien Permanent Encodé (302)</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="lightboxEncodedInput" class="form-control bg-light font-mono small" readonly style="border-radius: 8px 0 0 8px;">
                        <button type="button" class="btn btn-outline-secondary" id="lightboxCopyEncodedBtn" style="border-radius: 0 8px 8px 0;">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <!-- Current Target Destination -->
                <div class="mb-3 text-start">
                    <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Destination Active</label>
                    <div class="p-2 rounded-3 bg-light border font-mono small text-truncate text-secondary" id="lightboxTargetText"></div>
                </div>

                <!-- Direct Download Buttons -->
                <div class="d-grid gap-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="#" id="lightboxSvgDownload" class="btn btn-sm btn-outline-primary w-100 fw-semibold py-2" style="border-radius: 9px;">
                                <i class="bi bi-filetype-svg me-1"></i> Vector SVG
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" id="lightboxPngDownload" class="btn btn-sm btn-outline-info w-100 fw-semibold py-2" style="border-radius: 9px;">
                                <i class="bi bi-filetype-png me-1"></i> PNG HD
                            </a>
                        </div>
                    </div>
                    <a href="#" id="lightboxPdfDownload" class="btn btn-sm text-white w-100 fw-semibold py-2 shadow-xs border-0" style="background-color: #6320ee; border-radius: 9px;">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger la Carte PDF Prête à Imprimer
                    </a>
                </div>
            </div>
        </div>
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
                        <div class="position-relative">
                            <i class="bi bi-globe position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="pointer-events: none;"></i>
                            <input type="text" name="target_url" class="form-control filter-input" placeholder="https://..." required style="padding-left: 2.25rem !important;">
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
                    <button type="submit" class="btn btn-sm text-white px-4 fw-semibold shadow-sm border-0" style="background-color: #6320ee; border-radius: 8px;">
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
                        <div class="position-relative">
                            <i class="bi bi-globe position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="pointer-events: none;"></i>
                            <input type="text" name="target_url" id="modalTargetUrlInput" class="form-control filter-input" placeholder="https://..." required style="padding-left: 2.25rem !important;">
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
                    <button type="submit" id="targetSubmitBtn" class="btn btn-sm text-white px-3 fw-semibold shadow-sm border-0" style="background-color: #6320ee; border-radius: 8px;">
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
                    <button type="submit" class="btn btn-sm text-white px-4 shadow-sm fw-semibold border-0" style="background-color: #6320ee; border-radius: 8px;">
                        <i class="bi bi-magic me-1"></i> Générer le lot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. MODAL: BULK TARGET UPDATE & BULK CLIENT ASSIGN                         -->
<!-- ========================================================================= -->
<div class="modal fade" id="bulkTargetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h6 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Changer l'URL des QR sélectionnés</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <label class="form-label fw-semibold small text-dark mb-1">Nouvelle URL de destination commune</label>
                <input type="text" id="bulkTargetUrlInput" class="form-control filter-input" placeholder="https://...">
            </div>
            <div class="modal-footer border-top bg-light p-3">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm text-white px-3 fw-semibold border-0" style="background-color: #6320ee; border-radius: 8px;" onclick="submitBulkTarget()">
                    Appliquer à la sélection
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkAssignClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h6 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-building text-primary me-1"></i> Assigner les QR sélectionnés à un client</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <label class="form-label fw-semibold small text-dark mb-1">Choisir le client</label>
                <select id="bulkClientIdSelect" class="form-select filter-input">
                    <option value="">— Retirer l'assignation (Pool général) —</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer border-top bg-light p-3">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm text-white px-3 fw-semibold border-0" style="background-color: #6320ee; border-radius: 8px;" onclick="submitBulkClientAssign()">
                    Assigner
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 7. MODAL: UNIFIED DELETE CONFIRMATION                                     -->
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
                    const tableLink = document.getElementById(`qrTargetLink${activeEditQrId}`);
                    if (tableLink) {
                        tableLink.href = data.target_url;
                        tableLink.title = data.target_url;
                        const textSpan = tableLink.querySelector('.target-text');
                        if (textSpan) textSpan.textContent = data.target_url;
                    }
                    const gridTarget = document.getElementById(`qrTargetGridText${activeEditQrId}`);
                    if (gridTarget) {
                        const textSpan = gridTarget.querySelector('.target-text');
                        if (textSpan) textSpan.textContent = data.target_url;
                    }
                    document.querySelectorAll(`.edit-target-btn[data-id="${activeEditQrId}"]`).forEach(btn => {
                        btn.setAttribute('data-target', data.target_url);
                    });
                    document.querySelectorAll(`.qr-lightbox-trigger[data-id="${activeEditQrId}"]`).forEach(el => {
                        el.setAttribute('data-target', data.target_url);
                    });

                    if (targetModal) targetModal.hide();
                    showToast(`URL mise à jour : ${data.target_url}`);
                } else {
                    alert(data.message || 'Une erreur est survenue.');
                }
            })
            .catch(() => {
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

                    const tableBadge = document.getElementById(`statusBadgeTable${qrId}`);
                    if (tableBadge) {
                        tableBadge.className = isActive
                            ? 'badge rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold'
                            : 'badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle fw-semibold';
                        tableBadge.innerHTML = `<span class="d-inline-block rounded-circle ${isActive ? 'bg-success' : 'bg-secondary'} me-1" style="width: 6px; height: 6px;"></span>${isActive ? 'Actif' : 'Inactif'}`;
                    }

                    const gridBadge = document.getElementById(`statusBadgeGrid${qrId}`);
                    if (gridBadge) {
                        gridBadge.className = isActive
                            ? 'badge bg-success-subtle text-success border border-success-subtle small py-1 px-2'
                            : 'badge bg-secondary-subtle text-secondary border border-secondary-subtle small py-1 px-2';
                        gridBadge.textContent = isActive ? 'Actif' : 'Inactif';
                    }

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

    // 3. QR Lightbox Preview Modal
    const lightboxModalEl = document.getElementById('qrLightboxModal');
    const lightboxModal = lightboxModalEl ? new bootstrap.Modal(lightboxModalEl) : null;
    const lightboxCodeBadge = document.getElementById('lightboxCodeBadge');
    const lightboxLabelText = document.getElementById('lightboxLabelText');
    const lightboxSvgImg = document.getElementById('lightboxSvgImg');
    const lightboxEncodedInput = document.getElementById('lightboxEncodedInput');
    const lightboxTargetText = document.getElementById('lightboxTargetText');
    const lightboxSvgDownload = document.getElementById('lightboxSvgDownload');
    const lightboxPngDownload = document.getElementById('lightboxPngDownload');
    const lightboxPdfDownload = document.getElementById('lightboxPdfDownload');
    const lightboxCopyEncodedBtn = document.getElementById('lightboxCopyEncodedBtn');

    document.querySelectorAll('.qr-lightbox-trigger').forEach(trigger => {
        trigger.addEventListener('click', function () {
            if (!lightboxModal) return;
            lightboxCodeBadge.textContent = this.getAttribute('data-code') || '';
            lightboxLabelText.textContent = this.getAttribute('data-label') || '';
            lightboxSvgImg.src = this.getAttribute('data-svg-url') || '';
            lightboxEncodedInput.value = this.getAttribute('data-encoded') || '';
            lightboxTargetText.textContent = this.getAttribute('data-target') || '';
            lightboxSvgDownload.href = this.getAttribute('data-svg-download') || '#';
            lightboxPngDownload.href = this.getAttribute('data-png-download') || '#';
            lightboxPdfDownload.href = this.getAttribute('data-pdf-download') || '#';
            lightboxModal.show();
        });
    });

    if (lightboxCopyEncodedBtn && lightboxEncodedInput) {
        lightboxCopyEncodedBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(lightboxEncodedInput.value).then(() => {
                showToast('Lien permanent copié !');
            });
        });
    }

    // 4. Multi-Select Bulk Action Bar Logic
    const selectAllCheckbox = document.getElementById('selectAllQrs');
    const rowCheckboxes = document.querySelectorAll('.qr-row-checkbox');
    const bulkActionBar = document.getElementById('dynBulkActionBar');
    const bulkSelectedCount = document.getElementById('bulkSelectedCount');
    const btnClearBulk = document.getElementById('btnClearBulkSelection');

    function getSelectedIds() {
        return Array.from(rowCheckboxes).filter(cb => cb.checked).map(cb => parseInt(cb.value, 10));
    }

    function updateBulkBarUI() {
        const selectedIds = getSelectedIds();
        rowCheckboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (row) {
                row.classList.toggle('row-selected', cb.checked);
            }
        });
        if (bulkSelectedCount) bulkSelectedCount.textContent = selectedIds.length;
        if (bulkActionBar) {
            bulkActionBar.classList.toggle('active', selectedIds.length > 0);
        }
        if (selectAllCheckbox && rowCheckboxes.length > 0) {
            selectAllCheckbox.checked = selectedIds.length === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedIds.length > 0 && selectedIds.length < rowCheckboxes.length;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(cb => { cb.checked = selectAllCheckbox.checked; });
            updateBulkBarUI();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBarUI);
    });

    if (btnClearBulk) {
        btnClearBulk.addEventListener('click', function () {
            rowCheckboxes.forEach(cb => { cb.checked = false; });
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkBarUI();
        });
    }

    window.submitBulkAction = function (action, extraPayload = {}) {
        const ids = getSelectedIds();
        if (ids.length === 0) return;

        fetch(`{{ route('admin.dynamic-qrs.bulk-action') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(Object.assign({ ids: ids, action: action }, extraPayload))
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => window.location.reload(), 650);
            } else {
                alert(data.message || 'Erreur lors de l\'action groupée.');
            }
        })
        .catch(() => {
            alert('Erreur réseau.');
        });
    };

    window.submitBulkTarget = function () {
        const url = document.getElementById('bulkTargetUrlInput')?.value.trim();
        if (!url) return alert('Veuillez saisir une URL valide.');
        submitBulkAction('update_target', { target_url: url });
    };

    window.submitBulkClientAssign = function () {
        const clientId = document.getElementById('bulkClientIdSelect')?.value || null;
        submitBulkAction('assign_client', { client_id: clientId });
    };

    window.confirmBulkDelete = function () {
        const ids = getSelectedIds();
        if (ids.length === 0) return;
        if (confirm(`Confirmez-vous la suppression définitive des ${ids.length} QR codes sélectionnés ?`)) {
            submitBulkAction('delete');
        }
    };

    // 5. Unified Delete Modal
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

    // 6. Copy to clipboard helpers (Code & Encoded URL)
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const code = this.getAttribute('data-code');
            if (!code) return;

            navigator.clipboard.writeText(code).then(() => {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-check2 text-success';
                    setTimeout(() => { icon.className = 'bi bi-clipboard'; }, 1500);
                }
                showToast(`Code copié : ${code}`);
            });
        });
    });

    document.querySelectorAll('.copy-url-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            if (!url) return;

            navigator.clipboard.writeText(url).then(() => {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-check2 text-success';
                    setTimeout(() => { icon.className = 'bi bi-link-45deg'; }, 1500);
                }
                showToast(`Lien permanent copié : ${url}`);
            });
        });
    });

    // 7. Segmented View Switcher (Table vs Grid)
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

    // 8. Per-Page Handler (Bottom & Top Select Sync)
    window.changeDynPerPage = function (val) {
        const hiddenInput = document.getElementById('dynPerPageHiddenInput');
        const filterForm = document.getElementById('dynFilterForm');
        if (hiddenInput && filterForm) {
            hiddenInput.value = val;
            filterForm.submit();
        } else {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    };
});
</script>
@endpush
@endsection
