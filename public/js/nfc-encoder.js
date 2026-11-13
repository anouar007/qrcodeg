/**
 * Web NFC Encoder & Chip Writer
 * Allows encoding physical NFC cards/tags directly from Chromium browsers on Android.
 */
class NfcEncoder {
    constructor() {
        this.reader = null;
        this.abortController = null;
        this.modalElement = null;
        this.isSupported = ('NDEFReader' in window);
        this.initModal();
    }

    /**
     * Build the reusable modal UI in DOM
     */
    initModal() {
        if (document.getElementById('nfcEncoderModal')) {
            this.modalElement = document.getElementById('nfcEncoderModal');
            return;
        }

        const modalHtml = `
        <div class="modal fade" id="nfcEncoderModal" tabindex="-1" aria-labelledby="nfcEncoderModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered mx-2 mx-sm-auto">
                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                    <div class="p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #180d38 0%, #5736e9 100%);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; color: #5736e9;">
                                <i class="bi bi-broadcast"></i>
                            </div>
                            <h5 class="modal-title fw-bold fs-6 mb-0" id="nfcEncoderModalLabel">Encodeur NFC Intégré</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="window.nfcEncoder.cancel()"></button>
                    </div>

                    <div class="modal-body p-4 text-center">
                        
                        <!-- Target Payload Info -->
                        <div class="p-2.5 rounded-3 bg-light border text-start mb-3">
                            <small class="text-muted text-uppercase d-block fw-bold" style="font-size: 0.68rem;" id="nfcTargetLabel">DESTINATION À GRAVER</small>
                            <span class="font-mono text-dark small fw-bold d-block text-break" id="nfcTargetUrlDisplay">https://...</span>
                        </div>

                        <!-- SUPPORTED STATE: ANIMATED PULSE -->
                        <div id="nfcSupportedContainer">
                            <div class="nfc-pulse-zone my-3">
                                <div class="nfc-pulse-circle circle-1"></div>
                                <div class="nfc-pulse-circle circle-2"></div>
                                <div class="nfc-pulse-circle circle-3"></div>
                                <div class="nfc-center-icon">
                                    <i class="bi bi-broadcast fs-1 text-white"></i>
                                </div>
                            </div>

                            <div id="nfcStatusText" class="fw-bold text-dark fs-5 mb-1">
                                Approchez le tag NFC...
                            </div>
                            <p class="text-muted small mb-3" id="nfcStatusSubtext">
                                Tenez la carte ou le porte-clé NFC contre l'arrière de votre téléphone jusqu'à la confirmation sonore.
                            </p>

                            <!-- Success Banner -->
                            <div id="nfcSuccessBanner" class="alert alert-success border-0 rounded-3 p-3 d-none mb-0">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                                    <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                                    <strong class="fs-6">Puce NFC Gravée avec Succès !</strong>
                                </div>
                                <div class="small font-mono text-dark" id="nfcDetectedUid">UID: Détection en cours...</div>
                            </div>

                            <!-- Error Banner -->
                            <div id="nfcErrorBanner" class="alert alert-danger border-0 rounded-3 p-2.5 small d-none mb-0">
                                <span id="nfcErrorMessage">Erreur lors de l'écriture.</span>
                            </div>
                        </div>

                        <!-- UNSUPPORTED STATE (iOS / HTTP / Desktop without Web NFC) -->
                        <div id="nfcUnsupportedContainer" class="d-none text-start">
                            <div class="alert alert-warning border-0 rounded-3 p-3 mb-3">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-info-circle-fill fs-5 text-warning flex-shrink-0"></i>
                                    <div>
                                        <strong class="d-block small" id="nfcUnsupportedTitle">Web NFC non disponible sur ce navigateur</strong>
                                        <p class="small text-muted mb-0" id="nfcUnsupportedDesc">
                                            L'écriture NFC directe depuis le navigateur est uniquement prise en charge par <strong>Chrome, Edge et Samsung Internet sur Android (en HTTPS)</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold small text-dark mb-2">Comment graver depuis cet appareil :</h6>
                            <ol class="small text-muted ps-3 mb-3" id="nfcUnsupportedSteps">
                                <li class="mb-1">Copiez l'URL ci-dessous en 1 clic.</li>
                                <li class="mb-1">Ouvrez l'application gratuite <strong>NFC Tools</strong> (iOS / Android) → <em>Écrire</em> → <em>Ajouter un enregistrement</em> → <em>URL</em>, puis collez le lien.</li>
                                <li class="mb-1">Ou ouvrez cette page sur un <strong>smartphone Android (Chrome en HTTPS)</strong> pour graver directement sans app.</li>
                            </ol>

                            <div class="d-grid gap-2">
                                <button type="button" id="nfcCopyUrlBtn" class="btn btn-primary w-100 rounded-pill btn-sm fw-semibold" onclick="window.nfcEncoder.copyTargetUrl(this)">
                                    <i class="bi bi-clipboard me-1"></i> Copier l'URL à graver
                                </button>
                                <a id="nfcIosAppBtn" href="https://apps.apple.com/app/nfc-tools/id1252962749" target="_blank" rel="noopener" class="btn btn-outline-dark w-100 rounded-pill btn-sm d-none">
                                    <i class="bi bi-apple me-1"></i> Ouvrir NFC Tools (App Store)
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer border-top bg-light p-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="window.nfcEncoder.cancel()">
                            Fermer
                        </button>
                        <button type="button" class="btn text-white rounded-pill px-4" style="background: #5736e9;" id="nfcRetryBtn" onclick="window.nfcEncoder.retry()">
                            <i class="bi bi-arrow-repeat me-1"></i> Réessayer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .nfc-pulse-zone {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nfc-pulse-circle {
            position: absolute;
            border-radius: 50%;
            border: 2px solid #5736e9;
            opacity: 0;
            animation: nfcWave 2.2s infinite cubic-bezier(0.36, 0.11, 0.89, 0.32);
        }
        .circle-1 { width: 70px; height: 70px; animation-delay: 0s; }
        .circle-2 { width: 105px; height: 105px; animation-delay: 0.6s; }
        .circle-3 { width: 140px; height: 140px; animation-delay: 1.2s; }
        .nfc-center-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #5736e9;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            box-shadow: 0 4px 15px rgba(87, 54, 233, 0.5);
        }
        @keyframes nfcWave {
            0% { transform: scale(0.6); opacity: 0.8; }
            50% { opacity: 0.4; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        </style>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modalElement = document.getElementById('nfcEncoderModal');
    }

    /**
     * Start the encoding procedure for a URL
     */
    async writeUrl(url, label = 'Carte NFC', options = {}) {
        this.currentUrl = url;
        this.currentLabel = label;
        this.options = options;

        document.getElementById('nfcTargetLabel').textContent = label;
        document.getElementById('nfcTargetUrlDisplay').textContent = url;

        // Reset UI banners
        document.getElementById('nfcSuccessBanner').classList.add('d-none');
        document.getElementById('nfcErrorBanner').classList.add('d-none');
        document.getElementById('nfcRetryBtn').classList.add('d-none');
        document.getElementById('nfcStatusText').textContent = 'Approchez le tag NFC...';
        document.getElementById('nfcStatusSubtext').textContent = 'Tenez la carte ou le porte-clé NFC contre l\'arrière de votre appareil.';

        const modal = new bootstrap.Modal(this.modalElement);
        modal.show();

        if (!this.isSupported) {
            document.getElementById('nfcSupportedContainer').classList.add('d-none');
            document.getElementById('nfcUnsupportedContainer').classList.remove('d-none');
            document.getElementById('nfcRetryBtn')?.classList.add('d-none');

            const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            const titleEl = document.getElementById('nfcUnsupportedTitle');
            const descEl = document.getElementById('nfcUnsupportedDesc');
            const iosBtn = document.getElementById('nfcIosAppBtn');

            if (!window.isSecureContext) {
                titleEl.textContent = 'Connexion HTTPS requise pour le NFC';
                descEl.innerHTML = 'Les navigateurs bloquent l\'accès à l\'antenne NFC sur HTTP. Accédez au site en <strong>HTTPS</strong> pour activer l\'encodage direct.';
                iosBtn?.classList.add('d-none');
            } else if (isIos) {
                titleEl.textContent = 'iPhone (iOS) : Web NFC bloqué par Apple';
                descEl.innerHTML = 'Apple interdit l\'écriture NFC depuis tous les navigateurs iPhone (Safari, Chrome, Edge). Pour écrire sur votre puce avec votre iPhone, copiez le lien et utilisez <strong>NFC Tools</strong> (gratuit).';
                iosBtn?.classList.remove('d-none');
            } else {
                titleEl.textContent = 'Web NFC non disponible sur ce navigateur';
                descEl.innerHTML = 'L\'écriture NFC directe depuis le navigateur fonctionne uniquement sur <strong>Android (Chrome, Edge, Samsung Internet) en HTTPS</strong>.';
                iosBtn?.classList.add('d-none');
            }
            return;
        }

        document.getElementById('nfcSupportedContainer').classList.remove('d-none');
        document.getElementById('nfcUnsupportedContainer').classList.add('d-none');

        this.startNfcWrite();
    }

    copyTargetUrl(btn) {
        const url = document.getElementById('nfcTargetUrlDisplay')?.textContent || this.currentUrl || '';
        if (!url) {
            return;
        }

        const markCopied = () => {
            if (!btn) return;
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> URL copiée !';
            btn.classList.replace('btn-primary', 'btn-success');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.replace('btn-success', 'btn-primary');
            }, 2000);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(markCopied).catch(() => this.fallbackCopy(url, markCopied));
        } else {
            this.fallbackCopy(url, markCopied);
        }
    }

    fallbackCopy(text, onSuccess) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            if (document.execCommand('copy') && typeof onSuccess === 'function') {
                onSuccess();
            }
        } catch (e) {}
        document.body.removeChild(ta);
    }

    async startNfcWrite() {
        try {
            this.abortController = new AbortController();
            this.reader = new NDEFReader();

            // Execute the physical NDEF URL write directly
            await this.reader.write(
                {
                    records: [
                        { recordType: "url", data: this.currentUrl }
                    ]
                },
                { signal: this.abortController.signal }
            );

            // Play celebratory audio chime
            this.playChime();

            document.getElementById('nfcStatusText').textContent = 'Écriture terminée !';
            document.getElementById('nfcStatusSubtext').textContent = 'Votre carte NFC est maintenant active et prête à l\'usage.';
            document.getElementById('nfcSuccessBanner').classList.remove('d-none');

            const uidText = this.capturedUid ? `UID physique : ${this.capturedUid}` : 'Gravure NDEF URL confirmée';
            document.getElementById('nfcDetectedUid').textContent = uidText;

            // Trigger callback if provided
            if (typeof this.options.onSuccess === 'function') {
                this.options.onSuccess(this.capturedUid || 'NFC-TAG-OK', this.currentUrl);
            }

            // If auto-bind endpoint provided, dispatch AJAX update
            if (this.options.bindEndpoint && this.capturedUid) {
                fetch(this.options.bindEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ nfc_uid: this.capturedUid, tag_uid: this.capturedUid })
                }).catch(e => console.warn('UID bind error:', e));
            }

        } catch (error) {
            if (error.name === 'AbortError') return;

            let msg = error.message || 'Le tag a été retiré trop vite ou est protégé en écriture.';
            if (error.name === 'NotAllowedError') {
                msg = 'Permission NFC refusée. Autorisez l\'accès NFC dans votre navigateur et réessayez.';
            } else if (error.name === 'NotSupportedError') {
                msg = 'NFC désactivé ou non pris en charge sur cet appareil. Vérifiez que le NFC est activé dans les paramètres du téléphone.';
            }

            document.getElementById('nfcErrorBanner').classList.remove('d-none');
            document.getElementById('nfcErrorMessage').textContent = 'Erreur d\'écriture : ' + msg;
            document.getElementById('nfcRetryBtn').classList.remove('d-none');
        }
    }

    retry() {
        document.getElementById('nfcSuccessBanner').classList.add('d-none');
        document.getElementById('nfcErrorBanner').classList.add('d-none');
        document.getElementById('nfcRetryBtn').classList.add('d-none');
        this.startNfcWrite();
    }

    cancel() {
        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }
    }

    playChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
            osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); // E5
            osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.2); // G5
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.4);
        } catch(e) {}
    }
}

// Global instance
window.addEventListener('DOMContentLoaded', () => {
    window.nfcEncoder = new NfcEncoder();
});

