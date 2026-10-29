@extends('layouts.app')

@section('title', 'Edit ' . ($nfcTag->label ?: $nfcTag->name))

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <!-- Breadcrumb & Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.nfc-tags.index') }}" class="text-decoration-none">NFC Tags</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $nfcTag->tag_uid }}</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-pencil-square text-primary"></i>
            Edit NFC Tag Configuration
        </h1>
        <p class="text-muted small mb-0">
            Hardware UID <span class="font-mono fw-bold text-primary">{{ $nfcTag->tag_uid }}</span>. Destination URLs can be updated or swapped anytime.
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.nfc-tags.update', $nfcTag) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <!-- Label -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Tag Label / Description <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" 
                               value="{{ old('label', $nfcTag->label ?: $nfcTag->name) }}" required>
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Hardware UID -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Hardware UID</label>
                        <input type="text" name="tag_uid" class="form-control font-mono @error('tag_uid') is-invalid @enderror" 
                               value="{{ old('tag_uid', $nfcTag->tag_uid) }}">
                        <div class="form-text small">Hardware identifier read from physical NFC chip.</div>
                        @error('tag_uid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Client Association -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Assigned Client</label>
                        <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                            <option value="">General (No Client Assigned)</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ old('client_id', $nfcTag->client_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} {{ $c->project_name ? "({$c->project_name})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $nfcTag->status) === 'active' ? 'selected' : '' }}>Active (Routable on Tap)</option>
                            <option value="inactive" {{ old('status', $nfcTag->status) === 'inactive' ? 'selected' : '' }}>Inactive (Suspended)</option>
                            <option value="locked" {{ old('status', $nfcTag->status) === 'locked' ? 'selected' : '' }}>Locked</option>
                        </select>
                    </div>

                    <!-- Preset Target Dropdown -->
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted">Select Target Preset (Digital Card or Dynamic QR)</label>
                        <select id="editPresetSelect" name="preset_target" class="form-select bg-light">
                            <option value="custom" selected>Custom Destination URL</option>
                            @if(isset($digitalCards) && $digitalCards->isNotEmpty())
                                <optgroup label="--- Digital Business Cards ---">
                                    @foreach($digitalCards as $card)
                                        <option value="card:{{ $card->id }}" data-url="{{ route('digital-cards.public.show', $card->slug) }}"
                                            {{ $nfcTag->digital_card_id == $card->id ? 'selected' : '' }}>
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
                    </div>

                    <!-- Destination URL -->
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted">Designated Destination URL <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-link-45deg"></i></span>
                            <input type="text" id="editDestUrlInput" name="destination_url" class="form-control @error('destination_url') is-invalid @enderror" 
                                   value="{{ old('destination_url', $nfcTag->destination_url ?: $nfcTag->target_url) }}" required>
                        </div>
                        @error('destination_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted">Chip Notes / Location</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes', $nfcTag->notes) }}" placeholder="e.g. Card serial # or location">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.nfc-tags.show', $nfcTag) }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const preset = document.getElementById('editPresetSelect');
    const input = document.getElementById('editDestUrlInput');
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
