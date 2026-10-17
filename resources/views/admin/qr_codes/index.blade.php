@extends('layouts.admin')

@section('title', 'All Dynamic QR Codes')
@section('page_title', 'QR Code Directory')
@section('page_subtitle', 'Browse, filter, customize, and batch download all dynamic QR codes')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <!-- Top Action Row -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Dynamic QR Codes</h5>
            <small class="text-muted">Total: {{ $qrCodes->total() }} codes generated</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-dark rounded-pill" id="batchZipBtn" onclick="submitBatchZip();">
                <i class="bi bi-file-earmark-zip me-1"></i> Download Selected ZIP
            </button>
            <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary-custom rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Generate QR Codes
            </a>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <form method="GET" action="{{ route('admin.qr-codes.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search name, code, or client..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-2">
            <select name="client_id" class="form-select">
                <option value="">All Clients</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="website" {{ request('type') === 'website' ? 'selected' : '' }}>Website</option>
                <option value="contact" {{ request('type') === 'contact' ? 'selected' : '' }}>Contact / vCard</option>
                <option value="whatsapp" {{ request('type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="wifi" {{ request('type') === 'wifi' ? 'selected' : '' }}>WiFi</option>
                <option value="business" {{ request('type') === 'business' ? 'selected' : '' }}>Business</option>
                <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Product</option>
                <option value="social" {{ request('type') === 'social' ? 'selected' : '' }}>Social</option>
                <option value="custom_landing" {{ request('type') === 'custom_landing' ? 'selected' : '' }}>Landing Page</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </form>

    <!-- Batch ZIP Form (Hidden) -->
    <form id="batchZipForm" action="{{ route('admin.qr-codes.batch-zip') }}" method="POST" style="display: none;">
        @csrf
        <div id="batchZipInputs"></div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onclick="toggleSelectAll(this)">
                    </th>
                    <th>QR Code</th>
                    <th>Public Identifier</th>
                    <th>Client Organization</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Scans</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qrCodes as $qr)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input qr-select-item" value="{{ $qr->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{ route('admin.qr-codes.show', $qr) }}" class="p-1 bg-light border rounded-3 d-inline-block text-center" style="width: 44px; height: 44px; overflow: hidden;">
                                    <i class="bi bi-qr-code fs-4 text-primary"></i>
                                </a>
                                <div>
                                    <a href="{{ route('admin.qr-codes.show', $qr) }}" class="fw-bold text-dark text-decoration-none d-block">
                                        {{ $qr->name }}
                                    </a>
                                    <small class="text-muted font-mono" style="font-size: 0.72rem;">Access: {{ $qr->access_code }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-code">{{ $qr->public_code }}</span>
                            <a href="{{ url('/q/' . $qr->public_code) }}" target="_blank" class="text-muted ms-1" title="Test Dynamic Route">
                                <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i>
                            </a>
                        </td>
                        <td>
                            @if($qr->client)
                                <a href="{{ route('admin.clients.show', $qr->client) }}" class="text-dark fw-semibold text-decoration-none">
                                    {{ $qr->client->company_name }}
                                </a>
                            @else
                                <span class="badge bg-light text-muted border">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $qr->type) }}</span>
                        </td>
                        <td>
                            @switch($qr->status)
                                @case('active')
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                    @break
                                @case('inactive')
                                    <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                    @break
                                @case('suspended')
                                    <span class="badge bg-warning-subtle text-warning">Suspended</span>
                                    @break
                                @case('archived')
                                    <span class="badge bg-dark-subtle text-dark">Archived</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="fw-bold">
                            {{ number_format($qr->scan_count) }}
                            @if($qr->last_scanned_at)
                                <small class="text-muted d-block fw-normal" style="font-size: 0.72rem;">{{ $qr->last_scanned_at->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Options
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.qr-codes.show', $qr) }}"><i class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.qr-codes.edit', $qr) }}"><i class="bi bi-sliders me-2 text-info"></i> Customize Content & Design</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header small text-uppercase">Download Format</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.qr-codes.download', [$qr, 'svg']) }}"><i class="bi bi-filetype-svg me-2 text-warning"></i> Vector SVG</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.qr-codes.download', [$qr, 'png']) }}"><i class="bi bi-filetype-png me-2 text-success"></i> High-Res PNG</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.qr-codes.download', [$qr, 'pdf']) }}"><i class="bi bi-filetype-pdf me-2 text-danger"></i> Printable PDF Card</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.qr-codes.destroy', $qr) }}" method="POST" onsubmit="return confirm('Move this QR code to trash?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Delete Code
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-qr-code fs-2 d-block mb-2"></i>
                            No dynamic QR codes found matching criteria.
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

@push('scripts')
<script>
function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.qr-select-item');
    checkboxes.forEach(cb => cb.checked = master.checked);
}

function submitBatchZip() {
    const selected = document.querySelectorAll('.qr-select-item:checked');
    if (selected.length === 0) {
        alert('Please select at least one QR code to bundle into a ZIP.');
        return;
    }
    const container = document.getElementById('batchZipInputs');
    container.innerHTML = '';
    selected.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'qr_ids[]';
        input.value = cb.value;
        container.appendChild(input);
    });
    document.getElementById('batchZipForm').submit();
}
</script>
@endpush
