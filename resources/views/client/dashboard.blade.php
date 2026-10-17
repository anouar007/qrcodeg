@extends('layouts.client')

@section('title', 'Client Dashboard')
@section('page_title', 'Client Portal')
@section('page_subtitle', 'Real-time overview of your dynamic QR codes and customer engagement')

@section('content')
<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <!-- My QR Codes -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">My QR Codes</span>
                <div class="metric-icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-qr-code"></i>
                </div>
            </div>
            <div class="display-6 fw-bold text-dark">{{ $totalQrs }}</div>
            <div class="small text-muted mt-2 d-flex justify-content-between">
                <span><span class="text-success fw-bold">{{ $activeQrs }}</span> Active</span>
                <a href="{{ route('client.qr-codes.index') }}" class="text-primary text-decoration-none fw-semibold">Manage <i class="bi bi-arrow-right"></i></a>
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

    <!-- Scans This Month -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">This Month</span>
                <div class="metric-icon-box bg-info-subtle text-info">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
            <div class="display-6 fw-bold text-dark">{{ number_format($scansThisMonth) }}</div>
            <div class="small text-muted mt-2">
                <a href="{{ route('client.analytics') }}" class="text-info text-decoration-none fw-semibold">View breakdowns <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Last Scan -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase tracking-wider">Last Scan</span>
                <div class="metric-icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark mt-1 text-truncate">
                {{ $lastScan ? $lastScan->scanned_at->diffForHumans() : 'No scans yet' }}
            </div>
            <div class="small text-muted mt-2">
                {{ $lastScan ? ($lastScan->country ?: 'Direct visitor') : 'Awaiting physical scans' }}
            </div>
        </div>
    </div>
</div>

<!-- Charts & Highlights Row -->
<div class="row g-4 mb-4">
    <!-- Scans Timeline -->
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Scan Activity Trend</h5>
                    <small class="text-muted">Daily interaction volume over the past 14 days</small>
                </div>
                <span class="badge bg-primary-subtle text-primary">REAL-TIME</span>
            </div>
            <div style="height: 260px; position: relative;">
                <canvas id="clientTimelineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Client Quick Access Card -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 bg-white h-100">
            <h5 class="fw-bold mb-1">Dynamic Control</h5>
            <small class="text-muted mb-3 d-block">Instant changes, zero reprinting required</small>

            <div class="p-3 bg-light rounded-3 border mb-3">
                <small class="text-muted d-block" style="font-size: 0.72rem;">YOUR COMPANY</small>
                <strong class="text-dark fs-5">{{ $client->company_name }}</strong>
            </div>

            <p class="text-muted small mb-4">
                Whenever you customize a QR code in this portal, what guests see after scanning updates automatically.
            </p>

            <a href="{{ route('client.qr-codes.index') }}" class="btn btn-primary-custom w-100 py-2 rounded-pill mt-auto">
                <i class="bi bi-sliders me-1"></i> Customize My QR Codes
            </a>
        </div>
    </div>
</div>

<!-- Assigned QR Codes List Preview -->
<div class="card card-custom p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Your QR Inventory</h5>
        <a href="{{ route('client.qr-codes.index') }}" class="btn btn-sm btn-link text-decoration-none">View All QRs</a>
    </div>

    <div class="row g-3">
        @forelse($topQrsWithSvg as $entry)
            @php $qr = $entry['qr']; @endphp
            <div class="col-md-6 col-lg-3">
                <div class="card p-3 border text-center h-100 rounded-4 bg-white shadow-sm position-relative">
                    <div class="p-2 bg-light rounded-3 d-inline-block mx-auto mb-2 border">
                        {!! $entry['svg'] !!}
                    </div>

                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $qr->name }}</h6>
                    <span class="badge-code mb-2 d-inline-block">{{ $qr->public_code }}</span>

                    <div class="small text-muted mb-3">
                        <span class="fw-bold text-primary">{{ number_format($qr->scan_count) }}</span> scans recorded
                    </div>

                    <div class="d-flex gap-1 justify-content-center mt-auto pt-2 border-top">
                        <a href="{{ route('client.qr-codes.customize', $qr) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-pencil me-1"></i> Customize
                        </a>
                        <a href="{{ url('/q/' . $qr->public_code) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill" title="Test Dynamic Link">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4 text-muted">
                No QR codes currently assigned to your account.
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('clientTimelineChart');
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
});
</script>
@endpush
