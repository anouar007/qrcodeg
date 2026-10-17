<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print — {{ $qrDocument->title }}</title>
    <style>
        @page {
            size: {{ $qrDocument->paper_size }} portrait;
            margin: 10mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #ffffff;
            color: #000000;
            margin: 0;
            padding: 0;
        }
        .no-print {
            background: #1e293b;
            color: #ffffff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        .print-grid {
            display: grid;
            grid-template-columns: repeat({{ $qrDocument->columns }}, 1fr);
            gap: 12px;
            padding: 10px;
        }
        .qr-card-print {
            border: 1px dashed #94a3b8;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            background: #ffffff;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .qr-card-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #0f172a;
        }
        .qr-card-svg {
            margin: 6px 0;
        }
        .qr-card-footer {
            margin-top: 6px;
            font-size: 10px;
            font-family: monospace;
            color: #334155;
            line-height: 1.4;
        }
        .qr-access-key {
            font-weight: bold;
            color: #1e3a8a;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-grid {
                padding: 0;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<div class="no-print">
    <div>
        <strong>{{ $qrDocument->title }}</strong> &bull; {{ strtoupper($qrDocument->paper_size) }} ({{ $qrDocument->columns }} cols &times; {{ $qrDocument->rows }} rows)
    </div>
    <div>
        <button class="btn-print" onclick="window.print();">Print Document Now</button>
    </div>
</div>

<div class="print-grid">
    @foreach($itemsWithSvg as $entry)
        @php $qr = $entry['qr']; @endphp
        <div class="qr-card-print">
            @if($qrDocument->show_logo && $qrDocument->client && $qrDocument->client->logo)
                <img src="{{ asset('storage/' . $qrDocument->client->logo) }}" alt="Logo" style="height: 24px; margin-bottom: 4px;">
            @endif

            <div class="qr-card-title">{{ $qr ? $qr->name : 'Dynamic QR' }}</div>

            <div class="qr-card-svg">
                {!! $entry['svg'] !!}
            </div>

            @if($qr)
                <div class="qr-card-footer">
                    @if($qrDocument->show_public_code)
                        <div>CODE: {{ $qr->public_code }}</div>
                    @endif
                    @if($qrDocument->show_access_code)
                        <div class="qr-access-key">ACCESS: {{ $qr->access_code }}</div>
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>

</body>
</html>
