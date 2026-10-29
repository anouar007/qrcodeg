<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $qr->code }} - Social Media Card</title>
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
            background-color: #090d16;
            color: #ffffff;
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
                background: linear-gradient(180deg, #090d16 0%, #1e1b4b 100%);
            }
            .badge-pill {
                display: inline-block;
                background: rgba(56, 189, 248, 0.15);
                border: 1px solid rgba(56, 189, 248, 0.35);
                color: #38bdf8;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                margin-bottom: 8px;
            }
            .headline {
                font-size: 22px;
                font-weight: 800;
                letter-spacing: -0.5px;
                margin-bottom: 4px;
            }
            .networks-bar {
                font-size: 11px;
                color: #38bdf8;
                font-weight: 700;
                letter-spacing: 2px;
                margin-bottom: 12px;
            }
            .qr-container {
                background: #ffffff;
                border: 3px solid #38bdf8;
                border-radius: 14px;
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
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.25);
                color: #ffffff;
                padding: 4px 14px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 1px;
                margin-bottom: 8px;
            }
            .handle-slot {
                font-size: 14px;
                font-weight: 800;
                color: #38bdf8;
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
                background: #090d16;
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
            .badge-pill {
                display: inline-block;
                background: rgba(56, 189, 248, 0.15);
                border: 1px solid rgba(56, 189, 248, 0.35);
                color: #38bdf8;
                padding: 2px 7px;
                border-radius: 12px;
                font-size: 7px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin-bottom: 3px;
            }
            .headline {
                font-size: 15px;
                font-weight: 800;
                letter-spacing: -0.3px;
                line-height: 1.1;
                margin-bottom: 2px;
            }
            .networks-bar {
                font-size: 7.5px;
                color: #38bdf8;
                font-weight: 700;
                letter-spacing: 1px;
                margin-bottom: 4px;
            }
            .handle-slot {
                font-size: 10px;
                font-weight: 800;
                color: #38bdf8;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 45mm;
            }
            .instructions {
                font-size: 6.5px;
                color: #94a3b8;
                line-height: 1.2;
            }
            .qr-container {
                background: #ffffff;
                border: 2px solid #38bdf8;
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
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.25);
                color: #ffffff;
                padding: 2px 7px;
                border-radius: 10px;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.5px;
                margin-top: 3px;
            }
        @endif
    </style>
</head>
<body>

@if(($dimension ?? '85x55') === 'a6')
    <div class="card-wrapper">
        <div class="badge-pill">&#9679; CONNECT &bull; ENGAGE &#9679;</div>
        <div class="headline">Follow Us</div>
        <div class="networks-bar">&#9670; INSTAGRAM &bull; TIKTOK &bull; FACEBOOK &bull; WHATSAPP &#9670;</div>

        <div class="qr-container">
            <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
        </div>

        <div>
            <span class="code-pill">{{ $qr->code }}</span>
        </div>

        <div class="handle-slot">{{ '@' . ltrim(preg_replace('/[^a-zA-Z0-9_.]/', '', strtolower($qr->client?->company_name ?: ($qr->client?->name ?: $qr->label))), '@') }}</div>
        <div class="instructions">Scan with any smartphone camera to visit all our social channels</div>
    </div>
@else
    <div class="card-wrapper">
        <table class="card-table">
            <tr>
                <td class="left-col">
                    <div class="badge-pill">&#9679; CONNECT &bull; ENGAGE &#9679;</div>
                    <div class="headline">Follow Us</div>
                    <div class="networks-bar">INSTAGRAM &bull; TIKTOK &bull; FB &bull; WA</div>
                    <div class="handle-slot">{{ '@' . ltrim(preg_replace('/[^a-zA-Z0-9_.]/', '', strtolower($qr->client?->company_name ?: ($qr->client?->name ?: $qr->label))), '@') }}</div>
                    <div class="instructions">Scan to access all links & social platforms in one tap</div>
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
