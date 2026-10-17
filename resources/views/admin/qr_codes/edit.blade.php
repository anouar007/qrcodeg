@extends('layouts.admin')

@section('title', 'Customize QR Code: ' . $qrCode->name)
@section('page_title', 'Customize QR Code')
@section('page_subtitle', 'Update destination content, styling, and frame options with live preview')

@section('content')
<form method="POST" action="{{ route('admin.qr-codes.update', $qrCode) }}" enctype="multipart/form-data" id="qrEditForm">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- LEFT: Configuration Panels -->
        <div class="col-lg-8">
            <!-- General Settings -->
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">General Information</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">QR Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="nameInput" class="form-control" value="{{ old('name', $qrCode->name) }}" required oninput="triggerLivePreview();">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Assigned Client</label>
                        <select name="client_id" class="form-select">
                            <option value="">-- Unassigned --</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ old('client_id', $qrCode->client_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Content Type <span class="text-danger">*</span></label>
                        <select name="type" id="typeSelector" class="form-select" required onchange="toggleContentSections();">
                            <option value="website" {{ old('type', $qrCode->type) === 'website' ? 'selected' : '' }}>Website URL Redirect</option>
                            <option value="contact" {{ old('type', $qrCode->type) === 'contact' ? 'selected' : '' }}>Contact / Digital vCard</option>
                            <option value="whatsapp" {{ old('type', $qrCode->type) === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="wifi" {{ old('type', $qrCode->type) === 'wifi' ? 'selected' : '' }}>WiFi Connect</option>
                            <option value="business" {{ old('type', $qrCode->type) === 'business' ? 'selected' : '' }}>Business Profile</option>
                            <option value="product" {{ old('type', $qrCode->type) === 'product' ? 'selected' : '' }}>Product Showcase</option>
                            <option value="social" {{ old('type', $qrCode->type) === 'social' ? 'selected' : '' }}>Social Media Hub</option>
                            <option value="custom_landing" {{ old('type', $qrCode->type) === 'custom_landing' ? 'selected' : '' }}>Custom Landing Page</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Operational Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $qrCode->status) === 'active' ? 'selected' : '' }}>Active (Normal)</option>
                            <option value="inactive" {{ old('status', $qrCode->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $qrCode->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="archived" {{ old('status', $qrCode->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Content Configuration (Dynamic Form Tabs) -->
            @php $content = $qrCode->content?->content_data ?? []; @endphp
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Dynamic Scan Content</h5>

                <!-- 1. Website -->
                <div class="content-box" id="box_website">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Target Web URL</label>
                        <input type="url" name="content_data[url]" class="form-control" value="{{ $content['url'] ?? '' }}" placeholder="https://example.com/promo">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="content_data[auto_redirect]" value="1" id="autoRedirectCheck"
                            {{ !isset($content['auto_redirect']) || $content['auto_redirect'] ? 'checked' : '' }}>
                        <label class="form-check-label small" for="autoRedirectCheck">Automatically redirect scanner to URL immediately</label>
                    </div>
                </div>

                <!-- 2. Contact -->
                <div class="content-box d-none" id="box_contact">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Full Name</label>
                            <input type="text" name="content_data[name]" class="form-control" value="{{ $content['name'] ?? '' }}" placeholder="Alexander Wright">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="content_data[phone]" class="form-control" value="{{ $content['phone'] ?? '' }}" placeholder="+1 555 123 4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="content_data[email]" class="form-control" value="{{ $content['email'] ?? '' }}" placeholder="alex@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Company</label>
                            <input type="text" name="content_data[company]" class="form-control" value="{{ $content['company'] ?? '' }}" placeholder="Acrobat Technologies">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Job Title</label>
                            <input type="text" name="content_data[job_title]" class="form-control" value="{{ $content['job_title'] ?? '' }}" placeholder="Chief Technology Officer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="content_data[website]" class="form-control" value="{{ $content['website'] ?? '' }}" placeholder="https://company.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Physical Address</label>
                            <input type="text" name="content_data[address]" class="form-control" value="{{ $content['address'] ?? '' }}" placeholder="123 Innovation Way, Suite 100">
                        </div>
                    </div>
                </div>

                <!-- 3. WhatsApp -->
                <div class="content-box d-none" id="box_whatsapp">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">WhatsApp Number</label>
                            <input type="text" name="content_data[phone]" class="form-control" value="{{ $content['phone'] ?? '' }}" placeholder="e.g. 15550198273">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pre-filled Message</label>
                            <input type="text" name="content_data[message]" class="form-control" value="{{ $content['message'] ?? 'Hello!' }}">
                        </div>
                    </div>
                </div>

                <!-- 4. WiFi -->
                <div class="content-box d-none" id="box_wifi">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Network SSID</label>
                            <input type="text" name="content_data[ssid]" class="form-control" value="{{ $content['ssid'] ?? '' }}" placeholder="Guest_WiFi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="text" name="content_data[password]" class="form-control" value="{{ $content['password'] ?? '' }}" placeholder="NetworkPasscode">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Encryption</label>
                            <select name="content_data[encryption]" class="form-select">
                                <option value="WPA" {{ ($content['encryption'] ?? '') === 'WPA' ? 'selected' : '' }}>WPA / WPA2 / WPA3</option>
                                <option value="WEP" {{ ($content['encryption'] ?? '') === 'WEP' ? 'selected' : '' }}>WEP</option>
                                <option value="nopass" {{ ($content['encryption'] ?? '') === 'nopass' ? 'selected' : '' }}>Open (No Password)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 5. Business -->
                <div class="content-box d-none" id="box_business">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Business Name</label>
                            <input type="text" name="content_data[business_name]" class="form-control" value="{{ $content['business_name'] ?? '' }}" placeholder="The Grand Hotel">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="content_data[phone]" class="form-control" value="{{ $content['phone'] ?? '' }}" placeholder="+1 555 777 8899">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Opening Hours</label>
                            <input type="text" name="content_data[opening_hours]" class="form-control" value="{{ $content['opening_hours'] ?? '9:00 AM - 10:00 PM' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="content_data[website]" class="form-control" value="{{ $content['website'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Full Address</label>
                            <input type="text" name="content_data[address]" class="form-control" value="{{ $content['address'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Business Description</label>
                            <textarea name="content_data[description]" class="form-control" rows="2">{{ $content['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 6. Custom Landing -->
                <div class="content-box d-none" id="box_custom_landing">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Landing Heading</label>
                        <input type="text" name="content_data[heading]" class="form-control" value="{{ $content['heading'] ?? '' }}" placeholder="Welcome to our experience">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Introductory Paragraph</label>
                        <textarea name="content_data[paragraph]" class="form-control" rows="3">{{ $content['paragraph'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Visual Design Settings -->
            @php $design = $qrCode->design; @endphp
            <div class="card card-custom p-4 bg-white">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Visual Styling & Geometry</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Foreground Color</label>
                        <div class="input-group">
                            <input type="color" name="foreground_color" id="fgColorInput" class="form-control form-control-color" value="{{ old('foreground_color', $design->foreground_color ?? '#000000') }}" onchange="triggerLivePreview();">
                            <input type="text" class="form-control font-mono" id="fgColorText" value="{{ old('foreground_color', $design->foreground_color ?? '#000000') }}" onchange="syncColorInput('fg');">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Background Color</label>
                        <div class="input-group">
                            <input type="color" name="background_color" id="bgColorInput" class="form-control form-control-color" value="{{ old('background_color', $design->background_color ?? '#ffffff') }}" onchange="triggerLivePreview();">
                            <input type="text" class="form-control font-mono" id="bgColorText" value="{{ old('background_color', $design->background_color ?? '#ffffff') }}" onchange="syncColorInput('bg');">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Dot Pattern Style</label>
                        <select name="dot_style" id="dotStyleInput" class="form-select" onchange="triggerLivePreview();">
                            <option value="square" {{ ($design->dot_style ?? '') === 'square' ? 'selected' : '' }}>Square</option>
                            <option value="dots" {{ ($design->dot_style ?? '') === 'dots' ? 'selected' : '' }}>Circular Dots</option>
                            <option value="rounded" {{ ($design->dot_style ?? '') === 'rounded' ? 'selected' : '' }}>Rounded</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Corner Style</label>
                        <select name="corner_style" class="form-select">
                            <option value="square" {{ ($design->corner_style ?? '') === 'square' ? 'selected' : '' }}>Square</option>
                            <option value="rounded" {{ ($design->corner_style ?? '') === 'rounded' ? 'selected' : '' }}>Rounded</option>
                            <option value="extra-rounded" {{ ($design->corner_style ?? '') === 'extra-rounded' ? 'selected' : '' }}>Extra Rounded</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Frame Style</label>
                        <select name="frame_style" id="frameStyleInput" class="form-select" onchange="triggerLivePreview();">
                            <option value="none" {{ ($design->frame_style ?? '') === 'none' ? 'selected' : '' }}>None</option>
                            <option value="badge" {{ ($design->frame_style ?? '') === 'badge' ? 'selected' : '' }}>Framed Banner (SCAN ME)</option>
                            <option value="bottom-bar" {{ ($design->frame_style ?? '') === 'bottom-bar' ? 'selected' : '' }}>Bottom Bar</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Frame Banner Text</label>
                        <input type="text" name="frame_text" id="frameTextInput" class="form-control" value="{{ old('frame_text', $design->frame_text ?? 'SCAN ME') }}" oninput="triggerLivePreview();">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Frame Color</label>
                        <input type="color" name="frame_color" id="frameColorInput" class="form-control form-control-color" value="{{ old('frame_color', $design->frame_color ?? '#1e3a8a') }}" onchange="triggerLivePreview();">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Error Correction Level</label>
                        <select name="error_correction" class="form-select">
                            <option value="L" {{ ($design->error_correction ?? '') === 'L' ? 'selected' : '' }}>L (7%)</option>
                            <option value="M" {{ ($design->error_correction ?? '') === 'M' ? 'selected' : '' }}>M (15%)</option>
                            <option value="Q" {{ ($design->error_correction ?? '') === 'Q' ? 'selected' : '' }}>Q (25%)</option>
                            <option value="H" {{ ($design->error_correction ?? '') === 'H' ? 'selected' : '' }}>H (30%)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">QR Dimension (px)</label>
                        <input type="number" name="qr_size" class="form-control" value="{{ old('qr_size', $design->qr_size ?? 300) }}" min="150" max="1000">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.qr-codes.show', $qrCode) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom px-4">Save All Changes</button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Live Vector Preview Simulator -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 bg-white sticky-top" style="top: 90px;">
                <div class="text-center mb-3">
                    <span class="badge bg-primary-subtle text-primary font-mono mb-2">LIVE DESIGN SIMULATOR</span>
                    <h6 class="fw-bold mb-0">Dynamic SVG Preview</h6>
                </div>

                <div class="p-3 bg-light rounded-4 text-center border my-2" style="min-height: 290px; display: flex; align-items: center; justify-content: center;" id="previewContainer">
                    <div id="liveSvgTarget">
                        {!! $svgPreview !!}
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 text-start small border mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Public Code:</span>
                        <code class="fw-bold text-dark">{{ $qrCode->public_code }}</code>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Access Key:</span>
                        <code class="fw-bold text-primary">{{ $qrCode->access_code }}</code>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-3 rounded-pill fw-bold">
                    <i class="bi bi-check2-circle me-1"></i> Update & Push Live
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function toggleContentSections() {
    const val = document.getElementById('typeSelector').value;
    document.querySelectorAll('.content-box').forEach(el => el.classList.add('d-none'));
    const box = document.getElementById('box_' + val);
    if (box) box.classList.remove('d-none');
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
    const frameColor = document.getElementById('frameColorInput').value;
    const name = document.getElementById('nameInput').value;

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
            frame_color: frameColor,
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
    toggleContentSections();
});
</script>
@endpush
