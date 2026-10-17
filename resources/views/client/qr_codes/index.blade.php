@extends('layouts.client')

@section('title', 'My QR Codes')
@section('page_title', 'My Dynamic QR Codes')
@section('page_subtitle', 'Browse your active QR codes, customize destination content, and download print assets')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <!-- Header & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Assigned QR Codes ({{ $qrCodes->total() }})</h5>
            <small class="text-muted">Modify what appears when customers scan your physical QR codes</small>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('client.qr-codes.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search name or public code..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">All Content Types</option>
                <option value="website" {{ request('type') === 'website' ? 'selected' : '' }}>Website URL</option>
                <option value="contact" {{ request('type') === 'contact' ? 'selected' : '' }}>Contact / vCard</option>
                <option value="whatsapp" {{ request('type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="wifi" {{ request('type') === 'wifi' ? 'selected' : '' }}>WiFi</option>
                <option value="business" {{ request('type') === 'business' ? 'selected' : '' }}>Business Profile</option>
                <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Product</option>
                <option value="custom_landing" {{ request('type') === 'custom_landing' ? 'selected' : '' }}>Landing Page</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
            <a href="{{ route('client.qr-codes.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </form>

    <!-- Table of QR codes conforming to Section 12 -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th>QR Preview</th>
                    <th>QR Name</th>
                    <th>Public Code</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Scans</th>
                    <th>Last Scan</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qrItemsWithSvg as $entry)
                    @php $qr = $entry['qr']; @endphp
                    <tr>
                        <td style="width: 80px;">
                            <div class="p-1 bg-light border rounded-3 text-center d-inline-block">
                                {!! $entry['svg'] !!}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('client.qr-codes.customize', $qr) }}" class="fw-bold text-dark text-decoration-none d-block">
                                {{ $qr->name }}
                            </a>
                            <small class="text-muted text-truncate d-block" style="max-width: 200px;">{{ $qr->description ?: 'No memo' }}</small>
                        </td>
                        <td>
                            <span class="badge-code">{{ $qr->public_code }}</span>
                            <a href="{{ url('/q/' . $qr->public_code) }}" target="_blank" class="text-muted ms-1" title="Test Dynamic Route">
                                <i class="bi bi-box-arrow-up-right" style="font-size: 0.72rem;"></i>
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $qr->type) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $qr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                {{ ucfirst($qr->status) }}
                            </span>
                        </td>
                        <td class="fw-bold">
                            {{ number_format($qr->scan_count) }}
                        </td>
                        <td class="text-muted small">
                            {{ $qr->last_scanned_at ? $qr->last_scanned_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('client.qr-codes.customize', $qr) }}" class="btn btn-primary" title="Customize Content">
                                    <i class="bi bi-sliders me-1"></i> Customize
                                </a>
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ url('/q/' . $qr->public_code) }}" target="_blank"><i class="bi bi-eye me-2 text-primary"></i> Preview Live Scan</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.analytics', ['qr_code_id' => $qr->id]) }}"><i class="bi bi-graph-up me-2 text-info"></i> Scan Analytics</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header small text-uppercase">Download Asset</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('client.qr-codes.download', [$qr, 'png']) }}"><i class="bi bi-filetype-png me-2 text-success"></i> PNG Image</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.qr-codes.download', [$qr, 'svg']) }}"><i class="bi bi-filetype-svg me-2 text-warning"></i> SVG Vector</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client.qr-codes.download', [$qr, 'pdf']) }}"><i class="bi bi-filetype-pdf me-2 text-danger"></i> PDF Print Card</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-qr-code fs-2 d-block mb-2"></i>
                            No QR codes match your search criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $qrCodes->links() }}
    </div>
</div>
@endsection
