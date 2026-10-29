<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dynamic QR Batch Cards</title>
    <style>
        @page {
            size: {{ $widthPt }}pt {{ $heightPt }}pt;
            margin: 0;
            padding: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            width: {{ $widthPt }}pt;
            height: {{ $heightPt }}pt;
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            overflow: hidden;
        }
        .qr-card-page {
            position: relative;
            width: {{ $widthPt }}pt;
            height: {{ $heightPt }}pt;
            background: #ffffff;
            overflow: hidden;
            page-break-after: always;
        }
        .qr-card-page:last-child {
            page-break-after: avoid;
        }
        .bg-template {
            position: absolute;
            top: 0;
            left: 0;
            width: {{ $widthPt }}pt;
            height: {{ $heightPt }}pt;
            background: #ffffff;
            z-index: 1;
        }
        .qr-container {
            position: absolute;
            z-index: 10;
            left: {{ $qrLeftPt }}pt;
            top: {{ $qrTopPt }}pt;
            width: {{ $qrSizePt }}pt;
            height: {{ $qrSizePt }}pt;
            text-align: center;
            @if(!empty($qrBg) && $qrBg !== 'transparent')
                background-color: {{ $qrBg }};
            @endif
        }
        .qr-container img {
            width: 100%;
            height: 100%;
            display: block;
        }
        .code-container {
            position: absolute;
            z-index: 12;
            width: {{ $codeWidthPt }}pt;
            left: {{ $codeLeftPt }}pt;
            top: {{ $codeTopPt }}pt;
            text-align: center;
            line-height: 1;
        }
        .code-pill {
            display: inline-block;
            font-size: {{ $codeFontSizePt }}pt;
            color: {{ $codeColor }};
            font-weight: {{ $codeFontWeight }};
            letter-spacing: {{ $codeLetterSpacingPt }}pt;
            @if($codeFontFamily === 'monospace')
                font-family: 'Courier New', Courier, monospace;
            @else
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            @endif
            @if($codeBg && $codeBg !== 'transparent')
                background: {{ $codeBg }};
                padding: {{ $codePad }}pt {{ $codePad * 2 }}pt;
                border-radius: 4pt;
            @endif
        }
    </style>
</head>
<body>
    @foreach($items as $item)
        <div class="qr-card-page">
            @if(!empty($bgImageBase64))
                <img src="{{ $bgImageBase64 }}" class="bg-template" />
            @endif

            <div class="qr-container">
                <img src="{{ $item['qrPng'] }}" />
            </div>

            @if($showCode)
                <div class="code-container">
                    <span class="code-pill">{{ $item['code'] }}</span>
                </div>
            @endif
        </div>
    @endforeach
</body>
</html>
