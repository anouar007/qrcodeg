<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR — {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #ffffff;
            color: #0f172a;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .minimal-topbar {
            display: flex;
            justify-content: flex-end;
            padding: 1rem 1.25rem;
        }
        .minimal-center {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .minimal-box {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        #qrReaderBox {
            width: 100%;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #0f172a;
        }
    </style>
</head>
<body>
    <div class="minimal-topbar">
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
        </form>
    </div>

    <div class="minimal-center">
        <div class="minimal-box">
            <!-- Step 1: Scan QR Code Only -->
            <div id="scannerStep">
                <div id="qrReaderWrapper" class="d-none mb-3">
                    <div id="qrReaderBox"></div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" id="startScanBtn" class="btn btn-primary btn-lg rounded-pill py-3 fw-semibold shadow-sm" onclick="startQrCamera()">
                        <i class="bi bi-qr-code-scan me-2"></i>Scan QR Code
                    </button>
                    <button type="button" id="stopScanBtn" class="btn btn-outline-danger rounded-pill py-2 fw-semibold d-none" onclick="stopQrCamera()">
                        <i class="bi bi-stop-circle me-2"></i>Stop Camera
                    </button>
                </div>

                <div id="lookupErrorBox" class="alert alert-danger rounded-3 small mt-3 mb-0 d-none"></div>
            </div>

            <!-- Step 2: Shown ONLY after scanning & finding the QR code -->
            <div id="editorStep" class="d-none text-start">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary font-mono px-3 py-2 rounded-pill" id="qrCodeBadge">VP-XXXXXX</span>
                    <span class="small text-muted fw-semibold" id="qrLabelText"></span>
                </div>

                <form id="updateLinkForm" onsubmit="event.preventDefault(); saveQrLink();">
                    <div class="mb-3">
                        <label for="targetUrlInput" class="form-label fw-semibold small">Link</label>
                        <input type="url" id="targetUrlInput" class="form-control form-control-lg" placeholder="https://..." required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" id="saveLinkBtn" class="btn btn-success btn-lg rounded-pill fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i>Save
                        </button>
                        <button type="button" class="btn btn-light border rounded-pill" onclick="resetToScanner()">
                            <i class="bi bi-qr-code-scan me-1"></i>Scan Another QR
                        </button>
                    </div>
                </form>

                <div id="saveFeedbackBox" class="alert rounded-3 small mt-3 mb-0 d-none"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const lookupEndpoint = @json(route('qr-link-editor.lookup'));
        const updateEndpointTemplate = @json(route('qr-link-editor.update', ['dynamicQr' => '__ID__']));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let html5Qr = null;
        let currentQrId = null;

        async function startQrCamera() {
            hideError();
            document.getElementById('qrReaderWrapper').classList.remove('d-none');
            document.getElementById('startScanBtn').classList.add('d-none');
            document.getElementById('stopScanBtn').classList.remove('d-none');

            try {
                html5Qr = new Html5Qrcode('qrReaderBox');
                await html5Qr.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 240, height: 240 } },
                    async (decodedText) => {
                        await stopQrCamera();
                        lookupCode(decodedText);
                    }
                );
            } catch (err) {
                await stopQrCamera();
                showError('Unable to access camera.');
            }
        }

        async function stopQrCamera() {
            if (html5Qr) {
                try {
                    await html5Qr.stop();
                    html5Qr.clear();
                } catch (e) {}
                html5Qr = null;
            }
            document.getElementById('qrReaderWrapper').classList.add('d-none');
            document.getElementById('startScanBtn').classList.remove('d-none');
            document.getElementById('stopScanBtn').classList.add('d-none');
        }

        async function lookupCode(rawCode) {
            const code = (rawCode || '').trim();
            if (!code) {
                showError('Invalid QR code.');
                return;
            }

            hideError();

            try {
                const res = await fetch(`${lookupEndpoint}?code=${encodeURIComponent(code)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                if (!res.ok || !data.found) {
                    showError(data.message || 'QR code not found.');
                    return;
                }

                showEditor(data.qr);
            } catch (e) {
                showError('Error looking up QR code.');
            }
        }

        function showEditor(qr) {
            currentQrId = qr.id;
            document.getElementById('qrCodeBadge').textContent = qr.code;
            document.getElementById('qrLabelText').textContent = qr.label || '';
            document.getElementById('targetUrlInput').value = qr.target_url || '';

            document.getElementById('saveFeedbackBox').classList.add('d-none');
            document.getElementById('scannerStep').classList.add('d-none');
            document.getElementById('editorStep').classList.remove('d-none');
            document.getElementById('targetUrlInput').focus();
        }

        async function saveQrLink() {
            if (!currentQrId) return;

            const btn = document.getElementById('saveLinkBtn');
            const targetUrl = document.getElementById('targetUrlInput').value.trim();
            const feedback = document.getElementById('saveFeedbackBox');

            btn.disabled = true;
            feedback.classList.add('d-none');

            try {
                const url = updateEndpointTemplate.replace('__ID__', currentQrId);
                const res = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ target_url: targetUrl })
                });

                const data = await res.json();

                if (!res.ok) {
                    const msg = data?.errors?.target_url?.[0] || data?.message || 'Unable to save link.';
                    feedback.className = 'alert alert-danger rounded-3 small mt-3 mb-0';
                    feedback.textContent = msg;
                    return;
                }

                feedback.className = 'alert alert-success rounded-3 small mt-3 mb-0';
                feedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + (data.message || 'Saved!');
            } catch (e) {
                feedback.className = 'alert alert-danger rounded-3 small mt-3 mb-0';
                feedback.textContent = 'Error saving link.';
            } finally {
                btn.disabled = false;
            }
        }

        function resetToScanner() {
            currentQrId = null;
            document.getElementById('editorStep').classList.add('d-none');
            document.getElementById('scannerStep').classList.remove('d-none');
            hideError();
        }

        function showError(msg) {
            const box = document.getElementById('lookupErrorBox');
            box.textContent = msg;
            box.classList.remove('d-none');
        }

        function hideError() {
            document.getElementById('lookupErrorBox').classList.add('d-none');
        }

        @if($initialQr)
            @php
                $initialQrPayload = [
                    'id' => $initialQr->id,
                    'code' => $initialQr->code,
                    'label' => $initialQr->label ?: $initialQr->code,
                    'target_url' => $initialQr->target_url,
                ];
            @endphp
            document.addEventListener('DOMContentLoaded', () => {
                showEditor(@json($initialQrPayload));
            });
        @endif
    </script>
</body>
</html>
