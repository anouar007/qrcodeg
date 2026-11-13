@extends('layouts.app')

@section('title', 'Client Profile: ' . ($client->name ?: $client->contact_name))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $client->name ?: $client->contact_name }}</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2 border-bottom">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-extrabold mb-0 text-dark">{{ $client->name ?: $client->contact_name }}</h4>
            @if($client->project_name)
                <span class="badge bg-primary-subtle text-primary border font-mono small">
                    <i class="bi bi-folder2-open me-1"></i>{{ $client->project_name }}
                </span>
            @endif
            <x-badge :status="$client->status" />
        </div>
        <p class="text-muted small mb-0">Client profile, connected asset ecosystem, and complete timeline audit trail.</p>
    </div>

    <!-- Actions -->
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs">
            <i class="bi bi-pencil me-1"></i> Edit Profile
        </a>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-xs">
            <i class="bi bi-arrow-left me-1"></i> Back to Clients
        </a>
    </div>
</div>

<!-- 1. Key Stat Cards Row -->
<div class="row g-3 mb-4">
    <!-- Total Cards Assigned -->
    <div class="col-md-3">
        <x-stat-card
            title="Digital Business Cards"
            :value="$totalDigitalCards"
            icon="bi-person-vcard"
            :trend="number_format($totalCardViews) . ' views'"
            trendLabel="cumulative views"
            color="info"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">Cartes Visite</span>
        </x-stat-card>
    </div>

    <!-- Dynamic QR Codes Assigned -->
    <div class="col-md-3">
        <x-stat-card
            title="Dynamic QR Codes"
            :value="$totalQrCodesAssigned"
            icon="bi-qr-code-scan"
            :trend="number_format($totalQrScans) . ' scans'"
            trendLabel="total scans logged"
            color="primary"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">QR Codes</span>
        </x-stat-card>
    </div>

    <!-- Active NFC Tags Assigned -->
    <div class="col-md-3">
        <x-stat-card
            title="NFC Tags Assigned"
            :value="$totalNfcTags"
            icon="bi-broadcast-pin"
            :trend="number_format($totalNfcTaps) . ' taps'"
            trendLabel="total taps logged"
            color="warning"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">Tags NFC</span>
        </x-stat-card>
    </div>

    <!-- Loyalty Cards Generated vs Quota -->
    <div class="col-md-3">
        <x-stat-card
            title="Cartes Fidélité (Quota)"
            :value="$totalLoyaltyCards . ' / ' . $loyaltyLimit"
            icon="bi-award"
            :trend="($loyaltyLimit - $totalLoyaltyCards) . ' restantes'"
            trendLabel="Quota admin autorisé"
            color="success"
        >
            <span class="badge {{ $client->loyalty_enabled ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border font-mono small mt-2">
                {{ $client->loyalty_enabled ? 'Programme Actif' : 'Programme Suspendu' }}
            </span>
        </x-stat-card>
    </div>
</div>

<!-- 2. Main Content Grid -->
<div class="row g-4">
    <!-- Left Column: Profile Card & Access Credentials -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white mb-4 border">
            <div class="text-center mb-4 pb-3 border-bottom">
                @if($client->logo)
                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded-3 border p-2 mb-3 bg-white shadow-xs" style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-inline-flex align-items-center justify-content-center mb-3 fs-2 shadow-xs" style="width: 80px; height: 80px;">
                        {{ strtoupper(substr($client->name ?: ($client->contact_name ?: 'C'), 0, 1)) }}
                    </div>
                @endif
                <h5 class="fw-bold mb-1 text-dark">{{ $client->name ?: $client->contact_name }}</h5>
                @if($client->project_name)
                    <div class="text-primary small fw-semibold mb-1">
                        <i class="bi bi-folder2-open me-1"></i>Projet: {{ $client->project_name }}
                    </div>
                @endif
                @if($client->company_name && $client->company_name !== $client->name)
                    <div class="text-muted small mb-2">{{ $client->company_name }}</div>
                @endif
                <div>
                    <x-badge :status="$client->status" />
                </div>
            </div>

            <!-- Client Portal Credentials Box -->
            <div class="p-3 bg-light rounded-3 border mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-muted text-uppercase fw-bold font-mono" style="font-size: 0.72rem;">Portal Access Code (Password)</span>
                    <i class="bi bi-key text-primary"></i>
                </div>
                <div class="font-mono fw-extrabold fs-5 text-primary my-1">{{ $client->access_code ?? 'Not configured' }}</div>
                <div class="small text-muted mb-2">
                    Login Email: <strong class="text-dark">{{ $client->email }}</strong>
                </div>
                @if($client->access_code && $client->email)
                    <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill mb-2" onclick="copyClientCredentials('{{ route('login') }}', '{{ $client->email }}', '{{ $client->access_code }}', this)">
                        <i class="bi bi-clipboard me-1"></i> Copy Login &amp; Access Code
                    </button>
                @endif
                <div class="d-flex justify-content-between align-items-center small text-muted pt-1">
                    <span>Password status:</span>
                    @if($client->is_password_set)
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-shield-check"></i> Active</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i> Awaiting 1st login</span>
                    @endif
                </div>

                @if(session('generated_credentials'))
                    @php $creds = session('generated_credentials'); @endphp
                    <div class="alert alert-success p-2.5 mt-3 mb-0 small rounded-3 border-success-subtle">
                        <div class="fw-bold mb-1"><i class="bi bi-check-circle-fill me-1"></i>Credentials Ready to Share</div>
                        <div class="font-mono" style="font-size: 0.75rem;">
                            <div><strong>URL:</strong> {{ $creds['login_url'] }}</div>
                            <div><strong>Login:</strong> {{ $creds['email'] }}</div>
                            <div><strong>Password:</strong> {{ $creds['password'] }}</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-success w-100 rounded-pill mt-2" onclick="copyClientCredentials('{{ $creds['login_url'] }}', '{{ $creds['email'] }}', '{{ $creds['password'] }}', this)">
                            <i class="bi bi-clipboard me-1"></i> Copy Login &amp; Password
                        </button>
                    </div>
                @endif

                <div class="mt-3 pt-2 border-top d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary-custom btn-sm w-100 rounded-pill" data-bs-toggle="collapse" data-bs-target="#setCredentialsCollapse" aria-expanded="{{ $errors->has('email') || $errors->has('password') ? 'true' : 'false' }}">
                        <i class="bi bi-shield-lock me-1"></i> Set Login &amp; Password
                    </button>

                    <div class="collapse {{ $errors->has('email') || $errors->has('password') ? 'show' : '' }}" id="setCredentialsCollapse">
                        <form action="{{ route('admin.clients.set-credentials', $client) }}" method="POST" class="p-2.5 bg-white rounded-3 border mt-1">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold mb-1">Login Email</label>
                                <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-semibold mb-0">Password</label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" style="font-size: 0.72rem;" onclick="generateClientPassword()">
                                        <i class="bi bi-magic me-1"></i>Generate
                                    </button>
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="password" id="adminClientPasswordInput" class="form-control font-mono @error('password') is-invalid @enderror" placeholder="Min. 6 characters" required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill">
                                <i class="bi bi-check2 me-1"></i> Save &amp; Generate Share Box
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('admin.clients.reset-access', $client) }}" method="POST" onsubmit="return confirm('Regenerate access code? The client will be required to set a new password on their next login.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100 rounded-pill">
                            <i class="bi bi-arrow-repeat me-1"></i> Reset Access Code
                        </button>
                    </form>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="d-flex flex-column gap-2 small mb-4">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Contact Name:</span>
                    <strong class="text-dark">{{ $client->contact_name ?: $client->name }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Project:</span>
                    <strong class="text-primary">{{ $client->project_name ?: 'None' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">City:</span>
                    <strong class="text-dark">{{ $client->city ?: 'Not specified' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Phone:</span>
                    <strong class="text-dark font-mono">{{ $client->phone ?: 'Not specified' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Email:</span>
                    <strong class="text-dark text-truncate" style="max-width: 170px;">{{ $client->email }}</strong>
                </div>
                @if($client->address)
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Address:</span>
                        <span class="text-dark text-end">{{ $client->address }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Registered:</span>
                    <span class="text-dark">{{ $client->created_at ? $client->created_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Last Login:</span>
                    <span class="text-dark">{{ $client->last_login_at ? $client->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>

            @if($client->notes)
                <div class="p-3 bg-light rounded-3 small border mb-4">
                    <div class="fw-semibold text-muted mb-1"><i class="bi bi-sticky me-1"></i>Internal Notes:</div>
                    <p class="mb-0 text-dark">{{ $client->notes }}</p>
                </div>
            @endif

            <div class="d-flex gap-2">
                <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-pencil me-1"></i> Edit Client
                </a>
                <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                        {{ $client->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Tabbed Assets & Timeline Interface -->
    <div class="col-lg-8">
        <div class="card card-custom border bg-white">
            <!-- Navigation Tabs Header -->
            <div class="card-header bg-white border-bottom pt-3 px-3">
                <ul class="nav nav-pills" id="clientTabs" role="tablist">
                    <!-- Tab 1: Assigned Digital Cards -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold small py-2 px-3" id="cards-tab" data-bs-toggle="tab" data-bs-target="#cards-pane" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-person-vcard me-1 text-info"></i> Cartes Visite ({{ $client->digitalCards->count() }})
                        </button>
                    </li>

                    <!-- Tab 2: Assigned Dynamic QR Codes & NFCs -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small py-2 px-3" id="assets-tab" data-bs-toggle="tab" data-bs-target="#assets-pane" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-qr-code-scan me-1 text-primary"></i> QR Codes & Tags NFC ({{ $totalQrCodesAssigned + $client->nfcTags->count() }})
                        </button>
                    </li>

                    <!-- Tab 3: Loyalty Cards -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small py-2 px-3" id="loyalty-tab" data-bs-toggle="tab" data-bs-target="#loyalty-pane" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-award me-1 text-purple"></i> Cartes Fidélité ({{ $totalLoyaltyCards }} / {{ $loyaltyLimit }})
                        </button>
                    </li>

                    <!-- Tab 4: Complete Historic Activity Timeline -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small py-2 px-3" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-clock-history me-1 text-warning"></i> Historique Complet ({{ $clientHistories->count() ?: $historicEvents->count() }})
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-3">
                <div class="tab-content" id="clientTabsContent">

                    <!-- ======================================================== -->
                    <!-- TAB 1: ASSIGNED DIGITAL BUSINESS CARDS -->
                    <!-- ======================================================== -->
                    <div class="tab-pane fade show active" id="cards-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Digital Business Cards</h6>
                                <small class="text-muted">Smart contact profiles with live vCard downloads and custom themes</small>
                            </div>
                            <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                <i class="bi bi-plus-lg me-1"></i> New Digital Card
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Card Holder / Title</th>
                                        <th>Slug URL</th>
                                        <th>Template</th>
                                        <th>Views</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->digitalCards as $card)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $card->title ?: ($card->full_name ?: $card->name) }}</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">{{ $card->job_title ?: 'Digital Profile' }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="badge bg-light text-primary border font-mono text-decoration-none">
                                                    /c/{{ $card->slug }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info text-capitalize">{{ $card->template_id ?: ($card->template ?: 'Modern') }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold font-mono">{{ number_format($card->views_count) }}</span>
                                            </td>
                                            <td>
                                                <x-badge :status="$card->status" />
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.digital-cards.edit', $card) }}" class="btn btn-outline-secondary" title="Edit Card"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{ route('digital-cards.public.show', $card->slug) }}" target="_blank" class="btn btn-outline-info" title="Preview Public Page"><i class="bi bi-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="bi bi-person-vcard fs-3 d-block mb-1 opacity-50"></i>
                                                No digital business cards created for this client yet.
                                                <div class="mt-2">
                                                    <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-plus-lg me-1"></i> Create First Digital Card
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- TAB 2: ASSIGNED DYNAMIC QR CODES & NFCs -->
                    <!-- ======================================================== -->
                    <div class="tab-pane fade" id="assets-pane" role="tabpanel">
                        <!-- Dynamic QR Codes Subsection -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-qr-code me-1 text-primary"></i> Dynamic VP QR Codes</h6>
                                    <small class="text-muted">Configured dynamic redirect sequences with scan telemetry</small>
                                </div>
                                <a href="{{ route('admin.qr-codes.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-plus-lg me-1"></i> New Dynamic QR
                                </a>
                            </div>

                            @if($availableQrs->count() > 0)
                                <div class="p-2 bg-light rounded-3 border mb-3">
                                    <form action="{{ route('admin.clients.assign-qr', $client) }}" method="POST" class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                                        @csrf
                                        <div class="flex-grow-1">
                                            <select name="qr_code_id" class="form-select form-select-sm" required>
                                                <option value="">-- Assign Unallocated QR Code --</option>
                                                @foreach($availableQrs as $uqr)
                                                    <option value="{{ $uqr->id }}">
                                                        {{ $uqr->name }} ({{ $uqr->public_code }} - Type: {{ $uqr->type }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-dark btn-sm rounded-pill text-nowrap px-3">
                                            <i class="bi bi-plus-lg me-1"></i> Assign to Client
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Code</th>
                                            <th>Label / Target URL</th>
                                            <th>Template</th>
                                            <th>Scans</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic QR Codes (VP Sequence) -->
                                        @foreach($client->dynamicQrCodes as $dqr)
                                            <tr>
                                                <td>
                                                    <code class="badge-code font-mono text-primary fw-bold">{{ $dqr->code }}</code>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $dqr->label }}</div>
                                                    <a href="{{ $dqr->target_url }}" target="_blank" class="small text-muted text-truncate d-block text-decoration-none" style="max-width: 200px;">
                                                        {{ $dqr->target_url }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-secondary border font-mono small">
                                                        {{ ucfirst(str_replace('_', ' ', $dqr->template_type)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold font-mono">{{ number_format($dqr->scans_count) }}</span>
                                                </td>
                                                <td>
                                                    <x-badge :status="$dqr->status" />
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('qr.redirect', ['code' => $dqr->code]) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Test Redirect">
                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                        </a>
                                                        <a href="{{ route('admin.dynamic-qrs.pdf', ['dynamicQr' => $dqr->id, 'template' => $dqr->template_type]) }}" class="btn btn-sm btn-outline-danger" title="Download Printable Card PDF">
                                                            <i class="bi bi-file-earmark-pdf"></i>
                                                        </a>
                                                        <a href="{{ route('admin.dynamic-qrs.show', $dqr) }}" class="btn btn-sm btn-outline-secondary" title="View & Manage Destination">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        <!-- Legacy QR Codes -->
                                        @foreach($client->qrCodes as $lqr)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-dark-subtle text-dark border font-mono">{{ $lqr->public_code }}</span>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $lqr->name }}</div>
                                                    <small class="text-muted">{{ $lqr->destination_url ?: 'Dynamic Template' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $lqr->type }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold font-mono">{{ number_format($lqr->scan_count) }}</span>
                                                </td>
                                                <td>
                                                    <x-badge :status="$lqr->status" />
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.qr-codes.show', $lqr) }}" class="btn btn-outline-secondary" title="Details"><i class="bi bi-eye"></i></a>
                                                        <a href="{{ url('/q/' . $lqr->public_code) }}" target="_blank" class="btn btn-outline-primary" title="Test Scan"><i class="bi bi-box-arrow-up-right"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if($client->dynamicQrCodes->isEmpty() && $client->qrCodes->isEmpty())
                                            <tr>
                                                <td colspan="6" class="text-center py-3 text-muted">
                                                    No QR codes assigned to this client yet.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Physical NFC Tags Subsection -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-broadcast-pin me-1 text-warning"></i> Assigned Physical NFC Tags</h6>
                                    <small class="text-muted">Touch cards and physical venue touchpoints</small>
                                </div>
                                <a href="{{ route('admin.nfc-tags.create') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                    <i class="bi bi-plus-lg me-1"></i> New NFC Tag
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tag Label</th>
                                            <th>Tag UID</th>
                                            <th>Destination Target</th>
                                            <th>Taps</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($client->nfcTags as $tag)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $tag->label ?: $tag->name }}</div>
                                                </td>
                                                <td>
                                                    <code class="badge-code font-mono text-dark">{{ $tag->tag_uid }}</code>
                                                </td>
                                                <td>
                                                    <span class="small text-muted text-truncate d-block" style="max-width: 220px;">
                                                        {{ $tag->destination_url ?: ($tag->target_type ? ucfirst(str_replace('_', ' ', $tag->target_type)) : 'Direct Link') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold font-mono">{{ number_format($tag->scans_count ?: $tag->tap_count) }}</span>
                                                </td>
                                                <td>
                                                    <x-badge :status="$tag->status" />
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('nfc.redirect', ['id_or_uid' => $tag->tag_uid ?: $tag->id]) }}" target="_blank" class="btn btn-outline-primary" title="Test Tap Redirect"><i class="bi bi-box-arrow-up-right"></i></a>
                                                        <a href="{{ route('admin.nfc-tags.show', $tag) }}" class="btn btn-outline-secondary" title="Details & Telemetry"><i class="bi bi-graph-up"></i></a>
                                                        <a href="{{ route('admin.nfc-tags.edit', $tag) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-3 text-muted">
                                                    No physical NFC tags mapped to this client.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- TAB 3: LOYALTY CARDS (CLIENT PROGRAM) -->
                    <!-- ======================================================== -->
                    <div class="tab-pane fade" id="loyalty-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Cartes de Fidélité Générées</h6>
                                <small class="text-muted">Quota attribué : <strong>{{ $totalLoyaltyCards }}</strong> / <strong>{{ $loyaltyLimit }}</strong> cartes ({{ $loyaltyLimit - $totalLoyaltyCards }} disponibles)</small>
                            </div>
                            <span class="badge {{ $client->loyalty_enabled ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border px-3 py-2 rounded-pill font-mono">
                                <i class="bi {{ $client->loyalty_enabled ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                {{ $client->loyalty_enabled ? 'Programme Autorisé' : 'Programme Suspendu' }}
                            </span>
                        </div>

                        <div class="card card-custom border shadow-xs overflow-hidden">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Code Carte</th>
                                            <th>Client Final</th>
                                            <th>Contact</th>
                                            <th>Format</th>
                                            <th>Progression Tampons</th>
                                            <th>Récompenses</th>
                                            <th>Statut</th>
                                            <th class="text-end">Pass Public</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($client->loyaltyCards as $card)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-dark font-mono">{{ $card->card_code }}</span>
                                                    @if($card->nfc_uid)
                                                        <span class="d-block text-muted font-mono" style="font-size: 0.72rem;">NFC: {{ $card->nfc_uid }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ $card->customer_name }}</span>
                                                </td>
                                                <td>
                                                    <div class="small text-muted">
                                                        @if($card->customer_phone) <div><i class="bi bi-telephone me-1"></i>{{ $card->customer_phone }}</div> @endif
                                                        @if($card->customer_email) <div><i class="bi bi-envelope me-1"></i>{{ $card->customer_email }}</div> @endif
                                                        @if(!$card->customer_phone && !$card->customer_email) <span class="text-muted italic">—</span> @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($card->card_type === 'both')
                                                        <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-qr-code me-1"></i>QR + <i class="bi bi-broadcast ms-1 me-1"></i>NFC</span>
                                                    @elseif($card->card_type === 'nfc')
                                                        <span class="badge bg-warning-subtle text-warning border"><i class="bi bi-broadcast me-1"></i>NFC Tag</span>
                                                    @else
                                                        <span class="badge bg-info-subtle text-info border"><i class="bi bi-qr-code me-1"></i>QR Code</span>
                                                    @endif
                                                </td>
                                                <td style="min-width: 140px;">
                                                    <div class="d-flex align-items-center justify-content-between small mb-1">
                                                        <span class="fw-bold text-dark">{{ $card->stamps_count }} / {{ $card->max_stamps }}</span>
                                                        <span class="text-muted">{{ $card->progressPercentage() }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $card->progressPercentage() }}%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-purple-subtle text-purple font-mono">{{ $card->rewards_redeemed_count }} réclamée(s)</span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $card->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} border">
                                                        {{ ucfirst($card->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('loyalty.public.show', ['card_code' => $card->card_code]) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Voir le Pass Client">
                                                        <i class="bi bi-box-arrow-up-right me-1"></i> Pass
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="bi bi-award fs-3 d-block mb-1 text-muted opacity-50"></i>
                                                    Aucune carte fidélité n'a encore été générée par ce client.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- TAB 4: COMPLETE HISTORIC ACTIVITY TIMELINE -->
                    <!-- ======================================================== -->
                    <div class="tab-pane fade" id="history-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3 pt-1">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Historic Activity Timeline</h6>
                                <small class="text-muted">Audit trail of all client events, asset updates, and redirection events</small>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border font-mono">Historique Complet</span>
                        </div>

                        <!-- Chronological Timeline -->
                        <div class="timeline position-relative ps-4 border-start border-2 ms-2 my-4">
                            <!-- A. ClientHistory entries (Primary) -->
                            @forelse($clientHistories as $history)
                                <div class="position-relative mb-4">
                                    <!-- Node Indicator -->
                                    <div class="position-absolute rounded-circle bg-primary" style="width: 12px; height: 12px; left: -25px; top: 4px; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);"></div>

                                    <div class="card card-custom p-3 bg-white border">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary-subtle text-primary border font-mono small text-uppercase">
                                                    {{ str_replace('_', ' ', $history->action_type) }}
                                                </span>
                                                <span class="small fw-semibold text-dark">
                                                    <i class="bi bi-person me-1 text-muted"></i>{{ $history->performer?->name ?? 'System' }}
                                                </span>
                                            </div>
                                            <div class="text-sm-end text-muted small font-mono" style="font-size: 0.75rem;">
                                                <span>{{ $history->created_at?->format('d/m/Y H:i') }}</span>
                                                <span class="text-secondary ms-1">({{ $history->created_at?->diffForHumans() }})</span>
                                            </div>
                                        </div>

                                        <p class="mb-1 text-dark small fw-medium">
                                            {{ $history->description }}
                                        </p>

                                        @if(!empty($history->metadata))
                                            <div class="mt-2 p-2 bg-light rounded small font-mono text-muted border" style="font-size: 0.75rem;">
                                                @foreach($history->metadata as $k => $v)
                                                    <div><strong>{{ $k }}:</strong> {{ is_array($v) ? json_encode($v) : $v }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <!-- Fallback to legacy ActivityLog if no ClientHistory yet -->
                                @forelse($historicEvents as $log)
                                    <div class="position-relative mb-4">
                                        <div class="position-absolute rounded-circle bg-secondary" style="width: 12px; height: 12px; left: -25px; top: 4px;"></div>
                                        <div class="card card-custom p-3 bg-white border">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="badge bg-light text-dark border font-mono small">{{ $log->action }}</span>
                                                <span class="small text-muted font-mono" style="font-size: 0.72rem;">{{ $log->created_at?->diffForHumans() }}</span>
                                            </div>
                                            <div class="small text-dark">{{ $log->user?->name ?? 'System' }}</div>
                                            @if($log->details)
                                                <div class="mt-1 small text-muted font-mono bg-light p-2 rounded">
                                                    {{ is_array($log->details) ? json_encode($log->details) : $log->details }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted small">
                                        <i class="bi bi-clock-history fs-3 d-block mb-1 opacity-50"></i>
                                        No timeline events recorded yet.
                                    </div>
                                @endforelse
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function generateClientPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const input = document.getElementById('adminClientPasswordInput');
        if (input) {
            input.value = password;
        }
    }

    function copyClientCredentials(url, email, password, btn) {
        if (!navigator.clipboard) {
            return;
        }
        const text = `Portal Login: ${url}\nEmail: ${email}\nPassword: ${password}`;
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2000);
        });
    }
</script>
@endpush
