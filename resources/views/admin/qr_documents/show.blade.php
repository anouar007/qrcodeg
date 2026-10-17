@extends('layouts.admin')

@section('title', $qrDocument->title)
@section('page_title', $qrDocument->title)
@section('page_subtitle', 'Print sheet preview & export operations')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold mb-1">{{ $qrDocument->title }}</h4>
            <div class="small text-muted d-flex gap-3">
                <span><strong>Format:</strong> {{ strtoupper($qrDocument->paper_size) }}</span>
                <span><strong>Grid:</strong> {{ $qrDocument->columns }} columns &times; {{ $qrDocument->rows }} rows</span>
                <span><strong>QR Cards:</strong> {{ $qrDocument->items->count() }} items</span>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.qr-documents.print', $qrDocument) }}" target="_blank" class="btn btn-outline-dark rounded-pill">
                <i class="bi bi-printer me-1"></i> Print Sheet (Browser)
            </a>
            <a href="{{ route('admin.qr-documents.pdf', $qrDocument) }}" class="btn btn-danger rounded-pill">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Interactive Grid Preview -->
    <div class="row g-3" style="--bs-gutter-y: 1.25rem;">
        @php
            $colClass = match($qrDocument->columns) {
                1 => 'col-12',
                2 => 'col-md-6',
                3 => 'col-md-4',
                4 => 'col-md-3',
                default => 'col-md-6',
            };
        @endphp

        @foreach($itemsWithSvg as $entry)
            @php $qr = $entry['qr']; @endphp
            <div class="{{ $colClass }}">
                <div class="card p-3 text-center border-2 border-dashed h-100 bg-white shadow-sm position-relative">
                    @if($qrDocument->show_logo && $qrDocument->client && $qrDocument->client->logo)
                        <img src="{{ asset('storage/' . $qrDocument->client->logo) }}" alt="Logo" class="mx-auto mb-2" style="height: 28px; object-fit: contain;">
                    @endif

                    <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $qr ? $qr->name : 'QR Card' }}</h6>

                    <div class="my-2 d-inline-block">
                        {!! $entry['svg'] !!}
                    </div>

                    @if($qr)
                        <div class="mt-2 pt-2 border-top small">
                            @if($qrDocument->show_public_code)
                                <div><strong class="text-muted" style="font-size: 0.72rem;">PUBLIC:</strong> <span class="font-mono text-dark fw-bold">{{ $qr->public_code }}</span></div>
                            @endif
                            @if($qrDocument->show_access_code)
                                <div class="mt-1"><strong class="text-muted" style="font-size: 0.72rem;">ACCESS KEY:</strong> <span class="font-mono text-primary fw-bold">{{ $qr->access_code }}</span></div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
