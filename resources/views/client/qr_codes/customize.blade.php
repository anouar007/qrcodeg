@extends('layouts.client')

@section('title', 'Customize: ' . $qrCode->name)
@section('page_title', 'Customize QR: ' . $qrCode->name)
@section('page_subtitle', 'Update what appears after scanning without modifying the physical QR code')

@section('content')
<form method="POST" action="{{ route('client.qr-codes.update', $qrCode) }}" enctype="multipart/form-data" id="clientCustomizeForm">
    @csrf
    @method('PUT')

    @php
        $content = $qrCode->content?->content_data ?? [];
        $design = $qrCode->design;
    @endphp

    <div class="row g-4">
        <!-- LEFT: Dynamic Content & Design Configuration -->
        <div class="col-lg-7">
            <!-- Basic Details -->
            <div class="card card-custom p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold mb-0">General Info & Type</h5>
                    <span class="badge-code">{{ $qrCode->public_code }}</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">QR Code Label / Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="clientQrNameInput" class="form-control" value="{{ old('name', $qrCode->name) }}" required oninput="updateLiveSimulator();">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Destination Content Type <span class="text-danger">*</span></label>
                        <select name="type" id="clientTypeSelector" class="form-select" required onchange="switchClientContentBox(); updateLiveSimulator();">
                            <option value="website" {{ old('type', $qrCode->type) === 'website' ? 'selected' : '' }}>Website URL Redirect</option>
                            <option value="contact" {{ old('type', $qrCode->type) === 'contact' ? 'selected' : '' }}>Contact Card (vCard)</option>
                            <option value="whatsapp" {{ old('type', $qrCode->type) === 'whatsapp' ? 'selected' : '' }}>WhatsApp Instant Chat</option>
                            <option value="wifi" {{ old('type', $qrCode->type) === 'wifi' ? 'selected' : '' }}>WiFi Network Connect</option>
                            <option value="business" {{ old('type', $qrCode->type) === 'business' ? 'selected' : '' }}>Business Profile</option>
                            <option value="product" {{ old('type', $qrCode->type) === 'product' ? 'selected' : '' }}>Product Showcase</option>
                            <option value="social" {{ old('type', $qrCode->type) === 'social' ? 'selected' : '' }}>Social Media Hub</option>
                            <option value="custom_landing" {{ old('type', $qrCode->type) === 'custom_landing' ? 'selected' : '' }}>Custom Landing Page</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Content Type Configuration -->
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Scan Content Configuration</h5>

                <!-- 1. Website -->
                <div class="client-content-panel" id="cbox_website">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Destination Website URL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="content_data[url]" id="inputWebUrl" class="form-control" value="{{ $content['url'] ?? '' }}" placeholder="https://yourdomain.com/menu" oninput="updateLiveSimulator();">
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="content_data[auto_redirect]" value="1" id="swClientAuto"
                            {{ !isset($content['auto_redirect']) || $content['auto_redirect'] ? 'checked' : '' }}>
                        <label class="form-check-label small" for="swClientAuto">Instantly redirect user upon scan</label>
                    </div>
                </div>

                <!-- 2. Contact -->
                <div class="client-content-panel d-none" id="cbox_contact">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Full Name</label>
                            <input type="text" name="content_data[name]" id="inputContactName" class="form-control" value="{{ $content['name'] ?? '' }}" placeholder="Alexander Wright" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone Number</label>
                            <input type="text" name="content_data[phone]" id="inputContactPhone" class="form-control" value="{{ $content['phone'] ?? '' }}" placeholder="+1 555 123 4567" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="content_data[email]" id="inputContactEmail" class="form-control" value="{{ $content['email'] ?? '' }}" placeholder="alex@company.com" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Company</label>
                            <input type="text" name="content_data[company]" id="inputContactCompany" class="form-control" value="{{ $content['company'] ?? '' }}" placeholder="Acrobat Inc." oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Job Title</label>
                            <input type="text" name="content_data[job_title]" id="inputContactTitle" class="form-control" value="{{ $content['job_title'] ?? '' }}" placeholder="General Manager" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="content_data[website]" class="form-control" value="{{ $content['website'] ?? '' }}" placeholder="https://example.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Physical Address</label>
                            <input type="text" name="content_data[address]" id="inputContactAddress" class="form-control" value="{{ $content['address'] ?? '' }}" placeholder="742 Evergreen Terrace">
                        </div>
                    </div>
                </div>

                <!-- 3. WhatsApp -->
                <div class="client-content-panel d-none" id="cbox_whatsapp">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">WhatsApp Number (e.g. 15550198273)</label>
                            <input type="text" name="content_data[phone]" id="inputWaPhone" class="form-control" value="{{ $content['phone'] ?? '' }}" placeholder="15550198273" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Default Chat Message</label>
                            <input type="text" name="content_data[message]" id="inputWaMsg" class="form-control" value="{{ $content['message'] ?? 'Hello!' }}" oninput="updateLiveSimulator();">
                        </div>
                    </div>
                </div>

                <!-- 4. WiFi -->
                <div class="client-content-panel d-none" id="cbox_wifi">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Network Name (SSID)</label>
                            <input type="text" name="content_data[ssid]" id="inputWifiSsid" class="form-control" value="{{ $content['ssid'] ?? 'Guest_WiFi' }}" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">WiFi Password</label>
                            <input type="text" name="content_data[password]" id="inputWifiPass" class="form-control" value="{{ $content['password'] ?? '' }}" placeholder="Password123" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Security Type</label>
                            <select name="content_data[encryption]" class="form-select">
                                <option value="WPA" {{ ($content['encryption'] ?? '') === 'WPA' ? 'selected' : '' }}>WPA/WPA2/WPA3</option>
                                <option value="WEP" {{ ($content['encryption'] ?? '') === 'WEP' ? 'selected' : '' }}>WEP</option>
                                <option value="nopass" {{ ($content['encryption'] ?? '') === 'nopass' ? 'selected' : '' }}>Open</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 5. Business -->
                <div class="client-content-panel d-none" id="cbox_business">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Business Name</label>
                            <input type="text" name="content_data[business_name]" id="inputBizName" class="form-control" value="{{ $content['business_name'] ?? '' }}" placeholder="My Business" oninput="updateLiveSimulator();">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Phone</label>
                            <input type="text" name="content_data[phone]" class="form-control" value="{{ $content['phone'] ?? '' }}" placeholder="+1 555 777 8899">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Hours of Operation</label>
                            <input type="text" name="content_data[opening_hours]" class="form-control" value="{{ $content['opening_hours'] ?? '9:00 AM - 6:00 PM' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="content_data[website]" class="form-control" value="{{ $content['website'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Address</label>
                            <input type="text" name="content_data[address]" class="form-control" value="{{ $content['address'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">About / Description</label>
                            <textarea name="content_data[description]" id="inputBizDesc" class="form-control" rows="2" oninput="updateLiveSimulator();">{{ $content['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 6. Custom Landing -->
                <div class="client-content-panel d-none" id="cbox_custom_landing">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Landing Heading</label>
                        <input type="text" name="content_data[heading]" id="inputLandingHeading" class="form-control" value="{{ $content['heading'] ?? '' }}" placeholder="Welcome to Our Page" oninput="updateLiveSimulator();">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Introductory Message</label>
                        <textarea name="content_data[paragraph]" id="inputLandingParagraph" class="form-control" rows="3" oninput="updateLiveSimulator();">{{ $content['paragraph'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Visual Design Settings -->
            <div class="card card-custom p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">QR Visual Design</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Foreground Color</label>
                        <div class="input-group">
                            <input type="color" name="foreground_color" id="clientFgColor" class="form-control form-control-color" value="{{ old('foreground_color', $design->foreground_color ?? '#1e3a8a') }}" onchange="triggerClientPreview();">
                            <input type="text" class="form-control font-mono" id="clientFgText" value="{{ old('foreground_color', $design->foreground_color ?? '#1e3a8a') }}" onchange="syncClientColor('fg');">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Background Color</label>
                        <div class="input-group">
                            <input type="color" name="background_color" id="clientBgColor" class="form-control form-control-color" value="{{ old('background_color', $design->background_color ?? '#ffffff') }}" onchange="triggerClientPreview();">
                            <input type="text" class="form-control font-mono" id="clientBgText" value="{{ old('background_color', $design->background_color ?? '#ffffff') }}" onchange="syncClientColor('bg');">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Dot Style</label>
                        <select name="dot_style" id="clientDotStyle" class="form-select" onchange="triggerClientPreview();">
                            <option value="square" {{ ($design->dot_style ?? '') === 'square' ? 'selected' : '' }}>Square</option>
                            <option value="dots" {{ ($design->dot_style ?? '') === 'dots' ? 'selected' : '' }}>Dots</option>
                            <option value="rounded" {{ ($design->dot_style ?? '') === 'rounded' ? 'selected' : '' }}>Rounded</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Frame Style</label>
                        <select name="frame_style" id="clientFrameStyle" class="form-select" onchange="triggerClientPreview();">
                            <option value="none" {{ ($design->frame_style ?? '') === 'none' ? 'selected' : '' }}>None</option>
                            <option value="badge" {{ ($design->frame_style ?? '') === 'badge' ? 'selected' : '' }}>Badge (SCAN ME)</option>
                            <option value="bottom-bar" {{ ($design->frame_style ?? '') === 'bottom-bar' ? 'selected' : '' }}>Bottom Bar</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Frame Text</label>
                        <input type="text" name="frame_text" id="clientFrameText" class="form-control" value="{{ old('frame_text', $design->frame_text ?? 'SCAN ME') }}" oninput="triggerClientPreview();">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Corner Style</label>
                        <select name="corner_style" class="form-select">
                            <option value="square" {{ ($design->corner_style ?? '') === 'square' ? 'selected' : '' }}>Square</option>
                            <option value="rounded" {{ ($design->corner_style ?? '') === 'rounded' ? 'selected' : '' }}>Rounded</option>
                            <option value="extra-rounded" {{ ($design->corner_style ?? '') === 'extra-rounded' ? 'selected' : '' }}>Extra Rounded</option>
                        </select>
                    </div>

                    <input type="hidden" name="frame_color" value="{{ $design->frame_color ?? '#1e3a8a' }}">
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('client.qr-codes.index') }}" class="btn btn-outline-secondary">Back to My QRs</a>
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Save & Publish Dynamic Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Live Smartphone & Vector Preview Simulator -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 85px;">
                <!-- Tab toggles for simulator -->
                <ul class="nav nav-pills nav-fill bg-white p-2 rounded-4 border shadow-sm mb-3" id="simTabs">
                    <li class="nav-item">
                        <button type="button" class="nav-link active fw-bold small py-2 rounded-pill" onclick="switchSimTab('phone')">
                            <i class="bi bi-phone me-1"></i> Mobile Scan Preview
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link fw-bold small py-2 rounded-pill" onclick="switchSimTab('qr')">
                            <i class="bi bi-qr-code me-1"></i> Physical QR Vector
                        </button>
                    </li>
                </ul>

                <!-- Smartphone Screen Simulator -->
                <div id="simPhoneView" class="phone-mockup">
                    <div class="phone-speaker"></div>
                    <div class="phone-screen" id="simPhoneContent">
                        <div class="text-center p-3 rounded-4 mb-3 text-white" id="simHeaderBanner" style="background-color: {{ $design->foreground_color ?? '#1e3a8a' }};">
                            <h6 class="fw-bold mb-0" id="simTitle">{{ $qrCode->name }}</h6>
                            <small class="text-white-50" style="font-size: 0.72rem;">{{ auth('client')->user()->company_name }}</small>
                        </div>

                        <div class="p-3 bg-light rounded-4 text-center border flex-grow-1" id="simBody">
                            <p class="small text-muted mb-3" id="simSubtitle">Scanners will view this updated content immediately.</p>
                            <div id="simDynamicDetails"></div>
                        </div>

                        <div class="text-center pt-2 text-muted" style="font-size: 0.65rem;">
                            Powered by QR Studio Dynamic Platform
                        </div>
                    </div>
                </div>

                <!-- QR Vector Preview Simulator -->
                <div id="simQrView" class="card card-custom p-4 bg-white text-center d-none">
                    <h6 class="fw-bold mb-3">Physical QR Code</h6>
                    <div class="p-3 bg-light rounded-4 border d-inline-block mx-auto mb-3" id="clientLiveSvgTarget">
                        {!! $svgPreview !!}
                    </div>
                    <div class="small text-muted font-mono">
                        {{ url('/q/' . $qrCode->public_code) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function switchClientContentBox() {
    const val = document.getElementById('clientTypeSelector').value;
    document.querySelectorAll('.client-content-panel').forEach(p => p.classList.add('d-none'));
    const target = document.getElementById('cbox_' + val);
    if (target) target.classList.remove('d-none');
}

function switchSimTab(tab) {
    const phone = document.getElementById('simPhoneView');
    const qr = document.getElementById('simQrView');
    const buttons = document.querySelectorAll('#simTabs .nav-link');

    if (tab === 'phone') {
        phone.classList.remove('d-none');
        qr.classList.add('d-none');
        buttons[0].classList.add('active');
        buttons[1].classList.remove('active');
    } else {
        phone.classList.add('d-none');
        qr.classList.remove('d-none');
        buttons[0].classList.remove('active');
        buttons[1].classList.add('active');
    }
}

function syncClientColor(type) {
    if (type === 'fg') {
        document.getElementById('clientFgColor').value = document.getElementById('clientFgText').value;
    } else {
        document.getElementById('clientBgColor').value = document.getElementById('clientBgText').value;
    }
    triggerClientPreview();
    updateLiveSimulator();
}

function updateLiveSimulator() {
    const title = document.getElementById('clientQrNameInput').value || 'My QR Code';
    const type = document.getElementById('clientTypeSelector').value;
    const fg = document.getElementById('clientFgColor').value;

    document.getElementById('simTitle').innerText = title;
    document.getElementById('simHeaderBanner').style.backgroundColor = fg;

    const details = document.getElementById('simDynamicDetails');
    details.innerHTML = '';

    if (type === 'website') {
        const url = document.getElementById('inputWebUrl')?.value || 'https://example.com';
        details.innerHTML = `<a href="#" class="btn btn-sm btn-primary w-100 rounded-pill py-2">Open ${url}</a>`;
    } else if (type === 'contact') {
        const name = document.getElementById('inputContactName')?.value || 'Contact';
        const phone = document.getElementById('inputContactPhone')?.value || '';
        details.innerHTML = `
            <h6 class="fw-bold mb-1">${name}</h6>
            <small class="text-muted d-block mb-3">${phone}</small>
            <div class="btn btn-sm btn-success w-100 rounded-pill mb-2"><i class="bi bi-person-plus"></i> Save to Contacts</div>
        `;
    } else if (type === 'wifi') {
        const ssid = document.getElementById('inputWifiSsid')?.value || 'Guest_WiFi';
        details.innerHTML = `
            <i class="bi bi-wifi fs-1 text-warning mb-2 d-block"></i>
            <strong>${ssid}</strong>
            <p class="small text-muted mt-2">Tap to connect automatically</p>
        `;
    } else if (type === 'whatsapp') {
        details.innerHTML = `
            <i class="bi bi-whatsapp fs-1 text-success mb-2 d-block"></i>
            <div class="btn btn-sm btn-success w-100 rounded-pill py-2">Start WhatsApp Chat</div>
        `;
    } else {
        details.innerHTML = `
            <div class="text-muted small">Dynamic mobile layout active for <strong>${type}</strong></div>
        `;
    }
}

function triggerClientPreview() {
    const fg = document.getElementById('clientFgColor').value;
    const bg = document.getElementById('clientBgColor').value;
    const frame = document.getElementById('clientFrameStyle').value;
    const text = document.getElementById('clientFrameText').value;
    const dot = document.getElementById('clientDotStyle').value;
    const name = document.getElementById('clientQrNameInput').value;

    document.getElementById('clientFgText').value = fg;
    document.getElementById('clientBgText').value = bg;

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
            qr_size: 200
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.svg) {
            document.getElementById('clientLiveSvgTarget').innerHTML = data.svg;
        }
    })
    .catch(() => {});
}

document.addEventListener('DOMContentLoaded', () => {
    switchClientContentBox();
    updateLiveSimulator();
});
</script>
@endpush
