@extends('layouts.app')

@section('title', 'Register Physical NFC Tag')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <!-- Breadcrumb & Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none">NFC Tags</a></li>
                <li class="breadcrumb-item active" aria-current="page">Register</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-broadcast text-primary"></i>
            Register Physical NFC Tag
        </h1>
        <p class="text-muted small mb-0">
            Configure label, optional hardware UID, and assign destination URL (or select an existing Digital Card or Dynamic QR target).
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.nfc-tags.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <!-- Label -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Tag Label / Description <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" 
                               value="{{ old('label') }}" placeholder="e.g. Executive PVC Metal Card, VIP Table 12" required>
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Hardware UID (Optional) -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Hardware UID (Optional)</label>
                        <input type="text" name="tag_uid" class="form-control font-mono @error('tag_uid') is-invalid @enderror" 
                               value="{{ old('tag_uid') }}" placeholder="e.g. 04:A1:B2:C3 or leave blank to auto-generate">
                        <div class="form-text small">Read from physical chip or leave empty for auto-generated identifier.</div>
                        @error('tag_uid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Client Association -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Assign to Client (Optional)</label>
                        <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                            <option value="">General (No Client Assigned)</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ old('client_id', request('client_id')) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} {{ $c->project_name ? "({$c->project_name})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

                    <!-- Preset Target Dropdown -->
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted">Select Target Preset (Digital Card or Dynamic QR)</label>
                        <select id="presetSelect" name="preset_target" class="form-select bg-light">
                            <option value="custom" selected>Custom Destination URL (Enter manually below)</option>
                            @if(isset($digitalCards) && $digitalCards->isNotEmpty())
                                <optgroup label="--- Digital Business Cards ---">
                                    @foreach($digitalCards as $card)
                                        <option value="card:{{ $card->id }}" data-url="{{ route('digital-cards.public.show', $card->slug) }}">
                                            {{ $card->title ?: $card->name }} (Slug: {{ $card->slug }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if(isset($dynamicQrs) && $dynamicQrs->isNotEmpty())
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

                    <!-- Destination URL -->
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted">Designated Destination URL <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                            <input type="text" id="destUrlInput" name="destination_url" class="form-control @error('destination_url') is-invalid @enderror" 
                                   value="{{ old('destination_url') }}" placeholder="https://yourdomain.com/landing" required>
                        </div>
                        <div class="form-text small">Tapping the physical NFC card will 302-redirect to this URL while logging telemetry.</div>
                        @error('destination_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted">Chip Notes / Physical Location (Optional)</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="e.g. NTAG215 card, given to sales director">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.nfc-tags.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Register NFC Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const preset = document.getElementById('presetSelect');
    const input = document.getElementById('destUrlInput');
    if (preset && input) {
        preset.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const url = selectedOpt.getAttribute('data-url');
            if (url) {
                input.value = url;
            }
        });
    }
});
</script>
@endpush
@endsection
