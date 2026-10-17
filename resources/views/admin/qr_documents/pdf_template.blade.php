<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $document->title }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header-subtitle {
            text-align: center;
            font-size: 9pt;
            color: #666;
            margin-bottom: 15px;
        }
        table.grid-table {
            width: 100%;
            border-collapse: collapse;
        }
        td.qr-cell {
            border: 1px dashed #999;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .card-name {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .card-codes {
            font-family: monospace;
            font-size: 7.5pt;
            margin-top: 4px;
            line-height: 1.3;
        }
        .qr-access {
            font-weight: bold;
            color: #1e3a8a;
        }
    </style>
</head>
<body>

<div class="header-title">{{ $document->title }}</div>
<div class="header-subtitle">
    {{ $document->client ? $document->client->company_name . ' • ' : '' }}
    {{ strtoupper($document->paper_size) }} ({{ $document->columns }} Cols &times; {{ $document->rows }} Rows)
</div>

<table class="grid-table">
    @php
        $cols = $document->columns ?: 2;
        $chunks = array_chunk($itemsWithQrs, $cols);
        $cellWidth = floor(100 / $cols) . '%';
    @endphp

    @foreach($chunks as $rowItems)
        <tr>
            @foreach($rowItems as $entry)
                @php $qr = $entry['qr']; @endphp
                <td class="qr-cell" style="width: {{ $cellWidth }};">
                    <div class="card-name">{{ $qr ? $qr->name : 'Dynamic QR' }}</div>

                    <div>
                        <img src="{{ $entry['qr_data_uri'] }}" style="width: 130px; height: 130px;">
                    </div>

                    @if($qr)
                        <div class="card-codes">
                            @if($document->show_public_code)
                                <div>CODE: {{ $qr->public_code }}</div>
                            @endif
                            @if($document->show_access_code)
                                <div class="qr-access">ACCESS: {{ $qr->access_code }}</div>
                            @endif
                        </div>
                    @endif
                </td>
            @endforeach

            {{-- Fill remainder of row if incomplete --}}
            @for($pad = count($rowItems); $pad < $cols; $pad++)
                <td class="qr-cell" style="width: {{ $cellWidth }};">&nbsp;</td>
            @endfor
        </tr>
    @endforeach
</table>

</body>
</html>
