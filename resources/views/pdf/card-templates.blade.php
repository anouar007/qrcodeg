<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $qr->name }} - {{ ucfirst(str_replace('_', ' ', $template)) }} Card</title>
    <style>
        @page {
            size: 105mm 148mm portrait;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            width: 105mm;
            height: 148mm;
            position: relative;
            overflow: hidden;
        }

        /* ------------------ TEMPLATE 1: GOOGLE REVIEW ------------------ */
        .card-google {
            background: #ffffff;
            width: 105mm;
            height: 148mm;
            padding: 12mm 10mm;
            text-align: center;
            border: 2px solid #e2e8f0;
        }
        .google-badge {
            display: inline-block;
            margin-bottom: 6px;
        }
        .google-logo-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .g-blue { color: #4285F4; }
        .g-red { color: #EA4335; }
        .g-yellow { color: #FBBC05; }
        .g-green { color: #34A853; }

        .google-stars {
            color: #FBBC05;
            font-size: 20px;
            margin: 4px 0 8px 0;
            letter-spacing: 2px;
        }
        .google-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .google-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 12px;
        }
        .qr-box {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            display: inline-block;
            margin: 0 auto 10px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .qr-img {
            width: 180px;
            height: 180px;
            display: block;
        }
        .code-pill {
            display: inline-block;
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .google-footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            margin-top: 4px;
        }
        .google-footer-text {
            font-size: 10px;
            color: #475569;
            font-weight: 600;
        }
        .google-client-name {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }

        /* ------------------ TEMPLATE 2: SOCIAL MEDIA ------------------ */
        .card-social {
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
            color: #ffffff;
            width: 105mm;
            height: 148mm;
            padding: 12mm 10mm;
            text-align: center;
        }
        .social-header-pill {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 30px;
            padding: 5px 14px;
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .social-title {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
            letter-spacing: -0.2px;
        }
        .social-sub {
            font-size: 11px;
            color: #cbd5e1;
            margin-bottom: 10px;
        }
        .social-icons-bar {
            margin-bottom: 12px;
            font-size: 11px;
            color: #38bdf8;
            font-weight: 700;
            letter-spacing: 3px;
        }
        .card-social .qr-box {
            background: #ffffff;
            border: 4px solid #ffffff;
            border-radius: 14px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .card-social .code-pill {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .social-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 8px;
        }
        .social-handle {
            font-size: 13px;
            font-weight: 700;
            color: #38bdf8;
        }

        /* ------------------ TEMPLATE 3: MENU CARD ------------------ */
        .card-menu {
            background: #fffdfa;
            color: #292524;
            width: 105mm;
            height: 148mm;
            padding: 12mm 10mm;
            text-align: center;
            border: 2px solid #e7e5e4;
        }
        .menu-ornament {
            font-size: 18px;
            color: #b45309;
            margin-bottom: 4px;
        }
        .menu-title {
            font-family: Georgia, serif;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #78350f;
            margin-bottom: 2px;
        }
        .menu-sub {
            font-size: 10.5px;
            font-style: italic;
            color: #78716c;
            margin-bottom: 10px;
        }
        .card-menu .qr-box {
            background: #ffffff;
            border: 2px solid #d6d3d1;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .card-menu .code-pill {
            background: #f5f5f4;
            color: #44403c;
            border: 1px solid #d6d3d1;
        }
        .menu-table-box {
            border: 1px dashed #a8a29e;
            border-radius: 8px;
            padding: 6px;
            margin-top: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #57534e;
        }
    </style>
</head>
<body>

@if($template === 'social_media')
    {{-- SOCIAL MEDIA CARD --}}
    <div class="card-social">
        <div class="social-header-pill">&#9679; CONNECT & FOLLOW &#9679;</div>
        <div class="social-title">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->name) }}</div>
        <div class="social-sub">Scannez pour rejoindre notre communauté en direct</div>
        <div class="social-icons-bar">&#9670; INSTAGRAM &bull; TIKTOK &bull; FACEBOOK &bull; WHATSAPP &#9670;</div>

        <div class="qr-box">
            <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
        </div>

        <div class="code-pill">{{ $qr->public_code }}</div>

        <div class="social-footer">
            <div class="social-handle">{{ $qr->name }}</div>
            <div style="font-size: 9px; color: #94a3b8; margin-top: 2px;">Compatible Appareil Photo & NFC Sans Contact</div>
        </div>
    </div>

@elseif($template === 'menu')
    {{-- RESTAURANT / CAFE MENU CARD --}}
    <div class="card-menu">
        <div class="menu-ornament">&#10022; &bull; &bull; &#10022;</div>
        <div class="menu-title">MENU DIGITAL</div>
        <div class="menu-sub">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->name) }}</div>
        <div style="font-size: 10px; color: #a16207; font-weight: 600; margin-bottom: 8px;">Scannez avec votre téléphone pour consulter la carte</div>

        <div class="qr-box">
            <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
        </div>

        <div class="code-pill">{{ $qr->public_code }}</div>

        <div class="menu-table-box">
            TABLE / EMPLACEMENT : _______________
        </div>
        <div style="font-size: 8.5px; color: #a8a29e; margin-top: 5px;">
            Accès instantané sans contact &bull; Cartes, Boissons & Tarifs
        </div>
    </div>

@else
    {{-- GOOGLE REVIEW CARD (DEFAULT) --}}
    <div class="card-google">
        <div class="google-badge">
            <span class="google-logo-text">
                <span class="g-blue">G</span><span class="g-red">o</span><span class="g-yellow">o</span><span class="g-blue">g</span><span class="g-green">l</span><span class="g-red">e</span>
                <span style="color: #1e293b; font-size: 19px; font-weight: 700; margin-left: 4px;">Avis</span>
            </span>
        </div>
        <div class="google-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <div class="google-title">Votre avis compte pour nous</div>
        <div class="google-subtitle">Scannez pour laisser votre note 5 étoiles sur Google</div>

        <div class="qr-box">
            <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
        </div>

        <div class="code-pill">{{ $qr->public_code }}</div>

        <div class="google-footer">
            <div class="google-client-name">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->name) }}</div>
            <div class="google-footer-text">Compatible tous smartphones &bull; Flash QR ou Toucher NFC</div>
        </div>
    </div>
@endif

</body>
</html>
