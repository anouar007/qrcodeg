<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR or NFC — {{ config('app.name') }}</title>

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
            <div id="qrReaderWrapper" class="d-none mb-3">
                <div id="qrReaderBox"></div>
            </div>

            <div class="d-grid gap-2.5">
                <button type="button" id="startQrBtn" class="btn btn-primary btn-lg rounded-pill py-3 fw-semibold shadow-sm" onclick="startQrScanner()">
                    <i class="bi bi-qr-code-scan me-2"></i>Scan QR Code
                </button>
                <button type="button" id="stopQrBtn" class="btn btn-outline-danger rounded-pill py-2 fw-semibold d-none" onclick="stopQrScanner()">
                    <i class="bi bi-stop-circle me-2"></i>Stop Camera
                </button>

                <button type="button" id="startNfcBtn" class="btn btn-dark btn-lg rounded-pill py-3 fw-semibold shadow-sm" onclick="startNfcScanner()">
                    <i class="bi bi-broadcast me-2"></i>Scan NFC
                </button>
                <button type="button" id="stopNfcBtn" class="btn btn-outline-secondary rounded-pill py-2 fw-semibold d-none" onclick="stopNfcScanner()">
                    <i class="bi bi-x-circle me-2"></i>Cancel NFC Scan
                </button>
            </div>

            <div id="nfcWaitingBox" class="alert alert-info rounded-3 small mt-3 mb-0 d-none">
                <i class="bi bi-broadcast me-1"></i>Hold your NFC card or tag near the back of your device...
            </div>
            <div id="scanErrorBox" class="alert alert-danger rounded-3 small mt-3 mb-0 d-none"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const lookupEndpoint = @json(route('menu-card-scanner.lookup'));
        let html5Qr = null;
        let nfcAbortController = null;

        async function startQrScanner() {
            hideMessages();
            stopNfcScanner();
            document.getElementById('qrReaderWrapper').classList.remove('d-none');
            document.getElementById('startQrBtn').classList.add('d-none');
            document.getElementById('stopQrBtn').classList.remove('d-none');

            try {
                html5Qr = new Html5Qrcode('qrReaderBox');
                await html5Qr.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 240, height: 240 } },
                    async (decodedText) => {
                        await stopQrScanner();
                        resolvePayload(decodedText);
                    }
                );
            } catch (err) {
                await stopQrScanner();
                showError('Unable to access camera.');
            }
        }

        async function stopQrScanner() {
            if (html5Qr) {
                try {
                    await html5Qr.stop();
                    html5Qr.clear();
                } catch (e) {}
                html5Qr = null;
            }
            document.getElementById('qrReaderWrapper').classList.add('d-none');
            document.getElementById('startQrBtn').classList.remove('d-none');
            document.getElementById('stopQrBtn').classList.add('d-none');
        }

        async function startNfcScanner() {
            hideMessages();
            await stopQrScanner();

            if (!('NDEFReader' in window)) {
                showError('Web NFC is only supported on Android Chrome/Edge over HTTPS.');
                return;
            }

            try {
                nfcAbortController = new AbortController();
                const reader = new NDEFReader();
                document.getElementById('nfcWaitingBox').classList.remove('d-none');
                document.getElementById('startNfcBtn').classList.add('d-none');
                document.getElementById('stopNfcBtn').classList.remove('d-none');

                await reader.scan({ signal: nfcAbortController.signal });

                reader.onreading = (event) => {
                    let payload = '';
                    if (event.message && event.message.records) {
                        for (const record of event.message.records) {
                            if (record.recordType === 'url' || record.recordType === 'text') {
                                const decoder = new TextDecoder(record.encoding || 'utf-8');
                                payload = decoder.decode(record.data);
                                break;
                            }
                        }
                    }
                    if (!payload && event.serialNumber) {
                        payload = event.serialNumber;
                    }
                    stopNfcScanner();
                    if (payload) {
                        resolvePayload(payload);
                    } else {
                        showError('Empty NFC tag.');
                    }
                };
            } catch (err) {
                stopNfcScanner();
                if (err.name !== 'AbortError') {
                    showError('Unable to start NFC scan: ' + (err.message || 'Check NFC settings.'));
                }
            }
        }

        function stopNfcScanner() {
            if (nfcAbortController) {
                nfcAbortController.abort();
                nfcAbortController = null;
            }
            document.getElementById('nfcWaitingBox').classList.add('d-none');
            document.getElementById('startNfcBtn').classList.remove('d-none');
            document.getElementById('stopNfcBtn').classList.add('d-none');
        }

        async function resolvePayload(rawPayload) {
            hideMessages();
            try {
                const res = await fetch(`${lookupEndpoint}?payload=${encodeURIComponent(rawPayload)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (!res.ok || !data.found || !data.edit_url) {
                    showError(data.message || 'No menu or digital card found.');
                    return;
                }
                window.location.href = data.edit_url;
            } catch (e) {
                showError('Error resolving QR/NFC target.');
            }
        }

        function showError(msg) {
            const box = document.getElementById('scanErrorBox');
            box.textContent = msg;
            box.classList.remove('d-none');
        }

        function hideMessages() {
            document.getElementById('scanErrorBox').classList.add('d-none');
            document.getElementById('nfcWaitingBox').classList.add('d-none');
        }
    </script>
</body>
</html>
