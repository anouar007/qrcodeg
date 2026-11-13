@extends('layouts.app')

@section('title', 'Client Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Clients</li>
@endsection

@push('styles')
<style>
    .clients-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 60%, #eef2ff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }
    .clients-hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.875rem;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.25);
        flex-shrink: 0;
    }
    .client-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1rem 1.15rem;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        height: 100%;
    }
    .client-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }
    .client-kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .client-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }
    .clients-table thead th {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .clients-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .clients-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .client-avatar-box {
        width: 44px;
        height: 44px;
        border-radius: 0.75rem;
        object-fit: contain;
        flex-shrink: 0;
    }
    .copy-code-btn {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        border-radius: 0.45rem;
        padding: 0.15rem 0.4rem;
        font-size: 0.72rem;
        line-height: 1;
        transition: all 0.15s ease;
    }
    .copy-code-btn:hover {
        background: #eef2ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }
</style>
@endpush

@section('content')
<!-- Executive Hero Header -->
<div class="clients-hero-card p-3 p-md-4 mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="clients-hero-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h4 class="fw-extrabold mb-0 text-dark">Client Management</h4>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-mono small px-2 py-1">Section Clients</span>
                </div>
                <p class="text-muted small mb-0">Manage partner accounts, projects, access credentials, and connected IoT assets.</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.clients.export', request()->query()) }}" class="btn btn-sm btn-light border rounded-pill px-3 py-2 fw-semibold shadow-xs">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#quickClientModal">
                <i class="bi bi-lightning-charge me-1"></i> Quick Add
            </button>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-sm btn-primary-custom rounded-pill px-3 py-2 fw-semibold shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> New Client
            </a>
        </div>
    </div>
</div>

<!-- KPI Summary Strip -->
@php
    $pageCollection = $clients->getCollection();
    $activeOnPage = $pageCollection->where('status', 'active')->count();
    $assetsOnPage = $pageCollection->sum(fn ($c) => ($c->dynamic_qr_codes_count ?? 0) + ($c->qr_codes_count ?? 0) + ($c->digital_cards_count ?? 0) + ($c->nfc_tags_count ?? 0));
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="client-kpi-card d-flex align-items-center gap-3">
            <div class="client-kpi-icon bg-primary-subtle text-primary">
                <i class="bi bi-buildings"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Clients</div>
                <div class="fs-4 fw-extrabold text-dark lh-1 mt-1">{{ $clients->total() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="client-kpi-card d-flex align-items-center gap-3">
            <div class="client-kpi-icon bg-success-subtle text-success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Active (Page)</div>
                <div class="fs-4 fw-extrabold text-dark lh-1 mt-1">{{ $activeOnPage }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="client-kpi-card d-flex align-items-center gap-3">
            <div class="client-kpi-icon bg-info-subtle text-info">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Cities Covered</div>
                <div class="fs-4 fw-extrabold text-dark lh-1 mt-1">{{ $cities->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="client-kpi-card d-flex align-items-center gap-3">
            <div class="client-kpi-icon bg-warning-subtle text-warning">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Assets (Page)</div>
                <div class="fs-4 fw-extrabold text-dark lh-1 mt-1">{{ $assetsOnPage }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="client-filter-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-2 align-items-center">
        <!-- Search input -->
        <div class="col-lg-5 col-md-6">
            <div class="input-icon-group">
                <i class="bi bi-search input-icon text-muted"></i>
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-custom rounded-3"
                    placeholder="Search by name, project, city, phone, email, or access code..."
                    value="{{ request('search') }}"
                >
            </div>
        </div>

        <!-- City filter -->
        <div class="col-lg-3 col-md-3 col-sm-6">
            <select name="city" class="form-select form-control-custom rounded-3">
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
            <select name="status" class="form-select form-control-custom rounded-3">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="col-lg-2 col-md-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary-custom rounded-3 w-100 fw-semibold">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'city', 'status']))
                <a href="{{ route('admin.clients.index') }}" class="btn btn-light border rounded-3" title="Reset Filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Clients Data Table -->
<x-card title="Clients Directory" :subtitle="'Showing ' . $clients->total() . ' clients registered across active projects'" icon="bi-people">
    <x-slot:actions>
        <span class="badge bg-light text-muted border rounded-pill font-mono small px-2 py-1">Page {{ $clients->currentPage() }} of {{ $clients->lastPage() ?: 1 }}</span>
    </x-slot:actions>

    <div class="table-responsive">
        <table class="table clients-table align-middle mb-0">
            <thead class="text-uppercase tracking-wider">
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
                                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="client-avatar-box border p-1 bg-white">
                                @else
                                    <div class="client-avatar-box bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="font-size: 1.05rem;">
                                        {{ strtoupper(substr($client->name ?: ($client->contact_name ?: 'C'), 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.clients.show', $client) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $client->name ?: $client->contact_name }}
                                    </a>
                                    @if($client->project_name)
                                        <div class="mt-1">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                                <i class="bi bi-folder2-open me-1"></i>{{ $client->project_name }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($client->company_name)
                                        <div class="small text-muted mt-1">{{ $client->company_name }}</div>
                                    @endif
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
                            <div class="d-inline-flex align-items-center gap-1">
                                <span class="badge bg-light text-dark border font-mono px-2 py-1 rounded-2">
                                    {{ $client->access_code ?? 'Not configured' }}
                                </span>
                                @if($client->access_code)
                                    <button type="button" class="copy-code-btn" title="Copy Access Code" onclick="copyAccessCode('{{ $client->access_code }}', this)">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                @endif
                            </div>
                            @if($client->is_password_set)
                                <small class="text-success d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-check"></i> Password active</small>
                            @else
                                <small class="text-warning d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-hourglass-split"></i> Awaiting 1st login</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;" title="Dynamic & Static QR Codes">
                                    <i class="bi bi-qr-code me-1"></i>{{ ($client->dynamic_qr_codes_count ?? 0) + ($client->qr_codes_count ?? 0) }} QR
                                </span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;" title="Digital Business Cards">
                                    <i class="bi bi-person-vcard me-1"></i>{{ $client->digital_cards_count ?? 0 }} Cards
                                </span>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;" title="Physical NFC Tags">
                                    <i class="bi bi-broadcast me-1"></i>{{ $client->nfc_tags_count ?? 0 }} NFC
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <x-badge :status="$client->status" />
                        </td>
                        <td class="text-muted small">
                            {{ $client->last_login_at ? $client->last_login_at->diffForHumans() : ($client->created_at ? $client->created_at->diffForHumans() : 'Recently') }}
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-light border rounded-pill px-2" title="View Client">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

@push('scripts')
<script>
    function copyAccessCode(code, btn) {
        if (!navigator.clipboard) {
            return;
        }
        navigator.clipboard.writeText(code).then(() => {
            const icon = btn.querySelector('i');
            if (!icon) {
                return;
            }
            icon.className = 'bi bi-check2 text-success';
            setTimeout(() => {
                icon.className = 'bi bi-clipboard';
            }, 1500);
        });
    }
</script>
@endpush
