@extends('layouts.admin')

@section('title', 'Client Management')
@section('page_title', 'Client Organizations')
@section('page_subtitle', 'Manage client companies, access credentials, and assigned QR inventory')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <!-- Header with Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Registered Clients & Accounts</h5>
            <small class="text-muted">Total: {{ $clients->total() }} organizations</small>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary-custom rounded-pill">
            <i class="bi bi-building-add me-1"></i> Register New Client
        </a>
    </div>

    <!-- Search & Status filter -->
    <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search company, contact, email, or access code..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Search</button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </form>

    <!-- Clients Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th>Company / Client</th>
                    <th>Access Code</th>
                    <th>Location</th>
                    <th>Assigned QRs</th>
                    <th>Status</th>
                    <th>Last Active</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($client->logo)
                                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded border p-1" style="width: 40px; height: 40px; object-fit: contain;">
                                @else
                                    <div class="rounded-3 bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        {{ substr($client->company_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.clients.show', $client) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $client->company_name }}
                                    </a>
                                    <small class="text-muted d-block">{{ $client->contact_name }} &bull; {{ $client->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-access-code">{{ $client->access_code ?? 'None' }}</span>
                            @if($client->is_password_set)
                                <small class="text-success d-block" style="font-size: 0.72rem;"><i class="bi bi-shield-check"></i> Password Set</small>
                            @else
                                <small class="text-warning d-block" style="font-size: 0.72rem;"><i class="bi bi-hourglass-split"></i> Pending Setup</small>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $client->city ? $client->city . ', ' : '' }}{{ $client->country ?: 'Not specified' }}
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3 py-1">
                                {{ $client->qr_codes_count }} QR Code(s)
                            </span>
                        </td>
                        <td>
                            @if($client->status === 'active')
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Manage
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.clients.show', $client) }}"><i class="bi bi-eye me-2 text-primary"></i> View Profile & QRs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.clients.edit', $client) }}"><i class="bi bi-pencil me-2 text-info"></i> Edit Details</a></li>
                                    <li>
                                        <form action="{{ route('admin.clients.reset-access', $client) }}" method="POST" onsubmit="return confirm('Reset access code? The client will receive a new code and must set password again.');">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-key me-2 text-warning"></i> Reset Access Code
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-power me-2 {{ $client->status === 'active' ? 'text-secondary' : 'text-success' }}"></i>
                                                {{ $client->status === 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client organization?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Delete Client
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-buildings fs-2 d-block mb-2"></i>
                            No clients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</div>
@endsection
