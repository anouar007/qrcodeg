@extends('layouts.app')

@section('title', 'NFC Tag Management')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header Matching Screenshot -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="font-size: 1.65rem; letter-spacing: -0.02em;">Cartes NFC</h2>
            <p class="text-muted small mb-0" style="color: #64748b;">
                {{ $nfcTags->total() }} cartes au total
            </p>
        </div>
        <div>
            <button type="button" class="btn text-white fw-medium d-flex align-items-center gap-1.5 shadow-sm" style="background-color: #6320ee; border-radius: 8px; padding: 0.55rem 1.25rem;" data-bs-toggle="modal" data-bs-target="#createNfcModal">
                <i class="bi bi-plus-lg fs-6"></i>
                <span>Nouvelle carte</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar Matching Screenshot -->
    <div class="card bg-white border mb-4 shadow-xs" style="border-radius: 14px; border-color: #e2e8f0;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.nfc-tags.index') }}" class="d-flex flex-wrap align-items-center gap-2">
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
                        <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Verrouillée</option>
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
                        <option value="date_desc" {{ request('sort') === 'date_desc' || !request('sort') ? 'selected' : '' }}>Trier par date</option>
                        <option value="scans_desc" {{ request('sort') === 'scans_desc' ? 'selected' : '' }}>Trier par scans</option>
                        <option value="code_asc" {{ request('sort') === 'code_asc' ? 'selected' : '' }}>Trier par code</option>
                    </select>
                </div>

                <!-- Export CSV Button -->
                <a href="{{ route('admin.nfc-tags.export') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1.5" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem; color: #334155;">
                    <i class="bi bi-download"></i>
                    <span>Export CSV</span>
                </a>

                <!-- Import CSV Button -->
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1.5" style="border-radius: 10px; border-color: #e2e8f0; font-size: 0.88rem; color: #334155;" data-bs-toggle="modal" data-bs-target="#createNfcModal">
                    <i class="bi bi-upload"></i>
                    <span>Import CSV</span>
                </button>
            </form>
        </div>
    </div>

    <!-- NFC Tags Table Card Matching Screenshot -->
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
                        @forelse($nfcTags as $tag)
                            @php
                                $dest = $tag->destination_url ?: $tag->target_url;
                                $codeDisplay = $tag->tag_uid ?: ('YN33-' . str_pad($tag->id, 6, '0', STR_PAD_LEFT));
                            @endphp
                            <tr style="height: 60px;">
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark" style="font-size: 0.92rem; letter-spacing: 0.02em;">
                                        {{ $codeDisplay }}
                                    </div>
                                    @if($tag->label && $tag->label !== $codeDisplay)
                                        <div class="text-muted text-truncate" style="font-size: 0.78rem; max-width: 170px;">{{ $tag->label }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($tag->status === 'active')
                                        <span class="badge" style="background-color: #dcfce7; color: #16a34a; font-weight: 500; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                            Active
                                        </span>
                                    @elseif($tag->status === 'locked')
                                        <span class="badge" style="background-color: #fee2e2; color: #dc2626; font-weight: 500; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                            Locked
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #f1f5f9; color: #64748b; font-weight: 500; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($tag->client)
                                        <span class="text-dark small fw-medium">{{ $tag->client->name }}</span>
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
                                        {{ number_format($tag->scans_count ?: $tag->tap_count ?: 0) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <form method="POST" action="{{ route('admin.nfc-tags.destroy', $tag) }}" class="d-inline" onsubmit="return confirm('Supprimer cette carte NFC ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-muted border-0" title="Supprimer">
                                            <i class="bi bi-trash fs-6" style="color: #94a3b8;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="bi bi-credit-card fs-1 text-muted opacity-50 d-block mb-2"></i>
                                        <div class="fw-semibold text-dark">Aucune carte NFC enregistrée</div>
                                        <small class="text-muted">Cliquez sur « Nouvelle carte » pour en ajouter une.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($nfcTags->hasPages())
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Affichage de {{ $nfcTags->firstItem() }} à {{ $nfcTags->lastItem() }} sur {{ $nfcTags->total() }}</span>
                    {{ $nfcTags->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 1. CREATE NFC TAG MODAL                                                   -->
<!-- ========================================================================= -->
<div class="modal fade" id="createNfcModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-broadcast text-primary"></i>
                    Register Physical NFC Tag
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.nfc-tags.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Label -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Tag Label / Description <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control" placeholder="e.g. VIP Metal Card - Karim, Bar Table #3" required>
                        </div>

                        <!-- Hardware UID (Optional) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Hardware UID (Optional)</label>
                            <input type="text" name="tag_uid" class="form-control font-mono" placeholder="e.g. 04:A1:B2:C3 or leave empty to auto-generate">
                            <div class="form-text small">Read from physical chip or leave empty for auto-generated UID.</div>
                        </div>

                        <!-- Client Association -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Assign to Client</label>
                            <select name="client_id" class="form-select">
                                <option value="">General (No Client Assigned)</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} {{ $c->project_name ? "({$c->project_name})" : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Initial Status</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Active (Routable on Tap)</option>
                                <option value="inactive">Inactive (Suspended)</option>
                                <option value="locked">Locked</option>
                            </select>
                        </div>

                        <!-- Preset Destination Dropdown -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Select Target Preset (Digital Card or Dynamic QR)</label>
                            <select id="createPresetSelect" name="preset_target" class="form-select bg-light">
                                <option value="custom" selected>Custom Destination URL (Enter manually below)</option>
                                @if($digitalCards->isNotEmpty())
                                    <optgroup label="--- Digital Business Cards ---">
                                        @foreach($digitalCards as $card)
                                            <option value="card:{{ $card->id }}" data-url="{{ route('digital-cards.public.show', $card->slug) }}">
                                                {{ $card->title ?: $card->name }} (Slug: {{ $card->slug }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if($dynamicQrs->isNotEmpty())
                                    <optgroup label="--- Dynamic QR Codes (VP-) ---">
                                        @foreach($dynamicQrs as $dqr)
                                            <option value="qr:{{ $dqr->id }}" data-url="{{ route('qr.redirect', ['code' => $dqr->code]) }}">
                                                {{ $dqr->code }} - {{ $dqr->label }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            <div class="form-text small">Select an existing card or dynamic QR code to automatically link the URL.</div>
                        </div>

                        <!-- Destination URL Input -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Designated Destination URL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                                <input type="text" id="createDestUrlInput" name="destination_url" class="form-control" placeholder="https://yourdomain.com/campaign-landing" required>
                            </div>
                            <div class="form-text small">Smartphone taps will instantly 302-redirect to this URL. Telemetry is logged on every tap.</div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Chip Notes / Physical Location (Optional)</label>
                            <input type="text" name="notes" class="form-control" placeholder="e.g. NTAG216 chip, embedded in acrylic stand on main desk">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Register NFC Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. EDIT NFC TAG MODAL                                                     -->
<!-- ========================================================================= -->
<div class="modal fade" id="editNfcModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary"></i>
                    Edit NFC Tag Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editNfcForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Label -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Tag Label / Description <span class="text-danger">*</span></label>
                            <input type="text" id="editLabelInput" name="label" class="form-control" required>
                        </div>

                        <!-- Hardware UID -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Hardware UID</label>
                            <input type="text" id="editUidInput" name="tag_uid" class="form-control font-mono">
                        </div>

                        <!-- Client Association -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Assigned Client</label>
                            <select id="editClientSelect" name="client_id" class="form-select">
                                <option value="">General (No Client Assigned)</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} {{ $c->project_name ? "({$c->project_name})" : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Status</label>
                            <select id="editStatusSelect" name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="locked">Locked</option>
                            </select>
                        </div>

                        <!-- Preset Dropdown -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Select Target Preset (Digital Card or Dynamic QR)</label>
                            <select id="editPresetSelect" name="preset_target" class="form-select bg-light">
                                <option value="custom">Custom Destination URL</option>
                                @if($digitalCards->isNotEmpty())
                                    <optgroup label="--- Digital Business Cards ---">
                                        @foreach($digitalCards as $card)
                                            <option value="card:{{ $card->id }}" data-url="{{ route('digital-cards.public.show', $card->slug) }}">
                                                {{ $card->title ?: $card->name }} (Slug: {{ $card->slug }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if($dynamicQrs->isNotEmpty())
                                    <optgroup label="--- Dynamic QR Codes (VP-) ---">
                                        @foreach($dynamicQrs as $dqr)
                                            <option value="qr:{{ $dqr->id }}" data-url="{{ route('qr.redirect', ['code' => $dqr->code]) }}">
                                                {{ $dqr->code }} - {{ $dqr->label }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <!-- Destination URL Input -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Designated Destination URL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                                <input type="text" id="editDestUrlInput" name="destination_url" class="form-control" required>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Chip Notes</label>
                            <input type="text" id="editNotesInput" name="notes" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Update Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. QUICK SWAP TARGET DESTINATION MODAL                                    -->
<!-- ========================================================================= -->
<div class="modal fade" id="swapTargetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-repeat text-warning"></i>
                    Quick Swap Target Destination
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="swapTargetForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>
                            Swapping target updates instantly when campaigns change. The physical NFC chip <strong id="swapModalUidPill" class="font-mono"></strong> remains untouched.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Select Target Preset</label>
                        <select id="swapPresetSelect" name="preset_target" class="form-select bg-light">
                            <option value="custom" selected>Custom Destination URL</option>
                            @if($digitalCards->isNotEmpty())
                                <optgroup label="--- Digital Business Cards ---">
                                    @foreach($digitalCards as $card)
                                        <option value="card:{{ $card->id }}" data-url="{{ route('digital-cards.public.show', $card->slug) }}">
                                            {{ $card->title ?: $card->name }} (Slug: {{ $card->slug }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($dynamicQrs->isNotEmpty())
                                <optgroup label="--- Dynamic QR Codes (VP-) ---">
                                    @foreach($dynamicQrs as $dqr)
                                        <option value="qr:{{ $dqr->id }}" data-url="{{ route('qr.redirect', ['code' => $dqr->code]) }}">
                                            {{ $dqr->code }} - {{ $dqr->label }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Target Destination URL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-globe"></i></span>
                            <input type="text" id="swapTargetUrlInput" name="destination_url" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Swap Destination Instantly
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Preset Dropdown to Input auto-fill (Create modal)
    const createPreset = document.getElementById('createPresetSelect');
    const createInput = document.getElementById('createDestUrlInput');
    if (createPreset && createInput) {
        createPreset.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const url = selectedOpt.getAttribute('data-url');
            if (url) {
                createInput.value = url;
            }
        });
    }

    // 2. Preset Dropdown to Input auto-fill (Edit modal)
    const editPreset = document.getElementById('editPresetSelect');
    const editInput = document.getElementById('editDestUrlInput');
    if (editPreset && editInput) {
        editPreset.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const url = selectedOpt.getAttribute('data-url');
            if (url) {
                editInput.value = url;
            }
        });
    }

    // 3. Preset Dropdown to Input auto-fill (Swap modal)
    const swapPreset = document.getElementById('swapPresetSelect');
    const swapInput = document.getElementById('swapTargetUrlInput');
    if (swapPreset && swapInput) {
        swapPreset.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const url = selectedOpt.getAttribute('data-url');
            if (url) {
                swapInput.value = url;
            }
        });
    }

    // 4. Quick Swap Target Modal Trigger
    const swapModal = new bootstrap.Modal(document.getElementById('swapTargetModal'));
    const swapForm = document.getElementById('swapTargetForm');
    const swapUidPill = document.getElementById('swapModalUidPill');

    document.querySelectorAll('.swap-target-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const uid = this.getAttribute('data-uid');
            const target = this.getAttribute('data-target');

            swapForm.action = `/admin/nfc-tags/${id}/destination`;
            swapInput.value = target || '';
            swapUidPill.textContent = uid || `ID: ${id}`;
            swapPreset.value = 'custom';
            swapModal.show();
        });
    });

    // 5. Edit Modal Trigger
    const editModal = new bootstrap.Modal(document.getElementById('editNfcModal'));
    const editForm = document.getElementById('editNfcForm');
    const editLabel = document.getElementById('editLabelInput');
    const editUid = document.getElementById('editUidInput');
    const editClient = document.getElementById('editClientSelect');
    const editStatus = document.getElementById('editStatusSelect');
    const editNotes = document.getElementById('editNotesInput');

    document.querySelectorAll('.edit-modal-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const label = this.getAttribute('data-label');
            const uid = this.getAttribute('data-uid');
            const target = this.getAttribute('data-target');
            const client = this.getAttribute('data-client');
            const status = this.getAttribute('data-status');
            const notes = this.getAttribute('data-notes');

            editForm.action = `/admin/nfc-tags/${id}`;
            editLabel.value = label || '';
            editUid.value = uid || '';
            editInput.value = target || '';
            editClient.value = client || '';
            editStatus.value = status || 'active';
            editNotes.value = notes || '';
            editPreset.value = 'custom';

            editModal.show();
        });
    });

    // 6. Copy Tap URL
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const icon = this.querySelector('i');
                icon.className = 'bi bi-check2 text-success';
                setTimeout(() => {
                    icon.className = 'bi bi-clipboard';
                }, 1500);
            });
        });
    });
});
</script>
@endpush
@endsection
