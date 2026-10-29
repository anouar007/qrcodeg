@extends('layouts.app')

@section('title', ($nfcTag->label ?: $nfcTag->name) . ' - NFC Tag Details')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none">NFC Tags</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $nfcTag->tag_uid }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <span class="font-mono text-primary">{{ $nfcTag->tag_uid }}</span>
                <span class="text-muted fw-normal">| {{ $nfcTag->label ?: $nfcTag->name }}</span>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.nfc-tags.edit', $nfcTag) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-1"></i> Edit Tag
            </a>
            <a href="{{ route('nfc.redirect', ['id_or_uid' => $nfcTag->tag_uid ?: $nfcTag->id]) }}" target="_blank" class="btn btn-primary shadow-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Test Live Tap (302 Redirect)
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: Chip Info & Instant Target Swapper -->
        <div class="col-12 col-lg-5">
            <!-- Chip Visual Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 text-center">
                <div class="card-body p-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-4 bg-primary-subtle text-primary rounded-circle mb-3 shadow-sm" style="width: 90px; height: 90px;">
                        <i class="bi bi-broadcast fs-1"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $nfcTag->label ?: $nfcTag->name }}</h5>
                    <div class="mb-3">
                        <span class="badge bg-dark-subtle text-dark border font-mono fs-6 px-3 py-1">
                            UID: {{ $nfcTag->tag_uid }}
                        </span>
                    </div>

                    <div class="small text-muted mb-3 text-start bg-light border rounded p-3">
                        <div class="fw-semibold text-dark mb-1">Physical Programming Tap URL:</div>
                        <div class="font-mono text-break text-primary small">
                            {{ route('nfc.redirect', ['id_or_uid' => $nfcTag->tag_uid ?: $nfcTag->id]) }}
                        </div>
                        <div class="mt-2 text-muted" style="font-size: 0.72rem;">
                            Encode this permanent URL into the physical chip using NFC Tools or smartphone writer.
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <form method="POST" action="{{ route('admin.nfc-tags.toggle-status', $nfcTag) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $nfcTag->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                <i class="bi {{ $nfcTag->status === 'active' ? 'bi-pause-circle' : 'bi-play-circle' }} me-1"></i>
                                {{ $nfcTag->status === 'active' ? 'Suspend Chip' : 'Activate Chip' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instant Target Swapper Form -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        Target Destination Swapper
                    </h5>
                    <p class="text-muted small mb-0">Swap the campaign destination instantly. The physical NFC card remains unchanged.</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.nfc-tags.destination', $nfcTag) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Select Target Preset</label>
                            <select id="showPresetSelect" name="preset_target" class="form-select bg-light">
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
                            <label class="form-label fw-semibold small text-muted">Designated Destination URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-globe"></i></span>
                                <input type="text" id="showDestUrlInput" name="destination_url" class="form-control" 
                                       value="{{ old('destination_url', $nfcTag->destination_url ?: $nfcTag->target_url) }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Swap Destination Instantly
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Metrics, Client & Telemetry Scan Log Stream -->
        <div class="col-12 col-lg-7">
            <!-- Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-sm-4">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Total Taps / Scans</div>
                        <div class="fs-4 fw-bold font-mono text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-broadcast text-success"></i>
                            {{ number_format($nfcTag->scans_count ?: $nfcTag->tap_count) }}
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-4">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Status</div>
                        <div class="mt-1">
                            <x-badge :status="$nfcTag->status" />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Last Interaction</div>
                        <div class="small fw-bold text-dark mt-1 font-mono">
                            {{ $nfcTag->last_tapped_at ? $nfcTag->last_tapped_at->diffForHumans() : 'Never' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Info Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-building text-primary"></i>
                        Client Association
                    </h6>
                    @if($nfcTag->client)
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $nfcTag->client->name }}</h6>
                                <div class="text-muted small">
                                    Project: {{ $nfcTag->client->project_name ?: 'Standard' }} &bull; Phone: {{ $nfcTag->client->phone ?: 'N/A' }}
                                </div>
                            </div>
                            <a href="{{ route('admin.clients.show', $nfcTag->client) }}" class="btn btn-sm btn-outline-primary">
                                View Client Profile
                            </a>
                        </div>
                    @else
                        <div class="text-muted small">
                            Not assigned to a client. Available in inventory.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Polymorphic ScanLog Telemetry Stream -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-1">NFC Tap Telemetry Stream</h5>
                        <p class="text-muted small mb-0">Polymorphic ScanLog events recorded on every physical chip detection.</p>
                    </div>
                    <span class="badge bg-light text-secondary border font-mono">
                        {{ $nfcTag->scanLogs->count() }} Recorded Events
                    </span>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">IP Address</th>
                                    <th>User Agent / Device</th>
                                    <th class="pe-4 text-end">Tapped At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y small">
                                @forelse($nfcTag->scanLogs as $log)
                                    <tr>
                                        <td class="ps-4 font-mono fw-semibold text-primary">
                                            {{ $log->ip_address ?: '127.0.0.1' }}
                                        </td>
                                        <td>
                                            <div class="text-truncate text-muted" style="max-width: 320px;" title="{{ $log->user_agent }}">
                                                {{ $log->user_agent ?: 'NFC Mobile Browser' }}
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end font-mono text-muted">
                                            {{ $log->scanned_at ? $log->scanned_at->format('M d, Y H:i:s') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="bi bi-broadcast fs-4 d-block mb-1"></i>
                                            No tap telemetry recorded yet for this NFC chip.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const showPreset = document.getElementById('showPresetSelect');
    const showInput = document.getElementById('showDestUrlInput');
    if (showPreset && showInput) {
        showPreset.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const url = selectedOpt.getAttribute('data-url');
            if (url) {
                showInput.value = url;
            }
        });
    }
});
</script>
@endpush
@endsection
