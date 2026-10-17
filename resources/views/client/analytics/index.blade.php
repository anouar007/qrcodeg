@extends('layouts.client')

@section('title', 'My Scan Analytics')
@section('page_title', 'Scan Analytics')
@section('page_subtitle', 'Measure physical audience engagement across your dynamic QR codes')

@section('content')
<!-- Filter Bar -->
<div class="card card-custom p-4 bg-white mb-4">
    <form method="GET" action="{{ route('client.analytics') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Filter by QR Code</label>
            <select name="qr_code_id" class="form-select form-select-sm">
                <option value="">All My QR Codes</option>
                @foreach($myQrs as $qr)
                    <option value="{{ $qr->id }}" {{ $validQrId == $qr->id ? 'selected' : '' }}>
                        {{ $qr->name }} ({{ $qr->public_code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label small fw-semibold">Date Window</label>
            <select name="days" class="form-select form-select-sm">
                <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                <option value="14" {{ $days == 14 ? 'selected' : '' }}>Last 14 Days</option>
                <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 Days</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end gap-1 mt-auto">
            <button type="submit" class="btn btn-dark btn-sm w-100 py-2">Update</button>
            <a href="{{ route('client.analytics') }}" class="btn btn-outline-secondary btn-sm py-2" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </form>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-6">
        <div class="card card-custom p-3 bg-white text-center">
            <span class="text-muted small fw-semibold">Total Scans</span>
            <div class="fs-2 fw-bold text-dark mt-1">{{ number_format($summary['total_scans']) }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-6">
        <div class="card card-custom p-3 bg-white text-center">
            <span class="text-muted small fw-semibold">Unique Visitors</span>
            <div class="fs-2 fw-bold text-primary mt-1">{{ number_format($summary['unique_scans']) }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-6">
        <div class="card card-custom p-3 bg-white text-center">
            <span class="text-muted small fw-semibold">Today</span>
            <div class="fs-2 fw-bold text-success mt-1">{{ number_format($summary['scans_today']) }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-6">
        <div class="card card-custom p-3 bg-white text-center">
            <span class="text-muted small fw-semibold">This Month</span>
            <div class="fs-2 fw-bold text-info mt-1">{{ number_format($summary['scans_this_month']) }}</div>
        </div>
    </div>
</div>

<!-- Daily Scan Volume Chart -->
<div class="card card-custom p-4 bg-white mb-4">
    <h5 class="fw-bold mb-1">Scan Volume (Last {{ $days }} Days)</h5>
    <small class="text-muted mb-3 d-block">Timeline of customer engagements</small>
    <div style="height: 270px; position: relative;">
        <canvas id="clientScanChart"></canvas>
    </div>
</div>

<!-- Demographic Breakdowns -->
<div class="row g-4 mb-4">
    <!-- Devices -->
    <div class="col-md-4">
        <div class="card card-custom p-4 bg-white h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-phone me-1 text-primary"></i> Device Categories</h6>
            <div style="height: 190px; position: relative;">
                <canvas id="clientDevicesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Operating Systems -->
    <div class="col-md-4">
        <div class="card card-custom p-4 bg-white h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-laptop me-1 text-info"></i> Operating Systems</h6>
            <div style="height: 190px; position: relative;">
                <canvas id="clientOsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Geographic Locations -->
    <div class="col-md-4">
        <div class="card card-custom p-4 bg-white h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-1 text-danger"></i> Geographic Distribution</h6>
            <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                @forelse($summary['countries'] as $c => $cnt)
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-dark fw-semibold">{{ $c ?: 'Direct / Global' }}</span>
                        <span class="badge bg-light text-dark border">{{ number_format($cnt) }}</span>
                    </li>
                @empty
                    <li class="text-muted text-center py-3">No location data captured yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Scan Records -->
<div class="card card-custom p-4 bg-white">
    <h5 class="fw-bold mb-3">Recent Scans</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light text-uppercase tracking-wider" style="font-size: 0.72rem;">
                <tr>
                    <th>Timestamp</th>
                    <th>QR Code</th>
                    <th>Device</th>
                    <th>Operating System</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                    <tr>
                        <td class="text-nowrap">{{ $scan->scanned_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <strong class="text-dark">{{ $scan->qrCode?->name }}</strong>
                            <code class="text-primary d-block" style="font-size: 0.72rem;">{{ $scan->qrCode?->public_code }}</code>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $scan->device_type }}</span></td>
                        <td>{{ $scan->operating_system }} ({{ $scan->browser }})</td>
                        <td>{{ $scan->city ? $scan->city . ', ' : '' }}{{ $scan->country ?: 'Global' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No scan records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $scans->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Line Chart
    const ctx = document.getElementById('clientScanChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($summary['chart_daily']['labels']),
                datasets: [{
                    label: 'Scans',
                    data: @json($summary['chart_daily']['data']),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#4f46e5',
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

    // 2. Devices
    const ctxDev = document.getElementById('clientDevicesChart');
    if (ctxDev) {
        const dData = @json($summary['devices']);
        new Chart(ctxDev, {
            type: 'doughnut',
            data: {
                labels: Object.keys(dData),
                datasets: [{
                    data: Object.values(dData),
                    backgroundColor: ['#6366f1', '#06b6d4', '#f59e0b', '#64748b'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
            }
        });
    }

    // 3. OS
    const ctxOs = document.getElementById('clientOsChart');
    if (ctxOs) {
        const osData = @json($summary['operating_systems']);
        new Chart(ctxOs, {
            type: 'pie',
            data: {
                labels: Object.keys(osData),
                datasets: [{
                    data: Object.values(osData),
                    backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#ef4444', '#f97316'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
            }
        });
    }
});
</script>
@endpush
