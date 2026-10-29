@extends('layouts.app')

@section('title', 'Client Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Clients</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2 border-bottom">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-extrabold mb-0 text-dark">Client Management</h4>
            <span class="badge bg-primary-subtle text-primary font-mono small">Section Clients</span>
        </div>
        <p class="text-muted small mb-0">Manage partner accounts, projects, access credentials, and connected IoT assets.</p>
    </div>

    <!-- Quick Actions -->
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.clients.export', request()->query()) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-xs">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#quickClientModal">
            <i class="bi bi-lightning-charge me-1"></i> Quick Add
        </button>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-sm btn-primary-custom rounded-pill px-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> New Client
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-custom p-3 bg-white mb-4 border">
    <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-2 align-items-center">
        <!-- Search input -->
        <div class="col-lg-5 col-md-6">
            <div class="input-icon-group">
                <i class="bi bi-search input-icon text-muted"></i>
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-custom"
                    placeholder="Search by name, project, city, phone, email, or access code..."
                    value="{{ request('search') }}"
                >
            </div>
        </div>

        <!-- City filter -->
        <div class="col-lg-3 col-md-3 col-sm-6">
            <select name="city" class="form-select form-control-custom">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status filter -->
        <div class="col-lg-2 col-md-3 col-sm-6">
            <select name="status" class="form-select form-control-custom">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="col-lg-2 col-md-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary-custom w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'city', 'status']))
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Clients Data Table -->
<x-card title="Clients Directory" :subtitle="'Showing ' . $clients->total() . ' clients registered across active projects'" icon="bi-people">
    <x-slot:actions>
        <span class="badge bg-light text-muted border font-mono small">Page {{ $clients->currentPage() }} of {{ $clients->lastPage() ?: 1 }}</span>
    </x-slot:actions>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th class="ps-3 py-3">Client & Project</th>
                    <th>City & Phone</th>
                    <th>Access Code</th>
                    <th class="text-center">Connected Assets</th>
                    <th class="text-center">Status</th>
                    <th>Last Activity</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="ps-3 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($client->logo)
                                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded border p-1 bg-white" style="width: 44px; height: 44px; object-fit: contain;">
                                @else
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.05rem;">
                                        {{ strtoupper(substr($client->name ?: ($client->contact_name ?: 'C'), 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.clients.show', $client) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $client->name ?: $client->contact_name }}
                                    </a>
                                    @if($client->project_name)
                                        <div class="small text-primary fw-semibold">
                                            <i class="bi bi-folder2-open me-1"></i>{{ $client->project_name }}
                                        </div>
                                    @endif
                                    <div class="small text-muted">{{ $client->company_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @if($client->city)
                                    <span class="small fw-semibold text-dark"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $client->city }}</span>
                                @else
                                    <span class="small text-muted fst-italic">No city</span>
                                @endif
                                @if($client->phone)
                                    <span class="small text-muted font-mono"><i class="bi bi-telephone text-success me-1"></i>{{ $client->phone }}</span>
                                @endif
                                <span class="small text-muted text-truncate" style="max-width: 180px;">{{ $client->email }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-dark-subtle text-dark border font-mono px-2 py-1">
                                {{ $client->access_code ?? 'Not configured' }}
                            </span>
                            @if($client->is_password_set)
                                <small class="text-success d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-check"></i> Password active</small>
                            @else
                                <small class="text-warning d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-hourglass-split"></i> Awaiting 1st login</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <span class="badge bg-primary-subtle text-primary border" title="Dynamic VP QR Codes">
                                    <i class="bi bi-qr-code me-1"></i>{{ ($client->dynamic_qr_codes_count ?? 0) + ($client->qr_codes_count ?? 0) }} QR
                                </span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-info-subtle text-info border" style="font-size: 0.7rem;" title="Digital Business Cards">
                                        <i class="bi bi-person-vcard me-1"></i>{{ $client->digital_cards_count ?? 0 }}
                                    </span>
                                    <span class="badge bg-warning-subtle text-warning border" style="font-size: 0.7rem;" title="Physical NFC Tags">
                                        <i class="bi bi-broadcast me-1"></i>{{ $client->nfc_tags_count ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <x-badge :status="$client->status" />
                        </td>
                        <td class="text-muted small">
                            {{ $client->last_login_at ? $client->last_login_at->diffForHumans() : ($client->created_at ? $client->created_at->diffForHumans() : 'Recently') }}
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.clients.show', $client) }}">
                                            <i class="bi bi-clock-history me-2 text-primary"></i> Timeline & Assets
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.clients.edit', $client) }}">
                                            <i class="bi bi-pencil me-2 text-info"></i> Edit Client
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.clients.reset-access', $client) }}" method="POST" onsubmit="return confirm('Regenerate a new access code? Client must reset password.');">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-key me-2 text-warning"></i> Reset Access Code
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item {{ $client->status === 'active' ? 'text-danger' : 'text-success' }}">
                                                <i class="bi {{ $client->status === 'active' ? 'bi-pause-circle text-danger' : 'bi-play-circle text-success' }} me-2"></i>
                                                {{ $client->status === 'active' ? 'Deactivate Account' : 'Activate Account' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client? Associated assets will be unlinked.');">
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
                            <i class="bi bi-people display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h5 class="fw-bold">No clients found</h5>
                            <p class="small text-muted mb-3">Get started by creating your first client profile with project name, city, and phone number.</p>
                            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary-custom">
                                <i class="bi bi-person-plus-fill me-1"></i> New Client
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <span class="small text-muted">Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} clients</span>
                {{ $clients->links() }}
            </div>
        </x-slot:footer>
    @endif
</x-card>

<!-- Modal: Quick Add Client -->
<x-modal id="quickClientModal" title="Quick Add Client Profile" icon="bi-person-plus" size="lg">
    <form id="quickClientForm" action="{{ route('admin.clients.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <x-input
                    name="name"
                    label="Client Name"
                    placeholder="e.g. Sara Mansouri"
                    required
                    icon="bi-person"
                />
            </div>
            <div class="col-md-6">
                <x-input
                    name="project_name"
                    label="Project Name"
                    placeholder="e.g. Luxury Resort Rebranding"
                    icon="bi-folder2-open"
                />
            </div>
            <div class="col-md-6">
                <x-input
                    name="city"
                    label="City"
                    placeholder="e.g. Marrakech, Casablanca, Paris"
                    icon="bi-geo-alt"
                />
            </div>
            <div class="col-md-6">
                <x-input
                    name="phone"
                    label="Phone (International/Local)"
                    placeholder="+212600112233 or 0600112233"
                    icon="bi-telephone"
                    help="Accepts formats like +212600112233, +1-555-0199, or 0612345678."
                />
            </div>
            <div class="col-md-6">
                <x-select
                    name="status"
                    label="Status"
                    :options="['active' => 'Active', 'inactive' => 'Inactive']"
                    selected="active"
                    required
                />
            </div>
            <div class="col-md-6">
                <x-input
                    name="access_code"
                    label="Access Code"
                    :value="$suggestedCode"
                    placeholder="CL-XXXXXX"
                    icon="bi-key"
                    help="Generated unique access code for the client portal."
                />
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="quick_notes" class="form-label fw-semibold text-dark small mb-1">Internal Notes</label>
                    <textarea name="notes" id="quick_notes" rows="2" class="form-control form-control-custom" placeholder="Optional background details or contract notes..."></textarea>
                </div>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="quickClientForm" class="btn btn-sm btn-primary-custom px-3">
            <i class="bi bi-check2-circle me-1"></i> Save Client
        </button>
    </x-slot:footer>
</x-modal>
@endsection
