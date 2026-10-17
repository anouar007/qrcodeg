@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'System Dashboard')
@section('page_subtitle', 'Real-time overview of clients, dynamic QR codes, and scan metrics')

@section('content')
<!-- Metric Cards Row -->
<div class="row g-3 mb-4">
    <!-- Total Clients -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Clients</span>
                <div class="metric-icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-buildings"></i>
                </div>
            </div>
            <div class="display-6 fw-bold text-dark">{{ number_format($totalClients) }}</div>
            <div class="small text-muted mt-2 d-flex align-items-center gap-1">
                <a href="{{ route('admin.clients.index') }}" class="text-primary text-decoration-none fw-semibold">
                    View all clients <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total QR Codes -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total QR Codes</span>
                <div class="metric-icon-box bg-info-subtle text-info">
                    <i class="bi bi-qr-code"></i>
                </div>
            </div>
            <div class="display-6 fw-bold text-dark">{{ number_format($totalQrs) }}</div>
            <div class="small text-muted mt-2 d-flex align-items-center justify-content-between">
                <span><span class="text-success fw-bold">{{ $activeQrs }}</span> Active</span>
                <span><span class="text-secondary fw-bold">{{ $inactiveQrs }}</span> Inactive</span>
            </div>
        </div>
    </div>

    <!-- Total Scans -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Scans</span>
                <div class="metric-icon-box bg-success-subtle text-success">
                    <i class="bi bi-upc-scan"></i>
                </div>
            </div>
            <div class="display-6 fw-bold text-dark">{{ number_format($totalScans) }}</div>
            <div class="small text-muted mt-2">
                <span class="badge bg-success-subtle text-success">+{{ number_format($scansToday) }} today</span>
                <span class="ms-1">{{ number_format($scansThisMonth) }} this month</span>
            </div>
        </div>
    </div>

    <!-- Staff Members -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Staff & Admins</span>
                <div class="metric-icon-box bg-purple-subtle text-purple" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="display-6 fw-bold text-dark">{{ number_format($totalStaff) }}</div>
            <div class="small text-muted mt-2">
                <a href="{{ route('admin.users.index') }}" class="text-primary text-decoration-none fw-semibold">
                    Manage roles & staff <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Scans Over Time -->
    <div class="col-lg-8">
        <div class="card card-custom p-4 h-100 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0">QR Scans Over Time</h5>
                    <small class="text-muted">Daily scan volume across all dynamic codes (Last 14 Days)</small>
                </div>
                <span class="badge bg-primary-subtle text-primary font-mono">CHART.JS LIVE</span>
            </div>
            <div style="height: 280px; position: relative;">
                <canvas id="scansTimelineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- QR Codes By Type -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 h-100 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0">Codes by Type</h5>
                    <small class="text-muted">Distribution of active formats</small>
                </div>
                <i class="bi bi-pie-chart text-muted"></i>
            </div>
            <div style="height: 240px; position: relative;">
                <canvas id="qrsByTypeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Charts & Analytics Row -->
<div class="row g-4 mb-4">
    <!-- QR Creation Over Time -->
    <div class="col-md-6">
        <div class="card card-custom p-4 bg-white h-100">
            <h5 class="fw-bold mb-1">QR Generation by Month</h5>
            <small class="text-muted mb-3 d-block">Monthly volume of batch and single generations</small>
            <div style="height: 220px; position: relative;">
                <canvas id="qrCreationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Device Statistics -->
    <div class="col-md-6">
        <div class="card card-custom p-4 bg-white h-100">
            <h5 class="fw-bold mb-1">Scanning Device Types</h5>
            <small class="text-muted mb-3 d-block">Hardware breakdown of physical scanner audience</small>
            <div style="height: 220px; position: relative;">
                <canvas id="devicesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Recent Scans Row -->
<div class="row g-4">
    <!-- Recent Activity Trail -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent System Activity</h5>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-link text-decoration-none">View Full Audit Log</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light text-uppercase tracking-wider" style="font-size: 0.72rem;">
                        <tr>
                            <th>Action</th>
                            <th>Resource / User</th>
                            <th>IP</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $log)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark font-mono">{{ $log->action }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $log->user?->name ?? ($log->client?->company_name ?? 'System') }}</span>
                                    @if($log->resource_type)
                                        <small class="text-muted d-block">{{ $log->resource_type }} #{{ $log->resource_id }}</small>
                                    @endif
                                </td>
                                <td class="text-muted font-mono">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                                <td class="text-muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No activity records logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Live Scan Feed</h5>
                <a href="{{ route('admin.analytics.index') }}" class="btn btn-sm btn-link text-decoration-none">Analytics</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light text-uppercase tracking-wider" style="font-size: 0.72rem;">
                        <tr>
                            <th>QR Code</th>
                            <th>Device</th>
                            <th>Location</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentScans as $scan)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-dark d-block text-truncate" style="max-width: 130px;">
                                        {{ $scan->qrCode?->name ?? 'Unknown' }}
                                    </span>
                                    <code class="text-primary" style="font-size: 0.75rem;">{{ $scan->qrCode?->public_code }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $scan->device_type }}</span>
                                </td>
                                <td class="text-muted">{{ $scan->country ?: 'Global' }}</td>
                                <td class="text-muted text-nowrap">{{ $scan->scanned_at?->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No scans recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Scans Timeline Chart (Line Chart)
    const ctxTimeline = document.getElementById('scansTimelineChart');
    if (ctxTimeline) {
        new Chart(ctxTimeline, {
            type: 'line',
            data: {
                labels: @json($dailySummary['chart_daily']['labels']),
                datasets: [{
                    label: 'Daily Scans',
                    data: @json($dailySummary['chart_daily']['data']),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. QR Codes By Type (Doughnut Chart)
    const ctxTypes = document.getElementById('qrsByTypeChart');
    if (ctxTypes) {
        const typeData = @json($qrsByType);
        new Chart(ctxTypes, {
            type: 'doughnut',
            data: {
                labels: Object.keys(typeData),
                datasets: [{
                    data: Object.values(typeData),
                    backgroundColor: ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }

    // 3. QR Creation by Month (Bar Chart)
    const ctxCreation = document.getElementById('qrCreationChart');
    if (ctxCreation) {
        new Chart(ctxCreation, {
            type: 'bar',
            data: {
                labels: @json($qrCreationLabels),
                datasets: [{
                    label: 'QRs Created',
                    data: @json($qrCreationData),
                    backgroundColor: '#0ea5e9',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 4. Devices Chart (Pie Chart)
    const ctxDevices = document.getElementById('devicesChart');
    if (ctxDevices) {
        const devData = @json($devices);
        new Chart(ctxDevices, {
            type: 'pie',
            data: {
                labels: Object.keys(devData),
                datasets: [{
                    data: Object.values(devData),
                    backgroundColor: ['#6366f1', '#14b8a6', '#f97316', '#64748b'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>
@endpush
