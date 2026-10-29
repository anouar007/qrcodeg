<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $qr->code }} - Digital Menu Card</title>
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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, Georgia, serif;
        }
        body {
            background-color: #fdfbf7;
            color: #292524;
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
                background: #fdfbf7;
                border: 2px solid #e7e5e4;
            }
            .brand-name {
                font-size: 14px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 2px;
                color: #b45309;
                margin-bottom: 2px;
            }
            .headline {
                font-family: Georgia, serif;
                font-size: 24px;
                font-weight: 700;
                color: #78350f;
                letter-spacing: 0.5px;
                margin-bottom: 4px;
            }
            .subtitle {
                font-size: 11px;
                color: #78716c;
                font-style: italic;
                margin-bottom: 12px;
            }
            .qr-container {
                background: #ffffff;
                border: 2px solid #d6d3d1;
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
                background: #f5f5f4;
                color: #44403c;
                border: 1px solid #d6d3d1;
                padding: 4px 14px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 1px;
                margin-bottom: 10px;
            }
            .table-box {
                border: 1px dashed #a8a29e;
                border-radius: 6px;
                padding: 6px 14px;
                display: inline-block;
                font-size: 12px;
                font-weight: 700;
                color: #78350f;
                letter-spacing: 1px;
                margin-bottom: 6px;
            }
            .instructions {
                font-size: 9.5px;
                color: #a8a29e;
            }
        @else
            /* Standard 85mm x 55mm Card Layout (Landscape) */
            .card-wrapper {
                width: 85mm;
                height: 55mm;
                padding: 4mm 5mm;
                background: #fdfbf7;
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
            .brand-name {
                font-size: 8px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: #b45309;
                margin-bottom: 1px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 45mm;
            }
            .headline {
                font-family: Georgia, serif;
                font-size: 13px;
                font-weight: 700;
                color: #78350f;
                letter-spacing: 0.3px;
                line-height: 1.1;
                margin-bottom: 2px;
            }
            .subtitle {
                font-size: 7.5px;
                color: #78716c;
                font-style: italic;
                line-height: 1.2;
                margin-bottom: 4px;
            }
            .table-box {
                border: 1px dashed #a8a29e;
                border-radius: 4px;
                padding: 2px 6px;
                display: inline-block;
                font-size: 8px;
                font-weight: 700;
                color: #78350f;
                margin-bottom: 3px;
            }
            .instructions {
                font-size: 6.5px;
                color: #a8a29e;
                line-height: 1.2;
            }
            .qr-container {
                background: #ffffff;
                border: 1.5px solid #d6d3d1;
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
                background: #f5f5f4;
                color: #44403c;
                border: 1px solid #d6d3d1;
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
        <div class="brand-name">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->label) }}</div>
        <div class="headline">Digital Menu</div>
        <div class="subtitle">Scan to explore our fresh dishes, desserts & wine list</div>

        <div class="qr-container">
            <img src="{{ $qrPngBase64 }}" class="qr-img" alt="QR Code">
        </div>

        <div>
            <span class="code-pill">{{ $qr->code }}</span>
        </div>

        <div class="table-box">TABLE : _______________</div>
        <div class="instructions">Instant contactless access &bull; No app download required</div>
    </div>
@else
    <div class="card-wrapper">
        <table class="card-table">
            <tr>
                <td class="left-col">
                    <div class="brand-name">{{ $qr->client?->company_name ?: ($qr->client?->name ?: $qr->label) }}</div>
                    <div class="headline">Digital Menu</div>
                    <div class="subtitle">Scannez pour consulter notre carte & boissons</div>
                    <div class="table-box">TABLE : _________</div>
                    <div class="instructions">Accès instantané sans contact &bull; Smartphone</div>
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
