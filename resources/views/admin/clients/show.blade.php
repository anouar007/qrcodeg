@extends('layouts.admin')

@section('title', $client->company_name)
@section('page_title', $client->company_name)
@section('page_subtitle', 'Organization profile, assigned QR codes, and access credentials')

@section('content')
<div class="row g-4">
    <!-- Left Column: Client Profile & Credentials -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white mb-4">
            <div class="text-center mb-4 pb-3 border-bottom">
                @if($client->logo)
                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded-3 border p-2 mb-3" style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-inline-flex align-items-center justify-content-center mb-3 fs-2" style="width: 80px; height: 80px;">
                        {{ substr($client->company_name, 0, 1) }}
                    </div>
                @endif
                <h4 class="fw-bold mb-1">{{ $client->company_name }}</h4>
                <div class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($client->status) }}
                </div>
            </div>

            <!-- Access Credentials Box -->
            <div class="p-3 bg-light rounded-3 border mb-4">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.72rem;">Client Access Code</small>
                <div class="font-mono fw-bold fs-5 text-primary my-1">{{ $client->access_code ?? 'None' }}</div>
                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <span>Status:</span>
                    @if($client->is_password_set)
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-shield-check"></i> Password Initialized</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i> Awaiting First Login</span>
                    @endif
                </div>

                <div class="mt-3 pt-2 border-top">
                    <form action="{{ route('admin.clients.reset-access', $client) }}" method="POST" onsubmit="return confirm('Generate a new access code? Client will need to set password again.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100 rounded-pill">
                            <i class="bi bi-arrow-repeat me-1"></i> Reset Access Code
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact & Details -->
            <div class="d-flex flex-column gap-2 small mb-4">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Primary Contact:</span>
                    <strong class="text-dark">{{ $client->contact_name }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Email:</span>
                    <strong class="text-dark">{{ $client->email }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Phone:</span>
                    <strong class="text-dark">{{ $client->phone ?: 'None' }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Location:</span>
                    <strong class="text-dark">{{ $client->city ?: '' }} {{ $client->country ?: 'N/A' }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Member Since:</span>
                    <strong class="text-dark">{{ $client->created_at->format('M d, Y') }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Last Portal Login:</span>
                    <strong class="text-dark">{{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Never' }}</strong>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-primary-custom btn-sm w-100">
                    <i class="bi bi-pencil me-1"></i> Edit Details
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Assigned QR Codes & Assignments -->
    <div class="col-lg-8">
        <!-- Quick Stats -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="card card-custom p-3 bg-white">
                    <span class="text-muted small fw-semibold">Assigned QR Codes</span>
                    <div class="fs-3 fw-bold text-dark mt-1">{{ $client->qrCodes->count() }}</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom p-3 bg-white">
                    <span class="text-muted small fw-semibold">Total Scans Across All QRs</span>
                    <div class="fs-3 fw-bold text-primary mt-1">{{ number_format($totalScans) }}</div>
                </div>
            </div>
        </div>

        <!-- Assign New QR Code Form -->
        @if($availableQrs->count() > 0)
            <div class="card card-custom p-3 bg-white mb-4 border-dashed border-2">
                <form action="{{ route('admin.clients.assign-qr', $client) }}" method="POST" class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                    @csrf
                    <div class="flex-grow-1">
                        <select name="qr_code_id" class="form-select form-select-sm" required>
                            <option value="">-- Select an unassigned QR Code to allocate to this client --</option>
                            @foreach($availableQrs as $uqr)
                                <option value="{{ $uqr->id }}">
                                    {{ $uqr->name }} ({{ $uqr->public_code }} - Type: {{ $uqr->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill text-nowrap px-3">
                        <i class="bi bi-plus-lg me-1"></i> Assign QR
                    </button>
                </form>
            </div>
        @endif

        <!-- Assigned QR Codes List -->
        <div class="card card-custom p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Assigned QR Codes</h5>
                <a href="{{ route('admin.qr-codes.create', ['client_id' => $client->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Generate New for Client
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light text-uppercase" style="font-size: 0.72rem;">
                        <tr>
                            <th>QR Name</th>
                            <th>Public Code</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Scans</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->qrCodes as $qr)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.qr-codes.show', $qr) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $qr->name }}
                                    </a>
                                </td>
                                <td><span class="badge-code">{{ $qr->public_code }}</span></td>
                                <td><span class="badge bg-light text-dark border">{{ $qr->type }}</span></td>
                                <td>
                                    <span class="badge {{ $qr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $qr->status }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ number_format($qr->scan_count) }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.qr-codes.show', $qr) }}" class="btn btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.qr-codes.edit', $qr) }}" class="btn btn-outline-secondary" title="Customize"><i class="bi bi-pencil"></i></a>
                                        <a href="{{ url('/q/' . $qr->public_code) }}" target="_blank" class="btn btn-outline-primary" title="Test Dynamic Scan"><i class="bi bi-box-arrow-up-right"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No QR codes currently assigned to this client.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity Logs for Client -->
        <div class="card card-custom p-4 bg-white">
            <h5 class="fw-bold mb-3">Client Audit Trail</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0 small">
                    <thead class="table-light text-uppercase" style="font-size: 0.72rem;">
                        <tr>
                            <th>Action</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr>
                                <td><code class="text-dark">{{ $log->action }}</code></td>
                                <td class="text-muted">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                                <td class="text-muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">No logged activity yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
