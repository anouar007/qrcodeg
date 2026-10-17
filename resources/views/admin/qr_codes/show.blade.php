@extends('layouts.admin')

@section('title', $qrCode->name)
@section('page_title', $qrCode->name)
@section('page_subtitle', 'Dynamic QR Code Configuration, Status & Live Analytics')

@section('content')
<div class="row g-4">
    <!-- Left Column: High-Res QR Card & Action Triggers -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white text-center mb-4">
            <!-- Rendered Vector SVG -->
            <div class="p-3 bg-light rounded-4 border d-inline-block mb-3 shadow-sm" style="max-width: 100%;">
                {!! $svgPreview !!}
            </div>

            <h4 class="fw-bold mb-1">{{ $qrCode->name }}</h4>
            <div class="mb-3">
                <span class="badge {{ $qrCode->status === 'active' ? 'bg-success' : 'bg-secondary' }} px-3 py-1">
                    {{ strtoupper($qrCode->status) }}
                </span>
            </div>

            <!-- Dynamic URL Box -->
            <div class="p-3 bg-light rounded-3 text-start border mb-3">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Dynamic Public URL</small>
                <a href="{{ $qrCode->dynamic_url }}" target="_blank" class="fw-bold text-primary font-mono small text-truncate d-block text-decoration-none">
                    {{ $qrCode->dynamic_url }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            </div>

            <!-- Identifiers Box -->
            <div class="row g-2 mb-4 text-start">
                <div class="col-6">
                    <div class="p-2 border rounded-3 bg-light">
                        <small class="text-muted d-block" style="font-size: 0.7rem;">PUBLIC IDENTIFIER</small>
                        <span class="font-mono fw-bold text-dark small">{{ $qrCode->public_code }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 border rounded-3 bg-light">
                        <small class="text-muted d-block" style="font-size: 0.7rem;">CLIENT ACCESS KEY</small>
                        <span class="font-mono fw-bold text-primary small">{{ $qrCode->access_code }}</span>
                    </div>
                </div>
            </div>

            <!-- Download Buttons -->
            <div class="d-flex flex-column gap-2 mb-3">
                <a href="{{ route('admin.qr-codes.download', [$qrCode, 'svg']) }}" class="btn btn-outline-primary btn-sm rounded-pill py-2">
                    <i class="bi bi-filetype-svg me-1"></i> Download Vector SVG
                </a>
                <a href="{{ route('admin.qr-codes.download', [$qrCode, 'png']) }}" class="btn btn-outline-success btn-sm rounded-pill py-2">
                    <i class="bi bi-filetype-png me-1"></i> Download High-Res PNG (600px)
                </a>
                <a href="{{ route('admin.qr-codes.download', [$qrCode, 'pdf']) }}" class="btn btn-outline-danger btn-sm rounded-pill py-2">
                    <i class="bi bi-filetype-pdf me-1"></i> Download Print Card PDF
                </a>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.qr-codes.edit', $qrCode) }}" class="btn btn-primary-custom btn-sm w-100 rounded-pill py-2">
                    <i class="bi bi-pencil me-1"></i> Customize Content
                </a>
            </div>
        </div>

        <!-- Status Management Card -->
        <div class="card card-custom p-4 bg-white">
            <h6 class="fw-bold mb-2">QR Operational Status</h6>
            <small class="text-muted d-block mb-3">Inactive or suspended QR codes will display an unavailable message when scanned.</small>

            <form action="{{ route('admin.qr-codes.toggle-status', $qrCode) }}" method="POST">
                @csrf
                <div class="input-group">
                    <select name="status" class="form-select">
                        <option value="active" {{ $qrCode->status === 'active' ? 'selected' : '' }}>Active (Normal)</option>
                        <option value="inactive" {{ $qrCode->status === 'inactive' ? 'selected' : '' }}>Inactive (Unavailable)</option>
                        <option value="suspended" {{ $qrCode->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="archived" {{ $qrCode->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    <button type="submit" class="btn btn-dark">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Content Configuration & Analytics Feed -->
    <div class="col-lg-8">
        <!-- Scan Statistics KPI -->
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="card card-custom p-3 bg-white">
                    <span class="text-muted small fw-semibold">Total Scans</span>
                    <div class="fs-2 fw-bold text-primary mt-1">{{ number_format($qrCode->scan_count) }}</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card card-custom p-3 bg-white">
                    <span class="text-muted small fw-semibold">Assigned Client</span>
                    <div class="fs-6 fw-bold text-dark mt-2 text-truncate">
                        {{ $qrCode->client ? $qrCode->client->company_name : 'Unassigned' }}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card card-custom p-3 bg-white">
                    <span class="text-muted small fw-semibold">Content Format</span>
                    <div class="fs-6 fw-bold text-dark mt-2 text-capitalize">
                        {{ str_replace('_', ' ', $qrCode->type) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Dynamic Content Configuration -->
        <div class="card card-custom p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Dynamic Destination Settings</h5>
                <a href="{{ route('admin.qr-codes.edit', $qrCode) }}" class="btn btn-sm btn-link text-decoration-none">
                    <i class="bi bi-pencil me-1"></i> Edit Configuration
                </a>
            </div>

            <div class="p-3 bg-light rounded-3 border">
                @php
                    $contentData = $qrCode->content?->content_data ?? [];
                @endphp
                <pre class="mb-0 font-mono small text-dark" style="max-height: 250px; overflow-y: auto;">{{ json_encode($contentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <!-- Recent Scans for this QR -->
        <div class="card card-custom p-4 bg-white">
            <h5 class="fw-bold mb-3">Scan Audit Trail</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light text-uppercase" style="font-size: 0.72rem;">
                        <tr>
                            <th>Scanned Time</th>
                            <th>Device</th>
                            <th>OS & Browser</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qrCode->scans as $scan)
                            <tr>
                                <td class="text-nowrap">{{ $scan->scanned_at?->format('M d, Y H:i:s') }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $scan->device_type }}</span></td>
                                <td>{{ $scan->operating_system }} &bull; {{ $scan->browser }}</td>
                                <td>{{ $scan->city ? $scan->city . ', ' : '' }}{{ $scan->country ?: 'Global' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No scan history recorded for this QR code yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
