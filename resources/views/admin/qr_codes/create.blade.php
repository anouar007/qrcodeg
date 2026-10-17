@extends('layouts.admin')

@section('title', 'Generate QR Codes')
@section('page_title', 'Generate Dynamic QR Codes')
@section('page_subtitle', 'Create single or batch dynamic QR codes with unique public and access keys')

@section('content')
<form method="POST" action="{{ route('admin.qr-codes.store') }}" id="qrGenerateForm">
    @csrf

    <div class="row g-4">
        <!-- Configuration Columns -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Batch & Operational Settings</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Quantity to Generate <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-stack text-muted"></i></span>
                            <input type="number" name="quantity" id="quantityInput" class="form-control fw-bold" min="1" max="1000" value="{{ old('quantity', 1) }}" required>
                        </div>
                        <small class="text-muted">Generate 1 for single QR, or up to 1,000 unique records in batch.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Name / Prefix <span class="text-danger">*</span></label>
                        <input type="text" name="name_prefix" id="namePrefixInput" class="form-control" value="{{ old('name_prefix', 'QR Code') }}" required placeholder="e.g. Hotel Room or Table">
                        <small class="text-muted">Batch will auto-append sequence numbers (#001, #002...).</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Assign to Client (Optional)</label>
                        <select name="client_id" class="form-select">
                            <option value="">-- Leave Unassigned --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ (old('client_id') == $client->id || request('client_id') == $client->id) ? 'selected' : '' }}>
                                    {{ $client->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Initial Content Type <span class="text-danger">*</span></label>
                        <select name="type" id="typeSelector" class="form-select" required onchange="updateContentTypeFields();">
                            <option value="website" {{ old('type', 'website') === 'website' ? 'selected' : '' }}>Website URL Redirect</option>
                            <option value="contact" {{ old('type') === 'contact' ? 'selected' : '' }}>Digital Contact (vCard)</option>
                            <option value="whatsapp" {{ old('type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp Trigger</option>
                            <option value="wifi" {{ old('type') === 'wifi' ? 'selected' : '' }}>WiFi Fast Connect</option>
                            <option value="business" {{ old('type') === 'business' ? 'selected' : '' }}>Business Profile</option>
                            <option value="product" {{ old('type') === 'product' ? 'selected' : '' }}>Product Showcase</option>
                            <option value="social" {{ old('type') === 'social' ? 'selected' : '' }}>Social Media Hub</option>
                            <option value="custom_landing" {{ old('type') === 'custom_landing' ? 'selected' : '' }}>Custom Landing Page</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Internal Description / Memo</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="e.g. Generated for Grand Ballroom summer conference...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Content Details Container -->
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Initial Content Defaults</h5>

                <!-- 1. Website Fields -->
                <div class="type-section" id="section_website">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Destination URL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="url" class="form-control" placeholder="https://example.com" value="{{ old('url', 'https://example.com') }}">
                        </div>
                    </div>
                </div>

                <!-- 2. Contact Fields -->
                <div class="type-section d-none" id="section_contact">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Full Name</label>
                            <input type="text" name="contact_name" class="form-control" placeholder="Jane Doe">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+1 555 123 4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="jane@example.com">
                        </div>
                    </div>
                </div>

                <!-- 3. WhatsApp Fields -->
                <div class="type-section d-none" id="section_whatsapp">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">WhatsApp Number (with country code)</label>
                            <input type="text" name="whatsapp_phone" class="form-control" placeholder="e.g. 15550198273">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Default Greeting Message</label>
                            <input type="text" name="whatsapp_message" class="form-control" placeholder="Hello! I scanned your QR code.">
                        </div>
                    </div>
                </div>

                <!-- 4. WiFi Fields -->
                <div class="type-section d-none" id="section_wifi">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Network SSID</label>
                            <input type="text" name="wifi_ssid" class="form-control" placeholder="Guest_WiFi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">WiFi Password</label>
                            <input type="text" name="wifi_password" class="form-control" placeholder="Passcode123">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Design Styling & Customization -->
            <div class="card card-custom p-4 bg-white">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Visual QR Styling & Customization</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Foreground Color</label>
                        <div class="input-group">
                            <input type="color" name="foreground_color" id="fgColorInput" class="form-control form-control-color" value="#1e3a8a" onchange="triggerLivePreview();">
                            <input type="text" class="form-control font-mono" id="fgColorText" value="#1e3a8a" onchange="syncColorInput('fg');">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Background Color</label>
                        <div class="input-group">
                            <input type="color" name="background_color" id="bgColorInput" class="form-control form-control-color" value="#ffffff" onchange="triggerLivePreview();">
                            <input type="text" class="form-control font-mono" id="bgColorText" value="#ffffff" onchange="syncColorInput('bg');">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Dot Pattern Style</label>
                        <select name="dot_style" id="dotStyleInput" class="form-select" onchange="triggerLivePreview();">
                            <option value="square">Standard Square</option>
                            <option value="dots">Circular Dots</option>
                            <option value="rounded">Smooth Rounded</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Corner Eye Style</label>
                        <select name="corner_style" class="form-select">
                            <option value="square">Sharp Square</option>
                            <option value="rounded">Soft Rounded</option>
                            <option value="extra-rounded">Pill / Extra-Rounded</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Card Frame Style</label>
                        <select name="frame_style" id="frameStyleInput" class="form-select" onchange="triggerLivePreview();">
                            <option value="none">None (Clean QR)</option>
                            <option value="badge" selected>Framed Banner (SCAN ME)</option>
                            <option value="bottom-bar">Bottom Bar</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Frame Text</label>
                        <input type="text" name="frame_text" id="frameTextInput" class="form-control" value="SCAN ME" oninput="triggerLivePreview();">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Error Correction Level</label>
                        <select name="error_correction" class="form-select">
                            <option value="L">L (7% Recovery)</option>
                            <option value="M" selected>M (15% Recovery - Recommended)</option>
                            <option value="Q">Q (25% Recovery)</option>
                            <option value="H">H (30% Recovery - High)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Render Size (px)</label>
                        <input type="number" name="qr_size" class="form-control" value="300" min="150" max="1000">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Preview Simulator -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 bg-white sticky-top" style="top: 90px;">
                <div class="text-center mb-3">
                    <span class="badge bg-primary-subtle text-primary font-mono mb-2">LIVE DYNAMIC PREVIEW</span>
                    <h6 class="fw-bold mb-0">Visual Card Simulator</h6>
                </div>

                <div class="p-3 bg-light rounded-4 text-center border my-2" style="min-height: 310px; display: flex; align-items: center; justify-content: center;" id="previewContainer">
                    <div id="liveSvgTarget">
                        <i class="bi bi-qr-code text-primary" style="font-size: 150px;"></i>
                    </div>
                </div>

                <div class="small text-muted p-2 text-center">
                    <i class="bi bi-info-circle me-1"></i> Changes reflect automatically. Destination content can be edited at any time after generation.
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-3 rounded-pill fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Generate QR Code(s) Now
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function updateContentTypeFields() {
    const type = document.getElementById('typeSelector').value;
    document.querySelectorAll('.type-section').forEach(el => el.classList.add('d-none'));
    const target = document.getElementById('section_' + type);
    if (target) target.classList.remove('d-none');
}

function syncColorInput(type) {
    if (type === 'fg') {
        document.getElementById('fgColorInput').value = document.getElementById('fgColorText').value;
    } else {
        document.getElementById('bgColorInput').value = document.getElementById('bgColorText').value;
    }
    triggerLivePreview();
}

function triggerLivePreview() {
    const fg = document.getElementById('fgColorInput').value;
    const bg = document.getElementById('bgColorInput').value;
    const frame = document.getElementById('frameStyleInput').value;
    const text = document.getElementById('frameTextInput').value;
    const dot = document.getElementById('dotStyleInput').value;
    const name = document.getElementById('namePrefixInput').value;

    document.getElementById('fgColorText').value = fg;
    document.getElementById('bgColorText').value = bg;

    fetch('{{ route('admin.qr-codes.preview-live') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            foreground_color: fg,
            background_color: bg,
            frame_style: frame,
            frame_text: text,
            dot_style: dot,
            name: name,
            qr_size: 240
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.svg) {
            document.getElementById('liveSvgTarget').innerHTML = data.svg;
        }
    })
    .catch(() => {});
}

document.addEventListener('DOMContentLoaded', () => {
    triggerLivePreview();
});
</script>
@endpush
