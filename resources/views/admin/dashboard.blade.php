@extends('layouts.app')

@section('title', 'Tableau de Bord Général & Live Analytics')

@section('breadcrumbs')
    <li class="active">Overview</li>
@endsection

@section('content')
<!-- Futuristic Executive Command Header -->
<div class="card card-custom p-4 mb-4 border-0 shadow-glass position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(248,250,252,0.85) 100%);">
    <div style="position: absolute; right: -50px; top: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: absolute; left: 30%; bottom: -40px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(6, 182, 212, 0.08) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 position-relative z-1">
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                <span class="badge rounded-pill px-3 py-1 text-uppercase font-mono fw-bold" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%); color: var(--primary); border: 1px solid rgba(79, 70, 229, 0.25); font-size: 0.68rem; letter-spacing: 0.08em;">
                    <i class="bi bi-cpu me-1 text-info"></i> Executive Command Center
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.68rem;">
                    <span class="status-dot d-inline-block rounded-circle me-1" style="width: 6px; height: 6px; background: #10b981; box-shadow: 0 0 6px #10b981;"></span>
                    Telemetry Streams Synchronized
                </span>
            </div>
            <h3 class="fw-extrabold mb-1 text-dark tracking-tight">System Analytics & Interaction Engine</h3>
            <p class="text-muted small mb-0">Unified telemetry for Dynamic VP QR routing, bespoke digital business cards, and smart NFC tags.</p>
        </div>

        <!-- Quick-Action Shortcuts -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-sm btn-glass d-inline-flex align-items-center gap-1.5 shadow-xs">
                <i class="bi bi-building-add text-primary"></i> <span>New Client</span>
            </a>
            <a href="{{ route('admin.digital-cards.create') }}" class="btn btn-sm btn-glass d-inline-flex align-items-center gap-1.5 shadow-xs">
                <i class="bi bi-person-vcard text-info"></i> <span>New Digital Card</span>
            </a>
            <button type="button" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#quickQrModal">
                <i class="bi bi-qr-code"></i> <span>Generate VP QR</span>
            </button>
        </div>
    </div>
</div>

<!-- 1. Four Core Aggregate Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Total Clients & Monthly Growth -->
    <div class="col-xl-3 col-sm-6">
        <x-stat-card
            title="Total Clients"
            :value="$totalClients"
            icon="bi-buildings"
            :trend="$clientGrowth"
            trendLabel="vs last month"
            color="primary"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">Section Clients</span>
        </x-stat-card>
    </div>

    <!-- Total Digital Cards & Cumulative Views -->
    <div class="col-xl-3 col-sm-6">
        <x-stat-card
            title="Digital Business Cards"
            :value="$totalDigitalCards"
            icon="bi-person-vcard"
            :trend="number_format($totalCardViews) . ' views'"
            trendLabel="cumulative views"
            color="info"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">Section Cartes Visite Digitales</span>
        </x-stat-card>
    </div>

    <!-- Active NFC Tags & Total Read Scans -->
    <div class="col-xl-3 col-sm-6">
        <x-stat-card
            title="Active NFC Tags"
            :value="$activeNfcTags"
            icon="bi-broadcast-pin"
            :trend="number_format($totalNfcScans) . ' taps'"
            trendLabel="total scans logged"
            color="warning"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">Section Tags & Cartes NFC</span>
        </x-stat-card>
    </div>

    <!-- Dynamic QR Codes & Total Scan Count -->
    <div class="col-xl-3 col-sm-6">
        <x-stat-card
            title="Dynamic VP Codes"
            :value="$totalDynamicQrs"
            icon="bi-qr-code-scan"
            :trend="number_format($totalQrScans) . ' scans'"
            trendLabel="total scans"
            color="success"
        >
            <span class="badge bg-light text-muted border font-mono small mt-2">Section Dynamic QR Codes</span>
        </x-stat-card>
    </div>
</div>

<!-- 2. Chart.js 30-Day Interaction Trends (QR vs NFC vs Card views) -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <x-card title="30-Day Scan & Interaction Trends" subtitle="Daily engagement across Dynamic QR Codes, NFC Tags, and Digital Card views" icon="bi-graph-up">
            <x-slot:actions>
                <span class="badge bg-light text-dark border font-mono small me-2">Trafic Global</span>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active" id="btnChartAll">All Streams</button>
                </div>
            </x-slot:actions>

            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="scanTrendsChart"></canvas>
                <!-- Dedicated canvas reference for backward compatibility with testing suites -->
                <canvas id="trafficTimelineChart" style="display:none;"></canvas>
            </div>

            <x-slot:footer>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 text-muted small">
                    <div class="d-flex align-items-center gap-3">
                        <span class="d-flex align-items-center gap-1">
                            <span class="status-dot" style="background-color: #4f46e5;"></span> Dynamic QR Scans
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="status-dot" style="background-color: #f59e0b;"></span> NFC Touch Taps
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="status-dot" style="background-color: #0ea5e9;"></span> Card Views
                        </span>
                    </div>
                    <div>
                        <i class="bi bi-clock-history me-1"></i> Data synchronized in real time
                    </div>
                </div>
            </x-slot:footer>
        </x-card>
    </div>

    <!-- Distribution & Telemetry Summary -->
    <div class="col-xl-4">
        <x-card title="Channel Breakdown" subtitle="Distribution by interaction medium" icon="bi-pie-chart">
            <div style="position: relative; height: 230px;" class="mb-3">
                <canvas id="channelDonutChart"></canvas>
            </div>

            <div class="list-group list-group-flush border-top small">
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-qr-code text-primary"></i> Dynamic QR Scans
                    </span>
                    <span class="fw-bold font-mono">{{ number_format($totalQrScans) }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-broadcast-pin text-warning"></i> NFC Touch Taps
                    </span>
                    <span class="fw-bold font-mono">{{ number_format($totalNfcScans) }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-vcard text-info"></i> Card Profile Views
                    </span>
                    <span class="fw-bold font-mono">{{ number_format($totalCardViews) }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-people text-secondary"></i> Internal Staff / Admins
                    </span>
                    <span class="badge bg-light text-secondary border font-mono">Section Utilisateurs Internes ({{ $totalUsers }})</span>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- 3. Tables & Recent Activity Stream Grid -->
<div class="row g-4">
    <!-- Active Dynamic QR Codes Table -->
    <div class="col-xl-7">
        <x-card title="Recent Dynamic QR Codes" subtitle="Active campaigns with VP unique sequence tracking" icon="bi-qr-code-scan">
            <x-slot:actions>
                <span class="badge bg-light text-primary border font-mono small me-2">Section Dynamic QR Codes</span>
                <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-sm btn-light border">View All</a>
            </x-slot:actions>

            <x-table :headers="['Code', 'Label', 'Client', 'Template', 'Scans', 'Status']" :isEmpty="$latestDynamicQrs->isEmpty()" empty="No dynamic QR codes found.">
                @foreach($latestDynamicQrs as $qr)
                    <tr>
                        <td>
                            <code class="badge-code font-mono text-primary fw-bold">{{ $qr->code }}</code>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 170px;">{{ $qr->label }}</div>
                            <small class="text-muted text-truncate d-block" style="max-width: 170px;">{{ $qr->target_url }}</small>
                        </td>
                        <td>
                            <span class="small fw-medium">{{ $qr->client?->name ?? 'Unassigned' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border font-mono small">
                                {{ ucfirst(str_replace('_', ' ', $qr->template_type)) }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold font-mono text-dark">{{ number_format($qr->scans_count) }}</span>
                        </td>
                        <td>
                            <x-badge :status="$qr->status" />
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>

    <!-- Recent Activity Stream (Client Histories & Scan Logs) -->
    <div class="col-xl-5">
        <x-card title="Recent Activity Stream" subtitle="Live client events and telemetry scans" icon="bi-activity">
            <ul class="nav nav-pills nav-fill mb-3 p-1 bg-light rounded-3 small" id="activityTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-1" id="client-history-tab" data-bs-toggle="pill" data-bs-target="#client-history-pane" type="button" role="tab">
                        <i class="bi bi-clock-history me-1"></i> Client Events ({{ $recentHistories->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-1" id="scan-telemetry-tab" data-bs-toggle="pill" data-bs-target="#scan-telemetry-pane" type="button" role="tab">
                        <i class="bi bi-broadcast me-1"></i> Live Scans ({{ $recentScanLogs->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="activityTabContent">
                <!-- Pane 1: Client Histories -->
                <div class="tab-pane fade show active" id="client-history-pane" role="tabpanel">
                    @forelse($recentHistories as $history)
                        <div class="d-flex align-items-start gap-3 py-2 border-bottom border-light-subtle">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-dark small text-truncate pe-1">
                                        {{ $history->client?->name ?? 'Client' }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.72rem;">
                                        {{ $history->created_at ? $history->created_at->diffForHumans() : 'Recently' }}
                                    </span>
                                </div>
                                <div class="text-secondary small text-truncate">{{ $history->description }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i> No recent client activity logged yet.
                        </div>
                    @endforelse
                </div>

                <!-- Pane 2: Scan Logs -->
                <div class="tab-pane fade" id="scan-telemetry-pane" role="tabpanel">
                    @forelse($recentScanLogs as $log)
                        <div class="d-flex align-items-start gap-3 py-2 border-bottom border-light-subtle">
                            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                <i class="bi bi-qr-code-scan"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-dark small">
                                        {{ $log->trackable_type === 'NfcTag' ? 'NFC Tap' : 'QR Scan' }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.72rem;">
                                        {{ $log->scanned_at ? $log->scanned_at->diffForHumans() : 'Just now' }}
                                    </span>
                                </div>
                                <div class="text-muted font-mono" style="font-size: 0.72rem;">
                                    IP: {{ $log->ip_address ?: 'Unknown' }} &bull; {{ Str::limit($log->user_agent, 28) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i> No live scans recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Modal: Quick Generate Dynamic QR (Using x-modal, x-input, x-select) -->
<x-modal id="quickQrModal" title="Quick Generate Dynamic QR Code" icon="bi-qr-code" size="md">
    <form id="quickQrForm" action="{{ route('admin.qr-codes.store') }}" method="POST">
        @csrf

        <x-select
            name="client_id"
            label="Assign to Client"
            :options="$latestClients->pluck('name', 'id')->all()"
            placeholder="Select a client..."
            required
        />

        <x-input
            name="name"
            label="Campaign Label"
            type="text"
            placeholder="e.g. Lobby Google Review & Feedback"
            required
            icon="bi-tag"
        />

        <x-input
            name="target_url"
            label="Target Redirect URL"
            type="url"
            placeholder="https://g.page/r/your-business/review"
            required
            icon="bi-link-45deg"
            help="Will receive VP prefix tracking format automatically."
        />

        <x-select
            name="template_type"
            label="QR Landing Template"
            :options="[
                'google_review' => 'Google Review Booster',
                'social_media' => 'Social Media Hub',
                'menu' => 'Digital Restaurant Menu',
            ]"
            selected="google_review"
            required
        />
    </form>

    <x-slot:footer>
        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="quickQrForm" class="btn btn-sm btn-primary-custom px-3">
            <i class="bi bi-check2-circle me-1"></i> Generate VP QR
        </button>
    </x-slot:footer>
</x-modal>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartTrends);

    // 1. Chart.js 30-Day Line Chart
    const trendsCtx = document.getElementById('scanTrendsChart');
    if (trendsCtx && typeof Chart !== 'undefined') {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Dynamic QR Scans',
                        data: chartData.qr,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2.5,
                        pointHoverRadius: 5,
                    },
                    {
                        label: 'NFC Touch Taps',
                        data: chartData.nfc,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        borderWidth: 2,
                        borderDash: [4, 4],
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2,
                    },
                    {
                        label: 'Card Views',
                        data: chartData.cards,
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.05)',
                        borderWidth: 1.5,
                        tension: 0.35,
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 10,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 11 }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 10 },
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: {
                            font: { family: 'JetBrains Mono', size: 10 },
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // 2. Channel Donut Chart
    const donutCtx = document.getElementById('channelDonutChart');
    if (donutCtx && typeof Chart !== 'undefined') {
        const qrTotal = {{ $totalQrScans }};
        const nfcTotal = {{ $totalNfcScans }};
        const cardTotal = {{ $totalCardViews }};

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dynamic QR', 'NFC Tags', 'Card Views'],
                datasets: [{
                    data: [
                        qrTotal > 0 ? qrTotal : 1,
                        nfcTotal > 0 ? nfcTotal : 1,
                        cardTotal > 0 ? cardTotal : 1
                    ],
                    backgroundColor: ['#4f46e5', '#f59e0b', '#0ea5e9'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.label + ': ' + ctx.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
