<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $qr->name }} - PDF Card</title>
    <style>
        @page {
            margin: 20mm;
            size: a5 portrait;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            text-align: center;
            color: #0f172a;
            padding-top: 30px;
        }
        .card-box {
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 30px;
            background: #ffffff;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .client-name {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 25px;
        }
        .qr-image-container {
            margin: 20px auto;
        }
        .code-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 20px auto;
            display: inline-block;
            font-family: monospace;
            font-size: 14px;
        }
        .instructions {
            font-size: 13px;
            color: #475569;
            margin-top: 25px;
            line-height: 1.5;
        }
        .footer {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="card-box">
    <div class="header-title">{{ $qr->name }}</div>
    <div class="client-name">{{ $qr->client ? $qr->client->company_name : 'Dynamic QR Service' }}</div>

    <div class="qr-image-container">
        <img src="{{ $svgDataUri }}" style="width: 200px; height: 200px;">
    </div>

    <div>
        <div class="code-box">
            <div><strong>PUBLIC CODE:</strong> {{ $qr->public_code }}</div>
            <div style="margin-top: 5px;"><strong>ACCESS CODE:</strong> {{ $qr->access_code }}</div>
        </div>
    </div>

    <div class="instructions">
        Scan using any standard smartphone camera.<br>
        To customize the live destination of this QR code, visit:<br>
        <strong>{{ url('/client/access') }}</strong>
    </div>

    <div class="footer">
        Powered by QR Studio Dynamic Platform &bull; Code: {{ $qr->public_code }}
    </div>
</div>

</body>
</html>
