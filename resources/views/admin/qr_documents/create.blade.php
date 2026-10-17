@extends('layouts.admin')

@section('title', 'Generate Printable QR Document')
@section('page_title', 'Build Printable QR Document')
@section('page_subtitle', 'Configure multi-grid paper layout for cutting, framing, or distribution')

@section('content')
<form method="POST" action="{{ route('admin.qr-documents.store') }}">
    @csrf

    <div class="row g-4">
        <!-- Configuration Column -->
        <div class="col-lg-5">
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Sheet Specifications</h5>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Document Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', 'QR Print Sheet - ' . date('Y-m-d')) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Target Client (Optional Filter)</label>
                        <select name="client_id" class="form-select" onchange="window.location.href = '{{ route('admin.qr-documents.create') }}?client_id=' + this.value;">
                            <option value="">-- All Clients / General Inventory --</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ $selectedClientId == $c->id ? 'selected' : '' }}>
                                    {{ $c->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Paper Standard</label>
                        <select name="paper_size" class="form-select" required>
                            <option value="a4" selected>A4 (210 &times; 297 mm)</option>
                            <option value="a5">A5 (148 &times; 210 mm)</option>
                            <option value="letter">US Letter (8.5 &times; 11 in)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">QR Size (mm)</label>
                        <input type="number" name="qr_size_mm" class="form-control" value="45" min="20" max="100">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Columns per Page</label>
                        <select name="columns" class="form-select" required>
                            <option value="2" selected>2 Columns (Standard Cards)</option>
                            <option value="3">3 Columns (High Density)</option>
                            <option value="4">4 Columns (Small Badges)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Rows per Page</label>
                        <select name="rows" class="form-select" required>
                            <option value="3">3 Rows</option>
                            <option value="4" selected>4 Rows (8 cards/page)</option>
                            <option value="5">5 Rows</option>
                            <option value="6">6 Rows</option>
                        </select>
                    </div>

                    <div class="col-12 mt-3 pt-3 border-top">
                        <label class="form-label small fw-semibold d-block mb-2">Display Elements on Cards</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_access_code" value="1" id="swAccess" checked>
                                <label class="form-check-label small" for="swAccess">Print Client Access Code on each card</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_public_code" value="1" id="swPublic" checked>
                                <label class="form-check-label small" for="swPublic">Print Public QR Identifier (e.g. QR-X7K9P2M4)</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_logo" value="1" id="swLogo" checked>
                                <label class="form-check-label small" for="swLogo">Include Venue / Client Logo</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-semibold">Custom Header / Cutting Instructions</label>
                        <input type="text" name="custom_text" class="form-control" placeholder="e.g. Scan to connect or view menu. Keep access code private.">
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Codes Selection Column -->
        <div class="col-lg-7">
            <div class="card card-custom p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0">Select QR Codes to Include</h5>
                        <small class="text-muted">Pick the physical cards to lay out on this printable document</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAllDocQrs();">
                        Select / Deselect All
                    </button>
                </div>

                @error('qr_code_ids')
                    <div class="alert alert-danger py-2 small">{{ $message }}</div>
                @enderror

                <div class="p-2 border rounded-3 bg-light" style="max-height: 480px; overflow-y: auto;">
                    <div class="d-flex flex-column gap-2">
                        @forelse($availableQrs as $qr)
                            <div class="p-2 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                                <div class="form-check d-flex align-items-center gap-2 mb-0">
                                    <input class="form-check-input doc-qr-item" type="checkbox" name="qr_code_ids[]" value="{{ $qr->id }}" id="doc_qr_{{ $qr->id }}" checked>
                                    <label class="form-check-label small" for="doc_qr_{{ $qr->id }}">
                                        <strong class="text-dark d-block">{{ $qr->name }}</strong>
                                        <span class="text-muted font-mono" style="font-size: 0.72rem;">{{ $qr->public_code }} &bull; Key: {{ $qr->access_code }}</span>
                                    </label>
                                </div>
                                <span class="badge bg-light text-dark border">{{ $qr->type }}</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                No QR codes available. Please create or assign QR codes first.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.qr-documents.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="bi bi-file-earmark-check me-1"></i> Generate Printable Sheet
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function toggleAllDocQrs() {
    const items = document.querySelectorAll('.doc-qr-item');
    const allChecked = Array.from(items).every(i => i.checked);
    items.forEach(i => i.checked = !allChecked);
}
</script>
@endpush
