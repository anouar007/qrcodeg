<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $qr->code }} - Google Review Card</title>
    <style>
        @page {
            @if(($dimension ?? '85x55') === 'a6')
                size: 105mm 148mm portrait;
            @else
                size: 85mm 55mm landscape;
            @endif
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        body {
            background-color: #ffffff;
            color: #1e293b;
            @if(($dimension ?? '85x55') === 'a6')
                width: 105mm;
                height: 148mm;
            @else
                width: 85mm;
                height: 55mm;
            @endif
            position: relative;
            overflow: hidden;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @if(($dimension ?? '85x55') === 'a6')
            /* A6 Portrait Layout */
            .card-wrapper {
                width: 105mm;
                height: 148mm;
                padding: 12mm 10mm;
                text-align: center;
                background: #ffffff;
            }
            .google-logo {
                font-size: 26px;
                font-weight: 800;
                letter-spacing: -0.5px;
                margin-bottom: 4px;
            }
            .stars {
                color: #FBBC05;
                font-size: 22px;
                letter-spacing: 3px;
                margin-bottom: 8px;
            }
            .headline {
                font-size: 16px;
                font-weight: 800;
                color: #0f172a;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 4px;
            }
            .subtitle {
                font-size: 11px;
                color: #64748b;
                margin-bottom: 12px;
            }
            .qr-container {
                background: #ffffff;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 10px;
                display: inline-block;
                margin: 0 auto 10px auto;
            }
            .qr-img {
                width: 175px;
                height: 175px;
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
            .client-name {
                font-size: 13px;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: 2px;
            }
            .instructions {
                font-size: 9.5px;
                color: #94a3b8;
            }
        @else
            /* Standard 85mm x 55mm Card Layout (Landscape) */
            .card-wrapper {
                width: 85mm;
                height: 55mm;
                padding: 4mm 5mm;
                table-layout: fixed;
                width: 100%;
                height: 100%;
            }
            .card-table {
                width: 100%;
                height: 100%;
                border-collapse: collapse;
            }
            .left-col {
                width: 58%;
                vertical-align: middle;
                padding-right: 3mm;
            }
            .right-col {
                width: 42%;
                vertical-align: middle;
                text-align: center;
            }
            .google-logo {
                font-size: 16px;
                font-weight: 800;
                letter-spacing: -0.3px;
                margin-bottom: 2px;
            }
            .stars {
                color: #FBBC05;
                font-size: 13px;
                letter-spacing: 1.5px;
                margin-bottom: 4px;
            }
            .headline {
                font-size: 11px;
                font-weight: 800;
                color: #0f172a;
                line-height: 1.2;
                margin-bottom: 2px;
                text-transform: uppercase;
            }
            .subtitle {
                font-size: 7.5px;
                color: #64748b;
                line-height: 1.2;
                margin-bottom: 4px;
            }
            .client-name {
                font-size: 9px;
                font-weight: 700;
                color: #1e3a8a;
                margin-bottom: 3px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 45mm;
            }
            .qr-container {
                background: #ffffff;
                border: 1.5px solid #cbd5e1;
                border-radius: 8px;
                padding: 4px;
                display: inline-block;
            }
            .qr-img {
                width: 110px;
                height: 110px;
                display: block;
            }
            .code-pill {
                display: inline-block;
                background: #f8fafc;
                color: #0f172a;
                border: 1px solid #cbd5e1;
                padding: 2px 7px;
                border-radius: 10px;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.5px;
                margin-top: 3px;
            }
            .instructions {
                font-size: 6.5px;
                color: #94a3b8;
                margin-top: 2px;
            }
        @endif

        .g-blue { color: #4285F4; }
        .g-red { color: #EA4335; }
        .g-yellow { color: #FBBC05; }
        .g-green { color: #34A853; }
    </style>
</head>
<body>

@if(($dimension ?? '85x55') === 'a6')
    <div class="card-wrapper">
        <div class="google-logo">
            <span class="g-blue">G</span><span class="g-red">o</span><span class="g-yellow">o</span><span class="g-blue">g</span><span class="g-green">l</span><span class="g-red">e</span>
            <span style="color: #1e293b; font-size: 20px; font-weight: 700; margin-left: 4px;">Reviews</span>
        </div>
        <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <div class="headline">Scan to leave a review</div>
        <div class="subtitle">Your feedback helps us serve you better every day</div>

        <div class="qr-container">
            <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
        </div>

        <div>
            <span class="code-pill">{{ $qr->code }}</span>
        </div>

        <div class="client-name">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->label) }}</div>
        <div class="instructions">Compatible with all smartphones &bull; Instant camera scan</div>
    </div>
@else
    <div class="card-wrapper">
        <table class="card-table">
            <tr>
                <td class="left-col">
                    <div class="google-logo">
                        <span class="g-blue">G</span><span class="g-red">o</span><span class="g-yellow">o</span><span class="g-blue">g</span><span class="g-green">l</span><span class="g-red">e</span>
                        <span style="color: #1e293b; font-size: 13px; font-weight: 700;">Avis</span>
                    </div>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <div class="headline">Scan to leave a review</div>
                    <div class="subtitle">Laissez votre note 5 étoiles en flashant ce code</div>
                    <div class="client-name">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->label) }}</div>
                    <div class="instructions">Appareil photo &bull; Sans application requise</div>
                </td>
                <td class="right-col">
                    <div class="qr-container">
                        <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
                    </div>
                    <div>
                        <span class="code-pill">{{ $qr->code }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endif

</body>
</html>
